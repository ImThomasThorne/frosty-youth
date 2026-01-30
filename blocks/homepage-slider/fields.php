<?php
/**
 * ACF Field Group for Homepage Slider Block
 *
 * Register this in your functions.php or use ACF's JSON sync feature
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group( array(
		'key' => 'group_homepage_slider',
		'title' => 'Homepage Slider Settings',
		'fields' => array(
			array(
				'key' => 'field_slider_images',
				'label' => 'Slider Images',
				'name' => 'slider_images',
				'type' => 'gallery',
				'instructions' => 'Add multiple images for the slider. Recommended size: 1920x800px',
				'required' => 1,
				'min' => 1,
				'max' => 10,
				'preview_size' => 'medium',
				'insert' => 'append',
				'library' => 'all',
				'min_width' => '',
				'min_height' => '',
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '',
				'mime_types' => 'jpg,jpeg,png,webp',
			),
			array(
				'key' => 'field_slider_title',
				'label' => 'Title',
				'name' => 'slider_title',
				'type' => 'text',
				'instructions' => 'Main title displayed over the slider',
				'required' => 0,
				'default_value' => 'Welcome to Cumbria Youth Alliance',
				'placeholder' => 'Enter your title',
				'maxlength' => 100,
			),
			array(
				'key' => 'field_slider_text',
				'label' => 'Description Text',
				'name' => 'slider_text',
				'type' => 'textarea',
				'instructions' => 'Description text displayed below the title',
				'required' => 0,
				'default_value' => '',
				'placeholder' => 'Enter your description',
				'maxlength' => 250,
				'rows' => 3,
			),
			array(
				'key' => 'field_button_text',
				'label' => 'Button Text',
				'name' => 'button_text',
				'type' => 'text',
				'instructions' => 'Text for the call-to-action button',
				'required' => 0,
				'default_value' => 'Learn More',
				'placeholder' => 'Enter button text',
				'maxlength' => 50,
			),
			array(
				'key' => 'field_button_link',
				'label' => 'Button Link',
				'name' => 'button_link',
				'type' => 'link',
				'instructions' => 'URL for the button to link to',
				'required' => 0,
				'return_format' => 'url',
			),
			array(
				'key' => 'field_autoplay',
				'label' => 'Enable Autoplay',
				'name' => 'autoplay',
				'type' => 'true_false',
				'instructions' => 'Automatically transition between slides',
				'required' => 0,
				'default_value' => 1,
				'ui' => 1,
			),
			array(
				'key' => 'field_autoplay_speed',
				'label' => 'Autoplay Speed',
				'name' => 'autoplay_speed',
				'type' => 'number',
				'instructions' => 'Time between slides in milliseconds (e.g., 5000 = 5 seconds)',
				'required' => 0,
				'default_value' => 5000,
				'min' => 1000,
				'max' => 10000,
				'step' => 500,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_autoplay',
							'operator' => '==',
							'value' => '1',
						),
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'block',
					'operator' => '==',
					'value' => 'acf/homepage-slider',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'active' => true,
	) );

endif;
