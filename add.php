<?php

session_start();
require "database/loader.php";

$db = new AdminDB();
$db->run('database');

if (isset($_SESSION["time_error"]) && time() < $_SESSION["time_error"]) {
    echo json_encode(array("success" => false, "msg" => "No haz dejado transcurrir el minuto."));
    return;
} elseif(isset($_SESSION["time_error"]) && time() > $_SESSION["time_error"]) {
    unset($_SESSION["time_error"]);
    $_SESSION["errors"] = 0;
}

if (isset($_SESSION["errors"]) && $_SESSION["errors"] > 2) {
    $_SESSION["time_error"] = strtotime("+1 minute");
    echo json_encode(array("success" => false, "time" => $_SESSION["time_error"], "msg" => "Tras tres errores debes de dejar transcurrir un minuto para poder seguir ingresando datos."));
    die();
}

try {
    if (!isset($_SESSION["errors"]) && empty($_SESSION["errors"])) {
        $_SESSION["errors"] = 0;
    }
    
    if (is_numeric($_POST["number"])) {
        $query = Connections::get()
                ->select("id_cifra")
                ->from("cifras")
                ->where("cifra = ?", intval($_POST["number"]));

        $comprueba = Fetch::row($query);

        if (is_object($comprueba)) {
            $_SESSION["errors"] ++;

            echo json_encode(array("success" => false, "msg" => "Este número ya esta registrado en la base de datos. \n Número de errores {$_SESSION["errors"]}"));
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

            $_SESSION["errors"] = 0;

            echo json_encode(array("success" => true, "total" => $total, "user_records" => $user_records, "data" => Fetch::row(Connections::get()->select(array("id_cifra", "cifra", "fecha_add"))->from("cifras")->where("id_cifra = ?", $insert))));
        }
    } else {
        $_SESSION["errors"] ++;

        echo json_encode(array("success" => false, "msg" => "Sólo se aceptan números. \n Número de errores {$_SESSION["errors"]}"));
    }
} catch (Exception $e) {
    echo json_encode(array("success" => false, "msg" => $e->getMessage()));
}

