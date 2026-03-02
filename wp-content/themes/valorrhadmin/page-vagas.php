<?php
if ( ! is_user_logged_in() ) {wp_redirect( home_url('/login') );
    exit;
    }
    $user = wp_get_current_user();
    if ( in_array('subscriber', (array) $user->roles, true ) ) { wp_redirect( home_url('/perfil-candidato') );
    exit;
    }

include("header.php");

// 1. Captura de Parâmetros
$q          = isset($_GET['q']) ? sanitize_text_field(wp_unslash($_GET['q'])) : '';
$f_empresa  = isset($_GET['f_empresa']) ? sanitize_text_field(wp_unslash($_GET['f_empresa'])) : '';
$f_situacao = isset($_GET['f_situacao']) ? sanitize_text_field(wp_unslash($_GET['f_situacao'])) : '';

// Paginação
$paged = max(1, (int) get_query_var('paged'));
if ( isset($_GET['paged']) ) {
    $paged = max(1, (int) $_GET['paged']);
}

// Filtro obrigatório por empresa do usuário logado (se houver)
$current_user_id = get_current_user_id();
$user_empresa_id = get_user_meta($current_user_id, 'id_empresa', true);

$args = [
    'post_type'      => 'vaga',
    'post_status'    => 'publish',
    'posts_per_page' => 20,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'meta_query'     => ['relation' => 'AND']
];

// Busca textual
if ( $q !== '' ) {
    $args['s'] = $q;
}

// Lógica de Filtros Meta

// Filtro de Situação
if ( !empty($f_situacao) ) {
    $args['meta_query'][] = [
        'key'     => 'situacao',
        'value'   => $f_situacao,
        'compare' => '=',
    ];
}

$query = new WP_Query($args);

// Helpers
function argo_vaga_status_badge($raw_status) {
    $st = mb_strtolower(trim((string) $raw_status));
    $is_open = in_array($st, ['aberta', 'aberto', 'open', 'ativa', 'ativo']);
    $label = $is_open ? 'Aberta' : 'Encerrada';
    $cls = $is_open
        ? 'badge rounded-pill bg-emerald-100 text-emerald-700 border border-emerald-200'
        : 'badge rounded-pill bg-slate-100 text-slate-600 border border-slate-200';

    return '<span class="' . esc_attr($cls) . '" style="font-size: 0.75rem;">' . esc_html($label) . '</span>';
}

function argo_empresa_nome_por_id($id_empresa) {
    if ( is_numeric($id_empresa) && $id_empresa > 0 ) {
        $t = get_the_title($id_empresa);
        if ( $t ) return $t;
    }
    return (string) $id_empresa;
}
?>

<div class="row d-flex justify-content-between align-items-center mb-4">
    <div class="col-lg-5 col-sm-12">
        <h2 class="h3 fw-bold text-slate-800"><?php the_title(); ?></h2>
        <p class="text-slate-500"><?php the_field('subtitulo'); ?></p>
    </div>
    <div class="col-lg-auto col-sm-12 d-flex gap-3">
        <a href="<?php bloginfo('url'); ?>/vagas-valorrh" target="_blank" class="btn btn-light border text-slate-600 fw-medium d-flex align-items-center gap-2">
            <i data-lucide="external-link" width="16"></i> Central de Vagas
        </a>
        <a href="<?php bloginfo('url'); ?>/adicionar-vaga" class="btn btn-primary d-flex align-items-center gap-2">
            <i data-lucide="plus" width="16" class="text-indigo-500"></i>
            Nova Vaga
        </a>
    </div>
</div>

<?php if (isset($_GET['excluido']) && $_GET['excluido'] === 'sucesso') : ?>
    <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center gap-2">
        <i data-lucide="check-circle" width="20"></i>
        A vaga foi <strong>removida</strong> com sucesso.
    </div>
    <script>
        // Limpa a URL para não mostrar a mensagem de novo ao atualizar a página
        window.history.replaceState({}, document.title, window.location.pathname);
    </script>
<?php endif; ?>

<div class="card border-0 shadow-sm flex-grow-1 overflow-hidden d-flex flex-column">

    <div class="p-3 border-bottom border-slate-200">
        <form method="get" class="row g-2 align-items-center" action="<?php echo esc_url(get_permalink()); ?>">
            
            <div class="col-md-7">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i data-lucide="search" width="16"></i></span>
                    <input type="text" name="q" class="form-control border-start-0" placeholder="Busque por título..." value="<?php echo esc_attr($q); ?>" />
                </div>
            </div>

            <div class="col-md-2">
                <select name="f_empresa" class="form-select">
                    <option value="">Todas as Empresas</option>
                    <?php
                    // Busca todas as empresas para o select (ajuste o post_type se necessário)
                    $empresas = get_posts(['post_type' => 'empresa', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC']);
                    foreach ($empresas as $emp) {
                        $selected = ($f_empresa == $emp->ID) ? 'selected' : '';
                        echo '<option value="'.esc_attr($emp->ID).'" '.$selected.'>'.esc_html($emp->post_title).'</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-2">
                <select name="f_situacao" class="form-select">
                    <option value="">Todas Situações</option>
                    <option value="Aberta" <?php selected($f_situacao, 'Aberta'); ?>>Aberta</option>
                    <option value="Encerrada" <?php selected($f_situacao, 'Encerrada'); ?>>Encerrada</option>
                </select>
            </div>

            <div class="col-md-1 d-flex gap-2">
                <button class="btn btn-primary flex-grow-1" type="submit">Filtrar</button>
                <?php if ( $q !== '' || $f_empresa !== '' || $f_situacao !== '' ) : ?>
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
                    <th>Vaga / Empresa</th>
                    <th>Local / Modelo</th>
                    <th>Departamento</th>
                    <th>Situação</th>
                    <!-- <th>Prazo</th> -->
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ( $query->have_posts() ) : ?>
                    <?php while ( $query->have_posts() ) : $query->the_post(); 
                        $post_id      = get_the_ID();
                        $id_empresa   = get_field('id_empresa', $post_id);
                        $situacao     = get_field('situacao', $post_id);
                        $cidade       = get_field('cidade', $post_id);
                        $estado       = get_field('estado', $post_id);
                        $departamento = get_field('departamento', $post_id);
                        $prazo        = get_field('limite', $post_id);
                        $modelo       = get_field('modelo', $post_id);
                        $empresa_nome = argo_empresa_nome_por_id($id_empresa);

                        $local = trim((string)$cidade . ($estado ? ', ' . $estado : ''));
                        if (empty($local)) $local = '-';

                        $st = mb_strtolower(trim((string)$situacao));
                        $is_open = in_array($st, ['aberta','aberto','open','ativa','ativo'], true);
                        $icon = $is_open ? 'briefcase' : 'lock';
                        $icon_wrap_cls = $is_open ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500';
                    ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center justify-content-center <?php echo esc_attr($icon_wrap_cls); ?> rounded-lg flex-shrink-0" style="width: 2.5rem; height: 2.5rem;">
                                        <i data-lucide="<?php echo esc_attr($icon); ?>" width="20"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-slate-900"><?php the_title(); ?></div>
                                        <div class="text-slate-500"><?php echo esc_html($empresa_nome ?: '-'); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-slate-700"><?php echo esc_html($local); ?></div>
                                <div class="text-slate-500"><?php echo esc_html($modelo ?: '-'); ?></div>
                            </td>
                            <td><span class="text-slate-600"><?php echo esc_html($departamento ?: '-'); ?></span></td>
                            <td><?php echo argo_vaga_status_badge($situacao); ?></td>
                            <!-- <td class="text-slate-700"><?php echo esc_html($prazo ?: '-'); ?></td> -->
                            <td class="text-end">
                                <a target="_blank" href="<?php the_permalink(); ?>" class="btn btn-link text-slate-400 p-2 hover:bg-indigo-50 rounded-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Link Divulgação" aria-label="Link Divulgação"
                                >
                                    <i data-lucide="external-link" width="16"></i>
                                </a>
                                <a href="<?php the_permalink(); ?>?modo=selecao" class="btn btn-link text-slate-400 p-2 hover:bg-indigo-50 rounded-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Seleção" aria-label="Seleção">
                                    <i data-lucide="users" width="16"></i>
                                </a>
                                <a href="<?php the_permalink(); ?>?modo=edicao" class="btn btn-link text-slate-400 p-2 hover:bg-indigo-50 rounded-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar" aria-label="Editar">
                                    <i data-lucide="pencil" width="16"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; wp_reset_postdata(); ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="p-5 text-center text-slate-500">
                            <i data-lucide="search-x" width="48" class="mb-3 opacity-20"></i>
                            <p>Nenhuma vaga encontrada com os filtros aplicados.</p>
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
            if ($f_empresa) $add_args['f_empresa'] = $f_empresa;
            if ($f_situacao) $add_args['f_situacao'] = $f_situacao;

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