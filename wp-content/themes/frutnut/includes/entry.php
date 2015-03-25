<?php 
	if ( is_home() ){
		if (get_option('simplepress_duplicate') == 'false') {
			global $ids;
			$args=array(
				   'showposts'=> (int) get_option('simplepress_homepage_posts'),
				   'post__not_in' => $ids,
				   'paged'=>$paged,
				   'category__not_in' => (array) get_option('simplepress_exlcats_recent'),
			);
		} else {
			$args=array(
			   'showposts'=> (int) get_option('simplepress_homepage_posts'),
			   'paged'=>$paged,
			   'category__not_in' => (array) get_option('simplepress_exlcats_recent'),
			);
		};
		query_posts($args);	
	} ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php $thumb = '';
	$width = 182;
	$height = 182;
	$classtext = '';
	$titletext = get_the_title();
	?>
	<div class="post">
		<div class="text <?php if ($thumb == '' || get_option('simplepress_thumbnails_index') == 'false') print "no_thumb" ?>">
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php if (get_option('simplepress_blog_style') == 'false') { ?>
			<?php truncate_post(400);?>
			<br class="clear" />
			<span class="readmore"><a href="<?php the_permalink(); ?>"><?php esc_html_e('read more','SimplePress'); ?></a></span>
			<?php }; ?>
		</div>
		<?php if (get_option('simplepress_blog_style') == 'on') { ?>
		<?php the_content(''); ?>
		<br class="clear" />
		<span class="readmore"><a href="<?php the_permalink(); ?>"><?php esc_html_e('read more','SimplePress'); ?></a></span>
		<?php }; ?>
	</div><!-- .post -->
<?php endwhile; ?>
	<br class="clear"  />
	<div class="entry page-nav clearfix">
        <?php   if(function_exists('wp_pagenavi')) :
                    wp_pagenavi();
		        else:
        ?>
                    <div class="pagination">
                        <div class="alignleft"><?php next_posts_link(esc_html__('&laquo; Older Entries','SimplePress')) ?></div>
                        <div class="alignright"><?php previous_posts_link(esc_html__('Next Entries &raquo;', 'SimplePress')) ?></div>
                    </div>
		<?php
                endif;
        ?>
	</div> <!-- end .entry -->
<?php else : ?>
    <div class="entry">
        <!--If no results are found-->
        <h1><?php esc_html_e('No Results Found','SimplePress'); ?></h1>
        <p><?php esc_html_e('The page you requested could not be found. Try refining your search, or use the navigation above to locate the post.','SimplePress'); ?></p>
    </div>
<?php endif; if ( is_home() ) wp_reset_query(); ?>
