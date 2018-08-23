<?php // Install:
// composer require automattic/woocommerce
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

// Setup:
require __DIR__ . '/../vendor/autoload.php';

use Automattic\WooCommerce\Client;

/*$woocommerce = new Client(
    'http://kocomeishop.com/store', // Your store URL
    'ck_7830aa2ca31916bea498455042e4779ac94ad779', // Your consumer key
    'cs_583a5cc732fc2eb5a9f14a17bef0ee8a2b434826', // Your consumer secret
    [
        'wp_api' => true, // Enable the WP REST API integration
        'version' => 'wc/v2' // WooCommerce WP REST API version
    ]
);*/

//define('_baseUrl','http://www.playground-inseoul.com/shop');
define('_baseUrl','https://www.yoonthai.com');


 $woocommerce = [
    'url'=>_baseUrl."",
    'consumerKey'=>'ck_48dd376cb5c46dd24cd1597a5fd480598a73660a',
    'consumerSecret'=>'cs_46367838bf0387d65cf6d054beea3828fbd3f95a',
    'wp_api'=>true,
    'version'=>'wc/v2'
]; 

$wordpress = [
    'username'=>'admin',
    'password'=>'playworks'
];

define('_MaxRows','100');

//for connect database direct
define('_CFG_driver',"mysql");
define('_CFG_host',"localhost");
define('_CFG_user',"wordpress_l");
define('_CFG_pass', "6FX04$guSy");
define('_CFG_db_name', "wordpress_k0"); 

define('_CFG_connectInfo',"mysql:host="._CFG_host.";dbname="._CFG_db_name.";charset=utf8"); 

include_once (dirname(__FILE__)."/class_database.php");

include_once (dirname(__FILE__)."/class_wc.php");
include_once (dirname(__FILE__)."/class_product.php");


?>