<!-- Sidebar -->
        <aside class="sidebar d-flex flex-column flex-shrink-0 shadow-sm" id="sidebar">
            <!-- Brand Header -->
            <div class="sidebar-brand">
                <div class="text-center">
                    <a href="<?php bloginfo('url'); ?>">
                        <img width="190px" src="<?php bloginfo('template_url'); ?>/img/logo_valor_rh.svg" alt="" class="img-fluid py-0">
                    </a>
                </div>
            </div>

            <nav class="sidebar-nav">
                <?php 
                    $user = wp_get_current_user();
                    if ( in_array('subscriber', (array) $user->roles, true ) ) {
                 ?>
                    
                    <ul class="list-unstyled mb-0">
                        <?php 
                            $current_url = $_SERVER['REQUEST_URI'];
                            $home_url = get_bloginfo('url');
                            
                            function is_active($slug, $current_url) {
                                if ($slug === '' || $slug === '/') {
                                    return ($current_url === '/' || $current_url === '' || $current_url === parse_url(get_bloginfo('url'), PHP_URL_PATH)) ? 'active' : '';
                                }
                                return (strpos($current_url, $slug) !== false) ? 'active' : '';
                            }

                            // Variáveis de controle para facilitar a leitura
                            $active_home = (is_front_page()) ? 'active' : '';
                            $active_perfil = is_active('perfil-candidato', $current_url);
                            $active_vagas = is_active('vagas-candidatos', $current_url);
                            
                            // Snippet da setinha para reutilizar
                            $chevron = '<i data-lucide="chevron-right" width="16"></i>';
                        ?>

                        <li>
                            <a href="<?php echo $home_url; ?>/perfil-candidato" class="sidebar-link <?php echo $active_perfil; ?>">
                                <div class="d-flex align-items-center">
                                    <i data-lucide="briefcase" width="15"></i>
                                    <span class="fw-medium text-sm">Meu Perfil</span>
                                </div>
                                <?php echo ($active_perfil === 'active') ? $chevron : ''; ?>
                            </a>
                        </li>

                        <li>
                            <a target="_blank" href="<?php echo $home_url; ?>/vagas-valorrh" class="sidebar-link <?php echo $active_vagas; ?>">
                                <div class="d-flex align-items-center">
                                    <i data-lucide="briefcase" width="15"></i>
                                    <span class="fw-medium text-sm">Vagas</span>
                                </div>
                                <?php echo ($active_vagas === 'active') ? $chevron : ''; ?>
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo wp_logout_url($home_url); ?>" class="sidebar-link">
                                <div class="d-flex align-items-center">
                                    <i data-lucide="user-circle" width="15"></i>
                                    <span class="fw-medium text-sm">Sair</span>
                                </div>
                            </a>
                        </li>
                    </ul>

                <?php } else { ?>

                    <ul class="list-unstyled mb-0">
                        <?php 
                            $current_url = $_SERVER['REQUEST_URI'];
                            $home_url = get_bloginfo('url');
                            
                            function is_active($slug, $current_url) {
                                if ($slug === '' || $slug === '/') {
                                    return ($current_url === '/' || $current_url === '' || $current_url === parse_url(get_bloginfo('url'), PHP_URL_PATH)) ? 'active' : '';
                                }
                                return (strpos($current_url, $slug) !== false) ? 'active' : '';
                            }

                            // Variáveis de controle para facilitar a leitura
                            $active_home = (is_front_page()) ? 'active' : '';
                            $active_vagas = is_active('vagas', $current_url);
                            $active_talentos = is_active('talentos', $current_url);
                            $active_empresas = is_active('empresas', $current_url);
                            
                            // Snippet da setinha para reutilizar
                            $chevron = '<i data-lucide="chevron-right" width="16"></i>';
                        ?>

                        <li>
                            <a href="<?php echo $home_url; ?>/" class="sidebar-link <?php echo $active_home; ?>">
                                <div class="d-flex align-items-center">
                                    <i data-lucide="layout-dashboard" width="15"></i>
                                    <span class="fw-medium text-sm">Visão Geral</span>
                                </div>
                                <?php echo ($active_home === 'active') ? $chevron : ''; ?>
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo $home_url; ?>/vagas" class="sidebar-link <?php echo $active_vagas; ?>">
                                <div class="d-flex align-items-center">
                                    <i data-lucide="briefcase" width="15"></i>
                                    <span class="fw-medium text-sm">Vagas</span>
                                </div>
                                <?php echo ($active_vagas === 'active') ? $chevron : ''; ?>
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo $home_url; ?>/talentos" class="sidebar-link <?php echo $active_talentos; ?>">
                                <div class="d-flex align-items-center">
                                    <i data-lucide="users" width="15"></i>
                                    <span class="fw-medium text-sm">Banco de Talentos</span>
                                </div>
                                <?php echo ($active_talentos === 'active') ? $chevron : ''; ?>
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo $home_url; ?>/empresas" class="sidebar-link <?php echo $active_empresas; ?>">
                                <div class="d-flex align-items-center">
                                    <i data-lucide="building-2" width="15"></i>
                                    <span class="fw-medium text-sm">Empresas</span>
                                </div>
                                <?php echo ($active_empresas === 'active') ? $chevron : ''; ?>
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo wp_logout_url($home_url); ?>" class="sidebar-link">
                                <div class="d-flex align-items-center">
                                    <i data-lucide="user-circle" width="15"></i>
                                    <span class="fw-medium text-sm">Sair</span>
                                </div>
                            </a>
                        </li>
                    </ul>

                <?php } ?>
            </nav>

            <div class="sidebar-footer">                
                <div class="d-flex align-items-center gap-3">                    
                    <div class="flex-grow-1 overflow-hidden">
                        <?php
                        $current_user = wp_get_current_user();

                        $nome_acf = function_exists('get_field') ? get_field('nome', 'user_' . $current_user->ID) : '';
                        $nome  = trim($nome_acf ?: ''); // se não tiver, fica vazio
                        $email = trim($current_user->user_email ?? '');
                        ?>

                        <p class="m-0 text-white fw-medium text-truncate" style="font-size: 0.875rem;">
                          Usuário:
                        </p>
                        <p class="m-0 text-slate-400 text-truncate" style="font-size: 0.75rem;">
                          <?php echo esc_html($email); ?>
                        </p>

                    </div>
                </div>
            </div>
        </aside>