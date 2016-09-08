<?php
class queryOrdDtlForm extends Processing {
    //初始化参数
    function init(){
     
        $this->setParameter("merchantId", "");     
        $this->setParameter("queryType", "");  
        $this->setParameter("orderId", "");         
        $this->setParameter("signType", "");       
       
    }
}
?>