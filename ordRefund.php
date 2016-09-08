<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh" xml:lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>退款发起</title>
</head>

<body>
<form id="form1" method="post" action="ordRefund_do.php">
<input name="txnCod"   type="hidden" id="txnCod"  value="MerchantmerchantRefund" />
商品订单号      <input name="orderId"  type="text"  id="orderId" value="" /><br />
退款金额        <input name="rfAmt"       type="text"  id="rfAmt"     value="1"    /><br />
收款银行卡账号  <input name="bankPayAcNo"         type="text"  id="bankPayAcNo"     value="62258801"    /><br />
收款账号户名    <input name="bankPayUserNm"       type="text"  id="bankPayUserNm"     value="封"    /><br />
退款理由        <input name="rfSake"       type="text"  id="rfSake"     value="退款"    /><br />
异步通知URL     <input name="notifyUrl"       type="text"  id="notifyUrl"     value="http://www.baidu.com"    /><br />
<input type="submit" name="submit" value="提交" />
</form>
</body>
</html>