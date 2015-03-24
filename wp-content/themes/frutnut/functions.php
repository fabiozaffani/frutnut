<?php

add_theme_support( 'post-thumbnails' );
add_image_size('slider', 960, 310, true);
add_image_size('smallslider', 317, 100, true);


add_action('after_setup_theme', 'setup_language');

function setup_language(){
    load_theme_textdomain('AmazonBrazilNuts', get_template_directory() . '/language');
}

/* INTERNATIOLIZATION */

if (! function_exists('lang_category_id'))
{
    function lang_category_id($id){
        if(function_exists('icl_object_id')) {
            return icl_object_id($id,'category',true);
        } else {
            return $id;
        }
    }
}

if (! function_exists('lang_page_id'))
{
    function lang_page_id($id){
        if(function_exists('icl_object_id')) {
            return icl_object_id($id,'page',false);
        } else {
            return $id;
        }
    }
}

if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'name' => 'Sidebar',
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div> <!-- end .widget -->',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));

function custom_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

function register_main_menus() {
	register_nav_menus(
		array(
			'primary-menu' => __( 'Primary Menu' )
		)
	);
};

if (function_exists('register_nav_menus')) add_action( 'init', 'register_main_menus' );

if ( ! function_exists( 'get_custom_header' ) ) {
    // compatibility with versions of WordPress prior to 3.4.
    add_custom_background();
} else {
    add_theme_support( 'custom-background', apply_filters( 'et_custom_background_args', array() ) );
}

if (function_exists('add_post_type_support')) add_post_type_support( 'page', 'excerpt' );
add_theme_support( 'automatic-feed-links' );


add_filter('widget_text', 'do_shortcode');
add_filter('the_excerpt', 'do_shortcode');

if ( ! function_exists( 'et_options_stored_in_one_row' ) ){
    function et_options_stored_in_one_row(){
        global $et_store_options_in_one_row;

        return isset( $et_store_options_in_one_row ) ? (bool) $et_store_options_in_one_row : false;
    }
}

add_filter('body_class','et_browser_body_class');
function et_browser_body_class($classes) {
    global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

    if($is_lynx) $classes[] = 'lynx';
    elseif($is_gecko) $classes[] = 'gecko';
    elseif($is_opera) $classes[] = 'opera';
    elseif($is_NS4) $classes[] = 'ns4';
    elseif($is_safari) $classes[] = 'safari';
    elseif($is_chrome) $classes[] = 'chrome';
    elseif($is_IE) $classes[] = 'ie';
    else $classes[] = 'unknown';

    if($is_iphone) $classes[] = 'iphone';
    return $classes;
}

// Tells wp_trim_words() function to use characters instead of words
function et_wp_trim_words_to_characters( $default_translated_text, $original_text, $context ) {
    if ( ! is_admin() && 'words' == $original_text && 'word count: words or characters?' == $context ) {
        return 'characters';
    }

    return $default_translated_text;
}
add_filter( 'gettext_with_context', 'et_wp_trim_words_to_characters', 20, 3 );


/*this function truncates titles to create preview excerpts*/
if ( ! function_exists( 'truncate_title' ) ){
    function truncate_title( $amount, $echo = true, $post = '' ) {
        if ( $post == '' ) $truncate = get_the_title();
        else $truncate = $post->post_title;

        if ( strlen( $truncate ) <= $amount ) $echo_out = '';
        else $echo_out = '...';

        $truncate = wp_trim_words( $truncate, $amount, '' );

        if ( '' != $echo_out ) $truncate .= $echo_out;

        if ( $echo )
            echo $truncate;
        else
            return $truncate;
    }
}


if ( ! function_exists( 'et_new_thumb_resize' ) ){
    function et_new_thumb_resize( $thumbnail, $width, $height, $alt='', $forstyle = false ){
        global $shortname;

        $new_method = true;
        $new_method_thumb = '';
        $external_source = false;

        $allow_new_thumb_method = !$external_source && $new_method;

        if ( $allow_new_thumb_method && $thumbnail <> '' ){
            $et_crop = true;
            $new_method_thumb = et_resize_image( $thumbnail, $width, $height, $et_crop );
            if ( is_wp_error( $new_method_thumb ) ) $new_method_thumb = '';
        }

        $thumb = esc_attr( $new_method_thumb );

        $output = '<img src="' . esc_url( $thumb ) . '" alt="' . esc_attr( $alt ) . '" width =' . esc_attr( $width ) . ' height=' . esc_attr( $height ) . ' />';

        return ( !$forstyle ) ? $output : $thumb;
    }
}

if ( ! function_exists( 'et_multisite_thumbnail' ) ){
    function et_multisite_thumbnail( $thumbnail = '' ) {
        // do nothing if it's not a Multisite installation or current site is the main one
        if ( is_main_site() ) return $thumbnail;

        # get the real image url
        preg_match( '#([_0-9a-zA-Z-]+/)?files/(.+)#', $thumbnail, $matches );
        if ( isset( $matches[2] ) ){
            $file = rtrim( BLOGUPLOADDIR, '/' ) . '/' . str_replace( '..', '', $matches[2] );
            if ( is_file( $file ) ) $thumbnail = str_replace( ABSPATH, trailingslashit( get_site_url( 1 ) ), $file );
            else $thumbnail = '';
        }

        return $thumbnail;
    }
}

if ( ! function_exists( 'et_is_portrait' ) ){
    function et_is_portrait($imageurl, $post='', $ignore_cfields = false){
        if ( $post == '' ) global $post;

        if ( get_post_meta($post->ID,'et_disable_portrait',true) == 1 ) return false;

        if ( !$ignore_cfields ) {
            if ( get_post_meta($post->ID,'et_imagetype',true) == 'l' ) return false;
            if ( get_post_meta($post->ID,'et_imagetype',true) == 'p' ) return true;
        }

        $imageurl = et_path_reltoabs(et_multisite_thumbnail($imageurl));

        $et_thumb_size = @getimagesize($imageurl);
        if ( empty($et_thumb_size) ) {
            $et_thumb_size = @getimagesize( str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $imageurl ) );
            if ( empty($et_thumb_size) ) return false;
        }
        $et_thumb_width = $et_thumb_size[0];
        $et_thumb_height = $et_thumb_size[1];

        $result = ($et_thumb_width < $et_thumb_height) ? true : false;

        return $result;
    }
}

if ( ! function_exists( 'et_path_reltoabs' ) ){
    function et_path_reltoabs( $imageurl ){
        if ( strpos(strtolower($imageurl), 'http://') !== false || strpos(strtolower($imageurl), 'https://') !== false ) return $imageurl;

        if ( strpos( strtolower($imageurl), $_SERVER['HTTP_HOST'] ) !== false )
            return $imageurl;
        else {
            $imageurl = esc_url( apply_filters( 'et_path_relative_image', site_url() . '/' ) . $imageurl );
        }

        return $imageurl;
    }
}

if ( ! function_exists( 'in_subcat' ) ){
    function in_subcat($blogcat,$current_cat='') {
        $in_subcategory = false;

        if (cat_is_ancestor_of($blogcat,$current_cat) || $blogcat == $current_cat) $in_subcategory = true;

        return $in_subcategory;
    }
}

/*this function gets page name by its id*/
if ( ! function_exists( 'get_pagename' ) ){
    function get_pagename( $page_id )
    {
        $page_object = get_page( $page_id );

        return apply_filters( 'the_title', $page_object->post_title, $page_id );
    }
}

/*this function gets category name by its id*/
if ( ! function_exists( 'get_categname' ) ){
    function get_categname( $cat_id )
    {
        return get_cat_name( $cat_id );
    }
}

/*this function gets category id by its name*/
if ( ! function_exists( 'get_catId' ) ){
    function get_catId( $cat_name )
    {
        $cat_name_id = is_numeric( $cat_name ) ? (int) $cat_name : (int) get_cat_ID( html_entity_decode( $cat_name, ENT_QUOTES ) );

        // wpml compatibility
        if ( function_exists( 'icl_object_id' ) )
            $cat_name_id = (int) icl_object_id( $cat_name_id, 'category', true );

        return $cat_name_id;
    }
}

/*this function gets page id by its name*/
if ( ! function_exists( 'get_pageId' ) ){
    function get_pageId( $page_name )
    {
        if ( is_numeric( $page_name ) ) {
            $page_id = intval( $page_name );
        } else {
            $page_name = html_entity_decode( $page_name, ENT_QUOTES );
            $page = get_page_by_title( $page_name );
            $page_id = intval( $page->ID );
        }

        // wpml compatibility
        if ( function_exists( 'icl_object_id' ) )
            $page_id = (int) icl_object_id( $page_id, 'page', true );

        return $page_id;
    }
}

/**
 * Transforms an array of posts, pages, post_tags or categories ids
 * into corresponding "objects" ids, if WPML plugin is installed
 *
 * @param array $ids_array Posts, pages, post_tags or categories ids.
 * @param string $type "Object" type.
 * @return array IDs.
 */
if ( ! function_exists( 'et_generate_wpml_ids' ) ){
    function et_generate_wpml_ids( $ids_array, $type ) {
        if ( function_exists( 'icl_object_id' ) ){
            $wpml_ids = array();
            foreach ( $ids_array as $id ) {
                $translated_id = icl_object_id( $id, $type, false );
                if ( ! is_null( $translated_id ) ) $wpml_ids[] = $translated_id;
            }
            $ids_array = $wpml_ids;
        }

        return array_map( 'intval', $ids_array );
    }
}

add_action( 'init', 'et_create_images_temp_folder' );
function et_create_images_temp_folder(){
    #clean et_temp folder once per week
    if ( false !== $last_time = get_option( 'et_schedule_clean_images_last_time'  ) ){
        $timeout = 86400 * 7;
        if ( ( $timeout < ( time() - $last_time ) ) && '' != get_option( 'et_images_temp_folder' ) ) et_clean_temp_images( get_option( 'et_images_temp_folder' ) );
    }

    if ( false !== get_option( 'et_images_temp_folder' ) ) return;

    $uploads_dir = wp_upload_dir();
    $destination_dir = ( false === $uploads_dir['error'] ) ? path_join( $uploads_dir['basedir'], 'et_temp' ) : null;

    if ( ! wp_mkdir_p( $destination_dir ) ) update_option( 'et_images_temp_folder', '' );
    else {
        update_option( 'et_images_temp_folder', preg_replace( '#\/\/#', '/', $destination_dir ) );
        update_option( 'et_schedule_clean_images_last_time', time() );
    }
}

if ( ! function_exists( 'et_clean_temp_images' ) ){
    function et_clean_temp_images( $directory ){
        $dir_to_clean = @ opendir( $directory );

        if ( $dir_to_clean ) {
            while (($file = readdir( $dir_to_clean ) ) !== false ) {
                if ( substr($file, 0, 1) == '.' )
                    continue;
                if ( is_dir( $directory.'/'.$file ) )
                    et_clean_temp_images( path_join( $directory, $file ) );
                else
                    @ unlink( path_join( $directory, $file ) );
            }
            closedir( $dir_to_clean );
        }

        #set last time cleaning was performed
        update_option( 'et_schedule_clean_images_last_time', time() );
    }
}

add_filter( 'update_option_upload_path', 'et_update_uploads_dir' );
function et_update_uploads_dir( $upload_path ){
    #check if we have 'et_temp' folder within $uploads_dir['basedir'] directory, if not - try creating it, if it's not possible $destination_dir = null

    $destination_dir = '';
    $uploads_dir = wp_upload_dir();
    $et_temp_dir = path_join( $uploads_dir['basedir'], 'et_temp' );

    if ( is_dir( $et_temp_dir ) || ( false === $uploads_dir['error'] && wp_mkdir_p( $et_temp_dir ) ) ){
        $destination_dir = $et_temp_dir;
        update_option( 'et_schedule_clean_images_last_time', time() );
    }

    update_option( 'et_images_temp_folder', preg_replace( '#\/\/#', '/', $destination_dir ) );

    return $upload_path;
}

if ( ! function_exists( 'et_resize_image' ) ){
    function et_resize_image( $thumb, $new_width, $new_height, $crop ){
        if ( is_ssl() ) $thumb = preg_replace( '#^http://#', 'https://', $thumb );
        $info = pathinfo($thumb);
        $ext = $info['extension'];
        $name = wp_basename($thumb, ".$ext");
        $is_jpeg = false;
        $site_uri = apply_filters( 'et_resize_image_site_uri', site_url() );
        $site_dir = apply_filters( 'et_resize_image_site_dir', ABSPATH );

        // If multisite, not the main site, WordPress version < 3.5 or ms-files rewriting is enabled ( not the fresh WordPress installation, updated from the 3.4 version )
        if ( is_multisite() && ! is_main_site() && ( ! function_exists( 'wp_get_mime_types' ) || get_site_option( 'ms_files_rewriting' ) ) ) {
            //Get main site url on multisite installation

            switch_to_blog( 1 );
            $site_uri = site_url();
            restore_current_blog();
        }

        if ( 'jpeg' == $ext ) {
            $ext = 'jpg';
            $name = preg_replace( '#.jpeg$#', '', $name );
            $is_jpeg = true;
        }

        $suffix = "{$new_width}x{$new_height}";

        $destination_dir = '' != get_option( 'et_images_temp_folder' ) ? preg_replace( '#\/\/#', '/', get_option( 'et_images_temp_folder' ) ) : null;

        $matches = apply_filters( 'et_resize_image_site_dir', array(), $site_dir );
        if ( !empty($matches) ){
            preg_match( '#'.$matches[1].'$#', $site_uri, $site_uri_matches );
            if ( !empty($site_uri_matches) ){
                $site_uri = str_replace( $matches[1], '', $site_uri );
                $site_uri = preg_replace( '#/$#', '', $site_uri );
                $site_dir = str_replace( $matches[1], '', $site_dir );
                $site_dir = preg_replace( '#\\\/$#', '', $site_dir );
            }
        }

        #get local name for use in file_exists() and get_imagesize() functions
        $localfile = str_replace( apply_filters( 'et_resize_image_localfile', $site_uri, $site_dir, et_multisite_thumbnail($thumb) ), $site_dir, et_multisite_thumbnail($thumb) );

        $add_to_suffix = '';
        if ( file_exists( $localfile ) ) $add_to_suffix = filesize( $localfile ) . '_';

        #prepend image filesize to be able to use images with the same filename
        $suffix = $add_to_suffix . $suffix;
        $destfilename_attributes = '-' . $suffix . '.' . $ext;

        $checkfilename = ( '' != $destination_dir && null !== $destination_dir ) ? path_join( $destination_dir, $name ) : path_join( dirname( $localfile ), $name );
        $checkfilename .= $destfilename_attributes;

        if ( $is_jpeg ) $checkfilename = preg_replace( '#.jpeg$#', '.jpg', $checkfilename );

        $uploads_dir = wp_upload_dir();
        $uploads_dir['basedir'] = preg_replace( '#\/\/#', '/', $uploads_dir['basedir'] );

        if ( null !== $destination_dir && '' != $destination_dir && apply_filters('et_enable_uploads_detection', true) ){
            $site_dir = trailingslashit( preg_replace( '#\/\/#', '/', $uploads_dir['basedir'] ) );
            $site_uri = trailingslashit( $uploads_dir['baseurl'] );
        }

        #check if we have an image with specified width and height

        if ( file_exists( $checkfilename ) ) return str_replace( $site_dir, trailingslashit( $site_uri ), $checkfilename );

        $size = @getimagesize( $localfile );
        if ( !$size ) return new WP_Error('invalid_image_path', __('Image doesn\'t exist'), $thumb);
        list($orig_width, $orig_height, $orig_type) = $size;

        #check if we're resizing the image to smaller dimensions
        if ( $orig_width > $new_width || $orig_height > $new_height ){
            if ( $orig_width < $new_width || $orig_height < $new_height ){
                #don't resize image if new dimensions > than its original ones
                if ( $orig_width < $new_width ) $new_width = $orig_width;
                if ( $orig_height < $new_height ) $new_height = $orig_height;

                #regenerate suffix and appended attributes in case we changed new width or new height dimensions
                $suffix = "{$add_to_suffix}{$new_width}x{$new_height}";
                $destfilename_attributes = '-' . $suffix . '.' . $ext;

                $checkfilename = ( '' != $destination_dir && null !== $destination_dir ) ? path_join( $destination_dir, $name ) : path_join( dirname( $localfile ), $name );
                $checkfilename .= $destfilename_attributes;

                #check if we have an image with new calculated width and height parameters
                if ( file_exists($checkfilename) ) return str_replace( $site_dir, trailingslashit( $site_uri ), $checkfilename );
            }

            #we didn't find the image in cache, resizing is done here
            if ( ! function_exists( 'wp_get_image_editor' ) ) {
                // compatibility with versions of WordPress prior to 3.5.
                $result = image_resize( $localfile, $new_width, $new_height, $crop, $suffix, $destination_dir );
            } else {
                $et_image_editor = wp_get_image_editor( $localfile );

                if ( ! is_wp_error( $et_image_editor ) ) {
                    $et_image_editor->resize( $new_width, $new_height, $crop );

                    // generate correct file name/path
                    $et_new_image_name = $et_image_editor->generate_filename( $suffix, $destination_dir );

                    do_action( 'et_resize_image_before_save', $et_image_editor, $et_new_image_name );

                    $et_image_editor->save( $et_new_image_name );

                    // assign new image path
                    $result = $et_new_image_name;
                } else {
                    // assign a WP_ERROR ( WP_Image_Editor instance wasn't created properly )
                    $result = $et_image_editor;
                }
            }

            if ( ! is_wp_error( $result ) ) {
                // transform local image path into URI

                if ( $is_jpeg ) $thumb = preg_replace( '#.jpeg$#', '.jpg', $thumb);

                $site_dir = str_replace( '\\', '/', $site_dir );
                $result = str_replace( '\\', '/', $result );
                $result = str_replace( '//', '/', $result );
                $result = str_replace( $site_dir, trailingslashit( $site_uri ), $result );
            }

            #returns resized image path or WP_Error ( if something went wrong during resizing )
            return $result;
        }

        #returns unmodified image, for example in case if the user is trying to resize 800x600px to 1920x1080px image
        return $thumb;
    }
}

add_filter('pre_set_site_transient_update_themes', 'et_check_themes_updates');
function et_check_themes_updates( $update_transient ){
    global $wp_version;

    if ( !isset($update_transient->checked) ) return $update_transient;
    else $themes = $update_transient->checked;

    $send_to_api = array(
        'action' => 'check_theme_updates',
        'installed_themes' => $themes
    );

    $options = array(
        'timeout' => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30 : 3),
        'body'			=> $send_to_api,
        'user-agent'	=> 'WordPress/' . $wp_version . '; ' . home_url()
    );

    $last_update = new stdClass();

    $theme_request = wp_remote_post( 'http://www.elegantthemes.com/api/api.php', $options );
    if ( !is_wp_error($theme_request) && wp_remote_retrieve_response_code($theme_request) == 200 ){
        $theme_response = unserialize( wp_remote_retrieve_body( $theme_request ) );
        if ( !empty($theme_response) ) {
            $update_transient->response = array_merge(!empty($update_transient->response) ? $update_transient->response : array(),$theme_response);
            $last_update->checked = $themes;
            $last_update->response = $theme_response;
        }
    }

    $last_update->last_checked = time();
    set_site_transient( 'et_update_themes', $last_update );

    return $update_transient;
}

add_filter('site_transient_update_themes', 'et_add_themes_to_update_notification');
function et_add_themes_to_update_notification( $update_transient ){
    $et_update_themes = get_site_transient( 'et_update_themes' );
    if ( !is_object($et_update_themes) || !isset($et_update_themes->response) ) return $update_transient;
    $update_transient->response = array_merge(!empty($update_transient->response) ? $update_transient->response : array(), $et_update_themes->response);

    return $update_transient;
}


add_filter( 'default_hidden_meta_boxes', 'et_show_hidden_metaboxes', 10, 2 );
function et_show_hidden_metaboxes( $hidden, $screen ){
    # make custom fields and excerpt meta boxes show by default
    if ( 'post' == $screen->base || 'page' == $screen->base )
        $hidden = array('slugdiv', 'trackbacksdiv', 'commentstatusdiv', 'commentsdiv', 'authordiv', 'revisionsdiv');

    return $hidden;
}

add_filter('widget_title','et_widget_force_title');
function et_widget_force_title( $title ){
    #add an empty title for widgets ( otherwise it might break the sidebar layout )
    if ( $title == '' ) $title = ' ';

    return $title;
}


add_filter( 'gettext', 'et_admin_update_theme_message', 20, 3 );
function et_admin_update_theme_message( $default_translated_text, $original_text, $domain ) {
    global $themename;
    $theme_page_message = 'There is a new version of %1$s available. <a href="%2$s" class="thickbox" title="%1$s">View version %3$s details</a>. <em>Automatic update is unavailable for this theme.</em>';
    $updates_page_message = 'Update package not available.';

    if ( is_admin() && $original_text === $theme_page_message ) {
        return __( 'There is a new version of %1$s available. <a href="%2$s" class="thickbox" title="%1$s">View version %3$s details</a>. <em>Auto-updates are not available for this theme. If this is an Elegant Themes theme, then you must re-download the theme from the member\'s area and <a href="http://www.elegantthemes.com/members-area/documentation.html#update" target="_blank">re-install it</a> in order to update it to the latest version.</em>', $themename );
    }

    if ( is_admin() && $original_text === $updates_page_message ){
        return __( 'Auto-updates are not available for this theme. If this is an Elegant Themes theme, then you must re-download the theme from the member\'s area and <a href="http://www.elegantthemes.com/members-area/documentation.html#update" target="_blank">re-install it</a> in order to update it to the latest version.', $themename );
    }

    return $default_translated_text;
}

add_filter( 'body_class', 'et_add_fullwidth_body_class' );
function et_add_fullwidth_body_class( $classes ){
    $fullwidth_view = false;

    if ( is_page_template('page-full.php') ) $fullwidth_view = true;

    if ( is_page() || is_single() ){
        $et_ptemplate_settings = get_post_meta( get_queried_object_id(),'et_ptemplate_settings',true );
        $fullwidth = isset( $et_ptemplate_settings['et_fullwidthpage'] ) ? (bool) $et_ptemplate_settings['et_fullwidthpage'] : false;

        if ( $fullwidth ) $fullwidth_view = true;
    }

    if ( is_single() && 'on' == get_post_meta( get_queried_object_id(), '_et_full_post', true ) ) $fullwidth_view = true;

    $classes[] = apply_filters( 'et_fullwidth_view_body_class', $fullwidth_view ) ? 'et_fullwidth_view' : 'et_includes_sidebar';

    return $classes;
}

if ( ! function_exists( 'et_gf_attach_font' ) ) :
    /**
     * Attaches Google Font to given css elements
     *
     */
    function et_gf_attach_font( $et_gf_font_name, $elements ) {
        $google_fonts = et_get_google_fonts();

        printf( '%s { font-family: \'%s\', %s; }',
            esc_html( $elements ),
            esc_html( $et_gf_font_name ),
            et_get_websafe_font_stack( $google_fonts[$et_gf_font_name]['type'] )
        );
    }
endif;

/********* Page Templates v.1.8 ************/

define( 'JS_PATH', get_template_directory_uri() . '/js/' );

add_action('wp_print_styles','et_ptemplates_css');
function et_ptemplates_css(){
    if ( !is_admin() && !(strstr( $_SERVER['PHP_SELF'], 'wp-login.php')) ) {
        wp_enqueue_style( 'fancybox', JS_PATH . 'fancybox/jquery.fancybox-1.3.4.css', array(), '1.3.4', 'screen' );
        wp_enqueue_style( 'flexslider', JS_PATH . 'flexslider.css', array(), '2.2.0', 'screen' );
        wp_enqueue_style( 'et_page_templates', JS_PATH . '/page_templates.css', array(), '1.8', 'screen' );
    }
}

add_action('wp_print_scripts','et_ptemplates_footer_js');
function et_ptemplates_footer_js(){
    global $themename;
    if ( !is_admin() ) {
        wp_enqueue_script( 'easing', JS_PATH . 'fancybox/jquery.easing-1.3.pack.js', array('jquery'), '1.3.4', true );
        wp_enqueue_script( 'fancybox', JS_PATH . 'fancybox/jquery.fancybox-1.3.4.pack.js', array('jquery'), '1.3.4', true );
        wp_enqueue_script( 'front-easing', JS_PATH . 'easing.js', array('jquery'), '1.0', true );
        wp_enqueue_script( 'superfish', JS_PATH . 'superfish.js', array('jquery'), '1.4.8', true );
        wp_enqueue_script( 'flexslider', JS_PATH . 'jquery.flexslider-min.js', array('jquery'), '2.2.0', true );
        wp_enqueue_script( 'et-ptemplates-frontend', JS_PATH . 'et-ptemplates-frontend.js', array('jquery','fancybox', 'front-easing', 'superfish', 'flexslider'), '1.1', true );
        wp_localize_script( 'et-ptemplates-frontend', 'et_ptemplates_strings', array( 'captcha' => esc_html__( 'Captcha', $themename ), 'fill' => esc_html__( 'Fill', $themename ), 'field' => esc_html__( 'field', $themename ), 'invalid' => esc_html__( 'Invalid email', $themename ) ) );


    }
}
