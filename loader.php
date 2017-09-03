<?php




/**
 * @param $form_fields
 * @param $args
 *
 * @return string
 */
function buddyforms_form_element_conditional_logic( $form_fields, $args ) {
	global $buddyforms;
	extract( $args );

	ob_start();

	echo '<div class="element_field">';

	echo '

            <table class="wp-list-table widefat posts">
                <thead>
                    <tr>
                        <th><span style="padding-left: 10px;">Field</span></th>
                        <th><span style="padding-left: 10px;">Value</span></th>
                        <th><span style="padding-left: 10px;">Default</span></th>
                        <th class="manage-column column-author"><span style="padding-left: 10px;">Action</span></th>
                    </tr>
                </thead>
            </table>
            <br>
    ';

	echo '<ul id="field_' . $field_id . '" class="element_field_sortable">';

	if ( ! isset( $buddyform['form_fields'][ $field_id ]['options'] ) && isset( $buddyform['form_fields'][ $field_id ]['value'] ) ) {
		foreach ( $buddyform['form_fields'][ $field_id ]['value'] as $key => $value ) {
			$buddyform['form_fields'][ $field_id ]['options'][ $key ]['label'] = $value;
			$buddyform['form_fields'][ $field_id ]['options'][ $key ]['value'] = $value;
		}
	}

	if ( isset( $buddyform['form_fields'][ $field_id ]['options'] ) ) {
		$count = 1;
		foreach ( $buddyform['form_fields'][ $field_id ]['options'] as $value => $label ) {
			buddyforms_form_element_conditional_logic_template($buddyform['slug'], $field_id, $count, $value, $label);
			$count ++;
		}
	}

	echo '
	    </ul>
     </div>
     <a href="' . $field_id . '"  data-form_slug="' . $buddyform['slug'] . '" class="button bf_add_conditional_logic">+</a>';

	$tmp = ob_get_clean();

	return $tmp;
}






function buddyforms_form_element_conditional_logic_template($form_slug, $field_id, $count, $value = "", $label = ""){
	global $buddyforms;


	$form_fields = $buddyforms[$form_slug]['form_fields'];

	$conditional_logic_options['none'] = 'Select Field';
	foreach( $form_fields as $field_key => $field ){
		if( $field_id != $field_key ){
			$conditional_logic_options[$field['slug']] = $field['name'];
		}
	}


	echo '<li class="field_item field_item_' . $field_id . '_' . $count . '">';
	echo '<table class="wp-list-table widefat posts striped"><tbody><tr><td>';

	$conditional_logic_val = isset( $buddyforms[$form_slug]['form_fields'][$field_id]['conditional_logic']) ? $buddyforms[$form_slug]['form_fields'][$field_id]['conditional_logic'] : false;

	$form_element = new Element_Select( '<b>' . __( 'Save field as ', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][conditional_logic]",
		$conditional_logic_options,
		array(
			'value'    => $conditional_logic_val,
			'class'    => 'conditional_logic',
			'field_id' => $field_id,
			'id'       => 'conditional_logic' . $field_id,
		) );

	$form_element->render();
	echo '</td><td>';

	$option = $buddyforms[$form_slug]['form_fields'][$field_id]['options'][$key];

	$form_element = new Element_Textbox( '', "buddyforms_options[form_fields][" . $field_id . "][options][" . $key . "][value]", array( 'value' => $option['value'] ) );
	$form_element->render();
	echo '</td><td>';
	$form_element = new Element_Radio( '', "buddyforms_options[form_fields][" . $field_id . "][default]", array( $option['value'] ), array( 'value' => isset( $buddyform['form_fields'][ $field_id ]['default'] ) ? $buddyform['form_fields'][ $field_id ]['default'] : '' ) );
	$form_element->render();
	echo '</td><td class="manage-column column-author">';
	echo '<a href="#" id="' . $field_id . '_' . $count . '" class="bf_delete_input" title="delete me">Delete</a>';
	echo '</td></tr></tbody></table></li>';
}



// Form Element
$field_conditional_logic = isset( $customfield['field_conditional_logic'] ) ? $customfield['field_conditional_logic'] : false;
$form_fields['conditional_logic']['field_conditional_logic'] = new Element_Select( '<b>' . __( 'Conditional Logic ', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][field_conditional_logic]",
	array(
		'logged_in'  => 'Logged In',
		'logged_off' => 'Logged Off',
	), array(
		'value'    => $field_conditional_logic,
		'class'    => 'field_conditional_logic',
		'field_id' => $field_id,
		'id'       => 'field_conditional_logic' . $field_id,
	) );



$field_args                               = Array(
	'field_id'  => $field_id,
	'buddyform' => $buddyform
);
$form_fields['general']['select_options'] = new Element_HTML( buddyforms_form_element_conditional_logic( $form_fields, $field_args ) );



// Form ELement End


add_action( 'wp_ajax_buddyforms_add_conditional_logic', 'buddyforms_add_conditional_logic' );
function buddyforms_add_conditional_logic(){
	global $buddyforms;


	$field_id  = $_POST['field_id'];
	$form_slug = $_POST['form_slug'];
	$numItems  = $_POST['numItems'];

	ob_start();
	buddyforms_form_element_conditional_logic_template( $form_slug, $field_id, $numItems);
	$tmp = ob_get_clean();

	echo json_encode( $tmp );
	die();
}

