<div class="row">
    <?php echo $this->prepend; ?>
</div>
<fieldset>
    <legend><?php echo $this->pageHeaders['index']; ?></legend>
    <?php $this->widget('booster.widgets.TbListView',array(
    'dataProvider'=>$dataProvider,
    'itemView'=>$this->alias.'_view',
    )); ?>
</fieldset>
<div class="row">
    <?php echo $this->append; ?>
</div>
