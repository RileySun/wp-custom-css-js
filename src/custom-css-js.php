<?php

/*
Plugin Name: RedPixie Custom CSS/JS
Plugin URI: https://sun-sys.tk/Modules/Wordpress
Description: Add custom CSS & JS to any page in Wordpress!
Version: 1.0
Author: Riley Lesser
Author URI: https://sun-sys.tk
*/
add_action( 'add_meta_boxes', 'customMetaBoxAdd' );
add_action( 'save_post', 'customMetaBoxSave' );
add_action( 'wp_print_footer_scripts', 'addCustomCSSJS', 100 );

function customMetaBoxAdd() {
    add_meta_box( 'custom_css', 'Custom CSS', 'customCSSMetaBoxCall', 'page', 'normal', 'low' );
    add_meta_box( 'custom_js', 'Custom JS', 'customJSMetaBoxCall', 'page', 'normal', 'low' );
}
function customCSSMetaBoxCall( $post ) {
	$customCSS = get_post_meta( $post->ID, "Custom-CSS", true);
	$HTML = '
		<textarea id="Custom-CSS" name="Custom-CSS" class="Custom-Meta-Box">'.$customCSS.'</textarea>
		<style>.Custom-Meta-Box {display: block; width: 100%; height: 40vh; margin: 0; font-size: 1.2vw; resize: none;}</style>
	';
	echo $HTML;
}
function customJSMetaBoxCall( $post ) {
	$customJS = get_post_meta( $post->ID, "Custom-JS", true);
	$HTML = '
		<textarea id="Custom-JS" name="Custom-JS" class="Custom-Meta-Box">'.$customJS.'</textarea>
		<style>.Custom-Meta-Box {display: block; width: 100%; height: 40vh; margin: 0; font-size: 1.2vw; resize: none;}</style>
	';
	echo $HTML;
}
function customMetaBoxSave() {
	global $post;
	$post_id = $post->ID;
	
	$list = array('Custom-CSS', 'Custom-JS');
	foreach ($list as &$listItem) {
		$new_meta_value = ( isset( $_POST[$listItem] ) ? $_POST[$listItem] : '' );
		$meta_key = $listItem;
		$meta_value = get_post_meta( $post_id, $meta_key, true );
		
		if ( $new_meta_value && '' == $meta_value ) {
			add_post_meta( $post_id, $meta_key, $new_meta_value, true );
		}
		elseif ( $new_meta_value && $new_meta_value != $meta_value ) {
			update_post_meta( $post_id, $meta_key, $new_meta_value );
		}
		elseif ( '' == $new_meta_value && $meta_value ) {
			delete_post_meta( $post_id, $meta_key, $new_meta_value );
		}
	}
}
function addCustomCSSJS() {
	global $post;
	$customCSS = get_post_meta( $post->ID, "Custom-CSS", true);
	$customJS = get_post_meta( $post->ID, "Custom-JS", true);	
	$HTML = "";
	$HTML .= ($customCSS != "") ? '<style>'.$customCSS.'</style>' : "";
	$HTML .= ($customJS != "") ? '<script>'.$customJS.'</script>' : "";
	echo $HTML;
}

?>w