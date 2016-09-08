<?php
class ordRefundForm extends Processing {
    //初始化参数
    function init(){
        $this->setParameter("merchantId", "");     
        $this->setParameter("orderId", "");    
        $this->setParameter("rfAmt", "");         
        $this->setParameter("bankPayAcNo", "");        
        $this->setParameter("bankPayUserNm", "");   
        $this->setParameter("rfSake", "");  
        $this->setParameter("notifyUrl", "");  
        $this->setParameter("signType", "");  
      
    }
}
?>