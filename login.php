    <?php
    // login.php - Página de login do sistema

    require_once 'config/database.php';
    require_once 'includes/functions.php';

    iniciarSessao();

    if (estaLogado()) {
        redirecionarUsuario();
    }

    $erro = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = limparDados($_POST['email']);
        $senha = $_POST['senha'];

        if (empty($email) || empty($senha)) {
            $erro = "Por favor, preencha todos os campos.";
        } else {
            $sql = "SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $usuario = $result->fetch_assoc();

                if (password_verify($senha, $usuario['senha'])) {
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['nome_usuario'] = $usuario['nome'];
                    $_SESSION['email_usuario'] = $usuario['email'];
                    $_SESSION['tipo_usuario'] = $usuario['tipo'];

                    if ($usuario['tipo'] == 'admin') {

                        header("Location: admin/index.php");
                        exit;

                    } else {

                        // BUSCAR ID DA EMPRESA DO CLIENTE
                        $sql_empresa = "SELECT id FROM empresas WHERE usuario_id = ?";

                        $stmt_empresa = $conn->prepare($sql_empresa);

                        $stmt_empresa->bind_param("i", $usuario['id']);

                        $stmt_empresa->execute();

                        $result_empresa = $stmt_empresa->get_result();

                        $empresa = $result_empresa->fetch_assoc();

                        $stmt_empresa->close();

                        if ($empresa) {

                            header("Location: empresa/empresa_informacoes.php?id=" . $empresa['id']);
                            exit;

                        } else {

                            $erro = "Nenhuma empresa encontrada para este utilizador.";
                        }
                    }
                } else {
                    $erro = "Senha incorreta.";
                }
            } else {
                $erro = "Usuário não encontrado.";
            }
        }
    }

    include 'includes/header.php';
    ?>

    <style>
        .login-back-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px 18px;
            margin-top: 14px;
            border-radius: 8px;
            border: 2px solid #1a6ff4;
            color: #1a6ff4;
            background: #ffffff;
            text-decoration: none;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .login-back-btn:hover {
            background: #1458cc;
            color: #ffffff;
            text-decoration: none;
        }
    </style>

    <div class="container login-container">
        <div class="card">
            <div class="card-header text-center">
                <h2><i class="fas fa-lock"></i> Login</h2>
                <p class="mb-0">Introduza as suas credenciais para ter acesso</p>
            </div>

            <div class="card-body p-4">
                <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $erro; ?>
                    </div>
                <?php endif; ?>

                <?php
                $alerta = obterAlerta();
                if ($alerta):
                    ?>
                    <div class="alert alert-<?php echo $alerta['tipo']; ?>">
                        <i class="fas fa-info-circle"></i> <?php echo $alerta['mensagem']; ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i> E-mail:
                        </label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-4">
                        <label for="senha" class="form-label">
                            <i class="fas fa-key"></i> Senha:
                        </label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-sign-in-alt"></i> Entrar
                        </button>
                    </div>
                </form>

                <a href="index.php" class="login-back-btn">
                    <i class="fas fa-arrow-left"></i> Voltar à Página Inicial
                </a>

                <div class="text-center mt-4">
                    <p>Ainda não tem uma conta? <a href="register.php">Registe-se Aqui</a></p>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>