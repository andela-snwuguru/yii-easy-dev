<div class="row">
    <?php echo $this->prepend; ?>
</div>
<fieldset>
    <legend><?php echo $this->pageHeaders['view']; ?></legend>
<?php
$attributes = array('data'=> $model);
if($this->viewColumns)
    $attributes['attributes'] = $this->viewColumns;

$this->widget('booster.widgets.TbDetailView', $attributes);
?>
</fieldset>

<?php
if($this->relations){
    foreach ($this->relations as $key => $value) { ?>

<div class="view">
    <fieldset>
    <legend><?php echo isset($value['title']) ? $value['title'] : ucwords($key); ?></legend>
    <?php
        $params = array(
            'id'=>$key.'-grid',
        );
        if(isset($value['columns'])){
            $params['columns'] = $value['columns'];
        }
        $columns = YedOperation::getRelations(get_class($model));
        $relationModel = new $columns[$key][0]('search');
        $relationModel->$columns[$key][1] = $model->id;
        $params['dataProvider'] = $relationModel->search();
        $this->widget('booster.widgets.TbGridView', $params);
    ?>
    </fieldset>
</div>
 <?php   }
}

?>

<div class="row">
    <?php echo $this->append; ?>
</div>