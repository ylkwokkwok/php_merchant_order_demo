<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh" xml:lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>商品订单明细查询</title>
</head>

<body>
<form id="form1" method="post" action="queryOrdDtl_do.php">
<input name="txnCod"   type="hidden" id="txnCod"  value="MerchantmerchantTransQuery" />
查询方式<input name="queryType"  type="text"  id="queryType" value="1" /><br />
商户订单号<input name="orderId"       type="text"  id="orderId"     value=""    /><br />
<input type="submit" name="submit" value="提交" />
</form>
</body>
</html>