<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Användare'=>array('admin'),
);

?>
<h1>Hantera användare</h1>

<p>
	Här är en lista på alla användare som är registrerade på sidan. Genom att 
	klicka på ikonerna till höger i listan kan man ändra och ta bort användare.
</p>

<?php $this->widget('TbGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'name',
		'email',
		'username',
		array(
			'name'=>'has_werket_login',
            'header'=>'Har werket.tlk.fi konto',
            'filter'=>array('1'=>'Ja','0'=>'Nej'),
            'value'=>'($data->has_werket_login=="1")?("Ja"):("Nej")',
		),
		array(
			'class'=>'TbButtonColumn',
			'template'=>'{update} {delete}',
		),
	),
));
