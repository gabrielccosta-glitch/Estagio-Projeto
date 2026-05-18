<?php
session_start();

$esta_logado = isset($_SESSION['usuario_id']);
$tipo_usuario = $_SESSION['tipo_usuario'] ?? '';

$dashboard_url = 'login.php';

if ($esta_logado && $tipo_usuario === 'admin') {
    $dashboard_url = 'admin/dashboard.php';
} elseif ($esta_logado && $tipo_usuario === 'cliente') {
    $dashboard_url = 'empresa/dashboard.php';
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreeBox Sites | Criação de Websites Empresariais</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="/projeto/css/home.css">
</head>
<body>

<header class="main-navbar">
    <div class="container navbar-inner">

        <a href="/projeto/" class="brand">
            <img src="/projeto/imagens/Logotipo_freebox.png" alt="FreeBox Sites">
            <span>FreeBox Sites</span>
        </a>

        <nav class="nav-menu">
            <a href="#sobre">Sobre</a>
            <a href="#funcionalidades">Funcionalidades</a>
            <a href="#como-funciona">Como funciona</a>
            <a href="#contacto">Contacto</a>
        </nav>

        <div class="nav-actions">
            <?php if ($esta_logado): ?>
                <a href="<?= htmlspecialchars($dashboard_url); ?>" class="btn btn-outline-main">
                    Dashboard
                </a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-main">
                    Login
                </a>
                <a href="register.php" class="btn btn-main">
                    Criar Site
                </a>
            <?php endif; ?>
        </div>

    </div>
</header>

<section id="inicio" class="hero-section">
    <div class="hero-overlay"></div>

    <div class="container hero-container">
        <div class="hero-content">

            <span class="hero-label">
                <i class="fas fa-wand-magic-sparkles"></i>
                Plataforma de criação automática de websites
            </span>

            <h1>Cria o site da tua empresa de forma simples e profissional</h1>

            <p>
                O FreeBox Sites permite que empresas criem uma presença online
                através de um painel simples, sem precisar de programar.
                Basta preencher os dados, adicionar serviços, imagens e contactos.
            </p>

            <div class="hero-buttons">
                <a href="register.php" class="btn btn-main btn-lg">
                    Criar o meu site
                </a>

                <a href="login.php" class="btn btn-outline-white btn-lg">
                    Já tenho conta
                </a>
            </div>

        </div>
    </div>
</section>

<section id="sobre" class="about-section section-padding">
    <div class="container">

        <div class="section-title-box">
            <span>Sobre nós</span>
            <h2>Um sistema que cria sites para empresas</h2>
            <div class="title-line"></div>
        </div>

        <div class="row align-items-center g-5">

            <div class="col-lg-7">
                <div class="about-text">
                    <div class="about-icon">
                        <i class="fas fa-building"></i>
                    </div>

                    <div>
                        <h3>FreeBox Sites</h3>

                        <p>
                            Este projeto foi desenvolvido para simplificar a criação
                            de websites institucionais. O administrador gere empresas
                            e os clientes conseguem configurar o próprio website através
                            de um painel simples e direto.
                        </p>

                        <p>
                            Cada empresa pode editar as suas informações, serviços,
                            portfólio, logotipo, capa, redes sociais e endereço público.
                            O sistema transforma estes dados num website pronto a visitar.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="about-info-card">
                    <h4>O que o sistema permite?</h4>

                    <ul>
                        <li><i class="fas fa-check"></i> Criar websites empresariais</li>
                        <li><i class="fas fa-check"></i> Gerir conteúdo sem código</li>
                        <li><i class="fas fa-check"></i> Apresentar serviços e portfólio</li>
                        <li><i class="fas fa-check"></i> Ter página de contacto e mapa</li>
                    </ul>
                </div>
            </div>

        </div>

    </div>
</section>

<section id="funcionalidades" class="features-section section-padding">
    <div class="container">

        <div class="section-title-box">
            <span>Funcionalidades</span>
            <h2>O cliente controla o conteúdo do próprio site</h2>
            <div class="title-line"></div>
        </div>

        <div class="features-grid">

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-circle-info"></i>
                </div>
                <h4>Informações</h4>
                <p>Nome, morada, telefone, email e dados principais da empresa.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h4>Serviços</h4>
                <p>Criação e gestão dos serviços apresentados no website público.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-images"></i>
                </div>
                <h4>Portfólio</h4>
                <p>Upload de imagens para mostrar trabalhos, produtos ou projetos.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <h4>Website</h4>
                <p>Configuração de logotipo, capa, descrição, redes sociais e URL.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h4>Contacto</h4>
                <p>Página com contactos, formulário, mapa e informações da empresa.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-language"></i>
                </div>
                <h4>Idiomas</h4>
                <p>Seletor de idioma no site público para tornar o website mais acessível.</p>
            </div>

        </div>

    </div>
</section>

<section id="como-funciona" class="steps-section section-padding">
    <div class="container">

        <div class="section-title-box">
            <span>Como funciona</span>
            <h2>Do registo ao website publicado</h2>
            <div class="title-line"></div>
        </div>

        <div class="steps-grid">

            <div class="step-card">
                <div class="step-number">1</div>
                <h4>Criar conta</h4>
                <p>A empresa faz o registo e passa a ter acesso ao painel de cliente.</p>
            </div>

            <div class="step-card">
                <div class="step-number">2</div>
                <h4>Configurar conteúdo</h4>
                <p>O cliente preenche informações, serviços, portfólio e dados do website.</p>
            </div>

            <div class="step-card">
                <div class="step-number">3</div>
                <h4>Publicar site</h4>
                <p>O sistema apresenta automaticamente o website público com os dados inseridos.</p>
            </div>

        </div>

    </div>
</section>

<section id="contacto" class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Queres criar o site da tua empresa?</h2>
            <p>Começa agora. Cria uma conta, configura os dados e publica o teu website.</p>

            <div class="cta-buttons">
                <a href="register.php" class="btn btn-light btn-lg">
                    Criar o meu site
                </a>

                <a href="login.php" class="btn btn-outline-light btn-lg">
                    Entrar na conta
                </a>
            </div>
        </div>
    </div>
</section>

<footer class="main-footer">
    <div class="container footer-grid">

        <div>
            <h5>FreeBox Sites</h5>
            <p>Projeto de criação automática de websites institucionais.</p>
        </div>

        <div>
            <h5>Páginas</h5>
            <a href="#sobre">Sobre</a>
            <a href="#funcionalidades">Funcionalidades</a>
            <a href="#como-funciona">Como funciona</a>
        </div>

        <div>
            <h5>Acesso</h5>
            <a href="login.php">Login</a>
            <a href="register.php">Criar site</a>
        </div>

    </div>

    <div class="footer-bottom">
        <p>&copy; <?= date('Y'); ?> FreeBox Sites — Todos os direitos reservados</p>
    </div>
</footer>

<a href="#inicio" class="back-to-top">
    <i class="fas fa-chevron-up"></i>
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>