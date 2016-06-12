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

<div class="row">
    <?php echo $this->append; ?>
</div>