    <footer class="public-footer">
        <div class="footer-top">
            <div class="container">
                <div class="footer-cols">
                    <?php if (!isset($nome_empresa)) $nome_empresa = 'Empresa'; ?>

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
                            <?php if (!empty($morada_completa)): ?>
                                <li><span><?= htmlspecialchars($morada_completa); ?></span></li>
                            <?php endif; ?>
                            <li><a href="<?= $link_formulario; ?>">Enviar Mensagem</a></li>
                        </ul>
                    </div>

                    <!-- PÁGINAS -->
                    <div class="footer-col">
                        <h6 class="footer-col-title">Páginas</h6>
                        <ul>
                            <li><a href="<?= $link_sobre; ?>">Sobre Nós</a></li>
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
                                    Política de Privacidade
                                </a>
                            </li>
                            <li>
                                <a href="https://www.livroreclamacoes.pt/Inicio/" target="_blank">
                                    Livro de Reclamações
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