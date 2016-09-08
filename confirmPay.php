<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh" xml:lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>确认付款</title>
</head>

<body>
<form id="form1" method="post" action="confirmPay_do.php">
<input name="txnCod"   type="hidden" id="txnCod"  value="MerchantmerchantConfirmPay" />
商品订单号      <input name="orderId"  type="text"  id="orderId" value="" /><br />
订单金额        <input name="orderAmount"       type="text"  id="orderAmount"     value="1"    /><br />
<input type="submit" name="submit" value="提交" />
</form>
</body>
</html>