<?php
namespace color520\uploadToOss;

use Yii;

class Assets extends \yii\web\AssetBundle
{
    public $depends = ['yii\web\JqueryAsset'];

    private function getJs()
    {
        return [
            'js/upload.js',
        ];
    }

    private function getCss(){
        return [
            'css/upload.css',
        ];
    }

    public function init()
    {
        $this->sourcePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
        if (empty($this->js)) {
            $this->js = $this->getJs();
        }
        if (empty($this->css)){
            $this->css = $this->getCss();
        }
        parent::init();
    }
}