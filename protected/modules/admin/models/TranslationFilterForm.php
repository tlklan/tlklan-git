<?php

/**
 * Form model for the filter on the translation page
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class TranslationFilterForm extends CFormModel
{

	/**
	 * @var string the target language (to translate to)
	 */
	public $targetLanguage;

	/**
	 * @var string (optional) the category that should be shown. Defaults to 
	 * false.
	 */
	public $category = false;

	/**
	 * @return array the attribute labels for this model
	 */
	public function attributeLabels()
	{
		return array(
			'targetLanguage'=>'Översätt till',
			'category'=>'Kategori',
		);
	}

	/**
	 * @return array the validation rules for this model
	 */
	public function rules()
	{
		return array(
			array('targetLanguage', 'required'),
			array('targetLanguage', 'validateTargetLanguage'),
			array('category', 'validateCategory'),
		);
	}
	
	/**
	 * Validates the category attribute (checking that it exists)
	 * @param string $attribute the attribute being validated
	 */
	public function validateCategory($attribute)
	{
		if (!empty($this->{$attribute}))
		{
			$model = MessageSource::model()->findByAttributes(array(
				'category'=>$this->{$attribute}));

			if ($model === null)
				$this->addError($attribute, 'Ogiltig kategori');
		}
	}

	/**
	 * Validates the targetLanguage attribute
	 * @param string $attribute the attribute being validated
	 */
	public function validateTargetLanguage($attribute)
	{
		if (!array_key_exists($this->{$attribute}, Controller::$validLanguages))
			$this->addError($attribute, 'Ogiltigt språkval');
	}

	/**
	 * Returns a list of all available message categories in a form suitable 
	 * for dropdown menus.
	 * @return array
	 */
	public function getCategoryList()
	{
		$result = Yii::app()->db->createCommand()
				->selectDistinct('category')
				->from(Yii::app()->messages->sourceMessageTable)
				->queryAll();

		// Make the result compatible with dropdown lists
		$categories = array();
		foreach ($result as $category)
			$categories[$category['category']] = $category['category'];

		return $categories;
	}

}