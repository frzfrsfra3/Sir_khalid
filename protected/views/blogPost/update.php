<h1><?php echo $model->isNewRecord ? 'Create BlogPost' : 'Update BlogPost'; ?></h1>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
