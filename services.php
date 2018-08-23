<?php  header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");

include 'class/config.php';


$lang = (isset($_GET['language']) && $_GET['language'] == "ko")? $_GET['language'] : "" ;

$product = new product($woocommerce,$lang);

$wp = new WC($wordpress,$lang);
$username = (isset($_REQUEST['username'])? $_REQUEST['username'] : "");
$password = (isset($_REQUEST['password'])? $_REQUEST['password'] : "");
$wp->setusername($username);
$wp->setpassword($password);
$data = array();

if($lang =="ko"){
    $category = array('123','135','117','127','129');  // Category in KO
}else{
    $category = array('118','128','130','124','136');   // Category in TH
}


$action = $_REQUEST['action'];
switch($action){

    case "getmenu":
        $menu = [];
        $w = $wp->getHeadMenu();
        print_r($w);
    break;
   
    case "checkService":
        $w = $wp->ServiceStatus();
        echo json_encode($w);
    break;

    case "getmedia":
        $id = (isset($_GET['id'])? $_GET['id'] : '1');
        $w = $wp->getMedia($id);
        echo json_encode($w);
    break;

    case "getAllCategories":
        $option = '?per_page=100';
        $w = $wp->getCategory($option);
        echo json_encode($w);
    break;

    case "getAllParentCategories":
        //$parent = (isset($_GET['parent'])? $_GET['parent'] : '0');
        $category = array('132','118','128','130','124','136','138','140'); 
        for($i = 0; $i < count($category); $i++){
            $categorydetail = $wp->getCategoryById($category[$i]); //get category
            $w[] = $categorydetail;
        }
       // $option = "?per_page=100&parent=".$parent;
       // $w = $wp->getCategory($option);
        echo json_encode($w);
    break;

    case "getCategoryById":
        $id = (isset($_GET['id'])? $_GET['id'] : die(json_encode('error please provide a id ')));
        $w = $wp->getCategoryById($id);
        echo json_encode($w);
    break;

    case "getAllPost":
        //option
        $page =(isset($_REQUEST['page'])? $_REQUEST['page'] : 1);
        $option = '?per_page=10&page='.$page;
        $w = $wp->getpostDetail($option);
        echo json_encode($w);
    break;

    case "getPostByCategories":
        $category =(isset($_REQUEST['cat'])? $_REQUEST['cat'] : die(json_encode('error please set a categorie id')));
        $page =(isset($_REQUEST['page'])? $_REQUEST['page'] : 1);
        $per_page =(isset($_GET['per_page'])? $_GET['per_page'] : 10);
        $option = '?per_page='.$per_page.'&page='.$page.'&categories='.$category;
        $data = $wp->getpostDetail($option);
        for($i=0;$i<count($data);$i++){
            $feature_image[$i] = $wp->getMedia($data[$i]->featured_media);
        }
        $w = array("data"=>$data, "feature_image"=>$feature_image);
        //$w = $wp->getpostDetail($option);
        echo json_encode($w);
    break;

    case "getPostById":
        $id = (isset($_GET['id'])? $_GET['id'] : "");
        $option = '/'.$id;
        $w = $wp->getpostdetail($option);
        echo json_encode($w);
    break;

    case "userLogin":
        $wp->setusername($_GET['username']);
        $wp->setpassword($_GET['password']);
        
        $w = $wp->userlogin();
        echo json_encode($w);
    break;

    case "getMyDetail":
        $w = $wp->getMyDetail();
        echo json_encode($w);
    break;

    case "getUserDetail":
        $w = $wp->getUserDetail($_GET['id']);
        echo json_encode($w);
    break;

    case "getPostFirstPageNew":
    for($i = 0; $i < count($category); $i++){
        $categorydetail[] = $wp->getCategoryById($category[$i]); //get category
        $category_option .= "categories=".$category[$i]."&";
        $w[$i] = array('id'=>$categorydetail[$i]->id,'slug'=>$categorydetail[$i]->slug,'name'=>$categorydetail[$i]->name);
    }
    echo json_encode($w);
    break;

    case "getPostFirstPage": //will depecate on new version
    //get Category to show in First Page 

       /* for($i = 0; $i < count($category); $i++){
           $categorydetail[] = $wp->getCategoryById($category[$i]); //get category
           $category_option .= "categories=".$category[$i]."&";
       
            $option ='?per_page=5&page=1&categories='.$category[$i]; // get posts by category
            $data = $wp->getpostDetail($option); 
            for($c = 0; $c < count($data); $c++){
                $feature_image[$c] = $wp->getMedia($data[$c]->featured_media);
            } 
           
            $w[] = array('id'=>$categorydetail[$i]->id,'slug'=>$categorydetail[$i]->slug,'name'=>$categorydetail[$i]->name,'data'=>$data, 'feature_image' => $feature_image);
        }
       echo json_encode($w); */
        //get Post in this category
        //get featureimage

        for($i = 0; $i < count($category); $i++){
            $categorydetail[] = $wp->getCategoryById($category[$i]); //get category
            $category_option .= "categories=".$category[$i]."&";
            $w[$i] = array('id'=>$categorydetail[$i]->id,'slug'=>$categorydetail[$i]->slug,'name'=>$categorydetail[$i]->name);
        }

        for($a = 0; $a < count($category); $a++){
            $option ='?per_page=5&page=1&categories='.$category[$a]; // get posts by category
             $data = $wp->getpostDetail($option); 
             for($c = 0; $c < count($data); $c++){
                 $feature_image[$c] = $wp->getMedia($data[$c]->featured_media);
             } 
            // array_push($w,array('data'=>$data, 'feature_image' => $feature_image));
            $w[$a]['data'] = $data;
            $w[$a]['feature_image'] = $feature_image;
             
        }
        echo json_encode($w);

    break;

    case "getCommentPost":
     $data = array();
     $alldata = array();
        $page = (isset($_GET['page'])? $_GET['page'] : '1');
        $option = '?page='.$page.'&perpage=10';
        $board = $wp->getWebboard($option);
        for($i = 0; $i < count($board); $i++){
            $userdetail[$board[$i]->ID] = $wp->getUserDetail($board[$i]->post_author);
        }
        //$data = $wp->getWebboard($option);
        $data = array('data'=>$board,'author'=>$userdetail);
       echo json_encode($data);
    break;

    case "listproducts":
        $data = array();       
        $page = (isset($_GET['page'])? $_GET['page'] : '1');
        $lang = (isset($_GET['lang']) || $_GET['lang']=='ko'? $_GET['lang'] : '');
        $perpage = 10;
        $list = $product->getAllProductList($perpage,$page,$lang);
        echo json_encode($list);
    break;

    case "getproductDetail":
        $lang = (isset($_GET['lang']) || $_GET['lang']=='ko'? $_GET['lang'] : '');
        $detail = $product->getProductDetail($_GET['id'],$lang);
        echo json_encode($detail);
    break;

}