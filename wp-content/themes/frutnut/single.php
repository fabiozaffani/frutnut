<?php get_header(); ?>
	<div id="content">
    	<div class="content_wrap">
            <div class="content_wrap">
            	<div id="posts">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <div id="breadcrumbs">
                        <?php get_template_part('includes/breadcrumbs'); ?>
                    </div>
					<?php if (get_option('simplepress_integration_single_top') <> '' && get_option('simplepress_integrate_singletop_enable') == 'on') echo(get_option('simplepress_integration_single_top')); ?>

                    <?php   $thumb = '';
                            $width = 182;
                            $height = 182;
                            $classtext = '';
                            $titletext = get_the_title();
                    ?>
                            <div class="post">
                                <h1><?php the_title(); ?></h1>
                                <?php the_content(''); ?>
                                <br class="clear" />
                                <?php if (get_option('simplepress_integration_single_bottom') <> '' && get_option('simplepress_integrate_singlebottom_enable') == 'on') echo(get_option('simplepress_integration_single_bottom')); ?>
                                <?php if (get_option('simplepress_468_enable') == 'on') { ?>
                                    <?php if(get_option('simplepress_468_adsense') <> '') echo(get_option('simplepress_468_adsense'));
                                    else { ?>
                                        <a href="<?php echo esc_url(get_option('simplepress_468_url')); ?>"><img src="<?php echo esc_url(get_option('simplepress_468_image')); ?>" alt="468 ad" class="foursixeight" /></a>
                                    <?php } ?>
                                <?php } ?>
                            </div><!-- .post -->
				<?php endwhile; endif; ?>
                </div><!-- #posts -->  
				<?php get_sidebar(); ?>
            </div><!-- .content_wrap --> 
        </div><!-- .content_wrap --> 
    </div><!-- #content --> 
</div><!-- .wrapper --> 
<?php get_footer(); ?>