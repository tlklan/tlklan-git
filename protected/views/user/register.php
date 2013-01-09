<?php

/* @var $this SiteController */
/* @var $model User */

$this->pageTitle = 'Registrera dig';
$this->breadcrumbs=array(
	'Registrera dig',
);

// Register some scripts
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.
		'/js/hide-login-details-fields.js', CClientScript::POS_END);

?>
<h1>Registrera dig</h1>
<?php $this->widget('cms.widgets.CmsBlock',array('name'=>'register-user-info')); ?>
<hr />
<?php

/* @var $form CActiveForm */

$form = $this->beginWidget('TbActiveForm', array('type'=>'horizontal')); 

echo $form->textFieldRow($model, 'name');
echo $form->textFieldRow($model, 'email');
echo $form->textFieldRow($model, 'username', array('hint'=>'Används då du loggar in på sidan. Går inte att ändra i efterhand.'));
echo $form->textFieldRow($model, 'nick', array('hint'=>'Nicket är det som syns på sidan och går att ändra i efterhand.'));
echo $form->checkboxRow($model, 'has_werket_login', array(
	'hint'=>'<div style="display: table;" class="alert-block alert alert-info"><b>OBS!</b> Kryssa inte i denna ruta om du inte har ett konto (annars kommer <br />
			 du inte att kunna logga in). Om du kryssar i det här måste du använda <br />
			 samma användarnamn här som ditt användarnamn till shellen (du kan <br />
			 dock byta nick)!</div>',
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
		'label'=>'Registrera',
	)); ?>&nbsp;&nbsp;&nbsp;
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'link',
		'icon'=>'remove',
		'label'=>'Avbryt',
		'url'=>Yii::app()->homeUrl,
	)); ?>
</div>

<?php $this->endWidget(); ?>