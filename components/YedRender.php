<?php

class YedRender {
    public $form, $model;

    /**
    *@return CController Instance
    */
    public static function controller(){
        return new CController(null);
    }

    /**
    *@return YedRender Instance
    */
    public static function instance(){
        return new self;
    }

    /**
    *@param string $field the model column name
    *@param string $params forms configuration parameters
    *@return textFieldGroup
    */
    public function text($field, $params){
        $htmlOptions = isset($params['htmlOptions']) ? $params['htmlOptions'] : array();
        return $this->form->textFieldGroup($this->model, $field, $htmlOptions);
    }

    /**
    *@param string $field the model column name
    *@param string $params forms configuration parameters
    *@return passwordFieldGroup
    */
    public function password($field, $params){
        $htmlOptions = isset($params['htmlOptions']) ? $params['htmlOptions'] : array();
        return $this->form->passwordFieldGroup($this->model, $field, $htmlOptions);
    }

    /**
    *@param string $field the model column name
    *@param string $params forms configuration parameters
    *@return checkboxGroup
    */
    public function checkbox($field, $params){
        $htmlOptions = isset($params['htmlOptions']) ? $params['htmlOptions'] : array();
        return $this->form->checkboxGroup($this->model, $field, $htmlOptions);
    }

    /**
    *@param string $field the model column name
    *@param string $params forms configuration parameters
    *@return datePickerGroup
    */
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

    /**
    *@param string $field the model column name
    *@param string $params forms configuration parameters
    *@return timePickerGroup
    */
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

    /**
    *@param string $field the model column name
    *@param string $params forms configuration parameters
    *@return dateRangeGroup
    */
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

    /**
    *@param string $field the model column name
    *@param string $params forms configuration parameters
    *@return colorpickerGroup
    */
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

    /**
    *@param string $field the model column name
    *@param string $params forms configuration parameters
    *@return dropDownListGroup
    */
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

    /**
    *@param string $field the model column name
    *@param string $params forms configuration parameters
    *@return select2Group
    */
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

    /**
    *@param string $field the model column name
    *@param string $params forms configuration parameters
    *@return textAreaGroup
    */
    public function textarea($field, $params){
        return $this->form->textAreaGroup(
            $this->model,
            $field,
            array(
                'widgetOptions' => isset($params['widgetOptions']) ? $params['widgetOptions'] : array(),
            )
        );
    }

    /**
    *@param string $field the model column name
    *@param string $params forms configuration parameters
    *@return fileFieldGroup
    */
    public function file($field, $params){
        $htmlOptions = isset($params['htmlOptions']) ? $params['htmlOptions'] : array();
        return $this->form->fileFieldGroup($this->model, $field, $htmlOptions);
    }

    /**
    *@param string $field the model column name
    *@param string $params forms configuration parameters
    *@return redactorGroup
    */
    public function redactor($field, $params){
        return $this->form->redactorGroup(
            $this->model,
            $field,
            array(
                'widgetOptions' => isset($params['widgetOptions']) ? $params['widgetOptions'] : array(),
            )
        );
    }

    /**
    *@param string $field the model column name
    *@param string $params forms configuration parameters
    *@return html5EditorGroup
    */
    public function html5Editor($field, $params){
        return $this->form->html5EditorGroup(
            $this->model,
            $field,
            array(
                'widgetOptions' => isset($params['widgetOptions']) ? $params['widgetOptions'] : array(),
            )
        );
    }

    /**
    *@param string $field the model column name
    *@param string $params forms configuration parameters
    *@return ckEditorGroup
    */
    public function ckEditor($field, $params){
        return $this->form->ckEditorGroup(
            $this->model,
            $field,
            array(
                'widgetOptions' => isset($params['widgetOptions']) ? $params['widgetOptions'] : array(),
            )
        );
    }

    /**
    *@param string $field the model column name
    *@param string $params forms configuration parameters
    *@return markdownEditorGroup
    */
    public function markdownEditor($field, $params){
        return $this->form->markdownEditorGroup(
            $this->model,
            $field,
            array(
                'widgetOptions' => isset($params['widgetOptions']) ? $params['widgetOptions'] : array(),
            )
        );
    }

    /**
    *@param string $field the model column name
    *@param string $params forms configuration parameters
    *@return switchGroup
    */
    public function toggle($field, $params){
        return $this->form->switchGroup($this->model, $field,
            array(
                'widgetOptions' => isset($params['widgetOptions']) ? $params['widgetOptions'] : array(),
            )
        );
    }

    /**
    *@param string $field the model column name
    *@param string $params forms configuration parameters
    *@return checkboxListGroup
    */
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

    /**
    *@param string $field the model column name
    *@param string $params forms configuration parameters
    *@return radioButtonListGroup
    */
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

    /**
    *@param string $field the model column name
    *@param string $params forms configuration parameters
    *@return radioButtonGroup
    */
    public function radio($field, $params){
        $htmlOptions = isset($params['htmlOptions']) ? $params['htmlOptions'] : array();
        return $this->form->radioButtonGroup($this->model, $field, $htmlOptions);
    }


    /**
    * Generate form from model form configuration
    *@param string $model the model class name
    *@param string $ctrl controller instance
    */
    public static function boosterForm($ctrl, $model){
        $render = self::instance();
        $model_name = get_class($model);
        $render->model = $model;
        $fields = YedOperation::getFormFieldsBySection($model_name);
        if(empty($ctrl->formId))
            $ctrl->formId = strtolower($model_name).'Form';

        $htmlOptions = array('class' => $ctrl->formClass);
        if($ctrl->isUpload)
            $htmlOptions['enctype'] = 'multipart/form-data';

        $render->form = $ctrl->beginWidget(
            'booster.widgets.TbActiveForm',
            array(
                'id' => $ctrl->formId,
                'type' => $ctrl->formType,
                'enableAjaxValidation'=>$ctrl->enableAjaxValidation,
                'htmlOptions' => $htmlOptions
            )
        );

        if(empty($fields)){
            echo '<p>No field defined</p>';
        }else{
            echo '<p class="help-block">Fields with <span class="required">*</span> are required.</p>';
            echo $render->form->errorSummary($render->model);
            for($i = 1; $i <= count($fields); $i++){
                if(!isset($fields[$i]))
                    continue;

                echo '<div class="row '.$ctrl->sectionClass.'">';
                if(isset($ctrl->sectionTitles[$i - 1]))
                    echo '<h3>'.$ctrl->sectionTitles[$i - 1].'</h3>';
                $count = 0;
                $sectionColumn = isset($ctrl->sectionColumns[$i - 1]) ? $ctrl->sectionColumns[$i - 1] : 1;
                $sectionColumnValue = $sectionColumn > 1 ? round(12/$sectionColumn) : 12;

                foreach ($fields[$i] as $key => $value) {
                    if(isset($value['type']) && method_exists($render, $value['type'])){
                        if($count == 0){
                            echo '<div class="col-md-'.$sectionColumnValue.'">';
                        }
                        echo $render->$value['type']($key, $value);
                        $count++;
                        if($count >= (count($fields[$i])/$sectionColumn)){
                            echo '</div>';
                            $count = 0;
                        }
                    }else{
                        Y::exception($value['type'].' is an invalid field type for YedRender');
                    }
                }
                if($count > 0){
                    echo '</div>';//close col-md if not closed
                }
                echo '</div>';
            }

            $render->submitFooter($ctrl);
        }


        $ctrl->endWidget();
    }

    /**
    * Generates submit button with wrapper
    *@param string $ctrl controller instance
    */
    public function submitFooter($ctrl){
        echo '<div class="row form-actions '.$ctrl->submitFooterClass.'" style="text-align:right">';
        $ctrl->widget('booster.widgets.TbButton', array(
            'buttonType'=>'submit',
            'context'=>'primary',
            'label'=>$this->model && $this->model->isNewRecord ? Y::getModule()->buttonLabelCreate : Y::getModule()->buttonLabelUpdate,
        ));
        echo '</div>';
    }

    /**
    * Renders button form element
    *@param string $model model for the form
    *@param string $type button type
    *@param string $context context of the button to be rendered
    */
    public static function button($model = null, $type = 'submit', $context = 'primary'){
        $ctrl = self::controller();
        $ctrl->widget('booster.widgets.TbButton', array(
            'buttonType'=>$type,
            'context'=>$context,
            'label'=>$model && $model->isNewRecord ? Y::getModule()->buttonLabelCreate : Y::getModule()->buttonLabelUpdate,
        ));
    }

    /**
    * Renders yiibooster badge widget
    *@param string $label value of the badge
    *@param string $context context of the badge to be rendered
    */
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

    /**
    * Renders yiibooster progress widget
    *@param string $percent value of the progress bar
    *@param string $context context of the badge to be rendered
    */
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

    /**
    * Renders yiibooster switch widget
    *@param string $name value access key
    *@param string $size available options null, 'mini', 'small', 'normal', 'large
    *@param string $onColor available options 'primary', 'info', 'success', 'warning', 'danger', 'default'
    *@param string $offColor available options 'primary', 'info', 'success', 'warning', 'danger', 'default'
    *@param string $context context of the badge to be rendered
    */
    public static function toggleAlone($name, $size = 'large', $onColor = 'success', $offColor='primary'){
        $ctrl = self::controller();
        $ctrl->widget(
            'booster.widgets.TbSwitch',
            array(
                'name' => $name,
                'options' => array(
                    'size' => $size,
                    'onColor' => $onColor,
                    'offColor' => $offColor,
                ),
            )
        );
    }

    /**
    * Renders yiibooster Menu widget
    *@param array $data the menu configuration
    */
    public static function menu($data){
        $ctrl = self::controller();
        $ctrl->widget( 'booster.widgets.TbMenu', array(
            'items' => $data,
            'htmlOptions' => array('class' => 'nav nav-list'),
        ));
    }


}

?>