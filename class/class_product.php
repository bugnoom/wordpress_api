<?php
require __DIR__ . '/../vendor/autoload.php';

use Automattic\WooCommerce\Client;

class product extends WC{
 private $text;
 public $woocommerce;
 private $parameter;
public $lang = '';
   public function setParameter($param){
       $this->parameter = $param;
   }
   public function getParameter(){
       return $this->parameter;
   }

   public function setLanguage($val){
        $this->lang = $val;
   }

   public function getLanguage(){
       return $this->lang;
   }

    function __construct($woocommerce_API,$language = ''){
        $url = ($language != "")? $woocommerce_API['url'].'/'.$language : $woocommerce_API['url'];
        $this->woocommerce = new Client($url, // Your store URL
        $woocommerce_API['consumerKey'], // Your consumer key
        $woocommerce_API['consumerSecret'], // Your consumer secret
        [
            'wp_api'=>$woocommerce_API['wp_api'],// Enable the WP REST API integration
            'version'=>$woocommerce_API['version'],    // WooCommerce WP REST API version
            'verify_ssl' => false // for verify SSl
        ]);

        $this->token_url = _baseUrl."/".$lang.'/wp-json/jwt-auth/v1/token';
        $this->setusername('admin');
        $this->setpassword('playworks');
        $this->CurlgetToken();

    }

    public function checkstatus(){
        return $this->ProductprocessURL(_baseUrl.$lang.'/wp-json/wc/v2/','');
       // return $this->woocommerce->get('');
    }

    public function getAllProductList($perpage=10,$page=1,$lang=''){
        $param = array(            
           'per_page'=>$perpage,
           'page'=>$page,
           'status'=> 'publish',
          'lang' => $lang
        );
       return $this->ProductprocessURL(_baseUrl.'/wp-json/wc/v2/products?'.http_build_query($param),'');
      
        //return $this->woocommerce->get('products',$param);
    }

    public function getProductDetail($product_id,$lang=''){
        $param = array('lang' => $lang);
        return $this->ProductprocessURL(_baseUrl.'/wp-json/wc/v2/products/'.$product_id."?".http_build_query($param),'');
    }

    public function update_order($data,$order_id){
       // $w = $this->woocommerce->put('orders/'.$order_id, $data); 
       $w = $this->ProductprocessURL('orders/'.$order_id, http_build_query($data),'POST');
        return $w;
    }

    public function ProductprocessURL($url,$post,$method = 'GET'){
        $api_url = $url;
    
       //  Initiate curl
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        //send head authentication
        curl_setopt($ch, CURLOPT_HTTPHEADER,array('Authorization:'.$this->getToken()));
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL, $api_url);

        if($method != 'GET'){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        // Execute
        $result = curl_exec($ch);
       
       // Closing
        curl_close($ch);

        return json_decode($result);
     }

}