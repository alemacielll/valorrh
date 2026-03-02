<?php 
/** Template Name: Adicionar Empresa */
get_header(); 

$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$edit_mode = ($post_id > 0);
?>

<form method="POST" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
    
    <input type="hidden" name="action" value="save_empresa_action">
    <?php if($edit_mode): ?>
        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
    <?php endif; ?>
    <?php wp_nonce_field('vaga_form_nonce', 'vaga_nonce'); ?>

    <div class="row d-flex justify-content-between align-items-center mb-4">
        <div class="col-lg-5 col-sm-12">
            <h2 class="h3 fw-bold text-slate-800"><?php echo $edit_mode ? 'Editar Empresa' : 'Adicionar Empresa'; ?></h2>
            <p class="text-slate-500">Preencha as informações para a empresa</p>
        </div>
        <div class="col-lg-auto col-sm-12 d-flex gap-3">
            <div class="d-flex gap-2">
                <a href="<?php bloginfo('url');?>/empresas" class="btn btn-light border text-slate-600 fw-medium">Cancelar</a>
                <button type="submit" class="btn btn-primary fw-medium d-flex align-items-center gap-2">
                    <i data-lucide="save" width="16" class="text-indigo-500"></i> Salvar
                </button>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card p-4 border-0 shadow-sm mb-4">
                <h3 class="h6 fw-bold text-slate-800 text-uppercase mb-3 d-flex align-items-center gap-2" style="letter-spacing: 0.05em;">
                    <i data-lucide="briefcase" width="16" class="text-indigo-500"></i> Dados Corporativos
                </h3>
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label text-slate-600 fw-bold small">Razão Social <span class="text-danger">*</span></label>
                        <input type="text" name="razao_social" class="form-control" value="<?php echo $edit_mode ? get_field('razao_social', $post_id) : ''; ?>" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label text-slate-600 fw-bold small">Nome Fantasia <span class="text-danger">*</span></label>
                        <input type="text" name="nome_fantasia" class="form-control" value="<?php echo $edit_mode ? get_the_title($post_id) : ''; ?>" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label text-slate-600 fw-bold small">CNPJ <span class="text-danger">*</span></label>
                        <input type="text" id="cnpj" name="cnpj" class="form-control" placeholder="00.000.000/0000-00" value="<?php echo $edit_mode ? get_field('cnpj', $post_id) : ''; ?>">
                    </div>
                    <div class="col-6">
                        <label class="form-label text-slate-600 fw-bold small">Site</label>
                        <div class="input-group">
                            <span class="input-group-text bg-slate-50 border-end-0"><i data-lucide="globe" width="16"></i></span>
                            <input type="text" name="site" class="form-control border-start-0 ps-0" value="<?php echo $edit_mode ? get_field('site', $post_id) : ''; ?>">
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label text-slate-600 fw-bold small">Linkedin</label>
                        <div class="input-group">
                            <span class="input-group-text bg-slate-50 border-end-0"><i data-lucide="linkedin" width="16"></i></span>
                            <input type="text" name="linkedin" class="form-control border-start-0 ps-0" value="<?php echo $edit_mode ? get_field('linkedin', $post_id) : ''; ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card p-4 border-0 shadow-sm mb-4">
                <h3 class="h6 fw-bold text-slate-800 text-uppercase mb-3 d-flex align-items-center gap-2" style="letter-spacing: 0.05em;">
                    <i data-lucide="map-pin" width="16" class="text-indigo-500"></i> Endereço e Contato
                </h3>
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label text-slate-600 fw-bold small">CEP</label>
                        <input type="text" id="cep" name="cep" class="form-control" value="<?php echo $edit_mode ? get_field('cep', $post_id) : ''; ?>">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label text-slate-600 fw-bold small">Logradouro</label>
                        <input type="text" id="logradouro" name="logradouro" class="form-control" value="<?php echo $edit_mode ? get_field('logradouro', $post_id) : ''; ?>">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label text-slate-600 fw-bold small">Número</label>
                        <input type="text" id="numero" name="numero" class="form-control" value="<?php echo $edit_mode ? get_field('numero', $post_id) : ''; ?>">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label text-slate-600 fw-bold small">Complemento</label>
                        <input type="text" id="complemento" name="complemento" class="form-control" value="<?php echo $edit_mode ? get_field('complemento', $post_id) : ''; ?>">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label text-slate-600 fw-bold small">Bairro</label>
                        <input type="text" id="bairro" name="bairro" class="form-control" value="<?php echo $edit_mode ? get_field('bairro', $post_id) : ''; ?>">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label text-slate-600 fw-bold small">Cidade</label>
                        <input type="text" id="cidade" name="cidade" class="form-control" value="<?php echo $edit_mode ? get_field('cidade', $post_id) : ''; ?>">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label text-slate-600 fw-bold small">Estado</label>
                        <select id="estado" name="estado" class="form-select">
                            <?php 
                            $ufs = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];
                            $uf_atual = get_field('estado', $post_id);
                            ?>
                            <option value="" <?php echo empty($uf_atual) ? 'selected' : ''; ?>>Selecione</option>
                            <?php foreach($ufs as $uf): ?>
                                <option value="<?php echo $uf; ?>" <?php echo ($uf_atual == $uf) ? 'selected' : ''; ?>><?php echo $uf; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label text-slate-600 fw-bold small">Telefone</label>
                        <input type="text" id="telefone" name="telefone" class="form-control" value="<?php echo $edit_mode ? get_field('telefone', $post_id) : ''; ?>">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label text-slate-600 fw-bold small">E-mail Corporativo</label>
                        <input type="email" name="email_corp" class="form-control" value="<?php echo $edit_mode ? get_field('e-mail', $post_id) : ''; ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card p-4 border-0 shadow-sm mb-4">
                 <h3 class="h6 fw-bold text-slate-800 text-uppercase mb-3 d-flex align-items-center gap-2" style="letter-spacing: 0.05em;">
                    <i data-lucide="tag" width="16" class="text-indigo-500"></i> Classificação
                </h3>
                <div class="d-flex flex-column gap-3">
                    <div>
                        <label class="form-label text-slate-600 fw-bold small mb-1">Segmento</label>
                        <select name="segmento" class="form-select">
                            <?php 
                            $segmentos = ['Agronegócio', 'Alimentos e Bebidas', 'Consultoria', 'Construção Civil', 'Educação', 'Entretenimento', 'Financeiro', 'Indústria', 'Logística e Transporte', 'Marketing e Comunicação', 'RH e Recrutamento', 'Saúde', 'Serviços', 'Tecnologia', 'Turismo e Lazer', 'Varejo'];
                            $seg_atual = get_field('segmento', $post_id);
                            ?>
                            <option value="" <?php echo empty($seg_atual) ? 'selected' : ''; ?>>Selecione</option>
                            <?php foreach($segmentos as $s): ?>
                                <option value="<?php echo $s; ?>" <?php echo ($seg_atual == $s) ? 'selected' : ''; ?>><?php echo $s; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label text-slate-600 fw-bold small mb-1">Porte</label>
                        <select name="porte" class="form-select">
                            <?php 
                            $portes = ['Startup', 'Micro (até 10 funcionários)', 'Pequena (11-50 funcionários)', 'Média (51-200 funcionários)', 'Grande (+200 funcionários)'];
                            $porte_atual = get_field('porte', $post_id);
                            ?>
                            <option value="" <?php echo empty($porte_atual) ? 'selected' : ''; ?>>Selecione</option>
                            <?php foreach($portes as $p): ?>
                                <option value="<?php echo $p; ?>" <?php echo ($porte_atual == $p) ? 'selected' : ''; ?>><?php echo $p; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card p-4 border-0 shadow-sm bg-indigo-50">
                <h3 class="h6 fw-bold text-indigo-900 text-uppercase mb-3 d-flex align-items-center gap-2" style="letter-spacing: 0.05em;">
                    <i data-lucide="user" width="16" class="text-indigo-600"></i> Contato Principal
                </h3>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label text-indigo-800 fw-bold small">Responsável</label>
                        <input type="text" name="responsavel" class="form-control border-indigo-200" value="<?php echo $edit_mode ? get_field('responsavel', $post_id) : ''; ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label text-indigo-800 fw-bold small">Cargo</label>
                        <input type="text" name="cargo_responsavel" class="form-control border-indigo-200" value="<?php echo $edit_mode ? get_field('cargo_responsavel', $post_id) : ''; ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label text-indigo-800 fw-bold small">E-mail</label>
                        <input type="email" name="email_responsavel" class="form-control border-indigo-200" value="<?php echo $edit_mode ? get_field('e-mail_responsavel', $post_id) : ''; ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label text-indigo-800 fw-bold small">Celular</label>
                        <input type="text" id="celular" name="telefone_responsavel" class="form-control border-indigo-200" value="<?php echo $edit_mode ? get_field('telefone_responsavel', $post_id) : ''; ?>">
                    </div>
                </div>
            </div>
            
            <?php if($edit_mode): ?>
                <div class="mt-4">
                    <a href="<?php echo admin_url('admin-post.php?action=excluir_empresa&id='.$post_id); ?>" 
                       class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2" 
                       onclick="return confirm('Tem certeza que deseja excluir?')">
                        <i data-lucide="trash-2" width="16"></i> Excluir Empresa
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</form>

<?php get_footer(); ?>