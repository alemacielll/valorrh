<?php
/**
 * Template Name: Vagas Valor RH (Público)
 */

// 1. Lógica de Busca e Filtros
$search_term = isset($_GET['q']) ? sanitize_text_field(wp_unslash($_GET['q'])) : '';

$args = [
    'post_type'      => 'vaga',
    'post_status'    => 'publish',
    'posts_per_page' => -1, // Mostra todas as vagas que batem com a busca
    's'              => $search_term,
];

$query = new WP_Query($args);

// 2. Função para definir o ícone Lucide baseado no departamento
function get_custom_job_icon($depto) {
    $depto = strtolower($depto);
    if (strpos($depto, 'ti') !== false || strpos($depto, 'tecnologia') !== false) return 'code-2';
    if (strpos($depto, 'rh') !== false || strpos($depto, 'recursos') !== false) return 'users';
    if (strpos($depto, 'finan') !== false) return 'dollar-sign';
    if (strpos($depto, 'venda') !== false || strpos($depto, 'comercial') !== false) return 'shopping-cart';
    if (strpos($depto, 'mkt') !== false || strpos($depto, 'marketing') !== false) return 'megaphone';
    return 'briefcase'; // Ícone padrão
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/styles2.css">
    <?php wp_head(); ?>
    <style>
        body { padding-top: 80px; }
        .header-public { height: 80px; background-color: white; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); z-index: 1030; }
        .search-hero { background-color: #f8fafc; padding: 3rem 0; margin-bottom: 2rem; border-bottom: 1px solid #e2e8f0; }
        .search-input-wrapper { position: relative; max-width: 600px; margin: 0 auto; }
        .search-icon { position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        .search-input-wrapper .form-control { padding-left: 3.5rem; height: 3.5rem; border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
        .job-card { transition: all 0.3s ease; cursor: pointer; border: 1px solid transparent !important; }
        .job-card:hover { transform: translateY(-4px); border-color: #6366f1 !important; box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1) !important; }
        .job-icon-wrapper { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body class="bg-light">

    <header class="header-public fixed-top d-flex align-items-center">
        <div class="container d-flex justify-content-center align-items-center">
            <a href="<?php bloginfo('url'); ?>/vagas-valorrh">
                <img src="<?php bloginfo('template_url'); ?>/img/logo_valor_rh.svg" alt="Valor RH" height="40">
            </a>
        </div>
    </header>

    <section class="search-hero">
        <div class="container text-center">
            <h1 class="h2 fw-bold text-slate-900 mb-3">Encontre sua próxima oportunidade</h1>
            <p class="text-slate-600 mb-4">Explore centenas de vagas em diversas áreas</p>
            
            <form action="<?php the_permalink(); ?>" method="GET" class="search-input-wrapper">
                <i data-lucide="search" class="search-icon"></i>
                <input type="text" name="q" class="form-control" placeholder="Buscar vaga ou área..." value="<?php echo esc_attr($search_term); ?>">
                <button type="submit" style="display:none;">Buscar</button>
            </form>
        </div>
    </section>

    <section class="container mb-5">
        <div class="row g-4">
            <?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); 
                $pid = get_the_ID();
                $depto = get_field('departamento', $pid);
                $cidade = get_field('cidade', $pid);
                $estado = get_field('estado', $pid);
                $modalidade = get_field('modalidade', $pid);
            ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm p-4 job-card d-flex flex-column" onclick="location.href='<?php the_permalink(); ?>'">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="job-icon-wrapper bg-indigo-50 text-indigo-600 rounded-lg">
                                <i data-lucide="<?php echo get_custom_job_icon($depto); ?>" width="24" height="24"></i>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 uppercase fw-bold" style="letter-spacing: 0.05em;">
                                    <?php echo esc_html($depto); ?>
                                </span>
                                <h3 class="h5 fw-bold text-slate-900 m-0"><?php the_title(); ?></h3>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <span class="d-flex align-items-center text-slate-500 text-xs gap-1 bg-slate-100 px-2 py-1 rounded">
                                <i data-lucide="map-pin" width="12"></i> 
                                <?php echo esc_html($cidade); ?>, <?php echo esc_html($estado); ?>
                            </span>
                            <span class="d-flex align-items-center text-slate-500 text-xs gap-1 bg-slate-100 px-2 py-1 rounded">
                                <i data-lucide="briefcase" width="12"></i> 
                                <?php echo esc_html($modalidade); ?>
                            </span>
                        </div>

                        <div class="d-grid gap-2 d-md-flex mt-auto">
                            <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary w-100 rounded-pill">Ver Detalhes</a>
                            
                            <?php 
                            if ( is_user_logged_in() ) : 
                                $vaga_id = get_the_ID();
                                $user_id = get_current_user_id();
                                $id_empresa = get_field('id_empresa', $vaga_id);

                                $ja_inscrito = false;
                                
                                // Busca o post "Talento" do usuário logado
                                $talento_posts = get_posts([
                                    'post_type'      => 'talento',
                                    'author'         => $user_id,
                                    'posts_per_page' => 1,
                                ]);

                                if ( !empty($talento_posts) ) {
                                    $talento_id = $talento_posts[0]->ID;
                                    
                                    // Usamos have_rows que é mais seguro para percorrer repeaters
                                    if( have_rows('candidatura', $talento_id) ):
                                        while( have_rows('candidatura', $talento_id) ) : the_row();
                                            
                                            $vaga_no_repeater = get_sub_field('vaga');

                                            // TRATAMENTO DO DEBUG: Como é um array, pegamos o primeiro índice [0]
                                            if ( is_array($vaga_no_repeater) ) {
                                                $vaga_no_repeater_id = $vaga_no_repeater[0];
                                            } elseif ( is_object($vaga_no_repeater) ) {
                                                $vaga_no_repeater_id = $vaga_no_repeater->ID;
                                            } else {
                                                $vaga_no_repeater_id = $vaga_no_repeater;
                                            }

                                            // Compara o ID da vaga atual do loop com o ID salvo no talento
                                            if ( intval($vaga_no_repeater_id) === intval($vaga_id) ) {
                                                $ja_inscrito = true;
                                                break;
                                            }

                                        endwhile;
                                    endif;
                                }
                            ?>
                                <?php if ($ja_inscrito) : ?>
                                    <button class="btn btn-success w-100 rounded-pill disabled" disabled>
                                        Inscrito <i data-lucide="check" width="16" height="16" class="ms-1"></i>
                                    </button>
                                <?php else : ?>
                                    <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post" class="w-100">
                                        <input type="hidden" name="action" value="processar_candidatura_direta">
                                        <input type="hidden" name="vaga_id" value="<?php echo $vaga_id; ?>">
                                        <input type="hidden" name="empresa_id" value="<?php echo $id_empresa; ?>">
                                        <?php wp_nonce_field('candidatar_vaga_nonce'); ?>
                                        <button type="submit" class="btn btn-primary w-100 rounded-pill">Candidatar-se</button>
                                    </form>
                                <?php endif; ?>

                            <?php else : ?>
                                <a href="<?php bloginfo('url'); ?>/register" class="btn btn-primary w-100 rounded-pill">Candidatar-se</a>
                            <?php endif; ?>
                        </div>
                        
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); else : ?>
                <div class="col-12 text-center py-5">
                    <div class="bg-white p-5 rounded-4 shadow-sm">
                        <i data-lucide="search-x" width="48" height="48" class="text-slate-300 mb-3"></i>
                        <h3 class="h5 fw-bold text-slate-800">Nenhuma vaga encontrada</h3>
                        <p class="text-slate-500">Não encontramos resultados para "<?php echo esc_html($search_term); ?>".</p>
                        <a href="<?php the_permalink(); ?>" class="btn btn-primary rounded-pill px-4 mt-2">Ver todas as vagas</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <footer class="bg-white border-top border-slate-200 py-4 mt-auto">
        <div class="container text-center text-slate-500 text-sm">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Valor RH. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
    <?php wp_footer(); ?>
</body>
</html>