<?php
// register.php - Página de registo de novos usuários

require_once 'config/database.php';
require_once 'includes/functions.php';

iniciarSessao();

$erro = '';

// Processar formulário de registo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome = limparDados($_POST['nome'] ?? '');

    $email = limparDados($_POST['email']);

    $senha = $_POST['senha'];

    $confirmar_senha = $_POST['confirmar_senha'];

    $nome_empresa = limparDados($_POST['nome_empresa']);

    $morada = limparDados($_POST['morada']);

    $codigo_postal = limparDados($_POST['codigo_postal']);

    $telefone = limparDados($_POST['telefone'] ?? '');

    $aceita_politica = isset($_POST['aceita_politica']) ? 1 : 0;

    if (
        empty($email) ||
        empty($senha) ||
        empty($confirmar_senha) ||
        empty($nome_empresa) ||
        empty($morada) ||
        empty($codigo_postal)
    ) {

        $erro = "Por favor, preencha os campos obrigatórios.";

    } elseif ($senha !== $confirmar_senha) {

        $erro = "As senhas não coincidem.";

    } elseif (strlen($senha) < 6) {

        $erro = "A senha deve ter pelo menos 6 caracteres.";

    } elseif (!$aceita_politica) {

        $erro = "Você deve aceitar a Política de Privacidade para se registar.";

    } else {

        $sql = "SELECT id FROM usuarios WHERE email = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $erro = "Já existe um registo com este e-mail.";

        } else {

            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            $conn->begin_transaction();

            try {

                // criar utilizador
                $sql = "INSERT INTO usuarios (nome, email, senha, tipo)
                        VALUES (?, ?, ?, 'cliente')";

                $stmt = $conn->prepare($sql);

                $stmt->bind_param("sss", $nome, $email, $senha_hash);

                $stmt->execute();

                $usuario_id = $conn->insert_id;

                // criar empresa
                $sql = "INSERT INTO empresas
                        (usuario_id, nome_empresa, morada, codigo_postal, telefone)
                        VALUES (?, ?, ?, ?, ?)";

                $stmt = $conn->prepare($sql);

                $stmt->bind_param(
                    "issss",
                    $usuario_id,
                    $nome_empresa,
                    $morada,
                    $codigo_postal,
                    $telefone
                );

                $stmt->execute();

                $conn->commit();

                mostrarAlerta(
                    "Registo realizado com sucesso. Faça login para continuar.",
                    "success"
                );

                header("Location: login.php");

                exit;

            } catch (Exception $e) {

                $conn->rollback();

                $erro = "Erro ao registrar: " . $e->getMessage();
            }
        }
    }
}

include 'includes/header.php';
?>

<!-- FLAGS TELEFONE -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.2.1/build/css/intlTelInput.css" />

<style>
    .register-back-btn {

        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;

        width: 100%;

        padding: 12px 18px;

        margin-top: 14px;

        border-radius: 8px;

        border: 2px solid #315f9b;

        color: #315f9b;

        background: #ffffff;

        text-decoration: none;

        font-weight: 600;

        transition: 0.2s ease;
    }

    .register-back-btn:hover {

        background: #315f9b;

        color: #ffffff;

        text-decoration: none;
    }

    .iti {

        width: 100%;
    }
</style>

<div class="container login-container">

    <div class="card">

        <div class="card-header text-center">

            <h2>
                <i class="fas fa-user-plus"></i>
                Registo de Cliente
            </h2>

            <p class="mb-0">
                Crie sua conta para aceder
            </p>

        </div>

        <div class="card-body p-4">

            <?php if (!empty($erro)): ?>

                <div class="alert alert-danger">

                    <i class="fas fa-exclamation-circle"></i>

                    <?php echo $erro; ?>

                </div>

            <?php endif; ?>

            <form method="post" action="">

                <!-- EMPRESA -->
                <div class="mb-3">

                    <label for="nome_empresa" class="form-label">

                        <i class="fas fa-building"></i>

                        Nome da Empresa:

                    </label>

                    <input type="text" class="form-control" id="nome_empresa" name="nome_empresa" required>

                </div>

                <!-- MORADA -->
                <div class="mb-3">

                    <label for="morada" class="form-label">

                        <i class="fas fa-map-marker-alt"></i>

                        Morada:

                    </label>

                    <input type="text" class="form-control" id="morada" name="morada" required>

                </div>

                <!-- CODIGO POSTAL -->
                <div class="mb-3">

                    <label for="codigo_postal" class="form-label">

                        <i class="fas fa-mail-bulk"></i>

                        Código Postal:

                    </label>

                    <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" required>

                </div>

                <!-- EMAIL -->
                <div class="mb-3">

                    <label for="email" class="form-label">

                        <i class="fas fa-envelope"></i>

                        E-mail do LOGIN/empresa:

                    </label>

                    <input type="email" class="form-control" id="email" name="email" required>

                </div>

                <!-- CONTACTO -->
                <div class="mb-3">

                    <label for="nome" class="form-label">

                        <i class="fas fa-user"></i>

                        Nome Contacto:

                    </label>

                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Opcional">

                </div>

                <!-- TELEFONE -->
                <div class="mb-3">

                    <label for="telefone" class="form-label">

                        <i class="fas fa-phone"></i>

                        Telefone:

                    </label>

                    <input type="tel" id="telefone" name="telefone" class="form-control" placeholder="Opcional">

                </div>

                <!-- SENHA -->
                <!-- SENHA -->
                <div class="mb-3">

                    <label for="senha" class="form-label">

                        <i class="fas fa-lock"></i>

                        Senha:

                    </label>

                    <div class="input-group">

                        <input type="password" class="form-control" id="senha" name="senha" required>

                        <button type="button" class="btn btn-outline-secondary" onclick="toggleSenha('senha', this)">

                            <i class="fas fa-eye"></i>

                        </button>

                    </div>

                    <small class="form-text text-muted">

                        A senha deve ter pelo menos 6 caracteres.

                    </small>

                </div>

                <!-- CONFIRMAR -->
                <div class="mb-3">

                    <label for="confirmar_senha" class="form-label">

                        <i class="fas fa-check"></i>

                        Confirmar Senha:

                    </label>

                    <div class="input-group">

                        <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha"
                            required>

                        <button type="button" class="btn btn-outline-secondary"
                            onclick="toggleSenha('confirmar_senha', this)">

                            <i class="fas fa-eye"></i>

                        </button>

                    </div>

                </div>

                <!-- POLITICA -->
                <div class="mb-4 form-check">

                    <input type="checkbox" class="form-check-input" id="aceita_politica" name="aceita_politica"
                        required>

                    <label class="form-check-label" for="aceita_politica">

                        Aceito que as minhas informações sejam processadas conforme descrito na

                        <a href="politica_privacidade.php" target="_blank">

                            Política de Privacidade

                        </a>

                    </label>

                </div>

                <!-- BOTAO -->
                <div class="d-grid gap-2">

                    <button type="submit" class="btn btn-success btn-lg">

                        <i class="fas fa-user-plus"></i>

                        Registar

                    </button>

                </div>

            </form>

            <a href="index.php" class="register-back-btn">

                <i class="fas fa-arrow-left"></i>

                Voltar à Página Inicial

            </a>

            <div class="text-center mt-4">

                <p>

                    Já tem uma conta?

                    <a href="login.php">Faça login</a>

                </p>

            </div>

        </div>

    </div>

</div>

<!-- FLAGS -->
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.2.1/build/js/intlTelInput.min.js"></script>

<script>

    const input = document.querySelector("#telefone");

    window.intlTelInput(input, {

        initialCountry: "pt",

        preferredCountries: ["pt", "br", "es", "fr", "gb", "us"],

        separateDialCode: true,

        utilsScript:
            "https://cdn.jsdelivr.net/npm/intl-tel-input@25.2.1/build/js/utils.js"

    });

</script>
<script>

    function toggleSenha(id, button) {

        const input = document.getElementById(id);

        const icon = button.querySelector('i');

        if (input.type === "password") {

            input.type = "text";

            icon.classList.remove("fa-eye");

            icon.classList.add("fa-eye-slash");

        } else {

            input.type = "password";

            icon.classList.remove("fa-eye-slash");

            icon.classList.add("fa-eye");

        }
    }

</script>
<?php include 'includes/footer.php'; ?>