<?php
/**
 * 数据处理类
 * ============================================================================
 * API说明
 * init  初始化数据
 * setParameter  设置参数
 * getParameter 获取参数
 * createData  组织数据
 * createPostData  与createData功能相同，不之处是对值进行了URL编码，方便用POST发送
 * getServerData 和服务器通信
 * loadXml  解析xml
 * createCheckData 组织从服务器收到的数据，为验签做准备
 * getDebugInfo  获取debug信息
 * ============================================================================
 */
class Processing {
    
    //参数
    var $parameters;
    
    //debug信息
    var $debugInfo;
    
    //网关url
    var $gateUrl;
    
    //与服务器通讯后的状态
    var $httpRspStat;
    
    //服务器地址
   // var $svrIp = "payment.tangdi.com.cn:8091";
   // var $svrIp = "59.41.60.154:8090";
     var $svrIp = "https://www.56zhifu.com";
    
    
    function __construct() {
        $this->parameters = array();
        $this->debugInfo = "";
       // echo 'txnCod:'.$_POST['txnCod'].'<br/>';
       if(empty($_POST['txnCod'])){
        $txnCod='';
       }else{
      	$txnCod=$_POST['txnCod'];
      	}
        //根据txnCod设置url
        if($txnCod=='MerchantmerchantPay'){
            $this->gateUrl = $this->svrIp."/user/MerchantmerchantPay.do";
        }else if ($txnCod=='MerchantmerchantOnlinePay'){
            $this->gateUrl = $this->svrIp."/user/MerchantmerchantOnlinePay.do";
        }else if ($txnCod=='MerchantmerchantRefund'){
            $this->gateUrl = $this->svrIp."/user/MerchantmerchantRefund.do";
        } else if ($txnCod=='MerchantmerchantConfirmPay'){
            $this->gateUrl = $this->svrIp."/user/MerchantmerchantConfirmPay.do";
        } else if ($txnCod=='MerchantmerchantTransQuery'){
            $this->gateUrl = $this->svrIp."/user/MerchantmerchantTransQuery.do";
        } else {
            //exit("txnCod设置有误");
        }
        $httpRspStat = false;
    }
    
    function setUrl($url) {
        $this->gateUrl = $url;
    }

    //设置参数
    function setParameter($key, $value){
        $this->parameters[$key] = $value;
    }
    
    //获取参数
    function getParameter($key){
        return $this->parameters[$key];
    }
    
    //组织数据
    function createData() {
        $data = "";
        foreach($this->parameters as $key => $value) {
            $data .= '&'.$key.'='.$value;
        }
        $data = substr($data, 1, (strlen($data)-1));
        return $data;
    }
    
    //组织数据(与createData功能相同，不之处是对值进行了URL编码，方便用POST发送)
    function createPostData() {
        $data = "";
        foreach($this->parameters as $key => $value) {
            $data .= '&'.$key.'='.rawurlencode($value);
        }
        $data = substr($data, 1, (strlen($data)-1));
        return $data;
    }
    
    //组建XML数据
    function createXml(){
        $XML = "";
        $XML  =  '<?xml version="1.0" encoding="UTF-8"?>';
        $XML .= '<merchantRes ';
        foreach($this->parameters as $key=>$value){
            $XML .= $key.'="'.$value.'" ';
        }
        $XML .= ' />';

        $this->debugInfo = $XML;
        return $XML;
    }
    
    function sendPay($data) {
        $redUrl = 'http://'.$this->gateUrl;
        if(empty($data)) {
            $this->debugInfo = "数据为空";
            return "";
        } else {
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: '.$redUrl.'?'.$data);
        }
    }
    
    /**
     * 和服务器通讯
     * @param  $data
     * @return 服务器返回信息 
     */
    function getServerData($data) {
    	$file  = 'log.txt';
    	
        if(empty($data)) {
            $this->debugInfo = "数据为空";
            return "";
        } else {
            $returnData = "";
            $limit = 0;
            $timeout = 25;
            $post = $data;
            $matches = parse_url($this->gateUrl);
            !isset($matches['host']) && $matches['host'] = '';
            !isset($matches['path']) && $matches['path'] = '';
            !isset($matches['query']) && $matches['query'] = '';
            !isset($matches['port']) && $matches['port'] = '';
            $host = $matches['host'];
            $path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
            $port = !empty($matches['port']) ? $matches['port'] : 443;
            
            $out  = "POST $path HTTP/1.1\r\n";
            $out .= "Accept: */*\r\n";
            $out .= "Accept-Language: zh-cn\r\n";
            $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
            $out .= "Host: $host\r\n";
            $out .= 'Content-Length: '.strlen($post)."\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Cache-Control: no-cache\r\n\r\n";
            $out .= $post;
/*test,remember to delete*/
//$out = date("Y-m-d H:i:s",time());
/*test,remember to delete*/
            file_put_contents($file, "host ".$host."\r\n",FILE_APPEND);
            file_put_contents($file, "port ".$port."\r\n",FILE_APPEND);
            file_put_contents($file, "out".$out."\r\n",FILE_APPEND);
            
            $fp = @fsockopen($host, $port, $errno, $errstr, $timeout);
           
            if(!$fp) {
                $this->debugInfo = "连接服务器失败";
                return "";
            } else {
            	  file_put_contents($file, "连接服务器成功"."\r\n",FILE_APPEND);
            	  
                stream_set_blocking($fp, true);
                stream_set_timeout($fp, $timeout);
                @fwrite($fp, $out);
                $status = stream_get_meta_data($fp);
                file_put_contents($file, "status ".$status['timed_out']."\r\n",FILE_APPEND);
                if(!$status['timed_out']) {
                    $headerLine = 1;
                    while (!feof($fp)) {
                        if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
                            break;
                        }
                    }
                    $stop = false;
                    file_put_contents($file, "stop ".$stop."\r\n",FILE_APPEND);
                    while(!feof($fp) && !$stop) {
                    	 file_put_contents($file, "data ".$data."\r\n",FILE_APPEND);
                        $data = @fread($fp, (($limit == 0 || $limit > 8192) ? 8192 : $limit));;
                        $returnData .= $data;
                        if($limit) {
                             $limit -= strlen($data);
                             $stop = $limit <= 0;
                        }
                    }
                }//if(!$status['timed_out'])
                @fclose($fp);
                //$this->debugInfo = "连接服务器成功";
                return $returnData;
            }//if(!$fp)
        }//if(empty($data))
    }//getServerData

    //解析xml
    function loadXml($str){
        $data = array();
        if($str!="") {
            $xml = @simplexml_load_string($str);
            if(isset($xml[0]) && $xml) {
                foreach($xml[0]->attributes() as $key => $value) {
                    $data[$key]=$value;
                }
                if(isset($xml[0]->order)) {
                    $count = isset($xml[0]) ? count($xml):0;
                    for($i=0;$i<$count;$i++) {
                        foreach($xml[0]->order[$i]->attributes() as $key => $value) {
                            $data[$key] = $value;
                        }
                        /*foreach($xml[0]->order[$i] as $key => $value) {
                            $data[$key] = $value;
                        }*/
                    }
                }
            } else {
                $data['retMsg'] = 'XML报文解析失败';
                $data['retCode'] = '999999';
            }
        } else {
            $data['retMsg'] = '待解析的XML报文为空';
            $data['retCode'] = '999999';
        }
        return $data;
    }
    
    /**
     * 组织从服务器收到的数据，为验签做准备
     * @param  $data：XML报文解析出的数组
     * @return $dataStr 拼接好的字符串，准备验签
     */
    function createCheckData($data) {
        $dataStr = "";
        $data['signature'] = "";
        foreach($data as $key => $value) {
            $dataStr .= '&'.$key.'='.$value;
        }
        $dataStr = substr($dataStr, 1, (strlen($dataStr)-1));
        return $dataStr;
    }
    
    //返回信息
    function getDebugInfo(){
        return $this->debugInfo; 
    }
    
    /*
     * 功能：MD5加签
     * 参数：要加签的字段名
     * 返回：MD5字符串
     */
    function getMD5($paraArr,$merchantKey) {
        $msgStr = "";
        if(isset($paraArr)&&(count($paraArr)>0)) {
            foreach($paraArr as $key => $value) {
                if(isset($this->parameters[$value])) {
                    $msgStr .= $value."=".$this->parameters[$value].'&';
                }
            }
            
            $msgStr = substr($msgStr,0,strlen($msgStr)-1).$merchantKey;
            $this->debugInfo = ($msgStr!="")? "源串组建成功" : "源串组建失败";
        } else {
            $this->debugInfo = "参数列表错误";
        }
        //echo $msgStr;
        return md5($msgStr);
    }
    
    /*
     * 功能：MD5验签
     * 参数：返回的数据数组，要验签的字段名数组
     * 返回：true OR false
     */
    function checkMD5($dataArr,$paraArr) {
        if(isset($dataArr) && isset($paraArr) && (count($dataArr)>0) && (count($paraArr)>0)) {
            foreach($paraArr as $key => $value) {
                if(isset($dataArr[$value])) {
                    $msgStr .= $separator.$value."=".$this->parameters[$value].'&';
                }
            }
            $msgStr .= $msgStr.'merchantKey='.$this->merchantKey;
        }
        return $dataArr["signature"] == md5($msgStr);
    }
    
    function iteratorArr($paraArr){
    	      $msgStr = "";
            foreach($paraArr as $key => $value) {
                if(isset($this->parameters[$value])) {
                    $msgStr .= $value."=".$this->parameters[$value].'&';
                }
            }
           $msgStr = substr($msgStr,0,strlen($msgStr)-1);
          return $msgStr;
    }
    
    
    
 function getHttpResponsePOST($url,  $para) {
   
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//SSL证书认证
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);//严格认证
	curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
	curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
	curl_setopt($curl,CURLOPT_POST,false); // post传输数据
	curl_setopt($curl,CURLOPT_POSTFIELDS,$para);// post传输数据
	$responseText = curl_exec($curl);
	
	curl_close($curl);
	
	return $responseText;
}
    
    
    
}
?>