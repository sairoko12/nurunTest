<?php

session_start();
require "database/loader.php";

$db = new AdminDB();
$db->run('database');
try {
    if (is_numeric($_POST["number"])) {
        $query = Connections::get()
                ->select("id_cifra")
                ->from("cifras")
                ->where("cifra = ?", intval($_POST["number"]));
                
        $comprueba = Fetch::row($query);
        
        if (is_object($comprueba)) {
            echo json_encode(array("success" => false, "msg" => "Número repetido"));
        } else {
            $data = array(
                "id_usuario" => $_SESSION["user"],
                "cifra" => $_POST["number"]
            );

            $insert = Connections::get()->insert("cifras", $data);
            
            $total = Fetch::row(Connections::get()
                            ->select("id_cifra")
                            ->from("cifras")
                            ->orderBy("id_cifra", "DESC"))->id_cifra;
            
            $user_records = Fetch::row(Connections::get()->select("COUNT(id_cifra) AS registros_usuario")->from("cifras")->where("id_usuario = ?", $_SESSION["user"]))->registros_usuario;

            echo json_encode(array("success" => true, "total" => $total, "user_records" => $user_records ,"data" => Fetch::row(Connections::get()->select(array("id_cifra","cifra", "fecha_add"))->from("cifras")->where("id_cifra = ?", $insert))));
        }
    } else {
        echo json_encode(array("success" => false, "msg" => "Sólo se aceptan números."));
    }
} catch (Exception $e) {
    echo json_encode(array("success" => false, "msg" => $e->getMessage()));
}

