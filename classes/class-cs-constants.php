<?php 
/**
 * Cs_Constants class.
 *
 * @class                 Cs_Constants
 * @version                0.0.1
 * @package                content-spinner/classes
 * @category        		Class
 */
class Cs_Constants {
	
    const SPIN_METHOD = 'domainpage';
    const SPIN_POST = 'both';
    
    const OPENING_CONSTRUCT = '{'; 
    const CLOSING_CONSTRUCT = '}'; 
    const SEPARATOR = '|'; 

    const SPIN_OPTION = 'detect';

    const SPIN_TITLES = FALSE;

	static $spinmethods = array(
        'domainpage' => 'domain page (default)',
        'every second' => 'every second',
        'every minute' => 'every minute',
        'hourly' => 'hourly',
        'daily' => 'daily',
        'weekly' => 'weekly',
        'monthly' => 'monthly',
        'annually' => 'annually',
        'false' => 'always spin'
    );

	static $spinoptions = array('flat' => 'flat', 'nested' => 'nested', 'detect' => 'detect');
	static $spinposts = array('post' => 'post', 'page' => 'page', 'both' => 'both');
    static $spintags = array(
        "opening_construct" => array('{', '[', '{{'),
        "closing_construct" => array('}', ']', '}}'),
        "separator" => array('|', '||', '_', '__', '-'),
        );
}



