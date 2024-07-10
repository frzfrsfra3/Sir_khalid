<h1>View BlogPost #<?php echo $model->id; ?></h1>
<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'id',
        'user.username', // Display the username of the post author
        'title',
        'content',
        'is_public:boolean',
        'created_at',
        'updated_at',
    ),
)); ?>