<?php
/*
  Plugin Name: Dynamic Phone Numbers
  Description: Allows you to dynamically change displayed phone numbers to match phone numbers used in advertisements for better phone call conversion tracking.
  Author: Childress Agency
  Author URI: https://childressagency.com
  Version: 2.0
  Text Domain: ad_phone_number
*/

if(!defined('ABSPATH')){ exit; } // can't access file directly

// define plugin paths
define('APN_PLUGIN_DIR', dirname(__FILE__));
define('APN_PLUGIN_URL', plugin_dir_url(__FILE__));

/*
  Include classes to add "Alternate Phone Number" meta box
  and options page for setting default phone number.
*/
require_once APN_PLUGIN_DIR . '/includes/class-apn_meta_box.php';
require_once APN_PLUGIN_DIR . '/includes/class-apn_options_page.php';
require_once APN_PLUGIN_DIR . '/includes/apn_custom_fields.php';

if(!class_exists('Ad_Phone_Number')){

  class Ad_Phone_Number{
    private $phone_number;
    private $use_acf = false;
    private $acf_option = '';

    public function __construct(){
      add_action('init', array($this, 'load_textdomain'));
      add_action('init', array($this, 'set_phone_number'));
      add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

      add_action('plugins_loaded', array($this, 'check_acf_available'));

      if(is_admin()){
        add_action('load-post.php', array('APN_Meta_Box', 'init'));
        add_action('load-post-new.php', array('APN_Meta_Box', 'init'));

        add_action('plugins_loaded', array($this, 'load_apn_options_page'));
        add_action('plugins_loaded', 'load_apn_acf_field_group');
      }

      add_shortcode('apn_phone_number_link', array($this, 'get_phone_number_link'));

      add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_settings_link'));
    }

    public function load_textdomain(){
      load_plugin_textdomain('ad_phone_number', false, basename(dirname(__FILE__)) . '/languages');
    }

    public function enqueue_scripts(){
      wp_enqueue_script(
        'apn-script',
        APN_PLUGIN_URL . 'js/apn-script.js',
        array('jquery'),
        null,
        true
      );

      wp_localize_script(
        'apn-script', 
        'adPhone', 
        array(
          'phone_number' => $this->phone_number
        )
      );
    }

    function load_apn_options_page(){
      if(class_exists('acf')){
        add_action('acf/init', array($this, 'add_acf_options_page'));
      }
      else{
        $apn_options_page = new APN_Options_Page();
      }
    }

    function check_acf_available(){
      $this->use_acf = true;
      $this->acf_option = 'options_';
    }

    function add_acf_options_page(){
      acf_add_options_page(array(
        'page_title' => esc_html__('Dynamic Phone Number Settings', 'ad_phone_number'),
        'menu_title' => esc_html__('Dynamic Phone Numbers', 'ad_phone_number'),
        'menu_slug' => 'apn-settings',
        'parent_slug' => '',
        'capability' => 'manage_options',
        'icon_url' => 'dashicons-phone'
      ));
    }

    public function set_phone_number(){
      $url_param_phone_number = $this->get_param_phone_number();

      if($url_param_phone_number !== false){
        $this->phone_number = $url_param_phone_number;
        $this->set_adphone_cookie();
      }
      elseif(isset($_COOKIE['apn_ad_phone'])){
        $this->phone_number = $_COOKIE['apn_ad_phone'];
      }
      else{
        $this->phone_number = $this->apn_get_option('default_phone_number');
      }
    }

    public function get_param_phone_number(){
      $phone_number = '';

      if($this->use_url_parameter() == true){
        $param_to_look_for = $this->get_param_to_look_for();

        if(isset($_GET[$param_to_look_for])){
          $possible_param_values = $this->get_possible_param_values();
          $phone_number = '';

          //foreach($possible_param_values as $i => $param_values){
          //  foreach($param_values as $param_value){
          //    if($_GET[$param_to_look_for] == $param_value['param_value']){
          //      $phone_number = $param_value['phone_number'];
          //    }
          //  }
          //}

          foreach($possible_param_values as $param_value){
            if($_GET[$param_to_look_for] == $param_value['param_value']){
              $phone_number = $param_value['phone_number'];
            }
          }

        }

      }

      if($phone_number !== ''){
        return $phone_number;
      }
      else{
        return false;
      }
    }

    private function get_possible_param_values(){
      $possible_values = array();
      if($this->acf_option == ''){
        $possible_values[0]['param_value'] = $this->apn_get_option('url_parameter_value_1');
        $possible_values[0]['phone_number'] = $this->apn_get_option('ad_phone_number_url_1');

        $possible_values[1]['param_value'] = $this->apn_get_option('url_parameter_value_2');
        $possible_values[1]['phone_number'] = $this->apn_get_option('ad_phone_number_url_2');
      }
      else{
        $phone_numbers_count = $this->apn_get_option('phone_numbers');

        for($i = 0; $i < $phone_numbers_count; $i++){
          $possible_values[$i]['param_value'] = $this->apn_get_option('phone_numbers_' . $i . '_url_parameter_value');
          $possible_values[$i]['phone_number'] = $this->apn_get_option('phone_numbers_' . $i . '_phone_number');
        }
      }

      return $possible_values;
    }

    private function use_url_parameter(){
      if($this->apn_get_option('use_url_parameter') == 1){
        return true;
      }

      return false;
    }

    private function get_param_to_look_for(){
      return $this->apn_get_option('url_parameter');
    }

    private function apn_get_option($option_name){
      return get_option($this->acf_option . $option_name);
    }

    public function set_adphone_cookie(){
      // 86400 = 1 day
      $num_days = $this->apn_get_option('cookie_lifespan');
      if($this->use_url_parameter()){
        setcookie('apn_ad_phone', $this->phone_number, time() + (86400 * (int)$num_days), COOKIEPATH, COOKIE_DOMAIN);
      }
    }

    public function get_phone_number_link($atts){
      $atts = shortcode_atts(array(
        'class' => '',
      ), $atts, 'apn_phone_number_link');

      $class = '';
      if($atts['class'] != ''){
        $class = sprintf(' class="%s"', esc_attr($atts['class']));
      }

      $link = '<a href="tel:' . $this->phone_number . '"' . $class . '>' . $this->phone_number . '</a>';

      return $link;
    }

    public function add_settings_link($links){
      $links[] = '<a href="' . esc_url(get_admin_url(null, 'admin.php?page=apn-settings')) . '">' . esc_html__('Settings', 'ad_phone_number') . '</a>';

      return $links;
    }
  }

  new Ad_Phone_Number;
}