    <?php get_header(); ?>
    <form method="POST" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
        
        <input type="hidden" name="action" value="save_vaga_action">
        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
        <?php wp_nonce_field('vaga_form_nonce', 'vaga_nonce'); ?>

        <div class="row d-flex justify-content-between align-items-center mb-4">
            <div class="col-lg-5 col-sm-12">
                <h2 class="h3 fw-bold text-slate-800">Editar Vaga</h2>
                <p class="text-slate-500">Atualize as informações desta oportunidade</p>
            </div>
            <div class="col-lg-auto col-sm-12 d-flex gap-3">
                <div class="d-flex gap-2">
                    <?php if ($modo === 'edicao') : ?>
                        <a href="<?php the_permalink($post_id); ?>" target="_blank" class="btn btn-light border text-slate-600 fw-medium d-flex align-items-center gap-2">
                            <i data-lucide="external-link" width="16"></i> Link Divulgação
                        </a>
                    <?php endif; ?>
                    <a href="<?php the_permalink($post_id); ?>/?modo=selecao" class="btn btn-light border text-slate-600 fw-medium"><i data-lucide="users" width="16"></i> Seleção</a>

                    <a href="<?php bloginfo('url');?>/vagas" class="btn btn-light border text-slate-600 fw-medium">Voltar</a>

                    <button type="submit" class="btn btn-primary fw-medium d-flex align-items-center gap-2">
                        <i data-lucide="save" width="16" class="text-indigo-500"></i> Salvar
                    </button>
                </div>
            </div>
        </div>

        <?php if ($sucesso) : ?>
            <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center gap-2">
                <i data-lucide="check-circle" width="20"></i>
                Vaga salva com sucesso!
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="card p-4 border-0 shadow-sm mb-4">
                    <h3 class="h6 fw-bold text-slate-800 text-uppercase mb-3 d-flex align-items-center gap-2" style="letter-spacing: 0.05em;">
                        <i data-lucide="info" width="16" class="text-indigo-500"></i> Dados Gerais
                    </h3>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-slate-600 fw-bold small">Título da Vaga <span class="text-danger">*</span></label>
                            <input type="text" name="titulo_vaga" class="form-control" value="<?php echo get_the_title(); ?>" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label text-slate-600 fw-bold small">Empresa <span class="text-danger">*</span></label>
                            <select name="empresa" class="form-select" required>
                                <?php
                                $empresas = get_posts(['post_type' => 'empresa', 'posts_per_page' => -1]);
                                $id_empresa_vaga = get_field('id_empresa');
                                foreach ($empresas as $emp) :
                                    $selected = ($id_empresa_vaga == $emp->ID) ? 'selected' : '';
                                    echo '<option value="'.$emp->ID.'" '.$selected.'>'.$emp->post_title.'</option>';
                                endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label text-slate-600 fw-bold small">Departamento <span class="text-danger">*</span></label>
                            <select name="departamento" class="form-select">
                                <?php $dept = get_field('departamento'); ?>
                                <option value="">Selecione</option>
                                <option value="Administrativo" <?php selected($dept, 'Administrativo'); ?>>Administrativo</option>
                                <option value="Comercial/Vendas" <?php selected($dept, 'Comercial/Vendas'); ?>>Comercial / Vendas</option>
                                <option value="Financeiro" <?php selected($dept, 'Financeiro'); ?>>Financeiro</option>
                                <option value="Jurídico" <?php selected($dept, 'Jurídico'); ?>>Jurídico</option>
                                <option value="Logística" <?php selected($dept, 'Logística'); ?>>Logística</option>
                                <option value="Marketing" <?php selected($dept, 'Marketing'); ?>>Marketing</option>
                                <option value="Operacional" <?php selected($dept, 'Operacional'); ?>>Operacional</option>
                                <option value="Recursos Humanos" <?php selected($dept, 'Recursos Humanos'); ?>>Recursos Humanos</option>
                                <option value="Tecnologia" <?php selected($dept, 'Tecnologia'); ?>>Tecnologia</option>
                                <option value="Outros" <?php selected($dept, 'Outros'); ?>>Outros</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label text-slate-600 fw-bold small">Modelo <span class="text-danger">*</span></label>
                            <select name="modelo" class="form-select">
                                <?php $modelo = get_field('modelo'); ?>
                                <option value="">Selecione</option>
                                <option value="Presencial" <?php selected($modelo, 'Presencial'); ?>>Presencial</option>
                                <option value="Remoto" <?php selected($modelo, 'Remoto'); ?>>Remoto (Home Office)</option>
                                <option value="Híbrido" <?php selected($modelo, 'Híbrido'); ?>>Híbrido</option>
                                <option value="Flexível" <?php selected($modelo, 'Flexível'); ?>>Flexível</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label text-slate-600 fw-bold small">Regime <span class="text-danger">*</span></label>
                            <select name="regime" class="form-select">
                                <?php $regime = get_field('regime'); ?>
                                <option value="">Selecione</option>
                                <option value="CLT" <?php selected($regime, 'CLT'); ?>>CLT (Efetivo)</option>
                                <option value="PJ" <?php selected($regime, 'PJ'); ?>>PJ (Prestador de Serviço)</option>
                                <option value="Estágio" <?php selected($regime, 'Estágio'); ?>>Estágio</option>
                                <option value="Trainee" <?php selected($regime, 'Trainee'); ?>>Trainee</option>
                                <option value="Temporário" <?php selected($regime, 'Temporário'); ?>>Temporário</option>
                                <option value="Freelancer" <?php selected($regime, 'Freelancer'); ?>>Freelancer / Autônomo</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label text-slate-600 fw-bold small">Faixa Salarial</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" name="salario_min" class="form-control" value="<?php echo get_field('faixa_inicial'); ?>" step="0.01">
                                <span class="input-group-text">até</span>
                                <input type="number" name="salario_max" class="form-control" value="<?php echo get_field('faixa_final'); ?>" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card p-4 border-0 shadow-sm mb-4">
                    <h3 class="h6 fw-bold text-slate-800 text-uppercase mb-3 d-flex align-items-center gap-2" style="letter-spacing: 0.05em;">
                        <i data-lucide="file-text" width="16" class="text-indigo-500"></i> Descrição da Vaga
                    </h3>
                    <div class="mb-3">
                        <label class="form-label text-slate-600 fw-bold small">Sobre a Vaga</label>
                        <textarea name="sobre_vaga" class="form-control" rows="4"><?php echo get_field('descricao'); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-600 fw-bold small">Responsabilidades e Atribuições</label>
                        <textarea name="responsabilidades" class="form-control" rows="5"><?php echo get_field('responsabilidades'); ?></textarea>
                    </div>
                </div>

                <div class="card p-4 border-0 shadow-sm mb-4">
                    <h3 class="h6 fw-bold text-slate-800 text-uppercase mb-3 d-flex align-items-center gap-2" style="letter-spacing: 0.05em;">
                        <i data-lucide="check-square" width="16" class="text-indigo-500"></i> Requisitos
                    </h3>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-slate-600 fw-bold small">Hard Skills</label>
                            <input type="text" name="hard_skills" class="form-control" value="<?php echo get_field('hard_skills'); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-slate-600 fw-bold small">Diferenciais</label>
                            <input type="text" name="diferenciais" class="form-control" value="<?php echo get_field('diferenciais'); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-slate-600 fw-bold small">Soft Skills</label>
                            <input type="text" name="soft_skills" class="form-control" value="<?php echo get_field('soft_skills'); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card p-4 border-0 shadow-sm mb-4">
                    <h3 class="h6 fw-bold text-slate-800 text-uppercase mb-3 d-flex align-items-center gap-2" style="letter-spacing: 0.05em;">
                        <i data-lucide="settings" width="16" class="text-indigo-500"></i> Detalhes Operacionais
                    </h3>
                    <div class="d-flex flex-column gap-3">
                        <div>
                            <label class="form-label text-slate-600 fw-bold small mb-1">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="">Selecione o Estado</option>
                                <option value="AC" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'AC'); ?>>Acre</option>
                                <option value="AL" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'AL'); ?>>Alagoas</option>
                                <option value="AP" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'AP'); ?>>Amapá</option>
                                <option value="AM" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'AM'); ?>>Amazonas</option>
                                <option value="BA" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'BA'); ?>>Bahia</option>
                                <option value="CE" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'CE'); ?>>Ceará</option>
                                <option value="DF" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'DF'); ?>>Distrito Federal</option>
                                <option value="ES" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'ES'); ?>>Espírito Santo</option>
                                <option value="GO" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'GO'); ?>>Goiás</option>
                                <option value="MA" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'MA'); ?>>Maranhão</option>
                                <option value="MT" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'MT'); ?>>Mato Grosso</option>
                                <option value="MS" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'MS'); ?>>Mato Grosso do Sul</option>
                                <option value="MG" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'MG'); ?>>Minas Gerais</option>
                                <option value="PA" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'PA'); ?>>Pará</option>
                                <option value="PB" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'PB'); ?>>Paraíba</option>
                                <option value="PR" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'PR'); ?>>Paraná</option>
                                <option value="PE" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'PE'); ?>>Pernambuco</option>
                                <option value="PI" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'PI'); ?>>Piauí</option>
                                <option value="RJ" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'RJ'); ?>>Rio de Janeiro</option>
                                <option value="RN" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'RN'); ?>>Rio Grande do Norte</option>
                                <option value="RS" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'RS'); ?>>Rio Grande do Sul</option>
                                <option value="RO" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'RO'); ?>>Rondônia</option>
                                <option value="RR" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'RR'); ?>>Roraima</option>
                                <option value="SC" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'SC'); ?>>Santa Catarina</option>
                                <option value="SP" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'SP'); ?>>São Paulo</option>
                                <option value="SE" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'SE'); ?>>Sergipe</option>
                                <option value="TO" <?php if(isset($post_id)) selected(get_field('estado', $post_id), 'TO'); ?>>Tocantins</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label text-slate-600 fw-bold small mb-1">Cidade</label>
                            <input type="text" name="cidade" class="form-control" value="<?php echo get_field('cidade'); ?>">
                        </div>
                        <div>
                            <label class="form-label text-slate-600 fw-bold small mb-1">Carga Horária</label>
                            <input type="text" name="carga_horaria" class="form-control" value="<?php echo get_field('carga_horaria'); ?>">
                        </div>
                        <div>
                            <label class="form-label text-slate-600 fw-bold small mb-1">Escolaridade</label>
                            <select name="escolaridade" class="form-select">
                                <?php $esc = get_field('escolaridade'); ?>
                                <option value="superior_completo" <?php selected($esc, 'superior_completo'); ?>>Superior completo</option>
                                <option value="pos_graduacao" <?php selected($esc, 'pos_graduacao'); ?>>Pós-graduação</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label text-slate-600 fw-bold small mb-1">Idiomas</label>
                            <select name="idioma" class="form-select">
                                <?php $idioma = get_field('idioma'); ?>
                                <option value="Português" <?php selected($idioma, 'Português'); ?>>Português</option>
                                <option value="Inglês" <?php selected($idioma, 'Inglês'); ?>>Inglês</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card p-4 border-0 shadow-sm mb-4">
                    <h3 class="h6 fw-bold text-slate-800 text-uppercase mb-3 d-flex align-items-center gap-2" style="letter-spacing: 0.05em;">
                        <i data-lucide="gift" width="16" class="text-indigo-500"></i> Benefícios
                    </h3>
                    <textarea name="beneficios" class="form-control" rows="4"><?php echo get_field('beneficios'); ?></textarea>
                </div>

                <div class="card p-4 border-0 shadow-sm mb-4">
                    <h3 class="h6 fw-bold text-slate-800 text-uppercase mb-3 d-flex align-items-center gap-2" style="letter-spacing: 0.05em;">
                        <i data-lucide="calendar" width="16" class="text-indigo-500"></i> Administração
                    </h3>
                    <div class="d-flex flex-column gap-3">
                        <div>
                            <label class="form-label text-slate-600 fw-bold small">Limite Candidatura</label>
                            <input type="date" name="limite_candidatura" class="form-control" value="<?php 
                                $data = get_field('limite', false, false); // Pega o valor bruto YYYYMMDD do ACF
                                echo $data ? date('Y-m-d', strtotime($data)) : ''; 
                            ?>">
                        </div>
                        <div>
                            <label class="form-label text-slate-600 fw-bold small">Situação</label>
                            <select name="situacao" class="form-select">
                                <?php $situacao = get_field('situacao'); ?>
                                <option value="Aberta" <?php selected($situacao, 'Aberta'); ?>>Aberta</option>
                                <option value="Encerrada" <?php selected($situacao, 'Encerrada'); ?>>Encerrada</option>
                            </select>
                        </div>
                    </div>

                </div>
                <a href="<?php echo esc_url( admin_url('admin-post.php?action=delete_vaga_action&vaga_id=' . $post_id . '&_wpnonce=' . wp_create_nonce('delete_vaga_' . $post_id)) ); ?>" class="btn btn-outline-danger fw-medium gap-2" onclick="return confirm('Tem certeza que deseja enviar esta vaga para a lixeira?');">
                        <i data-lucide="trash-2" width="16"></i> Excluir Vaga
                    </a>
            </div>
        </div>
    </form>
    <div class="mb-5"></div>
    <?php get_footer(); ?>