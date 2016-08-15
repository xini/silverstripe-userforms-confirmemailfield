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
	
	public function getFieldValidationOptions()
	{
		$fields = parent::getFieldValidationOptions();
		
		$validEmailFields =  EditableEmailField::get()->filter(array(
			'ParentID' => (int)$this->ParentID,
		))->exclude(array(
			'ID' => (int)$this->ID,
		));
		
		$fields->add(
			DropdownField::create(
			    'EqualToID',
				_t('EditableConfirmEmailField.EQUALTO', 'Must be equal to'),
				$validEmailFields->map('ID', 'Title'),
				$this->EqualToID
			)->setEmptyString('- select -')
		);
		
		return $fields;
	}
	
	public function populateFromPostData($data)
	{
		$this->EqualToID 	= (isset($data['EqualTo'])) ? $data['EqualTo']: 0;
		
		parent::populateFromPostData($data);
	}
	
	protected function updateFormField($field)
	{
		parent::updateFormField($field);
		
        if ($this->EqualTo()) {
            $fieldid = "UserForm_Form_".$this->EqualTo()->getFormField()->ID();
            $field->setAttribute('data-rule-equalTo', '#'.$fieldid);
        }
	}
	
	public function getIcon()
	{
		return USERFORMS_DIR . '/images/' . strtolower(get_parent_class($this)) . '.png';
	}
}
