<?php
/**
 * Plugin Name: abuyoyo / Screen Meta Links
 * Description: API for adding custom screen-meta-links alongside the "Screen Options" and "Help" links.
 * Version: 0.10
 * Author: abuyoyo
 * Author URI: https://github.com/abuyoyo
 * Plugin URI: https://github.com/abuyoyo/screen-meta-links
*/
/**
 * TODO:
 * 
 * if panel exists add 'aria-controls' attribute if none supplied
 * if no panel exists render anchor tag instead of button
 * 
 * 
 */

define ( 'SML_FILE', __FILE__ );
define ( 'SML_URL', plugin_dir_url( __FILE__ ) );

if ( ! class_exists('Screen_Meta_Links') ):

 
class Screen_Meta_Links {

	protected static $instance = null;
	
	static $registered_requests;
	static $links;
	static $panels;
	static $counter;

	static $debug = false;
	
	/**
	 * Constructor.
	 * 
	 * @return void
	 */
	function __construct(){
		global $iac_eng_settings;
		
		self::$registered_requests = array();
		self::$links = array();
		self::$panels = array();
		
		self::$counter = -1;
		
		if( defined( 'DEBUG_SCREEN_META_LINKS' ) ){
			self::$debug = true;
		}
		
		// inline solution
		add_action( 'current_screen' , [ $this, 'setup_current_screen_meta_links' ], 100 );
		add_action( 'admin_notices', [ $this, 'append_meta_links' ] ); // print inline sml script
		
		// external load solution 
		// add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'admin_print_styles', [ $this, 'add_link_styles' ] ); //admin_enqueue_styles too early
	}
	
	/**
	 * Get Instance
	 * 
	 * @return self::$instance
	 */
	public static function get_instance() {
		if (null == self::$instance) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	
	/**
	 * Register a new link (and optional panel) to the screen-meta area.
	 * 
	 * Do not call this method directly. Instead, use the global add_screen_meta_link() function.
	 * 
	 * @param string $id            - Link ID. Should be unique and a valid value for a HTML ID attribute.
	 * @param string $text          - Link text.
	 * @param string|string[] $page - The page(s) where you want to add the link.
	 * @param array $attributes     - Optional. Additional attributes for the link tag. Add 'aria-controls' => "{$id}-wrap" to toggle panel 
	 * @param callback $panel       - Optional. Callback should print out screen-meta panel contents
	 *
	 * @return void
	 */
	public function register_request($id, $text, $href='', $page='', $attributes = null, $panel=''){
		self::$counter++;
		self::$registered_requests[self::$counter] = compact( 'id', 'text', 'href', 'page', 'attributes', 'panel');
	}
	
	
	/**
	 * Setup all requests hooked to this screen
	 * 
	 * @hook current_screen
	 */
	public function setup_current_screen_meta_links( $screen ){	
		foreach(self::$registered_requests as $request_index => $args){
			$this->process_request($request_index, $args['id'], $args['text'], $args['href'], $args['page'], $args['attributes'], $args['panel']);
		}
	}
	
	
	/**
	 * process_request
	 * 
	 * @todo sanitize user input!
	 * @todo maybe don't use compact()
	 */
	private function process_request($request_index, $id, $text, $href, $page='', $attributes = null, $panel=''){
		
		if ( ! $this->show_on_this_screen($id, $page) ){
			return;
		}
				
		if ( is_null($attributes) )
			$attributes = array();
		if ( !is_array($attributes) )
			$attributes = array($attributes);
		
		if ($panel)
			$link = compact('id', 'text' );
		else
			$link = compact('id', 'text', 'href' );
		
		$link = array_merge($link, $attributes);
		
		if ( empty($link['class']) )
			$link['class'] = '';
		$link['class'] = 'show-settings custom-screen-meta-link ' . $link['class'];
		
		if ($panel)
			$link['class'] = 'button ' . $link['class'];

		self::$links[$request_index] = $link;
	
		
		if ($panel){
			ob_start();
				call_user_func($panel);
			$panel =	ob_get_clean();
			
			self::$panels[$request_index] = $panel;
		}
		
	}
	
	
	
	
	
	/**
	 * Test if registered link should be displayed on this screen
	 * 
	 * @param $id
	 * @param string|string[] $pages - list of hook_suffix or screen id to show screen-meta-link on
	 * 
	 * @return boolean
	 */
	private function show_on_this_screen($id, $pages){
		global $hook_suffix;

		if ( ! is_array( $pages ) ){
			$pages = [ $pages ];
		}
		
		$screen = convert_to_screen($hook_suffix);
		$add_to_current_page = false;
		foreach( $pages as $k => $page ){
			if ( ! $page )//ignore empty string. otherwise - will return same as '*' (on screen->id test)
				continue;
			
			$page_as_screen = convert_to_screen($page);
			if ( $page == $hook_suffix || $page_as_screen->id == $screen->id || $page == '*' ){
				$add_to_current_page = true;
				break;
			}
		}
		
		return $add_to_current_page;
	}
	
	
	/**
	 * DISABLED/UNUSED
	 * 
	 * Enqueueing does not work.
	 * Instead we add our script and data inline using append_meta_links().
	 * 
	 * @see append_meta_links()
	 */
	public function enqueue_scripts(){
		if ( empty(self::$links) ){
			return;
		}

		wp_enqueue_script( 'screen_meta_links', SML_URL . 'js/screen-meta-links.js', 'jquery' );
		wp_localize_script( 'screen_meta_links', 'sml', [ 'links' => self::$links, 'panels' => self::$panels ] );
	}

	/**
	 * Output the JS that appends the custom meta links to the page.
	 * Hooked on 'admin_notices' action.
	 * 
	 * This is very much a render-blocking script
	 * Runs here before the wp script that inits screen-meta-links runs
	 * (enqueueing external script w/ localized variables failed to add listener to button)
	 *
	 * @access public
	 * @return void
	 */
	public function append_meta_links(){
		
		if ( empty(self::$links) ){
			return;
		}
		
		// ---------------------[meta-screen-links script]-----------------------
		?>
		<script type="text/javascript">
			(function($, links, panels){
				
				var container = $('#screen-meta-links');
				var container_panels = $('#screen-meta');
				var linkTag; //if we have a panel it's a button - otherwise it's a link anchor
				
				if (!container.length){
					container = $('<div />')
						.attr({
							'id' : 'screen-meta-links'
						});
					container.insertAfter('#screen-meta');
				}
				
				$.each( links, function( i, element ) {
					
					if (panels[i]){
						container_panels.append(
							$('<div />')
								.attr({
									'id' : element.id + '-wrap',
									'class' : 'hidden',
									'tabindex' : '-1',
									'aria-label' : element.text + ' Tab'
								})
								.html(panels[i])
						);
						
						linkTag = '<button />'; 
					}else{
						linkTag = '<a />';
					}
					
					container.append(
						$('<div />')
							.attr({
								'id' : element.id + '-link-wrap',
								'class' : 'hide-if-no-js screen-meta-toggle custom-screen-meta-link-wrap'
							})
							.append( $( linkTag, element) )
					);
				});
			
			})(
				jQuery,
				<?php echo json_encode(self::$links); ?>,
				<?php echo json_encode(self::$panels); ?>  );
		</script>
		
		<?php 
	}
	
	
	/**
	 * DISABLED/UNUSED
	 * 
	 * Output the CSS code for custom screen meta links. Required because WP only
	 * has styles for specific meta links (by #id), not meta links in general.
	 * 
	 * Callback for 'admin_print_styles'.
	 * 
	 * This function is unused because we cannot reliably obtain URL of css file.
	 * Instead we add inline style using print_inline_style().
	 * 
	 * @see print_inline_style
	 * 
	 * @access public 
	 * @return void
	 */
	function add_link_styles(){
		//Don't output the CSS if there are no custom meta links for this page.
		if ( empty(self::$links) )
			return;
		
		wp_enqueue_style( 'screen-meta-links' , SML_URL . 'css/screen_meta_links.css');
	}
	
	
	private function json_encode($data){
		return json_encode($data);
	}
	
}


endif;



/**
 * DEMO
 *
 * A separate editable demo file
 * For debugging purposes
 * 
 */
if( defined('DEMO_SCREEN_META_LINKS') ){
	include plugin_dir_path( __FILE__ ) . 'demo/demo_screen_meta_links.php' ;
}

if ( ! function_exists( 'wph_add_screen_meta_panel' ) ):
/**
 * Add a new link+panel to the screen meta area.
 *
 * This function can be called on current_screen hook (priority < 100) or earlier (admin_init is fine)
 * Plugin begins heavy-lifting (filtering and processing) on current_screen priority 100
 * 
 * @param string 		$id - Link ID. Should be unique and a valid value for a HTML ID attribute.
 * @param string 		$text - Link text.
 * @param string 		$href - Optional. Link URL to be used if no panel is provided
 * @param string|array 	$page - The page(s) where you want to add the link.
 * @param array 		$attributes - Optional. Additional attributes for the link tag. Add 'aria-controls' => "{$id}-wrap" to toggle panel 
 * @param callback 		$panel - Optional. Callback should print out screen-meta panel contents
 * @return void
 * 
 * @todo Remove $href parameter and functionailty
 */
function wph_add_screen_meta_panel($id, $text, $href = '', $page, $attributes = null, $panel=''){
	
	static $sml_instance = null;
	if ( null === $sml_instance){
		$sml_instance = Screen_Meta_Links::get_instance();
	}
	
	$sml_instance->register_request($id, $text, $href, $page, $attributes, $panel);
	
}
endif;