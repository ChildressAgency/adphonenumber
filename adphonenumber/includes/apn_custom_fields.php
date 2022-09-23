<?php
if(!defined('ABSPATH')){ exit; }

function load_apn_acf_field_group(){

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_6320c795a28cd',
	'title' => 'APN Settings Fields',
	'fields' => array(
		array(
			'key' => 'field_6320c7a56de21',
			'label' => 'Default Phone Number',
			'name' => 'default_phone_number',
			'type' => 'text',
			'instructions' => 'Enter the default phone number for the website to use if no other phone numbers can be used.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_6320c7da6de22',
			'label' => 'Use URL Parameter?',
			'name' => 'use_url_parameter',
			'type' => 'true_false',
			'instructions' => 'If this is set to false, any URL parameter settings below are ignored.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 1,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_6320c84f6de24',
			'label' => 'Cookie Lifespan',
			'name' => 'cookie_lifespan',
			'type' => 'number',
			'instructions' => 'Enter how long in days the cookie should save the phone number.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 30,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'min' => '',
			'max' => '',
			'step' => '',
		),
		array(
			'key' => 'field_6320c7f86de23',
			'label' => 'URL Parameter',
			'name' => 'url_parameter',
			'type' => 'text',
			'instructions' => 'Enter the URL parameter name to look for.	This same parameter should be used on all ads with a different value for each ad - the value then determines which phone number to use.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_6320c8926de25',
			'label' => 'Phone Numbers',
			'name' => 'phone_numbers',
			'type' => 'repeater',
			'instructions' => 'Enter any number of values for the above URL parameter, then assign a phone number to that value.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => 0,
			'max' => 0,
			'layout' => 'table',
			'button_label' => 'Add Phone Number',
			'sub_fields' => array(
				array(
					'key' => 'field_6320c89c6de26',
					'label' => 'URL Parameter Value',
					'name' => 'url_parameter_value',
					'type' => 'text',
					'instructions' => 'Enter a value for the URL parameter.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_6320c8e56de27',
					'label' => 'Phone Number',
					'name' => 'phone_number',
					'type' => 'text',
					'instructions' => 'Enter the phone number to use when this URL parameter value is found.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'apn-settings',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
));

acf_add_local_field_group(array(
	'key' => 'group_632c6cef191c6',
	'title' => 'Childress Logo',
	'fields' => array(
		array(
			'key' => 'field_632c6cf4e0909',
			'label' => '',
			'name' => '',
			'type' => 'message',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '<h2 style="padding-left:0;font-size:22px;font-weight:bold;">Usage:</h2>
<p>There are 3 ways to set a dynamic phone number:</p>
<p>First the plugin will look for a cookie already set, that hasn\'t expired, and will use that phone number. You can set how long a cookie lasts in the field above.</p>
<p>Second, if the "Use URL Parameter" box is checked, it will look for the parameter and parameter values you set. If that parameter exists in the url, it will use the phone number assigned to that parameter.</p>
<p>Third, it will look for the "Is this an Advertisement Landing page?" box to be checked on the page visited then use the phone number entered on that page.</p>
<p>Once the plugin determines it needs to use a dynamic phone number from an ad, and it determines which phone number to use, each <code>&lt;a&gt;</code> element that has <code>href="tel:</code> will be updated with the ad phone number.</p>
<p>You can also use a shortcode in place of hard-coding the <code>&lt;a&gt;</code> element.	The element output by the shortcode will be updated the same way as a regular element would.</p>
<div class="childress-logo" style="margin-top:40px;">
	<a href="https://childressagency.com" target="_blank">
		<img src="https://dev.childressagency.com/testsite/wp-content/plugins/adphonenumber/img/childress_agency_logo.png" alt="Childress Agency Logo" />
	</a>
</div>',
			'new_lines' => '',
			'esc_html' => 0,
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'apn-settings',
			),
		),
	),
	'menu_order' => 99,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
));

endif;
}