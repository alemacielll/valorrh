<?php

function converter_data_para_acf($data_y_m_d) {
    if (!$data_y_m_d) return '';
    $date = DateTime::createFromFormat('Y-m-d', $data_y_m_d);
    return $date ? $date->format('d/m/Y') : $data_y_m_d;
}

function processar_perfil_talento_completo() {
    if (isset($_POST['action']) && $_POST['action'] == 'salvar_perfil_talento') {
        
        $user = wp_get_current_user();
        if (!$user->ID) return;

        $post_data = array(
            'post_title'    => wp_strip_all_tags($_POST['post_title']),
            'post_status'   => 'publish',
            'post_type'     => 'talento',
        );

        // Verifica existência pelo e-mail
        $existing_post = get_posts(array(
            'post_type' => 'talento',
            'meta_query' => array(
                array('key' => 'e-mail', 'value' => $user->user_email, 'compare' => '=')
            )
        ));

        if ($existing_post) {
            $post_data['ID'] = $existing_post[0]->ID;
            $post_id = wp_update_post($post_data);
        } else {
            $post_id = wp_insert_post($post_data);
        }

        if ($post_id) {
            // 1. Campos Simples
            $campos_simples = [
                'nome_social', 'cpf', 'rg', 'genero', 'estado_civil', 
                'nacionalidade', 'e-mail', 'telefone', 'cep', 'rua', 
                'numero', 'complemento', 'bairro', 'cidade', 'estado', 
                'linkedin', 'portfolio', 'cargo_pretendido', 'pretensao_salarial', 
                'modelo_de_trabalho_preferido', 'cnh', 'disponibilidade_viagem', 
                'disponibilidade_mudanca', 'pessoa_pcd', 'cid', 'raca'
            ];

            foreach ($campos_simples as $campo) {
                if (isset($_POST[$campo])) {
                    update_field($campo, $_POST[$campo], $post_id);
                }
            }

            // 2. Campos de Data (Converter Y-m-d para d/m/Y)
            if (isset($_POST['nascimento'])) {
                update_field('nascimento', converter_data_para_acf($_POST['nascimento']), $post_id);
            }

            // 3. Upload: Foto de Perfil
            if (!empty($_FILES['foto_perfil']['name'])) {
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');
                
                $thumb_id = media_handle_upload('foto_perfil', $post_id);
                if (!is_wp_error($thumb_id)) {
                    set_post_thumbnail($post_id, $thumb_id);
                }
            }

            // 4. Upload: Currículo (Campo Arquivo ACF)
            if (!empty($_FILES['curricullum_file']['name'])) {
                if (!function_exists('media_handle_upload')) {
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                    require_once(ABSPATH . 'wp-admin/includes/media.php');
                }
                $cv_id = media_handle_upload('curricullum_file', $post_id);
                if (!is_wp_error($cv_id)) {
                    update_field('curricullum', $cv_id, $post_id);
                }
            }

            // 5. Campos Repeater (Arrays)
            
            // Formação
            if (isset($_POST['formacao']) && is_array($_POST['formacao'])) {
                $formacao_data = [];
                foreach ($_POST['formacao'] as $row) {
                    if (!empty($row['titulo'])) { // Validação mínima
                        $row['data_inicial'] = converter_data_para_acf($row['data_inicial']);
                        $row['data_final'] = converter_data_para_acf($row['data_final']);
                        $formacao_data[] = $row;
                    }
                }
                update_field('formacao', $formacao_data, $post_id);
            }

            // Experiência
            if (isset($_POST['experiencia']) && is_array($_POST['experiencia'])) {
                $exp_data = [];
                foreach ($_POST['experiencia'] as $row) {
                    if (!empty($row['empresa'])) {
                        $row['data_inicial'] = converter_data_para_acf($row['data_inicial']);
                        $row['data_final'] = converter_data_para_acf($row['data_final']);
                        $exp_data[] = $row;
                    }
                }
                update_field('experiencia', $exp_data, $post_id);
            }

            // Idiomas
            if (isset($_POST['idiomas']) && is_array($_POST['idiomas'])) {
                $idiomas_data = [];
                foreach ($_POST['idiomas'] as $row) {
                    if (!empty($row['idioma'])) {
                        $idiomas_data[] = $row;
                    }
                }
                update_field('idiomas', $idiomas_data, $post_id);
            }

            // Ferramentas
            if (isset($_POST['ferramentas']) && is_array($_POST['ferramentas'])) {
                $ferramentas_data = [];
                foreach ($_POST['ferramentas'] as $row) {
                    if (!empty($row['ferramenta'])) {
                        $ferramentas_data[] = $row;
                    }
                }
                update_field('ferramentas', $ferramentas_data, $post_id);
            }
            
            wp_redirect(get_permalink() . '?sucesso=1');
            exit;
        }
    }
}
add_action('template_redirect', 'processar_perfil_talento_completo');