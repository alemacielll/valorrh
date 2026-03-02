<?php
    $modo = isset($_GET['modo']) ? sanitize_key(wp_unslash($_GET['modo'])) : '';
    $sucesso = isset($_GET['sucesso']) ? true : false;
    $post_id = get_the_ID();
?>

<?php if ($modo === 'edicao' && (current_user_can('administrator') || current_user_can('editor'))) : ?>
    
    <?php include('inc/modo-edicao-vaga.php'); ?>

<?php elseif ( $modo === 'selecao' ) : ?>

    <?php include('inc/modo-selecao-vaga.php'); ?>

<?php else : ?>
    
    <?php include('inc/modo-publico-vaga.php'); ?>

<?php endif; ?>