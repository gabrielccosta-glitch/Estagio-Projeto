<link rel="stylesheet" href="../css/header_cliente.css">

<div class="cliente-header">

    <div class="cliente-header-left">

        <img src="../imagens/logotipo_freebox.png"
             alt="logo"
             class="cliente-logo">

        <div class="cliente-header-title">

            <h3>
                <?= htmlspecialchars($_SESSION['nome_admin'] ?? 'Administrador'); ?>
            </h3>

            <span>
                Painel de Administração
            </span>

        </div>

    </div>

    <div class="cliente-header-right">

        <a href="../admin/editar_admin.php"
           class="btn btn-success me-2">
            Editar Admin
        </a>

        <a href="../logout.php"
           class="btn btn-danger">
            Logout
        </a>

    </div>

</div>

<div class="cliente-separator"></div>