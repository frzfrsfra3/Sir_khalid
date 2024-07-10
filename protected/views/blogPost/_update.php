<?php
/* @var $this BlogPostController */
/* @var $model BlogPost */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'update-form',
    'enableAjaxValidation' => false,
)); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'title'); ?>
        <?php echo $form->textField($model, 'title', array('size' => 60, 'maxlength' => 128)); ?>
        <?php echo $form->error($model, 'title'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'content'); ?>
        <?php echo $form->textArea($model, 'content', array('rows' => 6, 'cols' => 50)); ?>
        <?php echo $form->error($model, 'content'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
