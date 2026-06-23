<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!estaLogado()) {
    header("Location: ../login.php");
    exit;
}

include '../includes/header.php';
?>

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- HEADER CLIENTE -->
<?php include __DIR__ . '/header_cliente.php'; ?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="card dashboard-main-card">
                <div class="card-body">

                    <h3 class="mb-1">
                        <i class="fas fa-question-circle me-2"></i>Centro de Ajuda
                    </h3>
                    <p class="text-muted mb-4">
                        Envia-nos a tua dúvida ou problema. Respondemos o mais brevemente possível.
                    </p>

                    <div class="dashboard-divider mb-4"></div>

                    <form id="suporteForm">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user me-2" style="color:#356096;"></i>Nome
                            </label>
                            <input type="text" name="nome" class="form-control"
                                   placeholder="O seu nome"
                                   value="<?= htmlspecialchars($_SESSION['nome'] ?? ''); ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-envelope me-2" style="color:#356096;"></i>Email
                            </label>
                            <input type="email" name="email" class="form-control"
                                   placeholder="O seu email"
                                   value="<?= htmlspecialchars($_SESSION['email'] ?? ''); ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-tag me-2" style="color:#356096;"></i>Assunto
                            </label>
                            <select name="assunto" class="form-select" required>
                                <option value="" disabled selected>Seleciona o assunto</option>
                                <option value="Problema técnico">Problema técnico</option>
                                <option value="Dúvida sobre funcionalidades">Dúvida sobre funcionalidades</option>
                                <option value="Faturação / Licenças">Faturação / Licenças</option>
                                <option value="Sugestão de melhoria">Sugestão de melhoria</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-message me-2" style="color:#356096;"></i>Mensagem
                            </label>
                            <textarea name="mensagem" class="form-control" rows="5"
                                      placeholder="Descreve o teu problema ou dúvida..."
                                      required></textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4" id="btnEnviar">
                                Enviar <i class="fas fa-paper-plane ms-1"></i>
                            </button>
                        </div>

                    </form>

                    <div id="formFeedback" style="display:none; margin-top:16px;"></div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.getElementById('suporteForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form     = this;
    const feedback = document.getElementById('formFeedback');
    const btn      = document.getElementById('btnEnviar');

    btn.disabled   = true;
    btn.innerHTML  = '<i class="fas fa-spinner fa-spin"></i> A enviar...';

    fetch('enviar_suporte.php', {
        method: 'POST',
        body: new FormData(form)
    })
    .then(r => r.json())
    .then(res => {
        feedback.style.display = 'block';
        if (res.success) {
            feedback.style.cssText = 'display:block; padding:12px 16px; border-radius:8px; background:#d1fae5; color:#065f46; font-weight:600;';
            feedback.textContent   = '✓ Mensagem enviada com sucesso! Entraremos em contacto brevemente.';
            form.reset();
        } else {
            feedback.style.cssText = 'display:block; padding:12px 16px; border-radius:8px; background:#fee2e2; color:#991b1b; font-weight:600;';
            feedback.textContent   = '✗ Erro ao enviar. Tenta novamente.';
        }
        btn.disabled  = false;
        btn.innerHTML = 'Enviar <i class="fas fa-paper-plane ms-1"></i>';
    })
    .catch(() => {
        feedback.style.cssText = 'display:block; padding:12px 16px; border-radius:8px; background:#fee2e2; color:#991b1b; font-weight:600;';
        feedback.textContent   = '✗ Erro de ligação. Tenta novamente.';
        btn.disabled  = false;
        btn.innerHTML = 'Enviar <i class="fas fa-paper-plane ms-1"></i>';
    });
});
</script>

<!-- FOOTER CLIENTE -->
<?php include __DIR__ . '/footer_cliente.php'; ?>

<?php include '../includes/footer.php'; ?>