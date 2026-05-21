<?php
session_start();

require_once '../config/database.php';
require_once '../includes/functions.php';

if (!estaLogado()) {
    header("Location: ../login.php");
    exit;
}

if (!eCliente()) {
    header("Location: ../admin/dashboard.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$sql = "SELECT * FROM empresas WHERE usuario_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $usuario_id);

$stmt->execute();

$result = $stmt->get_result();

$empresa = $result->fetch_assoc();

$stmt->close();

if (!$empresa) {

    $_SESSION['error_message'] =
        "Não foi encontrada nenhuma empresa associada à sua conta.";

    header("Location: ../login.php");

    exit;
}

$empresa_id = $empresa['id'];

/*
|--------------------------------------------------------------------------
| WEBSITE URL
|--------------------------------------------------------------------------
*/

$url_site = '';

$url_stmt = $conn->prepare("
    SELECT url_site
    FROM website_config
    WHERE empresa_id = ?
");

$url_stmt->bind_param("i", $empresa_id);

$url_stmt->execute();

$url_result = $url_stmt->get_result();

$url_data = $url_result->fetch_assoc();

if ($url_data && !empty($url_data['url_site'])) {
    $url_site = trim($url_data['url_site']);
}

$url_stmt->close();

include '../includes/header.php';
?>

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<link rel="stylesheet"
      href="../css/empresa_dashboard.css">

<!-- HEADER CLIENTE -->
<?php include __DIR__ . '/header_cliente.php'; ?>

<!-- CONTEÚDO -->
<div class="container-fluid mt-4">

    <div class="row justify-content-center">

        <div class="col-md-10 col-lg-8">

            <div class="card dashboard-main-card">

                <div class="card-body">

                    <h3 class="mb-1">
                        <i class="fas fa-house me-2"></i>
                        Freebox
                    </h3>

                    <p class="text-muted mb-4">
                        Plataforma de criação e gestão de websites empresariais.
                    </p>

                    <!-- DIVISOR -->
                    <div class="dashboard-divider mb-3"></div>

                    <div class="row g-0">

                        <!-- INFORMAÇÕES -->
                        <div class="col-md-6">

                            <a href="empresa_informacoes.php?id=<?= $empresa_id; ?>"
                               class="dashboard-card">

                                <div class="icon-box icon-info">
                                    <i class="fas fa-circle-info"></i>
                                </div>

                                <div class="dashboard-card-text">
                                    <h5>Informações</h5>
                                    <p>Editar dados gerais da empresa.</p>
                                </div>

                            </a>

                        </div>

                        <!-- SERVIÇOS -->
                        <div class="col-md-6">

                            <a href="empresa_servicos.php?id=<?= $empresa_id; ?>"
                               class="dashboard-card">

                                <div class="icon-box icon-servicos">
                                    <i class="fas fa-handshake"></i>
                                </div>

                                <div class="dashboard-card-text">
                                    <h5>Serviços</h5>
                                    <p>Adicionar ou editar serviços.</p>
                                </div>

                            </a>

                        </div>

                        <!-- PORTFÓLIO -->
                        <div class="col-md-6">

                            <a href="empresa_portfolio.php?id=<?= $empresa_id; ?>"
                               class="dashboard-card">

                                <div class="icon-box icon-portfolio">
                                    <i class="fas fa-images"></i>
                                </div>

                                <div class="dashboard-card-text">
                                    <h5>Portfólio</h5>
                                    <p>Gerir imagens e trabalhos.</p>
                                </div>

                            </a>

                        </div>

                        <!-- WEBSITE -->
                        <div class="col-md-6">

                            <a href="empresa_website.php?id=<?= $empresa_id; ?>"
                               class="dashboard-card">

                                <div class="icon-box icon-website">
                                    <i class="fas fa-globe"></i>
                                </div>

                                <div class="dashboard-card-text">
                                    <h5>Website</h5>
                                    <p>Configurar capa, logo e redes sociais.</p>
                                </div>

                            </a>

                        </div>

                        <!-- DIVISOR -->
                        <div class="col-12">
                            <div class="dashboard-divider my-1"></div>
                        </div>

                        <!-- VER WEBSITE -->
                        <div class="col-md-6">

                            <?php if (!empty($url_site)): ?>

                                <a href="../freebox/<?= htmlspecialchars($url_site); ?>"
                                   target="_blank"
                                   class="dashboard-card">

                                    <div class="icon-box icon-ver-website">
                                        <i class="fas fa-eye"></i>
                                    </div>

                                    <div class="dashboard-card-text">
                                        <h5>Ver Website</h5>
                                        <p>Abrir o site público da empresa.</p>
                                    </div>

                                </a>

                            <?php else: ?>

                                <a class="dashboard-card dashboard-card-disabled">

                                    <div class="icon-box icon-ver-website">
                                        <i class="fas fa-eye-slash"></i>
                                    </div>

                                    <div class="dashboard-card-text">
                                        <h5>Ver Website</h5>
                                        <p>Defina o endereço primeiro.</p>
                                    </div>

                                </a>

                            <?php endif; ?>

                        </div>

                        <!-- EDITAR CONTA -->
                        <div class="col-md-6">

                            <a href="editar_conta.php"
                               class="dashboard-card">

                                <div class="icon-box icon-conta">
                                    <i class="fas fa-user-gear"></i>
                                </div>

                                <div class="dashboard-card-text">
                                    <h5>Editar Conta</h5>
                                    <p>Alterar nome, email e palavra-passe.</p>
                                </div>

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- FOOTER CLIENTE -->
<?php include __DIR__ . '/footer_cliente.php'; ?>

<?php include '../includes/footer.php'; ?>