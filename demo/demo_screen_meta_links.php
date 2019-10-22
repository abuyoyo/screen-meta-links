<?php
/**
 * This is a demo file
 * 
 * Edit this file to test and debug screen-meta-links plugin
 *
 */

// uncomment this line
//add_action( 'admin_init' , array( &$this , 'debug_screen_meta_links' ) );

/**
 * DEBUG FUNCTIONS
 * 
 *
 */
function debug_screen_meta_links(){
	
	//add_action('admin_notices',function(){echo '<div class="notice notice-info is-dismissible"><p>notice-manager INIT</p></div>';} );
	
	
	add_screen_meta_link(
		'meta-link-example5', 
		'Example Link',
		'', //href
		array('index.php','dashboard')
	);
	
	add_screen_meta_link(
		'meta-link-all', 
		'* All Pages',
		'', //href
		'*'
	);
	
	add_screen_meta_link(
		'meta-link-post_php', 
		'post.php',
		'', //href
		'post.php', //this is a tricky one!
		array(
			'aria-controls' => 'meta-link-post_php-wrap'
		),// $attributes 
		array(&$this,'print_yet_another_panel')//$panel callback
	);
	
	add_screen_meta_link(
		'meta-link-post', 
		'post',
		'', //href
		'post', //this is a tricky one!
		array(
			'aria-controls' => 'meta-link-post_php-wrap'
		),// $attributes 
		array(&$this,'print_yet_another_panel')//$panel callback
	);
	
	/* add_screen_meta_link(
		'meta-link-another_panel', 
		'YAPP PANEL',
		'', //href
		'', //pages
		array(
			'aria-controls' => 'meta-link-another_panel-wrap'
		),// $attributes 
		array(&$this,'print_yet_another_panel')//$panel callback
	); */
	
	add_screen_meta_link(
		'some-id', 
		'unformatted id',
		'', //href
		'*',
		array(
			'aria-controls' => 'some-id-wrap'
		),// $attributes 
		array(&$this,'print_yet_another_panel')//$panel callback
	);

	add_screen_meta_link(
		'notice-manager', // $id
		'Notices', //$text
		'', // $href
		array(
			//'index.php',
			//'dashboard', //abuyoyo
			'options-general.php', //Settings -> General	
			'post',                //The post editor
			'page',                //The page editor (?) no such hook suffix or screen id - from original
			'toplevel_page_iac-engine',
			'edit.php',				//abuyoyo
			'edit',				//abuyoyo
			'edit-post',				//abuyoyo
			'post.php',				//abuyoyo
			'*',
		), // $page string or array of page/screen IDs
		
		array(
			'aria-controls' => 'notice-manager-wrap'
		),// $attributes - Additional attributes for the link tag.
		
		array(&$this,'print_yet_another_panel')//$panel callback - cb echoes its output
	);
	
}



function print_yet_another_panel(){
	echo "<div>Yet Another Panel Panel</div>";
}