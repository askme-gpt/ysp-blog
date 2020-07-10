<?php
use Inhere\Validate\FieldValidation;
use Inhere\Validate\Validation;
use Yaf\Controller_Abstract as Controller;

class WeixinController extends Controller
{

    public function checkTokenAction()
    {
        // /weixin/checkToken?signature=46845c6585bf19eb139150b5b4820c246af3c899&echostr=5219002607666707671&timestamp=1594395719&nonce=2120896721
        $signature = $this->getRequest()->getQuery('signature');
        $timestamp = $this->getRequest()->getQuery('timestamp');
        $nonce = $this->getRequest()->getQuery('nonce');
        $echostr = $this->getRequest()->getQuery('echostr');
        if ($this->checkSignature($signature, $timestamp, $nonce)) {
            die($echostr);
        } else {
            echo false;
        }
    }

    private function checkSignature($signature, $timestamp, $nonce)
    {
        $token = '15xIUt0zXeUM0rDZg'; //这里写你在微信公众平台里面填写的token
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

}
