<?php 
/**
 * Template Name: Adicionar Vaga
 */
include("header.php"); 
?>

    <form method="POST" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
        
        <input type="hidden" name="action" value="save_vaga_action">
        <?php wp_nonce_field('vaga_form_nonce', 'vaga_nonce'); ?>

        <div class="row d-flex justify-content-between align-items-center mb-4">
            <div class="col-lg-5 col-sm-12">
                <h2 class="h3 fw-bold text-slate-800">Adicionar Vaga</h2>
                <p class="text-slate-500">Adicione as informações para a oportunidade</p>
            </div>
            <div class="col-lg-auto col-sm-12 d-flex gap-3">
                <div class="d-flex gap-2">
                    <a href="<?php bloginfo('url');?>/vagas" class="btn btn-light border text-slate-600 fw-medium">Cancelar</a>
                    <button type="submit" class="btn btn-primary fw-medium d-flex align-items-center gap-2">
                        <i data-lucide="save" width="16" class="text-indigo-500"></i> Salvar
                    </button>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-8">

                <div class="card p-4 border-0 shadow-sm mb-4">
                    <h3 class="h6 fw-bold text-slate-800 text-uppercase mb-3 d-flex align-items-center gap-2"
                        style="letter-spacing: 0.05em;">
                        <i data-lucide="info" width="16" class="text-indigo-500"></i> Dados Gerais
                    </h3>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-slate-600 fw-bold small">Título da Vaga <span class="text-danger">*</span></label>
                            <input type="text" name="titulo_vaga" class="form-control" placeholder="" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label text-slate-600 fw-bold small">Empresa <span class="text-danger">*</span></label>
                            <select name="empresa" class="form-select" required>
                                <option selected disabled>Selecione</option>
                                <?php
                                $empresas = get_posts(['post_type' => 'empresa', 'posts_per_page' => -1]);
                                foreach ($empresas as $emp) :
                                    echo '<option value="'.$emp->ID.'">'.$emp->post_title.'</option>';
                                endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label text-slate-600 fw-bold small">Departamento <span class="text-danger">*</span></label>
                            <select name="departamento" class="form-select" required>
                                <option selected disabled>Selecione</option>
                                <option value="Administrativo">Administrativo</option>
                                <option value="Comercial/Vendas">Comercial / Vendas</option>
                                <option value="Financeiro">Financeiro</option>
                                <option value="Jurídico">Jurídico</option>
                                <option value="Logística">Logística</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Operacional">Operacional</option>
                                <option value="Recursos Humanos">Recursos Humanos</option>
                                <option value="Tecnologia">Tecnologia</option>
                                <option value="Outros">Outros</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label text-slate-600 fw-bold small">Modelo <span class="text-danger">*</span></label>
                            <select name="modelo" class="form-select" required>
                                <option selected disabled>Selecione</option>
                                <option value="Presencial">Presencial</option>
                                <option value="Remoto">Remoto (Home Office)</option>
                                <option value="Híbrido">Híbrido</option>
                                <option value="Flexível">Flexível</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label text-slate-600 fw-bold small">Regime <span class="text-danger">*</span></label>
                            <select name="regime" class="form-select" required>
                                <option selected disabled>Selecione</option>
                                <option value="CLT">CLT (Efetivo)</option>
                                <option value="PJ">PJ (Prestador de Serviço)</option>
                                <option value="Estágio">Estágio</option>
                                <option value="Trainee">Trainee</option>
                                <option value="Temporário">Temporário</option>
                                <option value="Freelancer">Freelancer</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label text-slate-600 fw-bold small">Faixa Salarial</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" name="salario_min" class="form-control" placeholder="0,00" step="0.01">
                                <span class="input-group-text">até</span>
                                <input type="number" name="salario_max" class="form-control" placeholder="0,00" step="0.01">
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
                        <textarea name="sobre_vaga" class="form-control" rows="4" placeholder="Descreva o objetivo da posição..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-600 fw-bold small">Responsabilidades e Atribuições</label>
                        <textarea name="responsabilidades" class="form-control" rows="5" placeholder="Liste as principais atividades..."></textarea>
                    </div>
                </div>

                <div class="card p-4 border-0 shadow-sm mb-4">
                    <h3 class="h6 fw-bold text-slate-800 text-uppercase mb-3 d-flex align-items-center gap-2" style="letter-spacing: 0.05em;">
                        <i data-lucide="check-square" width="16" class="text-indigo-500"></i> Requisitos
                    </h3>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-slate-600 fw-bold small">Hard Skills</label>
                            <input type="text" name="hard_skills" class="form-control" placeholder="Ex: PHP, Laravel, WordPress">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-slate-600 fw-bold small">Diferenciais</label>
                            <input type="text" name="diferenciais" class="form-control" placeholder="Ex: Inglês fluente">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-slate-600 fw-bold small">Soft Skills</label>
                            <input type="text" name="soft_skills" class="form-control" placeholder="Ex: Proatividade, Trabalho em equipe">
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
                                <option selected disabled>Selecione</option>
                                <option value="AC">Acre</option>
                                <option value="AL">Alagoas</option>
                                <option value="AP">Amapá</option>
                                <option value="AM">Amazonas</option>
                                <option value="BA">Bahia</option>
                                <option value="CE">Ceará</option>
                                <option value="DF">Distrito Federal</option>
                                <option value="ES">Espírito Santo</option>
                                <option value="GO">Goiás</option>
                                <option value="MA">Maranhão</option>
                                <option value="MT">Mato Grosso</option>
                                <option value="MS">Mato Grosso do Sul</option>
                                <option value="MG">Minas Gerais</option>
                                <option value="PA">Pará</option>
                                <option value="PB">Paraíba</option>
                                <option value="PR">Paraná</option>
                                <option value="PE">Pernambuco</option>
                                <option value="PI">Piauí</option>
                                <option value="RJ">Rio de Janeiro</option>
                                <option value="RN">Rio Grande do Norte</option>
                                <option value="RS">Rio Grande do Sul</option>
                                <option value="RO">Rondônia</option>
                                <option value="RR">Roraima</option>
                                <option value="SC">Santa Catarina</option>
                                <option value="SP">São Paulo</option>
                                <option value="SE">Sergipe</option>
                                <option value="TO">Tocantins</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label text-slate-600 fw-bold small mb-1">Cidade</label>
                            <input type="text" name="cidade" class="form-control" placeholder="Ex: Campo Grande">
                        </div>
                        <div>
                            <label class="form-label text-slate-600 fw-bold small mb-1">Carga Horária</label>
                            <input type="text" name="carga_horaria" class="form-control" placeholder="Ex: Seg-Sex, 08h às 18h">
                        </div>
                        <div>
                            <label class="form-label text-slate-600 fw-bold small mb-1">Escolaridade Mínima</label>
                            <select name="escolaridade" class="form-select">
                                <option selected disabled>Selecione</option>
                                <option value="Ensino Fundamental Incompleto">Ensino Fundamental Incompleto</option>
                                <option value="Ensino Fundamental Completo">Ensino Fundamental Completo</option>
                                <option value="Ensino Médio Incompleto">Ensino Médio Incompleto</option>
                                <option value="Ensino Médio Completo">Ensino Médio Completo</option>
                                <option value="Ensino Técnico">Ensino Técnico</option>
                                <option value="Ensino Superior Incompleto">Ensino Superior Incompleto</option>
                                <option value="Ensino Superior Completo">Ensino Superior Completo</option>
                                <option value="Pós-graduação / Especialização">Pós-graduação / Especialização</option>
                                <option value="Mestrado">Mestrado</option>
                                <option value="Doutorado">Doutorado</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label text-slate-600 fw-bold small mb-1">Idiomas</label>
                            <select name="idioma" class="form-select">
                                <option value="Português">Português</option>
                                <option value="Inglês">Inglês</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card p-4 border-0 shadow-sm mb-4">
                    <h3 class="h6 fw-bold text-slate-800 text-uppercase mb-3 d-flex align-items-center gap-2" style="letter-spacing: 0.05em;">
                        <i data-lucide="gift" width="16" class="text-indigo-500"></i> Benefícios
                    </h3>
                    <textarea name="beneficios" class="form-control" rows="4" placeholder="- Vale Refeição&#10;- Plano de Saúde"></textarea>
                </div>

                <div class="card p-4 border-0 shadow-sm mb-4">
                    <h3 class="h6 fw-bold text-slate-800 text-uppercase mb-3 d-flex align-items-center gap-2" style="letter-spacing: 0.05em;">
                        <i data-lucide="calendar" width="16" class="text-indigo-500"></i> Administração
                    </h3>
                    <div class="d-flex flex-column gap-3">
                        <div>
                            <label class="form-label text-slate-600 fw-bold small">Limite Candidatura</label>
                            <input type="date" name="limite_candidatura" class="form-control">
                        </div>
                        <div>
                            <label class="form-label text-slate-600 fw-bold small">Situação</label>
                            <select name="situacao" class="form-select">
                                <option value="Aberta">Aberta</option>
                                <option value="Encerrada">Encerrada</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="mb-5"></div>

<?php include("footer.php"); ?>