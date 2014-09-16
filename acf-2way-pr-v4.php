<?php

class acf_field_prbd extends acf_field_relationship {
	
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
		$this->label = __( 'Bidirectional Post Relation', 'acf' );
		$this->category = __( 'Relational', 'acf' ); // Basic, Content, Choice, etc
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
	*  This filter is applied to the $value before it is updated in the db
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

		if ( $post_id === $GLOBALS['post_id'] ) {
			$new_value = $this->clean_post_value( $value );
			$old_value = $this->clean_post_value( get_field( $field['key'], false, false ) );

			$new_values = array();
			$missing_values = array();

			if ( ( ! empty( $new_value ) ) && ( ! empty( $old_value ) ) ) {
				$new_values = array_diff( $new_value, $old_value );
				$missing_values = array_diff( $old_value, $new_value );
			} else {
				if ( ! empty( $new_value ) ) {
					$new_values = array_map( 'strval', $new_value );
				}
				if ( ! empty( $old_value ) ) {
					$missing_values = array_map( 'strval', $old_value );
				}
			}

			foreach( $new_values as $value_add ) {
				$existing_value = get_field( $field['key'], $value_add, false );

				if ( ! empty( $existing_value ) ) {
					$existing_value[] = $post_id;
				} else {
					$existing_value = array( $post_id );
				}

				update_field( $field['key'], $existing_value, $value_add );
			}

			foreach( $missing_values as $value_remove ) {
				$existing_value = get_field( $field['key'], $value_remove, false );

				if ( ! empty( $existing_value ) ) {
					$existing_value = array_diff( $existing_value, array( $post_id ) );
					update_field( $field['key'], $existing_value, $value_remove );
				}

			}
		}

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
		$value = array_map( 'strval', $value );

		return $value;
	}

	
}


// create field
new acf_field_prbd();