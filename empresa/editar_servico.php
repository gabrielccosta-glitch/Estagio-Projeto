<?php
session_start();

require_once '../config/database.php';
require_once '../includes/functions.php';

/*
|--------------------------------------------------------------------------
| VERIFICAR LOGIN
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['usuario_id'])) {

    header("Location: ../login.php");

    exit();
}

/*
|--------------------------------------------------------------------------
| VALIDAR ID
|--------------------------------------------------------------------------
*/

if (
    !isset($_GET['id'])
    || !is_numeric($_GET['id'])
) {

    $_SESSION['error_message'] =
        "ID do serviço inválido.";

    header("Location: dashboard.php");

    exit();
}

$servico_id = intval($_GET['id']);

$usuario_id = $_SESSION['usuario_id'];

/*
|--------------------------------------------------------------------------
| BUSCAR SERVIÇO
|--------------------------------------------------------------------------
*/

if ($_SESSION['tipo_usuario'] == 'admin') {

    $sql = "
        SELECT
            s.*,
            e.id AS empresa_id,
            e.nome_empresa
        FROM servicos s
        JOIN empresas e
            ON s.empresa_id = e.id
        WHERE s.id = ?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $servico_id);

} else {

    $sql = "
        SELECT
            s.*,
            e.id AS empresa_id,
            e.nome_empresa
        FROM servicos s
        JOIN empresas e
            ON s.empresa_id = e.id
        WHERE s.id = ?
        AND e.usuario_id = ?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ii",
        $servico_id,
        $usuario_id
    );
}

$stmt->execute();

$result = $stmt->get_result();

$servico = $result->fetch_assoc();

$stmt->close();

if (!$servico) {

    $_SESSION['error_message'] =
        "Serviço não encontrado ou você não tem permissão para editá-lo.";

    header("Location: dashboard.php");

    exit();
}

/*
|--------------------------------------------------------------------------
| PROCESSAR FORMULÁRIO
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $titulo_servico =
        $_POST['titulo_servico'];

    $descricao_servico =
        $_POST['descricao_servico'];

    $update_sql = "
        UPDATE servicos
        SET
            titulo_servico = ?,
            descricao_servico = ?
        WHERE id = ?
    ";

    $update_stmt =
        $conn->prepare($update_sql);

    $update_stmt->bind_param(
        "ssi",
        $titulo_servico,
        $descricao_servico,
        $servico_id
    );

    if ($update_stmt->execute()) {

        $_SESSION['success_message'] =
            "Serviço atualizado com sucesso.";

        header(
            "Location: empresa_servicos.php?id="
            . $servico['empresa_id']
            . "&show_message=1#servicos"
        );

        exit();

    } else {

        $_SESSION['error_message'] =
            "Erro ao atualizar o serviço: "
            . $conn->error;
    }

    $update_stmt->close();
}

include '../includes/header.php';

/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

if ($_SESSION['tipo_usuario'] == 'admin') {

    include '../admin/header_admin.php';

} else {

    include __DIR__ . '/header_cliente.php';
}
?>

<link rel="stylesheet"
      href="../css/editar_servico.css">

<div class="separator"></div>

<div class="container editar-servico-container">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="editar-servico-header">

                <h5>
                    Editar Serviço
                </h5>

            </div>

            <div class="editar-servico-card">

                <form method="POST"
                      action="editar_servico.php?id=<?php echo $servico_id; ?>">
<div class="form-group mt-4">

                        <label for="titulo_servico">
                            Título do Serviço
                        </label>

                        <input type="text"
                               class="form-control"
                               id="titulo_servico"
                               name="titulo_servico"
                               value="<?php echo htmlspecialchars($servico['titulo_servico']); ?>"
                               required>

                    </div>

                    <div class="form-group mt-4">

                        <label for="descricao_servico">
                            Descrição do Serviço
                        </label>

                        <textarea class="form-control"
                                  id="descricao_servico"
                                  name="descricao_servico"
                                  rows="3"
                                  required><?php echo htmlspecialchars($servico['descricao_servico']); ?></textarea>

                    </div>

                    <div class="editar-servico-buttons mt-4">

                        <a href="empresa_servicos.php?id=<?php echo $servico['empresa_id']; ?>#servicos"
                           class="btn btn-secondary">
                            Cancelar
                        </a>

                        <button type="submit"
                                class="btn btn-success">
                            Guardar
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<?php

if ($_SESSION['tipo_usuario'] == 'admin') {

    include '../admin/footer_admin.php';

} else {

    include __DIR__ . '/footer_cliente.php';
}
?>