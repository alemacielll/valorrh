    <?php

        if ( ! is_user_logged_in() ) { 
            wp_redirect( home_url('/login') ); 
            exit; 
        } 

        $current_user = wp_get_current_user();
        $author_id = get_post_field('post_author', get_the_ID());

        $is_admin_editor = current_user_can('administrator') || current_user_can('editor');
        $is_owner = ($current_user->ID == $author_id);

        if ( ! $is_admin_editor && ! $is_owner ) {
            wp_redirect( home_url('/') ); 
            exit; 
        }
    ?>

    <?php
        include("header.php"); 
    ?>
    <div class="row d-flex justify-content-between align-items-center mb-4">
        <div class="col-lg-5 col-sm-12">
            <h2 class="h3 fw-bold text-slate-800"><?php the_title(); ?></h2>
            <p class="text-slate-500 mb-0">Perfil</p>
        </div>
        <div class="col-lg-auto col-sm-12 d-flex gap-3">
            <div class="d-flex gap-2">
                <a href="<?php bloginfo('url');?>/talentos" class="btn btn-light border text-slate-600 fw-medium">Voltar</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="pb-2 d-flex gap-2">
                <button data-tab-target="#tab-profile" class="btn btn-nav-pill active">Dados Gerais</button>
                <button data-tab-target="#tab-vagas" class="btn btn-nav-pill">Candidaturas</button>
            </div>
        </div>
    </div>

    <div id="view-profile" class="flex-grow-1 d-flex flex-column overflow-hidden animate-fade-in">
        <div id="tab-profile" class="tab-content flex-grow-1 overflow-y-auto py-3">
                
                <!-- 1. Dados Pessoais -->
                <div class="card p-4 border border-slate-200 mb-4 border-dashed shadow-sm bg-white">
                    <h3 class="fw-bold text-slate-800 text-uppercase mb-4 d-flex align-items-center gap-2"
                        style="font-size: 0.875rem; letter-spacing: 0.05em;">
                        <i data-lucide="user" width="18" class="text-indigo-500"></i> Sobre
                    </h3>

                    <div class="d-flex flex-column flex-md-row gap-4 align-items-center align-items-md-start">
                        <div class="flex-shrink-0">
                            <?php if (has_post_thumbnail()): ?>
                                <div class="rounded-circle border border-4 border-white shadow-sm overflow-hidden" style="width: 120px; height: 120px;">
                                    <?php the_post_thumbnail('medium', ['class' => 'w-100 h-100 object-fit-cover']); ?>
                                </div>
                            <?php else: ?>
                                <div class="rounded-circle bg-slate-100 border border-4 border-white shadow-sm d-flex align-items-center justify-content-center text-slate-400" style="width: 120px; height: 120px;">
                                    <i data-lucide="user" width="48"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="flex-grow-1 w-100">
                            <div class="row g-2">
                                <div class="col-12 col-md-4">
                                    <label class="form-label text-slate-600 fw-bold mb-0">Nome</label>
                                    <p class="text-slate-800 m-0"><?php the_title(); ?></p>
                                </div>
                                <?php 
                                    $nome_social = get_field('nome_social'); 
                                    if ( $nome_social ): 
                                ?>
                                <div class="col-12 col-md-4">
                                    <label class="form-label text-slate-600 fw-bold mb-0">Nome Social</label>
                                    <p class="text-slate-800 m-0"><?php the_field('nome_social') ?: '-'; ?></p>
                                </div>
                                <?php endif; ?>

                                <div class="col-12 col-md-4">
                                    <label class="form-label text-slate-600 fw-bold mb-0">Nascimento</label>
                                    <p data-type="date" class="text-slate-800 m-0"><?php the_field('nascimento'); ?></p>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label text-slate-600 fw-bold mb-0">CPF</label>
                                    <p class="text-slate-800 m-0"><?php the_field('cpf'); ?></p>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label text-slate-600 fw-bold mb-0">Gênero</label>
                                    <p class="text-slate-800 m-0"><?php the_field('genero'); ?></p>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label text-slate-600 fw-bold mb-0">Estado Civil</label>
                                    <p class="text-slate-800 m-0"><?php the_field('estado_civil'); ?></p>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label text-slate-600 fw-bold mb-0">Nacionalidade</label>
                                    <p class="text-slate-800 m-0"><?php the_field('nacionalidade'); ?></p>
                                </div>
                                 <div class="col-12 col-md-4">
                                    <label class="form-label text-slate-600 fw-bold mb-0">RG</label>
                                    <p class="text-slate-800 m-0"><?php the_field('rg'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- 2. Informações de Contato e Endereço -->
                <div class="card p-4 border border-slate-200 mb-4 border-dashed shadow-sm bg-white">
                    <h3 class="fw-bold text-slate-800 text-uppercase mb-4 d-flex align-items-center gap-2"
                        style="font-size: 0.875rem; letter-spacing: 0.05em;">
                        <i data-lucide="map-pin" width="18" class="text-indigo-500"></i>Endereço
                    </h3>
                    <div class="row g-2">
                        <div class="col-12 col-md-4">
                            <label class="form-label text-slate-600 fw-bold mb-0">E-mail</label>
                            <p data-type="email" class="text-slate-800 m-0"><?php the_field('e-mail'); ?></p>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label text-slate-600 fw-bold mb-0">Telefone Celular</label>
                            <p class="text-slate-800 m-0"><?php the_field('telefone'); ?></p>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label text-slate-600 fw-bold mb-0">CEP</label>
                            <p class="text-slate-800 m-0"><?php the_field('cep'); ?></p>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label text-slate-600 fw-bold mb-0">Logradouro (Rua, Av.)</label>
                            <p class="text-slate-800 m-0"><?php the_field('rua'); ?></p>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label text-slate-600 fw-bold mb-0">Número</label>
                            <p class="text-slate-800 m-0"><?php the_field('numero'); ?></p>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label text-slate-600 fw-bold mb-0">Complemento</label>
                            <p class="text-slate-800 m-0"><?php the_field('complemento'); ?></p>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label text-slate-600 fw-bold mb-0">Bairro</label>
                            <p class="text-slate-800 m-0"><?php the_field('bairro'); ?></p>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label text-slate-600 fw-bold mb-0">Cidade</label>
                            <p class="text-slate-800 m-0"><?php the_field('cidade'); ?></p>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label text-slate-600 fw-bold mb-0">Estado (UF)</label>
                            <p data-type="select" class="text-slate-800 m-0"><?php the_field('estado'); ?></p>
                        </div>
                        <?php
                            $linkedin = get_field('linkedin'); 
                            if ( $linkedin ): 
                        ?>
                            <div class="col-12 col-md-4">
                                <label class="form-label text-slate-600 fw-bold mb-0">LinkedIn</label>
                                <p data-type="url" class="text-slate-800 m-0 text-truncate">
                                    <a href="<?php echo esc_url($linkedin); ?>" target="_blank">Acessar</a>
                                </p>
                            </div>
                        <?php endif; ?>
                        <?php 
                            $portfolio_url = get_field('portfolio'); 
                            if ( $portfolio_url ): 
                        ?>
                            <div class="col-12 col-md-4">
                                <label class="form-label text-slate-600 fw-bold mb-0">Portfólio</label>
                                <p data-type="url" class="text-slate-800 m-0 text-truncate">
                                    <a href="<?php echo esc_url($portfolio_url); ?>" target="_blank">Acessar</a>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- 3. Dados da Vaga e Interesse -->
                <div class="card p-4 border border-slate-200 mb-4 border-dashed shadow-sm bg-white">
                    <h3 class="fw-bold text-slate-800 text-uppercase mb-4 d-flex align-items-center gap-2"
                        style="font-size: 0.875rem; letter-spacing: 0.05em;">
                        <i data-lucide="briefcase" width="18" class="text-indigo-500"></i> Interesse
                    </h3>
                    <div class="row g-2">
                        <div class="col-12 col-md-6">
                            <label class="form-label text-slate-600 fw-bold mb-0">Cargo Pretendido</label>
                            <p class="text-slate-800 m-0"><?php the_field('cargo_pretendido'); ?></p>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label text-slate-600 fw-bold mb-0">Pretensão Salarial (Mensal)</label>
                            <p class="text-slate-800 m-0">R$ <?php the_field('pretensao_salarial'); ?></p>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label text-slate-600 fw-bold mb-0">Modelo Trabalho Preferido</label>
                            <p data-type="select" class="text-slate-800 m-0"><?php the_field('modelo_de_trabalho_preferido'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- 5. Formação Acadêmica -->
                <?php if (have_rows('formacao')): ?>
                    <div class="card p-4 border border-slate-200 mb-4 border-dashed shadow-sm bg-white">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="fw-bold text-slate-800 text-uppercase m-0 d-flex align-items-center gap-2"
                                style="font-size: 0.875rem; letter-spacing: 0.05em;">
                                <i data-lucide="briefcase" width="18" class="text-indigo-500"></i>Formação Acadêmica
                            </h3>
                        </div>
                        
                        <ul class="list-unstyled mb-0" data-list="job-entry">
                            
                            <?php while (have_rows('formacao')): the_row(); 
                                // Subcampos
                                $curso       = get_sub_field('titulo');
                                $nivel       = get_sub_field('nivel');
                                $instituicao = get_sub_field('instituicao');
                                $inicio      = get_sub_field('data_inicial');
                                $conclusao   = get_sub_field('data_final');
                            ?>

                                <li class="border-start border-2 border-slate-100 ps-4 pb-4 position-relative job-item">
                                    <div class="position-absolute top-0 start-0 bg-white border border-2 border-slate-300 rounded-circle" style="width: 0.75rem; height: 0.75rem; margin-left: -0.4rem; margin-top: 0.25rem;"></div>
                                    
                                    <div class="mb-1">
                                        <h4 class="fw-bold text-slate-800 m-0" style="font-size: 1rem;">
                                            <span><?php echo esc_html($curso); ?></span>
                                            <span class="text-slate-400 fw-normal">em</span>
                                            <span><?php echo esc_html($instituicao); ?></span>
                                        </h4>
                                        <div class="d-flex gap-2 text-slate-500 mb-2">Nível <?php echo esc_html($nivel); ?></div>
                                        <div class="d-flex gap-2 text-slate-500 mb-2">
                                            <span><?php echo esc_html($inicio); ?></span> 
                                            - 
                                            <span><?php echo $conclusao ? esc_html($conclusao) : 'Atual'; ?></span>
                                        </div>
                                    </div>
                                </li>

                            <?php endwhile; ?>

                        </ul>
                    </div>
                <?php endif; ?>

                <!-- 5. Experiência Profissional -->
                <?php if (have_rows('experiencia')): ?>
                    <div class="card p-4 border border-slate-200 mb-4 border-dashed shadow-sm bg-white">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="fw-bold text-slate-800 text-uppercase m-0 d-flex align-items-center gap-2"
                                style="font-size: 0.875rem; letter-spacing: 0.05em;">
                                <i data-lucide="briefcase" width="18" class="text-indigo-500"></i> 5. Experiência Profissional
                            </h3>
                        </div>
                        
                        <ul class="list-unstyled mb-0" data-list="job-entry">
                            
                            <?php while (have_rows('experiencia')): the_row(); 
                                // Subcampos
                                $cargo        = get_sub_field('cargo');
                                $empresa      = get_sub_field('empresa');
                                $data_inicial = get_sub_field('data_inicial');
                                $data_final   = get_sub_field('data_final');
                                $atividades   = get_sub_field('atividades');
                            ?>

                                <li class="border-start border-2 border-slate-100 ps-4 pb-4 position-relative job-item">
                                    <div class="position-absolute top-0 start-0 bg-white border border-2 border-slate-300 rounded-circle" style="width: 0.75rem; height: 0.75rem; margin-left: -0.4rem; margin-top: 0.25rem;"></div>
                                    
                                    <div class="mb-1">
                                        <h4 class="fw-bold text-slate-800 m-0" style="font-size: 1rem;">
                                            <span><?php echo esc_html($cargo); ?></span>
                                            <span class="text-slate-400 fw-normal">em</span>
                                            <span><?php echo esc_html($empresa); ?></span>
                                        </h4>
                                        
                                        <div class="d-flex gap-2 text-slate-500 mb-2">
                                            <span><?php echo esc_html($data_inicial); ?></span> 
                                            - 
                                            <span><?php echo $data_final ? esc_html($data_final) : 'Atual'; ?></span>
                                        </div>
                                        
                                        <div class="text-slate-600 m-0">
                                            <?php echo nl2br(esc_html($atividades)); ?>
                                        </div>
                                    </div>
                                </li>

                            <?php endwhile; ?>

                        </ul>
                    </div>
                <?php endif; ?>
                
                <!-- 6. Idiomas e Habilidades -->
                <div class="card p-4 border border-slate-200 mb-4 border-dashed shadow-sm bg-white">
                    <h3 class="fw-bold text-slate-800 text-uppercase mb-4 d-flex align-items-center gap-2" style="font-size: 0.875rem; letter-spacing: 0.05em;">
                        <i data-lucide="languages" width="18" class="text-indigo-500"></i> Idiomas e Habilidades
                    </h3>
                
                    <div -list="language-entry" class="mb-4">
                        <div class="row g-2 mb-3 language-item">
                            <?php if( have_rows('idiomas') ): ?>
                            <div class="col-4 col-md-4">
                                <label class="form-label text-slate-600 fw-bold mb-0">Idiomas</label>
                                <div -list="badge-indigo" class="d-flex flex-wrap gap-2">
                                    <?php while( have_rows('idiomas') ): the_row(); 
                                        $idioma = get_sub_field('idioma');
                                        $nivel = get_sub_field('nivel');
                                    ?>
                                    <span class="badge bg-indigo-50 text-indigo-600 rounded-pill fw-bold border border-indigo-100"><?php echo esc_html($idioma); ?> - <?php echo esc_html($nivel); ?></span>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if( have_rows('ferramentas') ): ?>
                            <div class="col-4 col-md-4">
                                <label class="form-label text-slate-600 fw-bold mb-0">Ferramentas</label>
                                <div -list="badge-indigo" class="d-flex flex-wrap gap-2">
                                    <?php while( have_rows('ferramentas') ): the_row(); 
                                        $ferramenta = get_sub_field('ferramenta');
                                        $nivel = get_sub_field('nivel');
                                    ?>
                                    <span class="badge bg-indigo-50 text-indigo-600 rounded-pill fw-bold border border-indigo-100"><?php echo esc_html($ferramenta); ?> - <?php echo esc_html($nivel); ?></span>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- 7. Informações Complementares e Disponibilidade -->
                <div class="card p-4 border border-slate-200 mb-4 border-dashed shadow-sm bg-white">
                    <h3 class="fw-bold text-slate-800 text-uppercase mb-4 d-flex align-items-center gap-2"
                        style="font-size: 0.875rem; letter-spacing: 0.05em;">
                        <i data-lucide="info" width="18" class="text-indigo-500"></i>Informações Complementares
                    </h3>
                    <div class="row g-2">
                        <div class="col-12 col-md-4">
                            <label class="form-label text-slate-600 fw-bold mb-0">Possui CNH?</label>
                            <p data-type="select" class="text-slate-800 m-0"><?php the_field('cnh'); ?></p>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label text-slate-600 fw-bold mb-0">Disponibilidade para Viagens?</label>
                            <p data-type="select" data-options="Sim, Não" class="text-slate-800 m-0"><?php the_field('disponibilidade_viagem'); ?></p>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label text-slate-600 fw-bold mb-0">Disponibilidade para Mudança?</label>
                            <p data-type="select" data-options="Sim, Não" class="text-slate-800 m-0"><?php the_field('disponibilidade_mudanca'); ?></p>
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label text-slate-600 fw-bold mb-0">Pessoa com Deficiência?</label>
                            <p data-type="select" class="text-slate-800 m-0"><?php the_field('pessoa_pcd'); ?></p>
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label text-slate-600 fw-bold mb-0">CID</label>
                            <p class="text-slate-800 m-0"><?php the_field('cid'); ?></p>
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label text-slate-600 fw-bold mb-0">Cor/Raça</label>
                            <p data-type="select" class="text-slate-800 m-0"><?php the_field('raca'); ?></p>
                        </div>

                        <div class="col-6 col-md-4">
                            <label class="form-label text-slate-600 fw-bold mb-0">Aceite</label>
                            <p class="text-slate-800 m-0">Aceito aos Termos de Privacidade: em <?php the_time('d/m/Y'); ?>.</p>
                        </div>
                    </div>
                </div>

                <!-- 9. Curriculum -->
                <div class="card p-4 border border-slate-200 mb-4 border-dashed shadow-sm bg-white">
                    <h3 class="fw-bold text-slate-800 text-uppercase mb-4 d-flex align-items-center gap-2"
                        style="font-size: 0.875rem; letter-spacing: 0.05em;">
                        <i data-lucide="file-text" width="18" class="text-indigo-500"></i>Curriculum
                    </h3>
                    <?php 
                        $curriculo = get_field('curricullum');
                        if ( $curriculo ):
                        $size = filesize(get_attached_file($curriculo['ID']));
                        $size_mb = number_format($size / 1048576, 1) . ' MB';
                    ?>
                        <div class="row g-2">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex align-items-center justify-content-between hover:bg-slate-50 border-bottom">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="p-2 bg-red-50 text-red-600 rounded">
                                            <i data-lucide="file-text" width="20"></i>
                                        </div>
                                        <div>
                                            <p class="fw-bold text-slate-800 m-0"><?php echo esc_html($curriculo['title']); ?>.pdf</p>
                                            <p class="text-slate-500 m-0"><?php echo $size_mb; ?></p>
                                        </div>
                                    </div>
                                    <a href="<?php echo esc_url($curriculo['url']); ?>"  target="_blank"  download class="btn btn-link text-indigo-600 text-decoration-none fw-bold">
                                       Baixar
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    
        <div id="tab-vagas" class="tab-content hidden flex-grow-1 shadow-sm overflow-y-auto pt-2 animate-fade-in">
            <div class="tab-pane fade show active" id="pills-todas" role="tabpanel">
                <div class="card border-0 shadow-sm flex-grow-1 overflow-hidden d-flex flex-column">
                    <div class="table-responsive flex-grow-1">
                        <?php if (have_rows('candidatura')): ?>
                            <table class="table table-custom w-100 text-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th>Vaga / Empresa</th>
                                        <th>Local / Modelo</th>
                                        <th>Data Aplicação</th>
                                        <th class="text-end">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    while (have_rows('candidatura')): the_row(); 
                                        $data_aplicacao = get_sub_field('data_aplicacao');
                                        $vaga_id = get_sub_field('vaga');

                                        if (is_array($vaga_id)) { $vaga_id = $vaga_id[0]; }

                                        if ($vaga_id): 
                                            $titulo_vaga = get_the_title($vaga_id);
                                            $cidade      = get_field('cidade', $vaga_id);
                                            $estado      = get_field('estado', $vaga_id);
                                            $modelo      = get_field('modelo', $vaga_id);
                                            $link_vaga   = get_permalink($vaga_id);

                                            $empresa_id  = get_field('id_empresa', $vaga_id); 
                                            if (is_array($empresa_id)) { $empresa_id = $empresa_id[0]; }
                                            $nome_empresa = $empresa_id ? get_the_title($empresa_id) : 'Empresa não encontrada';
                                    ?>
                                        <tr class="cursor-pointer">
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="d-flex align-items-center justify-content-center bg-emerald-50 text-emerald-600 rounded-lg flex-shrink-0" style="width: 2.5rem; height: 2.5rem;">
                                                        <i data-lucide="building-2" width="20"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-slate-900"><?php echo esc_html($titulo_vaga); ?></div>
                                                        <div class="text-slate-500"><?php echo esc_html($nome_empresa); ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-slate-700"><?php echo esc_html($cidade); ?>/<?php echo esc_html($estado); ?></div>
                                                <div class="text-slate-500"><?php echo esc_html($modelo); ?></div>
                                            </td>
                                            <td><?php echo esc_html($data_aplicacao); ?></td>
                                            <td class="text-end">
                                                <a href="<?php echo esc_url($link_vaga); ?>?modo=edicao"
                                                    class="btn btn-link text-slate-400 p-2 hover:bg-indigo-50 rounded-circle">
                                                    <i data-lucide="eye" width="16"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endif; endwhile; ?>
                                </tbody>
                            </table>

                        <?php else: ?>
                            <div class="text-center py-5 border rounded-3 bg-slate-50 border-dashed border-slate-200">
                                <div class="bg-white d-inline-flex p-3 rounded-circle shadow-sm mb-3">
                                    <i data-lucide="folder-search" class="text-slate-400" width="32"></i>
                                </div>
                                <h5 class="text-slate-800 fw-bold mb-1">Nenhuma candidatura ainda</h5>
                                <p class="text-slate-500 m-0">As aplicações para vagas aparecerão listadas aqui.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>    

<div class="mb-5"></div>

<?php include("footer.php"); ?>