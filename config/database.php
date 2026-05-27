<?php
// Configurações da Base de Dados - WAMPServer
$host = 'localhost'; // No servidor, isto pode ser um IP ou 'localhost'
$db = 'siteinstitucional'; // O nome da base de dados que no wampserver
$user = 'root'; // O utilizador que definiu na base de dados do wanpserver
$pass = ''; // A senha que definiu na base de dados do wanpserver

// Configurações da Base de Dados - freebox.is4.pt
//$host = 'localhost'; // No servidor, isto pode ser um IP ou 'localhost'
//$db   = 'is4pt_siteinstitucional'; // O nome da base de dados que criou no servidor
//$user = 'is4pt_siteinstitucional'; // O utilizador da BD no servidor
//$pass = 'is4pt_siteinstitucional'; // A senha que definiu no cPanel

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli($host, $user, $pass, $db);
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Erro na ligação à base de dados: " . $e->getMessage());
}