<?php
// includes/functions.php - Funções auxiliares para o sistema de login

// Inicia a sessão se ainda não estiver iniciada
function iniciarSessao() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

// Verifica se o usuário está logado
function estaLogado() {
    iniciarSessao();
    return isset($_SESSION['usuario_id']);
}

// Verifica se o usuário é administrador
function eAdmin() {
    iniciarSessao();
    return isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'admin';
}

// Verifica se o usuário é cliente
function eCliente() {
    iniciarSessao();
    return isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'cliente';
}

// Redireciona para página adequada conforme o tipo de usuário
function redirecionarUsuario() {
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
        header("Location: empresa/index.php");
        exit;
    }
}

// Limpa e valida dados de entrada
function limparDados($dados) {
    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados);
    return $dados;
}

// Exibe mensagens de alerta/erro
function mostrarAlerta($mensagem, $tipo = 'success') {
    $_SESSION['alerta'] = [
        'mensagem' => $mensagem,
        'tipo' => $tipo
    ];
}

// Recupera e limpa alertas da sessão
function obterAlerta() {
    iniciarSessao();
    if (isset($_SESSION['alerta'])) {
        $alerta = $_SESSION['alerta'];
        unset($_SESSION['alerta']);
        return $alerta;
    }
    return null;
}

// Retorna a URL pública de uma empresa
function obterUrlEmpresa($url_site, $root_prefix = '../') {
    if (empty($url_site)) {
        return '#';
    }
    // Verifica se a pasta física existe no mesmo nível de "freebox"
    $caminho_relativo = dirname(__DIR__) . '/' . $url_site;
    if (is_dir($caminho_relativo)) {
        return $root_prefix . $url_site . '/';
    }
    
    // Fallback para o modo query string se a pasta ainda não existir
    return $root_prefix . 'freebox/?url=' . $url_site;
}

// Copia um diretório e todo o seu conteúdo recursivamente com personalização de .htaccess
function copiarDiretorioRecursivo($origem, $destino) {
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
                    $htaccess_content = "RewriteEngine On\n\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteCond %{REQUEST_FILENAME} !-d\n\n# Politica de privacidade\nRewriteRule ^politica-privacidade$ politica_privacidade.php [L,QSA]\n";
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

// Elimina um diretório e todo o seu conteúdo recursivamente de forma segura
function eliminarDiretorio($dir) {
    if (!is_dir($dir)) {
        return false;
    }
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        $path = $dir . '/' . $file;
        (is_dir($path)) ? eliminarDiretorio($path) : unlink($path);
    }
    return rmdir($dir);
}
?>