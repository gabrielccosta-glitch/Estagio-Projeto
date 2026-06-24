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
        $dashboard_url = 'empresa/empresa_informacoes.php?id=' . $empresa['id'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreeBox Sites | Contacto</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="./css/home.css?v=<?= time(); ?>">
</head>

<body>

    <header class="main-navbar">
        <div class="navbar-shell">

            <div class="navbar-left">
                <a href="./" class="brand">
                    <img src="./imagens/logotipo_freebox.png" alt="FreeBox Sites">
                </a>
            </div>

            <div class="navbar-right">
                <nav class="nav-menu">
                    <a href="./#sobre">Sobre</a>
                    <a href="./#funcionalidades">Funcionalidades</a>
                    <a href="./#como-funciona">Como funciona</a>
                    <a href="contato.php">Contacto</a>
                </nav>

                <div class="nav-actions">
                    <a href="sites.php" class="btn btn-main">Sites</a>
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
            <a href="./#sobre" class="mobile-link">Sobre</a>
            <a href="./#funcionalidades" class="mobile-link">Funcionalidades</a>
            <a href="./#como-funciona" class="mobile-link">Como funciona</a>
            <a href="sites.php" class="mobile-link">Sites</a>
            <a href="contato.php" class="mobile-link">Contacto</a>
        </nav>
    </header>

    <section class="about-section section-padding" style="margin-top: 80px;">
        <div class="container">
            <h2 class="section-title text-center">Contacto</h2>
            <div class="section-line mx-auto"></div>

            <div class="row mt-5 align-items-stretch">

                <div class="col-lg-8 mb-4 mb-lg-0">
                    <div class="d-flex align-items-start gap-4 p-4 h-100" style="background: #fff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border-left: 4px solid #000;">
                        <div class="about-icon fs-2 text-dark mt-1">
                            <i class="fas fa-store"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="mb-4">
                                <p class="mb-0 text-muted fs-5">
                                    <i class="fas fa-location-dot me-2 text-secondary"></i>
                                    Rua Acácio Lino, 354 <br> 4600-045 <br> Amarante
                                </p>
                            </div>
                            <div>
                                <p class="mb-0 text-muted fs-5">
                                    <i class="fas fa-mobile-screen me-2 text-secondary"></i>
                                    <a href="tel:923432234" class="text-decoration-none text-muted">923 432 234</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4" style="position: sticky; top: 90px; align-self: flex-start;">
                    <div class="about-map-card w-100" style="background: #fff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); overflow: hidden;">
                        <iframe
                            src="https://maps.google.com/maps?q=Rua+Ac%C3%A1cio+Lino+354+4600-045+Amarante&output=embed"
                            style="width: 100%; height: 260px; border: 0; display: block;"
                            allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <footer id="contacto" class="main-footer">
        <div class="container footer-grid">

            <div class="footer-about">
                <h5>FreeBox Sites</h5>
                <p>Projeto de criação automática de websites institucionais.</p>
            </div>

            <div>
                <h5>Páginas</h5>
                <a href="./#sobre">Sobre</a>
                <a href="./#funcionalidades">Funcionalidades</a>
                <a href="./#como-funciona">Como funciona</a>
                <a href="contato.php">Contacto</a>
            </div>

            <div>
                <h5>Legal</h5>
                <a href="politica_privacidade_freebox.php">Política de Privacidade</a>
                <a href="https://www.livroreclamacoes.pt/Inicio/" target="_blank" rel="noopener">Livro de Reclamações</a>
            </div>

            <div>
                <h5>Sociais</h5>
                <a href="https://www.facebook.com/people/Is4-Inform%C3%A1tica/100057189652028/?sk=following"
                    target="_blank" rel="noopener">Facebook</a>
            </div>

        </div>

        <div class="footer-bottom">
            <p>© <?= date('Y'); ?> FreeBox Sites — Todos os direitos reservados</p>
            <p>Desenvolvido por: <a href="https://webdesigner.is4.pt/" target="_blank" rel="noopener">IS4</a></p>
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
            while (walker.nextNode()) nodes.push(walker.currentNode);
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
                    if (originalTexts[i] !== undefined) node.textContent = originalTexts[i];
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