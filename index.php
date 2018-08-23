<?php 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With,Content-Type');
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");
//namespace KS;
require_once __DIR__."/vendor/autoload.php";

//this is a base on prompay QR Code 
// can change style of QR Code by function set_targetType
// if set_targetType == url this QR Code to redirect to Open Web url
// but if not set function it will return prompay code for payment
$pp = new \KS\PromptPay();
$pp->set_targetType('url');

// get URL and name from Woocommerce API
require_once __DIR__."/class/config.php";
require_once __DIR__."/class/class_product.php";

$product = new product($woocommerce);

//echo json_encode($product->checkstatus());die();

$list = $product->getAllProductList(100);


for($i = 0 ;$i < count($list); $i++){
    echo "<ul><li>";
    echo "<div>=====================================<br/>";
    echo "<p> ID : ".$list[$i]->id."</p>";
    echo "<p> Name : ".$list[$i]->name."</p>";
    echo "<p> URL : ".$list[$i]->permalink."</p>";  
    $target = "http://playground-inseoul.com/show/product/".$list[$i]->id; 
    $name = $list[$i]->slug."-".$list[$i]->id;
    $savePath = 'assets/'.$name.'_qrcode.png';
    $pp->generateQrCode($savePath, $target); 
    echo "=========================================<br/>";
    echo '<img src="'.$savePath.'">';
    echo "</div>";
    echo "</li></ul>";
}


//Generate QR Code PNG file



function getname($t){
    $spli = explode("/",$t);
    $name = $spli[count($spli)-1];
    return 'assets/'.$name.'_qrcode.png';
}

function showitem(){
 

}