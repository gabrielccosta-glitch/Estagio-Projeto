<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

// Só aceita POST de utilizadores autenticados
if (!estaLogado()) {
    echo json_encode(['success' => false, 'error' => 'Não autenticado.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método inválido.']);
    exit;
}

// Recolhe e sanitiza os dados
$nome     = trim(strip_tags($_POST['nome']     ?? ''));
$email    = trim(strip_tags($_POST['email']    ?? ''));
$assunto  = trim(strip_tags($_POST['assunto']  ?? ''));
$mensagem = trim(strip_tags($_POST['mensagem'] ?? ''));

if (!$nome || !$email || !$assunto || !$mensagem) {
    echo json_encode(['success' => false, 'error' => 'Campos obrigatórios em falta.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'error' => 'Email inválido.']);
    exit;
}

// ----------------------------------------------------------------
// Envia email para suporte@freebox.pt
// ----------------------------------------------------------------

$para    = 'suporte@freebox.pt';
$assunto_email = '[Freebox Suporte] ' . $assunto . ' — ' . $nome;

$corpo = "
=== NOVO PEDIDO DE SUPORTE ===

Nome:     {$nome}
Email:    {$email}
Assunto:  {$assunto}

Mensagem:
{$mensagem}

==============================
Enviado pelo painel Freebox
";

$headers  = "From: noreply@freebox.pt\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

$enviado = mail($para, $assunto_email, $corpo, $headers);

if ($enviado) {
    // Guarda o pedido na base de dados (tabela opcional — cria se quiseres)
    // Podes comentar este bloco se não tiveres a tabela ainda
    /*
    $usuario_id = $_SESSION['usuario_id'] ?? null;
    $stmt = $conn->prepare("
        INSERT INTO suporte_pedidos (usuario_id, nome, email, assunto, mensagem, criado_em)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    $stmt->bind_param("issss", $usuario_id, $nome, $email, $assunto, $mensagem);
    $stmt->execute();
    $stmt->close();
    */

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Falha ao enviar email.']);
}