<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace color520\uploadToOss;

use OssManager;
use Yii;
use yii\base\Action;
use yii\web\UploadedFile;
use yii\helpers\Json;


class Upload extends Action
{
    public $config = [

    ];
    public $action;
    public $callback;
    /** @var \OssManager oss_article */
    private $oss_article;

    public function init()
    {
        include_once(dirname(__FILE__).'/OssManager.php');
        Yii::$app->request->enableCsrfValidation = false;
        Yii::$app->request->enableCookieValidation = true;
        $this->config = array_merge([
            'uploadDir' => date('Y/m/d'),
            'webroot'=>Yii::getAlias('@webroot'),
        ],$this->config);
        $this->oss_article = new OssManager($this->config['bucket'], $this->config['access_id'], $this->config['access_key'], $this->config['end_point']);
        parent::init();
    }

    /**
     * Runs the action.
     */
    public function run()
    {
        $action = Yii::$app->getRequest()->get('action', null);
        switch ($action) {
            case 'uploadfile':
                $result = $this->fileUpload();
                break;
            default:
                $result = ['code' => '1'];
                break;
        }
        return $result;
    }

    protected function fileUpload($config = [])
    {
        $file = UploadedFile::getInstanceByName('file');

        $serverpath = $file->tempName;
        chmod($serverpath, 0777);
        $osspath = md5(uniqid(mt_rand(), true)).'.'.$file->extension;
        $osspath =  $this->config['uploadDir']."/".$osspath;
        $ret = $this->oss_article->upload_by_multi_part($serverpath, $osspath);
        if($ret){
            //$url = "http://".$this->oss_article->getBucket().".".$this->oss_article->getEndPoint()."/".$osspath;
            $url = "http://".$this->config['server_name']."/".$osspath;
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['code' => '0', 'url' => $url, "size" =>$file->size, "original" => $file->name, "name" => $file->name];
        }else{
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['code' => '1'];
        }
    }

    public static function makeDir($dir)
    {
        if (!is_dir($dir)) {
            if (!is_dir(dirname($dir))) {
                self::makeDir(dirname($dir));
                mkdir($dir, 0777);
            } else {
                mkdir($dir, 0777);
            }
        }
    }
}
