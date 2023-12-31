<?php
/**
 * UnderStrap functions and definitions
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// UnderStrap's includes directory.
$understrap_inc_dir = 'inc';

// Array of files to include.
$understrap_includes = array(
	'/theme-settings.php',                  // Initialize theme default settings.
	'/setup.php',                           // Theme setup and custom theme supports.
	'/widgets.php',                         // Register widget area.
	'/enqueue.php',                         // Enqueue scripts and styles.
	'/template-tags.php',                   // Custom template tags for this theme.
	'/pagination.php',                      // Custom pagination for this theme.
	'/hooks.php',                           // Custom hooks.
	'/extras.php',                          // Custom functions that act independently of the theme templates.
	'/customizer.php',                      // Customizer additions.
	'/custom-comments.php',                 // Custom Comments file.
	'/class-wp-bootstrap-navwalker.php',    // Load custom WordPress nav walker. Trying to get deeper navigation? Check out: https://github.com/understrap/understrap/issues/567.
	'/editor.php',                          // Load Editor functions.
	'/block-editor.php',                    // Load Block Editor functions.
	'/acf.php',                          // Load ACF functions.
	'/deprecated.php',                      // Load deprecated functions.
);

// Load WooCommerce functions if WooCommerce is activated.
if ( class_exists( 'WooCommerce' ) ) {
	$understrap_includes[] = '/woocommerce.php';
}

// Load Jetpack compatibility file if Jetpack is activiated.
if ( class_exists( 'Jetpack' ) ) {
	$understrap_includes[] = '/jetpack.php';
}

// Include files.
foreach ( $understrap_includes as $file ) {
	require_once get_theme_file_path( $understrap_inc_dir . $file );
}

//Take over format dropdown in TinyMCE classic editor 
//from https://gist.github.com/psorensen/ab45d9408be658b6f90dfeabf1c9f4e6
function mce_formats( $init ) {

	$formats = array(
		'p'          => __( 'Paragraph', 'text-domain' ),
		'h1'         => __( 'Heading 1', 'text-domain' ),
		'h2'         => __( 'Heading 2', 'text-domain' ),
		'h3'         => __( 'Heading 2', 'text-domain' ),
		'h4'         => __( 'Heading 2', 'text-domain' ),
		'h5'         => __( 'Heading 2', 'text-domain' ),
		'h6'         => __( 'Heading 2', 'text-domain' ),
		'pre'        => __( 'Preformatted', 'text-domain' ),
		'blockquote' => __( 'Blockquote', 'text-domain' ),		
	);
    
    // concat array elements to string
	array_walk( $formats, function ( $key, $val ) use ( &$block_formats ) {
		$block_formats .= esc_attr( $key ) . '=' . esc_attr( $val ) . ';';
	}, $block_formats = '' );

	$init['block_formats'] = $block_formats;

	return $init;
}
add_filter( 'tiny_mce_before_init', __NAMESPACE__ . '\\mce_formats' );

add_theme_support( 'responsive-embeds');



//hide show elements based on option set
//shows/hides standard content piece
function dlinq_acf_regular_event( $field ) {
    // Don't show this field once it contains a value.
    if( get_field('structured_events', 'options') === 'yes' ) {
        return false;
    }
    return $field;
}

// Apply to fields named "example_field".
add_filter('acf/prepare_field/key=field_6514218c94d25', 'dlinq_acf_regular_event');

//shows/hides standard content piece
function dlinq_acf_structured_event( $field ) {

    // Don't show this field once it contains a value.
    if( get_field('structured_events', 'options') === 'no' ) {
        return false;
    }
    return $field;
}

// Apply to fields named "example_field".
add_filter('acf/prepare_field/key=field_6569f3aeaceaf', 'dlinq_acf_structured_event');

