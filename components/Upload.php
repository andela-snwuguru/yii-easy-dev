<?php

class Upload {


    public $path = 'uploads/images/';
    public $filename = '';
    public $error = 0;
    public $msg = '';
    public $model;
    public $selectedfile;


    public function Upload($model,$path = '') {
        $this->path = Yii::getPathOfAlias('webroot').'/uploads'.$path;
        @mkdir($this->path);
        $this->model = $model;

    }

    public  function getPath() {
        return $this->path;
    }

    public function getError() {
        return $this->error;
    }

    public function getMessage() {
        return $this->msg;
    }

    public function setPath($newpath) {
        $this->path = $newpath;
    }

    public function setFileName($name) {
        $this->filename = $name;
    }

    public function getFileName() {
      return  $this->filename;
    }


    public function selected($name = 'filename') {
            $selected_file = CUploadedFile::getInstance($this->model, $name);
            if (!empty($selected_file)){
                $this->selectedfile = $selected_file;
                return 1;
            }
        return 0;
    }


    public function store() {
        if (empty($this->selectedfile)) {
            $this->exception('No Selected File');
        }

        $ext = strtolower($this->selectedfile->extensionName);
        $filename = $this->filename;
        if(!strpos($filename,$ext)){
            $filename.='.'.$ext;
        }
        if(empty($this->filename))
            $filename = time().'-'.$this->selectedfile->name;

        if (!is_dir($this->path)) {
           @mkdir($this->path);
        }
        $path = $this->path . $filename;

        if ($this->selectedfile->saveAs($path)) {
            $this->msg = "Upload Successful";
            $this->filename =  $filename;
            return $path;
        } else {
            $this->msg = "Unable to upload specified file; please try again";
            $this->error = 1;
            return 0;
        }

    }

    public function exception($msg) {
        throw new CHttpException(404, $msg);
    }

}