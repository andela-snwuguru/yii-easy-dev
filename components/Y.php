<?php

class Y
{

	public static function url($alias,$check_session = false){
        $url = $alias;
        if(self::session('redirect_to') && $check_session){
            $url = self::session('redirect_to');
            self::removeSession('redirect_to');
        }else{
            self::removeSession('redirect_to');
        }
        return Yii::app()->createUrl($url);
    }

    public static function userId() {
        return Yii::app()->user->id ? Yii::app()->user->id : 0;
    }

    public static function flashes(){
        foreach (Yii::app()->user->getFlashes() as $key => $message) {
            if ($message != '') {
                echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";
            }
        }
    }

    public static function msg($key,$message){
        Yii::app()->user->setFlash($key, ucfirst($message));
    }

    public static function err($msg){
        Yii::app()->user->setFlash('danger ' . rand(),ucfirst($msg));
    }

    public static function info($msg){
        Yii::app()->user->setFlash('info ' . rand(), ucfirst($msg));
    }

    public static function ok($msg){
        Yii::app()->user->setFlash('success ' . rand(), ucfirst($msg));
    }

    public static function ip() {
        return Yii::app()->request ? Yii::app()->request->getUserHostAddress() : '';
    }

    static function getRoles($array = false) {
        $roles = explode(' ',trim(yii::app()->user->getroles()));
        return $array ? $roles : implode(',',$roles);
    }

    public static function model($model_name) {
        return CActiveRecord::model($model_name);
    }

    public static function isAdmin() {
        return Yii::app()->user->isAdmin();
    }

    public static function themeBase() {
        return Yii::app()->theme->baseUrl;
    }

    public static function base($url='') {
        return Yii::app()->baseUrl.$url;
    }

    /*
     * loads and return a model record from a given condition
     * it throws exception if there is no data
     */
    public static function valid($model_name, $condition='',$exception = true) {
        $model =  Y::model($model_name)->find($condition);
        if(empty($model))
            if($exception)
                Y::exception('No data found');

        return $model;
    }

    static function currentUser($userModel = 'YumUser', $id = 0) {
        if (!$id)
            $id = Y::userId();
        $model = Y::model($userModel)->find('id = ' . $id);
        if (!empty($model)) {
            return $model;
        }
        return null;
    }


    ###################################
    ###  Rights helper functions

    public static function userCan($operation) {
        return Yii::app()->user->checkAccess($operation);
    }
    ########################################################

    public static function datePicker($ctrl,$name,$modelname=''){

        $params = array(
            'name'=>$name,
            'options'=>array(
                'showAnim'=>'fold',
                'changeMonth'=> 'true',
                'changeYear'=> 'true'
            ),
        );

        if(!empty($modelname))
            $params['name']=$modelname.'['.$name.']';

        $ctrl->widget('zii.widgets.jui.CJuiDatePicker',$params);
    }

    public static function listData($model,$key,$title,$cond=''){
        $params = array(
            'condition'=>$cond
        );
        $list = array(''=>'N/A');
        $data = $model::model()->findAll($params);
        if(!empty($data)){
            foreach($data as $row){
                $list[$row->$key] = $row->$title;
            }
        }

        return $list;
    }

    public static function arrayData($model,$title,$cond=''){
        $params = array(
            'condition'=>$cond
        );
        $list = array();
        $data = $model::model()->findAll($params);
        if(!empty($data)){
            foreach($data as $row){
                $list[] = $row->$title;
            }
        }

        return $list;
    }

    public static function yumRules(){
        $controller = Yii::app()->controller->id;
        $rules = array();
        $perms = YumAction::model()->findAll('title LIKE "'.$controller.'_%"');
        if(!empty($perms)){
            foreach($perms as $perm){
               $rules_data = array('allow');
               $args = explode('_',strtolower($perm->title));
                if($args[0] != $controller)
                    continue;

               $rules_data['actions'] = array($args[1]);
                if(!empty($perms->comment) && ($perm->comment == '*' || $perm->comment == '@')){
                    $rules_data['users'] = array($perm->comment);
                }else{
                    $rules_data['expression'] = "Y::can('".$perm->title."')";
                }
                $rules[] = $rules_data;
            }
        }


        $rules[]  = array('allow',  // deny all other users
                    'users'=>array('@'),
                    'expression'=>'Y::isAdmin()'
                );

        $rules[]  = array('deny',  // deny all other users
                    'users'=>array('*'),
                );
        return $rules;
    }


    public static function can($action){
        return Yii::app()->user->can($action) || Y::isAdmin();
    }

    public static function is($role,$strict = false){
        if($strict){
            return trim(Yii::app()->user->getroles()) == $role;
        }
        return trim(Yii::app()->user->getroles()) == $role || Y::isAdmin();
    }

   static function cs() {
        return app()->clientScript;
    }


    static function getRParam($key, $default=null) {
        return Yii::app()->request->getParam($key, $default);
    }


    static function getPostParam($key, $default=null) {
        return Yii::app()->request->getPost($key, $default);
    }

    static function root(){
        return Yii::getPathOfAlias('webroot');
    }

    static function module($name = ''){
        if(!empty($name))
            return  array_key_exists($name, Yii::app()->modules);

        return false;
    }

    static function getModule($name = 'yed'){
        return Yii::app()->getModule($name);
    }

    static function getRequest() {
        $cont = array();
        if (!empty($_REQUEST)) {
            $method = isset($_POST) && !empty($_POST) ? 'POST' : 'GET';
            $cont['method'] = $method;
            foreach($_REQUEST as $key=>$val){
                $cont[$key] = $val;
            }
        }

        return json_encode($cont);
    }

    static function tableExist($table_name){
       return !Yii::app()->db->schema->getTable($table_name, true) === null;
    }

    static function createTable($table_name,$columns){
        Yii::app()->db->createCommand()->createTable('access_log', $columns);
    }

    static function query($sql){
        $command = Yii::app()->db->createCommand($sql);
        return $command->queryAll();
    }

    static function operation($flag){
        $controller_id = Yii::app()->controller->id;
        $actionid = Yii::app()->controller->action->id;
        return Y::can($controller_id.'_'.$actionid) && $flag;
    }

    static function controller($name){
        return strtolower(Yii::app()->controller->id) == $name;
    }

    static function action($controller,$name){
        return self::controller($controller) && strtolower(Yii::app()->controller->action->id) == $name;
    }


    static function upload($subpath,$model,$name_suffix = 'FA',$fieldname = 'image'){
        /* Yii::import('application.vendors.wideimage.lib.WideImage');
         $folder=Yii::getPathOfAlias('webroot').'/uploads/user1/';// folder for uploaded files
         // WideImage::load($folder.'anim.jpg')->resize(300, 200, 'outside', 'down')->saveToFile($folder.'anim7.jpg');
         WideImage::load($folder.'anim.jpg')->crop('right', 'center', 400, 450)->saveToFile($folder.'crop1.jpg');*/
        $file_name = preg_replace('/\s+/', '_', get_class($model).'_'.$name_suffix);
        $filepath = '/uploads'.$subpath;
        $upload = new Upload($model,$subpath);
        if($upload->selected($fieldname)){
            $upload->setFileName($file_name);
            $upload->store();
            if(!$upload->getError()){
                return $filepath.$upload->getFileName();
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    static function deleteFile($path){
        $full_path = Yii::getPathOfAlias('webroot').$path;
        @unlink($full_path);
    }


    static function exception($msg = 'Invalid Page Access'){
        throw new CHttpException(404,$msg);
    }

    static function cleanString($string) {
        $string = str_replace(' ', '', $string); // Replaces all spaces.
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }


    static function session($key,$value = ''){
        if($value){
            Yii::app()->session[$key] = $value;
            Yii::app()->session[$key.'_time'] = time();
        }else{
            if(!empty(Yii::app()->session[$key.'_time'])){
                $diff = time() - Yii::app()->session[$key.'_time'];
                $day = floor($diff/(60*60*24));
                if($day > 0){
                    unset(Yii::app()->session[$key],Yii::app()->session[$key.'_time']);
                }
            }else{
                unset(Yii::app()->session[$key],Yii::app()->session[$key.'_time']);
            }
            return Yii::app()->session[$key];
        }

    }

    static function removeSession($keys){
        if(!is_array($keys)){
            unset(Yii::app()->session[$keys]);
            return 1;
        }

        foreach($keys as $key)
            unset(Yii::app()->session[$key]);

    }

    static function domain($compare_domain = ''){
        $domain = str_ireplace('www.','',$_SERVER['HTTP_HOST']);
        if(!empty($compare_domain))
            return $compare_domain == $domain;

        return $domain;
    }

    static function isInUrl($string){
        return strpos($_SERVER['REQUEST_URI'],$string);
    }

    static function domainNot($domain){
        return self::domain() != $domain ;
    }

    static function getDateCondition($colname,$date_from = '', $date_to = '',$format = true,$date_format = 'Y-m-d H:m:i'){
        $cond = '';
        if($format){
            if(!empty($date_from))
                $cond .= (!empty($cond) ? ' AND ': '').$colname.' >= "'.date($date_format,$date_from).'"';

            if(!empty($date_to))
                $cond .= (!empty($cond) ? ' AND ': '').$colname.' <= "'.date($date_format,($date_to +(60*60*12))).'"';

        }else{
            if(!empty($date_from))
                $cond .= (!empty($cond) ? ' AND ': '').$colname.' >= "'.$date_from.'"';

            if(!empty($date_to)){
                $cond .= (!empty($cond) ? ' AND ': '').$colname.' <= "'.($date_to +(60*60*12)).'"';
            }

        }

        return $cond;
    }
}