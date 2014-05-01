<?php

class acf_field_prbd extends acf_field_relationship {
	
	// vars
	var $settings, // will hold info such as dir / path
		$defaults, // will hold default field options
		$current; // will hold value prior to update, used for comparison
		
		
	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function __construct()
	{
		// vars
		$this->name = 'bidirectional-post-relation';
		$this->label = __('Bidirectional Post Relation');
		$this->category = __("Relational",'acf'); // Basic, Content, Choice, etc
		$this->defaults = array(
			'post_type'			=>	array('all'),
			'max' 				=>	'',
			'taxonomy' 			=>	array('all'),
			'filters'			=>	array('search'),
			'result_elements' 	=>	array('post_title', 'post_type'),
			'return_format'		=>	'object'
		);
		$this->l10n = array(
			'max'		=> __("Maximum values reached ( {max} values )",'acf'),
			'tmpl_li'	=> '
							<li>
								<a href="#" data-post_id="<%= post_id %>"><%= title %><span class="acf-button-remove"></span></a>
								<input type="hidden" name="<%= name %>[]" value="<%= post_id %>" />
							</li>
							'
		);


		// do not delete!
		acf_field::__construct();


		// extra
		add_action('wp_ajax_acf/fields/relationship/query_posts', array($this, 'query_posts'));
		add_action('wp_ajax_nopriv_acf/fields/relationship/query_posts', array($this, 'query_posts'));

	}

	/*
	*  load_value()
	*
		*  This filter is applied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value - the value found in the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the value to be saved in the database
	*/

	function load_value( $value, $post_id, $field )
	{
		// Note: This function can be removed if not used
		$this->current = $value;
		return $value;
	}

	function clean_post_value( $value ) {
		// validate
		if( empty($value) )
		{
			return $value;
		}


		if( is_string($value) )
		{
			// string
			$value = explode(',', $value);

		}
		elseif( is_object($value) && isset($value->ID) )
		{
			// object
			$value = array( $value->ID );

		}
		elseif( is_array($value) )
		{
			// array
			foreach( $value as $k => $v ){

				// object?
				if( is_object($v) && isset($v->ID) )
				{
					$value[ $k ] = $v->ID;
				}
			}

		}

		return array_map( 'strval', $value );
	}


	/*
	*  update_value()
	*
	*  This filter is appied to the $value before it is updated in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value - the value which will be saved in the database
	*  @param	$post_id - the $post_id of which the value will be saved
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the modified value
	*/

	function update_value( $value, $post_id, $field )
	{
		// validate
		if( empty($value) )
		{
			return $value;
		}


		if( is_string($value) )
		{
			// string
			$value = explode(',', $value);

		}
		elseif( is_object($value) && isset($value->ID) )
		{
			// object
			$value = array( $value->ID );

		}
		elseif( is_array($value) )
		{
			// array
			foreach( $value as $k => $v ){

				// object?
				if( is_object($v) && isset($v->ID) )
				{
					$value[ $k ] = $v->ID;
				}
			}

		}


		// save value as strings, so we can clearly search for them in SQL LIKE statements
		$value_int = $value;

		$value = array_map('strval', $value);

		$orig_value = $this->clean_post_value( get_field( $field['name'] ) );

//		if ( ! $current_value = get_field( $field['name'] ) ) {
//			return $value;
//		}
//
//		if ( is_array( $current_value ) ) {
//			if ( is_object( $current_value[0] ) ) {
//				$orig_value = array_map( 'strval', wp_list_pluck( $current_value, 'ID' ) );
//			} else {
//				$orig_value = array_map( 'strval', $current_value );
//			}
//		}

		$missing_values = array_diff( $orig_value, $value );

		$new_values = array_diff( $value, $orig_value );

		$magic = 'test';

//		$new_values = array_diff( $value_int, $orig_value );
//
//
//
		foreach( $missing_values as $id ) {
			if ( $post_value = get_field( $field['name'], $id ) ) {
				if ( is_array( $post_value ) ) {
					$new_value = array_diff( $post_value, array( $post_id ) );
					update_field( $field['name'], $new_value, intval( $id ) );
				}
			}
		}

		$compare = 'test';
//
//		$new_values = array_diff( $value_int, $orig_value );
//
		foreach( $new_values as $id ) {
			if ( $post_value = get_field( $field['name'], intval( $id ) ) ) {
				$post_value[] = strval( $post_id );
			} else {
				$post_value = array( strval( $post_id ) );
			}
			$test = update_field( $field['name'], $post_value, intval( $id ) );
			$magic = $test;
		}

		return $value;
	}

	
}


// create field
new acf_field_prbd();

?>
