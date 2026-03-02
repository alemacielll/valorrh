<?php 
get_header(); 

// Recuperar dados do post atual
$post_id = get_the_ID();
$id_empresa = get_field('id_empresa');
$situacao = get_field('situacao');
$departamento = get_field('departamento');
$modelo = get_field('modelo');
$regime = get_field('regime');
$cidade = get_field('cidade');
$estado = get_field('estado');
$carga_horaria = get_field('carga_horaria');
$escolaridade = get_field('escolaridade');
$idioma = get_field('idioma');
$sal_min = get_field('faixa_inicial');
$sal_max = get_field('faixa_final');
$limite_raw = get_field('limite', false, false);
$data_limite = $limite_raw ? date('d/m/Y', strtotime($limite_raw)) : 'Não definido';
?>

<div class="border-bottom">
    <div class="">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <a href="<?php bloginfo('url'); ?>/vagas" class="btn btn-sm btn-link text-slate-500 text-decoration-none p-0 d-flex align-items-center gap-1 hover:text-indigo-600" style="font-size: 0.75rem;">
                <i data-lucide="chevron-left" width="14"></i>
                Voltar
            </a>
            <div class="d-flex align-items-center gap-2">
                <span class="badge-custom <?php echo ($situacao === 'Aberta') ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200'; ?> px-2 rounded-pill border">
                    <?php echo $situacao ? $situacao : 'Indefinida'; ?>
                </span>
            </div>
        </div>

        <div class="d-flex align-items-start justify-content-between mb-4">
            <div>
                <h3 class="h4 fw-bold text-slate-900 mb-1"><?php the_title(); ?></h3>
                <div class="d-flex align-items-center gap-3">
                    <p class="text-slate-500 fw-medium d-flex align-items-center gap-2 mb-0" style="font-size: 0.875rem;">
                        <i data-lucide="building-2" width="14"></i> 
                        <span><?php echo get_the_title($id_empresa); ?></span>
                        <span class="bg-slate-300 rounded-circle" style="width: 4px; height: 4px;"></span>
                        <span><?php echo $departamento; ?></span>
                    </p>
                    <a href="<?php the_permalink(); ?>?modo=edicao" class="btn btn-sm btn-light border-indigo-200 text-indigo-600 d-flex align-items-center gap-2 shadow-sm py-0 px-2" style="font-size: 0.75rem;">
                        <i data-lucide="edit-2" width="12"></i> Editar
                    </a>                                    
                </div>
            </div>
            <div class="text-end">
                <p class="text-slate-900 fw-bold fs-5 mb-0">
                    <?php 
                        if ($sal_min && $sal_max) {
                            echo 'R$ ' . number_format($sal_min, 0, ',', '.') . ' - ' . number_format($sal_max, 0, ',', '.');
                        } elseif ($sal_min || $sal_max) {
                            echo 'R$ ' . number_format($sal_min ?: $sal_max, 0, ',', '.');
                        } else {
                            echo 'Combinar';
                        }
                    ?>
                </p>
                <p class="text-slate-500 mb-0" style="font-size: 0.75rem;"><?php echo "$regime • $modelo"; ?></p>
            </div>
        </div>
    </div>

    <div class="pb-2 d-flex gap-2">
        <button data-tab-target="#tab-pipeline" class="btn btn-nav-pill active">Seleção</button>
        <button data-tab-target="#tab-details" class="btn btn-nav-pill">Detalhes</button>                       
    </div>
</div>

<div id="tab-pipeline" class="tab-content flex-grow-1 overflow-x-auto overflow-y-hidden pt-4">
    <div class="d-flex gap-4 h-100" style="min-width: 1000px;">
        <?php
            // 1. Query para buscar as candidaturas vinculadas a esta vaga específica
            $args_candidaturas = array(
                'post_type'      => 'candidatura',
                'posts_per_page' => -1,
                'meta_query'     => array(
                    array(
                        'key'     => 'vaga',
                        'value'   => $post_id,
                        'compare' => '=' 
                    )
                )
            );
            $query_candidaturas = new WP_Query($args_candidaturas);
        ?>

        <div class="kanban-col d-flex flex-column h-100 rounded-xl border bg-slate-50 w-100">
            <div class="d-flex align-items-center justify-content-between p-3 border-bottom border-2 border-slate-200">
                <h3 class="fw-semibold text-slate-700 m-0 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">Candidaturas</h3>
                <span class="kanban-counter bg-white text-slate-600 px-2 py-0.5 rounded-pill shadow-sm border border-slate-100 fw-bold" style="font-size: 0.75rem;">
                    <?php echo $query_candidaturas->found_posts; ?>
                </span>
            </div>
            
            <div class="flex-grow-1 overflow-y-auto p-3 d-flex flex-column gap-3">
                <?php 
                    if ($query_candidaturas->have_posts()) : 
                        while ($query_candidaturas->have_posts()) : $query_candidaturas->the_post(); 
                            
                            $author_id = get_the_author_meta('ID');
                            
                            // 1. Buscar o post do tipo 'talento' que pertence a este autor
                            $talento_query = get_posts(array(
                                'post_type'      => 'talento',
                                'author'         => $author_id,
                                'posts_per_page' => 1
                            ));

                            // Inicializamos variáveis vazias para evitar erros
                            $nome_candidato = get_the_author_meta('display_name'); // Fallback para o nome do usuário
                            $perfil_url = "#";
                            $profissao = "Candidato";
                            $idade = "--";

                            if ($talento_query) {
                                $talento_id = $talento_query[0]->ID;
                                $nome_candidato = get_the_title($talento_id);
                                $perfil_url = get_permalink($talento_id);
                                $profissao = get_field('profissao', $talento_id) ?: 'Candidato';
                                
                                // 2. Lógica para calcular a idade a partir do campo 'nascimento' (ACF)
                                $nascimento = get_field('nascimento', $talento_id, false); // 'false' para pegar o valor bruto YYYYMMDD
                                if ($nascimento) {
                                    $data_nasc = new DateTime($nascimento);
                                    $hoje = new DateTime();
                                    $idade = $hoje->diff($data_nasc)->y;
                                }
                            }

                            $inicial = mb_substr($nome_candidato, 0, 1);
                ?>
                    <div class="card kanban-card border border-slate-200 shadow-sm p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="text-slate-300"><i data-lucide="grip-vertical" width="14"></i></div>
                                <div class="d-flex align-items-center justify-content-center bg-indigo-100 text-indigo-700 rounded-circle fw-bold"
                                    style="width: 2rem; height: 2rem; font-size: 0.75rem;"><?php echo esc_html($inicial); ?></div>
                                <div>
                                    <p class="fw-medium text-slate-800 m-0 lh-1" style="font-size: 0.875rem;">
                                        <?php echo esc_html($nome_candidato); ?>
                                    </p>
                                    <p class="text-slate-500 m-0 mt-1" style="font-size: 0.75rem;">
                                        <?php echo esc_html($profissao); ?> • <?php echo $idade; ?> anos
                                    </p>
                                </div>
                            </div>
                            <button class="btn btn-link p-0 text-slate-300 hover:text-slate-600">
                                <i data-lucide="more-vertical" width="16"></i>
                            </button>
                        </div>
                        <div class="mt-2 d-flex gap-2">
                            <a href="<?php echo esc_url($perfil_url); ?>" target="_blank" class="btn btn-sm btn-light border w-100 text-slate-600 text-decoration-none" style="font-size: 0.75rem;">Ver Perfil</a>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); else : ?>
                    <div class="text-center py-5 text-slate-400" style="font-size: 0.875rem;">
                        <p class="m-0">Nenhuma candidatura.</p>
                    </div>
                <?php endif; ?>
                </div>
        </div>

        <div class="kanban-col d-flex flex-column h-100 rounded-xl border bg-slate-50 w-100">
            <div class="d-flex align-items-center justify-content-between p-3 border-bottom border-2 border-blue-300">
                <h3 class="fw-semibold text-slate-700 m-0 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">Triagem</h3>
                <span class="kanban-counter bg-white text-slate-600 px-2 py-0.5 rounded-pill shadow-sm border border-slate-100 fw-bold" style="font-size: 0.75rem;">1</span>
            </div>
            <div class="flex-grow-1 overflow-y-auto p-3 d-flex flex-column gap-3">
                <div class="card kanban-card border border-slate-200 shadow-sm p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="text-slate-300"><i data-lucide="grip-vertical" width="14"></i></div>
                            <div class="d-flex align-items-center justify-content-center bg-indigo-100 text-indigo-700 rounded-circle fw-bold" style="width: 2rem; height: 2rem; font-size: 0.75rem;">M</div>
                            <div>
                                <p class="fw-medium text-slate-800 m-0 lh-1" style="font-size: 0.875rem;">Maria Souza</p>
                                <p class="text-slate-500 m-0 mt-1" style="font-size: 0.75rem;">Designer - 30 anos</p>
                            </div>
                        </div>
                        <button class="btn btn-link p-0 text-slate-300 hover:text-slate-600"><i data-lucide="more-vertical" width="16"></i></button>
                    </div>
                    <div class="mt-2 d-flex gap-2">
                        <a href="#" target="_blank" class="btn btn-sm btn-light border w-100 text-slate-600 text-decoration-none" style="font-size: 0.75rem;">Detalhes</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="kanban-col d-flex flex-column h-100 rounded-xl border bg-slate-50 w-100">
            <div class="d-flex align-items-center justify-content-between p-3 border-bottom border-2 border-amber-300">
                <h3 class="fw-semibold text-slate-700 m-0 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">Entrevista</h3>
                <span class="kanban-counter bg-white text-slate-600 px-2 py-0.5 rounded-pill shadow-sm border border-slate-100 fw-bold" style="font-size: 0.75rem;">0</span>
            </div>
            <div class="flex-grow-1 overflow-y-auto p-3 d-flex flex-column gap-3"></div>
        </div>

        <div class="kanban-col d-flex flex-column h-100 rounded-xl border bg-slate-50 w-100">
            <div class="d-flex align-items-center justify-content-between p-3 border-bottom border-2 border-emerald-300">
                <h3 class="fw-semibold text-slate-700 m-0 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">Encaminhamento</h3>
                <span class="kanban-counter bg-white text-slate-600 px-2 py-0.5 rounded-pill shadow-sm border border-slate-100 fw-bold" style="font-size: 0.75rem;">0</span>
            </div>
            <div class="flex-grow-1 overflow-y-auto p-3 d-flex flex-column gap-3"></div>
        </div>
    </div>
</div>

<div id="tab-details" class="tab-content hidden flex-grow-1 pt-4 animate-fade-in">
    <div class="container-fluid pt-4 px-0">
        <div class="row g-4 position-relative">

            <div class="col-12 col-lg-8 mt-1 mt-lg-0">

                <div class="card p-4 border-0 shadow-sm mb-4">
                    <h3 class="h5 fw-bold text-slate-800 mb-3">Sobre a Vaga</h3>
                    <div class="text-slate-600 mb-4" style="font-size: 0.875rem;">
                        <?php echo wpautop(get_field('descricao')); ?>
                    </div>

                    <h4 class="fw-semibold text-slate-800 mb-2" style="font-size: 0.875rem;">Responsabilidades e Atribuições</h4>
                    <div class="text-slate-600 mb-4 list-style-custom" style="font-size: 0.875rem;">
                        <?php echo get_field('responsabilidades'); ?>
                    </div>

                    <h4 class="fw-semibold text-slate-800 mb-2" style="font-size: 0.875rem;">Sobre a Empresa</h4>
                    <p class="text-slate-600 mb-0" style="font-size: 0.875rem;">
                        <?php echo get_the_excerpt($id_empresa); ?>
                    </p>
                </div>

                <div class="card p-4 border-0 shadow-sm">
                    <h3 class="h5 fw-bold text-slate-800 mb-3">Requisitos</h3>
                    <div class="row g-4">
                        <div class="col-12 col-md-6">
                            <h4 class="fw-semibold text-slate-700 mb-3 d-flex align-items-center gap-2" style="font-size: 0.875rem;">
                                <i data-lucide="briefcase" width="16"></i> Hard Skills
                            </h4>
                            <div class="text-slate-600" style="font-size: 0.875rem;">
                                <?php the_field('hard_skills'); ?>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <h4 class="fw-semibold text-slate-700 mb-3 d-flex align-items-center gap-2" style="font-size: 0.875rem;">
                                <i data-lucide="check-circle" width="16"></i> Diferenciais
                            </h4>
                            <div class="text-slate-600" style="font-size: 0.875rem;">
                                <?php the_field('diferenciais'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h4 class="fw-semibold text-slate-700 mb-2" style="font-size: 0.875rem;">Soft Skills</h4>
                        <div class="text-slate-600" style="font-size: 0.875rem;">
                            <?php the_field('soft_skills'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4 mt-5 mt-lg-0">
                <div class="d-flex flex-column gap-4">
                    <div class="card p-4 border-0 shadow-sm">
                        <h3 class="fw-bold text-slate-800 text-uppercase mb-3" style="font-size: 0.75rem; letter-spacing: 0.05em;">Detalhes Operacionais</h3>
                        <ul class="list-unstyled d-flex flex-column gap-3 mb-0">
                            <li class="d-flex align-items-start gap-3">
                                <i data-lucide="map-pin" class="text-slate-400 mt-1" width="16"></i>
                                <div>
                                    <p class="fw-medium text-slate-700 m-0" style="font-size: 0.875rem;">Localização</p>
                                    <p class="text-slate-500 m-0" style="font-size: 0.875rem;"><?php echo "$cidade, $estado ($modelo)"; ?></p>
                                </div>
                            </li>
                            <li class="d-flex align-items-start gap-3">
                                <i data-lucide="clock" class="text-slate-400 mt-1" width="16"></i>
                                <div>
                                    <p class="fw-medium text-slate-700 m-0" style="font-size: 0.875rem;">Carga Horária</p>
                                    <p class="text-slate-500 m-0" style="font-size: 0.875rem;"><?php echo $carga_horaria; ?></p>
                                </div>
                            </li>
                            <li class="d-flex align-items-start gap-3">
                                <i data-lucide="graduation-cap" class="text-slate-400 mt-1" width="16"></i>
                                <div>
                                    <p class="fw-medium text-slate-700 m-0" style="font-size: 0.875rem;">Escolaridade</p>
                                    <p class="text-slate-500 m-0" style="font-size: 0.875rem;">
                                        <?php echo str_replace('_', ' ', ucfirst($escolaridade)); ?>
                                    </p>
                                </div>
                            </li>
                            <li class="d-flex align-items-start gap-3">
                                <i data-lucide="languages" class="text-slate-400 mt-1" width="16"></i>
                                <div>
                                    <p class="fw-medium text-slate-700 m-0" style="font-size: 0.875rem;">Idiomas</p>
                                    <p class="text-slate-500 m-0" style="font-size: 0.875rem;"><?php echo $idioma; ?></p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="card p-4 border-0 shadow-sm">
                        <h3 class="fw-bold text-slate-800 text-uppercase mb-3 d-flex align-items-center gap-2" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                            <i data-lucide="gift" width="16"></i> Benefícios
                        </h3>
                        <div class="text-slate-600" style="font-size: 0.875rem;">
                            <?php echo nl2br(get_field('beneficios')); ?>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <div class="d-flex align-items-center gap-2 mb-2 fw-medium text-slate-800" style="font-size: 0.875rem;">
                            <i data-lucide="calendar" width="16"></i>
                            Prazo para Candidatura
                        </div>
                        <p class="text-red-500 fw-medium m-0" style="font-size: 0.75rem;">Até <?php echo $data_limite; ?></p>            
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php get_footer(); ?>