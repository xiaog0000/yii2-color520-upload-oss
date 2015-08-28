<?php

namespace color520\uploadToOss;
use Yii;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;
use yii\helpers\Url;
/**
 * This is just an example.
 */
class UploadOss extends InputWidget
{
    public $id;

    /**
     * UE 参数
     * @var array
     */
    public $jsOptions = [];


    public $readyEvent;


    public function init(){
        parent::init();

        if (empty($this->id)) {
            $this->id = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        }
        if (empty($this->name)) {
            $this->name = $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->id;
        }
        if (empty($this->value) && $this->hasModel() && $this->model->hasAttribute($this->attribute)) {
            $this->value = $this->model->getAttribute($this->attribute);
        }

        $options = [
            'id' => $this->id,
            'class' => 'color520UploadClass'
        ];
        $this->options = array_merge($options,$this->options);
    }

    public function run()
    {
        Assets::register($this->view);
        $token= Yii::$app->request->getCsrfToken();
        $tokenName= Yii::$app->request->csrfParam;
        $jsOptions = [
            'serverUrl' => Url::to(['upload']),
            'class'=> 'color520UploadClass',
            $tokenName => $token
        ];
        $this->jsOptions = array_merge($jsOptions,$this->jsOptions);
        //$this->registerScripts();
        $id = $this->id.'_upload';
        $message = '<table><tr><td><input style="width: 400px;" type="text" name='.$this->name.' id='.$this->id.' value='.$this->value.'></td>';
        $message .= '<td><input type="file" class = "colorUpload" name="upload" id = '.$id.' data-id= '.$id.' data-server='.Url::to(['upload']).' ></td>';
        $message .= '<td><input type="button" class = "colorUploadButton" value="上传"></td></tr></table>';
        $message .= '<img src='.$this->value.'@!w480'.' />';
        $content = Html::tag('div', $message, $this->options);
        return $content;
    }

    public function registerScripts()
    {
        $jsonOptions = Json::encode($this->jsOptions);
        $script = "var up = UP.getUpload('{$this->id}', " . $jsonOptions . ");";
        $this->view->registerJs($script, View::POS_READY);
    }
}
