<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!eAdmin()) {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID de empresa invalido.";
    header("Location: dashboard.php");
    exit;
}

function limparNomeBackup($nome)
{
    $nome = mb_strtolower((string) $nome, 'UTF-8');
    $nome = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $nome);
    $nome = preg_replace('/[^a-z0-9\-]+/', '-', $nome);
    $nome = trim($nome, '-');

    return $nome !== '' ? $nome : 'empresa';
}

function gerarNomePastaBackup($nome)
{
    $nome = mb_strtolower((string) $nome, 'UTF-8');
    $nome = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $nome);
    $nome = preg_replace('/[^a-z0-9]+/', '-', $nome);
    $nome = trim($nome, '-');

    return $nome !== '' ? $nome : 'empresa';
}

function caminhoDentroDaRaiz($caminho, $raiz)
{
    $realCaminho = realpath($caminho);
    $realRaiz = realpath($raiz);

    if ($realCaminho === false || $realRaiz === false) {
        return false;
    }

    $realCaminho = strtolower(rtrim($realCaminho, DIRECTORY_SEPARATOR));
    $realRaiz = strtolower(rtrim($realRaiz, DIRECTORY_SEPARATOR));

    return $realCaminho !== $realRaiz
        && strpos($realCaminho, $realRaiz . DIRECTORY_SEPARATOR) === 0;
}

function criarPastaBackup($projetoRoot, $urlSite, $empresaId)
{
    $base = $projetoRoot . DIRECTORY_SEPARATOR . 'backup' . DIRECTORY_SEPARATOR . 'empresas_eliminadas';

    if (!is_dir($base) && !mkdir($base, 0755, true)) {
        throw new Exception("Nao foi possivel criar a pasta de backup.");
    }

    $nome = limparNomeBackup($urlSite ?: 'empresa') . '_id' . $empresaId . '_' . date('Ymd_His');
    $destino = $base . DIRECTORY_SEPARATOR . $nome;
    $tentativa = 1;

    while (file_exists($destino)) {
        $destino = $base . DIRECTORY_SEPARATOR . $nome . '_' . $tentativa;
        $tentativa++;
    }

    if (!mkdir($destino, 0755, true)) {
        throw new Exception("Nao foi possivel criar a pasta de backup da empresa.");
    }

    return $destino;
}

function moverPastaParaBackup($origem, $backupDir, $nomeDestino, $raizPermitida, &$movidos)
{
    if (!is_dir($origem)) {
        return;
    }

    if (!caminhoDentroDaRaiz($origem, $raizPermitida)) {
        throw new Exception("Caminho de ficheiros fora da zona permitida.");
    }

    $realOrigem = realpath($origem);
    $nomeDestino = limparNomeBackup($nomeDestino);
    $destino = $backupDir . DIRECTORY_SEPARATOR . $nomeDestino;
    $tentativa = 1;

    while (file_exists($destino)) {
        $destino = $backupDir . DIRECTORY_SEPARATOR . $nomeDestino . '_' . $tentativa;
        $tentativa++;
    }

    if (!rename($realOrigem, $destino)) {
        throw new Exception("Nao foi possivel mover " . basename($realOrigem) . " para backup.");
    }

    $movidos[] = $destino;
}

function apagarTabelaEmpresa($conn, $tabela, $empresaId)
{
    $tabelaEscapada = $conn->real_escape_string($tabela);
    $existe = $conn->query("SHOW TABLES LIKE '{$tabelaEscapada}'");

    if (!$existe || $existe->num_rows === 0) {
        return;
    }

    $stmt = $conn->prepare("DELETE FROM `{$tabela}` WHERE empresa_id = ?");
    $stmt->bind_param("i", $empresaId);
    $stmt->execute();
    $stmt->close();
}

$empresaId = (int) $_GET['id'];
$transacaoAtiva = false;
$baseDadosEliminada = false;
$empresa = null;

try {
    $stmt = $conn->prepare("
        SELECT e.id, e.usuario_id, e.nome_empresa, wc.url_site
        FROM empresas e
        LEFT JOIN website_config wc ON wc.empresa_id = e.id
        WHERE e.id = ?
        LIMIT 1
    ");
    $stmt->bind_param("i", $empresaId);
    $stmt->execute();
    $empresa = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$empresa) {
        throw new Exception("Empresa nao encontrada.");
    }

    $usuarioId = (int) $empresa['usuario_id'];
    $nomeEmpresa = $empresa['nome_empresa'] ?? 'empresa';
    $urlSite = trim($empresa['url_site'] ?? '');

    $conn->begin_transaction();
    $transacaoAtiva = true;

    apagarTabelaEmpresa($conn, 'portfolio', $empresaId);
    apagarTabelaEmpresa($conn, 'servicos', $empresaId);
    apagarTabelaEmpresa($conn, 'website', $empresaId);
    apagarTabelaEmpresa($conn, 'website_config', $empresaId);

    $stmt = $conn->prepare("DELETE FROM empresas WHERE id = ?");
    $stmt->bind_param("i", $empresaId);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM empresas WHERE usuario_id = ?");
    $stmt->bind_param("i", $usuarioId);
    $stmt->execute();
    $totalEmpresasUsuario = (int) $stmt->get_result()->fetch_assoc()['total'];
    $stmt->close();

    if ($totalEmpresasUsuario === 0) {
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ? AND tipo = 'cliente'");
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $stmt->close();
    }

    $conn->commit();
    $transacaoAtiva = false;
    $baseDadosEliminada = true;

    $movidos = [];
    $backupRelativo = '';
    $projetoRoot = realpath(dirname(__DIR__));

    if ($projetoRoot !== false) {
        $backupDir = criarPastaBackup($projetoRoot, $urlSite, $empresaId);

        if ($urlSite !== '' && !in_array(strtolower($urlSite), ['freebox', 'template'], true) && preg_match('/^[a-z0-9\-]+$/i', $urlSite)) {
            moverPastaParaBackup(
                $projetoRoot . DIRECTORY_SEPARATOR . $urlSite,
                $backupDir,
                'site-' . $urlSite,
                $projetoRoot,
                $movidos
            );
        }

        $nomePasta = gerarNomePastaBackup($nomeEmpresa);
        $pastasImagens = [];

        foreach (['Imagens', 'imagens'] as $pastaBase) {
            foreach ([$nomePasta, $urlSite] as $subPasta) {
                if ($subPasta === '') {
                    continue;
                }

                $caminho = $projetoRoot . DIRECTORY_SEPARATOR . $pastaBase . DIRECTORY_SEPARATOR . $subPasta;
                $real = realpath($caminho);

                if ($real !== false && !isset($pastasImagens[strtolower($real)])) {
                    $pastasImagens[strtolower($real)] = $real;
                }
            }
        }

        foreach ($pastasImagens as $caminho) {
            moverPastaParaBackup(
                $caminho,
                $backupDir,
                'imagens-' . basename($caminho),
                $projetoRoot,
                $movidos
            );
        }

        $wwwRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? dirname($projetoRoot));

        if ($wwwRoot !== false) {
            moverPastaParaBackup(
                $wwwRoot . DIRECTORY_SEPARATOR . 'imagens' . DIRECTORY_SEPARATOR . $empresaId,
                $backupDir,
                'portfolio-imagens-' . $empresaId,
                $wwwRoot,
                $movidos
            );
        }

        if (empty($movidos)) {
            @rmdir($backupDir);
        } else {
            $backupRelativo = 'backup/empresas_eliminadas/' . basename($backupDir);
        }
    }

    $_SESSION['success'] = "Empresa eliminada da base de dados.";

    if ($backupRelativo !== '') {
        $_SESSION['success'] .= " Ficheiros guardados em: " . $backupRelativo . ".";
    } else {
        $_SESSION['success'] .= " Nao havia pastas de ficheiros para mover.";
    }
} catch (Exception $e) {
    if ($transacaoAtiva) {
        $conn->rollback();
    }

    if ($baseDadosEliminada) {
        $_SESSION['error'] = "Empresa eliminada da base de dados, mas houve erro ao mover ficheiros para backup: " . $e->getMessage();
    } else {
        $_SESSION['error'] = "Erro ao eliminar empresa: " . $e->getMessage();
    }
}

$conn->close();

header("Location: dashboard.php");
exit;
