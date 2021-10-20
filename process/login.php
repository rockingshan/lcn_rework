<?php
require "../include/dbvar.php";
$_SESSION['sidcounter']=array();

if (isset($_POST['user']) and isset($_POST['password'])) {
    $user = $_POST['user'];
    $pass = md5($_POST['password']);
    $user_account = DB::queryFirstRow("SELECT * FROM lcn_auth_tb LEFT JOIN city_tb ON lcn_auth_tb.default_city=city_tb.city_id WHERE lcn_auth_tb.user=%s AND lcn_auth_tb.pass=%s", $user, $pass);
    if (!empty($user_account)) {
        echo "success";
        $_SESSION['user'] = $user_account["user"];
        $_SESSION['fname'] = $user_account["first_name"];
        $_SESSION['user_id'] = $user_account["user_id"];
        $_SESSION['city'] = $user_account["city_name"];
        $_SESSION['default_db'] = $user_account["db_name"];
        header("location:../index.php");
    }else {
        header("location:../login.html");
    }
  
} else {
    header("location:../login.html");
}


?>