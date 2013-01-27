<?php

$this->pageTitle = Yii::t('login', 'Logga in');
$this->breadcrumbs=array(
	Yii::t('login', 'Logga in'),
);

?>
<h1><?php echo Yii::t('login', 'Logga in'); ?></h1>
<p>
	<?php echo Yii::t('login', 'Fyll i användarnamn och lösenord för att logga in. Användaruppgifterna är 
	samma som till shellen.'); ?>
</p>
<hr />
<?php 

/** @var BootActiveForm $form */
$form = $this->beginWidget('TbActiveForm', array(
    'id'=>'verticalForm',
)); 

echo $form->textFieldRow($model, 'username', array('class'=>'span3', 'autofocus'=>'autofocus'));
echo $form->passwordFieldRow($model, 'password', array('class'=>'span3'));
echo $form->checkboxRow($model, 'rememberMe');

?>
<div class="form-actions">
	<?php $this->widget('TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'icon'=>'ok white',
		'label'=>Yii::t('login', 'Logga in'),
	)); ?>
</div>
<?php $this->endWidget(); ?>