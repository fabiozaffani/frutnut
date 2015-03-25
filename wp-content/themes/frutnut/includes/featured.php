<?php

    switch_to_blog(get_current_blog_id());

    global $ids;
	$arr = array();
	$featured_auto_class = 5000;
	$i=1;
	$width = 960;
	$height = 310;

    $showPosts = 4;

    query_posts(array(
        'post_per_page' => $showPosts,
        'category_name' => 'destaques'
    ));

	while (have_posts()) : the_post();
		global $post;

        $arr[$i]["id"]  = get_the_ID();
		$arr[$i]["title"] = truncate_title(35,false);
		$arr[$i]["excerpt"] = get_the_excerpt();
		$arr[$i]["permalink"] = get_permalink();

		$i++;
		$ids[] = $post->ID;
	endwhile; wp_reset_query();

?>

  <div class="slider">
    <div class="flexslider">
        <ul class="slides">
            <?php for ($i = 1; $i <= $showPosts; $i++): ?>
                <?php $imageArraySlider = wp_get_attachment_image_src(get_post_thumbnail_id($arr[$i]["id"]), 'slider'); ?>
                <?php $imageArraySmall = wp_get_attachment_image_src(get_post_thumbnail_id($arr[$i]["id"]), 'smallslider'); ?>

                <li data-thumb="<?php echo $imageArraySmall[0]; ?>">
                    <img src="<?php echo $imageArraySlider[0]; ?>" />
                    <div class="flex-caption">
                        <h2><?php echo $arr[$i]["title"]; ?></h2>
                        <p><?php echo $arr[$i]["excerpt"]; ?></p>
                        <span class="readmore">
                            <a href="<?php echo $arr[$i]["permalink"]; ?>"><?php esc_html_e('read more','AmazonBrazilNuts'); ?></a>
                        </span>
                    </div>
                </li>
            <?php endfor; ?>
        </ul>
    </div>
  </div>
