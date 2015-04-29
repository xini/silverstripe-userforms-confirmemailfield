<?php
/**
 * EditableConfirmEmailField
 *
 * @package userforms
 */

class EditableConfirmEmailField extends EditableEmailField {
	
	private static $has_one = array(
		"EqualTo" => "EditableEmailField",
	);
	
	private static $singular_name = 'Email Confirmation Field';
	
	private static $plural_name = 'Email Confirmation Fields';
	
	public function getFieldValidationOptions() {
		$fields = parent::getFieldValidationOptions();
		
		$validEmailFields =  EditableEmailField::get()->filter(array(
			'ParentID' => (int)$this->ParentID,
		))->exclude(array(
			'ID' => (int)$this->ID,
		)); 
		
		$fields->add(
			DropdownField::create(
				$this->getFieldName('EqualTo'),
				_t('EditableConfirmEmailField.EQUALTO', 'Must be equal to'),
				$validEmailFields->map('ID', 'Title'),
				$this->EqualToID
			)->setEmptyString('- select -')
		);
		
		return $fields;
	}
	
	public function populateFromPostData($data) {
		$this->EqualToID 	= (isset($data['EqualTo'])) ? $data['EqualTo']: 0;
		
		parent::populateFromPostData($data);
	}
	
	/**
	 * Return the validation information related to this field. This is 
	 * interrupted as a JSON object for validate plugin and used in the 
	 * PHP. 
	 *
	 * @see http://docs.jquery.com/Plugins/Validation/Methods
	 * @return Array
	 */
	public function getValidation() {
		$options = parent::getValidation();
		
		if($this->EqualToID) {
			$fieldid = "Form_Form_".$this->EqualTo()->getFormField()->ID();
			$options['equalTo'] = '#'.$fieldid;
		}
			
		return $options;
	}
	
	public function getIcon() {
		return USERFORMS_DIR . '/images/' . strtolower(get_parent_class($this)) . '.png';
	}
}
