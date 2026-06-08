<?php
session_start();

$esta_logado = isset($_SESSION['usuario_id']);
$tipo_usuario = $_SESSION['tipo_usuario'] ?? '';

$dashboard_url = 'login.php';

if ($esta_logado && $tipo_usuario === 'admin') {

    $dashboard_url = 'admin/index.php';

} elseif ($esta_logado && $tipo_usuario === 'cliente') {

    require_once 'config/database.php';

    $usuario_id = $_SESSION['usuario_id'];

    $sql = "SELECT id FROM empresas WHERE usuario_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $usuario_id);

    $stmt->execute();

    $result = $stmt->get_result();

    $empresa = $result->fetch_assoc();

    $stmt->close();

    if ($empresa) {

        $dashboard_url =
            'empresa/empresa_informacoes.php?id=' . $empresa['id'];
    }
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

    <link rel="stylesheet" href="./css/home.css?v=<?= time(); ?>">
</head>

<body>

    <!-- NAVBAR -->
    <header class="main-navbar">
        <div class="navbar-shell">

            <div class="navbar-left">
                <a href="./" class="brand">
                    <img src="./imagens/logotipo_freebox.png" alt="FreeBox Sites"
                        style="height: 70px; width: 70px; max-height: 100%; display: block;">
                </a>
            </div>

            <div class="navbar-right">
                <nav class="nav-menu">
                    <a href="#sobre">Sobre</a>
                    <a href="#funcionalidades">Funcionalidades</a>
                    <a href="#como-funciona">Como funciona</a>
                    <a href="contato.php">Contacto</a>
                </nav>

                <div class="nav-actions">
                    <?php if ($esta_logado): ?>
                        <a href="<?= htmlspecialchars($dashboard_url); ?>" class="btn btn-outline-main">
                            Ver empresa
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-main">Login</a>
                    <?php endif; ?>
                </div>

                <div class="lang-selector" id="langSelector">
                    <button class="lang-btn" id="langBtn" type="button">
                        <img id="langCurrent" src="https://flagcdn.com/w20/pt.png" width="20" alt="PT">
                        <i class="fas fa-chevron-down"></i>
                    </button>

                    <div class="lang-dropdown" id="langDropdown">
                        <button class="lang-option" type="button"
                            onclick="changeLang('pt', 'https://flagcdn.com/w20/pt.png', 'PT')">
                            <img src="https://flagcdn.com/w20/pt.png" width="20" alt="PT"> Português
                        </button>
                        <button class="lang-option" type="button"
                            onclick="changeLang('en', 'https://flagcdn.com/w20/gb.png', 'EN')">
                            <img src="https://flagcdn.com/w20/gb.png" width="20" alt="EN"> English
                        </button>
                        <button class="lang-option" type="button"
                            onclick="changeLang('es', 'https://flagcdn.com/w20/es.png', 'ES')">
                            <img src="https://flagcdn.com/w20/es.png" width="20" alt="ES"> Español
                        </button>
                        <button class="lang-option" type="button"
                            onclick="changeLang('fr', 'https://flagcdn.com/w20/fr.png', 'FR')">
                            <img src="https://flagcdn.com/w20/fr.png" width="20" alt="FR"> Français
                        </button>
                    </div>
                </div>

                <button class="hamburger" id="hamburger" type="button" aria-label="Abrir menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>

        </div>

        <nav class="mobile-nav" id="mobileNav">
            <a href="#sobre" class="mobile-link">Sobre</a>
            <a href="#funcionalidades" class="mobile-link">Funcionalidades</a>
            <a href="#como-funciona" class="mobile-link">Como funciona</a>
            <a href="contato.php" class="mobile-link">Contacto</a>
        </nav>
    </header>

    <!-- HERO -->
    <section id="inicio" class="hero-section">
        <div class="hero-inner">

            <div class="hero-content-box">
                <span class="hero-label">
                    <i class="fas fa-wand-magic-sparkles"></i>
                    Criação automática de websites
                </span>

                <h1>Cria o site da tua empresa</h1>

                <p>
                    O FreeBox Sites permite que empresas criem uma presença online através de um
                    painel simples, sem precisar de programar. Basta preencher os dados, adicionar
                    serviços, imagens e contactos.
                </p>

                <div class="hero-buttons">
                    <a href="register.php" class="btn btn-main">Criar o meu site</a>
                    <a href="login.php" class="btn btn-outline-hero">Já tenho conta</a>
                </div>
            </div>

            <div class="hero-image-side">
                <img src="./imagens/hero.svg" alt="FreeBox Sites — criação de websites">
            </div>

        </div>
    </section>

    <!-- SOBRE -->
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

    <!-- FUNCIONALIDADES -->
    <section id="funcionalidades" class="features-section section-padding">
        <div class="container">

            <div class="section-title-box">
                <span>Funcionalidades</span>
                <h2>O cliente controla o conteúdo do próprio site</h2>
                <div class="title-line"></div>
            </div>

            <div class="features-grid">

                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-circle-info"></i></div>
                    <h4>Informações da Empresa</h4>
                    <p>Nome, morada, telefone, email e contacto principal.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-handshake"></i></div>
                    <h4>Serviços</h4>
                    <p>Criação e gestão dos serviços apresentados no site público.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-images"></i></div>
                    <h4>Portfólio</h4>
                    <p>Upload de imagens para mostrar trabalhos, produtos ou projetos.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-palette"></i></div>
                    <h4>Personalização</h4>
                    <p>Logotipo, capa, descrição, redes sociais e endereço público.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-envelope"></i></div>
                    <h4>Contacto</h4>
                    <p>Página de contacto com dados da empresa, mapa e formulário.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-language"></i></div>
                    <h4>Tradução</h4>
                    <p>Seletor de idioma no site público para tornar o site mais acessível.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- COMO FUNCIONA -->
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

    <!-- FOOTER -->
    <footer id="contacto" class="main-footer">
        <div class="container footer-grid">

            <div class="footer-about">
                <h5>FreeBox Sites</h5>
                <p>Projeto de criação automática de websites institucionais.</p>
            </div>

            <div>
                <h5>Páginas</h5>
                <a href="#sobre">Sobre</a>
                <a href="#funcionalidades">Funcionalidades</a>
                <a href="#como-funciona">Como funciona</a>
                <a href="contato.php">Contacto</a>
            </div>

            <div>
                <h5>Legal</h5>
                <a href="politica_privacidade_freebox.php">Política de Privacidade</a>
                <a href="https://www.livroreclamacoes.pt/Inicio/" target="_blank" rel="noopener">Livro de
                    Reclamações</a>
            </div>

            <div>
                <h5>Sociais</h5>
                <a href="https://www.facebook.com/people/Is4-Inform%C3%A1tica/100057189652028/?sk=following"
                    target="_blank" rel="noopener">Facebook</a>
                <!--
            <a href="#" target="_blank" rel="noopener">Instagram</a>
            <a href="#" target="_blank" rel="noopener">X / Twitter</a>
            -->
            </div>

        </div>

        <div class="footer-bottom">
            <p>&copy; <?= date('Y'); ?> FreeBox Sites — Todos os direitos reservados</p>
            <p>Desenvolvido por: <a href="https://webdesigner.is4.pt/ " target="_blank" rel="noopener">IS4 Web Designer</a></p>
        </div>

    </footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let originalTexts = [];
        let currentLang = 'pt';
        const translationCache = {};

        const skipTags = ['SCRIPT', 'STYLE', 'NOSCRIPT', 'IFRAME', 'INPUT', 'TEXTAREA', 'SELECT', 'BUTTON'];

        function getTextNodes() {
            const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, {
                acceptNode: function (node) {
                    if (!node.parentElement) return NodeFilter.FILTER_REJECT;
                    if (skipTags.includes(node.parentElement.tagName)) return NodeFilter.FILTER_REJECT;
                    if (node.parentElement.closest('.no-translate, .lang-selector')) return NodeFilter.FILTER_REJECT;
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

        async function translateText(text, targetLang) {
            const cacheKey = targetLang + '|' + text;
            if (translationCache[cacheKey]) return translationCache[cacheKey];

            try {
                const url = `https://api.mymemory.translated.net/get?q=${encodeURIComponent(text)}&langpair=pt|${targetLang}`;
                const res = await fetch(url);
                const data = await res.json();
                const translated = data?.responseData?.translatedText || text;
                translationCache[cacheKey] = translated;
                return translated;
            } catch (e) {
                return text;
            }
        }

        async function changeLang(lang, flag) {
            if (lang === currentLang) return;

            const btn = document.getElementById('langBtn');
            const current = document.getElementById('langCurrent');
            const dropdown = document.getElementById('langDropdown');

            current.src = flag;
            dropdown.classList.remove('open');
            btn.style.opacity = '0.5';
            btn.style.pointerEvents = 'none';
            btn.querySelector('i').className = 'fas fa-spinner fa-spin';

            const nodes = getTextNodes();

            if (originalTexts.length === 0) {
                originalTexts = nodes.map(n => n.textContent);
            } else {
                nodes.forEach((node, i) => {
                    if (originalTexts[i] !== undefined) {
                        node.textContent = originalTexts[i];
                    }
                });
            }

            if (lang === 'pt') {
                originalTexts = [];
                currentLang = 'pt';
                btn.style.opacity = '1';
                btn.style.pointerEvents = 'auto';
                btn.querySelector('i').className = 'fas fa-chevron-down';
                return;
            }

            currentLang = lang;

            const freshNodes = getTextNodes();
            const batchSize = 5;

            for (let i = 0; i < freshNodes.length; i += batchSize) {
                const batch = freshNodes.slice(i, i + batchSize);

                await Promise.all(batch.map(async (node, j) => {
                    const original = originalTexts[i + j] || node.textContent;
                    if (original.trim().length < 2) return;
                    node.textContent = await translateText(original.trim(), lang);
                }));
            }

            btn.style.opacity = '1';
            btn.style.pointerEvents = 'auto';
            btn.querySelector('i').className = 'fas fa-chevron-down';
        }

        document.getElementById('langBtn').addEventListener('click', function (e) {
            e.stopPropagation();
            document.getElementById('langDropdown').classList.toggle('open');
        });

        document.addEventListener('click', function () {
            document.getElementById('langDropdown').classList.remove('open');
        });

        const hamburger = document.getElementById('hamburger');
        const mobileNav = document.getElementById('mobileNav');

        hamburger.addEventListener('click', function (e) {
            e.stopPropagation();
            hamburger.classList.toggle('open');
            mobileNav.classList.toggle('open');
        });

        document.querySelectorAll('.mobile-link').forEach(function (link) {
            link.addEventListener('click', function () {
                hamburger.classList.remove('open');
                mobileNav.classList.remove('open');
            });
        });

        document.addEventListener('click', function (e) {
            if (!hamburger.contains(e.target) && !mobileNav.contains(e.target)) {
                hamburger.classList.remove('open');
                mobileNav.classList.remove('open');
            }
        });
    </script>

</body>

</html>