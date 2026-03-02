<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php bloginfo('template_url'); ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/styles2.css">
    <?php wp_head(); ?>
</head>
<body class="bg-slate-50 text-slate-900 overflow-hidden">

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="d-flex vh-100 overflow-hidden">
        <?php include('inc/menu.php'); ?>
        <main class="d-flex flex-nowrap flex-column bg-slate-50 overflow-hidden flex-grow-1">
            
            <!-- Mobile Toggle Button -->
            <div class="d-lg-none p-4 pb-0">
                <button class="btn p-0" id="sidebarToggle">
                    <i data-lucide="menu" class="text-slate-900"></i>
                </button>
            </div>

            <!-- Page Content: Dashboard -->
            <div class="flex-grow-1 overflow-auto p-4 p-md-5 animate-fade-in">
                <!-- <?php if ( is_singular('vaga') ) { ?>
                    <div class="row d-flex justify-content-between align-items-center mb-4">
                        <div class="col-lg-5 col-sm-12">
                            <h2 class="h3 fw-bold text-slate-800">Editar Vaga</h2>
                            <p class="text-slate-500">Altere as informações para esta oportunidade.</p>
                        </div>
                        <div class="col-lg-auto col-sm-12 d-flex gap-3">
                            <div class="d-flex gap-2">
                                <a href="<?php bloginfo('url');?>/vagas" class="btn btn-light border text-slate-600 fw-medium">Cancelar</a>
                                <button class="btn btn-primary fw-medium d-flex align-items-center gap-2">
                                    <i data-lucide="save" width="16" class="text-indigo-500"></i> Salvar
                                </button>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="row d-flex justify-content-between align-items-center mb-4">
                        <div class="col-lg-5 col-sm-12">
                            <h2 class="h3 fw-bold text-slate-800"><?php the_title(); ?></h2>
                            <p class="text-slate-500"><?php the_field('subtitulo'); ?></p>
                        </div>
                        <?php if (is_page(15)) { ?>
                            <div class="col-lg-auto col-sm-12 d-flex gap-3">
                                <a href="#" class="btn btn-primary d-flex align-items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="plus" class="lucide lucide-plus"><path d="M5 12h14"></path><path d="M12 5v14"></path></svg>
                                    Nova Vaga
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?> -->