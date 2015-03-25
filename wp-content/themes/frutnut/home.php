<?php get_header(); ?>
	<?php get_template_part('includes/featured'); ?>
    <div id="quote">
        <span><?php esc_html_e('PROTECTING THE FOREST AND YOUR HEALTH','AmazonBrazilNuts'); ?></span>
        <br class="clear" />
        <span><?php esc_html_e('RICH IN SELENIUM','AmazonBrazilNuts'); ?></span>
    </div>
</div><!-- .wrapper -->

<div class="wrapper">
    <div id="blurbs">

        <?php
            $pages = array(
                21, 22, 1128, 1125
            );

            $i = 0;
        ?>

        <?php foreach ($pages as $page) : $i++;?>

                <?php query_posts('page_id=' . $page);

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
                            <span class="readmore"><a href="<?php the_permalink(); ?>"><?php esc_html_e('read more','AmazonBrazilNuts'); ?></a></span>
                        </div>

                        <?php
                    endwhile; wp_reset_query();
            
                // hack para resetar corretamente o blurb
                if ($i == 2) {
                    $i = 0;
                };

            endforeach; ?>

        <object width="310" height="310" align="absmiddle" style="margin-left: 15px;">
            <param value="http://video.globo.com/Portal/videos/cda/player/player.swf" name="movie">
            <param value="high" name="quality">
            <param value="midiaId=898651&amp;autoStart=false&amp;width=380&amp;height=280" name="FlashVars">
            <embed src="http://video.globo.com/Portal/videos/cda/player/player.swf" width="380" height="280" align="absmiddle" flashvars="midiaId=898651&amp;autoStart=false&amp;width=380&amp;height=280" type="application/x-shockwave-flash" quality="high">
        </object>

        <br class="clear" />
    </div>
</div>
<?php get_footer(); ?>
