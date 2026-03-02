<!DOCTYPE html>
<html lang="pt-BR">
	<head>
	    <meta charset="UTF-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <link href="<?php bloginfo('template_url'); ?>/css/bootstrap.min.css" rel="stylesheet">
	    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/styles.css">
	    <?php wp_head(); ?>
	</head>
	<body class="">
		<div class="container py-5">
			<div class="row justify-content-center">
				<div class="col-12 col-md-8 col-lg-6">
					<div class="text-center">
						<div class="display-1 fw-bold">Erro 404</div>
						<h1 class="h3 fw-semibold mt-2">Página não encontrada</h1>
						<p class="text-muted mt-3 mb-4">O link pode estar incorreto ou a página foi removida.</p>
						<div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
							<a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">Ir para a Home</a>
							<a href="javascript:history.back()" class="btn btn-outline-secondary">
							Voltar
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php wp_footer(); ?>
	</body>
</html>