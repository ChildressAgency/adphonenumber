<?php

if(!defined('ABSPATH')){ exit; }

if(!class_exists('APN_Meta_Box')){
  abstract class APN_Meta_Box{
    public static function init(){
      add_action('add_meta_boxes_page', array(self::class, 'add'));
      add_action('save_post', array(self::class, 'save'));
    }

    public static function add(){
      add_meta_box(
        'apn_ad_phone_number_meta_box',
        esc_html__('Advertisement Phone Number', 'ad_phone_number'),
        [self::class, 'html']
      );
    }

    public static function save($post_id){
      if(isset($_POST['apn_ad_phone_number_nonce'])){
        if(!wp_verify_nonce($_POST['apn_ad_phone_number_nonce'], basename(__FILE__))){
          return $post_id;
        }
      }

      if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
        return $post_id;
      }

      if(isset($_POST['post_type']) && $_POST['post_type'] === 'page'){
        if(!current_user_can('edit_page', $post_id)){
          return $post_id;
        }
        elseif(!current_user_can('edit_post', $post_id)){
          return $post_id;
        }
      }

      $old_is_landing_page = get_post_meta($post_id, 'apn_landing_page', true);
      
      if(isset($_POST['apn_landing_page'])){
          $new_is_landing_page = $_POST['apn_landing_page'];
      }

      if(isset($new_is_landing_page) && $new_is_landing_page !== $old_is_landing_page){
        update_post_meta($post_id, 'apn_landing_page', '1');
      }
      elseif($old_is_landing_page && !isset($new_is_landing_page)){
        delete_post_meta($post_id, 'apn_landing_page', $old_is_landing_page);
      }

      $old_phone_number = get_post_meta($post_id, 'apn_ad_phone_number', true);
      
      if(isset($_POST['apn_ad_phone_number'])){
          $new_phone_number = sanitize_text_field($_POST['apn_ad_phone_number']);
      }

      if(isset($new_phone_number) && $new_phone_number !== $old_phone_number){
        update_post_meta($post_id, 'apn_ad_phone_number', $new_phone_number);
      }
      elseif($old_phone_number && $new_phone_number === ''){
        delete_post_meta($post_id, 'apn_ad_phone_number', $old_phone_number);
      }
    }

    public static function html(){
      global $post;
      $is_landing_page = get_post_meta($post->ID, 'apn_landing_page', true);
      $phone_number = get_post_meta($post->ID, 'apn_ad_phone_number', true);

      wp_nonce_field(basename(__FILE__), 'apn_ad_phone_number_nonce');

      echo '<p><input type="checkbox" name="apn_landing_page"'. checked($is_landing_page, '1', false) . ' />' . esc_html__('Is this an Advertisement Landing Page?', 'ad_phone_number') . '</p>';
      echo '<p><label for="apn_ad_phone_number">' . esc_html__('Alternate Phone Number', 'ad_phone_number') . '</label><br />';
      echo '<input type="text" name="apn_ad_phone_number" id="apn_ad_phone_number" class="regular-text" value="' . esc_html($phone_number) . '" />';
      echo '</p>';
    }
  }
}