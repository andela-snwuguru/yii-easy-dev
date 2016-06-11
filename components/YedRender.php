<?php

class YedRender {
    public $form, $model;
    public static function controller(){
        return new CController(null);
    }

    public static function instance(){
        return new self;
    }

    public function text($field, $params){
        $htmlOptions = isset($params['htmlOptions']) ? $params['htmlOptions'] : array();
        return $this->form->textFieldGroup($this->model, $field, $htmlOptions);
    }

    public function password($field, $params){
        $htmlOptions = isset($params['htmlOptions']) ? $params['htmlOptions'] : array();
        return $this->form->passwordFieldGroup($this->model, $field, $htmlOptions);
    }

    public function checkbox($field, $params){
        $htmlOptions = isset($params['htmlOptions']) ? $params['htmlOptions'] : array();
        return $this->form->checkboxGroup($this->model, $field, $htmlOptions);
    }

    public function date($field, $params){
        return $this->form->datePickerGroup(
            $this->model,
            $field,
            array(
                'widgetOptions' => isset($params['widgetOptions']) ? $params['widgetOptions'] : array(),
                'hint' => isset($params['hint']) ? $params['hint'] : '',
                'prepend' => isset($params['prepend']) ? $params['prepend'] : '',
                'append' => isset($params['append']) ? $params['append'] : '',
                //'<i class="glyphicon glyphicon-calendar"></i>'
            )
        );
    }

    public function time($field, $params){
        return $this->form->timePickerGroup(
            $this->model,
            $field,
            array(
                'widgetOptions' => isset($params['widgetOptions']) ? $params['widgetOptions'] : array(),
                'hint' => isset($params['hint']) ? $params['hint'] : '',
                'prepend' => isset($params['prepend']) ? $params['prepend'] : '',
                'append' => isset($params['append']) ? $params['append'] : '',
                //'<i class="glyphicon glyphicon-calendar"></i>'
            )
        );
    }

    public function dateRange($field, $params){
        return $this->form->dateRangeGroup(
            $this->model,
            $field,
           array(
                'widgetOptions' => isset($params['widgetOptions']) ? $params['widgetOptions'] : array(),
                'hint' => isset($params['hint']) ? $params['hint'] : '',
                'prepend' => isset($params['prepend']) ? $params['prepend'] : '',
                'append' => isset($params['append']) ? $params['append'] : '',
                //'<i class="glyphicon glyphicon-calendar"></i>'
            )
        );
    }

    public function color($field, $params){
        return $this->form->colorpickerGroup(
            $this->model,
            $field,
            array(
                'widgetOptions' => isset($params['widgetOptions']) ? $params['widgetOptions'] : array(),
                'hint' => isset($params['hint']) ? $params['hint'] : '',
                'prepend' => isset($params['prepend']) ? $params['prepend'] : '',
                'append' => isset($params['append']) ? $params['append'] : '',
                //'<i class="glyphicon glyphicon-calendar"></i>'
            )
        );
    }

    public function dropdown($field, $params){
        $widgetOptions = isset($params['widgetOptions']) ? $params['widgetOptions'] : array();
        $data = isset($params['data']) ? eval('return '.$params['data'].';') : array();
        $widgetOptions['data'] = $data;
        return $this->form->dropDownListGroup(
            $this->model,
            $field,
            array(
                'widgetOptions' => $widgetOptions,
                'hint' => isset($params['hint']) ? $params['hint'] : '',
                'prepend' => isset($params['prepend']) ? $params['prepend'] : '',
                'append' => isset($params['append']) ? $params['append'] : '',
            )
        );
    }

    public function tags($field, $params){
        $widgetOptions = isset($params['widgetOptions']) ? $params['widgetOptions'] : array();
        $data = isset($params['data']) ? eval($params['data']) : array();
        $widgetOptions['options']['tags'] = $data;
        $widgetOptions['asDropDownList'] = false;
        $widgetOptions['options']['tokenSeparators'] = isset($widgetOptions['options']['tokenSeparators']) ? $widgetOptions['options']['tokenSeparators'] : array(',', ' ');
        return $this->form->select2Group(
            $this->model,
            $field,
            array(
                'widgetOptions' => $widgetOptions,
                'hint' => isset($params['hint']) ? $params['hint'] : '',
                'prepend' => isset($params['prepend']) ? $params['prepend'] : '',
                'append' => isset($params['append']) ? $params['append'] : '',
            )
        );
    }

    public function textarea($field, $params){
        return $this->form->textAreaGroup(
            $this->model,
            $field,
            array(
                'widgetOptions' => isset($params['widgetOptions']) ? $params['widgetOptions'] : array(),
            )
        );
    }

    public function file($field, $params){
        $htmlOptions = isset($params['htmlOptions']) ? $params['htmlOptions'] : array();
        return $this->form->fileFieldGroup($this->model, $field, $htmlOptions);
    }

    public function redactor($field, $params){
        return $this->form->redactorGroup(
            $this->model,
            $field,
            array(
                'widgetOptions' => isset($params['widgetOptions']) ? $params['widgetOptions'] : array(),
            )
        );
    }

    public function html5Editor($field, $params){
        return $this->form->html5EditorGroup(
            $this->model,
            $field,
            array(
                'widgetOptions' => isset($params['widgetOptions']) ? $params['widgetOptions'] : array(),
            )
        );
    }

    public function ckEditor($field, $params){
        return $this->form->ckEditorGroup(
            $this->model,
            $field,
            array(
                'widgetOptions' => isset($params['widgetOptions']) ? $params['widgetOptions'] : array(),
            )
        );
    }

    public function markdownEditor($field, $params){
        return $this->form->markdownEditorGroup(
            $this->model,
            $field,
            array(
                'widgetOptions' => isset($params['widgetOptions']) ? $params['widgetOptions'] : array(),
            )
        );
    }

    public function toggle($field, $params){
        return $this->form->switchGroup($this->model, $field,
            array(
                'widgetOptions' => isset($params['widgetOptions']) ? $params['widgetOptions'] : array(),
            )
        );
    }

    public function checkboxList($field, $params){
        $widgetOptions = isset($params['widgetOptions']) ? $params['widgetOptions'] : array();
        $data = isset($params['data']) ? eval($params['data']) : array();
        $widgetOptions = array_merge($widgetOptions, $data);
        return $this->form->checkboxListGroup(
            $this->model,
            $field,
            array(
                'widgetOptions' => $widgetOptions,
                'inline' => isset($params['inline']) ? $params['inline'] : '',
            )
        );
    }


    public function radioList($field, $params){
        $widgetOptions = isset($params['widgetOptions']) ? $params['widgetOptions'] : array();
        $data = isset($params['data']) ? eval($params['data']) : array();
        $widgetOptions = array_merge($widgetOptions, $data);
        return $this->form->radioButtonListGroup(
            $this->model,
            $field,
            array(
                'widgetOptions' => $widgetOptions,
                'inline' => isset($params['inline']) ? $params['inline'] : '',
            )
        );

    }

    public function radio($field, $params){
        $htmlOptions = isset($params['htmlOptions']) ? $params['htmlOptions'] : array();
        return $this->form->radioButtonGroup($this->model, $field, $htmlOptions);
    }



    public static function boosterForm($model, $id='', $class = ''){
        $render = self::instance();
        $ctrl = self::controller();
        $model_name = get_class($model);
        $render->model = $model;
        $fields = YedOperation::getFormFields($model_name);
        if(empty($id))
            $id = strtolower($model_name).'Form';

        $render->form = $ctrl->beginWidget(
            'booster.widgets.TbActiveForm',
            array(
                'id' => $id,
                //'type' => 'inline',
                'htmlOptions' => array('class' => $class)
            )
        );

        if(empty($fields)){
            echo '<p>No field defined</p>';
        }else{
            foreach ($fields as $key => $value) {
                if(isset($value['type']) && method_exists($render, $value['type'])){
                    echo $render->$value['type']($key, $value);
                }else{
                    Y::exception($value['type'].' is an invalid field type for YedRender');
                }
            }
            $render->submitFooter();
        }


        $ctrl->endWidget();
    }

    public function submitFooter(){
        $ctrl = self::controller();
        echo '<div class="row form-actions well" style="text-align:right">';
        $ctrl->widget('booster.widgets.TbButton', array(
            'buttonType'=>'submit',
            'context'=>'primary',
            'label'=>$this->model && $this->model->isNewRecord ? Y::getModule()->buttonLabelCreate : Y::getModule()->buttonLabelUpdate,
        ));
        echo '</div>';
    }

    public static function button($model = null, $type = 'submit', $context = 'primary'){
        $ctrl = self::controller();
        $ctrl->widget('booster.widgets.TbButton', array(
            'buttonType'=>$type,
            'context'=>$context,
            'label'=>$model && $model->isNewRecord ? Y::getModule()->buttonLabelCreate : Y::getModule()->buttonLabelUpdate,
        ));
    }

    public static function badge($label, $context= 'success'){
        $ctrl = self::controller();
        $ctrl->widget(
            'booster.widgets.TbBadge',
            array(
                'context' => $context,
                // 'default', 'success', 'info', 'warning', 'danger'
                'label' => $label,
            )
        );
    }

    public static function progress($percent, $context= 'success'){
        $ctrl = self::controller();
        $ctrl->widget(
            'booster.widgets.TbProgress',
            array(
                'context' => $context, // 'success', 'info', 'warning', or 'danger'
                'percent' => $percent,
            )
        );
    }

    public static function toggleAlone($name, $switchChange= ''){
        $ctrl = self::controller();
        $ctrl->widget(
            'booster.widgets.TbSwitch',
            array(
                'name' => $name,
                'events' => array(
                    //'switchChange' => 'js:'.$switchChange
                ),
                'options' => array(
                    'size' => 'large', //null, 'mini', 'small', 'normal', 'large
                    'onColor' => 'success', // 'primary', 'info', 'success', 'warning', 'danger', 'default'
                    'offColor' => 'danger',  // 'primary', 'info', 'success', 'warning', 'danger', 'default'
                ),
            )
        );
    }


    public static function menu($data){
        $ctrl = self::controller();
        $ctrl->widget( 'booster.widgets.TbMenu', array(
            'items' => $data,
            'htmlOptions' => array('class' => 'nav nav-list'),
        ));
    }


}

?>