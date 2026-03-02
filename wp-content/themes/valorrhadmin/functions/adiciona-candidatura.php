<?php 
    
    /**
     * Processamento de candidatura via POST (com reload)
     */
    add_action('admin_post_processar_candidatura_direta', 'handle_candidatura_direta');
    add_action('admin_post_nopriv_processar_candidatura_direta', 'handle_candidatura_direta');

    function handle_candidatura_direta() {
        // 1. Validação de Segurança
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'candidatar_vaga_nonce')) {
            wp_die('Falha de segurança.');
        }

        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_redirect(home_url('/login'));
            exit;
        }

        $vaga_id = intval($_POST['vaga_id']);
        $empresa_id = intval($_POST['empresa_id']);
        $user_info = get_userdata($user_id);
        $vaga_title = get_the_title($vaga_id);

        // 2. Criar post no post type "candidatura"
        $candidatura_title = $user_info->display_name . ' - ' . $vaga_title;
        
        $candidatura_post_id = wp_insert_post([
            'post_title'   => $candidatura_title,
            'post_type'    => 'candidatura',
            'post_status'  => 'publish',
        ]);

        if ($candidatura_post_id) {
            update_field('vaga', $vaga_id, $candidatura_post_id);
            update_field('empresa', $empresa_id, $candidatura_post_id);
        }

        // 3. Atualizar o repeater no post type "talento" do usuário
        $talento_posts = get_posts([
            'post_type'   => 'talento',
            'author'      => $user_id,
            'numberposts' => 1
        ]);

        if ($talento_posts) {
            $talento_id = $talento_posts[0]->ID;
            
            $nova_linha = [
                'vaga'           => $vaga_id,
                'data_aplicacao' => date('d/m/Y')
            ];
            
            add_row('candidatura', $nova_linha, $talento_id);
        }

        // 4. Redirecionar de volta para a página de vagas
        // Usamos referer para voltar exatamente de onde o usuário clicou
        wp_redirect(wp_get_referer() ? wp_get_referer() : home_url('/vagas-valorrh'));
        exit;
    }

 ?>