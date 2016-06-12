<div class="view">
<?php
if($this->indexColumns){
    foreach ($this->indexColumns as $key => $value) {
        echo '<b>'.CHtml::encode($data->getAttributeLabel($value)).':</b> ';
        if(strpos($value, '.')){
            $col = explode('.', $value);
            $val = $data;
            foreach ($col as $field) {
                $val = $val->$field;
            }
            echo CHtml::encode($val).'<br/>';
        }else{
            echo CHtml::encode($data->$value).'<br/>';
        }

    }
}

?>
</div>
<hr/>