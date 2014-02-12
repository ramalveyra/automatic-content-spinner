<?php

/**
 * Tests to test that that testing framework is testing tests. Meta, 
 *
 * @package wordpress-plugins-tests
 */
class WP_Test_CS_Admin extends WP_UnitTestCase 
{
	/* A reference to the plugin */
 	private $cs;
 	private $current_user;

	/* Set and initiate the plugins here */
 	function setUp() 
 	{
		parent::setUp();
 		$this->cs = new ContentSpinner;
 		$this->current_user = get_current_user_id();
 	}
 
 	function tearDown() 
 	{
 		parent::tearDown();
 	}

	/**
	 * If these tests are being run on Travis CI, verify that the version of
	 * WordPress installed is the version that we requested.
	 *
	 * @requires PHP 5.3
	 */
	function test_wp_version() 
	{

		if ( !getenv( 'TRAVIS' ) )
			$this->markTestSkipped( 'Test skipped since Travis CI was not detected.' );

		$requested_version = getenv( 'WP_VERSION' ) . '-src';

		// The "master" version requires special handling.
		if ( $requested_version == 'master-src' ) 
		{
			$file = file_get_contents( 'https://raw.github.com/tierra/wordpress/master/src/wp-includes/version.php' );
			preg_match( '#\$wp_version = \'([^\']+)\';#', $file, $matches );
			$requested_version = $matches[1];
		}

		$this->assertEquals( get_bloginfo( 'version' ), $requested_version );

	}

	/**
	 * Ensure that the plugin has been installed and activated.
	 */
	function test_plugin_activated() 
	{
		$this->assertTrue( is_plugin_active( CS_PLUGIN_TO_TEST ) );
	}

	/**
 	* Verifies that the plugin isn't null and was properly retrieved.
 	 */
 	function test_plugin_init() 
 	{
 		$this->assertTrue(class_exists('Cs_Constants'));
 		$this->assertFalse( null == $this->cs );
 	}

 	
 	// The plugin core functionality tests 
 	// Add tests here for the plugins core functionalities

 	/**
 	 * Test if the menu was added
 	 */
 	function test_plugin_menu_added()
 	{
 		$this->set_admin_user();

 		$this->cs->display_menu();

 		$this->assertNotNull($this->cs->menu_slug);
 		$this->assertEquals(CS_PLUGIN_FOLDER . '/form.php', str_replace('admin_page_', '', $this->cs->menu_slug));

 		$this->set_default_user();
 	}

 	/**
 	 * sets the current user as the admin / temporarily overrides the current user
 	 */
 	private function set_admin_user()
 	{
 		wp_set_current_user( $this->factory->user->create( array( 'role' => 'administrator' ) ) );
 	}

 	/**
 	 * sets the current user back to default
 	 */
 	private function set_default_user()
 	{
 		wp_set_current_user( $this->current_user );
 	}

 	// The plugin core functionality tests (FRONT END - WEBSITE)

 	/**
 	 * tests wheter the actual spinner class is existing
 	 */
 	function test_spinner_class()
 	{
 		$this->assertTrue(file_exists(CS_PLUGIN_PATH . '/classes/spinner.php') );
 		$this->assertTrue(class_exists('Spinner'));
 	}

 	/*
 	* Make sure it only spins when needed
 	*	@depends test_plugin_init
 	*/
 	function test_is_spin()
 	{

 		//static $spinposts = array('post' => 'post', 'page' => 'page', 'both' => 'both');
 		$post1 = $this->create_dummy_post('testing', 'testing content', 'post');
 		$post2 = $this->create_dummy_post('testing', 'testing content', 'page');
 		$post3 = $this->create_dummy_post('testing', 'testing content', 'both');

 		$dummy_posts = array($post1, $post2, $post3);

 		foreach (Cs_Constants::$spinposts as $spinpost)
 		{
 			foreach ($dummy_posts as $dummy_post)
 			{	
 				$GLOBALS['post'] = $dummy_post;
 				$this->cs->spinpost = $spinpost;
 				if ($dummy_post->post_type == $spinpost || $spinpost == 'both')
 					$this->assertTrue($this->cs->is_spin());
 				else
 					$this->assertFalse($this->cs->is_spin());
 			}
 	
 		}
 	}

 	/*
 	*	creates a dummy post / page
 	*/
 	private function create_dummy_post($title, $content, $post_type)
 	{
 		$id = $this->factory->post->create(array('post_type' => $post_type, 'post_title' => $title, 'post_content' => $content));
 		$post = get_post($id);
		return $post;
 	}


}