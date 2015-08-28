<?php
/**
 * Created by PhpStorm.
 * User: lijungang
 * Date: 15-8-26
 * Time: 下午6:33
 */
require_once(dirname(__FILE__)."/oss/conf.inc.php");
require_once(dirname(__FILE__)."/oss/sdk.class.php");

class OssManager{
    private $ossobj;
    private $ossbuket;
    private $ossid;
    private $osskey;
    private $endpoint;

    function __construct($ossbucket, $ossid, $osskey, $endpoint) {
        $this->ossbuket = $ossbucket;
        $this->ossid = $ossid;
        $this->osskey = $osskey;
        $this->endpoint = $endpoint;
        $this->ossobj = new ALIOSS($this->ossid, $this->osskey, $this->endpoint);
    }

    function getBucket(){
        return $this->ossbuket;
    }

    function getEndPoint(){
        return $this->endpoint;
    }

    function is_create_dir($dir){
        $ret_code = $this->ossobj->create_object_dir($this->ossbuket, $dir);
        $ret = $ret_code->isOK();
        return $ret == true ? true : false;
    }

    function upload_by_multi_part($serverpath, $osspath) {
        $isok = true;
        $options = array(
            'fileUpload' => $serverpath,
            'partSize' => 5242880,
        );
//        $options = array(
//            ALIOSS::OSS_HEADERS => array(
//                'Content-Type' => 'image/jpeg',
//            ),
//        );
        $is_object_exist = $this->ossobj->is_object_exist($this->ossbuket, $osspath);
        $isexist = $is_object_exist->isOK();
        if (!$isexist) {
            $response = $this->ossobj->create_mpu_object($this->ossbuket, $osspath, $options);
            //$response = $this->ossobj->upload_file_by_file($this->ossbuket, $osspath, $serverpath, $options);
            $isok = $response->isOK();
            if (!$isok) {
                $this->_format($response);
                die();
            }
        }
        return $isok;
    }

    function delete_object($osspath) {
        $isok = true;
        $isexist = $this->ossobj->is_object_exist($this->ossbuket, $osspath);
        if ($isexist) {
            $response = $this->ossobj->delete_object($this->ossbuket, $osspath);
            $isok = $response->isOK();
            if (!$isok) {
                $this->_format($response);
                die();
            }
        }
        return $isok;
    }

    function is_object_exist($osspath) {
        $response = $this->ossobj->is_object_exist($this->ossbuket, $osspath);
        $isok = $response->isOK();
        if (!$isok) {
            $this->_format($response);
            die();
        }
        return $isok;
    }

    function _format($response) {
        echo '|———————–Start—————————————————————————————————' . "<br>";
        echo '|-Status:' . $response->status . "<br>";
        echo '|-Body:' . "<br>";
        echo $response->body . "<br>";
        echo "|-Header:<br>";
        print_r($response->header);
        echo '———————–End—————————————————————————————————–' . "<br><br>";
    }
}