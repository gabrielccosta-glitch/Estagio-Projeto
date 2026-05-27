<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

if (isset($_GET['id'])) {
    $empresa_id = intval($_GET['id']);
} else {
    header("Location: ../admin/dashboard.php");
    exit();
}

$sql = "SELECT * FROM empresas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $empresa_id);
$stmt->execute();
$result = $stmt->get_result();
$empresa = $result->fetch_assoc();
$stmt->close();

if (!$empresa) {
    header("Location: ../admin/dashboard.php");
    exit();
}

// ── Nome da pasta baseado no nome da empresa ──
function gerarNomePasta($nome) {
    $nome = mb_strtolower($nome, 'UTF-8');
    $nome = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $nome);
    $nome = preg_replace('/[^a-z0-9]+/', '-', $nome);
    $nome = trim($nome, '-');
    return $nome ?: 'empresa';
}
$nome_pasta = gerarNomePasta($empresa['nome_empresa']);

$is_admin = isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin';

$website_sql = "SELECT * FROM website_config WHERE empresa_id = ?";
$website_stmt = $conn->prepare($website_sql);
$website_stmt->bind_param("i", $empresa_id);
$website_stmt->execute();
$website = $website_stmt->get_result()->fetch_assoc();
$website_stmt->close();

$old_url_site = $website['url_site'] ?? '';

$url_ja_definido          = !empty($website['url_site']);
$logotipo_ja_definido     = !empty($website['logotipo']);
$botao_texto_ja_definido  = !empty($website['hero_botao_texto']);
$botao_link_ja_definido   = !empty($website['hero_botao_link']);
$email_ja_definido        = !empty($website['email_formulario']);

$cliente_pode_definir_url         = !$is_admin && !$url_ja_definido;
$cliente_pode_definir_logotipo    = !$is_admin && !$logotipo_ja_definido;
$cliente_pode_definir_botao_texto = !$is_admin && !$botao_texto_ja_definido;
$cliente_pode_definir_botao_link  = !$is_admin && !$botao_link_ja_definido;
$cliente_pode_definir_email       = !$is_admin && !$email_ja_definido;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $descricao_empresa = trim($_POST['descricao_empresa'] ?? '');
    $link_facebook     = trim($_POST['link_facebook'] ?? '');
    $link_instagram    = trim($_POST['link_instagram'] ?? '');
    $link_x            = trim($_POST['link_x'] ?? '');
    $hero_titulo       = trim($_POST['hero_titulo'] ?? '');
    $hero_subtitulo    = trim($_POST['hero_subtitulo'] ?? '');
    $cor_primaria      = trim($_POST['cor_primaria'] ?? '#1a1a1a');
    $cor_secundaria    = trim($_POST['cor_secundaria'] ?? '#555555');

    // URL do site
    if ($is_admin) {
        $url_site = trim($_POST['url_site'] ?? '');
        $url_site = strtolower(preg_replace('/[^a-zA-Z0-9\-]/', '', $url_site));
    } elseif ($cliente_pode_definir_url) {
        $url_site = trim($_POST['url_site'] ?? '');
        $url_site = strtolower(preg_replace('/[^a-zA-Z0-9\-]/', '', $url_site));
    } else {
        $url_site = $website['url_site'] ?? '';
    }

    // ── Logotipo — pasta por nome da empresa ──
    $logotipo = $website['logotipo'] ?? '';
    if ($is_admin || $cliente_pode_definir_logotipo) {
        if (!empty($_FILES['logotipo']['tmp_name'])) {
            $allowed   = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $file_type = mime_content_type($_FILES['logotipo']['tmp_name']);
            if (in_array($file_type, $allowed) && $_FILES['logotipo']['size'] <= 2 * 1024 * 1024) {
                $upload_dir = '../imagens/' . $nome_pasta . '/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                $ext      = pathinfo($_FILES['logotipo']['name'], PATHINFO_EXTENSION);
                $logotipo = '../imagens/' . $nome_pasta . '/logotipo.' . $ext;
                move_uploaded_file($_FILES['logotipo']['tmp_name'], $upload_dir . 'logotipo.' . $ext);
            } else {
                $_SESSION['error_message'] = "Logotipo inválido. Use JPG, PNG, GIF ou WEBP até 2MB.";
                header("Location: empresa_website.php?id=$empresa_id&show_message=1");
                exit();
            }
        }
    }

    // Texto do botão
    if ($is_admin || $cliente_pode_definir_botao_texto) {
        $hero_botao_texto = trim($_POST['hero_botao_texto'] ?? '');
    } else {
        $hero_botao_texto = $website['hero_botao_texto'] ?? '';
    }

    // Link do botão
    if ($is_admin || $cliente_pode_definir_botao_link) {
        $hero_botao_link = trim($_POST['hero_botao_link'] ?? '');
    } else {
        $hero_botao_link = $website['hero_botao_link'] ?? '';
    }

    // Email
    if ($is_admin || $cliente_pode_definir_email) {
        $email_formulario = trim($_POST['email_formulario'] ?? '');
    } else {
        $email_formulario = $website['email_formulario'] ?? '';
    }

    // ── Capa — pasta por nome da empresa ──
    $capa_empresa = $website['capa_empresa'] ?? '';
    if (!empty($_FILES['capa_empresa']['tmp_name'])) {
        $allowed   = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = mime_content_type($_FILES['capa_empresa']['tmp_name']);
        if (in_array($file_type, $allowed) && $_FILES['capa_empresa']['size'] <= 5 * 1024 * 1024) {
            $upload_dir = '../imagens/' . $nome_pasta . '/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $ext          = pathinfo($_FILES['capa_empresa']['name'], PATHINFO_EXTENSION);
            $capa_empresa = '../imagens/' . $nome_pasta . '/capa.' . $ext;
            move_uploaded_file($_FILES['capa_empresa']['tmp_name'], $upload_dir . 'capa.' . $ext);
        } else {
            $_SESSION['error_message'] = "Capa inválida. Use JPG, PNG, GIF ou WEBP até 5MB.";
            header("Location: empresa_website.php?id=$empresa_id&show_message=1");
            exit();
        }
    }

    // Verificar URL duplicado
    if (!empty($url_site) && ($is_admin || $cliente_pode_definir_url)) {
        $check_stmt = $conn->prepare("SELECT id FROM website_config WHERE url_site = ? AND empresa_id != ?");
        $check_stmt->bind_param("si", $url_site, $empresa_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $check_stmt->close();
        if ($check_result->num_rows > 0) {
            $_SESSION['error_message'] = "Este endereço já está em uso por outra empresa. Escolha outro.";
            header("Location: empresa_website.php?id=$empresa_id&show_message=1");
            exit();
        }
    }

    $sql = "INSERT INTO website_config 
        (empresa_id, descricao_empresa, logotipo, capa_empresa, hero_titulo, hero_subtitulo, hero_botao_texto, hero_botao_link, link_facebook, link_instagram, link_x, url_site, email_formulario, cor_primaria, cor_secundaria)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
        descricao_empresa = VALUES(descricao_empresa),
        logotipo          = VALUES(logotipo),
        capa_empresa      = VALUES(capa_empresa),
        hero_titulo       = VALUES(hero_titulo),
        hero_subtitulo    = VALUES(hero_subtitulo),
        hero_botao_texto  = VALUES(hero_botao_texto),
        hero_botao_link   = VALUES(hero_botao_link),
        link_facebook     = VALUES(link_facebook),
        link_instagram    = VALUES(link_instagram),
        link_x            = VALUES(link_x),
        url_site          = VALUES(url_site),
        email_formulario  = VALUES(email_formulario),
        cor_primaria      = VALUES(cor_primaria),
        cor_secundaria    = VALUES(cor_secundaria)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssssssssssss",
        $empresa_id, $descricao_empresa, $logotipo, $capa_empresa,
        $hero_titulo, $hero_subtitulo, $hero_botao_texto, $hero_botao_link,
        $link_facebook, $link_instagram, $link_x, $url_site, $email_formulario,
        $cor_primaria, $cor_secundaria
    );

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Guardado com sucesso!";

        // Criar ou renomear pasta do site dinâmico
        if (!empty($url_site)) {
            $projeto_root     = dirname(__DIR__);
            $novo_diretorio   = $projeto_root . '/' . $url_site;
            $diretorio_origem = $projeto_root . '/freebox';

            if (!empty($old_url_site) && $old_url_site !== $url_site) {
                $antigo_diretorio = $projeto_root . '/' . $old_url_site;
                if (is_dir($antigo_diretorio) && $old_url_site !== 'freebox') {
                    rename($antigo_diretorio, $novo_diretorio);
                }
            }

            if (is_dir($diretorio_origem)) {
                copiarDiretorioRecursivo($diretorio_origem, $novo_diretorio);
            }
        }
    } else {
        $_SESSION['error_message'] = "Erro ao guardar: " . $conn->error;
    }

    header("Location: empresa_website.php?id=$empresa_id&show_message=1");
    exit();
}

include '../includes/header.php';

if ($is_admin) {
    include '../admin/header_admin.php';
} else {
    include __DIR__ . '/header_cliente.php';
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="../css/empresa_website.css">

<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0; top: 0;
        width: 100%; height: 100%;
        background-color: rgba(0,0,0,0.4);
    }
    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 500px;
        text-align: center;
    }
    .preview-img {
        max-width: 200px;
        max-height: 100px;
        object-fit: contain;
        margin-top: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 4px;
        display: block;
    }
    .url-group {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 8px;
    }
    .url-prefix {
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 6px 12px;
        white-space: nowrap;
        color: #495057;
    }
    .field-locked {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 8px;
        background: #f0f9f0;
        border: 1px solid #c3e6cb;
        border-radius: 6px;
        padding: 10px 14px;
        color: #2d6a4f;
        font-weight: 600;
    }
    .field-locked i { color: #28a745; }
</style>

<div class="separator"></div>

<div class="container-fluid mt-4">
    <div class="row">

        <!-- MENU -->
        <div class="col-md-3 d-flex justify-content-start">
            <?php if ($is_admin): ?>
                <?php include __DIR__ . '/empresa_menu.php'; ?>
            <?php else: ?>
                <?php include __DIR__ . '/empresa_menu_cliente.php'; ?>
            <?php endif; ?>
        </div>

        <!-- CONTEÚDO -->
        <div class="col-md-9">
            <div class="card custom-card">
                <div class="card-body">

                    <h4 class="text-center mb-4">
                        <i class="fas fa-globe"></i> Website
                    </h4>

                    <form method="POST" enctype="multipart/form-data">

                        <!-- URL DO SITE -->
                        <label class="mt-3"><i class="fas fa-link"></i> Endereço do seu site</label>

                        <?php if ($is_admin): ?>
                            <div class="url-group">
                                <span class="url-prefix">http://freebox/</span>
                                <input type="text" name="url_site" class="form-control"
                                    placeholder="nome-do-seu-site"
                                    value="<?= htmlspecialchars($website['url_site'] ?? '') ?>"
                                    maxlength="100">
                            </div>
                            <small class="text-muted">Apenas letras, números e hífens.</small>
                        <?php elseif ($cliente_pode_definir_url): ?>
                            <div class="url-group">
                                <span class="url-prefix">http://freebox/</span>
                                <input type="text" name="url_site" class="form-control"
                                    placeholder="nome-do-seu-site" value="" maxlength="100">
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-exclamation-triangle text-warning"></i>
                                Atenção: só pode definir este endereço <strong>uma vez</strong>. Após guardar, não poderá alterá-lo.
                            </small>
                        <?php else: ?>
                            <div class="field-locked">
                                <i class="fas fa-lock"></i>
                                <span>http://freebox/<?= htmlspecialchars($website['url_site']) ?></span>
                            </div>
                            <small class="text-muted">O endereço do site já foi definido e não pode ser alterado.</small>
                        <?php endif; ?>

                        <?php if (!empty($website['url_site'])): ?>
                            <div class="mt-2">
                                <a href="<?= htmlspecialchars(obterUrlEmpresa($website['url_site'], '../')); ?>"
                                    target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-external-link-alt"></i> Ver site
                                </a>
                            </div>
                        <?php endif; ?>

                        <!-- LOGOTIPO -->
                        <label class="mt-4"><i class="fas fa-image"></i> Logotipo</label>
                        <?php if ($is_admin || $cliente_pode_definir_logotipo): ?>
                            <input type="file" name="logotipo" class="form-control"
                                accept="image/jpeg,image/png,image/gif,image/webp">
                            <small class="text-muted">
                                JPG, PNG, GIF ou WEBP. Máx. 2MB.
                                <?php if ($cliente_pode_definir_logotipo): ?>
                                    <br><i class="fas fa-exclamation-triangle text-warning"></i>
                                    Atenção: só pode definir o logotipo <strong>uma vez</strong>. Após guardar, não poderá alterá-lo.
                                <?php endif; ?>
                            </small>
                        <?php else: ?>
                            <div class="field-locked">
                                <i class="fas fa-lock"></i>
                                <span>Logotipo já definido — apenas o administrador pode alterar.</span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($website['logotipo'])): ?>
                            <img src="<?= htmlspecialchars($website['logotipo']); ?>"
                                alt="Logotipo atual" class="preview-img mt-2">
                        <?php endif; ?>

                        <!-- CAPA -->
                        <label class="mt-4"><i class="fas fa-panorama"></i> Capa</label>
                        <input type="file" name="capa_empresa" class="form-control"
                            accept="image/jpeg,image/png,image/gif,image/webp">
                        <small class="text-muted">JPG, PNG, GIF ou WEBP. Máx. 5MB.</small>
                        <?php if (!empty($website['capa_empresa'])): ?>
                            <img src="<?= htmlspecialchars($website['capa_empresa']); ?>"
                                alt="Capa atual" class="preview-img mt-2">
                        <?php endif; ?>

                        <!-- CORES DO SITE -->
                        <hr class="mt-4">
                        <h6 class="mt-3 mb-3"><i class="fas fa-palette"></i> Cores do Website</h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <label><i class="fas fa-circle text-primary"></i> Cor Primária</label>
                                <input type="color" name="cor_primaria" class="form-control form-control-color w-100" 
                                    value="<?= htmlspecialchars($website['cor_primaria'] ?? '#1a1a1a') ?>" 
                                    style="height: 48px; padding: 4px; cursor: pointer;"
                                    title="Escolha a cor primária">
                                
                            </div>
                            <div class="col-md-6">
                                <label><i class="fas fa-circle text-secondary"></i> Cor Secundária</label>
                                <input type="color" name="cor_secundaria" class="form-control form-control-color w-100" 
                                    value="<?= htmlspecialchars($website['cor_secundaria'] ?? '#555555') ?>" 
                                    style="height: 48px; padding: 4px; cursor: pointer;"
                                    title="Escolha a cor secundária">
                                
                            </div>
                        </div>

                        <!-- HERO TEXTO -->
                        <hr class="mt-4">
                        <h6 class="mt-3 mb-3"><i class="fas fa-image"></i> Texto em cima da imagem (opcional)</h6>

                        <label><i class="fas fa-heading"></i> Título</label>
                        <input type="text" name="hero_titulo" class="form-control"
                            placeholder="Ex: Bem-vindo à nossa empresa"
                            value="<?= htmlspecialchars($website['hero_titulo'] ?? '') ?>"
                            maxlength="255">

                        <label class="mt-3"><i class="fas fa-font"></i> Subtítulo</label>
                        <input type="text" name="hero_subtitulo" class="form-control"
                            placeholder="Ex: Qualidade e confiança desde 2010"
                            value="<?= htmlspecialchars($website['hero_subtitulo'] ?? '') ?>"
                            maxlength="255">

                        <!-- TEXTO DO BOTÃO -->
                        <label class="mt-3"><i class="fas fa-mouse-pointer"></i> Texto do botão</label>
                        <?php if ($is_admin || $cliente_pode_definir_botao_texto): ?>
                            <input type="text" name="hero_botao_texto" class="form-control"
                                placeholder="Ex: Contacte-nos"
                                value="<?= htmlspecialchars($website['hero_botao_texto'] ?? '') ?>"
                                maxlength="100">
                            <?php if ($cliente_pode_definir_botao_texto): ?>
                                <small class="text-muted">
                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                    Atenção: só pode definir o texto do botão <strong>uma vez</strong>.
                                </small>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="field-locked">
                                <i class="fas fa-lock"></i>
                                <span><?= htmlspecialchars($website['hero_botao_texto']) ?></span>
                            </div>
                            <small class="text-muted">Apenas o administrador pode alterar.</small>
                        <?php endif; ?>

                        <!-- LINK DO BOTÃO -->
                        <label class="mt-3"><i class="fas fa-link"></i> Link do botão</label>
                        <?php if ($is_admin || $cliente_pode_definir_botao_link): ?>
                            <input type="text" name="hero_botao_link" class="form-control"
                                placeholder="Ex: https://... ou #contactos"
                                value="<?= htmlspecialchars($website['hero_botao_link'] ?? '') ?>"
                                maxlength="255">
                            <?php if ($cliente_pode_definir_botao_link): ?>
                                <small class="text-muted">
                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                    Atenção: só pode definir o link do botão <strong>uma vez</strong>.
                                </small>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="field-locked">
                                <i class="fas fa-lock"></i>
                                <span><?= htmlspecialchars($website['hero_botao_link']) ?></span>
                            </div>
                            <small class="text-muted">Apenas o administrador pode alterar.</small>
                        <?php endif; ?>

                        <small class="text-muted d-block mt-1">Se deixar tudo em branco, a imagem fica limpa sem texto.</small>
                        <hr>

                        <!-- DESCRIÇÃO -->
                        <label class="mt-3"><i class="fas fa-align-left"></i> Descrição (Sobre Nós)</label>
                        <textarea name="descricao_empresa" class="form-control" rows="4"><?= htmlspecialchars($website['descricao_empresa'] ?? '') ?></textarea>

                        <!-- EMAIL FORMULÁRIO -->
                        <label class="mt-4"><i class="fas fa-envelope"></i> Email para receber formulários do site</label>
                        <?php if ($is_admin || $cliente_pode_definir_email): ?>
                            <input type="email" name="email_formulario" class="form-control"
                                placeholder="empresa@email.com"
                                value="<?= htmlspecialchars($website['email_formulario'] ?? '') ?>">
                            <?php if ($cliente_pode_definir_email): ?>
                                <small class="text-muted">
                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                    Atenção: só pode definir o email <strong>uma vez</strong>. Após guardar, não poderá alterá-lo.
                                </small>
                            <?php else: ?>
                                <small class="text-muted">O formulário do site vai enviar as mensagens para este email.</small>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="field-locked">
                                <i class="fas fa-lock"></i>
                                <span><?= htmlspecialchars($website['email_formulario']) ?></span>
                            </div>
                            <small class="text-muted">Apenas o administrador pode alterar este email.</small>
                        <?php endif; ?>

                        <!-- REDES SOCIAIS -->
                        <label class="mt-4"><i class="fab fa-facebook"></i> Facebook</label>
                        <input type="url" name="link_facebook" class="form-control"
                            placeholder="https://facebook.com/suaempresa"
                            value="<?= htmlspecialchars($website['link_facebook'] ?? '') ?>">

                        <label class="mt-3"><i class="fab fa-instagram"></i> Instagram</label>
                        <input type="url" name="link_instagram" class="form-control"
                            placeholder="https://instagram.com/suaempresa"
                            value="<?= htmlspecialchars($website['link_instagram'] ?? '') ?>">

                        <label class="mt-3"><i class="fab fa-x-twitter"></i> X (Twitter)</label>
                        <input type="url" name="link_x" class="form-control"
                            placeholder="https://x.com/suaempresa"
                            value="<?= htmlspecialchars($website['link_x'] ?? '') ?>">

                        <button type="submit" class="btn btn-success mt-4">
                            Guardar
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL -->
<div id="messageModal" class="modal">
    <div class="modal-content">
        <h4 id="modalTitle"></h4>
        <p id="modalMessage"></p>
        <button id="okButton" class="btn btn-success">OK</button>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get("show_message") === "1") {
            const modal   = document.getElementById("messageModal");
            const title   = document.getElementById("modalTitle");
            const message = document.getElementById("modalMessage");
            const okBtn   = document.getElementById("okButton");

            <?php if (isset($_SESSION['success_message'])): ?>
                title.textContent   = "Sucesso";
                message.textContent = "<?= htmlspecialchars($_SESSION['success_message'], ENT_QUOTES); ?>";
                <?php unset($_SESSION['success_message']); ?>
            <?php elseif (isset($_SESSION['error_message'])): ?>
                title.textContent   = "Erro";
                message.textContent = "<?= htmlspecialchars($_SESSION['error_message'], ENT_QUOTES); ?>";
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            modal.style.display = "block";

            okBtn.onclick = function () {
                modal.style.display = "none";
                window.history.replaceState({}, document.title,
                    window.location.pathname + "?id=<?= $empresa_id ?>");
            };
        }
    });
</script>

<?php
if ($is_admin) {
    include '../admin/footer_admin.php';
} else {
    include __DIR__ . '/footer_cliente.php';
}
?>