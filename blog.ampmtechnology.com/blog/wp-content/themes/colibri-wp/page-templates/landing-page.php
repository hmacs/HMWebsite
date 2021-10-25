<?php
/*
 * Template Name: Landing Page Template
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php wp_head(); ?>
    <?php colibriwp_theme()->get( 'css' )->render(); ?>
</head>

<body id="colibri" <?php body_class(); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
}
?>
<div class="site" id="page-top">
    <?php colibriwp_theme()->get( 'front-page-content' )->render(); ?>
</div>
<?php wp_footer(); ?>
</body>
</html>
