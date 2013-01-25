<?php
/*
Plugin Name: WP PJAX
Plugin URI: http://upthemes.com/plugins/wp-pjax/
Description: WordPress plugin to add pjax
Version: 0.0.1
Author: Matthew Simo 
Author URI: http://matthewsimo.com/
*/

class WP_pjax {

  /**
   * Print Scripts?
   * @var bool
   */
  private $print_scripts = false;

  /** Pjax delimeter
   *  @var
   */
  public $delim = "@@@PJAXBREAK@@@";

  /**
   * Pjax Target
   *
   * Target for click events to watch for pjax triggering.
   * @var string
   */
  public $pjax_target = 'a';

  /**
   * Pjax Container Element
   *
   * Something that works nicely between a $(), passed to pjax js to tell it which container to put the data recieved from the server.
   * @var string
   */
  public $container_el = 'body';

  /**
   * Pjax filter types
   *
   * Array of strings that relates to file type endings pjax will ignore, passed into site-pjax
   * @var array
   */
  public $filters = array(".jpg", ".png", ".pdf");

  /**
   * Pjax Success CB
   *
   * False or Array of js callback functions
   * @var array
   */
  public $success_cb = false;

  /**
   * Hook WordPress
   * @return void
   */
  public function __construct(){
    add_action('init', array($this, 'register_scripts'), 0);
    add_action('wp_footer', array($this, 'print_scripts'), 20);
    add_action('get_header', array($this, 'test_page'), 0);
    add_action('wp_footer', array($this, 'test_footer'), 0);
  } 

  /**
   * Register scripts for our plugin
   * @return void
   */
  public function register_scripts(){
    wp_register_script('jquery-pjax', plugins_url('assets/js/jquery.pjax.js', __FILE__), array('jquery'), '0.0.1', true);
    wp_register_script('site-pjax', plugins_url('assets/js/site-pjax.js', __FILE__), array('jquery-pjax'), '0.0.1', true);
  }

  /**
   * Pass Container element to site-pjax & Print out our scripts
   * @return void
   */
  public function print_scripts(){
    $data = array(
      'pjaxContainer'   =>  $this->container_el,
      'pjaxFilters'     =>  $this->filters,
      'pjaxTarget'      =>  $this->pjax_target
    );

    if($this->success_cb){
      $data['successCB'] = $this->success_cb;
    }

    wp_localize_script('site-pjax', 'pjaxData', $data);
    wp_print_scripts('site-pjax'); 
  }

  /**
   * Is this a PJAX (pushState + AJAX) request for a subtle page data change?
   * @return bool
   */
  public function is_pjax_request() {
    return (isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true');
  }

  public function output_delim(){
    if($this->is_pjax_request() && $this->container_el != 'body')
      echo $this->delim;
  }


  /**
   * Test page for pjax request, start trim page to return only content.
   * @return void
   */
  public function test_page() {

    if($this->is_pjax_request()) {
      ob_start(array($this, 'trim_page'));
    }

  }

  /**
   * Test footer for pjax request, insert delimeter for parsing out content.
   */
  public function test_footer() {

    if($this->container_el == 'body' && $this->is_pjax_request())
      echo $this->delim;

  }

  /**
   * Parse out content for pjax requests.
   * @param $buffer - string
   * @return string
   */
  public function trim_page($buffer){


    // Get the title for the requested page.
    preg_match('/<title>.+<\/title>/ism', $buffer, $titleMatch);
    $pageTitle = $titleMatch[0];

    // Add the 'first' delimeter just after the body tag for default settings
    if($this->container_el == 'body'){
      $buffer = preg_replace('/<body([^>]*)?>/ism', "$0$this->delim", $buffer);
    }

    // Split page by delimeter, return page title + junk in the middle
    $buffer = explode($this->delim, $buffer);
    return $pageTitle . $buffer[1];


  }

}

$wp_pjax = new WP_pjax();
