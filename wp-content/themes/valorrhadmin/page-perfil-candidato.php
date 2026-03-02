<?php 
if ( ! is_user_logged_in() ) { wp_redirect( home_url('/login') ); exit; } 

$user = wp_get_current_user();

// Busca Perfil existente
$perfil_query = new WP_Query(array(
    'post_type' => 'talento',
    'meta_query' => array(
        array('key' => 'e-mail', 'value' => $user->user_email, 'compare' => '=')
    )
));
$perfil_id = $perfil_query->have_posts() ? $perfil_query->posts[0]->ID : false;

// Função auxiliar para data no value do input (d/m/Y -> Y-m-d)
function get_date_value($field_name, $post_id) {
    $date_acf = get_field($field_name, $post_id); // vem d/m/Y do ACF
    if (!$date_acf) return '';
    $date_obj = DateTime::createFromFormat('d/m/Y', $date_acf);
    return $date_obj ? $date_obj->format('Y-m-d') : '';
}

include("header.php"); 
?>

<div class="row d-flex justify-content-between align-items-center mb-4">
    <div class="col-lg-5 col-sm-12">
        <h2 class="h3 fw-bold text-slate-800">Meu Currículo</h2>
        <p class="text-slate-500 mb-0">Preencha todos os campos para manter seu perfil atualizado.</p>
    </div>
    <div class="col-lg-auto">
        <?php if($perfil_id): ?>
            <a href="<?php echo get_permalink($perfil_id); ?>" class="btn btn-light border text-indigo-600 fw-medium" target="_blank">
                <i data-lucide="eye" width="16"></i> Ver Perfil
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if(isset($_GET['sucesso'])): ?>
    <div class="alert alert-success border-0 bg-emerald-50 text-emerald-700 pb-3 mb-4 shadow-sm">
        <div class="d-flex align-items-center gap-2">
            <i data-lucide="check-circle" width="20"></i> 
            <span class="fw-bold">Sucesso!</span> Seu perfil foi atualizado.
        </div>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" id="form-perfil">
    <input type="hidden" name="action" value="salvar_perfil_talento">

    <div class="card p-4 border border-slate-200 mb-4 shadow-sm bg-white animate-fade-in">
        <h3 class="fw-bold text-slate-800 h5 mb-4 d-flex align-items-center gap-2">
            <i data-lucide="user" width="20" class="text-indigo-500"></i> Dados Pessoais
        </h3>
        
        <div class="d-flex flex-column flex-md-row gap-4 mb-4">
            <div class="flex-shrink-0 text-center">
                <div class="rounded-circle bg-slate-100 border border-4 border-white shadow-sm overflow-hidden mb-3 mx-auto" style="width: 120px; height: 120px;">
                    <?php if ($perfil_id && has_post_thumbnail($perfil_id)): 
                        echo get_the_post_thumbnail($perfil_id, 'medium', ['class' => 'w-100 h-100 object-fit-cover']);
                    else: ?>
                        <div class="d-flex align-items-center justify-content-center h-100 text-slate-300">
                            <i data-lucide="user" width="48"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <label class="btn btn-sm btn-outline-primary w-100 fw-medium">
                    <i data-lucide="camera" width="16" class="me-1"></i> Trocar Foto 
                    <input type="file" name="foto_perfil" class="d-none" accept="image/*">
                </label>
            </div>

            <div class="flex-grow-1 w-100">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-slate-600">Nome Completo <span class="text-danger fw-">*</span></label>
                        <input type="text" name="post_title" class="form-control" value="<?php echo ($perfil_id) ? get_the_title($perfil_id) : ''; ?>" placeholder="" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-slate-600">Nome Social</label>
                        <input type="text" name="nome_social" class="form-control" value="<?php echo get_field('nome_social', $perfil_id); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-slate-600">Nascimento <span class="text-danger fw-">*</span></label>
                        <input type="date" name="nascimento" class="form-control" value="<?php echo get_date_value('nascimento', $perfil_id); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-slate-600">CPF <span class="text-danger fw-">*</span></label>
                        <input type="text" name="cpf" id="cpf" class="form-control" value="<?php echo get_field('cpf', $perfil_id); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-slate-600">RG <span class="text-danger fw-">*</span></label>
                        <input type="text" name="rg" class="form-control" value="<?php echo get_field('rg', $perfil_id); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-slate-600">Gênero <span class="text-danger fw-">*</span></label>
                        <select required name="genero" class="form-select">
                            <option value="">Selecione</option>
                            <?php $opts = ['Masculino','Feminino','Não Binário','Outro','Prefiro não informar']; 
                            foreach($opts as $opt) { 
                                $sel = (get_field('genero', $perfil_id) == $opt) ? 'selected' : '';
                                echo "<option value='$opt' $sel>$opt</option>";
                            } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-slate-600">Estado Civil <span class="text-danger fw-">*</span></label>
                        <select name="estado_civil" class="form-select" required>
                            <option value="">Selecione</option>
                            <?php $opts = ['Solteiro(a)','Casado(a)','Divorciado(a)','Viúvo(a)','União Estável']; 
                            foreach($opts as $opt) { 
                                $sel = (get_field('estado_civil', $perfil_id) == $opt) ? 'selected' : '';
                                echo "<option value='$opt' $sel>$opt</option>";
                            } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-slate-600">Nacionalidade <span class="text-danger fw-">*</span></label>
                        <select name="nacionalidade" class="form-select" required>
                            <option value="">Selecione</option>
                            <?php $optsnacionalidade = ['Brasileira(o)','Estrangeria(a)']; 
                            foreach($optsnacionalidade as $opt) { 
                                $sel = (get_field('nacionalidade', $perfil_id) == $opt) ? 'selected' : '';
                                echo "<option value='$opt' $sel>$opt</option>";
                            } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-4 border border-slate-200 mb-4 shadow-sm bg-white animate-fade-in">
        <h3 class="fw-bold text-slate-800 h5 mb-4 d-flex align-items-center gap-2">
            <i data-lucide="map-pin" width="20" class="text-indigo-500"></i> Contato
        </h3>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-bold text-slate-600">E-mail <span class="text-danger fw-">*</span></label>
                <input required type="email" name="e-mail" class="form-control bg-slate-50 text-muted" value="<?php echo $user->user_email; ?>" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold text-slate-600">Telefone <span class="text-danger fw-">*</span></label>
                <input required type="text" id="telefone" name="telefone" class="form-control" value="<?php echo get_field('telefone', $perfil_id); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold text-slate-600">CEP <span class="text-danger fw-">*</span></label>
                <input required type="text" id="cep" name="cep" class="form-control" value="<?php echo get_field('cep', $perfil_id); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold text-slate-600">Rua <span class="text-danger fw-">*</span></label>
                <input required type="text" name="rua" id="logradouro" class="form-control" value="<?php echo get_field('rua', $perfil_id); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold text-slate-600">Número <span class="text-danger fw-">*</span></label>
                <input required type="text" name="numero" id="numero" class="form-control" value="<?php echo get_field('numero', $perfil_id); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold text-slate-600">Complemento</label>
                <input type="text" name="complemento" id="complemento" class="form-control" value="<?php echo get_field('complemento', $perfil_id); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold text-slate-600">Bairro <span class="text-danger fw-">*</span></label>
                <input required type="text" name="bairro" id="bairro" class="form-control" value="<?php echo get_field('bairro', $perfil_id); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold text-slate-600">Cidade <span class="text-danger fw-">*</span></label>
                <input required type="text" name="cidade" id="cidade" class="form-control" value="<?php echo get_field('cidade', $perfil_id); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold text-slate-600">Estado <span class="text-danger fw-">*</span></label>
                <select required name="estado" class="form-select">
                    <option value="">Selecione</option>
                    <?php 
                    $ufs = [
                        'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 
                        'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 
                        'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
                    ]; 

                    $estado_salvo = get_field('estado', $perfil_id);

                    foreach($ufs as $uf) { 
                        $sel = ($estado_salvo == $uf) ? 'selected' : '';
                        echo "<option id='estado' value='$uf' $sel>$uf</option>";
                    } 
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div class="card p-4 border border-slate-200 mb-4 shadow-sm bg-white animate-fade-in">
        <h3 class="fw-bold text-slate-800 h5 mb-4 d-flex align-items-center gap-2">
            <i data-lucide="briefcase" width="20" class="text-indigo-500"></i> Objetivos Profissionais
        </h3>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold text-slate-600">Cargo Pretendido <span class="text-danger fw-">*</span></label>
                <input required type="text" name="cargo_pretendido" class="form-control" value="<?php echo get_field('cargo_pretendido', $perfil_id); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold text-slate-600">Pretensão Salarial <span class="text-danger fw-">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-slate-50">R$</span>
                    <input required type="text" name="pretensao_salarial" class="form-control" value="<?php echo get_field('pretensao_salarial', $perfil_id); ?>">
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold text-slate-600">Modelo de Trabalho <span class="text-danger fw-">*</span></label>
                <select required name="modelo_de_trabalho_preferido" class="form-select">
                    <option value="">Selecione</option>
                    <?php $opts = ['Presencial','Híbrido','Remoto']; 
                    foreach($opts as $opt) { 
                        $sel = (get_field('modelo_de_trabalho_preferido', $perfil_id) == $opt) ? 'selected' : '';
                        echo "<option value='$opt' $sel>$opt</option>";
                    } ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold text-slate-600">LinkedIn (URL)</label>
                <input type="url" name="linkedin" class="form-control" value="<?php echo get_field('linkedin', $perfil_id); ?>">
            </div>
            <div class="col-md-12">
                <label class="form-label fw-bold text-slate-600">Portfólio (URL)</label>
                <input type="url" name="portfolio" class="form-control" value="<?php echo get_field('portfolio', $perfil_id); ?>">
            </div>
            
            <div class="col-md-12 mt-4">
                <label class="form-label fw-bold text-slate-600 mb-2">Arquivo de Currículo (PDF)</label>
                <?php 
                    $cv = get_field('curricullum', $perfil_id);
                    if($cv): 
                ?>
                    <div class="d-flex align-items-center gap-2 p-3 border rounded bg-slate-50 mb-2">
                        <div class="bg-white p-2 rounded shadow-sm">
                            <i data-lucide="file-text" class="text-red-500"></i>
                        </div>
                        <div>
                            <span class="d-block fw-bold text-slate-800"><?php echo esc_html($cv['title']); ?></span>
                            <span class="small text-slate-500">Atual</span>
                        </div>
                        <a href="<?php echo $cv['url']; ?>" target="_blank" class="ms-auto btn btn-sm btn-outline-secondary">Baixar</a>
                    </div>
                <?php endif; ?>
                <input type="file" name="curricullum_file" class="form-control" accept="application/pdf">
                <div class="form-text">Envie um novo PDF para substituir o atual.</div>
            </div>
        </div>
    </div>

    <div class="card p-4 border border-slate-200 mb-4 shadow-sm bg-white animate-fade-in">
        <h3 class="fw-bold text-slate-800 h5 mb-4 d-flex align-items-center gap-2">
            <i data-lucide="info" width="20" class="text-indigo-500"></i> Informações Adicionais
        </h3>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-bold text-slate-600">Possui CNH?</label>
                <select name="cnh" class="form-select">
                    <?php 
                    $cnh_salva = get_field('cnh', $perfil_id);
                    $opcoes_cnh = [
                        'Não' => 'Não possuo',
                        'A'   => 'Categoria A (Moto)',
                        'B'   => 'Categoria B (Carro)',
                        'AB'  => 'Categoria AB (Moto e Carro)',
                        'C'   => 'Categoria C (Caminhão)',
                        'D'   => 'Categoria D (Ônibus)',
                        'E'   => 'Categoria E (Articulados)'
                    ];

                    foreach($opcoes_cnh as $valor => $rotulo): 
                        $selected = ($cnh_salva == $valor) ? 'selected' : '';
                        echo "<option value='$valor' $selected>$rotulo</option>";
                    endforeach; 
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold text-slate-600">Disponibilidade Viagem? <span class="text-danger fw-">*</span></label>
                <select required name="disponibilidade_viagem" class="form-select">
                    <option value="Não" <?php echo (get_field('disponibilidade_viagem', $perfil_id) == 'Não') ? 'selected' : ''; ?>>Não</option>
                    <option value="Sim" <?php echo (get_field('disponibilidade_viagem', $perfil_id) == 'Sim') ? 'selected' : ''; ?>>Sim</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold text-slate-600">Disponibilidade Mudança? <span class="text-danger fw-">*</span></label>
                <select required name="disponibilidade_mudanca" class="form-select">
                    <option value="Não" <?php echo (get_field('disponibilidade_mudanca', $perfil_id) == 'Não') ? 'selected' : ''; ?>>Não</option>
                    <option value="Sim" <?php echo (get_field('disponibilidade_mudanca', $perfil_id) == 'Sim') ? 'selected' : ''; ?>>Sim</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold text-slate-600">Pessoa com Deficiência? <span class="text-danger fw-">*</span></label>
                <select required name="pessoa_pcd" class="form-select">
                    <option value="Não" <?php echo (get_field('pessoa_pcd', $perfil_id) == 'Não') ? 'selected' : ''; ?>>Não</option>
                    <option value="Sim" <?php echo (get_field('pessoa_pcd', $perfil_id) == 'Sim') ? 'selected' : ''; ?>>Sim</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold text-slate-600">CID (Se aplicável)</label>
                <input type="text" name="cid" class="form-control" value="<?php echo get_field('cid', $perfil_id); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold text-slate-600">Cor/Raça <span class="text-danger fw-">*</span></label>
                <select required name="raca" class="form-select">
                    <option value="">Selecione</option>
                    <?php $opts = ['Branca','Preta','Parda','Amarela','Indígena']; 
                    foreach($opts as $opt) { 
                        $sel = (get_field('raca', $perfil_id) == $opt) ? 'selected' : '';
                        echo "<option value='$opt' $sel>$opt</option>";
                    } ?>
                </select>
            </div>
        </div>
    </div>

    <div class="card p-4 border border-slate-200 mb-4 shadow-sm bg-white animate-fade-in">
        <h3 class="fw-bold text-slate-800 h5 mb-4 d-flex justify-content-between align-items-center">
            <span class="d-flex align-items-center gap-2"><i data-lucide="graduation-cap" width="20" class="text-indigo-500"></i> Formação Acadêmica</span>
            <button type="button" class="btn btn-sm btn-indigo-50 text-indigo-600 fw-bold border-indigo-100" onclick="addRow('formacao')">
                <i data-lucide="plus" width="16"></i> Adicionar
            </button>
        </h3>
        
        <div id="container-formacao">
            <?php 
            if(have_rows('formacao', $perfil_id)): 
                $i = 0;
                while(have_rows('formacao', $perfil_id)): the_row(); 
                $dt_ini = DateTime::createFromFormat('d/m/Y', get_sub_field('data_inicial'));
                $dt_fim = DateTime::createFromFormat('d/m/Y', get_sub_field('data_final'));
            ?>
            <div class="repeater-item p-4 mb-3 border rounded-3 bg-slate-50 position-relative">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-2 bg-white shadow-sm p-2" style="z-index:10;" onclick="removeRow(this)"></button>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="small fw-bold text-uppercase text-slate-500">Curso/Título</label>
                        <input type="text" name="formacao[<?php echo $i; ?>][titulo]" class="form-control" value="<?php the_sub_field('titulo'); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold text-uppercase text-slate-500">Instituição</label>
                        <input type="text" name="formacao[<?php echo $i; ?>][instituicao]" class="form-control" value="<?php the_sub_field('instituicao'); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-bold text-uppercase text-slate-500">Nível</label>
                        <input type="text" name="formacao[<?php echo $i; ?>][nivel]" class="form-control" value="<?php the_sub_field('nivel'); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-bold text-uppercase text-slate-500">Início</label>
                        <input type="date" name="formacao[<?php echo $i; ?>][data_inicial]" class="form-control" value="<?php echo $dt_ini ? $dt_ini->format('Y-m-d') : ''; ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-bold text-uppercase text-slate-500">Conclusão</label>
                        <input type="date" name="formacao[<?php echo $i; ?>][data_final]" class="form-control" value="<?php echo $dt_fim ? $dt_fim->format('Y-m-d') : ''; ?>">
                    </div>
                </div>
            </div>
            <?php $i++; endwhile; endif; ?>
        </div>
    </div>

    <div class="card p-4 border border-slate-200 mb-4 shadow-sm bg-white animate-fade-in">
        <h3 class="fw-bold text-slate-800 h5 mb-4 d-flex justify-content-between align-items-center">
            <span class="d-flex align-items-center gap-2"><i data-lucide="building-2" width="20" class="text-indigo-500"></i> Experiência Profissional</span>
            <button type="button" class="btn btn-sm btn-indigo-50 text-indigo-600 fw-bold border-indigo-100" onclick="addRow('experiencia')">
                <i data-lucide="plus" width="16"></i> Adicionar
            </button>
        </h3>
        
        <div id="container-experiencia">
            <?php 
            if(have_rows('experiencia', $perfil_id)): 
                $j = 0;
                while(have_rows('experiencia', $perfil_id)): the_row();
                $dt_ini_exp = DateTime::createFromFormat('d/m/Y', get_sub_field('data_inicial'));
                $dt_fim_exp = DateTime::createFromFormat('d/m/Y', get_sub_field('data_final'));
            ?>
            <div class="repeater-item p-4 mb-3 border rounded-3 bg-slate-50 position-relative">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-2 bg-white shadow-sm p-2" style="z-index:10;" onclick="removeRow(this)"></button>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="small fw-bold text-uppercase text-slate-500">Empresa</label>
                        <input type="text" name="experiencia[<?php echo $j; ?>][empresa]" class="form-control" value="<?php the_sub_field('empresa'); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold text-uppercase text-slate-500">Cargo</label>
                        <input type="text" name="experiencia[<?php echo $j; ?>][cargo]" class="form-control" value="<?php the_sub_field('cargo'); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold text-uppercase text-slate-500">Início</label>
                        <input type="date" name="experiencia[<?php echo $j; ?>][data_inicial]" class="form-control" value="<?php echo $dt_ini_exp ? $dt_ini_exp->format('Y-m-d') : ''; ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold text-uppercase text-slate-500">Fim</label>
                        <input type="date" name="experiencia[<?php echo $j; ?>][data_final]" class="form-control" value="<?php echo $dt_fim_exp ? $dt_fim_exp->format('Y-m-d') : ''; ?>">
                    </div>
                    <div class="col-12">
                        <label class="small fw-bold text-uppercase text-slate-500">Atividades</label>
                        <textarea name="experiencia[<?php echo $j; ?>][atividades]" class="form-control" rows="3"><?php the_sub_field('atividades'); ?></textarea>
                    </div>
                </div>
            </div>
            <?php $j++; endwhile; endif; ?>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-6 mb-3 mb-md-0">
            <div class="card p-4 border border-slate-200 shadow-sm bg-white h-100 animate-fade-in">
                <h3 class="fw-bold text-slate-800 h6 mb-4 d-flex justify-content-between align-items-center">
                    <span class="d-flex align-items-center gap-2"><i data-lucide="languages" width="18" class="text-indigo-500"></i> Idiomas</span>
                    <button type="button" class="btn btn-sm btn-indigo-50 text-indigo-600 fw-bold" onclick="addRow('idiomas')">+ Adicionar</button>
                </h3>
                <div id="container-idiomas">
                    <?php if(have_rows('idiomas', $perfil_id)): $k=0; while(have_rows('idiomas', $perfil_id)): the_row(); ?>
                    <div class="repeater-item mb-2 d-flex gap-2 align-items-center">
                        <input type="text" name="idiomas[<?php echo $k; ?>][idioma]" class="form-control form-control-sm" placeholder="Idioma" value="<?php the_sub_field('idioma'); ?>">
                        
                        <select name="idiomas[<?php echo $k; ?>][nivel]" class="form-select form-select-sm">
                            <?php 
                            $nivel_salvo = get_sub_field('nivel');
                            $niveis = ['Básico', 'Intermediário', 'Avançado', 'Fluente'];
                            foreach($niveis as $nivel): 
                                $selected = ($nivel_salvo == $nivel) ? 'selected' : '';
                                echo "<option value='$nivel' $selected>$nivel</option>";
                            endforeach; 
                            ?>
                        </select>

                        <button type="button" class="btn btn-sm text-danger" onclick="removeRow(this)"><i data-lucide="x" width="14"></i></button>
                    </div>
                    <?php $k++; endwhile; endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-4 border border-slate-200 shadow-sm bg-white h-100 animate-fade-in">
                <h3 class="fw-bold text-slate-800 h6 mb-4 d-flex justify-content-between align-items-center">
                    <span class="d-flex align-items-center gap-2"><i data-lucide="wrench" width="18" class="text-indigo-500"></i> Ferramentas</span>
                    <button type="button" class="btn btn-sm btn-indigo-50 text-indigo-600 fw-bold" onclick="addRow('ferramentas')">+ Adicionar</button>
                </h3>
                <div id="container-ferramentas">
                    <?php if(have_rows('ferramentas', $perfil_id)): $l=0; while(have_rows('ferramentas', $perfil_id)): the_row(); ?>
                    <div class="repeater-item mb-2 d-flex gap-2 align-items-center">
                        <input type="text" name="ferramentas[<?php echo $l; ?>][ferramenta]" class="form-control form-control-sm" placeholder="Ex: Word, Excel" value="<?php the_sub_field('ferramenta'); ?>">
                        
                        <select name="ferramentas[<?php echo $l; ?>][nivel]" class="form-select form-select-sm">
                            <?php 
                            $nivel_salvo = get_sub_field('nivel');
                            $niveis = ['Básico', 'Intermediário', 'Avançado'];
                            foreach($niveis as $nivel): 
                                $selected = ($nivel_salvo == $nivel) ? 'selected' : '';
                                echo "<option value='$nivel' $selected>$nivel</option>";
                            endforeach; 
                            ?>
                        </select>

                        <button type="button" class="btn btn-sm text-danger" onclick="removeRow(this)"><i data-lucide="x" width="14"></i></button>
                    </div>
                    <?php $l++; endwhile; endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center pt-4 pb-5">
        <button type="submit" class="btn btn-primary btn-lg fw-bold px-5 rounded-pill shadow d-flex align-items-center gap-2" style="min-width: 250px; justify-content: center;">
            <i data-lucide="save" width="20"></i> SALVAR
        </button>
    </div>

</form>

<div class="d-none">
    
    <div id="tmpl-formacao">
        <div class="repeater-item p-4 mb-3 border rounded-3 bg-slate-50 position-relative animate-fade-in">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-2 bg-white shadow-sm p-2" style="z-index:10;" onclick="removeRow(this)"></button>
            <div class="row g-3">
                <div class="col-md-6"><label class="small fw-bold text-uppercase text-slate-500">Curso/Título</label><input type="text" name="formacao[{{id}}][titulo]" class="form-control"></div>
                <div class="col-md-6"><label class="small fw-bold text-uppercase text-slate-500">Instituição</label><input type="text" name="formacao[{{id}}][instituicao]" class="form-control"></div>
                <div class="col-md-4"><label class="small fw-bold text-uppercase text-slate-500">Nível</label><input type="text" name="formacao[{{id}}][nivel]" class="form-control"></div>
                <div class="col-md-4"><label class="small fw-bold text-uppercase text-slate-500">Início</label><input type="date" name="formacao[{{id}}][data_inicial]" class="form-control"></div>
                <div class="col-md-4"><label class="small fw-bold text-uppercase text-slate-500">Fim</label><input type="date" name="formacao[{{id}}][data_final]" class="form-control"></div>
            </div>
        </div>
    </div>

    <div id="tmpl-experiencia">
        <div class="repeater-item p-4 mb-3 border rounded-3 bg-slate-50 position-relative animate-fade-in">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-2 bg-white shadow-sm p-2" style="z-index:10;" onclick="removeRow(this)"></button>
            <div class="row g-3">
                <div class="col-md-6"><label class="small fw-bold text-uppercase text-slate-500">Empresa</label><input type="text" name="experiencia[{{id}}][empresa]" class="form-control"></div>
                <div class="col-md-6"><label class="small fw-bold text-uppercase text-slate-500">Cargo</label><input type="text" name="experiencia[{{id}}][cargo]" class="form-control"></div>
                <div class="col-md-6"><label class="small fw-bold text-uppercase text-slate-500">Início</label><input type="date" name="experiencia[{{id}}][data_inicial]" class="form-control"></div>
                <div class="col-md-6"><label class="small fw-bold text-uppercase text-slate-500">Fim</label><input type="date" name="experiencia[{{id}}][data_final]" class="form-control"></div>
                <div class="col-12"><label class="small fw-bold text-uppercase text-slate-500">Atividades</label><textarea name="experiencia[{{id}}][atividades]" class="form-control" rows="3"></textarea></div>
            </div>
        </div>
    </div>

    <div id="tmpl-idiomas">
        <div class="repeater-item mb-2 d-flex gap-2 align-items-center animate-fade-in">
            <input type="text" name="idiomas[{{id}}][idioma]" class="form-control form-control-sm" placeholder="Idioma">
            <select name="idiomas[{{id}}][nivel]" class="form-select form-select-sm">
                <option value="">Nível</option>
                <option value="Básico">Básico</option>
                <option value="Intermediário">Intermediário</option>
                <option value="Avançado">Avançado</option>
                <option value="Fluente">Fluente</option>
            </select>
            <button type="button" class="btn btn-sm text-danger" onclick="removeRow(this)"><i data-lucide="x" width="14"></i></button>
        </div>
    </div>

    <div id="tmpl-ferramentas">
        <div class="repeater-item mb-2 d-flex gap-2 align-items-center animate-fade-in">
            <input type="text" name="ferramentas[{{id}}][ferramenta]" class="form-control form-control-sm" placeholder="Ex: Word, Excel">
            
            <select name="ferramentas[{{id}}][nivel]" class="form-select form-select-sm">
                <option value="">Nível</option>
                <option value="Básico">Básico</option>
                <option value="Intermediário">Intermediário</option>
                <option value="Avançado">Avançado</option>
            </select>

            <button type="button" class="btn btn-sm text-danger" onclick="removeRow(this)">
                <i data-lucide="x" width="14"></i>
            </button>
        </div>
    </div>
    
</div>

<script>
    // Apenas funções de repeater (sem tabs)
    function addRow(type) {
        const container = document.getElementById('container-' + type);
        const template = document.getElementById('tmpl-' + type).innerHTML;
        const uniqueId = Date.now(); // ID único
        
        const newRowHtml = template.replace(/{{id}}/g, uniqueId);
        container.insertAdjacentHTML('beforeend', newRowHtml);
        lucide.createIcons(); // Re-renderiza ícones
    }

    function removeRow(btn) {
        if(confirm('Tem certeza que deseja remover este item?')) {
            btn.closest('.repeater-item').remove();
        }
    }
</script>

<?php include("footer.php"); ?>