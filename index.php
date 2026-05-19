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

    <link rel="stylesheet" href="/projeto/css/home.css?v=<?= time(); ?>">
</head>
<body>

<header class="main-navbar">
    <div class="navbar-shell">

        <div class="navbar-left">
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
        </div>

        <div class="navbar-right">
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

            <div class="lang-selector" id="langSelector">
                <button class="lang-btn" id="langBtn">
                    <img id="langCurrent" src="https://flagcdn.com/w20/pt.png" width="20" alt="PT">
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="lang-dropdown" id="langDropdown">
                    <button class="lang-option" onclick="changeLang('pt', 'https://flagcdn.com/w20/pt.png', 'PT')">
                        <img src="https://flagcdn.com/w20/pt.png" width="20"> Português
                    </button>
                    <button class="lang-option" onclick="changeLang('en', 'https://flagcdn.com/w20/gb.png', 'EN')">
                        <img src="https://flagcdn.com/w20/gb.png" width="20"> English
                    </button>
                    <button class="lang-option" onclick="changeLang('es', 'https://flagcdn.com/w20/es.png', 'ES')">
                        <img src="https://flagcdn.com/w20/es.png" width="20"> Espanol
                    </button>
                    <button class="lang-option" onclick="changeLang('fr', 'https://flagcdn.com/w20/fr.png', 'FR')">
                        <img src="https://flagcdn.com/w20/fr.png" width="20"> Francês
                    </button>
                </div>
        </div>

    </div>
</header>

<section id="inicio" class="hero-section">
    <div class="hero-overlay"></div>

    <div class="hero-content">
        <span class="hero-label">
            <i class="fas fa-wand-magic-sparkles"></i>
            Plataforma de criação automática de websites
        </span>

        <h1>Cria o site da tua empresa de forma simples e profissional</h1>

        <p>
            O FreeBox Sites permite que empresas criem uma presença online através de um
            painel simples, sem precisar de programar. Basta preencher os dados, adicionar
            serviços, imagens e contactos.
        </p>

        <div class="hero-buttons">
            <a href="register.php" class="btn btn-main btn-lg">
                Criar o meu site
            </a>

            <a href="login.php" class="btn btn-outline-hero btn-lg">
                Já tenho conta
            </a>
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
                            Este projeto foi desenvolvido para simplificar a criação de websites
                            institucionais. O administrador gere empresas e os clientes conseguem
                            configurar o próprio website através de um painel simples e direto.
                        </p>

                        <p>
                            Cada empresa pode editar as suas informações, serviços, portfólio,
                            logotipo, capa, redes sociais e endereço público. O sistema transforma
                            estes dados num website pronto a visitar.
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
                <h4>Informações da Empresa</h4>
                <p>Nome, morada, telefone, email e contacto principal.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h4>Serviços</h4>
                <p>Criação e gestão dos serviços apresentados no site público.</p>
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
                    <i class="fas fa-palette"></i>
                </div>
                <h4>Personalização</h4>
                <p>Logotipo, capa, descrição, redes sociais e endereço público.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h4>Contacto</h4>
                <p>Página de contacto com dados da empresa, mapa e formulário.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-language"></i>
                </div>
                <h4>Tradução</h4>
                <p>Seletor de idioma no site público para tornar o site mais acessível.</p>
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
                <h4>Gerar site</h4>
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

<script>
let originalTexts = [];
let currentLang = 'pt';

function getTextNodes() {
    const skipTags = ['SCRIPT', 'STYLE', 'NOSCRIPT', 'IFRAME', 'INPUT', 'TEXTAREA', 'SELECT', 'BUTTON'];

    const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, {
        acceptNode: function(node) {
            if (!node.parentElement) return NodeFilter.FILTER_REJECT;
            if (skipTags.includes(node.parentElement.tagName)) return NodeFilter.FILTER_REJECT;
            if (node.parentElement.closest('.no-translate')) return NodeFilter.FILTER_REJECT;
            if (!node.textContent.trim()) return NodeFilter.FILTER_REJECT;

            return NodeFilter.FILTER_ACCEPT;
        }
    });

    const nodes = [];

    while (walker.nextNode()) {
        nodes.push(walker.currentNode);
    }

    return nodes;
}

// index.php - Página inicial que redireciona para o login ou área do usuário

// Incluir arquivos necessários
require_once 'config/database.php';
require_once 'includes/functions.php';

iniciarSessao();

// Se já estiver logado, redireciona conforme o tipo de usuário
if (estaLogado()) {
    redirecionarUsuario();
} else {
    // Se não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit;
}

async function changeLang(lang, flag) {
    if (lang === currentLang) return;

    const btn = document.getElementById('langBtn');
    const current = document.getElementById('langCurrent');
    const dropdown = document.getElementById('langDropdown');

    current.textContent = flag;
    dropdown.classList.remove('open');

    btn.style.opacity = '0.6';
    btn.style.pointerEvents = 'none';

    if (lang === 'pt') {
        const nodes = getTextNodes();

        nodes.forEach((node, index) => {
            if (originalTexts[index] !== undefined) {
                node.textContent = originalTexts[index];
            }
        });

        originalTexts = [];
        currentLang = 'pt';

        btn.style.opacity = '1';
        btn.style.pointerEvents = 'auto';
        return;
    }

    let nodes = getTextNodes();

    if (originalTexts.length === 0) {
        originalTexts = nodes.map(node => node.textContent);
    } else {
        nodes.forEach((node, index) => {
            if (originalTexts[index] !== undefined) {
                node.textContent = originalTexts[index];
            }
        });
    }

    nodes = getTextNodes();
    const texts = nodes.map(node => node.textContent);
    const translated = await translateBatch(texts, lang);

    nodes.forEach((node, index) => {
        node.textContent = translated[index];
    });

    currentLang = lang;

    btn.style.opacity = '1';
    btn.style.pointerEvents = 'auto';
}

document.getElementById('langBtn').addEventListener('click', function(event) {
    event.stopPropagation();
    document.getElementById('langDropdown').classList.toggle('open');
});

document.addEventListener('click', function() {
    document.getElementById('langDropdown').classList.remove('open');
});
</script>

</body>
</html>
