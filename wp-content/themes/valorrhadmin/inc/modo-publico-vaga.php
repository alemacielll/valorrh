<!DOCTYPE html>
    <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Vaga Disponível | <?php the_title(); ?></title>
            <link href="<?php bloginfo('template_url'); ?>/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
            
            <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/styles2.css">
            <style>
                body {padding-top: 80px;}       
            </style>
        </head>
        <body class="bg-slate-50 text-slate-900">
            <header class="header-public fixed-top d-flex align-items-center px-4 px-md-5 justify-content-between">
                <div class="d-flex align-items-center">
                    <?php if ( current_user_can('administrator') ) { ?>
                        <a href="<?php bloginfo('url'); ?>/vagas-valorrh">
                            <img src="<?php bloginfo('template_url'); ?>/img/logo_valor_rh.svg" alt="Valor RH" height="40">
                        </a>
                    <?php } else { ?>
                        <a href="<?php bloginfo('url'); ?>/vagas-valorrh">
                            <img src="<?php bloginfo('template_url'); ?>/img/logo_valor_rh.svg" alt="Valor RH" height="40">
                        </a>
                    <?php } ?>
                </div>
                <div>
                    <a href="<?php bloginfo('url'); ?>/register" class="btn btn-danger d-flex align-items-center gap-2 rounded-pill px-4">
                        <span>Cadastre-se</span>
                        <i data-lucide="arrow-right" width="16"></i>
                    </a>
                </div>
            </header>

            <?php
            $post_id = get_the_ID();
            $id_empresa = get_field('id_empresa');
            $situacao = get_field('situacao');
            $data_publicacao = get_the_date('d/m/Y');

            // Dados Operacionais
            $cidade = get_field('cidade');
            $estado = get_field('estado');
            $modelo = get_field('modelo');
            $regime = get_field('regime');
            $carga_horaria = get_field('carga_horaria');
            $escolaridade = get_field('escolaridade');

            $sal_min = get_field('faixa_inicial');
            $sal_max = get_field('faixa_final');

            $limite_raw = get_field('limite', false, false);
            $data_limite = $limite_raw ? date('d/m/Y', strtotime($limite_raw)) : '';
            ?>

            <?php if ( current_user_can('administrator') ) : ?>
            <div class="alert alert-danger mt-2 text-center" role="alert">
                <strong>Atenção</strong> você está logado como administrador, portanto não pode se candidatar a uma vaga.
            </div>
            <?php endif; ?>

            <section class="job-header py-5">
                <div class="container">
                    <a href="<?php bloginfo('url'); ?>/vagas-valorrh" class="btn btn-link text-slate-500 text-decoration-none p-0 d-flex align-items-center gap-1 hover:text-indigo-600 mb-4" style="font-size: 0.875rem;">
                        <i data-lucide="arrow-left" width="16"></i>
                        Voltar
                    </a>

                    <div class="row align-items-start">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge rounded-pill <?php echo ($situacao === 'Aberta') ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'badge rounded-pill bg-slate-100 text-slate-600 border border-slate-200'; ?> border px-3">
                                    <?php echo $situacao ? $situacao : 'Indefinida'; ?>
                                </span>
                                <span class="text-slate-400">•</span>
                                <span class="text-slate-500 text-sm">Publicada em <?php echo $data_publicacao; ?></span>
                            </div>
                            
                            <h1 class="h2 fw-bold text-slate-900 mb-2"><?php the_title(); ?></h1>
                            
                            <div class="d-flex align-items-center flex-wrap gap-4 text-slate-600">
                                <div class="d-flex align-items-center gap-2">
                                    <i data-lucide="building-2" width="18" class="text-slate-400"></i>
                                    <span class="fw-medium"><?php echo get_the_title($id_empresa); ?></span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <i data-lucide="map-pin" width="18" class="text-slate-400"></i>
                                    <span><?php echo "$cidade, $estado ($modelo)"; ?></span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <i data-lucide="briefcase" width="18" class="text-slate-400"></i>
                                    <span><?php echo $regime; ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <?php if ($sal_min || $sal_max) : ?>
                                <div class="d-flex flex-column align-items-lg-end gap-1">
                                    <span class="text-slate-500 text-sm">Faixa Salarial</span>
                                    <span class="h4 fw-bold text-slate-900 mb-0">
                                        <?php 
                                            if ($sal_min && $sal_max) {
                                                echo 'R$ ' . number_format($sal_min, 0, ',', '.') . ' - ' . number_format($sal_max, 0, ',', '.');
                                            } else {
                                                echo 'R$ ' . number_format($sal_min ?: $sal_max, 0, ',', '.');
                                            }
                                        ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

            <section class="container pb-5">
                <div class="row g-4">
                    <div class="col-12 col-lg-8">
                        <div class="card p-4 border-0 shadow-sm mb-4">
                            <h3 class="h5 fw-bold text-slate-800 mb-3">Sobre a Vaga</h3>
                            <div class="text-slate-600 content-area"><?php echo wpautop(get_field('descricao')); ?></div>

                            <h4 class="fw-semibold mt-4 text-slate-800 mb-2" style="font-size: 1rem;">Responsabilidades e Atribuições</h4>
                            <div class="text-slate-600 mb-4 list-style-custom"><?php echo get_field('responsabilidades'); ?></div>

                            <h4 class="fw-semibold text-slate-800 mb-2" style="font-size: 1rem;">Sobre a Empresa</h4>
                            <p class="text-slate-600 mb-0">
                                <?php echo get_the_excerpt($id_empresa); ?>
                            </p>
                        </div>

                        <div class="card p-4 border-0 shadow-sm">
                            <h3 class="h5 fw-bold text-slate-800 mb-4">Requisitos</h3>
                            <div class="row g-4">
                                <div class="col-12 col-md-6">
                                    <h4 class="fw-semibold text-slate-700 mb-3 d-flex align-items-center gap-2" style="font-size: 0.95rem;">
                                        <i data-lucide="code-2" width="18" class="text-indigo-600"></i> Hard Skills
                                    </h4>
                                    <div class="text-slate-600"><?php the_field('hard_skills'); ?></div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <h4 class="fw-semibold text-slate-700 mb-3 d-flex align-items-center gap-2" style="font-size: 0.95rem;">
                                        <i data-lucide="star" width="18" class="text-amber-500"></i> Diferenciais
                                    </h4>
                                    <div class="text-slate-600"><?php the_field('diferenciais'); ?></div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <h4 class="fw-semibold text-slate-700 mb-2" style="font-size: 0.95rem;">Soft Skills</h4>
                                <div class="text-slate-600"><?php the_field('soft_skills'); ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4">
                        <div class="d-flex flex-column gap-4">
                            <?php if ($situacao === 'Aberta') : ?>
                            <div class="card p-4 text-center border-0 shadow-sm bg-indigo-50 border-indigo-100">
                                <h3 class="h5 fw-bold text-indigo-900 mb-2">Interessado?</h3>
                                <p class="text-indigo-700 text-sm mb-2">Envie sua candidatura agora mesmo.</p>
                                <a href="<?php bloginfo('url'); ?>/register" class="btn btn-primary w-100 rounded-pill mb-2">Candidatar-se para a vaga</a>
                            </div>
                            <?php endif; ?>

                            <div class="card p-4 border-0 shadow-sm">
                                <h3 class="fw-bold text-slate-800 text-uppercase mb-3" style="font-size: 0.75rem; letter-spacing: 0.05em;">Detalhes Operacionais</h3>
                                <ul class="list-unstyled d-flex flex-column gap-3 mb-0">
                                    <li class="d-flex align-items-start gap-3">
                                        <div class="bg-slate-50 p-2 rounded-circle text-slate-500"><i data-lucide="map-pin" width="18"></i></div>
                                        <div>
                                            <p class="fw-medium text-slate-700 m-0 text-sm">Localização</p>
                                            <p class="text-slate-500 m-0 text-sm"><?php echo "$cidade, $estado"; ?></p>
                                        </div>
                                    </li>
                                    <li class="d-flex align-items-start gap-3">
                                        <div class="bg-slate-50 p-2 rounded-circle text-slate-500"><i data-lucide="clock" width="18"></i></div>
                                        <div>
                                            <p class="fw-medium text-slate-700 m-0 text-sm">Carga Horária</p>
                                            <p class="text-slate-500 m-0 text-sm"><?php echo $carga_horaria; ?></p>
                                        </div>
                                    </li>
                                    <li class="d-flex align-items-start gap-3">
                                        <div class="bg-slate-50 p-2 rounded-circle text-slate-500"><i data-lucide="graduation-cap" width="18"></i></div>
                                        <div>
                                            <p class="fw-medium text-slate-700 m-0 text-sm">Escolaridade</p>
                                            <p class="text-slate-500 m-0 text-sm"><?php echo str_replace('_', ' ', ucfirst($escolaridade)); ?></p>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="card p-4 border-0 shadow-sm">
                                <h3 class="fw-bold text-slate-800 text-uppercase mb-3 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                                    <i data-lucide="gift" width="16"></i> Benefícios
                                </h3>
                                <div class="text-slate-600 text-sm">
                                    <?php echo nl2br(get_field('beneficios')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <footer class="bg-white border-top border-slate-200 py-4 mt-auto">
                <div class="container text-center text-slate-500 text-sm">
                    <p class="mb-0">&copy; 2026 Valor RH. Todos os direitos reservados. Design por <a href="https://argosolucoes.com.br" target="_blank" class="text-slate-800 text-decoration-none">Argo Soluções</a></p>
                </div>
            </footer>

            <script src="<?php bloginfo('template_url'); ?>/js/bootstrap.bundle.min.js"></script>
            <script src="<?php bloginfo('template_url'); ?>/js/lucide.min.js"></script>
            <script>
                lucide.createIcons();
            </script>
        </body>
    </html>