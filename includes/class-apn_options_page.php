<?php
if(!defined('ABSPATH')){ exit; }

if(!class_exists('APN_Options_Page')){

  class APN_Options_Page{
    private $options;

    public function __construct(){
      add_action('admin_menu', array($this, 'add_plugin_page'));
      add_action('admin_init', array($this, 'page_init'));
    }

    public function add_plugin_page(){
      /*add_options_page(
        'Default Phone Number',
        'Phone Number',
        'manage_options',
        'default-phone-number',
        array($this, 'create_admin_page')
      );*/

      add_menu_page(
        'Ad Phone Number',
        'Ad Phone Number',
        'manage_options',
        'default-phone-number',
        array($this, 'create_admin_page'),
        'dashicons-phone',
        81
      );

    }

    public function create_admin_page(){
      $this->options = get_option('default_phone_number'); ?>

      <div class="wrap">
        <h1>Advertising Phone Number and Usage</h1>
        <form method="post" action="options.php">
          <?php
            settings_fields('default-phone-number-group');
            do_settings_sections('default-phone-number');
            submit_button();
          ?>
        </form>
        <h2>Usage:</h2>
        <p>Set the website's default phone number here. All links with the tel: href attribute will be replaced with either the landing page phone number or this default phone number.</p>
        <p>In your theme you can use the [ad_phone_number] shortcode to display a linked phone number.</p>
      </div>

      <?php
    }

    public function page_init(){
      register_setting(
        'default-phone-number-group',
        'default_phone_number',
        array($this, 'sanitize')
      );

      add_settings_section(
        'default_phone_number_section_id',
        '',
        array($this, 'print_section_info'),
        'default-phone-number'
      );

      add_settings_field(
        'default_phone_number',
        'Default Phone Number',
        array($this, 'default_phone_number_callback'),
        'default-phone-number',
        'default_phone_number_section_id'
      );
    }

    public function sanitize($input){
      //$new_input = array();
      //if(isset($input['default_phone_number'])){
      //  $new_input['default_phone_number'] = sanitize_text_field($input['default_phone_number']);
      //}
      $new_input = sanitize_text_field($input);

      return $new_input;
    }

    public function print_section_info(){
      print 'Enter the default Phone Number';
    }

    public function default_phone_number_callback(){
      printf(
        '<input type="text" id="default_phone_number" name="default_phone_number" value="%s" />',
        isset($this->options) ? esc_attr($this->options) : ''
      );
    }
  }
}