<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh" xml:lang="zh">
<head>
<!--Created on: 2015-3-13-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title> 网银支付</title>
 
</head>

<body>
<form id="form1" method="post" action="onlinePay_do.php">
<input name="txnCod"   type="hidden" id="txnCod"  value="MerchantmerchantOnlinePay" />
<label for="prodCode"    >商户购买产品编码    </label><input name="prodCode"     type="text" id="prodCode"     maxlength="33"  value="CP00000003" /><br />
<label for="orderId"     >商品订单号  </label><input name="orderId"      type="text" id="orderId"      maxlength="50" value="59349743903454" />*<br />
<label for="orderAmount" >订单金额(以分为单位)    </label><input name="orderAmount"  type="text" id="orderAmount"  maxlength="13" value="1" />*<br />
<label for="orderDate"   >订单日期    </label><input name="orderDate"    type="text" id="orderDate"    maxlength="14" value="20151108105321" />*<br />
<label for="prdOrdType"   >订单类型    </label><input name="prdOrdType"    type="text" id="prdOrdType"    maxlength="14" value="0" />*<br />
<label for="retUrl"      >异步返回URL </label><input name="retUrl"       type="text" id="retUrl"       maxlength="2"  value="http://10.251.1.11:8080/tdpay/1.6demo_do.php" /><br />
<label for="returnUrl"   >同步返回URL </label><input name="returnUrl"    type="text" id="returnUrl"    maxlength="2"  value="http://10.251.1.11:8080/tdpay/1.6demo_do.php" /><br />
<label for="prdName"     >商品名称    </label><input name="prdName"      type="text" id="prdName"      maxlength="50" value="鲜花" /><br />
<label for="prdDesc"     >商品描述    </label><input name="prdDesc"      type="text" id="prdDesc"      maxlength="500" value="鲜花礼品" /><br />

<input type="submit" name="submit" value="提交" />
</form>
</body>


<script type="text/javascript">
 	        var date = new Date();
          var year = date.getFullYear();
	        var mon = date.getMonth() + 1;
	        var day = date.getDate();
	        var hour = date.getHours();
	        var mins = date.getMinutes();
	        var sec = date.getSeconds();   
	        if(mon < 10){
	          mon = "0" + mon;
	        } 
	        if(day < 10){
	          day = "0" + day;
	        } 
	        if(hour < 10){
	          hour = "0" + hour;
	        } 
	        if(mins < 10){
	          mins = "0" + mins;
	        } 
	        if(sec < 10){
	          sec = "0" + sec;
	        } 
	         document.getElementById("orderId").value="P"+year + "" + mon + day + hour + mins + sec;
	         document.getElementById("orderDate").value=year +""+ mon + day+ hour + mins + sec;
	      
 	
 	
 	
 	
 </script>
</html>