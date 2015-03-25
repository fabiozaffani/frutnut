<?php session_start();
/*
Template Name: Contact Page
*/
?>
<?php 
	$et_ptemplate_settings = array();
	$et_ptemplate_settings = maybe_unserialize( get_post_meta($post->ID,'et_ptemplate_settings',true) );
	
	$et_regenerate_numbers = false;
		
	$et_error_message = '';
	$et_contact_error = false;
	
	if ( isset($_POST['et_contactform_submit']) ) {
		if ( !isset($_POST['et_contact_captcha']) || empty($_POST['et_contact_captcha']) ) {
			$et_error_message .= '<p>' . esc_html__('Make sure you entered the captcha. ','AmazonBrazilNuts') . '</p>';
			$et_contact_error = true;
		} else if ( $_POST['et_contact_captcha'] <> ( $_SESSION['et_first_digit'] + $_SESSION['et_second_digit'] ) ) {			
			$et_numbers_string = $et_regenerate_numbers ? esc_html__('Numbers regenerated.','AmazonBrazilNuts') : '';
			$et_error_message .= '<p>' . esc_html__('You entered the wrong number in captcha. ','AmazonBrazilNuts') . $et_numbers_string . '</p>';
			
			if ($et_regenerate_numbers) {
				unset( $_SESSION['et_first_digit'] );
				unset( $_SESSION['et_second_digit'] );
			}
			
			$et_contact_error = true;
		} else if ( empty($_POST['et_contact_name']) || empty($_POST['et_contact_email']) || empty($_POST['et_contact_subject']) || empty($_POST['et_contact_message']) ){
			$et_error_message .= '<p>' . esc_html__('Make sure you fill all fields. ','AmazonBrazilNuts') . '</p>';
			$et_contact_error = true;
		}
		
		if ( !is_email( $_POST['et_contact_email'] ) ) {
			$et_error_message .= '<p>' . esc_html__('Invalid Email. ','AmazonBrazilNuts') . '</p>';
			$et_contact_error = true;
		}
	} else {
		$et_contact_error = true;
		if ( isset($_SESSION['et_first_digit'] ) ) unset( $_SESSION['et_first_digit'] );
		if ( isset($_SESSION['et_second_digit'] ) ) unset( $_SESSION['et_second_digit'] );
	}
	
	if ( !isset($_SESSION['et_first_digit'] ) ) $_SESSION['et_first_digit'] = $et_first_digit = rand(1, 15);
	else $et_first_digit = $_SESSION['et_first_digit'];
	
	if ( !isset($_SESSION['et_second_digit'] ) ) $_SESSION['et_second_digit'] = $et_second_digit = rand(1, 15);
	else $et_second_digit = $_SESSION['et_second_digit'];
	
	if ( !$et_contact_error ) {
		$et_email_to = ( isset($et_ptemplate_settings['et_email_to']) && !empty($et_ptemplate_settings['et_email_to']) ) ? $et_ptemplate_settings['et_email_to'] : get_site_option('admin_email');
				
		$et_site_name = is_multisite() ? $current_site->site_name : get_bloginfo('name');	
		wp_mail($et_email_to, sprintf( '[%s] ' . esc_html($_POST['et_contact_subject']), $et_site_name ), esc_html($_POST['et_contact_message']),'From: "'. esc_html($_POST['et_contact_name']) .'" <' . esc_html($_POST['et_contact_email']) . '>');
		
		$et_error_message = '<p>' . esc_html__('Thanks for contacting us','AmazonBrazilNuts') . '</p>';
	}
?>

<?php get_header(); ?>
	<div id="content">

    	<div class="content_wrap">
            <div class="content_wrap">
              	<div id="posts">
                  <div id="breadcrumbs">
                      <?php get_template_part('includes/breadcrumbs'); ?>
                  </div>
                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<?php if (get_option('simplepress_integration_single_top') <> '' && get_option('simplepress_integrate_singletop_enable') == 'on') echo(get_option('simplepress_integration_single_top')); ?>
					<?php $thumb = '';
                    $width = 182;
                    $height = 182;
                    $classtext = '';
                    $titletext = get_the_title();
                    ?>
                    <div class="post">
                        <h2><?php the_title(); ?></h2>
                        <span class="line"></span>
                                <?php the_content(''); ?>
                            <br class="clear" />
							
							<div id="et-contact">

								<div id="et-contact-message"><?php echo($et_error_message); ?> </div>
								
								<?php if ( $et_contact_error ) { ?>
									<form action="<?php echo(get_permalink($post->ID)); ?>" method="post" id="et_contact_form">
										<div id="et_contact_left">
											<p>
												<label for="et_contact_name" class="et_contact_form_label"><?php esc_html_e('Name','AmazonBrazilNuts'); ?></label>
												<input type="text" name="et_contact_name" value="<?php if ( isset($_POST['et_contact_name']) ) echo esc_attr($_POST['et_contact_name']); else esc_attr_e('Name','AmazonBrazilNuts'); ?>" id="et_contact_name" class="input" />
											</p>
											
											<p>
												<label for="et_contact_email" class="et_contact_form_label"><?php esc_html_e('Email Address','AmazonBrazilNuts'); ?></label>
												<input type="text" name="et_contact_email" value="<?php if ( isset($_POST['et_contact_email']) ) echo esc_attr($_POST['et_contact_email']); else esc_attr_e('Email Address','AmazonBrazilNuts'); ?>" id="et_contact_email" class="input" />
											</p>
											
											<p>
												<label for="et_contact_subject" class="et_contact_form_label"><?php esc_html_e('Subject','AmazonBrazilNuts'); ?></label>
												<input type="text" name="et_contact_subject" value="<?php if ( isset($_POST['et_contact_subject']) ) echo esc_attr($_POST['et_contact_subject']); else esc_attr_e('Subject','AmazonBrazilNuts'); ?>" id="et_contact_subject" class="input" />
											</p>
										</div> <!-- #et_contact_left -->
										
										<div id="et_contact_right">
											<p>
												<?php 
													esc_html_e('Captcha: ','SimplePress');	
													echo '<br/>';
													echo esc_attr($et_first_digit) . ' + ' . esc_attr($et_second_digit) . ' = ';
												?>
												<input type="text" name="et_contact_captcha" value="<?php if ( isset($_POST['et_contact_captcha']) ) echo esc_attr($_POST['et_contact_captcha']); ?>" id="et_contact_captcha" class="input" size="2" />
											</p>
										</div> <!-- #et_contact_right -->
										
										<div class="clear"></div>
										
										<p class="clearfix">
											<label for="et_contact_message" class="et_contact_form_label"><?php esc_html_e('Message','AmazonBrazilNuts'); ?></label>
											<textarea class="input" id="et_contact_message" name="et_contact_message"><?php if ( isset($_POST['et_contact_message']) ) echo esc_textarea($_POST['et_contact_message']); else echo esc_textarea( __('Message','AmazonBrazilNuts') ); ?></textarea>
										</p>
											
										<input type="hidden" name="et_contactform_submit" value="et_contact_proccess" />
										
										<input type="reset" id="et_contact_reset" value="<?php esc_attr_e('Reset','AmazonBrazilNuts'); ?>" />
										<input class="et_contact_submit" type="submit" value="<?php esc_attr_e('Submit','AmazonBrazilNuts'); ?>" id="et_contact_submit" />
									</form>
								<?php } ?>
							</div> <!-- end #et-contact -->
							
							<div class="clear"></div>

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
