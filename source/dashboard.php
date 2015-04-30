<?php
require "/../database/loader.php";

$db = new AdminDB();
$db->run("database");

$usuario = Fetch::row(Connections::get()->select(array("nombre", "username", "email", "original_picture", "visible_id", "app_picture"))->from("usuarios")->where("id_usuario = ?", $_SESSION["user"]));

$cifras = Fetch::all(Connections::get()
                        ->select(array("id_cifra", "cifra", "fecha_add"))
                        ->from("cifras")->where("id_usuario = ?", $_SESSION["user"]));
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta charset="UTF-8">
        <title>Dashboard Cifras</title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" />

        <script type="text/javascript" src="assets/js/mustache.js"></script>
        <script type="text/javascript" src="assets/js/jquery-2.1.3.min.js"></script>
        <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/js/jquery.form.min.js"></script>
        <script type="text/javascript" src="assets/js/tablesorter.js"></script>
        <script type="text/javascript" src="assets/js/general.js"></script>
    </head>
    <body class="bg-success">
        <div id="fb-root"></div>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-xs-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="thumbnail">
                                        <img src="<?php echo $usuario->original_picture; ?>" alt="Facebook Picture" style="height: 100px;" class="img-circle img-responsive">
                                        <div class="caption">
                                            <h3><?php echo $usuario->nombre; ?></h3>
                                            <p>
                                            <ul class="list-inline">
                                                <li><strong>Username:</strong> <?php echo $usuario->username; ?></li>
                                                <li><strong>Email:</strong> <?php echo $usuario->email; ?></li>
                                                <li><strong>Usuario ID:</strong> <?php echo $usuario->visible_id; ?></li>
                                            </ul>
                                            </p>
                                            <p><button type="button" class="btn btn-danger btn-block" onclick="location.href = 'logout.php'">Cerrar Sesión</button></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="panel panel-primary">
                                <div class="panel-heading"><strong>Herramientas</strong><button class="btn btn-xs btn-default tools pull-right" data-toogle="open"><span class="glyphicon glyphicon-plus"> </span></button></div>
                                <div class="panel-body content-tools" style="display: none;">
                                    <div class="row">
                                        <div class="col-xs-10">
                                            <form method="POST" action="add.php" id="add_number">
                                                <div class="form-group">
                                                    <label for="q">Agregar Cifra:</label>
                                                    <div class="row">
                                                        <div class="col-xs-10">
                                                            <input type="text" class="form-control" name="number" id="q" required="required" placeholder="Puedes agregar números decimales o enteros" autocomplete="false" />
                                                        </div>
                                                        <div class="col-xs-2">
                                                            <button type="submit" class="btn btn-success btn-block">Agregar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h4>Tus cifras (<span id="user_records"><?php echo count($cifras); ?></span>)</h4>
                            <h4>El contador actual es: <span id="total"><?php
                                    $cifra = Fetch::row(Connections::get()
                                                            ->select("id_cifra")
                                                            ->from("cifras")
                                                            ->orderBy("id_cifra", "DESC"));
                                    echo (is_object($cifra)) ? $cifra->id_cifra : '0';
                                    ?></span></h4>
                            <div class="well">
                                <table class="table table-bordered table-responsive" id="registros">
                                    <thead>
                                        <tr>
                                            <th style="cursor: pointer; text-decoration: underline;">Contador</th>
                                            <th style="cursor: pointer; text-decoration: underline;">Cifra</th>
                                            <th style="cursor: pointer; text-decoration: underline;">Fecha inserción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        foreach ($cifras AS $k => &$v): $i++;
                                            ?>
                                            <tr>
                                                <td><?php echo $v->id_cifra; ?></td>
                                                <td><?php echo $v->cifra; ?></td>
                                                <td><?php echo $v->fecha_add; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>	
            </div>
        </div>

        <script type="text/html" id="tpl-add">
            {{#record}}
        <tr>
            <td>{{id_cifra}}</td>
            <td>{{cifra}}</td>
            <td>{{fecha_add}}</td>
        </tr>
        {{/record}}
    </script>
</body>
</html>