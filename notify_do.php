<?php
header("Content-Type: text/html; charset=utf-8");

$file  = 'log.txt';//要写入文件的文件名（可以是任意文件名），如果文件不存在，将会创建一个
/*
付款通知商城结果
*/
require 'lib/Rsa.class.php';
require 'lib/Processing.class.php';
require 'lib/payInfo.class.php';

$RSA = new Rsa();
$PIF = new payInfo();
$PIF->init();

    

	
	 $config = require('config.php');
	 $signType = $config[1]['signType'];
	 
	 $versionId="";
	 $merchantId="";
	 $orderId="";
	 
	    $PIF->setParameter("versionId",    $_POST['versionId']); 
      $PIF->setParameter("merchantId",    $_POST['merchantId']);   //商户编号
      $PIF->setParameter("orderId",      $_POST['orderId']);      //商品订单号
      $PIF->setParameter("settleDate",   $_POST['settleDate']);   //对账日期
      $PIF->setParameter("completeDate",   $_POST['completeDate']);
      $PIF->setParameter("status",       $_POST['status']);       //账单状态
      $PIF->setParameter("notifyTyp",    $_POST['notifyTyp']);    //通知类型
      $PIF->setParameter("payOrdNo",     $_POST['payOrdNo']);     //支付系统交易号
      $PIF->setParameter("orderAmt",     $_POST['orderAmt']);     //订单总金额
      $PIF->setParameter("notifyUrl",     $_POST['notifyUrl']);
      $PIF->setParameter("signType",     $_POST['signType']);     //签名方式
      //$PIF->setParameter("signature",    $_POST['signature']);    //签名信息
    
    $paraArr = array("versionId","merchantId","orderId","settleDate","completeDate","status","notifyTyp","payOrdNo","orderAmt","notifyUrl","signType");
    
    if($signType=='MD5'){ //MD5加密
    	
    	  //接收数据
    	  
    	
    	 $merchantId=$_POST['merchantId'];
    	 $orderId=$_POST['orderId'];
 
    
      //验签
      $signature = $PIF->getParameter("signature");          //获取签名信息
      $PIF->setParameter("signature", "");                   //清空收到报文中的签名信息
      $signType=$PIF->getParameter("signType");
    	
    	
    	
    	
        //对指定字段进行加签
       $config = require('config.php');
       $merchantKey = $config[1]['merchantKey'];
       $md5Str = $PIF->getMD5($paraArr,$merchantKey);
       
      
       if($md5Str==$signature){
       	$checkFlag=true;
       }else{
      	$checkFlag=false;
       }
       
     }else if($signType=='CFCA' || $signType=='ZJCA'){
         $source=$PIF->iteratorArr($paraArr);
         file_put_contents($file, "原串:$source \r\n",FILE_APPEND);
         $sourcencode=urlencode($source);
         file_put_contents($file, "原串:$sourcencode \r\n",FILE_APPEND); 
     	   //$signature=urlencode($_POST['signature']);   
     	   $signature=$_POST['signature'];     
     	   file_put_contents($file, "验签串:$signature \r\n",FILE_APPEND);             
         $checkFlag = $RSA->getSslVerifyByBat( $sourcencode, $signature);    //验签
         file_put_contents($file, "验签结果:$checkFlag \r\n",FILE_APPEND);         
       
  	 }
  	
    
   
    if(trim($checkFlag)=="true") {
        /***********处理***********/
        $PIF->status = "1";  
        $repdata= "success";
        
    
        /***********处理***********/
    } else {
        $PIF->status = "2";       //消息处理状态
        $repdata= "fail";  //接收回执消息处理失败原因
    }




echo $repdata;

?>