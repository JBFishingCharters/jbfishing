<?php
define(THEME_FILE_PATH, dirname(__FILE__));
define(DIVI_THEME_FILE_PATH, THEME_FILE_PATH . '/../Divi');
define(DIVI_BUILDER_URI, DIVI_THEME_FILE_PATH . '/includes/builder');

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Uncomment to reveal errors
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/


/* Redirect category pages */
function category_template_redirect($url){
    if ( is_category( 'fishing-reports' ) ) {

        $url = site_url( '/fishing-reports/' );

        wp_safe_redirect( $url, 301 );

        exit;

    } else if( is_category( 'press' ) ) {

        $url = site_url( '/press/' );

        wp_safe_redirect( $url, 301 );

        exit;

    }

    return $url;
}
add_action( 'template_redirect', 'category_template_redirect' );

function term_link_filter( $url, $term, $taxonomy ) {
    if($taxonomy === "category" && $term){
        switch($term->slug){

            case "fishing-reports" :
                $url = str_replace('/category/fishing-reports/', '/fishing-reports/', $url);
                break;
                
            case "press" :
                $url = str_replace('/category/press/', '/press/', $url);
                break;
        }
    }
    return $url;
   
}
add_filter('term_link', 'term_link_filter', 10, 3);


function turn_off_divi_google_font( $translated, $text, $context, $domain ) {
    if ( 'Divi' == $domain ) {
        if ( 'on' == $text && 'Open Sans font: on or off' == $context ) {
            $translated = 'off';  // turn off
        }
    }

    return $translated;
}
add_filter( 'gettext_with_context', 'turn_off_divi_google_font', 10, 4 );

// BEGIN ENQUEUE PARENT ACTION
function jb_enqueue_scripts() {
    
    $fonts_url = jb_google_fonts_url();
	wp_register_style( "jb_google_fonts", $fonts_url);
    
    $parent_style = 'parent-style';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css', ["jb_google_fonts"] );
    /*wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );*/
    
    wp_register_script( 'jb-js', get_stylesheet_directory_uri() . '/js/footer-min.js', ["jquery", "divi-custom-script"], "121317.0", true );

    $jb_array = array( 'theme_path' => get_stylesheet_directory_uri() );
    //after wp_enqueue_script
    wp_localize_script( 'jb-js', 'jb_fishing', $jb_array );
    
    wp_enqueue_script( 'jb-js' );
}
add_action( 'wp_enqueue_scripts', 'jb_enqueue_scripts' );
// END ENQUEUE PARENT ACTION

function jb_google_fonts_url() {
	$fonts_url = '';

	$font_families = array();

	$font_families[] = 'Architects+Daughter';
	$font_families[] = 'Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&amp;subset=latin-ext';

	$fonts_url = "https://fonts.googleapis.com/css?family=".implode( '%7C', $font_families );

	return esc_url_raw($fonts_url);
}

// Add media sizes
function setImageSize($id, $w, $h, $crop){
    switch( $id ){
        case 'thumbnail' :
        case 'medium' :
        case 'large' :
            update_option( $id.'_size_w', $w );
            update_option( $id.'_size_h', $h );
            update_option( $id.'_crop', $crop );
            break;
        default :
            add_image_size( $id, $w, $h, $crop );
    }
}
setImageSize( 'tiny-thumbnail', 160, 90, true ); // Tiny Thumbnail (16:9)
setImageSize( 'thumbnail', 320, 240, true ); // Thumbnail (4:3)
setImageSize( 'thumbnail-16x9', 320, 180, true ); // Thumbnail (16:9)
setImageSize( 'small', 640, 360, false ); // Small (16:9)
setImageSize( 'medium', 800, 450, false ); // Medium (16:9)
setImageSize( 'large', 1280, 720, false ); // Large (16:9)
setImageSize( 'x-large', 1600, 900, false ); // X-Large (16:9)
setImageSize( 'xx-large', 1920, 1080, true ); // 2X-Large (16:9)

setImageSize( 'medium-4x3', 800, 600, false ); // Medium (4:3)
setImageSize( 'large-4x3', 1280, 960, false ); // Large (4:3)
setImageSize( 'x-large-4x3', 1600, 1200, false ); // X-Large (4:3)