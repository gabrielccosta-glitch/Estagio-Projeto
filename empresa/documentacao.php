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

<link rel="stylesheet"
      href="../css/empresa_dashboard.css">

<style>
.doc-wrapper {
    background: #f0f4fa;
    border-radius: 12px;
    padding: 36px 40px;
}

.doc-section {
    margin-bottom: 36px;
}

.doc-section-title {
    font-size: 17px;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.doc-section-title .icon-box {
    width: 32px;
    height: 32px;
    min-width: 32px;
    border-radius: 50%;
    background: #356096;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.doc-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #dce6f0;
}

.doc-item:last-child {
    border-bottom: none;
}

.doc-item-icon {
    width: 22px;
    color: #356096;
    font-size: 14px;
    margin-top: 2px;
    flex-shrink: 0;
    text-align: center;
}

.doc-item-content h6 {
    font-size: 14px;
    font-weight: 700;
    color: #1a2e4a;
    margin: 0 0 4px 0;
}

.doc-item-content p {
    font-size: 13px;
    color: #6b7280;
    margin: 0;
    line-height: 1.5;
}

.doc-divider {
    height: 1px;
    background: #d1d9e6;
    margin: 28px 0;
}

.doc-badge {
    display: inline-block;
    background: #DBEAFE;
    color: #356096;
    font-size: 11px;
    font-weight: 600;
    border-radius: 20px;
    padding: 2px 10px;
    margin-left: 8px;
    vertical-align: middle;
}

.faq-question {
    font-size: 14px;
    font-weight: 700;
    color: #356096;
    cursor: pointer;
    padding: 12px 0;
    border-bottom: 1px solid #dce6f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    user-select: none;
}

.faq-question:last-of-type {
    border-bottom: none;
}

.faq-answer {
    font-size: 13px;
    color: #6b7280;
    padding: 0 0 12px 0;
    display: none;
    line-height: 1.6;
}
</style>

<!-- HEADER CLIENTE -->
<?php include __DIR__ . '/header_cliente.php'; ?>

<div class="container-fluid mt-4">

    <div class="row justify-content-center">

        <div class="col-md-10 col-lg-8">

            <div class="card dashboard-main-card">

                <div class="card-body">

                    <h3 class="mb-1">
                        <i class="fas fa-book me-2"></i>
                        Documentação
                    </h3>

                    <p class="text-muted mb-4">
                        Guia completo para utilizar a plataforma Freebox.
                    </p>

                    <div class="dashboard-divider mb-4"></div>

                    <div class="doc-wrapper">

                        <!-- PRIMEIROS PASSOS -->
                        <div class="doc-section">

                            <div class="doc-section-title">

                                <div class="icon-box">
                                    <i class="fas fa-rocket"></i>
                                </div>

                                Primeiros Passos

                            </div>

                            <div class="doc-item">

                                <div class="doc-item-icon">
                                    <i class="fas fa-right-to-bracket"></i>
                                </div>

                                <div class="doc-item-content">

                                    <h6>Como fazer login</h6>

                                    <p>
                                        Acede a
                                        <strong>freebox.pt/login</strong>,
                                        insere o teu email e palavra-passe
                                        e clica em "Entrar".
                                        Caso não tenhas conta,
                                        clica em "Registar"
                                        e preenche os campos.
                                    </p>

                                </div>

                            </div>

                            <div class="doc-item">

                                <div class="doc-item-icon">
                                    <i class="fas fa-circle-info"></i>
                                </div>

                                <div class="doc-item-content">

                                    <h6>
                                        Preencher as informações da empresa
                                    </h6>

                                    <p>
                                        No dashboard,
                                        clica em
                                        <strong>Informações</strong>.
                                        Preenche o nome, morada,
                                        telefone, email e outros dados
                                        da empresa.
                                        Guarda as alterações
                                        no final da página.
                                    </p>

                                </div>

                            </div>

                            <div class="doc-item">

                                <div class="doc-item-icon">
                                    <i class="fas fa-link"></i>
                                </div>

                                <div class="doc-item-content">

                                    <h6>Definir o URL do website</h6>

                                    <p>
                                        No dashboard,
                                        clica em
                                        <strong>Website</strong>
                                        e preenche o campo
                                        <em>Endereço do Website</em>.

                                        O URL só pode conter letras,
                                        números e hífens
                                        (ex:
                                        <code>minha-empresa</code>).

                                        O teu site ficará disponível em
                                        <strong>
                                            freebox.pt/minha-empresa
                                        </strong>.
                                    </p>

                                </div>

                            </div>

                        </div>

                        <div class="doc-divider"></div>

                        <!-- GESTÃO DO WEBSITE -->
                        <div class="doc-section">

                            <div class="doc-section-title">

                                <div class="icon-box">
                                    <i class="fas fa-globe"></i>
                                </div>

                                Gestão do Website

                            </div>

                            <div class="doc-item">

                                <div class="doc-item-icon">
                                    <i class="fas fa-handshake"></i>
                                </div>

                                <div class="doc-item-content">

                                    <h6>Adicionar serviços</h6>

                                    <p>
                                        Clica em
                                        <strong>Serviços</strong>
                                        no dashboard.

                                        Podes adicionar o nome,
                                        descrição e ícone
                                        de cada serviço.

                                        Os serviços aparecem
                                        automaticamente
                                        na página pública
                                        da tua empresa.
                                    </p>

                                </div>

                            </div>

                            <div class="doc-item">

                                <div class="doc-item-icon">
                                    <i class="fas fa-images"></i>
                                </div>

                                <div class="doc-item-content">

                                    <h6>Gerir o portfólio</h6>

                                    <p>
                                        Clica em
                                        <strong>Portfólio</strong>
                                        para carregar imagens
                                        dos teus trabalhos.

                                        Formatos aceites:
                                        JPG, PNG, WEBP.

                                        Tamanho máximo:
                                        <strong>5 MB</strong>.

                                        Podes eliminar imagens
                                        a qualquer momento.
                                    </p>

                                </div>

                            </div>

                            <div class="doc-item">

                                <div class="doc-item-icon">
                                    <i class="fas fa-image"></i>
                                </div>

                                <div class="doc-item-content">

                                    <h6>
                                        Configurar capa e logótipo
                                    </h6>

                                    <p>
                                        Em
                                        <strong>Website</strong>,
                                        podes carregar uma imagem
                                        de capa (banner)
                                        e o logótipo da empresa.

                                        Recomendamos uma imagem
                                        com pelo menos
                                        <strong>1200×400 px</strong>.
                                    </p>

                                </div>

                            </div>

                            <div class="doc-item">

                                <div class="doc-item-icon">
                                    <i class="fas fa-share-nodes"></i>
                                </div>

                                <div class="doc-item-content">

                                    <h6>Redes sociais</h6>

                                    <p>
                                        Em
                                        <strong>Website</strong>,
                                        adiciona os links completos
                                        do Facebook, Instagram
                                        e X (Twitter).

                                        Exemplo:
                                        <code>
                                            https://facebook.com/minha-empresa
                                        </code>
                                    </p>

                                </div>

                            </div>

                            <div class="doc-item">

                                <div class="doc-item-icon">
                                    <i class="fas fa-eye"></i>
                                </div>

                                <div class="doc-item-content">

                                    <h6>Ver o website público</h6>

                                    <p>
                                        Após definir o URL,
                                        o botão
                                        <strong>Ver Website</strong>
                                        ficará disponível
                                        no dashboard.
                                    </p>

                                </div>

                            </div>

                        </div>

                        <div class="doc-divider"></div>

                        <!-- CONTA -->
                        <div class="doc-section">

                            <div class="doc-section-title">

                                <div class="icon-box">
                                    <i class="fas fa-user-gear"></i>
                                </div>

                                Conta &amp; Definições

                            </div>

                            <div class="doc-item">

                                <div class="doc-item-icon">
                                    <i class="fas fa-pen"></i>
                                </div>

                                <div class="doc-item-content">

                                    <h6>Alterar nome e email</h6>

                                    <p>
                                        Clica em
                                        <strong>Editar Conta</strong>
                                        no dashboard.

                                        Altera os dados
                                        e guarda as alterações.
                                    </p>

                                </div>

                            </div>

                            <div class="doc-item">

                                <div class="doc-item-icon">
                                    <i class="fas fa-lock"></i>
                                </div>

                                <div class="doc-item-content">

                                    <h6>
                                        Alterar palavra-passe
                                    </h6>

                                    <p>
                                        Em
                                        <strong>Editar Conta</strong>,
                                        define a nova palavra-passe.

                                        Deve ter pelo menos
                                        <strong>8 caracteres</strong>.
                                    </p>

                                </div>

                            </div>

                        </div>

                        <div class="doc-divider"></div>

                        <!-- FAQ -->
                        <div class="doc-section">

                            <div class="doc-section-title">

                                <div class="icon-box">
                                    <i class="fas fa-circle-question"></i>
                                </div>

                                Perguntas Frequentes

                                <span class="doc-badge">
                                    FAQ
                                </span>

                            </div>

                            <div class="faq-question"
                                 onclick="toggleFaq(this)">

                                O meu website não aparece após definir o URL.

                                <i class="fas fa-chevron-down"></i>

                            </div>

                            <div class="faq-answer">

                                Aguarda alguns segundos
                                e recarrega a página.

                                Verifica também
                                se o URL não contém
                                espaços ou caracteres especiais.

                            </div>

                            <div class="faq-question"
                                 onclick="toggleFaq(this)">

                                Posso ter mais do que um website?

                                <i class="fas fa-chevron-down"></i>

                            </div>

                            <div class="faq-answer">

                                Atualmente,
                                cada conta suporta
                                apenas uma empresa
                                e um website.

                            </div>

                            <div class="faq-question"
                                 onclick="toggleFaq(this)">

                                As imagens não carregam.

                                <i class="fas fa-chevron-down"></i>

                            </div>

                            <div class="faq-answer">

                                Verifica se a imagem
                                é JPG, PNG ou WEBP
                                e se não ultrapassa 5 MB.

                            </div>

                            <div class="faq-question"
                                 onclick="toggleFaq(this)">

                                Como eliminar a conta?

                                <i class="fas fa-chevron-down"></i>

                            </div>

                            <div class="faq-answer">

                                Envia um email para
                                <a href="mailto:suporte@freebox.pt">
                                    suporte@freebox.pt
                                </a>

                            </div>

                        </div>

                        <!-- AJUDA -->
                        <div class="doc-divider"></div>

                        <p style="
                            font-size:13px;
                            color:#6b7280;
                            text-align:center;
                        ">

                            Não encontraste o que procuras?

                            <a href="formulario_suporte.php"
                               style="
                                    color:#356096;
                                    font-weight:600;
                               ">
                                Contacta o suporte
                            </a>

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script>
function toggleFaq(el) {

    const answer = el.nextElementSibling;

    const icon = el.querySelector('i');

    const isOpen = answer.style.display === 'block';

    // Fecha todos
    document
        .querySelectorAll('.faq-answer')
        .forEach(a => a.style.display = 'none');

    document
        .querySelectorAll('.faq-question i')
        .forEach(i => {
            i.style.transform = 'rotate(0deg)';
        });

    if (!isOpen) {

        answer.style.display = 'block';

        icon.style.transform = 'rotate(180deg)';
    }
}
</script>

<!-- FOOTER CLIENTE -->
<?php include __DIR__ . '/footer_cliente.php'; ?>

<?php include '../includes/footer.php'; ?>