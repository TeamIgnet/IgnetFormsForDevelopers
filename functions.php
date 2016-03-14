<?php

/**
 * Create simple input
 *
 * @param array $attr
 *
 * @return string With simple <input> tag
 */
function create_simple_input( array $attr ) {

	$input = '<input';
	
	foreach ( $attr as $attribute_key => $attribute_value ) {
		
		// Boolean attributes
		if ( true === $attribute_value ) {
			$input .= ' ' .	$attribute_key;
		}
		
		// Attributes with value
		elseif ( ! empty( $attribute_value ) ) {
			$input .= ' ' . $attribute_key . '="' . $attribute_value . '"';
		}
	}
	$input .= '>';
	
	return $input;
}


/**
 * Create checkbox input
 *
 * @param array $attr
 * @param string $output_value Need for indicate checked checkbox
 *
 * @return string With single <input type="checkbox"> tag
 */
function create_checkbox_input( array $attr, $output_value = '' ) {

	$input = '<input';
	
	foreach ( $attr as $attribute_key => $attribute_value ) {
	
		// Do not add caption attribute
		if ( 'caption' == $attribute_key ) {
			continue;
		}
		
		// Boolean attributes
		if ( true === $attribute_value ) {
			$input .= ' ' .	$attribute_key;
		}
		
		// Attributes with value
		elseif ( ! empty( $attribute_value ) ) {
			$input .= ' ' . $attribute_key . '="' . $attribute_value . '"';
		}
	}
	
	// Checked if it is checkbox and value equals db
	if ( ( is_array( $output_value ) and in_array( $attr['value'], $output_value ) ) or $attr['value'] == $output_value ) {
		$input .= ' checked';
	}
	
	$input .= '>';
	
	// Add caption
	if ( ! empty( $attr['caption'] ) or ! $attr['caption'] ) {
		$input .= ' ' . $attr['caption'];
	}
	
	return $input;
}


/**
 * Create group of radio inputs
 *
 * @param array $attr
 * @param array $options
 *
 * @return string With group of <input type="radio"> tags
 */
function create_radio_input( array $attr, array $options ) {
	
	// Compile options tags with attributes
	foreach ( $options as $option ) {
	
		$input .= '<label class="iffd-field"><input type="radio" name="' . $attr['name'] . '"';
	
		foreach ( $option as $attribute_key => $attribute_value ) {
		
			// Do not add caption attribute
			if ( 'caption' == $attribute_key ) {
				continue;
			}
			
			// Attributes without value
			if ( true === $attribute_value ) {
				$input .= ' ' . $attribute_key;
			}
			
			// Attributes with value
			elseif ( ! empty( $attribute_value ) or ! $attribute_value ) {
				$input .= ' ' . $attribute_key . '="' . $attribute_value . '"';
			}
		}
		
		// Mark as selected if value matches
		$option_is_checked = array_key_exists( 'checked', $option );
		if ( $attr['value'] == $option['value'] and false == $option_is_checked ) {
			$input .= ' ' . 'checked';
		}
		
		$input .= '> ' . $option['caption'] . '</label>';
	}

	return $input;
}


/**
 * Create textarea input
 *
 * @param array $attr
 * @param boolean $tinymce Is optional. Means that input can be TinyMCE editor
 * @param array $tinymce_settings 
 *
 * @return string With simple <textarea> tag, or TinyMCE editor
 */
function create_textarea_input( array $attr, $tinymce = false, $tinymce_settings = '' ) {

	// Create TinyMCE editor
	if ( true == $tinymce ) {
		
		// Set default settings
		$default_settings = array( 
			'media_buttons' => 0,	// Show media button
			'teeny'         => 0,	// Hide or not extended editor button
			'tinymce'       => 1,	// Load TinyMCE or not
			'quicktags'     => 1,	// Load HTML editor or not
			'textarea_rows' => $attr['rows'],
			'textarea_name' => $attr['name'],
		);
		
		// Add input TinyMCE setting to default settings
		if ( isset( $tinymce_settings ) ) {
			foreach ( $tinymce_settings as $setting_key => $setting_value ) {
				$default_settings[ $setting_key ] = $setting_value;
			}
		}
		
		// Add id attribute
		if ( ! empty( $default_settings['editor_id'] ) ) {
			$editor_id = str_replace( array( '-', '_'), '', $default_settings['editor_id'] );
		} else {
			$editor_id = 'editorid';
		}

		// Buffering print
		ob_start();
		wp_editor( stripslashes( $attr['value'] ), $editor_id, $default_settings );	
		
		$input = ob_get_contents();
		
		ob_end_clean();
	}
	
	// Create simple textarea
	else {

		$input = '<textarea ';
	
		foreach ( $attr as $attribute_key => $attribute_value ) {
			
			// Don't add type or value attributes
			if ( 'type' == $attribute_key or 'value' == $attribute_key ) {
				continue;
			}
			
			// Boolean attributes
			if ( true === $attribute_value ) {
				$input .= " $attribute_key";
			}
			
			// Attributes with value
			elseif ( ! empty( $attribute_value ) or ! $attribute_value ) {
				$input .= ' ' . $attribute_key . '="' . $attribute_value . '"';
			}
		}
		
		$input .= '>' . $attr['value'] . '</textarea>';
	}
	
	return $input;
}


/**
 * Create select input
 *
 * @param array $attr
 * @param array $options
 *
 * @return string With <select> tag
 */
function create_select_input( array $attr, array $options ) {

	// Compile select tag with attributes
	$input = '<select ';
	
	foreach ( $attr as $attribute_key => $attribute_value ) {
		
		// Do not add type and value attributes
		if ( 'type' == $attribute_key or 'value' == $attribute_key ) {
			continue;
		}
		
		// Boolean attributes
		if ( true === $attribute_value ) {
			$input .= ' ' . $attribute_key;
		}
		
		// Attributes with value
		elseif ( ! empty( $attribute_value ) or ! $attribute_value ) {
			$input .= ' ' . $attribute_key . '="' . $attribute_value . '"';
		}
	}
	
	$input .= '>';
	
	// Compile options tags with attributes
	foreach ( $options as $option ) {
	
		$input .= '<option ';
	
		foreach ( $option as $attribute_key => $attribute_value ) {
		
			// Do not add caption attribute
			if ( 'caption' == $attribute_key ) {
				continue;
			}
			
			// Boolean attributes
			if ( true === $attribute_value ) {
				$input .= ' ' . $attribute_key;
			}
			
			// Attributes with value
			elseif ( ! empty( $attribute_value ) or ! $attribute_value ) {
				$input .= ' ' . $attribute_key . '="' . $attribute_value . '"';
			}
		}
		
		// Mark as selected if value matches
		$option_is_selected = array_key_exists( 'selected', $option );
		if ( $attr['value'] == $option['value'] and false == $option_is_selected ) {
			$input .= ' ' . 'selected';
		}
		
		$input .= '>' . $option['caption'] . '</option>';
	}
	
	$input .= '</select>';
	
	return $input;
}


/**
 * Get object id
 *
 * @param $object_type
 * @return integer With $object_id
 */
function get_object_id( $object_type ) {

	// Get post id
	if ( 'post_meta' == $object_type ) {
		$object_id = get_the_ID();
	}
	
	// Get user id
	elseif ( 'user_meta' == $object_type or 'user_data' == $object_type ) {
		$object_id = get_current_user_id();
	}
	
	// Get comment id
	// ToDo
	
	return $object_id;
}


/**
 * Validate input data through GUMP validator
 *
 * @param string $value
 * @param array $rules
 *
 * @return array|void Array returns if find errors
 */
function validate_data( $value, array $rules ) {
	
	// Add GUMP validator
	if ( ! class_exists( 'GUMP' ) ) {
		include IFFD_DIR . 'plugins/gump/gump.class.php';
	}
	$validator = new GUMP();
	
	// Parse rules
	foreach ( $rules as $rules_key => $rules_value ) {
		
		// Boolean rules
		if ( true === $rules_value ) {
			$rules_string .= $rules_key . '|';
		} 
		
		// Rules with value
		elseif ( ! empty( $rules_value ) ) {
			$rules_string .= $rules_key . ',' . $rules_value . '|';
		}
	}
	
	// Remove the last separator "|"
	$rules_string_last_symbol = substr($rules_string, -1);
	if ( '|' == $rules_string_last_symbol ) {
		$rules_string = substr( $rules_string, 0, -1 ); 
	}
	
	// Validate
	$validated = $validator->validate(
		array( 'value' => $value ),
		array( 'value' => $rules_string )
	);
	
	// Check and return errors
	if ( true !== $validated ) {

		include IFFD_DIR . 'error_messages.php';
		
		foreach ( $validated as $error ) {
			$errors[] = $error_messages[ $error['rule'] ];
		}

		return $errors;
	}
}


/**
 * Set value to database
 *
 * @param string $object_slug
 * @param string $object_type
 * @param integer $object_id
 * @param array $attr
 *
 * @return boolean save functions result
 */
function set_db_value( $object_slug, $object_type, $object_id, $input_value, $attr ) {

	// Get optional $type and $name
	if ( is_array( $attr ) ) {
		extract( $attr );
	}

	// Set option
	if ( 'option' == $object_type ) {
		if ( ! empty( $input_value ) or ! $input_value ) {
			$result = update_option( $object_slug, $input_value ); 
		} else {
			$result = delete_option( $object_slug );
		}
	}
	
	// Set user data
	elseif ( 'user_data' == $object_type ) {
		$userdata['ID'] = $object_id;
		$userdata[ $object_slug ] = $input_value;
		
		$result = wp_update_user( $userdata );
	}
	
	// Set post
	elseif ( 'post' == $object_type ) {
		$post_data['ID'] = $object_id;
		
		// Set thumbnail
		if ( 'thumbnail' == $object_slug ) {
			if ( ! empty( $input_value ) ) {
				$result = set_post_thumbnail( $object_id, $input_value );
			} else {
				$result = delete_post_thumbnail( $object_id );
			}
		}
		
		// Set categories as array
		elseif ( 'post_category' == $object_slug ) {
			
			$post_categories = get_the_category( $object_id );
      
			foreach ( $post_categories as $post_category ) {
				$post_data['post_category'][] = $post_category->cat_ID;
			}
			
			// Add selected category
			$post_data['post_category'][] = $input_value; 
			
			// Delete unselected category
			if ( empty( $input_value ) and ! empty( $name ) ) {
				$category_id = get_cat_ID( $name );
				
				if ( in_array( $category_id, $post_data['post_category'] ) ) {
					$delete_key = array_search( $category_id, $post_data['post_category'] );
					unset( $post_data['post_category'][$delete_key] );
				}
			}
		} else {
			$post_data[ $object_slug ] = $input_value;
		}
		
		$result = wp_update_post( $post_data );
	}
	
	// Set meta
	else {
		$add_function = 'add_' . $object_type;
		$delete_function = 'delete_' . $object_type;
		$update_function = 'update_' . $object_type;
		
		// Set serialize value from MediaUploader
		if ( ! empty( $type ) and 'file' == $type ) {
			$result = $delete_function( $object_id, $object_slug );
			
			// Unserialize values
			$input_value = array_diff( explode( ',', $input_value ), array('') );
			
			foreach( $input_value as $attachment_id ) {
				$result = $add_function( $object_id, $object_slug, $attachment_id );
			}
		} else {
		
			// Set single value in one meta
			if ( $object_slug == $attr['name'] ) {
				if ( ! empty( $input_value ) or $input_value ) {
					$result = $update_function( $object_id, $object_slug, $input_value );
				} else {
					$result = $delete_function( $object_id, $object_slug );
				}
			}
			
			// Set multiple value in one meta
			else {
				if ( ! empty( $input_value ) or '0' === $input_value ) {
					$existing_values = get_user_meta( $object_id, $object_slug );
					
					// Add value only if it's not exist
					if ( ! in_array( $input_value, $existing_values ) ) {
						$result = $add_function( $object_id, $object_slug, $input_value );
					}
				} else {
					$result = $delete_function( $object_id, $object_slug, $attr['value'] );
				}
			}
		}
	}
	
	return $result;
}


/**
 * Get value from database
 *
 * @param string $object_slug
 * @param string $object_type
 * @param integer $object_id
 * @param boolean $single is optional. Default = true. For control single or multiple meta needed get
 *
 * @return array|string database value
 */
function get_db_value( $object_slug, $object_type, $object_id, $single = true ) {
	
	// Get option
	if( 'option' == $object_type ) {
		$db_value = get_option( $object_slug );
	}
	
	// Get user data
	elseif ( 'user_data' == $object_type ) {
		$current_user = wp_get_current_user();
		$db_value = $current_user->$object_slug;
	}
	
	// Get post data
	elseif ( 'post' == $object_type ) {
		
		// Get thumbnail
		if ( 'thumbnail' == $object_slug ) {
			$db_value = get_post_thumbnail_id( $object_id );
		}
		
		// Get string data
		else {
			$post_data = get_post( $object_id, 'ARRAY_A' );
			$db_value = $post_data[ $object_slug ];
		}
	}
	
	// Get meta
	else {
		$get_function = 'get_' . $object_type;
		$db_value = $get_function( $object_id, $object_slug, $single );
	}
	
	return $db_value;
}


/**
 * Display field
 *
 * @param string $input
 * @param string $object_slug (optional)
 * @param string $title (optional)
 * @param string $description (optional)
 * @param array $errors (optional)
 */
function display_field( $input, $object_slug = '', $title = '', $description = '', $errors = '') {

	$title = $title ? '<div class="iffd-field-title">' . $title . '</div>' : '';
	$description = $description ? '<div class="iffd-field-description">' . $description . '</div>' : '';
	
	// Parse errors
	if ( ! empty( $errors ) ) {
		$error_message = '<div class="iffd-field-errors-messages">';
		
		foreach ( $errors as $error ) {
			$error_message .= '<span class="iffd-field-error">' . __( $error, 'IFFD-Textdomain' ) . '</span>';
		}
		
		$error_message .= '</div>';
	}
	
	// If it is radio group or media uploader wrap in div
	$tag_is_radio_group = strripos( $input, 'type="radio"' );
	$tag_is_media_uploader = strripos( $input, 'id="iffd-mediauploader"' );
	
	if ( false === $tag_is_radio_group and false === $tag_is_media_uploader ) {
		$wrapper = 'label';
	} else {
		$wrapper = 'div';
	}
	
	// Make all field
	$field = 
		'<' . $wrapper . ' id="iffd-field-' . $object_slug . '" class="iffd-field">' .
			$title .
			$description .
			$error_message .
			$input .
		'</' . $wrapper . '>';

	print $field;
}


/**
 * Just display control input tag (button|reset|submit)
 *
 * @param array $args
 */
function use_control_element( array $args ) {
	echo create_simple_input( $args['attr'] );
}


/**
 * Direct data processing
 *
 * @param array $args
 */
function use_data_field( array $args ) {

	extract( $args );

	if ( empty( $object_id ) and ! empty( $object_type ) ) {
		$object_id = get_object_id( $object_type );
	}

	$input_value = $_POST[ $attr['name'] ];
	
	// Validate input data
	if ( ! empty( $_POST ) and ! empty( $rules ) and ! empty( $attr['name'] ) ) {
		$errors = validate_data( $input_value, $rules );
	}
	
	// Processing data
	if ( ! empty( $object_slug ) and ! empty( $object_type ) and ! empty( $object_id ) ) {
			
		// Save data
		if ( empty( $errors ) and ! empty( $_POST ) ) {
			set_db_value( $object_slug, $object_type, $object_id, $input_value, $attr );
		}
	
		// Get single or multiple meta value
		if ( $object_slug == $attr['name'] ) {
			$output_value = get_db_value( $object_slug, $object_type, $object_id );
		} else {
			$output_value = get_db_value( $object_slug, $object_type, $object_id, $single = false );
		}
	}
	
	// Determine value for display. Priorities: $_POST[ $name ]['value'] (default) > $args['attr']['value'] > DB value
	if ( isset( $input_value ) ) {
		$attr['value'] = $input_value;
	} elseif ( empty( $attr['value'] ) ) {
		$attr['value'] = $output_value;
	}
	
	// Cleaning value
	if ( $attr['tinymce'] != true ) {
		$attr['value'] = esc_html( $attr['value'] );
	}
	
	// Get and display result
	if ( 'textarea' == $attr['type'] ) {
		$input = create_textarea_input( $attr, $attr['tinymce'], $settings );
	} elseif ( 'radio' == $attr['type'] ) {
		$input = create_radio_input( $attr, $options );
	} elseif ( 'select' == $attr['type'] ) {
		$input = create_select_input( $attr, $options );
	} elseif ( 'checkbox' == $attr['type'] ) {
		$input = create_checkbox_input( $attr, $output_value );
	} else {
		$input = create_simple_input( $attr );
	}
	
	display_field( $input, $object_slug, $title, $description, $errors );
}


/**
 * Direct media processing
 *
 * @param array $args
 */
function use_media_field( array $args ) {

	wp_enqueue_media();
	wp_enqueue_script( 'iffd-mediauploader-script' );
	wp_enqueue_style( 'iffd-mediauploader-style' );

	extract( $args );
	
	$button_text = empty( $button_text ) ? 'Add file' : $button_text;
	$mime_type = substr( $post->post_mime_type, 0, strpos( $post->post_mime_type, '/' ) );
	
	if ( empty( $object_id ) and ! empty( $object_type ) ) {
		$object_id = get_object_id( $object_type );
	}
	
	// Processing data
	if ( ! empty( $object_slug ) and ! empty( $object_type ) and ! empty( $object_id ) ) {
		if ( ! empty( $_POST ) ) {

			$input_value = $_POST[ $attr['name'] ];
			
			// Unserialize values
			$input_value_array = array_diff( explode( ',', $input_value ), array('') );
			
			if ( ! empty( $rules ) ) {
			
				// Validate if value is string
				if ( is_array( $input_value_array ) ) {
					$errors = validate_data( $input_value, $rules );
				} 
				
				// Validate if value is array
				else {
					foreach ( $input_value_array as $value_item ) {
						$errors = validate_data( $value_item, $rules );
					}
				}
			}

			if ( empty( $errors ) ) {
				set_db_value( $object_slug, $object_type, $object_id, $input_value, $attr );
			}
		}
		
		$output_value = get_db_value( $object_slug, $object_type, $object_id, $single = false );
		
		// Serialize if output value is array
		if ( is_array( $output_value ) ) {
			$output_value = implode( ",", $output_value );
		}
	}

	// Determine value for display
	if ( $default_file_id and empty( $output_value ) ) {
		$attr['value'] = $default_file_id;
	} else {
		$attr['value'] = $output_value;
	}

	// Create media uploader input
	$input .= '<div id="iffd-mediauploader"';
		
		if ( isset( $multiple_select ) ) {
			$input .= ' data-multiple-select="' . ( $multiple_select ? 1 : 0 ) . '"';
		}
		
		if ( ! empty( $mime_type ) ) {
			$input .= ' data-mime-type="' . $mime_type . '"';
		}

	$input .= '>';

	// Get attachments
	$get_post_args = array( 
		'post_type' => 'attachment',
		'post_status' => null,
		'include' => $attr['value'],
	);

	if ( ! empty( $attr['value'] ) ) {
		$attachments = get_posts( $get_post_args );
	}
	
	// Add attachments
	if ( is_array( $attachments ) ) {
		foreach ( $attachments as $post ) {
			
			// Add video file
			if ( 'video' == $mime_type ) {
				$input .=
					'<div' .
						' data-file-id="' . $post->ID . '"' . 
						' class="iffd-mediauploader-mediaplayer">' .
							'<div>' . wp_video_shortcode( array( 'src' => $post->guid ) ) . '</div>' .
							'<a class="iffd-mediauploader-file-remove-btn">×</a>' .
					'</div>';
			} 
			
			// Add audio file
			elseif ( 'audio' == $mime_type ) {
				$input .=
					'<div' .
						' data-file-id="' . $post->ID . '"' . 
						' class="iffd-mediauploader-mediaplayer iffd-mediauploader-mediaplayer-audio">' .
						'<div>' . wp_audio_shortcode( array('src' => $post->guid) ) . '</div>' .
							'<a class="iffd-mediauploader-file-remove-btn">×</a>' .
				'</div>';
			} 
			
			// Add other file
			else {
				$metadata = wp_get_attachment_image_src( $post->ID, 'thumbnail', true );
				$input .=
					'<div' .
						' data-file-id="' . $post->ID . '"' . 
						' class="iffd-mediauploader-files-wrapper"' . 
						' style="background-image: url(' . $metadata[0] . ')">' .
							'<span class="iffd-mediauploader-caption">' . $post->post_title . '</span>' .
							'<a class="iffd-mediauploader-file-remove-btn">×</a>' .
					'</div>';
			}
		}
	}

	// Add hidden input with attachments ids
	$input .= 
		'<input' .
			' type="hidden"' .
			' name="' . $attr['name'] . '"' .
			'	id="iffd-mediauploader-files-ids"' .
			' value="' . $attr['value'] . '">';
	
	// Add button
	$input .= 
		'<div id="iffd-mediauploder-btn-wrapper" style="display: none">' .
			'<button' .
				' type="submit"' .
				' id="iffd-mediauploader-btn">' . $button_text . 
			'</button>' . 
		'</div>' .
	'</div>';
	
	display_field( $input, $object_slug, $title, $description, $errors );
}