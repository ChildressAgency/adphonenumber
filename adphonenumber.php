<?php
/*
  Plugin Name: Advertisement Phone Number
  Description: Allows you to set a specific phone number for an advertisement landing page. The phone number will replace all default phone numbers that use the href="tel:" attribute for the rest of their visit and until cookie expires. The advertisement landing page requires using a page template called template-landingpage.php
  Author: The Childress Agency
  Author URI: https://childressagency.com
  Version: 1.0
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

if(!class_exists('Ad_Phone_Number')){

  class Ad_Phone_Number{
    private $phone_number;

    public function __construct(){
      add_action('init', array($this, 'load_textdomain'));
      add_action('init', array($this, 'get_phone_number'));
      add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

      //$this->phone_number = $this->get_phone_number();

      if(!is_admin()){
        add_action('wp', array($this, 'set_adphone_cookie'), 10);        
      }

      if(is_admin()){
        add_action('load-post.php', array('APN_Meta_Box', 'init'));
        add_action('load-post-new.php', array('APN_Meta_Box', 'init'));

        $apn_options_page = new APN_Options_Page();
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

      wp_localize_script('apn-script', 'adPhone', $this->phone_number);
    }

    public function get_phone_number(){
      //global $wp_query;
      //$page_id = $wp_query->post->ID;

      if($this->use_url_parameter()){
        $phone = get_option('ad_phone_number_url');
      }
      elseif(isset($_COOKIE['apn_ad_phone'])){
        $phone = $_COOKIE['apn_ad_phone'];
      }
     // elseif(get_post_meta($page_id, 'apn_landing_page', true) && (get_post_meta($page_id, 'apn_landing_page', true) == 1) && (get_post_meta($page_id, 'apn_ad_phone_number', true) != '')){
      //  $phone = get_post_meta($page_id, 'apn_ad_phone_number', true);
      //}
      else{
        $phone = get_option('default_phone_number');
      }

      //return $phone;
      $this->phone_number = $phone;
    }

    private function use_url_parameter(){
      if(get_option('use_url_parameter') == 1){
        $url_parameter = get_option('url_parameter');
        $url_parameter_value = get_option('url_parameter_value');
        if(isset($_GET[$url_parameter]) && $_GET[$url_parameter] == $url_parameter_value){
          return true;
        }
      }

      return false;
    }

    public function set_adphone_cookie(){
      //global $wp_query;
      //if((get_post_meta($wp_query->post->ID, 'apn_landing_page', true) == 1) && (get_post_meta($wp_query->post->ID, 'apn_ad_phone_number', true) != '')){
       // $ad_phone = get_post_meta($wp_query->post->ID, 'apn_ad_phone_number', true);

        // 86400 = 1 day
        //$num_days = get_option('cookie_lifespan');
        if($this->use_url_parameter()){
          setcookie('apn_ad_phone', $this->phone_number, time() + (86400 * 30), COOKIEPATH, COOKIE_DOMAIN);
        }
      //}
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