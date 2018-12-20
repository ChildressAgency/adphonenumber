<?php

if(!defined('ABSPATH')){ exit; }

abstract class APN_Meta_Box{
  public static function add(){
    global $post;
    if(get_post_meta($post->ID, '_wp_page_template', true) == 'template-landingpage.php'){
      add_meta_box(
        'apn_ad_phone_number_meta_box',
        'Ad Phone Number',
        [self::class, 'html'],
        'page'
      );
    }
  }

  public static function save($post_id){
    if(!wp_verify_nonce($_POST['apn_ad_phone_number_nonce'], basename(__FILE__))){
      return $post_id;
    }

    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
      return $post_id;
    }

    if($_POST['post_type'] === 'page'){
      if(!current_user_can('edit_page', $post_id)){
        return $post_id;
      }
      elseif(!current_user_can('edit_post', $post_id)){
        return $post_id;
      }
    }

    $old_value = get_post_meta($post_id, 'apn_ad_phone_number', true);
    $new_value = sanitize_text_field($_POST['apn_ad_phone_number']);

    if($new_value && $new_value !== $old_value){
      update_post_meta($post_id, 'apn_ad_phone_number', $new_value);
    }
    elseif($old_value && $new_value === ''){
      delete_post_meta($post_id, 'apn_ad_phone_number', $old_value);
    }
  }

  public static function html(){
    global $post;
    $meta = get_post_meta($post->ID, 'apn_ad_phone_number', true);

    echo '<input type="hidden" name="apn_ad_phone_number_nonce" value="' . wp_create_nonce(basename(__FILE__)) . '" />';

    echo '<p><label for="apn_ad_phone_number">Alternate Phone Number</label><br />';
    echo '<input type="text" name="apn_ad_phone_number" id="apn_ad_phone_number" class="regular-text" value="' . esc_html($meta) . '" />';
    echo '</p>';
  }
}