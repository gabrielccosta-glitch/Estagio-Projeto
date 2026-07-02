    <footer class="public-footer">
        <div class="footer-top">
            <div class="container">
                <div class="footer-cols">
                    <?php
    if (!isset($nome_empresa)) $nome_empresa = 'Empresa';
    $footer_empresa = is_array($empresa ?? null) ? $empresa : [];
    $footer_morada = trim($footer_empresa['morada'] ?? ($morada ?? ''));
    $footer_codigo_postal = trim($footer_empresa['codigo_postal'] ?? ($codigo_postal ?? ''));

    if ($footer_morada === '' && $footer_codigo_postal === '' && !empty($morada_completa)) {
        $footer_morada = trim($morada_completa);
    }
?>

                    <!-- CONTACTO -->
                    <div class="footer-col">
                        <h6 class="footer-col-title">Contacto</h6>
                        <ul>
                            <?php if (!empty($email_principal)): ?>
                                <li><a href="mailto:<?= htmlspecialchars($email_principal); ?>"><?= htmlspecialchars($email_principal); ?></a></li>
                            <?php endif; ?>
                            <?php if (!empty($telefone_principal)): ?>
                                <li><a href="tel:<?= htmlspecialchars($telefone_principal); ?>"><?= htmlspecialchars($telefone_principal); ?></a></li>
                            <?php endif; ?>
                            <?php if ($footer_morada !== '' || $footer_codigo_postal !== ''): ?>
                                <li class="footer-address">
                                    <?php if ($footer_morada !== ''): ?>
                                        <span class="footer-address-street"><?= htmlspecialchars($footer_morada); ?></span>
                                    <?php endif; ?>
                                    <?php if ($footer_codigo_postal !== ''): ?>
                                        <span class="footer-address-postcode"><?= htmlspecialchars($footer_codigo_postal); ?></span>
                                    <?php endif; ?>
                                </li>
                            <?php endif; ?>
                            <li><a href="<?= $link_formulario; ?>">Enviar Mensagem</a></li>
                        </ul>
                    </div>

                    <!-- PÁGINAS -->
                    <div class="footer-col">
                        <h6 class="footer-col-title">Páginas</h6>
                        <ul>
                            <li><a href="<?= $link_sobre; ?>">Sobre nós</a></li>
                            <?php if (!empty($servicos)): ?>
                                <li><a href="<?= $link_servicos; ?>">Serviços</a></li>
                            <?php endif; ?>
                            <?php if (!empty($portfolio)): ?>
                                <li><a href="<?= $link_portfolio; ?>">Portfólio</a></li>
                            <?php endif; ?>
                            <li><a href="<?= $link_contato; ?>">Contacto</a></li>
                        </ul>
                    </div>

                    <!-- LEGAL -->
                    <div class="footer-col">
                        <h6 class="footer-col-title">Legal</h6>
                        <ul>
                            <li>
                                <a href="<?= $link_politica; ?>">
                                    Política de privacidade
                                </a>
                            </li>
                            <li>
                                <a href="https://www.livroreclamacoes.pt/Inicio/" target="_blank">
                                    Livro de reclamações
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- REDES SOCIAIS -->
                    <?php
                    $tem_redes = !empty($website['link_facebook']) || !empty($website['link_instagram']) || !empty($website['link_x']);
                    if ($tem_redes): ?>
                        <div class="footer-col">
                            <h6 class="footer-col-title">Sociais</h6>
                            <ul>
                                <?php if (!empty($website['link_facebook'])): ?>
                                    <li><a href="<?= htmlspecialchars($website['link_facebook']); ?>" target="_blank">Facebook</a></li>
                                <?php endif; ?>
                                <?php if (!empty($website['link_instagram'])): ?>
                                    <li><a href="<?= htmlspecialchars($website['link_instagram']); ?>" target="_blank">Instagram</a></li>
                                <?php endif; ?>
                                <?php if (!empty($website['link_x'])): ?>
                                    <li><a href="<?= htmlspecialchars($website['link_x']); ?>" target="_blank">X / Twitter</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

        <!-- NOME GRANDE REMOVIDO -->

        <div class="footer-bottom">
            <div class="container footer-bottom-inner">
                <h6><span>© <?= date('Y'); ?> <?= htmlspecialchars($nome_empresa); ?> — Todos os direitos reservados</span></h6>
                <h6><span>Made by <a href="https://webdesigner.is4.pt/" target="_blank" class="made-by">IS4 Web Designer</a></span></h6>
            </div>
        </div>
    </footer>