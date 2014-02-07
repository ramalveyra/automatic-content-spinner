<?php
/**
* Plugin Name: Content Spinner
* Plugin URI: https://github.com/Link7/automatic-content-spinner
* Description: A plugin to display spun text contents
* Version: 1.0
* Author: Link7
* Author URI: http://github.com/Link7
* License: GPL3
* License URI: http://www.gnu.org/licenses/gpl.html
*/

include_once dirname( __FILE__ ) . '/classes/class-cs-constants.php';
if (!class_exists('ContentSpinner'))
{
	class ContentSpinner
	{
		public function __construct() 
		{	
			if ( ! is_admin() )
			{
				add_filter('the_content', array(&$this, 'spin_contents'));
			}
			else
			{
				add_action('admin_menu', array($this, 'display_menu') );
				add_action('cs_check_posts', array($this, 'check_posts') );	

				$this->permalink_structure = get_option('permalink_structure');
			}

			$this->includes();
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
			add_options_page( 'Automatic Content Spinner', 'Automatic Content Spinner', 'manage_options', dirname(__FILE__) . '/form.php' );
			do_action('cs_check_posts');
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
			if($_POST['cs_hidden'] == 'Y') {  
				unset($_POST['cs_hidden']);
				unset($_POST['submit']);
				update_option('cs_options', $_POST);
			}
		}

		public function spin_contents($content)
		{	
			$options = get_option('cs_options');
			
		    $spinmethod = Cs_Constants::SPIN_METHOD;
		    $spinpost = Cs_Constants::SPIN_POST;
		    $opening_construct = Cs_Constants::OPENING_CONSTRUCT;
		    $closing_construct = Cs_Constants::CLOSING_CONSTRUCT;
		    $separator = Cs_Constants::SEPARATOR;
		    $spinoption = Cs_Constants::SPIN_OPTION;

		    if (!empty($options)){
		        $spinmethod = $options['spinmethod'];
		        $spinpost = $options['spinpost'];
		        $opening_construct = $options['opening_construct'];
		        $closing_construct = $options['closing_construct'];
		        $separator = $options['separator'];
		        $spinoption = $options['spinoption'];
		    }
		    $spin_content = FALSE;
		    if ($GLOBALS['post']->post_type == $spinpost)
		    	$spin_content = TRUE;
		    if ($spinpost == 'both')
		    	$spin_content = TRUE;
		    
		    // spin only if required
		    if ($spin_content){
		    	if ($spinoption == 'flat')
		    		$content = Spinner::flat($content, $spinmethod, FALSE, $opening_construct, $closing_construct, $separator);
		    	else
			$content = Spinner::$spinoption($content, $spinmethod, $opening_construct, $closing_construct, $separator);
		   
		    }
			
		   
			return $content;
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

	}
}


new ContentSpinner;
