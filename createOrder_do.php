<?php
ob_start();
require 'lib/Rsa.class.php';
require 'lib/Processing.class.php';
require 'lib/creOrderForm.class.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh" xml:lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>建立商品订单示例</title>
<!--link href="styles/style.css" rel="stylesheet" type="text/css" /-->
</head>

<body>
<?php




$nof = new creOrderForm();
$nof->init();
//接收表单数据
if(isset($_POST['submit'])) {
    $config = require('config.php');
    $merchantId = $config[1]['merchantId'];
    $signType = $config[1]['signType'];
    $keyFile = $config[1]['keyFile'];
    $password = $config[1]['password'];
    $merchantKey = $config[1]['merchantKey'];
    $nof->setParameter("merchantId",   $merchantId);
    $nof->setParameter("prodCode",     $_POST['prodCode']);
    $nof->setParameter("orderId",      $_POST['orderId']);
    $nof->setParameter("orderAmount",  $_POST['orderAmount']);
    $nof->setParameter("orderDate",    $_POST['orderDate']);
    $nof->setParameter("bizType",    "1");
    $nof->setParameter("retUrl",       $_POST['retUrl']);
    $nof->setParameter("returnUrl",    $_POST['returnUrl']);
    $nof->setParameter("prdName",      strtoupper(bin2hex($_POST['prdName'])));  
    $nof->setParameter("prdDesc",      strtoupper(bin2hex($_POST['prdDesc'])));
   
  
    $nof->setParameter("signType",     $signType);
    
    if($signType=='MD5'){ //MD5加密
        //设置参与MD5加签的字段
        $paraArr = array("merchantId","prodCode","orderId","orderAmount","orderDate","retUrl","returnUrl","prdName","prdDesc","signType");
        //对指定字段进行加签
        $md5Str = $nof->getMD5($paraArr,$merchantKey);
        $nof->setParameter("signature", $md5Str);//添加签名
    } else if($signType=='CFCA' || $signType=='ZJCA'){ //证书加签
        //组织数据
        $data = $nof->createData();
        
        $rsa = new Rsa();
        
        $cov = iconv("UTF-8","GB2312",$data);
        $encodata=urlencode($cov);
        file_put_contents($file, "加签数据:".$cov."\r\n",FILE_APPEND);
        file_put_contents($file, "加签数据:".$encodata."\r\n",FILE_APPEND);
        $signmessage = $rsa->getSslSignByBat($keyFile,$password,$encodata);//签名
        if(!$rsa->isContinue()) {exit("签名失败");}
       
        file_put_contents($file, "签名数据：".$signmessage."\r\n",FILE_APPEND);
        $nof->setParameter("signature", $signmessage);//添加签名
    } else {
        exit("签名类型有误");
    }
    
    //签名重新组织数据，准备与服务器通讯
    $data = $nof->createPostData();
    
    //和服务器通讯，发送表单
    $nof->sendPay($data);
} else {
    echo '表单接收失败';
}
?>
</body>
</html>