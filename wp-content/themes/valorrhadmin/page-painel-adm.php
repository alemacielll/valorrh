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

// --- QUERIES DINÂMICAS ---

// 1. Vagas Abertas (Baseado na meta 'situacao' do post type 'vaga')
$vagas_query = new WP_Query([
    'post_type'      => 'vaga',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'meta_query'     => [
        [
            'key'     => 'situacao',
            'value'   => 'Aberta',
            'compare' => '='
        ]
    ]
]);
$total_vagas_abertas = $vagas_query->found_posts;

// 2. Novos Candidatos (Inscritos no mês atual com role 'subscriber')
$novos_candidatos_query = new WP_User_Query([
    'role'       => 'subscriber',
    'date_query' => [
        [
            'year'  => date('Y'),
            'month' => date('m'),
        ],
    ],
]);
$total_novos_mes = $novos_candidatos_query->get_total();

// 3. Total de Empresas (Contagem simples do post type 'empresa')
$total_empresas = wp_count_posts('empresa')->publish;

// 4. Total de Candidatos (Todos os usuários 'subscriber')
$users_count = count_users();
$total_candidatos = isset($users_count['avail_roles']['subscriber']) ? $users_count['avail_roles']['subscriber'] : 0;

include("header.php"); 
?>
    
    <div class="col-lg-5 col-sm-12">
        <h2 class="h3 fw-bold text-slate-800"><?php the_title(); ?></h2>
        <p class="text-slate-500"><?php the_field('subtitulo'); ?></p>
    </div>
    
    <div class="row ">
        <div class="col-12 col-md-6 col-xl-6 mb-3">
            <a href="<?php bloginfo('url'); ?>/vagas" class="text-decoration-none">
                <div class="card h-100 p-4 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i data-lucide="megaphone" class="icon-geral rounded-lg p-3 bg-slate-50" width="20%" height="20%"></i>
                        </div>
                        <div>
                            <p class="text-slate-500 fw-medium mb-1" style="font-size:1rem;">Vagas Abertas</p>
                            <h3 class="h2 fw-bold text-slate-900 m-0"><?php echo $total_vagas_abertas; ?></h3>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-xl-6 mb-3">
            <a href="<?php bloginfo('url'); ?>/talentos" class="text-decoration-none">
                <div class="card h-100 p-4 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i data-lucide="user-plus" class="icon-geral rounded-lg p-3 bg-slate-50" width="20%" height="20%"></i>
                        </div>
                        <div>
                            <p class="text-slate-500 fw-medium mb-1" style="font-size: 1rem;">Novos Candidatos</p>
                            <h3 class="h2 fw-bold text-slate-900 m-0"><?php echo $total_novos_mes; ?></h3>
                        </div>
                    </div>
                </div>
            </a>
        </div>                    

        <div class="col-12 col-md-6 col-xl-6 mb-3">
            <a href="<?php bloginfo('url'); ?>/empresas" class="text-decoration-none">
                <div class="card h-100 p-4 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i data-lucide="briefcase" class="icon-geral rounded-lg p-3 bg-slate-50" width="20%" height="20%"></i>
                        </div>
                        <div>
                            <p class="text-slate-500 fw-medium mb-1" style="font-size: 1rem;">Empresas</p>
                            <h3 class="h2 fw-bold text-slate-900 m-0"><?php echo $total_empresas; ?></h3>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-xl-6 mb-3">
            <a href="<?php bloginfo('url'); ?>/talentos" class="text-decoration-none">
                <div class="card h-100 p-4 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i data-lucide="users" class="icon-geral rounded-lg p-3 bg-slate-50" width="20%" height="20%"></i>
                        </div>
                        <div>
                            <p class="text-slate-500 fw-medium mb-1" style="font-size: 1rem;">Total Candidatos</p>
                            <h3 class="h2 fw-bold text-slate-900 m-0"><?php echo $total_candidatos; ?></h3>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>    

<?php include("footer.php"); ?>