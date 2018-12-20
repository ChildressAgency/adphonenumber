<?php
/*
  Plugin Name: Advertisement Phone Number
  Description: Allows you to set a specific phone number for an advertisement landing page. The phone number will replace all default phone numbers that use the href="tel:" attribute. Requires ACF plugin.
  Author: The Childress Agency
  Author URI: https://childressagency.com
  Version: 1.0
  Text Domain: adphonenumber
*/

if(!defined('ABSPATH')){ exit; }

define('APN_PLUGIN_DIR', dirname(__FILE__));
define('APN_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once APN_PLUGIN_DIR . '/includes/class-apn_meta_box.php';
require_once APN_PLUGIN_DIR . '/includes/class-apn_options_page.php';

if(!class_exists('Ad_Phone_Number')){

  class Ad_Phone_Number{
    private $phone;
    
    public function __construct(){
      add_action('init', array($this, 'load_textdomain'));
      add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

      add_action('add_meta_boxes', array('APN_Meta_Box', 'add'));
      add_action('save_post', array('APN_Meta_Box', 'save'));

      if(is_admin()){
        $apn_options_page = new APN_Options_Page();
      }
    }

    public function load_textdomain(){
      load_plugin_textdomain('Ad_Phone_Number', false, basename(dirname(__FILE__)) . '/languages');
    }

    public function enqueue_scripts(){
      wp_enqueue_script(
        'apn-script',
        APN_PLUGIN_URL . 'js/apn-script.js',
        array('jquery'),
        null,
        true
      );

      wp_localize_script('apn-script', 'adPhone', $this->phone);
    }

    public function get_ad_phone_number(){
      
    }
  }

  new Ad_Phone_Number;
}