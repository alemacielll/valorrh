<?php 

	/**
	* Redireciona usuários logados que tentam acessar as páginas de Login e Registro
	*/
	function redirecionar_usuarios_logados() {
	// Verifica se o usuário está logado
	if ( is_user_logged_in() ) {
	    
	    // Verifica se a página atual é a de 'login' ou 'register'
	    // Você pode usar o ID da página, o slug ou o título. O slug é o mais comum.
	    if ( is_page('login') || is_page('register') ) {
	        
	        // Redireciona para a home do site
	        wp_redirect( home_url() );
	        exit;
	    }
	}
	}
	add_action( 'template_redirect', 'redirecionar_usuarios_logados' );

?>