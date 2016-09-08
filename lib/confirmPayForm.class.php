<?php
class confirmPayForm extends Processing {
    //初始化参数
    function init(){
        $this->setParameter("merchantId", "");     
        $this->setParameter("orderId", "");    
        $this->setParameter("orderAmount", "");         
        $this->setParameter("signType", "");  
      
    }
}
?>