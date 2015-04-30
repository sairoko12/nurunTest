<?php

session_start();

require "../database/loader.php";

function randomStr($length = 5) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	

    $size = strlen( $chars );
    $str = '';
    
    for( $i = 0; $i < $length; $i++ ) {
        $str .= $chars[ rand( 0, $size - 1 ) ];
    }
    
    return $str;
}

$db = new AdminDB();
$db->run('../database');

$user = $_POST["data"];

$q = Connections::get()
        ->select(array('id_usuario',"username"))
        ->from("usuarios")
        ->where("id_facebook = ?", $user["id"]);

$id = Fetch::row($q);

if (is_object($id) && !empty($user["id"])) {
    $_SESSION["user"] = $id->id_usuario;
    
    echo json_encode(array("success" => true, "username" => $id->username));
} elseif (!empty ($user["id"])) {
    try {
        do {
            $str = randomStr();
            $q = Connections::get()
                ->select("id_usuario")
                ->from("usuarios")
                ->where("visible_id = ?", $str);
        } while(is_object(Fetch::row($q)));
        
        $username = explode('@', $user["email"]);
        $info = new SplFileInfo($_POST["photo"]);
        
        $image = @file_get_contents($_POST["photo"]);
        @file_put_contents('../assets/images/profile_pictures/' . $username[0] . '.' . $info->getExtension(), $image);

        $id_user = Connections::get()->insert("usuarios", array(
            "visible_id" => $str,
            "id_facebook" => $user["id"],
            "username" => $username[0],
            "nombre" => (isset($user["middle_name"])) ? $user["middle_name"] : "Desconocido",
            "email" => $user["email"],
            "original_picture" => $_POST["photo"]
        ));

        $_SESSION["user"] = $id_user;

        echo json_encode(array("success" => true, "username" => $username[0]));
    } catch (Exception $e) {
        echo json_encode(array("success" => false, "msg" => $e->getMessage()));
    }
}