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

function guardarImagemWebp($ficheiro_temporario, $destino_webp, $mime_type = null, $qualidade = 82)
{
    if (!extension_loaded('gd') || !function_exists('imagewebp')) {
        return false;
    }

    if (empty($ficheiro_temporario) || !is_file($ficheiro_temporario)) {
        return false;
    }

    $mime_type = $mime_type ?: mime_content_type($ficheiro_temporario);
    $imagem = false;

    switch ($mime_type) {
        case 'image/jpeg':
        case 'image/pjpeg':
            $imagem = @imagecreatefromjpeg($ficheiro_temporario);
            break;

        case 'image/png':
            $imagem = @imagecreatefrompng($ficheiro_temporario);
            if ($imagem && function_exists('imagepalettetotruecolor')) {
                imagepalettetotruecolor($imagem);
            }
            if ($imagem) {
                imagealphablending($imagem, true);
                imagesavealpha($imagem, true);
            }
            break;

        case 'image/gif':
            $imagem = @imagecreatefromgif($ficheiro_temporario);
            if ($imagem && function_exists('imagepalettetotruecolor')) {
                imagepalettetotruecolor($imagem);
            }
            break;

        case 'image/webp':
            if (function_exists('imagecreatefromwebp')) {
                $imagem = @imagecreatefromwebp($ficheiro_temporario);
            }
            break;
    }

    if (!$imagem) {
        return false;
    }

    $diretorio = dirname($destino_webp);
    if (!is_dir($diretorio) && !mkdir($diretorio, 0777, true)) {
        imagedestroy($imagem);
        return false;
    }

    $guardado = imagewebp($imagem, $destino_webp, $qualidade);
    imagedestroy($imagem);

    if ($guardado && file_exists($destino_webp)) {
        @chmod($destino_webp, 0644);
        return true;
    }

    return false;
}
?>