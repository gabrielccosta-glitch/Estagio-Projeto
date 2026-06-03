<?php
// includes/functions.php

function iniciarSessao()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

function estaLogado()
{
    iniciarSessao();
    return isset($_SESSION['usuario_id']);
}

function eAdmin()
{
    iniciarSessao();
    return isset($_SESSION['tipo_usuario']) &&
           $_SESSION['tipo_usuario'] == 'admin';
}

function eCliente()
{
    iniciarSessao();
    return isset($_SESSION['tipo_usuario']) &&
           $_SESSION['tipo_usuario'] == 'cliente';
}

function redirecionarUsuario()
{
    iniciarSessao();

    if (!estaLogado()) {

        header("Location: login.php");
        exit;
    }

    if (eAdmin()) {

        header("Location: admin/index.php");
        exit;
    }

    if (eCliente()) {

        global $conn;

        $usuario_id = $_SESSION['usuario_id'];

        $sql = "SELECT id FROM empresas WHERE usuario_id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("i", $usuario_id);

        $stmt->execute();

        $result = $stmt->get_result();

        $empresa = $result->fetch_assoc();

        $stmt->close();

        if ($empresa) {

            header("Location: empresa/empresa_informacoes.php?id=" . $empresa['id']);
            exit;

        } else {

            session_destroy();

            header("Location: login.php");
            exit;
        }
    }
}

// ESTA FUNÇÃO FALTAVA
function limparDados($dados)
{
    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados);
    return $dados;
}

function mostrarAlerta($mensagem, $tipo = 'success')
{
    $_SESSION['alerta'] = [
        'mensagem' => $mensagem,
        'tipo' => $tipo
    ];
}

function obterAlerta()
{
    iniciarSessao();

    if (isset($_SESSION['alerta'])) {

        $alerta = $_SESSION['alerta'];

        unset($_SESSION['alerta']);

        return $alerta;
    }

    return null;
}

function obterUrlEmpresa($url_site, $root_prefix = '../')
{
    if (empty($url_site)) {
        return '#';
    }

    $caminho_relativo = dirname(__DIR__) . '/' . $url_site;

    if (is_dir($caminho_relativo)) {

        return $root_prefix . $url_site . '/';
    }

    return $root_prefix . 'template/?url=' . $url_site;
}

function copiarDiretorioRecursivo($origem, $destino)
{
    if (!is_dir($destino)) {
        mkdir($destino, 0755, true);
    }

    $dir = opendir($origem);

    if ($dir === false) {
        return false;
    }

    while (($file = readdir($dir)) !== false) {

        if ($file !== '.' && $file !== '..') {

            $srcFile = $origem . '/' . $file;

            $destFile = $destino . '/' . $file;

            if (is_dir($srcFile)) {

                copiarDiretorioRecursivo($srcFile, $destFile);

            } else {

                if ($file === '.htaccess') {

                    $htaccess_content = "RewriteEngine On\n\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteCond %{REQUEST_FILENAME} !-d\n\nRewriteRule ^politica-privacidade$ politica_privacidade.php [L,QSA]\n";

                    file_put_contents($destFile, $htaccess_content);

                } else {

                    copy($srcFile, $destFile);
                }
            }
        }
    }

    closedir($dir);

    return true;
}

function eliminarDiretorio($dir)
{
    if (!is_dir($dir)) {
        return false;
    }

    $files = array_diff(scandir($dir), array('.', '..'));

    foreach ($files as $file) {

        $path = $dir . '/' . $file;

        (is_dir($path))
            ? eliminarDiretorio($path)
            : unlink($path);
    }

    return rmdir($dir);
}
?>