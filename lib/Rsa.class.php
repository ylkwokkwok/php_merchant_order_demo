<?php
/**
 * 签名验证,加密解密
 * 需要开启openssl
 * ============================================================================
 * API说明
 * setPriKey 读取私钥
 * setPubKey 读取公钥
 * getPriKey 返回私钥
 * getPubKey 返回公钥
 * getSslSign 签名
 * getSslVerify 签名验证
 * priEncrypt 私钥加密
 * pubDecrypt 公钥解密
 * getDebugInfo 返回debug信息
 * isContinue 返回当前是否有错
 * ============================================================================
 */
 
$file  = 'log.txt';
class Rsa {
    //公钥
    var $pubkey;
    //私钥
    var $prikey;
    
    //debug信息
    var $debugInfo;
    
    //字符串分块长度
    var $blockSize = 128;
    
    //是否继续
    var $continue = true;
    /**
     * 读取私钥
     * @param $path  路径
     * @param $pass 密码
     * @return 
     */
    function setPriKey($path, $pass){
        $certs = array();
        $pkcs12 = file_get_contents($path);
        $flag=openssl_pkcs12_read( $pkcs12, $certs, $pass);
        $this->prikey = openssl_get_privatekey($certs['pkey']);
        $this->pubkey = openssl_pkey_get_public($certs['cert']);
         
    }
    /**
     * 读取公钥
     * @param $path  路径
     * @return 
     */
    function setPubKey($path){
        $content = file_get_contents($path);
        $this->pubkey = openssl_pkey_get_public($content);
    }
    function getPriKey(){
        return $this->prikey;
    }
    function getPubKey(){
        return $this->pubkey;
    }
    
    /**
     * 签名
     * @param $str 明码
     * @return 签名后的数据
     *  OPENSSL_ALGO_SHA1
     *  OPENSSL_ALGO_MD5
     *  OPENSSL_ALGO_MD4
     *  OPENSSL_ALGO_MD2     
     */
    function getSslSign($str){
        $out = "";
        if (openssl_sign($str, $out, $this->getPriKey(),OPENSSL_ALGO_SHA1)){
            $this->debugInfo = "签名成功";
         
            
            return base64_encode($out);
        }
        $this->continue = false;
        $this->debugInfo = "签名失败";
        return null;
    }
    
    /**
     * 签名验证
     * @param  $str  明码
     * @param  $sig  签名过的数据
     * @return type 
     */
    function getSslVerify($str, $sig){
        $sig = base64_decode($sig);
        if (openssl_verify($str, $sig, $this->getPubKey()) == 1){
            $this->debugInfo = "签名验证成功";
            return true;
        }
        $this->continue = false;
        $this->debugInfo = "签名验证失败";
        return false;

    }
    /**
     * 私钥加密
     * @param $str
     * @return 加密后的数据 
     */
    function priEncrypt($str)
    {
        $return = "";
        $len = $this->blockSize;
        for($i=0;$i<strlen($str);$i+=$len){
            $cryp = "";
            if(openssl_private_encrypt(substr($str, $i, $len), $cryp,  $this->getPriKey())){
                $return .= $cryp;
            }else{
                $return  = "";
                break;
            }
        }
        if(empty ($return)){
            $this->continue = false;
            $this->debugInfo = "加密失败";
            return "";  
        }
        $this->debugInfo = "加密成功";
        return base64_encode($return);
    }
    
    /**
     * 公钥解密
     * @param  $str
     * @return 返回解密后的数据 
     */
    function pubDecrypt($str){
        $return = "";
        $str = base64_decode($str);
        $len = $this->blockSize*2;
        for($i=0;$i<strlen($str);$i+=$len){
            $sour = "";
            if(openssl_public_decrypt(substr($str, $i, $len), $sour, $this->getPubKey())){
                $return .= $sour;
            }else{
                $return = "";
                break;
            }
        }
        if(empty ($return)){
            $this->continue = false;
            $this->debugInfo = "解密失败";
            return "";  
        }
        $this->debugInfo = "解密成功";
        return $return;
    }
    
    //返回信息
    function getDebugInfo(){
        return $this->debugInfo."<br/>"; 
    }
    //是否继续
    function isContinue(){
        return $this->continue;
    }
    
    
    function getSslSignByBat($keyFile,$password,$str){
    	 if(PATH_SEPARATOR==':'){//Linux
        $out=shell_exec(dirname(dirname(__FILE__))."/signature.sh $keyFile $password  $str"); 
       }else{
       	$out=shell_exec(dirname(dirname(__FILE__))."/signature.bat $keyFile $password  $str"); 
       	}
        return $out;
    }
    
    function getSslVerifyByBat($str, $sig){
    	 if(PATH_SEPARATOR==':'){//Linux
          $out=shell_exec(dirname(dirname(__FILE__))."/verify.sh $str $sig"); 
       }else{
       	 $out=shell_exec(dirname(dirname(__FILE__))."/verify.bat $str $sig"); 
       }
       	
        return $out;

    }
}

?>
