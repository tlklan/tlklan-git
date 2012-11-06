<?php

class AdminController extends Controller
{
	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'expression'=>'Yii::app()->user->isAdmin()',
			),
			array('deny'),
		);
	}


	public function actionIndex()
	{
		$this->render('index');
	}

	public function getPageTitle()
	{
		if ($this->_pageTitle !== null)
			return parent::getPageTitle();
		else
			return 'Administration - LAN-klubben';

	}


}