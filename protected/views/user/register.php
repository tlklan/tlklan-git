<?php

/* @var $this SiteController */
/* @var $model User */
/* @var $form CActiveForm */

$this->pageTitle = Yii::t('user', 'Registrera dig');
$this->breadcrumbs=array(
	Yii::t('user', 'Registrera dig'),
);

// Register some scripts
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.
		'/js/hide-login-details-fields.js', CClientScript::POS_END);

?>
<h1><?php echo Yii::t('user', 'Registrera dig'); ?></h1>
<?php $this->widget('cms.widgets.CmsBlock',array('name'=>'register-user-info')); ?>
<hr />
<?php

$form = $this->beginWidget('TbActiveForm', array('type'=>'horizontal')); 

echo $form->textFieldRow($model, 'name');
echo $form->textFieldRow($model, 'email');
echo $form->textFieldRow($model, 'username', array(
	'hint'=>Yii::t('user', 'Används då du loggar in på sidan. Går inte att ändra i efterhand.')));
echo $form->textFieldRow($model, 'nick', array(
	'hint'=>Yii::t('user', 'Nicket är det som syns på sidan och går att ändra i efterhand.')));
echo $form->checkboxRow($model, 'has_werket_login', array(
	'hint'=>$this->renderPartial('_werketLoginHint', null, true),
	'id'=>'has-werket-login',
));

?>
<div class="login-details-fields" style="display: <?php echo $model->has_werket_login ? 'none;' : 'block;'; ?>">
	<?php

	echo $form->passwordFieldRow($model, 'newPassword');
	echo $form->passwordFieldRow($model, 'passwordRepeat');

	?>
</div>

<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'icon'=>'ok white',
		'label'=>Yii::t('user', 'Registrera'),
	)); ?>&nbsp;&nbsp;&nbsp;
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'link',
		'icon'=>'remove',
		'label'=>Yii::t('general', 'Avbryt'),
		'url'=>Yii::app()->homeUrl,
	)); ?>
</div>

<?php $this->endWidget(); ?>