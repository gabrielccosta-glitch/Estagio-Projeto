    <?php
    session_start();
    require_once '../config/database.php';
    require_once '../includes/functions.php';

    // Verificar se o usuário é admin
    if (!eAdmin()) {
        header("Location: ../login.php");
        exit;
    }

    // Verificar se o ID da empresa foi fornecido
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        $_SESSION['error'] = "ID de empresa inválido.";
        header("Location: dashboard.php");
        exit;
    }

    $empresa_id = intval($_GET['id']);

    // Iniciar transação
    $conn->begin_transaction();

    try {
        // Obter o url_site associado para saber se há diretório dinâmico
        $url_stmt = $conn->prepare("SELECT url_site FROM website_config WHERE empresa_id = ?");
        $url_stmt->bind_param("i", $empresa_id);
        $url_stmt->execute();
        $url_row = $url_stmt->get_result()->fetch_assoc();
        $url_stmt->close();
        $url_site = $url_row['url_site'] ?? '';

        // Primeiro, obter o usuario_id associado à empresa
        $stmt = $conn->prepare("SELECT usuario_id FROM empresas WHERE id = ?");
        $stmt->bind_param("i", $empresa_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("Empresa não encontrada.");
        }
        
        $row = $result->fetch_assoc();
        $usuario_id = $row['usuario_id'];
    
        // Eliminar o protfolio associado á empresa
        $stmt = $conn->prepare("DELETE FROM portfolio WHERE empresa_id = ?");
        $stmt->bind_param("i", $empresa_id);
        $stmt->execute();
    
        // Eliminar os serviços associados á empresa
        $stmt = $conn->prepare("DELETE FROM servicos WHERE empresa_id = ?");
        $stmt->bind_param("i", $empresa_id);
        $stmt->execute();

        // Eliminar a empresa
        $stmt = $conn->prepare("DELETE FROM empresas WHERE id = ?");
        $stmt->bind_param("i", $empresa_id);
        $stmt->execute();

        // Eliminar o usuário associado
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();

        // Se chegou até aqui sem erros, commit da transação
        $conn->commit();

        // Eliminar a pasta física do website do cliente, se existir
        if (!empty($url_site)) {
            $projeto_root = dirname(__DIR__);
            $diretorio_cliente = $projeto_root . '/' . $url_site;
            if (is_dir($diretorio_cliente) && $url_site !== 'freebox') {
                eliminarDiretorio($diretorio_cliente);
            }
        }

        $_SESSION['success'] = "Empresa e serviços associados eliminados com sucesso.";
    } catch (Exception $e) {
        // Se ocorreu algum erro, faz rollback da transação
        $conn->rollback();
        $_SESSION['error'] = "Erro ao eliminar empresa e serviços: " . $e->getMessage();
    }

    // Fechar a conexão
    $conn->close();

    // Redirecionar de volta para o dashboard
    header("Location: dashboard.php");
    exit;
    ?>
