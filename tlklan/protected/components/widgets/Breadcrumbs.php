<?php

/**
 * Description of BreadCrumbs
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class Breadcrumbs extends TbBreadcrumbs
{

	public function run()
	{
		// Hide empty breadcrumbs.
		if (empty($this->links))
			return;

		$links = array();

		if (!isset($this->homeLink))
		{
			$content = CHtml::link(Yii::t('zii', 'Home'), Yii::app()->homeUrl);
			$links[] = $this->renderItem($content);
		}
		else if ($this->homeLink !== false)
			$links[] = $this->renderItem($this->homeLink);

		foreach ($this->links as $label => $url)
		{
			if (is_string($label) || is_array($url))
			{
				$content = CHtml::link($this->encodeLabel ? CHtml::encode($label) : $label, $url);
				$links[] = $this->renderItem($content);
			}
			else
				$links[] = $this->renderItem($this->encodeLabel ? CHtml::encode($url) : $url, true);
		}
		
		$links[] = $this->renderLanguageSelector();

		echo CHtml::tag('ul', $this->htmlOptions, implode('', $links));
	}
	
	/**
	 * Returns the HTML for the language selector
	 * @return string the HTML
	 */
	private function renderLanguageSelector()
	{
		ob_start();

		echo CHtml::openTag('li', array('class'=>'language-selector'));
		echo CHtml::beginForm();
		echo CHtml::label(Yii::t('general', 'Språk').': ', 'language');

		echo CHtml::dropDownList('language', Yii::app()->language, 
				Controller::$validLanguages, 
				array('submit'=>Yii::app()->controller->createUrl('/site/changeLanguage')));

		echo CHtml::endForm();
		echo CHtml::closeTag('li');

		return ob_get_clean();
	}

}