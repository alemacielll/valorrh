<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visão Geral - Valor RH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/styles.css">
    <?php wp_head(); ?>
</head>
	<body class="">

		<div class="container py-5">
			<div class="row d-flex align-items-center justify-content-center">
				<div class="col-md-4 mt-5">
					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						<div class="text-center">
							<img class="img-fluid" width="300" src="<?php bloginfo('template_url'); ?>/img/logo_login.png">
							<h2 class="py-5 text-center mb-0 pb-0 fs-3"><?php the_title(); ?></h2>
						</div>
					    <?php the_content(); ?>
					<?php endwhile; endif; ?>
				</div>
			</div>
		</div>

		<script src="<?php bloginfo('template_url'); ?>/js/bootstrap.bundle.min.js"></script>>
	    <?php wp_footer(); ?>
	</body>
	
</html>