<?php

/* @var $lan Lan */

$isActive = $lan->hasStarted() && !$lan->hasEnded();

$this->pageTitle = 'Dashboard';
$this->menu = array(
	array('label'=>'Uppdatera LAN-inställningar', 'url'=>array('lan/update', 'id'=>$lan->id)),
	array('label'=>'Lägg till ny betalning', 'url'=>array('payment/create')),
);

?>
<div class="dashboard">
	<h1>Dashboard</h1>

	<p>
		Snabb tillgång till allt man behöver för att sköta om ett pågående LAN.
	</p>

	<hr style="margin-bottom: 0;" />

	<div class="row">
		<div class="span12">
			<h2>Allmänt</h2>
		</div>
		
		<div class="span2">
			<div class="lan-status <?php echo $isActive ? 'on' : ''; ?>">
				<?php echo $isActive ? "It's on!" : "Soon"; ?>
			</div>
		</div>

		<div class="span8">
			<?php $this->widget('TbDetailView', array(
				'type'=>'striped',
				'data'=>$lan,
				'attributes'=>array(
					'name',
					'registrationCount',
				)
			)); ?>
		</div>
	</div>
	
	<div class="row">
		<div class="span12">
			<h2>Anmälningar</h2>

			<?php $this->renderPartial('/registration/_list', array(
				'dataProvider'=>$registrationModel->search(),
				'model'=>$registrationModel,
				'disableFilter'=>true,
				'disableSorting'=>true,
			)); ?>
		</div>
	</div>
	
	<div class="row">
		<div class="span12">
			<h2>Tävlingar</h2>

			<?php $this->renderPartial('_competitionList', array(
				'competitionDataProvider'=>$competitionDataProvider,
			)); ?>
		</div>
	</div>
</div>