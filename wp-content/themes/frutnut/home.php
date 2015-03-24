<?php get_header(); ?>
	<?php get_template_part('includes/featured'); ?>
    <div id="quote">
        <span><?php esc_html_e('FRUTNUT - Sabor & Saúde','FrutNut'); ?></span>
        <br class="clear" />
    </div>
</div><!-- .wrapper -->

<div class="wrapper">
    <div id="blurbs">

        <?php
            $pages = array(
                17, 6, 2
            );

            $i = 0;
        ?>

        <?php foreach ($pages as $page) : $i++;?>

                <?php query_posts('page_id=' . lang_page_id($page));

                    while (have_posts()) : the_post();

                        $icon = '';
                        $icon = get_post_meta($post->ID, 'Icon', true);
                        $tagline = '';
                        $tagline = get_post_meta($post->ID, 'Tagline', true); ?>

                        <div id="blurbs-<?php echo $i; ?>" <?php if ($icon <> '') { ?>style="background-image: url(<?php echo esc_url($icon); ?>);"<?php }; ?>>
                            <span class="titles"><?php the_title(); ?></span>
                            <?php global $more;
                                        $more = 0;
                                        the_excerpt(); ?>
                            <br class="clear" />
                            <span class="readmore"><a href="<?php the_permalink(); ?>"><?php esc_html_e('read more','FrutNut'); ?></a></span>
                        </div>

                <?php endwhile; wp_reset_query(); ?>

		<?php endforeach; ?>

        <br class="clear" />
    </div>
</div>
<?php get_footer(); ?>
