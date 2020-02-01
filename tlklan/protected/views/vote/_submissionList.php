<?php

$form = new TbActiveForm();
$form->type = 'horizontal';
$form->inlineErrors = true;
$form->errorMessageCssClass = 'help-inline error';

echo $form->radioButtonListRow($model, 'submission', $data);