<?php
return array(
  '1' => array(
    'merchantId' => '11111100000000',      //商户号
    'signType' =>'CFCA',                   //加密类型(MD5,CFCA或ZJCA)
    'merchantKey' => '12345678',       //MD5的密钥(加密方式为MD5时必输)
    'keyFile' => 'cer/macaupasstest.pfx',       //商户证书私钥文件(加密方式为CFCA或JZCA时必输)
    'password' => '12345678',               //商户证书私钥密码(加密方式为CFCA或JZCA时必输)
  ),
);
?>
