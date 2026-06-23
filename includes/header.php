<?php
// includes/header.php - Cabeçalho comum para todas as páginas

// Iniciar a sessão se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'FreeBox'; ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        body {
            background-color:rgb(134, 194, 243);
        }
        .login-container {
            max-width: 450px;
            margin: 100px auto;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #356096;
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
        }
        .btn-primary {
            background-color: #356096;
            border-color: #356096;
        }
        .btn-primary:hover {
            background-color: #356096;
            border-color: #356096;
        }
            /* PALETA UNIFICADA DOS BOTOES */
        .btn-success {
            background-color: #16a34a !important;
            border-color: #16a34a !important;
            color: #fff !important;
        }
        .btn-success:hover {
            background-color: #15803d !important;
            border-color: #15803d !important;
            color: #fff !important;
        }
        .btn-danger {
            background-color: #dc2626 !important;
            border-color: #dc2626 !important;
            color: #fff !important;
        }
        .btn-danger:hover {
            background-color: #b91c1c !important;
            border-color: #b91c1c !important;
            color: #fff !important;
        }
        .btn-secondary {
            background-color: #f7f9fc !important;
            border-color: #e4eaf2 !important;
            color: #1a2332 !important;
        }
    </style>
</head>
<body>
    <!-- Conteúdo principal começará aqui -->