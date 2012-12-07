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

  /** Print Scripts?
   *  @var
   */
  private $print_scripts = false;
  private $pjax_delim = "@@@PJAXBREAK@@@";

  /** Hook WordPress
  * @return void
  */
  public function __construct(){

    add_action('init', array($this, 'register_scripts'), 0);
    add_action('wp_footer', array($this, 'print_scripts'), 20);

    add_action('get_header', array($this, 'test_header'), 0);
    add_action('get_footer', array($this, 'test_footer'), 0);
  } 

  /* Register scripts for our plugin
   * @return void
   */
  public function register_scripts(){
    wp_register_script('jquery-pjax', plugins_url('assets/js/jquery.pjax.js', __FILE__), array('jquery'), '0.0.1', true);
    wp_register_script('site-pjax', plugins_url('assets/js/site-pjax.js', __FILE__), array('jquery-pjax'), '0.0.1', true);
  }

  /* Print out our scripts
   * @return void
   */
  public function print_scripts(){
    wp_print_scripts('site-pjax'); 
  }

  /**
   * Is this a PJAX (pushState + AJAX) request for a subtle page data change?
   * @return bool
   */
  public function is_pjax_request() {
      return (isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true');
  }

  public function test_header() {

    if($this->is_pjax_request()) {
      ob_start(array($this, 'trim_page'));
    }
  }

  public function test_footer() {

    if($this->is_pjax_request()) {
      echo $this->pjax_delim;
    }
  }

  public function trim_page($buffer){

    $buffer = preg_replace('/<(body|BODY)(.+)>/', "$0$this->pjax_delim", $buffer);
    $buffer = explode($this->pjax_delim, $buffer, -1);

  //  return var_dump($page);
    return $buffer[1];
  }

}

new WP_pjax();
