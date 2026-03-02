<?php

    //Função salvar/atualizar vaga
    function handle_vaga_form_submission() {
        if (isset($_POST['action']) && $_POST['action'] === 'save_vaga_action') {
            
            // Verificação de segurança (Nonce)
            if (!isset($_POST['vaga_nonce']) || !wp_verify_nonce($_POST['vaga_nonce'], 'vaga_form_nonce')) {
                wp_die('Erro de segurança.');
            }

            $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
            $is_update = ($post_id > 0);

            // Dados básicos do Post
            $post_data = [
                'post_title'   => sanitize_text_field($_POST['titulo_vaga']),
                'post_type'    => 'vaga',
                'post_status'  => 'publish',
            ];

            if ($is_update) {
                $post_data['ID'] = $post_id;
                $current_id = wp_update_post($post_data);
            } else {
                $current_id = wp_insert_post($post_data);
            }

            if ($current_id && !is_wp_error($current_id)) {
                // Mapeamento de campos ACF baseado no seu JSON
                update_field('id_empresa', $_POST['empresa'], $current_id);
                update_field('departamento', $_POST['departamento'], $current_id);
                update_field('modelo', $_POST['modelo'], $current_id);
                update_field('regime', $_POST['regime'], $current_id);
                update_field('faixa_inicial', $_POST['salario_min'], $current_id);
                update_field('faixa_final', $_POST['salario_max'], $current_id);
                update_field('descricao', $_POST['sobre_vaga'], $current_id);
                update_field('responsabilidades', $_POST['responsabilidades'], $current_id);
                update_field('hard_skills', $_POST['hard_skills'], $current_id);
                update_field('diferenciais', $_POST['diferenciais'], $current_id);
                update_field('soft_skills', $_POST['soft_skills'], $current_id);
                update_field('estado', $_POST['estado'], $current_id);
                update_field('cidade', $_POST['cidade'], $current_id);
                update_field('carga_horaria', $_POST['carga_horaria'], $current_id);
                update_field('escolaridade', $_POST['escolaridade'], $current_id);
                update_field('idioma', $_POST['idioma'], $current_id);
                update_field('beneficios', $_POST['beneficios'], $current_id);
                update_field('limite', $_POST['limite_candidatura'], $current_id);
                update_field('situacao', $_POST['situacao'], $current_id);

                // Redirecionamento após salvar
                $redirect_url = get_permalink($current_id) . '?modo=edicao&sucesso=1';
                wp_redirect($redirect_url);
                exit;
            }
        }
    }
    add_action('admin_post_save_vaga_action', 'handle_vaga_form_submission');
    add_action('admin_post_nopriv_save_vaga_action', 'handle_vaga_form_submission');

    // Função excluir vaga (envia para lixeira)
    function handle_delete_vaga() {
        if (!isset($_GET['vaga_id']) || !isset($_GET['_wpnonce'])) {
            wp_die('Acesso não autorizado.');
        }

        $post_id = intval($_GET['vaga_id']);
        $nonce = $_GET['_wpnonce'];

        if (!wp_verify_nonce($nonce, 'delete_vaga_' . $post_id)) {
            wp_die('Falha na verificação de segurança.');
        }

        if (!current_user_can('edit_post', $post_id)) {
            wp_die('Você não tem permissão para excluir esta vaga.');
        }
        $result = wp_trash_post($post_id);

        if ($result) {
            wp_redirect(home_url('/vagas?excluido=sucesso'));
            exit;
        } else {
            wp_die('Erro ao tentar excluir a vaga.');
        }
    }
    add_action('admin_post_delete_vaga_action', 'handle_delete_vaga');

?>