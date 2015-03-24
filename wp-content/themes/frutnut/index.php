<?php
if (is_category()) $post_number = get_option('simplepress_catnum_posts');
if (is_category($gallery_cat)) $post_number = get_option('simplepress_gallery_posts'); ?>
<?php get_header(); ?>
	<div id="content">
    	<div class="content_wrap">
            <div class="content_wrap">
            	<div id="posts">
                    <div id="breadcrumbs">
                        <?php get_template_part('includes/breadcrumbs'); ?>
                    </div>
                    <br class="clear"  />
					    <?php get_template_part('includes/entry'); ?>
                </div><!-- #posts -->  
				<?php get_sidebar(); ?>
            </div><!-- .content_wrap --> 
        </div><!-- .content_wrap --> 
    </div><!-- #content --> 
</div><!-- .wrapper --> 
<?php get_footer(); ?>