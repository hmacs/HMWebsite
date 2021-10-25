<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-154589997-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-154589997-2');
</script>

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
	<?php colibriwp_theme()->get( 'header' )->render(); ?>

