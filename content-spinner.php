<?php
/**
* Plugin Name: Content Spinner
* Plugin URI: https://github.com/Link7/automatic-content-spinner
* Description: A plugin to display spun text contents
* Version: 0.7.0
* Author: Link7
* Author URI: http://github.com/Link7
* License: GPL3
* License URI: http://www.gnu.org/licenses/gpl.html
*/
if ( ! defined( 'ABSPATH' ) ) exit('No direct script access allowed'); // Exit if accessed directly

include_once dirname( __FILE__ ) . '/classes/class-cs-constants.php';
if (!class_exists('ContentSpinner'))
{
	class ContentSpinner
	{
		public $notice = NULL;
        public $notice_iserror = FALSE;
        public $options = array();
        public $spinmethod = Cs_Constants::SPIN_METHOD;
	    public $spinpost = Cs_Constants::SPIN_POST;
	    public $opening_construct = Cs_Constants::OPENING_CONSTRUCT;
	    public $closing_construct = Cs_Constants::CLOSING_CONSTRUCT;
	    public $separator = Cs_Constants::SEPARATOR;
	    public $spinoption = Cs_Constants::SPIN_OPTION;
	    public $spin_titles = Cs_Constants::SPIN_TITLES;

	    public $post_titles = array();
	    public $menu_slug = NULL;

		public function __construct() 
		{	
			if ( ! is_admin() )
			{
				$this->options = get_option('cs_options');
				
				if (!empty($this->options)){
					$this->spinmethod = $this->options['spinmethod'];
			        $this->spinpost = $this->options['spinpost'];
			        $this->opening_construct = $this->options['opening_construct'];
			        $this->closing_construct = $this->options['closing_construct'];
			        $this->separator = $this->options['separator'];
			        $this->spinoption = $this->options['spinoption'];
			        $this->spin_titles = $this->options['spin_titles'];
				}
			
				add_filter('the_content', array(&$this, 'spin_contents'));
				add_filter( 'wp_nav_menu_objects', array($this, 'custom_nav_items'), 98, 2 );
				add_filter('widget_text', array($this, 'spin_contents'));
				add_filter('the_title', array(&$this, 'spin_title'));
				add_filter('wp_title', array(&$this, 'spin_title'));
			}
			else
			{
				add_action('admin_menu', array($this, 'display_menu') );

				register_uninstall_hook(__FILE__, array('ContentSpinner','cs_uninstall_plugin'));
			}

			$this->includes();
	  	}

	  	/**
		* cs_uninstall_plugin
		* 
		* completely removes the plugin installation
		*
		* @access public 
		* @return void
		*/
	  	public function cs_uninstall_plugin(){
	  		delete_option('cs_options');
	  	}

	  	/**
		* display_menu
		* 
		* add's the menu to the admin / dashboard
		*
		* @access public 
		* @return NONE
		*/
	  	public function display_menu()
		{
			$this->menu_slug = add_options_page( 'Automatic Content Spinner', 'Automatic Content Spinner', 'manage_options', dirname(__FILE__) . '/form.php');
			$this->menu_slug = str_replace('settings_page_', '', $this->menu_slug) . '.php';
			
			// load on checking of $_POSTs when on this page
			add_action('load-' . $this->menu_slug, array($this,'check_posts'));
		}

		/**
		* check_posts
		* 
		* used in the dashboard, checks if there's an update and save into DB - wp_options
		*
		* @access public 
		* @return NONE
		*/
		public function check_posts()
		{	
			if(isset($_POST['cs_hidden']) && $_POST['cs_hidden'] == 'Y') {  
				unset($_POST['cs_hidden']);
				unset($_POST['submit']);
				$_POST['spin_titles'] = isset($_POST['spin_titles']) ? $_POST['spin_titles'] : '0';
				update_option('cs_options', $_POST);
				wp_redirect(admin_url('options-general.php?page=' . $this->menu_slug . '&settings-updated=true'));
			}

			if ( ! empty( $_GET['settings-updated'] ) ) 
			{
				$this->notice = 'Settings saved.';
				add_action('admin_notices', array($this, 'display_notification'));
			}
		}

		/**
		* spin_title
		* 
		* spins all the titles
		*
		* @access public 
		* @return NONE
		*/
		public function spin_title($title){
			
			if (!$this->spin_titles)
				return $title;
		
			if (current_filter() == 'wp_title')
				$title = rtrim($title, '| ');
			
			$spin_title = $this->is_spin();


		    $spinoption = $this->spinoption;
		    // spin only if required
		    $new_title = $title;
		    if ($spin_title){
		    $id = md5(get_the_ID().$title);
		    	if (!isset($this->post_titles[$id]))
		    	{
		    		if ($spinoption == 'flat')
		    			$new_title = Spinner::flat($new_title, $this->spinmethod, FALSE, $this->opening_construct, $this->closing_construct, $this->separator);
		    		else
		    			$new_title = Spinner::$spinoption($new_title, $this->spinmethod, $this->opening_construct, $this->closing_construct, $this->separator);

		    		$this->post_titles[$id] = $new_title;
		    }
		    	else
		    	{
		    		$new_title = $this->post_titles[$id];
		    	}
		    }
		    if (current_filter() == 'wp_title')
		    	$new_title = $new_title . ' | ';
			return $new_title;
		}

		/**
		* spin_contents
		* 
		* spins post / page contents
		*
		* @access public 
		* @return NONE
		*/
		public function spin_contents($content, $addseed = NULL)
		{	
		    $spin_content = $this->is_spin();
		    $spinoption = $this->spinoption;
		    // spin only if required
		    if ($spin_content){
		    	if ($spinoption == 'flat')
		    		$content = Spinner::flat($content, $this->spinmethod, FALSE, $this->opening_construct, $this->closing_construct, $this->separator, $addseed);
		    	else
		    		$content = Spinner::$spinoption($content, $this->spinmethod, $this->opening_construct, $this->closing_construct, $this->separator, $addseed);
		   
		    }
			return $content;
		}

		public function custom_nav_items($items, $args)
		{
			global $post;
			if (!is_null($post))
				$post->post_content = $this->spin_contents($post->post_content);	

			return $items;
		}
			
		public function is_spin(){
			 $spin_content = FALSE;

			if (isset($GLOBALS['post']))
			{
		    if ($GLOBALS['post']->post_type == $this->spinpost)
		    	$spin_content = TRUE;
		    if ($this->spinpost == 'both')
		    	$spin_content = TRUE;
			}
		    
		   
		    return $spin_content;
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 *
		 * @access public
		 * @return void
		 */
		private function includes()
		{
			if ( is_admin() )
				$this->admin_includes();
			if ( ! is_admin() )
				$this->frontend_includes();
		}

		/**
		 * Include required admin files.
		 *
		 * @access public
		 * @return void
		 */
		private function admin_includes()
		{
			// do admin includes here
		}

		/**
		 * Include required frontend files.
		 *
		 * @access public
		 * @return void
		 */
		private function frontend_includes()
		{
			// do site includes here
			include_once('classes/spinner.php');
		}

		public function display_notification()
		{	
			if ($this->notice_iserror) {
				echo '<div id="message" class="error">';
			}
			else {
				echo '<div id="message" class="updated fade">';
			}

			echo '<p><strong>' . $this->notice . '</strong></p></div>';
		}   

	}
}


new ContentSpinner;
