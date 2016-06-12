<div class="row">
    <?php echo $this->prepend; ?>
</div>
<fieldset>
    <legend><?php echo $this->pageHeaders['admin']; ?></legend>
    <?php

if($this->addActionButtons && $this->adminColumns){
    $this->adminColumns[] = array(
        'class'=>'booster.widgets.TbButtonColumn',
        'buttons'=>array(
            'update'=>array('visible'=>function(){ return !$this->disableUpdate && $this->userCan('update'); }),
            'view'=>array('visible'=>function(){ return !$this->disableView && $this->userCan('view'); }),
            'delete'=>array('visible'=>function(){ return !$this->disableDelete && $this->userCan('delete'); }),
        )
    );
}

$params = array(
    'id'=>strtolower($this->modelName).'-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
);
if($this->adminColumns){
    $params['columns'] = $this->adminColumns;
}
$this->widget('booster.widgets.TbGridView', $params);

?>
</fieldset>

<div class="row">
    <?php echo $this->append; ?>
</div>