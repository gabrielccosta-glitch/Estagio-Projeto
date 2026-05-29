```php
<?php
$is_tenant = (basename(__DIR__) !== 'freebox');

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

    $link_home = "../freebox/?url=" . $url_param;
    $link_servicos = "../freebox/?url=" . $url_param . "#servicos";
    $link_portfolio = "../freebox/?url=" . $url_param . "#portfolio";
    $link_sobre = "../freebox/?url=" . $url_param . "#sobre";
    $link_contato = "../freebox/contato.php?url=" . $url_param;
    $link_formulario = "../freebox/formulario.php?url=" . $url_param;
    $link_politica = "../freebox/" . $url_param . "/politica-privacidade";
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($nome_empresa ?? 'Nome da Empresa'); ?></title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- GOOGLE FONT: POPPINS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="../css/site_publico.css">

    <?php
    $cor_primaria = $website['cor_primaria'] ?? '#1a1a1a';
    $cor_secundaria = $website['cor_secundaria'] ?? '#555555';

    if (!function_exists('isColorLight')) {
        function isColorLight($hex)
        {
            $hex = ltrim($hex, '#');

            if (strlen($hex) == 3) {
                $r = hexdec($hex[0] . $hex[0]);
                $g = hexdec($hex[1] . $hex[1]);
                $b = hexdec($hex[2] . $hex[2]);
            } elseif (strlen($hex) == 6) {
                $r = hexdec(substr($hex, 0, 2));
                $g = hexdec(substr($hex, 2, 2));
                $b = hexdec(substr($hex, 4, 2));
            } else {
                return false;
            }

            $brightness = sqrt(
                $r * $r * 0.299 +
                $g * $g * 0.587 +
                $b * $b * 0.114
            );

            return $brightness > 170;
        }
    }

    $is_light = isColorLight($cor_primaria);
    $is_sec_light = isColorLight($cor_secundaria);

    $footer_text = $is_light ? '#475569' : 'rgba(255,255,255,0.65)';
    $footer_title = $is_light ? '#0f172a' : '#ffffff';
    $footer_border = $is_light ? 'rgba(0,0,0,0.08)' : 'rgba(255,255,255,0.08)';
    $footer_muted = $is_light ? '#64748b' : 'rgba(255,255,255,0.45)';

    $primary_text = $is_light ? '#0f172a' : '#ffffff';
    $secondary_text = $is_sec_light ? '#0f172a' : '#ffffff';
    ?>

    <style>
        :root {
            --primary:
                <?= htmlspecialchars($cor_primaria); ?>
            ;
            --secondary:
                <?= htmlspecialchars($cor_secundaria); ?>
            ;

            --footer-text:
                <?= $footer_text; ?>
            ;
            --footer-title:
                <?= $footer_title; ?>
            ;
            --footer-border:
                <?= $footer_border; ?>
            ;
            --footer-muted:
                <?= $footer_muted; ?>
            ;

            --primary-text:
                <?= $primary_text; ?>
            ;
            --secondary-text:
                <?= $secondary_text; ?>
            ;
        }

        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            color: #334155;
        }

        .nav-links a {
            text-decoration: none;
            color: #64748b;

            font-family: 'Inter', sans-serif;
            font-weight: 500;
            font-size: 0.95rem;
            letter-spacing: -0.01em;

            transition: all 0.2s ease;
        }

        .nav-links a:hover {
            color: #0f172a;
        }

        .carousel-outer {
            position: relative;
            padding: 0 48px;
        }

        .nav-links a {
            font-family: 'Poppins', sans-serif !important;
        }

        .carousel-wrapper {
            overflow: hidden;
        }

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

            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);

            display: flex;
            align-items: center;
            justify-content: center;

            cursor: pointer;
            z-index: 10;

            font-size: 0.9rem;

            transition: box-shadow 0.2s;
        }

        .carousel-btn:hover {
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.18);
        }

        .carousel-btn.prev {
            left: 0;
        }

        .carousel-btn.next {
            right: 0;
        }

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
            border: none;

            background: #ccc;

            padding: 0;

            cursor: pointer;

            transition: background 0.2s, transform 0.2s;
        }

        .carousel-dots .dot.active {
            background: currentColor;
            transform: scale(1.4);
        }

        /* NAVBAR */

        .public-navbar {
            position: sticky;
            top: 0;
            z-index: 1000;

            background: white;

            box-shadow: 0 1px 8px rgba(0, 0, 0, 0.07);

            padding: 0 20px;

            height: 60px;

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
            height: 40px;
            object-fit: contain;
        }

        .brand-name {
            font-size: 1rem;
            font-weight: 600;
            color: #0f172a;
            letter-spacing: -0.02em;
            font-family: 'Inter', sans-serif;
        }

        .nav-links {
            display: flex;
            gap: 28px;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #64748b;
        }

        .nav-links a:hover {
            color: #1a3a5c;
        }

        /* HAMBURGER */

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

        .hamburger.open span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .hamburger.open span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.open span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        .mobile-menu {
            display: none;

            position: fixed;

            top: 60px;
            left: 0;

            width: 100%;

            background: white;

            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);

            z-index: 999;

            padding: 16px 24px 20px;

            flex-direction: column;
            gap: 0;
        }

        .mobile-menu.open {
            display: flex;
        }

        .mobile-menu a {
            text-decoration: none;

            color: #333;

            font-size: 0.95rem;

            padding: 12px 0;

            border-bottom: 1px solid #f0f0f0;

            transition: color 0.2s;
        }

        .mobile-menu a:last-child {
            border-bottom: none;
        }

        .mobile-menu a:hover {
            color: #1a3a5c;
        }

        /* LANG */

        .lang-selector {
            position: relative;
        }

        .lang-btn {
            display: flex;
            align-items: center;
            gap: 6px;

            background: none;

            border: 1px solid #ddd;
            border-radius: 7px;

            padding: 5px 10px;

            font-size: 0.82rem;
            font-weight: 500;

            color: #444;

            cursor: pointer;

            transition: border-color 0.2s, color 0.2s;

            white-space: nowrap;
        }

        .lang-btn:hover {
            border-color: #1a3a5c;
            color: #1a3a5c;
        }

        .lang-btn i {
            font-size: 0.65rem;
        }

        .lang-dropdown {
            display: none;

            position: absolute;

            right: 0;
            top: calc(100% + 8px);

            background: white;

            border: 1px solid #eee;
            border-radius: 8px;

            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);

            min-width: 140px;

            overflow: hidden;

            z-index: 9999;
        }

        .lang-dropdown.open {
            display: block;
        }

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

        .lang-option:hover {
            background: #f4f7fb;
            color: #1a3a5c;
        }

        /* RESPONSIVO */

        @media (max-width: 1024px) {
            .nav-links {
                display: none;
            }

            .hamburger {
                display: flex;
            }
        }

        @media (max-width: 1024px) {
            .carousel-page {
                grid-template-columns: repeat(2, 1fr);
            }

            .carousel-outer {
                padding: 0 36px;
            }
        }

        @media (max-width: 480px) {
            .carousel-page {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
```