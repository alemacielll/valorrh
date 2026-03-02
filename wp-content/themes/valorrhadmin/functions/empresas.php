<?php 

    // --- GESTÃO DE EMPRESAS (SALVAR / ATUALIZAR / EXCLUIR) ---

add_action('admin_post_save_empresa_action', 'handle_salvar_empresa');
add_action('admin_post_nopriv_save_empresa_action', 'handle_salvar_empresa');

function handle_salvar_empresa() {
    if (!is_user_logged_in()) wp_die('Acesso negado');

    // Verificação de Nonce (Importante!)
    if (!isset($_POST['vaga_nonce']) || !wp_verify_nonce($_POST['vaga_nonce'], 'vaga_form_nonce')) {
        wp_die('Erro de segurança no formulário.');
    }

    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    $dados_empresa = [
        'post_title'   => sanitize_text_field($_POST['nome_fantasia']),
        'post_type'    => 'empresa',
        'post_status'  => 'publish',
    ];

    if ($post_id) {
        $dados_empresa['ID'] = $post_id;
        $id_empresa = wp_update_post($dados_empresa);
    } else {
        $id_empresa = wp_insert_post($dados_empresa);
    }

    if ($id_empresa && !is_wp_error($id_empresa)) {
        // Atualize os campos ACF com os nomes corretos vindos do formulário
        update_field('razao_social', sanitize_text_field($_POST['razao_social']), $id_empresa);
        update_field('cnpj', sanitize_text_field($_POST['cnpj']), $id_empresa);
        update_field('site', esc_url_raw($_POST['site']), $id_empresa);
        update_field('linkedin', esc_url_raw($_POST['linkedin']), $id_empresa);
        
        // Endereço
        update_field('cep', sanitize_text_field($_POST['cep']), $id_empresa);
        update_field('logradouro', sanitize_text_field($_POST['logradouro']), $id_empresa);
        update_field('numero', sanitize_text_field($_POST['numero']), $id_empresa);
        update_field('complemento', sanitize_text_field($_POST['complemento']), $id_empresa);
        update_field('bairro', sanitize_text_field($_POST['bairro']), $id_empresa);
        update_field('cidade', sanitize_text_field($_POST['cidade']), $id_empresa);
        update_field('estado', sanitize_text_field($_POST['estado']), $id_empresa);
        
        // Contato
        update_field('telefone', sanitize_text_field($_POST['telefone']), $id_empresa);
        update_field('e-mail', sanitize_email($_POST['email_corp']), $id_empresa); // Corrigido de 'email' para 'email_corp'
        
        // Classificação
        update_field('segmento', sanitize_text_field($_POST['segmento']), $id_empresa);
        update_field('porte', sanitize_text_field($_POST['porte']), $id_empresa);
        
        // Responsável
        update_field('responsavel', sanitize_text_field($_POST['responsavel']), $id_empresa);
        update_field('cargo_responsavel', sanitize_text_field($_POST['cargo_responsavel']), $id_empresa);
        update_field('email_responsavel', sanitize_email($_POST['email_responsavel']), $id_empresa);
        update_field('telefone_responsavel', sanitize_text_field($_POST['telefone_responsavel']), $id_empresa);

        // Opcional: Se quiser vincular o ID da empresa ao usuário que criou
        if (!$post_id) {
            update_user_meta(get_current_user_id(), 'id_empresa', $id_empresa);
        }

        $redirect_url = add_query_arg('modo', 'edicao', get_permalink($id_empresa));
        $redirect_url = add_query_arg('sucesso', '1', $redirect_url);

        wp_redirect($redirect_url);
        exit;

    } else {
        wp_die('Erro ao salvar os dados da empresa.');
    }
}

// Lógica para Excluir
add_action('admin_post_excluir_empresa', 'handle_excluir_empresa');
function handle_excluir_empresa() {
    $post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $user_id = get_current_user_id();
    $user_empresa_id = get_user_meta($user_id, 'id_empresa', true);

    // Trava de segurança
    if ($post_id && (!empty($user_empresa_id) && $post_id != $user_empresa_id) && !current_user_can('administrator')) {
        wp_die('Não permitido');
    }

    wp_delete_post($post_id, true);
    wp_redirect(home_url('/empresas/?excluido=sucesso'));
    exit;
}

?>