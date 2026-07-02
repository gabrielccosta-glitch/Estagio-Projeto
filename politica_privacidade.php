<?php
// Não precisa de session_start() nem de verificar se está logado,
// pois esta página será pública para quem visita o site.

$link_site =
    (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://')
    . $_SERVER['HTTP_HOST']
    . '/';
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de privacidade</title>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet"
        href="css/empresa_politica_privacidade.css?v=<?= time(); ?>">
</head>

<body>

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

    <section class="privacy-section">

        <div class="container">

            <div class="privacy-container">

                <h1 class="privacy-title">
                    Política de privacidade
                </h1>

                <p class="privacy-subtitle">
                    A sua privacidade é importante para nós.
                    Esta política explica como os dados são recolhidos,
                    utilizados e protegidos no nosso website.
                </p>

                <div class="privacy-content">

                    <h2>1. Quem somos</h2>

                    <p>
                        Este website é uma plataforma online que permite aos utilizadores
                        entrarem em contacto, conhecerem os nossos serviços e interagirem
                        connosco de forma simples e profissional.
                    </p>

                    <div class="privacy-box">

                        <strong>Endereço do Website:</strong>

                        <br><br>

                        <a href="<?= htmlspecialchars($link_site); ?>"
                            target="_blank">

                            <?= htmlspecialchars($link_site); ?>

                        </a>

                    </div>

                    <h2>2. Que dados recolhemos</h2>

                    <p>
                        Durante a utilização do nosso website e através dos formulários
                        disponíveis, poderão ser recolhidos os seguintes dados:
                    </p>

                    <ul>
                        <li>Nome completo / Nome da empresa</li>
                        <li>Endereço de email</li>
                        <li>Contactos telefónicos</li>
                        <li>
                            Mensagens e informações submetidas nos formulários
                            de contacto ou suporte
                        </li>
                        <li>
                            Endereço IP e cookies técnicos para o correto
                            funcionamento do site
                        </li>
                    </ul>

                    <h2>3. Finalidade da recolha de dados</h2>

                    <p>
                        Os dados recolhidos no website destinam-se exclusivamente a:
                    </p>

                    <ul>
                        <li>
                            Responder a pedidos de informação ou suporte
                            enviados pelos utilizadores
                        </li>

                        <li>
                            Processar comunicações iniciadas através do
                            formulário de contacto
                        </li>

                        <li>
                            Garantir a segurança e o correto funcionamento
                            do website
                        </li>

                        <li>
                            Cumprir obrigações legais, quando aplicável
                        </li>
                    </ul>

                    <h2>4. Partilha de dados</h2>

                    <p>
                        Não vendemos, trocamos nem distribuímos os seus dados pessoais
                        a terceiros.

                        Os dados introduzidos nos formulários servem apenas para a
                        comunicação direta connosco.
                    </p>

                    <h2>5. Conservação dos dados</h2>

                    <p>
                        Os dados recolhidos através dos formulários de contacto
                        serão armazenados apenas durante o período necessário
                        para responder e tratar a sua solicitação,
                        ou para cumprir requisitos legais de arquivo.
                    </p>

                    <h2>6. Direitos do utilizador</h2>

                    <p>
                        Como utilizador e titular dos dados,
                        poderá solicitar a qualquer momento:
                    </p>

                    <ul>
                        <li>
                            O acesso e conhecimento dos dados
                            que guardamos sobre si
                        </li>

                        <li>
                            A correção ou atualização de alguma
                            informação incorreta
                        </li>

                        <li>
                            A eliminação definitiva dos dados submetidos
                            através dos formulários
                        </li>
                    </ul>

                    <h2>7. Cookies</h2>

                    <p>
                        Este website pode utilizar cookies essenciais
                        para otimizar a sua navegação,
                        lembrar as suas preferências em formulários
                        e garantir que o site carrega de forma rápida e segura.
                    </p>

                    <h2>8. Segurança</h2>

                    <p>
                        Implementamos medidas técnicas e de segurança padrão na internet
                        para proteger os seus dados contra acessos não autorizados,
                        perda, alteração ou divulgação indevida
                        enquanto navega pelo site.
                    </p>

                    <div class="privacy-highlight">

                        <h3>Compromisso com a privacidade</h3>

                        <p>
                            Garantimos total transparência e confidencialidade
                            no tratamento de todos os dados partilhados connosco
                            através deste website.
                        </p>

                    </div>

                    <h2>9. Alterações desta política</h2>

                    <p>
                        Esta Política de privacidade poderá ser atualizada periodicamente
                        de forma a refletir melhorias no website
                        ou alterações legislativas.

                        Recomendamos a consulta regular desta página.
                    </p>

                    <h2>10. Contacto</h2>

                    <p>
                        Para qualquer esclarecimento adicional relativo
                        à privacidade e proteção dos seus dados,
                        entre em contacto através do nosso email.
                    </p>

                    <div class="privacy-box">

                        <strong>Email de Contacto / Suporte:</strong>

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