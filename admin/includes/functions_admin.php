<?php
// Outras funções admin...

function gerarScriptEmpresasAdmin() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let empresaIdParaEliminar;
        const modalConfirm = document.getElementById('modalConfirm');
        const nomeEmpresaEliminar = document.getElementById('nomeEmpresaEliminar');
        const confirmText = document.getElementById('confirmText');
        const btnConfirm = document.getElementById('btnConfirm');
        const btnCancel = document.getElementById('btnCancel');
        const modal = document.getElementById('messageModal');
        const modalMessage = document.getElementById('modalMessage');
        const modalOkButton = document.getElementById('modalOkButton');

        window.confirmarExclusao = function(id, nomeEmpresa) {
            empresaIdParaEliminar = id;
            modalConfirm.style.display = 'flex';
            confirmText.value = '';
            nomeEmpresaEliminar.textContent = nomeEmpresa;
        }

        btnCancel.addEventListener('click', function() {
            modalConfirm.style.display = 'none';
        });

        btnConfirm.addEventListener('click', function() {
            if (confirmText.value.toUpperCase() === 'CONFIRMAR') {
                window.location.href = "eliminar_empresa.php?id=" + empresaIdParaEliminar;
            } else {
                alert('Por favor, digite CONFIRMAR para prosseguir com a eliminação.');
            }
        });

        <?php if (isset($_SESSION['success'])) : ?>
            modalMessage.textContent = "<?php echo addslashes($_SESSION['success']); ?>";
            modal.style.display = "flex";
            <?php unset($_SESSION['success']); ?>
        <?php elseif (isset($_SESSION['error'])) : ?>
            modalMessage.textContent = "<?php echo addslashes($_SESSION['error']); ?>";
            modal.style.display = "flex";
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        if (modalOkButton) {
            modalOkButton.onclick = function() {
                modal.style.display = "none";
                location.reload();
            }
        }

        window.onclick = function(event) {
            if (event.target == modalConfirm) {
                modalConfirm.style.display = "none";
            }
            if (event.target == modal) {
                modal.style.display = "none";
                location.reload();
            }
        }
    });
    </script>
    <?php
}
?>