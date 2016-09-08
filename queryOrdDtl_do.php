<?php
ob_start();
require 'lib/Rsa.class.php';
require 'lib/Processing.class.php';
require 'lib/queryOrdDtlForm.class.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh" xml:lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>商品订单明细查询</title>
</head>

<body>
<?php
$qry = new queryOrdDtlForm();
$qry->init();
//接收表单数据
if(isset($_POST['submit'])) {
    $config = require('config.php');
    $merchantId = $config[1]['merchantId'];
    $signType = $config[1]['signType'];
    $keyFile = $config[1]['keyFile'];
    $password = $config[1]['password'];
    $merchantKey = $config[1]['merchantKey'];
    $qry->setParameter("merchantId",    $merchantId);
    $qry->setParameter("queryType",    $_POST['queryType']);
    $qry->setParameter("orderId",       $_POST['orderId']);
    $qry->setParameter("signType",      $signType);

    //组织数据
    
    if($signType=='MD5'){ //MD5加密
      
    } else if($signType=='CFCA' || $signType=='ZJCA') {//证书加签
        $data = $qry->createData();
        //签名start
        $rsa = new Rsa();
        
        $encodata=urlencode($data);
        file_put_contents($file, "加签数据:".$data."\r\n",FILE_APPEND);
        
        $signmessage =$rsa->getSslSignByBat($keyFile,$password,$encodata);//签名
        
        file_put_contents($file, "签名：".$signmessage."\r\n",FILE_APPEND);
        
        if(!$rsa->isContinue()) {exit("签名失败");}
        $qry->setParameter("signature", $signmessage);//添加签名
    } else {
        exit("签名类型有误");
    }
    //签名end
    
    //签名重新组织数据，准备与服务器通讯
    $data = $qry->createPostData();
    
    //和服务器通讯，发送表单，接收返回的XML信息
    $xml = $qry->getServerData($data);
    file_put_contents($file, "返回：".$xml."\r\n",FILE_APPEND);
    echo $xml;
} else {
    echo '表单接收失败';
}
?>
</body>
</html>