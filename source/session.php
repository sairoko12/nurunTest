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
        ->select('id_usuario')
        ->from("usuarios")
        ->where("id_facebook = ?", $user["id"]);

$id = Fetch::row($q);

if (is_object($id) && !empty($user["id"])) {
    $_SESSION["user"] = $id->id_usuario;
    
    echo json_encode(array("success" => true));
} elseif (!empty ($user["id"])) {
    try {
        do {
            $str = randomStr();
            $q = Connections::get()
                ->select("id_usuario")
                ->from("usuarios")
                ->where("visible_id = ?", $str);
        } while(is_object(Fetch::row($q)));

        $id_user = Connections::get()->insert("usuarios", array(
            "visible_id" => $str,
            "id_facebook" => $user["id"],
            "nombre" => $user["middle_name"],
            "email" => $user["email"],
            "original_picture" => $_POST["photo"]
        ));

        $_SESSION["user"] = $id_user;

        echo json_encode(array("success" => true));
    } catch (Exception $e) {
        echo json_encode(array("success" => false, "msg" => $e->getMessage()));
    }
}