<?php 
header('Access-Control-Allow-Origin: *'); 

class WC{
    public $token= "";
    public $api_url = "";
    public $token_url = "";
    public $username = "";
    public $password = "";
   
    
    public function setusername($val){
        return $this->username = $val;
    } 

    public function getusername(){
        return $this->username;
    }

    public function setpassword($val){
        return $this->password = $val;
    }

    public function getpassword(){
        return $this->password;
    }
    
    public function getToken(){
        $decode = json_decode($this->token);
        @$r = 'Bearer '.$decode->token;
        return $r;
    }

    public function setToken($value){
        return $this->token = $value;

    }

    function __construct($wc,$lang=""){
        $this->api_url = _baseUrl."/".$lang."/wp-json/wp/v2/";
        $this->token_url = _baseUrl."/".$lang.'/wp-json/jwt-auth/v1/token';
        $this->setusername($wc['username']);
        $this->setpassword($wc['password']);
        $this->CurlgetToken();
    }

    public function CurlgetToken(){
       /*  $post = [
            'username' => $wc['username'],
            'password' => $wc['password'],
        ]; */
        $post = [
            'username' => $this->getusername(),
            'password' => $this->getpassword()
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->token_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        
        // execute!
        $response = curl_exec($ch);
        //$encode = json_encode($response);
        
        // close the connection, release resources used
        curl_close($ch);
        
       $this->setToken($response);
        //return $response;
    }

    private function processURL($url,$post,$method = 'GET'){
        $api_url = $this->api_url.$url;
     
        
       //  Initiate curl
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
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

     public function userlogin(){
        // $this->setusername($username);
        // $this->setpassword($password);
       $this->CurlgetToken();
        //echo $this->getToken();
       // die();
       // return $this->getToken(); die();
          if($this->token != ""){
             return $this->getMyDetail();
         } 
     }

    public function getMyDetail($option=""){  
        $r = $this->processURL('users/me','');
        return $r;
    }

    public function getUserDetail($id){
        $r = $this->processURL('users/'.$id,'');
        return $r;
    }

    public function getpostDetail($option=""){
        $r = $this->processURL('posts'.$option,'');
        return $r;
    }

    public function getpostByCate($cat){
        $r = $this->processURL('posts?categories='+$cat,'');
        return $r;
    }

    public function getCategory($option=""){
        $r = $this->processURL('categories'.$option,'');
        return $r;
    }

    public function getCategoryById($id){
        $r = $this->processURL('categories'."/".$id,'');
        return $r;
    }

    public function getMedia($id){
        $r = $this->processURL("media"."/".$id,'');
        return $r;
    }

    public function ServiceStatus(){
        $r = $this->processURL('','');
        return $r;
    }

    public function getHeadMenu(){
        $r = $this->processURL('head_menu','');
        return $r;
    }

    public function getWebboard($option=""){
        $r = $this->processURL('webboard'.$option,'');
        return $r;
    }

    

}

?>