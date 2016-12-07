<?
define('INCLUDE_DIR', dirname(__FILE__) . '/inc/');
$rules = array(
    //
    //main pages
    //
    'about' => "/about",
    'register' => "/register",
    'photographers' => "/photographers",
    'photoList' => "/photoList",
    'search' => "/search",
   // 'blog_article' => "/blog/(?'blogID'[\w\-]+)",
    'profile' => "/profile/(?'profileUsername'[\w\-]+)",
    'photo' => "/photo/(?'photoID'[\w\-]+)",
    
    //
    //Admin Pages
    //
    'uploadPhoto' => "/uploadPhoto",
    'buyPhoto' => "/buyPhoto",
    'boughtPhotos' => "/boughtPhotos",
    'uploadProfilePhoto' => "/uploadProfilePhoto",
    'applyPhotographer' => "/applyPhotographer",
    'editPhoto' => "/editPhoto",
    'insertComment' => "/insertComment",
    'editComment' => "/editComment",
    'deleteComment' => "/deleteComment",
    'editProfile' => "/editProfile",
    'addProfile' => "/addProfile",
    'login' => "/login",
    'admin'=> "/admin",
    'updateLevel' => "/updateLevel",
    'create_article' => "/createarticle",
    'logout' => "/logout",
    'uploadtest' => "/uploadtest",
    'deletePhoto' => "/deletePhoto",
    'up' => "/up",
    'youAreBanned' => "/youAreBanned",
    'banUser' => "/banUser",
    //
    // Home Page
    //
    'home' => "/"
);



$uri = rtrim(dirname($_SERVER["SCRIPT_NAME"]), '/');
$uri = '/' . trim(str_replace($uri, '', $_SERVER['REQUEST_URI']), '/');
$uri = urldecode($uri);
foreach ($rules as $action => $rule) {
    if (preg_match('~^' . $rule . '$~i', $uri, $params)) {
        include(INCLUDE_DIR . $action . '.php');
        exit();
    }
}
//nothing is found so handle the 404 error
include(INCLUDE_DIR . '404.php');
?>