<?php 
/**
 * Template para visualização única de Empresa com modo de Edição integrado mantendo o visual original
 */
get_header(); 

$post_id = get_the_ID();
// Verifica se a URL contém ?modo=edicao
$edit_mode = (isset($_GET['modo']) && $_GET['modo'] === 'edicao');
?>

<form method="POST" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
    
    <input type="hidden" name="action" value="save_empresa_action">
    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
    <?php wp_nonce_field('vaga_form_nonce', 'vaga_nonce'); ?>

    <div class="row d-flex justify-content-between align-items-center mb-4">
        <div class="col-lg-5 col-sm-12">
            <h2 class="h3 fw-bold text-slate-800"><?php the_title(); ?></h2>
            <p class="text-slate-500"><?php echo $edit_mode ? 'Edite os campos abaixo e salve as alterações' : 'Informações detalhadas da empresa'; ?></p>
        </div>
        <div class="col-lg-auto col-sm-12 d-flex gap-3">
            <div class="d-flex gap-2">
                <a href="<?php bloginfo('url');?>/empresas" class="btn btn-light border text-slate-600 fw-medium">Voltar</a>
                <?php if($edit_mode): ?>
                    <button type="submit" class="btn btn-primary fw-medium d-flex align-items-center gap-2">
                        <i data-lucide="save" width="16"></i> Salvar
                    </button>
                <?php else: ?>
                    <a href="<?php echo add_query_arg('modo', 'edicao', get_permalink($post_id)); ?>" class="btn btn-primary fw-medium d-flex align-items-center gap-2">
                        <i data-lucide="pencil" width="16"></i> Editar Empresa
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card p-4 border-0 shadow-sm mb-4">
                <h3 class="h6 fw-bold text-slate-800 text-uppercase mb-3 d-flex align-items-center gap-2" style="letter-spacing: 0.05em;">
                    <i data-lucide="briefcase" width="16" class="text-indigo-500"></i> Dados Corporativos
                </h3>
                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <label class="text-slate-400 fw-bold small text-uppercase d-block mb-1">Razão Social</label>
                        <?php if($edit_mode): ?>
                            <input type="text" name="razao_social" class="form-control" value="<?php echo get_field('razao_social', $post_id); ?>" required>
                        <?php else: ?>
                            <p class="text-slate-700 fw-medium"><?php echo get_field('razao_social', $post_id) ?: 'Não informado'; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-slate-400 fw-bold small text-uppercase d-block mb-1">Nome Fantasia</label>
                        <?php if($edit_mode): ?>
                            <input type="text" name="nome_fantasia" class="form-control" value="<?php the_title(); ?>" required>
                        <?php else: ?>
                            <p class="text-slate-700 fw-medium"><?php the_title(); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-slate-400 fw-bold small text-uppercase d-block mb-1">CNPJ</label>
                        <?php if($edit_mode): ?>
                            <input type="text" name="cnpj" id="cnpj" class="form-control" value="<?php echo get_field('cnpj', $post_id); ?>">
                        <?php else: ?>
                            <p class="text-slate-700 fw-medium"><?php echo get_field('cnpj', $post_id) ?: 'Não informado'; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-slate-400 fw-bold small text-uppercase d-block mb-1">Site Oficial</label>
                        <?php if($edit_mode): ?>
                            <input type="text" name="site" class="form-control" value="<?php echo get_field('site', $post_id); ?>">
                        <?php else: ?>
                            <p class="text-slate-700 fw-medium">
                                <?php $site = get_field('site', $post_id); ?>
                                <?php if($site): ?>
                                    <a href="<?php echo esc_url($site); ?>" target="_blank" class="text-indigo-600 text-decoration-none"><?php echo $site; ?></a>
                                <?php else: ?> Não informado <?php endif; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="col-12">
                        <label class="text-slate-400 fw-bold small text-uppercase d-block mb-1">LinkedIn</label>
                        <?php if($edit_mode): ?>
                            <input type="text" name="linkedin" class="form-control" value="<?php echo get_field('linkedin', $post_id); ?>">
                        <?php else: ?>
                            <p class="text-slate-700 fw-medium">
                                <?php $linkedin = get_field('linkedin', $post_id); ?>
                                <?php if($linkedin): ?>
                                    <a href="<?php echo esc_url($linkedin); ?>" target="_blank" class="text-indigo-600 text-decoration-none"><?php echo $linkedin; ?></a>
                                <?php else: ?> Não informado <?php endif; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card p-4 border-0 shadow-sm mb-4">
                <h3 class="h6 fw-bold text-slate-800 text-uppercase mb-3 d-flex align-items-center gap-2" style="letter-spacing: 0.05em;">
                    <i data-lucide="map-pin" width="16" class="text-indigo-500"></i> Endereço e Contato
                </h3>
                <div class="row g-4">
                    <div class="col-12 col-md-4">
                        <label class="text-slate-400 fw-bold small text-uppercase d-block mb-1">CEP</label>
                        <?php if($edit_mode): ?>
                            <input type="text" name="cep" id="cep" class="form-control" value="<?php echo get_field('cep', $post_id); ?>">
                        <?php else: ?>
                            <p class="text-slate-700 fw-medium"><?php echo get_field('cep', $post_id) ?: '-'; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="text-slate-400 fw-bold small text-uppercase d-block mb-1">Logradouro</label>
                        <?php if($edit_mode): ?>
                            <input type="text" name="logradouro" id="logradouro" class="form-control" value="<?php echo get_field('logradouro', $post_id); ?>">
                        <?php else: ?>
                            <p class="text-slate-700 fw-medium"><?php echo get_field('logradouro', $post_id) ?: '-'; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="text-slate-400 fw-bold small text-uppercase d-block mb-1">Número</label>
                        <?php if($edit_mode): ?>
                            <input type="text" name="numero" id="numero" class="form-control" value="<?php echo get_field('numero', $post_id); ?>">
                        <?php else: ?>
                            <p class="text-slate-700 fw-medium"><?php echo get_field('numero', $post_id) ?: '-'; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="text-slate-400 fw-bold small text-uppercase d-block mb-1">Cidade</label>
                        <?php if($edit_mode): ?>
                            <input type="text" name="cidade" id="cidade" class="form-control" value="<?php echo get_field('cidade', $post_id); ?>">
                        <?php else: ?>
                            <p class="text-slate-700 fw-medium"><?php echo get_field('cidade', $post_id) ?: '-'; ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="text-slate-400 fw-bold small text-uppercase d-block mb-1">Estado</label>
                        <?php 
                        if($edit_mode): 
                            $estado_atual = get_field('estado', $post_id);
                            $ufs = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];
                        ?>
                            <select name="estado" id="estado" class="form-select">
                                <option value="">UF</option>
                                <?php foreach($ufs as $uf): ?>
                                    <option value="<?php echo $uf; ?>" <?php echo ($estado_atual == $uf) ? 'selected' : ''; ?>>
                                        <?php echo $uf; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <p class="text-slate-700 fw-medium"><?php echo get_field('estado', $post_id) ?: '-'; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="text-slate-400 fw-bold small text-uppercase d-block mb-1">Telefone</label>
                        <?php if($edit_mode): ?>
                            <input type="text" name="telefone" id="telefone" class="form-control" value="<?php echo get_field('telefone', $post_id); ?>">
                        <?php else: ?>
                            <p class="text-slate-700 fw-medium"><?php echo get_field('telefone', $post_id) ?: '-'; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="text-slate-400 fw-bold small text-uppercase d-block mb-1">E-mail</label>
                        <?php if($edit_mode): ?>
                            <input type="email" name="email_corp" class="form-control" value="<?php echo get_field('e-mail', $post_id); ?>">
                        <?php else: ?>
                            <p class="text-slate-700 fw-medium"><?php echo get_field('e-mail', $post_id) ?: '-'; ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card p-4 border-0 shadow-sm mb-4">
                <h3 class="h6 fw-bold text-slate-800 text-uppercase mb-3 d-flex align-items-center gap-2" style="letter-spacing: 0.05em;">
                    <i data-lucide="tag" width="16" class="text-indigo-500"></i> Classificação
                </h3>
                <div class="mb-3">
                    <label class="text-slate-400 fw-bold small text-uppercase d-block mb-1">Segmento</label>
                    <?php if($edit_mode): ?>
                        <select name="segmento" class="form-select">
                            <?php 
                                $segs = ['Tecnologia','Varejo','Indústria','Saúde','Financeiro','Serviços'];
                                $atual = get_field('segmento', $post_id);
                                foreach($segs as $s) echo "<option value='$s' ".selected($atual, $s, false).">$s</option>";
                            ?>
                        </select>
                    <?php else: ?>
                        <span class="badge bg-slate-100 text-slate-700 px-3 py-2 rounded-pill"><?php echo get_field('segmento', $post_id) ?: 'Não definido'; ?></span>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="text-slate-400 fw-bold small text-uppercase d-block mb-1">Porte</label>
                    <?php if($edit_mode): ?>
                        <select name="porte" class="form-select">
                            <?php 
                                $portes = ['Startup','Pequena (1-50)','Média (50-100)','Grande (+100)'];
                                $atualp = get_field('porte', $post_id);
                                foreach($portes as $p) echo "<option value='$p' ".selected($atualp, $p, false).">$p</option>";
                            ?>
                        </select>
                    <?php else: ?>
                        <span class="badge bg-slate-100 text-slate-700 px-3 py-2 rounded-pill"><?php echo get_field('porte', $post_id) ?: 'Não definido'; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card p-4 border-0 shadow-sm bg-indigo-50">
                <h3 class="h6 fw-bold text-indigo-900 text-uppercase mb-3 d-flex align-items-center gap-2" style="letter-spacing: 0.05em;">
                    <i data-lucide="user" width="16" class="text-indigo-600"></i> Contato Principal
                </h3>
                <div class="col-12 col-md-12 mb-3">
                    <label class="text-indigo-800 fw-bold small text-uppercase d-block mb-1">Responsável</label>
                    <?php if($edit_mode): ?>
                        <input type="text" name="responsavel" class="form-control" value="<?php echo get_field('responsavel', $post_id); ?>">
                    <?php else: ?>
                        <p class="text-indigo-950 fw-bold mb-0"><?php echo get_field('responsavel', $post_id) ?: '-'; ?></p>
                    <?php endif; ?>
                </div>

                <div class="col-12 col-md-12 mb-3">
                    <label class="text-indigo-800 fw-bold small text-uppercase d-block mb-1">Cargo</label>
                    <?php if($edit_mode): ?>
                        <input type="text" name="cargo_responsavel" class="form-control" value="<?php echo get_field('cargo_responsavel', $post_id); ?>">
                    <?php else: ?>
                        <p class="text-indigo-600 small mb-0"><?php echo get_field('cargo_responsavel', $post_id) ?: '-'; ?></p>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="text-indigo-800 fw-bold small d-block mb-1">E-mail</label>
                    <?php if($edit_mode): ?>
                        <input type="email" name="email_responsavel" class="form-control" value="<?php echo get_field('email_responsavel', $post_id); ?>">
                    <?php else: ?>
                        <p class="text-indigo-950 fw-medium"><?php echo get_field('email_responsavel', $post_id) ?: '-'; ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="text-indigo-800 fw-bold small d-block mb-1">Celular</label>
                    <?php if($edit_mode): ?>
                        <input type="text" name="telefone_responsavel" id="celular" class="form-control" value="<?php echo get_field('telefone_responsavel', $post_id); ?>">
                    <?php else: ?>
                        <p class="text-indigo-950 fw-medium mb-0"><?php echo get_field('telefone_responsavel', $post_id) ?: '-'; ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- BOTAO EXCLUIR -->
            <a href="<?php echo esc_url( admin_url('admin-post.php?action=excluir_empresa&id='.$post_id) ); ?>" class="btn btn-danger fw-medium mt-4 align-items-center gap-2" onclick="return confirm('Excluir esta empresa?');"
            >
                <i data-lucide="trash-2" width="16"></i> Excluir
            </a>

        </div>
    </div>
</form>

<?php get_footer(); ?>