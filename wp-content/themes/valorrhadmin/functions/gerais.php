<?php 
	
	function removeHeadLinks() {
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
    }
    add_action('init', 'removeHeadLinks');
    remove_action('wp_head', 'wp_generator');
    
    show_admin_bar( false );
	add_filter('show_admin_bar', '__return_false');

	add_filter( 'wpseo_metabox_prio', function() {
	    return 'low';
	}, 10);

	add_theme_support('post-thumbnails');


?>