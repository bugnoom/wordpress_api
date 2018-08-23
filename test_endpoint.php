<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With,Content-Type');
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");

// Data return from DAOU Payment
/*
 a:18:{s:9:"PAYMETHOD";s:4:"CARD";s:4:"CPID";s:8:"CTS15087";s:7:"DAOUTRX";s:20:"CTS18062016001494646";s:7:"ORDERNO";s:5:"12035";s:6:"AMOUNT";s:3:"845";s:8:"SETTDATE";s:14:"20180620160124";s:5:"EMAIL";s:0:"";s:6:"USERID";s:2:"37";s:8:"USERNAME";s:5:"admin";s:11:"PRODUCTCODE";s:1:"1";s:11:"PRODUCTNAME";s:50:"Dainut ‡π?‡∏°‡?‡∏?‡∏°‡∏∞‡∏°‡?‡∏ß‡?‡∏´‡∏¥‡∏°‡?‡∏≤‡?";s:6:"AUTHNO";s:8:"28060467";s:8:"CARDCODE";s:4:"CCLG";s:8:"CARDNAME";s:15:"Ω≈«—ƒ´µÂ - √º≈©";s:6:"CARDNO";s:16:"4499140000009382";s:14:"RESERVEDINDEX1";s:0:"";s:14:"RESERVEDINDEX2";s:0:"";s:14:"RESERVEDSTRING";s:0:"";}

 RESERVEDSTRING = Order comeplete URL on web

 URL FOR TEST IS :PAYMETHOD=CARD&CPID=CTS15087&DAOUTRX=CTS18062016001494646&ORDERNO=12035&AMOUNT=845&SETTDATE=20180620160124&EMAIL=&USERID=37&USERNAME=admin&PRODUCTCODE=1&PRODUCTNAME=Dainut ‡π?‡∏°‡?‡∏?‡∏°‡∏∞‡∏°‡?‡∏ß‡?‡∏´‡∏¥‡∏°‡?‡∏≤‡?&AUTHNO=28060467&CARDCODE=CCLG&CARDNAME=Ω≈«—ƒ´µÂ - √º≈©&CARDNO=4499140000009382&RESERVEDINDEX1=&RESERVEDINDEX2=&RESERVEDSTRING=http://www.yoonthai.com
 */

 include 'class/config.php';

// Check Return variable PAYMETHOD from Daou Payment Gateway
if(isset($_REQUEST['PAYMETHOD'])){
    $wc = new product($woocommerce,'');
    $id = $_REQUEST['ORDERNO'];
    $data = [
        'status' => 'processing',
        'transaction_id' => $_REQUEST['DAOUTRX']
    ];
    //update Order
   // $a = $wc->update_order($data,$id);
   $updateStatus = $wc->ProductprocessURL($woocommerce['url']."/wp-json/wc/v2/orders/".$id,http_build_query($data),'POST');
  if($updateStatus != null){
      //Update Note in Woocommerce orders system
      $notedata = [
          'note' =>'Payment Success on Daou Payment Gateway Transaction No. '.$_REQUEST['DAOUTRX']
      ];
      $sendnote = $wc->ProductprocessURL($woocommerce['url']."/wp-json/wc/v2/orders/".$id."/notes",http_build_query($notedata),'POST');

    //redirect to Thankyou page and show some Detail on web
    header("Location:" . $_REQUEST['RESERVEDSTRING']);
    die();
  }
}else{
    //Send data to Daou Payment Gateway
    $urlpayment = "https://ssltest.kiwoompay.co.kr/card/DaouCardMng.jsp";
    // Recieve all Data from  Checkout page via GET Method
    $data = $_GET; 

    // Auto submit Form for send Any data to Daou Payment Gateway to Show Step to Payment on them system.
?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=11">
		<meta http-equiv="Cache-Control" content="no-cache"> 
		<meta http-equiv="Expires" content="0"> 
		<meta http-equiv="Pragma" content="no-cache">
<head>
    <title>Endpoint to Payment</title>
</head>
<body>
    <style>
    input{padding:10px;margin:10px}
    </style>
<h1> Test to connect to Payment Gateway</h1>
    <form name="senderpayment" id="toDaouPay" action="<?php echo $urlpayment; ?>" target="_top" method="POST"  accept-charset="euc-kr" >
    <label>Store ID *</label>    
    <input name="CPID" type="text" value="CTS15087"><br>
    <label>Order No. *</label>    
    <input name="ORDERNO" type="text" value="<?php echo $data['ORDERNO'];?>"><br>
    <label>Product Type (1:digi, 2:phy) *</label>    
    <input name="PRODUCTTYPE" type="text" value="2"><br>
    <label>Bill type *</label>    
    <input name="BILLTYPE" type="text" value="1"><br>
    <label>Tax Free *</label>    
    <input name="TAXFREECD" type="text" value="00"><br>
    <label>Amount *</label>    
    <input name="AMOUNT" type="text" value="<?php echo $data['AMOUNT'];?>"><br>
    <label>Product Name *</label>    
    <input name="PRODUCTNAME" type="text" value="<?php echo $data['PRODUCTNAME'];?>"><br>
     
    ================================= <br><br>
    <label>User Login ID</label>
        <input name="USERID" type="text" value="<?php echo $data['USERID'];?>"><br>
    <label>User Login Name</label>
        <input name="USERNAME" type="text" value="<?php echo $data['USERNAME'];?>"><br>
        <input name="PRODUCTCODE" type="text" value="">
    <label>Other Variable</label>
        <input name="RESERVEDSTRING" type="text" value="<?php echo $data['success_url'];?>"><br>
        <input type="submit" name="submit" vaule="SEND"> 
    </form>
    <script type="text/javascript" charset="EUC-KR">
        document.characterSet="EUC-KR";
    /* function submit_form(){document.getElementById('toDaouPay').submit();}
    
    if(window.attachEvent){
        window.attachEvent("onload",submit_form);
    }else{
        window.addEventListener("load", submit_form, false);
    }
 */
    </script>
</body>
</html> 
<?php } ?>