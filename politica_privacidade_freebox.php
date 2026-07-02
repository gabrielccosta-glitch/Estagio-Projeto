<?php
// Página pública - Sem session_start() ou verificação de login.
// Ideal para o index.php público ou politica_privacidade.php.

$link_site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? '') . '/';
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de privacidade - FreeBox</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="css/empresa_politica_privacidade.css?v=<?= time(); ?>">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .privacy-section {
            padding: 60px 0;
            display: flex;
            justify-content: center;
        }

        .container {
            width: 100%;
            max-width: 1000px;
            padding: 0 20px;
        }
    </style>
</head>

<body>

    <section class="privacy-section">

        <div class="container">

            <div class="privacy-container">

                <h1 class="privacy-title">
                    Política de privacidade
                </h1>

                <p class="privacy-subtitle">
                    A sua privacidade é importante para nós. Esta política explica como os dados são recolhidos,
                    utilizados e protegidos dentro da plataforma FreeBox.
                </p>

                <div class="privacy-content">

                    <h2>1. Quem somos</h2>

                    <p>
                        A <strong>FreeBox</strong> é uma plataforma de criação e gestão de websites empresariais,
                        permitindo às empresas criarem e gererem a sua presença online de forma simples,
                        rápida e profissional.
                    </p>

                    <div class="privacy-box">

                        <strong>Endereço da plataforma:</strong>

                        <br><br>

                        <a href="<?= htmlspecialchars($link_site); ?>" target="_blank">
                            <?= htmlspecialchars($link_site); ?>
                        </a>

                    </div>

                    <h2>2. Que dados recolhemos</h2>

                    <p>
                        Durante a utilização da plataforma poderão ser recolhidos os seguintes dados:
                    </p>

                    <ul>
                        <li>Nome da empresa</li>
                        <li>Email e contactos telefónicos</li>
                        <li>Morada e informações fiscais</li>
                        <li>Imagens, logótipos e conteúdos enviados</li>
                        <li>Informações inseridas nos websites criados</li>
                        <li>Endereço IP e informações técnicas de acesso</li>
                    </ul>

                    <h2>3. Finalidade da recolha de dados</h2>

                    <p>
                        Os dados recolhidos destinam-se exclusivamente a:
                    </p>

                    <ul>
                        <li>Criação e gestão dos websites empresariais</li>
                        <li>Identificação e autenticação das contas</li>
                        <li>Prestação de suporte técnico</li>
                        <li>Comunicação entre a plataforma e os utilizadores</li>
                        <li>Melhoria contínua dos serviços</li>
                        <li>Garantia de segurança da plataforma</li>
                    </ul>

                    <h2>4. Partilha de dados</h2>

                    <p>
                        A FreeBox não vende nem distribui dados pessoais a terceiros.
                        Os dados apenas poderão ser divulgados quando exigido legalmente
                        pelas autoridades competentes.
                    </p>

                    <h2>5. Conservação dos dados</h2>

                    <p>
                        Os dados serão armazenados apenas durante o período necessário
                        para garantir o funcionamento da plataforma e enquanto a conta
                        permanecer ativa.
                    </p>

                    <h2>6. Direitos do utilizador</h2>

                    <p>
                        Nos termos da legislação aplicável, o utilizador poderá solicitar:
                    </p>

                    <ul>
                        <li>Acesso aos seus dados pessoais</li>
                        <li>Correção ou atualização das informações</li>
                        <li>Eliminação da conta e dos dados associados</li>
                        <li>Exportação dos dados armazenados</li>
                    </ul>

                    <h2>7. Cookies</h2>

                    <p>
                        A plataforma pode utilizar cookies para melhorar a experiência de utilização,
                        manter sessões autenticadas e otimizar funcionalidades internas.
                    </p>

                    <h2>8. Segurança</h2>

                    <p>
                        Implementamos medidas técnicas e organizacionais adequadas para proteger
                        os dados pessoais contra acessos não autorizados, perda,
                        alteração ou divulgação indevida.
                    </p>

                    <div class="privacy-highlight">

                        <h3>Compromisso com a privacidade</h3>

                        <p>
                            Trabalhamos diariamente para garantir a proteção e confidencialidade
                            das informações dos nossos utilizadores e empresas.
                        </p>

                    </div>

                    <h2>9. Alterações desta política</h2>

                    <p>
                        A presente Política de privacidade poderá ser atualizada periodicamente,
                        sem necessidade de aviso prévio, de forma a refletir melhorias,
                        alterações legais ou novas funcionalidades da plataforma.
                    </p>

                    <h2>10. Contacto</h2>

                    <p>
                        Para questões relacionadas com privacidade,
                        proteção de dados ou suporte técnico:
                    </p>

                    <div class="privacy-box">

                        <strong>Email de suporte:</strong>

                        <br><br>

                        <a href="mailto:suporte@freebox.pt">
                            suporte@freebox.pt
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>

</body>

</html>