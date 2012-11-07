<?php

/* @var $this UserController */
/* @var $model User */

$this->pageTitle = 'Din profil';
$this->breadcrumbs=array(
	$model->name,
);

?>

<div class="user-profile">
	
	<fieldset>
		<legend>Dina användaruppgifter</legend>

		<?php $this->widget('TbDetailView', array(
			'type'=>'striped',
			'data'=>$model,
			'attributes'=>array(
				'name',
				'email',
				'username',
				array(
					'name'=>'has_werket_login',
					'type'=>'raw',
					'value'=>$model->hasShellAccount() ? '<i class="icon-ok"></i>' : '',
				),
				'date_added',
			),
		)); ?>
	</fieldset>

	<hr />

	<div class="form-actions">
		<?php

		$this->widget('bootstrap.widgets.TbButton', array(
			'type'=>'primary',
			'buttonType'=>'link',
			'icon'=>'edit white',
			'label'=>'Ändra uppgifter',
			'url'=>$this->createUrl('user/update'),
		));

		echo '&nbsp;&nbsp;&nbsp;';

		// Shell users must change their password from the shell
		if (!$model->hasShellAccount())
		{
			$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'link',
				'icon'=>'edit',
				'label'=>'Byt lösenord',
				'url'=>$this->createUrl('user/changePassword'),
			));
		}

		?>
	</div>
</div>