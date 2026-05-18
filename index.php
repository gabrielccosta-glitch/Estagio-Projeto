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

<header class="home-navbar">
    <div class="container navbar-inner">

        <a href="/projeto/" class="brand">
            <img src="/projeto/imagens/Logotipo_freebox.png" alt="FreeBox" class="brand-logo">
            <span>FreeBox Sites</span>
        </a>

        <nav class="nav-links">
            <a href="#sobre">Sobre</a>
            <a href="#funcionalidades">Funcionalidades</a>
            <a href="#como-funciona">Como funciona</a>

            <?php if ($esta_logado): ?>
                <a href="<?= htmlspecialchars($dashboard_url); ?>" class="btn btn-outline-primary">
                    Dashboard
                </a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-primary">
                    Login
                </a>
                <a href="register.php" class="btn btn-primary">
                    Criar Site
                </a>
            <?php endif; ?>
        </nav>

    </div>
</header>

<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-5">

            <div class="col-lg-6">
                <div class="hero-content">
                    <span class="hero-badge">
                        <i class="fas fa-wand-magic-sparkles"></i>
                        Plataforma de criação automática de websites
                    </span>

                    <h1>Cria o site da tua empresa de forma simples, rápida e profissional.</h1>

                    <p>
                        O FreeBox Sites permite que qualquer empresa crie uma presença online
                        sem precisar de programar. Basta preencher as informações, adicionar serviços,
                        imagens, contactos e o sistema gera automaticamente um website público.
                    </p>

                    <div class="hero-actions">
                        <a href="register.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus"></i>
                            Criar o meu site
                        </a>

                        <a href="login.php" class="btn btn-light btn-lg">
                            <i class="fas fa-right-to-bracket"></i>
                            Entrar
                        </a>
                    </div>

                    <div class="hero-mini-info">
                        <div>
                            <strong>Sem código</strong>
                            <span>o cliente só preenche dados</span>
                        </div>
                        <div>
                            <strong>Personalizável</strong>
                            <span>logo, capa, serviços e portfólio</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="site-preview">

                    <div class="browser-bar">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>

                    <div class="preview-navbar">
                        <div class="preview-logo"></div>
                        <div class="preview-menu">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>

                    <div class="preview-hero">
                        <div>
                            <span class="preview-title"></span>
                            <span class="preview-subtitle"></span>
                            <span class="preview-button"></span>
                        </div>
                    </div>

                    <div class="preview-section">
                        <div class="preview-card"></div>
                        <div class="preview-card"></div>
                        <div class="preview-card"></div>
                    </div>

                    <div class="preview-footer">
                        <span></span>
                        <span></span>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<section id="sobre" class="about-section">
    <div class="container">
        <div class="section-header">
            <span>Sobre o projeto</span>
            <h2>Um sistema que cria sites para empresas.</h2>
            <p>
                Este projeto foi desenvolvido para simplificar a criação de websites institucionais.
                O administrador gere empresas e os clientes conseguem configurar o próprio website
                através de um painel simples.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="info-card">
                    <i class="fas fa-user-gear"></i>
                    <h4>Painel do Cliente</h4>
                    <p>
                        O cliente pode editar informações da empresa, serviços, portfólio,
                        logotipo, capa, redes sociais e endereço do site.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-card">
                    <i class="fas fa-shield-halved"></i>
                    <h4>Painel Admin</h4>
                    <p>
                        O administrador consegue visualizar empresas, configurar contas,
                        editar acessos e gerir o sistema de forma centralizada.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-card">
                    <i class="fas fa-globe"></i>
                    <h4>Site Público</h4>
                    <p>
                        O sistema gera automaticamente um website público com apresentação,
                        serviços, portfólio, contactos, mapa e formulário.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="funcionalidades" class="features-section">
    <div class="container">

        <div class="section-header">
            <span>Funcionalidades</span>
            <h2>O cliente controla o conteúdo do próprio site.</h2>
        </div>

        <div class="features-grid">

            <div class="feature-item">
                <div class="feature-icon icon-blue">
                    <i class="fas fa-building"></i>
                </div>
                <h4>Informações da empresa</h4>
                <p>Nome, morada, telefone, email e contacto principal.</p>
            </div>

            <div class="feature-item">
                <div class="feature-icon icon-purple">
                    <i class="fas fa-handshake"></i>
                </div>
                <h4>Serviços</h4>
                <p>Criação e gestão dos serviços apresentados no site público.</p>
            </div>

            <div class="feature-item">
                <div class="feature-icon icon-green">
                    <i class="fas fa-images"></i>
                </div>
                <h4>Portfólio</h4>
                <p>Upload de imagens para mostrar trabalhos, produtos ou projetos.</p>
            </div>

            <div class="feature-item">
                <div class="feature-icon icon-cyan">
                    <i class="fas fa-palette"></i>
                </div>
                <h4>Personalização</h4>
                <p>Logotipo, capa, descrição, redes sociais e endereço público.</p>
            </div>

            <div class="feature-item">
                <div class="feature-icon icon-orange">
                    <i class="fas fa-envelope"></i>
                </div>
                <h4>Contacto</h4>
                <p>Página de contacto com dados da empresa, mapa e formulário.</p>
            </div>

            <div class="feature-item">
                <div class="feature-icon icon-red">
                    <i class="fas fa-language"></i>
                </div>
                <h4>Tradução</h4>
                <p>Seletor de idioma no site público para tornar o site mais acessível.</p>
            </div>

        </div>
    </div>
</section>

<section id="como-funciona" class="steps-section">
    <div class="container">

        <div class="section-header">
            <span>Como funciona</span>
            <h2>Do registo ao website publicado.</h2>
        </div>

        <div class="steps-wrapper">

            <div class="step-card">
                <div class="step-number">1</div>
                <h4>Criar conta</h4>
                <p>A empresa faz o registo e passa a ter acesso ao painel de cliente.</p>
            </div>

            <div class="step-line"></div>

            <div class="step-card">
                <div class="step-number">2</div>
                <h4>Configurar conteúdo</h4>
                <p>O cliente preenche informações, serviços, portfólio e dados do website.</p>
            </div>

            <div class="step-line"></div>

            <div class="step-card">
                <div class="step-number">3</div>
                <h4>Gerar site</h4>
                <p>O sistema apresenta automaticamente o website público com os dados inseridos.</p>
            </div>

        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-box">
            <h2>Queres criar o site da tua empresa?</h2>
            <p>
                Começa agora. Cria uma conta, configura os dados e publica o teu website.
            </p>

            <div class="cta-actions">
                <a href="register.php" class="btn btn-light btn-lg">
                    Criar o meu site
                </a>

                <a href="login.php" class="btn btn-outline-light btn-lg">
                    Já tenho conta
                </a>
            </div>
        </div>
    </div>
</section>

<footer class="home-footer">
    <div class="container footer-inner">
        <div>
            <strong>FreeBox Sites</strong>
            <p>Projeto de criação automática de websites institucionais.</p>
        </div>

        <div>
            <p>&copy; <?= date('Y'); ?> FreeBox Sites. Todos os direitos reservados.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>