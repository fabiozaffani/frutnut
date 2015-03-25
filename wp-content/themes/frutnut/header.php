<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width" />
    <title><?php bloginfo('name'); ?> | <?php is_home() ? bloginfo('description') :wp_title( '|', true ); ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,300' rel='stylesheet' type='text/css'>
    <?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
    <!--[if lt IE 9]>
    <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
    <![endif]-->
    <meta name="google-site-verification" content="xh9DpGiGAU1aXjlj5iTNbbwX1rT9-eS8DeVrJ27Vi28" />
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/style.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/style-Red.css" />
    <?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-37165645-1']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>
<div class="wrapper">
    <div id="navwrap">

        <?php wp_nav_menu( array( 'theme_location' => 'primary-menu', 'container' => '', 'fallback_cb' => '', 'menu_class' => 'nav superfish' ) ); ?>
        <br class="clear" />
        <?php do_action('icl_language_selector'); ?>

    </div><!-- #navwrap -->

    <a href="<?php bloginfo('url'); ?>"><?php $logo = get_bloginfo('template_directory').'/images/logo.jpg'; ?>
	<img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" id="logo"/>
  </a>
