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

$is_admin = isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['adicionar_portfolio'])) {

    $descricao_imagem = $_POST['descricao_imagem'] ?? '';
    $titulo           = $_POST['titulo'] ?? '';

    if (isset($_FILES['portfolio_imagem']) && $_FILES['portfolio_imagem']['error'] == UPLOAD_ERR_OK) {

        $allowed   = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = mime_content_type($_FILES['portfolio_imagem']['tmp_name']);

        if (!in_array($file_type, $allowed)) {
            $_SESSION['error_message'] = "Apenas imagens JPG, PNG, GIF ou WEBP são permitidas.";
            header("Location: empresa_portfolio.php?id=$empresa_id&show_message=1");
            exit();
        }

        if ($_FILES['portfolio_imagem']['size'] > 5 * 1024 * 1024) {
            $_SESSION['error_message'] = "O ficheiro não pode ter mais de 5MB.";
            header("Location: empresa_portfolio.php?id=$empresa_id&show_message=1");
            exit();
        }

        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/imagens/' . $empresa_id . '/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $filename      = uniqid() . '.webp';
        $uploaded_file = '/imagens/' . $empresa_id . '/' . $filename;

        if (guardarImagemWebp($_FILES['portfolio_imagem']['tmp_name'], $upload_dir . $filename, $file_type, 82)) {
            $insert_sql  = "INSERT INTO portfolio (empresa_id, imagem, titulo, descricao_imagem) VALUES (?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("isss", $empresa_id, $uploaded_file, $titulo, $descricao_imagem);

            if ($insert_stmt->execute()) {
                $_SESSION['success_message'] = "Imagem adicionada com sucesso!";
            } else {
                $_SESSION['error_message'] = "Erro ao guardar no banco de dados.";
            }
            $insert_stmt->close();
        } else {
            $_SESSION['error_message'] = "Erro ao fazer upload.";
        }
    } else {
        $_SESSION['error_message'] = "Erro no upload da imagem.";
    }

    header("Location: empresa_portfolio.php?id=$empresa_id&show_message=1");
    exit();
}

$portfolio_sql  = "SELECT * FROM portfolio WHERE empresa_id = ?";
$portfolio_stmt = $conn->prepare($portfolio_sql);
$portfolio_stmt->bind_param("i", $empresa_id);
$portfolio_stmt->execute();
$portfolio_result = $portfolio_stmt->get_result();
$portfolio_items  = $portfolio_result->fetch_all(MYSQLI_ASSOC);
$portfolio_stmt->close();

include '../includes/header.php';

if ($is_admin) {
    include '../admin/header_admin.php';
} else {
    include __DIR__ . '/header_cliente.php';
}
?>

<link rel="stylesheet" href="../css/empresa_portfolio.css">

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

                    <div class="text-center">
                        <h4>Portfólio</h4>
                    </div>

                    <div class="mt-4">
                        <button id="mostrarFormularioPortfolio" class="btn btn-success">
                            Adicionar Imagem
                        </button>
                    </div>

                    <!-- FORM -->
                    <div class="card mt-4" id="formularioPortfolio" style="display:none;">
                        <div class="card-body">
                            <h5>Nova Imagem</h5>
                            <form method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Imagem</label>
                                    <input type="file" name="portfolio_imagem" class="form-control" required>
                                </div>
                                <div class="form-group mt-3">
                                    <label>Título</label>
                                    <input type="text" name="titulo" class="form-control">
                                </div>
                                <div class="form-group mt-3">
                                    <label>Descrição</label>
                                    <textarea name="descricao_imagem" class="form-control"></textarea>
                                </div>
                                <div class="button-container_left mt-4">
                                    <button type="button" id="cancelarFormularioPortfolio" class="btn btn-secondary">
                                        Cancelar
                                    </button>
                                    <button type="submit" name="adicionar_portfolio" class="btn btn-success">
                                        Guardar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- GALERIA -->
                    <?php if (count($portfolio_items) > 0): ?>
                        <?php
                        $perPage    = 5;
                        $totalItems = count($portfolio_items);
                        $totalPages = ceil($totalItems / $perPage);
                        ?>

                        <div class="portfolio-list mt-4" id="portfolioList">
                            <?php foreach ($portfolio_items as $i => $p): ?>
                                <div class="portfolio-list-item" data-index="<?= $i ?>">
                                    <div class="portfolio-list-img-wrap">
                                        <img src="<?= htmlspecialchars($p['imagem']); ?>"
                                             alt="<?= htmlspecialchars($p['titulo']); ?>"
                                             onclick="openLightbox(<?= $i ?>)">
                                    </div>
                                    <div class="portfolio-list-info">
                                        <div class="portfolio-list-text">
                                            <p class="portfolio-list-titulo"><?= htmlspecialchars($p['titulo']); ?></p>
                                            <p class="portfolio-list-desc"><?= htmlspecialchars($p['descricao_imagem']); ?></p>
                                        </div>
                                        <div class="portfolio-list-actions">
                                            <a href="editar_portfolio.php?id=<?= $p['id']; ?>" class="btn btn-success btn-sm">Editar</a>
                                            <a href="eliminar_portfolio.php?id=<?= $p['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if ($totalPages > 1): ?>
                            <div class="portfolio-pagination mt-3" id="portfolioPagination">
                                <button class="btn btn-outline-secondary btn-sm" id="pagePrev">&#8592; Anterior</button>
                                <span id="pageInfo"></span>
                                <button class="btn btn-outline-secondary btn-sm" id="pageNext">Seguinte &#8594;</button>
                            </div>
                        <?php endif; ?>

                    <?php else: ?>
                        <p class="text-muted mt-4 text-center">Ainda não há imagens no portfólio.</p>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- LIGHTBOX -->
<div class="lightbox-overlay" id="lightboxOverlay">
    <button class="lightbox-close" id="lightboxClose">&times;</button>
    <button class="lightbox-arrow left" id="lightboxPrev">&#8592;</button>
    <img class="lightbox-img" id="lightboxImg" src="" alt="">
    <button class="lightbox-arrow right" id="lightboxNext">&#8594;</button>
    <div class="lightbox-desc" id="lightboxDesc"></div>
</div>

<!-- MODAL MENSAGENS -->
<div id="messageModal" class="modal">
    <div class="modal-content">
        <h4 id="modalTitle"></h4>
        <p id="modalMessage"></p>
        <button id="okButton" class="btn btn-success">OK</button>
    </div>
</div>

<script>
    const portfolioImages = <?= json_encode(array_map(function ($p) {
        return ['src' => $p['imagem'], 'desc' => $p['descricao_imagem'], 'titulo' => $p['titulo']];
    }, $portfolio_items)); ?>;

    document.getElementById('mostrarFormularioPortfolio').onclick = () =>
        document.getElementById('formularioPortfolio').style.display = 'block';

    document.getElementById('cancelarFormularioPortfolio').onclick = () =>
        document.getElementById('formularioPortfolio').style.display = 'none';

    let currentIndex = 0;

    function openLightbox(index) {
        currentIndex = index;
        updateLightbox();
        document.getElementById('lightboxOverlay').classList.add('open');
    }

    function updateLightbox() {
        document.getElementById('lightboxImg').src         = portfolioImages[currentIndex].src;
        document.getElementById('lightboxDesc').textContent = portfolioImages[currentIndex].titulo
            + (portfolioImages[currentIndex].desc ? ' — ' + portfolioImages[currentIndex].desc : '');
    }

    document.getElementById('lightboxClose').onclick = () =>
        document.getElementById('lightboxOverlay').classList.remove('open');

    document.getElementById('lightboxPrev').onclick = () => {
        currentIndex = (currentIndex - 1 + portfolioImages.length) % portfolioImages.length;
        updateLightbox();
    };

    document.getElementById('lightboxNext').onclick = () => {
        currentIndex = (currentIndex + 1) % portfolioImages.length;
        updateLightbox();
    };

    document.addEventListener('keydown', function(e) {
        const overlay = document.getElementById('lightboxOverlay');
        if (!overlay.classList.contains('open')) return;
        if (e.key === 'ArrowLeft') {
            currentIndex = (currentIndex - 1 + portfolioImages.length) % portfolioImages.length;
            updateLightbox();
        }
        if (e.key === 'ArrowRight') {
            currentIndex = (currentIndex + 1) % portfolioImages.length;
            updateLightbox();
        }
        if (e.key === 'Escape') overlay.classList.remove('open');
    });

    document.getElementById('lightboxOverlay').addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('open');
    });

    const listItems = document.querySelectorAll('.portfolio-list-item');
    if (listItems.length > 0) {
        const perPage  = 5;
        const total    = listItems.length;
        const totalPgs = Math.ceil(total / perPage);
        let currentPg  = 0;

        function showPage(page) {
            currentPg = page;
            listItems.forEach((item, i) => {
                item.style.display = (i >= page * perPage && i < (page + 1) * perPage) ? 'flex' : 'none';
            });
            const info = document.getElementById('pageInfo');
            if (info) info.textContent = `Página ${page + 1} de ${totalPgs}`;
            const prev = document.getElementById('pagePrev');
            const next = document.getElementById('pageNext');
            if (prev) prev.disabled = page === 0;
            if (next) next.disabled = page === totalPgs - 1;
        }

        const prevBtn = document.getElementById('pagePrev');
        const nextBtn = document.getElementById('pageNext');
        if (prevBtn) prevBtn.onclick = () => showPage(currentPg - 1);
        if (nextBtn) nextBtn.onclick = () => showPage(currentPg + 1);

        showPage(0);
    }

    document.addEventListener("DOMContentLoaded", function() {
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

            okBtn.onclick = function() {
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