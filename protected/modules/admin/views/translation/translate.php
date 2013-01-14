<?php

/* @var $model TranslationFilterForm */
$this->pageTitle = "Översättningar";
$this->breadcrumbs=array(
    'Översättningar',
);

// Calculate number of untranslated messages
$numTotalMessages = count($messageSourceList);
$numTranslatedMessage = 0;

foreach($messageSourceList as $messageSource)
	if(isset($messageSource->translations[0]))
		$numTranslatedMessage++;

$numUntranslatedMessage = $numTotalMessages - $numTranslatedMessage;

?>
<h1>Översättningar</h1>

<p>
	Med hjälp av det här verktyget kan du översätta alla de korta textsträngar 
	som används på sidan. Välj vilket språk du vill översätta till med 
	formuläret nedan. Du kan även välja att endast visa en kategori för att få 
	färre resultat.
</p>

<hr />

<?php 

/* @var $form TbActiveForm */
$form = $this->beginWidget('TbActiveForm', array(
	'type'=>'horizontal',
));

echo $form->dropDownListRow($model, 'targetLanguage', Controller::getValidTargetLanguages());
echo $form->dropDownListRow($model, 'category', $model->getCategoryList());

?>
<div class="form-actions">
	<?php $this->widget('TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'label'=>'Ändra',
	)); ?>
</div>

<?php $this->endWidget(); ?>

<hr />

<?php echo CHtml::beginForm(array('translation/translate'));  ?>

<h2>Icke översatta textsträngar (<?php echo $numUntranslatedMessage; ?>/<?php echo $numTotalMessages; ?>)</h2>
<p>
	I den här listan visas enbart de textsträngar som <i>inte</i> ännu är 
	översatta till det aktuella språket. För att översätta en eller flera texter 
	skriver du in den översatta texten i rutan till höger och klickar slutligen 
	på "Spara ändringar"-knappen.
</p>
<hr />

<?php 

$untranslatedList = $messageSourceList;
foreach($untranslatedList as $k => &$messageSource) {
	if(isset($messageSource->translations[0]))
		unset($untranslatedList[$k]);
}

echo CHtml::hiddenField('targetLanguage', $model->targetLanguage);

$this->renderPartial('_list', array(
	'messageSourceList'=>$untranslatedList,
	'includeMissing'=>true,
)); 

?>
<div class="form-actions">
	<?php $this->widget('TbButton', array(
		'buttonType'=>'submit',
		'icon'=>'edit white',
		'type'=>'primary',
		'label'=>'Spara ändringar',
	)); ?>
</div>

<hr />

<h2>Översatta textsträngar (<?php echo $numTranslatedMessage; ?>/<?php echo $numTotalMessages; ?>)</h2>
<p>
	Dessa textsträngar har redan översatts. Om du vill ändra på någon är det 
	bara att skriva in den nya översättningen i textrutan och klicka på 
	"Spara ändringar"-knappen längst ner på sidan.
</p>
<hr />

<?php

$this->renderPartial('_list', array(
	'messageSourceList'=>$messageSourceList,
	'includeMissing'=>false,
));

?>
<div class="form-actions">
	<?php $this->widget('TbButton', array(
		'buttonType'=>'submit',
		'icon'=>'edit white',
		'type'=>'primary',
		'label'=>'Spara ändringar',
	)); ?>
</div>
<?php

echo CHtml::endForm();