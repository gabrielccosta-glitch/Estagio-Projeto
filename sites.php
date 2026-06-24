<?php
session_start();

require_once 'config/database.php';
require_once 'includes/functions.php';

$esta_logado = isset($_SESSION['usuario_id']);
$tipo_usuario = $_SESSION['tipo_usuario'] ?? '';
$dashboard_url = 'login.php';

if ($esta_logado && $tipo_usuario === 'admin') {
    $dashboard_url = 'admin/index.php';
} elseif ($esta_logado && $tipo_usuario === 'cliente') {
    $usuario_id = $_SESSION['usuario_id'];
    $stmt = $conn->prepare("SELECT id FROM empresas WHERE usuario_id = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $empresa = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($empresa) {
        $dashboard_url = 'empresa/empresa_informacoes.php?id=' . $empresa['id'];
    }
}

$sql = "
    SELECT
        e.id,
        e.nome_empresa,
        w.url_site,
        w.logotipo
    FROM empresas e
    INNER JOIN website_config w
        ON w.empresa_id = e.id
    INNER JOIN (
        SELECT empresa_id, MAX(id) AS max_id
        FROM website_config
        WHERE TRIM(COALESCE(url_site, '')) <> ''
        GROUP BY empresa_id
    ) latest
        ON latest.max_id = w.id
    ORDER BY e.nome_empresa ASC
";

$result = $conn->query($sql);
$sites = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

function assetHomeUrl($path)
{
    $path = trim((string) $path);

    if ($path === '') {
        return '';
    }

    if (preg_match('/^https?:\/\//i', $path) || str_starts_with($path, '/')) {
        return $path;
    }

    while (str_starts_with($path, '../')) {
        $path = substr($path, 3);
    }

    return './' . ltrim($path, './');
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sites | FreeBox Sites</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="./css/home.css?v=<?= time(); ?>">
</head>

<body>
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
                    <a href="index.php#sobre">Sobre</a>
                    <a href="index.php#funcionalidades">Funcionalidades</a>
                    <a href="index.php#como-funciona">Como funciona</a>
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
                            onclick="changeLang('pt', 'https://flagcdn.com/w20/pt.png')">
                            <img src="https://flagcdn.com/w20/pt.png" width="20" alt="PT"> Portugu&ecirc;s
                        </button>
                        <button class="lang-option" type="button"
                            onclick="changeLang('en', 'https://flagcdn.com/w20/gb.png')">
                            <img src="https://flagcdn.com/w20/gb.png" width="20" alt="EN"> English
                        </button>
                        <button class="lang-option" type="button"
                            onclick="changeLang('es', 'https://flagcdn.com/w20/es.png')">
                            <img src="https://flagcdn.com/w20/es.png" width="20" alt="ES"> Espa&ntilde;ol
                        </button>
                        <button class="lang-option" type="button"
                            onclick="changeLang('fr', 'https://flagcdn.com/w20/fr.png')">
                            <img src="https://flagcdn.com/w20/fr.png" width="20" alt="FR"> Fran&ccedil;ais
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
            <a href="index.php#sobre" class="mobile-link">Sobre</a>
            <a href="index.php#funcionalidades" class="mobile-link">Funcionalidades</a>
            <a href="index.php#como-funciona" class="mobile-link">Como funciona</a>
            <a href="sites.php" class="mobile-link">Sites</a>
            <a href="contato.php" class="mobile-link">Contacto</a>
        </nav>
    </header>

    <main class="sites-page">
        <section class="sites-hero">
            <div class="container sites-hero-inner">
                <h1>Empresas com site Online</h1>
                <p>Explora as empresas que j&aacute; t&ecirc;m o website criado na FreeBox Sites. Cada card abre diretamente o site p&uacute;blico da empresa.</p>
            </div>
        </section>

        <section class="sites-list-section">
            <div class="container">
                <?php if (empty($sites)): ?>
                    <div class="empty-sites-card">
                        <i class="fas fa-circle-info"></i>
                        <h2>Ainda n&atilde;o h&aacute; sites publicados</h2>
                    </div>
                <?php else: ?>
                    <div class="published-sites-grid">
                        <?php foreach ($sites as $site): ?>
                            <?php
                            $site_url = obterUrlEmpresa($site['url_site'], './');
                            $logo = assetHomeUrl($site['logotipo']);
                            ?>
                            <a href="<?= htmlspecialchars($site_url); ?>" target="_blank" rel="noopener" class="published-site-card">
                                <?php if ($logo !== ''): ?>
                                    <img src="<?= htmlspecialchars($logo); ?>" alt="<?= htmlspecialchars($site['nome_empresa']); ?>" class="site-card-logo-large">
                                <?php else: ?>
                                    <span class="site-card-logo-large site-card-logo-fallback">
                                        <?= htmlspecialchars(mb_strtoupper(mb_substr($site['nome_empresa'], 0, 1, 'UTF-8'), 'UTF-8')); ?>
                                    </span>
                                <?php endif; ?>
                                <h2><?= htmlspecialchars($site['nome_empresa']); ?></h2>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer id="contacto" class="main-footer">
        <div class="container footer-grid">
            <div class="footer-about">
                <h5>FreeBox Sites</h5>
                <p>Projeto de cria&ccedil;&atilde;o autom&aacute;tica de websites institucionais.</p>
            </div>

            <div>
                <h5>P&aacute;ginas</h5>
                <a href="index.php#sobre">Sobre</a>
                <a href="index.php#funcionalidades">Funcionalidades</a>
                <a href="index.php#como-funciona">Como funciona</a>
                <a href="sites.php">Sites</a>
                <a href="contato.php">Contacto</a>
            </div>

            <div>
                <h5>Legal</h5>
                <a href="politica_privacidade_freebox.php">Pol&iacute;tica de Privacidade</a>
                <a href="https://www.livroreclamacoes.pt/Inicio/" target="_blank" rel="noopener">Livro de Reclama&ccedil;&otilde;es</a>
            </div>

            <div>
                <h5>Sociais</h5>
                <a href="https://www.facebook.com/people/Is4-Inform%C3%A1tica/100057189652028/?sk=following"
                    target="_blank" rel="noopener">Facebook</a>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?= date('Y'); ?> FreeBox Sites - Todos os direitos reservados</p>
            <p>Desenvolvido por: <a href="https://webdesigner.is4.pt/" target="_blank" rel="noopener">IS4 Web Designer</a></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let originalTexts = [];
        let currentLang = 'pt';
        const translationCache = {};
        const skipTags = ['SCRIPT', 'STYLE', 'NOSCRIPT', 'IFRAME', 'INPUT', 'TEXTAREA', 'SELECT', 'BUTTON'];

        const langBtn = document.getElementById('langBtn');
        const langDropdown = document.getElementById('langDropdown');
        const hamburger = document.getElementById('hamburger');
        const mobileNav = document.getElementById('mobileNav');

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
                const response = await fetch(url);
                const data = await response.json();
                const translated = data?.responseData?.translatedText || text;
                translationCache[cacheKey] = translated;
                return translated;
            } catch (error) {
                return text;
            }
        }

        async function changeLang(lang, flag) {
            if (lang === currentLang) {
                langDropdown.classList.remove('open');
                return;
            }

            const currentFlag = document.getElementById('langCurrent');
            currentFlag.src = flag;
            langDropdown.classList.remove('open');
            langBtn.style.opacity = '0.5';
            langBtn.style.pointerEvents = 'none';
            langBtn.querySelector('i').className = 'fas fa-spinner fa-spin';

            const nodes = getTextNodes();
            if (originalTexts.length === 0) {
                originalTexts = nodes.map(node => node.textContent);
            } else {
                nodes.forEach((node, index) => {
                    if (originalTexts[index] !== undefined) node.textContent = originalTexts[index];
                });
            }

            if (lang === 'pt') {
                originalTexts = [];
                currentLang = 'pt';
                langBtn.style.opacity = '1';
                langBtn.style.pointerEvents = 'auto';
                langBtn.querySelector('i').className = 'fas fa-chevron-down';
                return;
            }

            currentLang = lang;
            const freshNodes = getTextNodes();
            const batchSize = 5;

            for (let index = 0; index < freshNodes.length; index += batchSize) {
                const batch = freshNodes.slice(index, index + batchSize);
                await Promise.all(batch.map(async (node, batchIndex) => {
                    const original = originalTexts[index + batchIndex] || node.textContent;
                    if (original.trim().length < 2) return;
                    node.textContent = await translateText(original.trim(), lang);
                }));
            }

            langBtn.style.opacity = '1';
            langBtn.style.pointerEvents = 'auto';
            langBtn.querySelector('i').className = 'fas fa-chevron-down';
        }

        langBtn.addEventListener('click', function (event) {
            event.stopPropagation();
            langDropdown.classList.toggle('open');
        });

        hamburger.addEventListener('click', function (event) {
            event.stopPropagation();
            hamburger.classList.toggle('open');
            mobileNav.classList.toggle('open');
        });

        document.querySelectorAll('.mobile-link').forEach(function (link) {
            link.addEventListener('click', function () {
                hamburger.classList.remove('open');
                mobileNav.classList.remove('open');
            });
        });

        document.addEventListener('click', function (event) {
            langDropdown.classList.remove('open');
            if (!hamburger.contains(event.target) && !mobileNav.contains(event.target)) {
                hamburger.classList.remove('open');
                mobileNav.classList.remove('open');
            }
        });
    </script>
</body>

</html>