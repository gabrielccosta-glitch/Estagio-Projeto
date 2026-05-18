<?php
require_once '../config/database.php';

if (!isset($_GET['url'])) {
    header("Location: ../index.php");
    exit();
}

$url_site = preg_replace('/[^a-zA-Z0-9\-]/', '', $_GET['url']);

$website_stmt = $conn->prepare("SELECT * FROM website_config WHERE url_site = ?");
$website_stmt->bind_param("s", $url_site);
$website_stmt->execute();
$website = $website_stmt->get_result()->fetch_assoc();
$website_stmt->close();

if (!$website) {
    header("Location: ../index.php");
    exit();
}

$empresa_id = $website['empresa_id'];

$stmt = $conn->prepare("SELECT * FROM empresas WHERE id = ?");
$stmt->bind_param("i", $empresa_id);
$stmt->execute();
$empresa = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$empresa) {
    header("Location: ../index.php");
    exit();
}

$servicos_stmt = $conn->prepare("SELECT * FROM servicos WHERE empresa_id = ?");
$servicos_stmt->bind_param("i", $empresa_id);
$servicos_stmt->execute();
$servicos = $servicos_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$servicos_stmt->close();

$portfolio_stmt = $conn->prepare("SELECT * FROM portfolio WHERE empresa_id = ?");
$portfolio_stmt->bind_param("i", $empresa_id);
$portfolio_stmt->execute();
$portfolio = $portfolio_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$portfolio_stmt->close();

$nome_empresa       = $empresa['nome_empresa'] ?? 'Empresa';
$descricao          = trim($website['descricao_empresa'] ?? '');
$logo               = trim($website['logotipo'] ?? '');
$capa               = trim($website['capa_empresa'] ?? '');
$telefone_principal = !empty($empresa['telefone']) ? $empresa['telefone'] : ($empresa['telefone_contato'] ?? '');
$email_principal    = !empty($empresa['email_empresa']) ? $empresa['email_empresa'] : ($empresa['email_contato'] ?? '');
$morada_completa    = trim(($empresa['morada'] ?? '') . ' ' . ($empresa['codigo_postal'] ?? ''));

// Corrige caminhos relativos
if (!empty($logo)) {
    $logo = '/projeto/freebox/' . ltrim(preg_replace('#^\./#', '', $logo), '/');
}
if (!empty($capa)) {
    $capa = '/projeto/freebox/' . ltrim(preg_replace('#^\./#', '', $capa), '/');
}

$hero_style      = !empty($capa) ? "background-image: url('" . htmlspecialchars($capa, ENT_QUOTES) . "');" : '';
$portfolio_bg    = !empty($capa) ? $capa : (!empty($portfolio[0]['imagem']) ? $portfolio[0]['imagem'] : '');
$portfolio_style = !empty($portfolio_bg) ? "background-image: url('" . htmlspecialchars($portfolio_bg, ENT_QUOTES) . "');" : '';

include 'header_publico.php';
?>

<section class="section-padding">
    <div class="container" style="max-width: 1000px;">

        <h1 class="section-title" style="font-size: 2.4rem; letter-spacing: 2px;">Política de Privacidade</h1>
        <div class="section-line"></div>

        <div class="privacy-content">

            <h2>Quem somos</h2>

            <?php
            $link_site = 'http://' . ($_SERVER['HTTP_HOST'] ?? '') . '/projeto/freebox/' . ($website['url_site'] ?? '');
            ?>

            <p>
                O endereço do nosso site é:
                <a href="<?= htmlspecialchars($link_site); ?>" target="_blank">
                    <?= htmlspecialchars($link_site); ?>
                </a>
            </p>

            <h2>Proteção de Dados Pessoais</h2>

            <p>
                A proteção dos seus dados pessoais é muito importante para a
                <strong><?= htmlspecialchars($nome_empresa); ?></strong>.
            </p>

            <p>
                Tratamos os seus dados com responsabilidade e adotamos todas as medidas
                necessárias para garantir a sua segurança e confidencialidade.
            </p>

            <h2>Que dados pessoais são recolhidos</h2>

            <p>
                Apenas recolhemos os dados fornecidos voluntariamente
                através dos formulários deste website.
            </p>

            <ul>
                <li>Nome</li>
                <li>Email</li>
                <li>Telefone</li>
                <li>Mensagem enviada</li>
                <li>Endereço IP</li>
            </ul>

            <h2>Finalidade dos dados</h2>

            <p>Os dados recolhidos são utilizados exclusivamente para:</p>

            <ul>
                <li>Responder a pedidos de contacto</li>
                <li>Comunicação com clientes</li>
                <li>Prestação de serviços</li>
                <li>Melhoria da experiência de navegação</li>
            </ul>

            <h2>Cookies</h2>

            <p>
                Este website poderá utilizar cookies para melhorar
                a experiência do utilizador.
            </p>

            <p>
                Os cookies permitem guardar preferências de navegação
                e recolher dados estatísticos anónimos.
            </p>

            <h2>Conteúdo incorporado</h2>

            <p>
                Algumas páginas podem incluir conteúdos externos,
                como mapas, vídeos ou redes sociais.
            </p>

            <p>
                Esses serviços externos poderão recolher dados
                conforme as respetivas políticas de privacidade.
            </p>

            <h2>Partilha de dados</h2>

            <p>
                A <?= htmlspecialchars($nome_empresa); ?>
                não partilha dados pessoais com terceiros,
                exceto quando exigido por lei.
            </p>

            <h2>Conservação dos dados</h2>

            <p>
                Os dados serão conservados apenas pelo tempo necessário
                para cumprir as finalidades para as quais foram recolhidos.
            </p>

            <h2>Direitos do utilizador</h2>

            <p>O utilizador pode solicitar:</p>

            <ul>
                <li>Acesso aos seus dados</li>
                <li>Retificação dos dados</li>
                <li>Eliminação dos dados</li>
                <li>Limitação do tratamento</li>
                <li>Retirada do consentimento</li>
            </ul>

            <?php if (!empty($email_principal)): ?>
                <p>
                    Para qualquer questão relacionada com os seus dados:
                    <strong><?= htmlspecialchars($email_principal); ?></strong>
                </p>
            <?php endif; ?>

            <h2>Segurança</h2>

            <p>
                Implementamos medidas técnicas e organizativas adequadas
                para proteger os dados pessoais contra acessos não autorizados,
                perda ou divulgação indevida.
            </p>

            <h2>Livro de Reclamações</h2>

            <p>Pode aceder ao Livro de Reclamações Online através do link:</p>

            <p>
                <a href="https://www.livroreclamacoes.pt/Inicio/" target="_blank">
                    https://www.livroreclamacoes.pt/Inicio/
                </a>
            </p>

            <h2>Alterações à política</h2>

            <p>Esta Política de Privacidade pode ser alterada sem aviso prévio.</p>

            <h2>Legislação aplicável</h2>

            <p>
                Esta política é regida pela legislação portuguesa
                e pelo Regulamento Geral de Proteção de Dados (RGPD).
            </p>

        </div>

    </div>
</section>

<?php include 'footer_publico.php'; ?>