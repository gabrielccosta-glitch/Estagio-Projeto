<?php
$is_tenant = (basename(__DIR__) !== 'template');
if ($is_tenant) {
    $link_home = "./";
    $link_servicos = "./#servicos";
    $link_portfolio = "./#portfolio";
    $link_sobre = "./#sobre";
    $link_contato = "contato.php";
    $link_formulario = "formulario.php";
    $link_politica = "politica-privacidade";
} else {
    $url_param = htmlspecialchars($website['url_site'] ?? '');
    $link_home = "../template/?url=" . $url_param;
    $link_servicos = "../template/?url=" . $url_param . "#servicos";
    $link_portfolio = "../template/?url=" . $url_param . "#portfolio";
    $link_sobre = "../template/?url=" . $url_param . "#sobre";
    $link_contato = "../template/contato.php?url=" . $url_param;
    $link_formulario = "../template/formulario.php?url=" . $url_param;
    $link_politica = "../template/" . $url_param . "/politica-privacidade";
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($nome_empresa ?? 'Nome da Empresa'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/site_publico.css">
    <?php
    $cor_primaria = $website['cor_primaria'] ?? '#1a1a1a';
    $cor_secundaria = $website['cor_secundaria'] ?? '#555555';

    if (!function_exists('isColorLight')) {
        function isColorLight($hex) {
            $hex = ltrim($hex, '#');
            if (strlen($hex) == 3) {
                $r = hexdec($hex[0].$hex[0]);
                $g = hexdec($hex[1].$hex[1]);
                $b = hexdec($hex[2].$hex[2]);
            } elseif (strlen($hex) == 6) {
                $r = hexdec(substr($hex, 0, 2));
                $g = hexdec(substr($hex, 2, 2));
                $b = hexdec(substr($hex, 4, 2));
            } else {
                return false;
            }
            $brightness = sqrt($r * $r * 0.299 + $g * $g * 0.587 + $b * $b * 0.114);
            return $brightness > 170;
        }
    }

    $is_light = isColorLight($cor_primaria);
    $is_sec_light = isColorLight($cor_secundaria);
    $footer_text = $is_light ? '#475569' : 'rgba(255, 255, 255, 0.65)';
    $footer_title = $is_light ? '#0f172a' : '#ffffff';
    $footer_border = $is_light ? 'rgba(0, 0, 0, 0.08)' : 'rgba(255, 255, 255, 0.08)';
    $footer_muted = $is_light ? '#64748b' : 'rgba(255, 255, 255, 0.45)';
    $primary_text = $is_light ? '#0f172a' : '#ffffff';
    $secondary_text = $is_sec_light ? '#0f172a' : '#ffffff';
    ?>
    <style>
        :root {
            --primary: <?= htmlspecialchars($cor_primaria); ?>;
            --secondary: <?= htmlspecialchars($cor_secundaria); ?>;
            --footer-text: <?= $footer_text; ?>;
            --footer-title: <?= $footer_title; ?>;
            --footer-border: <?= $footer_border; ?>;
            --footer-muted: <?= $footer_muted; ?>;
            --primary-text: <?= $primary_text; ?>;
            --secondary-text: <?= $secondary_text; ?>;
        }
    </style>

    <style>
        * { font-family: 'DM Sans', sans-serif; font-weight: 400; }

        .carousel-outer {
            position: relative;
            padding: 0 48px;
        }

        .carousel-wrapper { overflow: hidden; }

        .carousel-track {
            display: flex;
            transition: transform 0.45s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .carousel-page {
            min-width: 100%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            box-sizing: border-box;
        }

        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            font-size: 0.9rem;
            transition: box-shadow 0.2s;
        }

        .carousel-btn:hover { box-shadow: 0 4px 18px rgba(0,0,0,0.18); }
        .carousel-btn.prev { left: 0; }
        .carousel-btn.next { right: 0; }

        .carousel-dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
        }

        .carousel-dots .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #ccc;
            border: none;
            padding: 0;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
        }

        .carousel-dots .dot.active {
            background: currentColor;
            transform: scale(1.4);
        }


        /* ── NAVBAR ── */
        .public-navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: white;
            box-shadow: 0 1px 8px rgba(0,0,0,0.07);
            padding: 0 20px;
            height: 74px;
            display: flex;
            align-items: center;
        }

        .navbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .brand {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .brand-logo {
            height: 56px;
            max-height: 56px;
            max-width: 240px;
            object-fit: contain;
        }

        .brand-name {
            font-size: 1.1rem;
            font-weight: 500;
            color: #1a3a5c;
        }

        .nav-links {
            display: flex;
            gap: 28px;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #444;
            font-weight: 400;
            font-size: 0.9rem;
            transition: color 0.2s;
        }

        .nav-links a:hover { color: #1a3a5c; }

        /* ── MENU HAMBÚRGUER ── */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            z-index: 1100;
        }

        .hamburger span {
            display: block;
            width: 22px;
            height: 2px;
            background: #333;
            border-radius: 2px;
            transition: all 0.3s;
        }

        .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .hamburger.open span:nth-child(2) { opacity: 0; }
        .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        .mobile-menu {
            display: none;
            position: fixed;
            top: 74px;
            left: 0;
            width: 100%;
            background: white;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            z-index: 999;
            padding: 16px 24px 20px;
            flex-direction: column;
            gap: 0;
        }

        .mobile-menu.open { display: flex; }

        .mobile-menu a {
            text-decoration: none;
            color: #333;
            font-size: 0.95rem;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
            transition: color 0.2s;
        }

        .mobile-menu a:last-child { border-bottom: none; }
        .mobile-menu a:hover { color: #1a3a5c; }

        /* ── SELETOR DE LÍNGUA ── */
        .lang-selector { position: relative; }

        .lang-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: none;
            border: 1px solid #ddd;
            border-radius: 7px;
            padding: 5px 10px;
            font-size: 0.82rem;
            font-weight: 400;
            color: #444;
            cursor: pointer;
            transition: border-color 0.2s, color 0.2s;
            white-space: nowrap;
        }

        .lang-btn:hover { border-color: #1a3a5c; color: #1a3a5c; }
        .lang-btn i { font-size: 0.65rem; }

        .lang-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            background: white;
            border: 1px solid #eee;
            border-radius: 8px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            min-width: 140px;
            overflow: hidden;
            z-index: 9999;
        }

        .lang-dropdown.open { display: block; }

        .lang-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            font-size: 0.85rem;
            color: #444;
            cursor: pointer;
            transition: background 0.15s;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .lang-option:hover { background: #f4f7fb; color: #1a3a5c; }

        /* ── RESPONSIVO ── */
        @media (max-width: 1024px) {
            .nav-links { display: none; }
            .hamburger { display: flex; }
        }

        @media (max-width: 1024px) {
            .carousel-page { grid-template-columns: repeat(2, 1fr); }
            .carousel-outer { padding: 0 36px; }
        }

        @media (max-width: 480px) {
            .carousel-page { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>

    <header class="public-navbar">
        <div class="container navbar-inner">

            <!-- LOGO / NOME -->
            <a href="<?= $link_home; ?>" class="brand">
                <?php if (!empty($logo)): ?>
                    <img src="<?= htmlspecialchars($logo); ?>"
                         alt="<?= htmlspecialchars($nome_empresa ?? 'Nome da Empresa'); ?>"
                         class="brand-logo">
                <?php else: ?>
                    <span class="brand-name"><?= htmlspecialchars($nome_empresa ?? 'Nome da Empresa'); ?></span>
                <?php endif; ?>
            </a>

            <!-- LINKS DESKTOP -->
            <nav class="nav-links">
                <?php if (!empty($servicos)): ?>
                    <a href="<?= $link_servicos; ?>">Serviços</a>
                <?php endif; ?>
                <?php if (!empty($portfolio)): ?>
                    <a href="<?= $link_portfolio; ?>">Portfólio</a>
                <?php endif; ?>
                <a href="<?= $link_sobre; ?>">Sobre Nós</a>
                <a href="<?= $link_contato; ?>">Contacto</a>
            </nav>

            <div style="display:flex;align-items:center;gap:12px;">
                <!-- SELETOR DE LÍNGUA -->
                <div class="lang-selector" id="langSelector">
                    <button class="lang-btn" id="langBtn">
                        <img id="langCurrent" src="https://flagcdn.com/w20/pt.png" width="18" alt="PT">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="lang-dropdown" id="langDropdown">
                        <button class="lang-option" onclick="changeLang('pt', 'https://flagcdn.com/w20/pt.png', 'PT')">
                            <img src="https://flagcdn.com/w20/pt.png" width="18"> Português
                        </button>
                        <button class="lang-option" onclick="changeLang('en', 'https://flagcdn.com/w20/gb.png', 'EN')">
                            <img src="https://flagcdn.com/w20/gb.png" width="18"> English
                        </button>
                        <button class="lang-option" onclick="changeLang('es', 'https://flagcdn.com/w20/es.png', 'ES')">
                            <img src="https://flagcdn.com/w20/es.png" width="18"> Español
                        </button>
                        <button class="lang-option" onclick="changeLang('fr', 'https://flagcdn.com/w20/fr.png', 'FR')">
                            <img src="https://flagcdn.com/w20/fr.png" width="18"> Français
                        </button>
                    </div>
                </div>

                <!-- HAMBÚRGUER -->
                <button class="hamburger" id="hamburger" aria-label="Menu">
                    <span></span><span></span><span></span>
                </button>
            </div>

        </div>
    </header>

    <!-- MENU MOBILE -->
    <nav class="mobile-menu" id="mobileMenu">
        <?php if (!empty($servicos)): ?>
            <a href="<?= $link_servicos; ?>"
               onclick="closeMobile()">Serviços</a>
        <?php endif; ?>
        <?php if (!empty($portfolio)): ?>
            <a href="<?= $link_portfolio; ?>"
               onclick="closeMobile()">Portfólio</a>
        <?php endif; ?>
        <a href="<?= $link_sobre; ?>"
           onclick="closeMobile()">Sobre Nós</a>
        <a href="<?= $link_contato; ?>"
           onclick="closeMobile()">Contacto</a>
    </nav>

    <script>
        // ── Hambúrguer ──
        const hamburger  = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobileMenu');

        hamburger.addEventListener('click', function(e) {
            e.stopPropagation();
            hamburger.classList.toggle('open');
            mobileMenu.classList.toggle('open');
        });

        function closeMobile() {
            hamburger.classList.remove('open');
            mobileMenu.classList.remove('open');
        }

        document.addEventListener('click', function(e) {
            if (!mobileMenu.contains(e.target) && !hamburger.contains(e.target)) {
                closeMobile();
            }
        });

        // ── Tradutor MyMemory ──
        let originalTexts = [];
        let currentLang = 'pt';

        function getTextNodes() {
            const skip = ['SCRIPT','STYLE','NOSCRIPT','IFRAME','INPUT','TEXTAREA','SELECT','BUTTON'];
            const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, {
                acceptNode: function(node) {
                    if (!node.parentElement) return NodeFilter.FILTER_REJECT;
                    if (skip.includes(node.parentElement.tagName)) return NodeFilter.FILTER_REJECT;
                    if (node.parentElement.closest('#langSelector')) return NodeFilter.FILTER_REJECT;
                    if (!node.textContent.trim()) return NodeFilter.FILTER_REJECT;
                    return NodeFilter.FILTER_ACCEPT;
                }
            });
            const nodes = [];
            while (walker.nextNode()) nodes.push(walker.currentNode);
            return nodes;
        }

        async function translateBatch(texts, toLang) {
            const results = [];
            for (let i = 0; i < texts.length; i += 10) {
                const batch = texts.slice(i, i + 10);
                const promises = batch.map(text => {
                    const url = 'https://api.mymemory.translated.net/get?q=' +
                        encodeURIComponent(text.trim()) + '&langpair=pt|' + toLang;
                    return fetch(url)
                        .then(r => r.json())
                        .then(d => d.responseData?.translatedText || text)
                        .catch(() => text);
                });
                const batchResults = await Promise.all(promises);
                results.push(...batchResults);
            }
            return results;
        }

        async function changeLang(lang, flag, code) {
            if (lang === currentLang) return;
            document.getElementById('langCurrent').src = flag;
            document.getElementById('langDropdown').classList.remove('open');
            const btn = document.getElementById('langBtn');
            btn.style.opacity = '0.6';
            btn.style.pointerEvents = 'none';
            if (lang === 'pt') {
                const nodes = getTextNodes();
                nodes.forEach((node, i) => {
                    if (originalTexts[i] !== undefined) node.textContent = originalTexts[i];
                });
                originalTexts = [];
                currentLang = 'pt';
            } else {
                const nodes = getTextNodes();
                if (originalTexts.length === 0) {
                    originalTexts = nodes.map(n => n.textContent);
                } else {
                    nodes.forEach((node, i) => {
                        if (originalTexts[i] !== undefined) node.textContent = originalTexts[i];
                    });
                }
                const fresh = getTextNodes();
                const texts = fresh.map(n => n.textContent);
                const translated = await translateBatch(texts, lang);
                fresh.forEach((node, i) => { node.textContent = translated[i]; });
                currentLang = lang;
            }
            btn.style.opacity = '1';
            btn.style.pointerEvents = 'auto';
        }

        document.getElementById('langBtn').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('langDropdown').classList.toggle('open');
        });

        document.addEventListener('click', function() {
            document.getElementById('langDropdown').classList.remove('open');
        });
    </script>