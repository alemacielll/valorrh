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
$q = isset($_GET['q']) ? sanitize_text_field(wp_unslash($_GET['q'])) : '';

// Paginação
$paged = max(1, (int) get_query_var('paged'));
if ( isset($_GET['paged']) ) {
    $paged = max(1, (int) $_GET['paged']);
}

// Filtro obrigatório por empresa do usuário logado (Trava de Segurança)
$current_user_id = get_current_user_id();
$user_empresa_id = get_user_meta($current_user_id, 'id_empresa', true);

$args = [
    'post_type'      => 'empresa',
    'post_status'    => 'publish',
    'posts_per_page' => 20,
    'paged'          => $paged,
    'orderby'        => 'title',
    'order'          => 'ASC',
];

// Busca textual
if ( $q !== '' ) {
    $args['s'] = $q;
}

$query = new WP_Query($args);
?>

<div class="row d-flex justify-content-between align-items-center mb-4">
    <div class="col-lg-5 col-sm-12">
        <h2 class="h3 fw-bold text-slate-800"><?php the_title(); ?></h2>
        <p class="text-slate-500"><?php the_field('subtitulo'); ?></p>
    </div>
    <div class="col-lg-auto col-sm-12 d-flex gap-3">
        <a href="<?php bloginfo('url'); ?>/adicionar-empresa" class="btn btn-primary d-flex align-items-center gap-2">
            <i data-lucide="plus" width="16" class="text-indigo-500"></i>
            Nova Empresa
        </a>
    </div>
</div>

<?php if (isset($_GET['excluido']) && $_GET['excluido'] === 'sucesso') : ?>
    <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center gap-2">
        <i data-lucide="check-circle" width="20"></i>
        A empresa foi <strong>removida</strong> com sucesso.
    </div>
    <script>
        window.history.replaceState({}, document.title, window.location.pathname);
    </script>
<?php endif; ?>

<div class="card border-0 shadow-sm flex-grow-1 overflow-hidden d-flex flex-column">

    <div class="p-3 border-bottom border-slate-200">
        <form method="get" class="row g-2 align-items-center" action="<?php echo esc_url(get_permalink()); ?>">
            
            <div class="col-md-11">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i data-lucide="search" width="16"></i></span>
                    <input type="text" name="q" class="form-control border-start-0" placeholder="Busque por título ou CNPJ..." value="<?php echo esc_attr($q); ?>" />
                </div>
            </div>

            <div class="col-md-1 d-flex gap-2">
                <button class="btn btn-primary flex-grow-1" type="submit">Buscar</button>
                <?php if ( $q !== '' ) : ?>
                    <a class="btn btn-outline-secondary" href="<?php echo esc_url(get_permalink()); ?>" title="Limpar Busca">
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
                    <th>Empresa</th>
                    <th>Segmento / Porte</th>
                    <th>Contato</th>
                    <th>Responsável</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ( $query->have_posts() ) : ?>
                    <?php while ( $query->have_posts() ) : $query->the_post(); 
                        $post_id     = get_the_ID();
                        $segmento    = get_field('segmento', $post_id);
                        $porte       = get_field('porte', $post_id);
                        $cnpj        = get_field('cnpj', $post_id);
                        $email       = get_field('e-mail', $post_id);
                        $telefone    = get_field('telefone', $post_id);
                        $responsavel = get_field('responsavel', $post_id);
                        $cargo_responsavel = get_field('cargo_responsavel', $post_id);
                    ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center justify-content-center bg-emerald-50 text-emerald-600 rounded-lg flex-shrink-0"
                                                        style="width: 2.5rem; height: 2.5rem;">
                                                        <i data-lucide="building-2" width="20"></i>
                                                    </div>
                                    <div>
                                        <div class="fw-bold text-slate-900"><?php the_title(); ?></div>
                                        <div class="text-slate-500"><?php echo esc_html($cnpj); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-slate-700"><?php echo esc_html($segmento ?: '-'); ?></div>
                                <div class="text-slate-500"><?php echo esc_html($porte ?: '-'); ?></div>
                            </td>
                            <td>
                                <div class="text-slate-700"><?php echo esc_html($email?: '-'); ?></div>
                                <div class="text-slate-500"><?php echo esc_html($telefone?: '-'); ?></div>
                            </td>
                            <td>
                                <div class="text-slate-700"><?php echo esc_html($responsavel?: '-'); ?></div>
                                <div class="text-slate-500"><?php echo esc_html($cargo_responsavel?: '-'); ?></div>
                            </td>
                            <td class="text-end">
                                <a href="<?php the_permalink(); ?>?modo=edicao" class="btn btn-link text-slate-400 p-2 hover:bg-indigo-50 rounded-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar">
                                    <i data-lucide="pencil" width="16"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; wp_reset_postdata(); ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="p-5 text-center text-slate-500">
                            <i data-lucide="search-x" width="48" class="mb-3 opacity-20"></i>
                            <p>Nenhuma empresa encontrada.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="p-3 border-top border-slate-200 d-flex align-items-center justify-content-center mt-auto">
        <?php
        $total_pages = (int) $query->max_num_pages;
        if ( $total_pages > 1 ) {
            $add_args = [];
            if ($q) $add_args['q'] = $q;

            $links = paginate_links([
                'base'      => esc_url_raw( add_query_arg( array_merge($add_args, ['paged' => '%#%']), get_permalink() ) ),
                'format'    => '',
                'current'   => $paged,
                'total'     => $total_pages,
                'type'      => 'array',
                'prev_text' => '&laquo;',
                'next_text' => '&raquo;',
            ]);

            if ($links) {
                echo '<nav><ul class="pagination mb-0">';
                foreach ($links as $link) {
                    $active = strpos($link, 'current') !== false ? ' active' : '';
                    echo '<li class="page-item'.$active.'">' . str_replace('page-numbers', 'page-link', $link) . '</li>';
                }
                echo '</ul></nav>';
            }
        }
        ?>
    </div>
</div>

<?php include("footer.php"); ?>