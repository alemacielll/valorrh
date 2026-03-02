<?php
if ( ! is_user_logged_in() ) {
    wp_redirect( home_url('/login') );
    exit;
}

$user = wp_get_current_user();
if ( in_array('subscriber', (array) $user->roles, true ) ) { 
    wp_redirect( home_url('/perfil-candidato') );
    exit;
}

include("header.php");

// 1. Captura de Parâmetros
$q         = isset($_GET['q']) ? sanitize_text_field(wp_unslash($_GET['q'])) : '';
$f_cargo   = isset($_GET['f_cargo']) ? sanitize_text_field(wp_unslash($_GET['f_cargo'])) : '';
$f_situacao = isset($_GET['f_situacao']) ? sanitize_text_field(wp_unslash($_GET['f_situacao'])) : '';

// Paginação
$paged = max(1, (int) get_query_var('paged'));
if ( isset($_GET['paged']) ) {
    $paged = max(1, (int) $_GET['paged']);
}

$args = [
    'post_type'      => 'talento',
    'post_status'    => 'publish',
    'posts_per_page' => 20,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'meta_query'     => ['relation' => 'AND']
];

// Busca por Título (Nativo WP)
if ( $q !== '' ) {
    $args['s'] = $q;
}

// Filtro por Cargo Pretendido (Meta Field)
if ( !empty($f_cargo) ) {
    $args['meta_query'][] = [
        'key'     => 'cargo_pretendido',
        'value'   => $f_cargo,
        'compare' => 'LIKE', // LIKE permite buscar parte do nome do cargo
    ];
}

// Filtro de Situação
if ( !empty($f_situacao) ) {
    $args['meta_query'][] = [
        'key'     => 'situacao',
        'value'   => $f_situacao,
        'compare' => '=',
    ];
}

$query = new WP_Query($args);
?>

<div class="row d-flex justify-content-between align-items-center mb-4">
    <div class="col-lg-5 col-sm-12">
        <h2 class="h3 fw-bold text-slate-800"><?php the_title(); ?></h2>
        <p class="text-slate-500"><?php the_field('subtitulo'); ?></p>
    </div>
    <div class="col-lg-auto col-sm-12 d-flex gap-3">
        <a href="<?php bloginfo('url'); ?>/adicionar-vaga" class="btn btn-primary d-flex align-items-center gap-2">
            <i data-lucide="plus" width="16"></i> Nova Vaga
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm flex-grow-1 overflow-hidden d-flex flex-column">
    <div class="p-3 border-bottom border-slate-200">
        <form method="get" class="row g-2 align-items-center" action="<?php echo esc_url(get_permalink()); ?>">
            
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i data-lucide="search" width="16"></i></span>
                    <input type="text" name="q" class="form-control border-start-0" placeholder="Nome do candidato..." value="<?php echo esc_attr($q); ?>" />
                </div>
            </div>

            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i data-lucide="briefcase" width="16"></i></span>
                    <input type="text" name="f_cargo" class="form-control border-start-0" placeholder="Cargo pretendido..." value="<?php echo esc_attr($f_cargo); ?>" />
                </div>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-primary flex-grow-1" type="submit">Filtrar</button>
                <?php if ( $q !== '' || $f_cargo !== '' || $f_situacao !== '' ) : ?>
                    <a class="btn btn-outline-secondary" href="<?php echo esc_url(get_permalink()); ?>" title="Limpar Filtros">
                        <i data-lucide="rotate-ccw" width="16"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="table-responsive flex-grow-1">
        <table class="table table-custom w-100 text-nowrap mb-0">
            <thead>
                <tr>
                    <th>Candidato</th>
                    <th>Localização / Cargo</th>
                    <th>Gênero</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ( $query->have_posts() ) : ?>
                    <?php while ( $query->have_posts() ) : $query->the_post(); 
                        $post_id = get_the_ID();
                        $email   = get_field('e-mail', $post_id);
                        $cargo   = get_field('cargo_pretendido', $post_id);
                        $cidade  = get_field('cidade', $post_id);
                        $estado  = get_field('estado', $post_id);
                        $local   = trim((string)$cidade . ($estado ? ', ' . $estado : ''));
                    ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center justify-content-center bg-slate-100 text-slate-500 rounded-lg" style="width: 2.5rem; height: 2.5rem;">
                                        <i data-lucide="user" width="20"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-slate-900"><?php the_title(); ?></div>
                                        <div class="text-slate-500" style="font-size: 0.85rem;"><?php echo esc_html($email); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-slate-700"><?php echo esc_html($local ?: '-'); ?></div>
                                <div class="text-slate-500 small"><?php echo esc_html($cargo ?: '-'); ?></div>
                            </td>
                            <td><span class="text-slate-600"><?php echo esc_html(get_field('genero', $post_id) ?: '-'); ?></span></td>
                            <td class="text-end">
                                <a href="<?php the_permalink(); ?>?modo=edicao" class="btn btn-link text-slate-400 p-2 hover:bg-indigo-50 rounded-circle">
                                    <i data-lucide="eye" width="16"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; wp_reset_postdata(); ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4" class="p-5 text-center text-slate-500">
                            <i data-lucide="search-x" width="48" class="mb-3 opacity-20"></i>
                            <p>Nenhum talento encontrado.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include("footer.php"); ?>