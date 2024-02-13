<?php
include 'Classes/config.php';
include 'Classes/clsSqlServer.php';

$SqlSrv            = new SqlServer();

$SqlSrv->dbConnect();

if ($_POST['action'] == 'partido') {

    for ($i = 0; $i < count($_POST['array']); $i++) {

        if ($_POST['array'][$i]['equipo'] != '') {
            if ($_POST['equipoganado'] == 'Blanco' && $_POST['array'][$i]['equipo'] == 'Blanco') {
                $golafavor = $_POST['goles'];
                $resultado = 'G';
            } else  if ($_POST['equipoganado'] == 'Negro' && $_POST['array'][$i]['equipo'] == 'Negro') {
                $golafavor = $_POST['goles'];
                $resultado = 'G';
            } else {
                $golafavor = $_POST['goles'] * -1;
                $resultado = 'P';
            }

            $sql1 = "INSERT INTO partido_detalle VALUES (" . $_POST['idpartido'] . ", " . $_POST['array'][$i]['idjugador'] . ", '" . $_POST['array'][$i]['equipo'] . "', '" . $resultado . "', " . $golafavor . ");";
            $resul1 = $SqlSrv->dbQuery($sql1);
        }
    }

    $sql1 = "UPDATE partidos set status=2 where idpartido=" . $_POST['idpartido'] . ";";
    $resul1 = $SqlSrv->dbQuery($sql1);
} else if ($_POST['action'] == 'resultadex') {

    $sql = "SELECT * FROM partido_detalle WHERE idpartido=" . $_POST['idpartido'] . ";";
    $result = $SqlSrv->dbQuery($sql);
    $totaljugadores = $SqlSrv->dbNumRows($result);

    for ($i = 0; $i < $totaljugadores; $i++) {
        $rowsplayers = $SqlSrv->dbArray($result);

        if ($_POST['equipoganado'] == 'Blanco' && $rowsplayers['equipo'] == 'Blanco') {
            $golafavor = $_POST['goles'];
            $resultado = 'G';
        } else  if ($_POST['equipoganado'] == 'Negro' && $rowsplayers['equipo'] == 'Negro') {
            $golafavor = $_POST['goles'];
            $resultado = 'G';
        } else {
            $golafavor = $_POST['goles'] * -1;
            $resultado = 'P';
        }

        $sql1 = "UPDATE partido_detalle SET resultado='" . $resultado . "', difgoles=" . $golafavor . " where idjugador=" . $rowsplayers['idjugador'] . " and idpartido=" . $_POST['idpartido'] . ";";
        $resul1 = $SqlSrv->dbQuery($sql1);
    }

    $sql1 = "UPDATE partidos set status=1 where idpartido=" . $_POST['idpartido'] . ";";
    $resul1 = $SqlSrv->dbQuery($sql1);
} else if ($_POST['action'] == 'estadisticapartido') {

    for ($i = 0; $i < count($_POST['array']); $i++) {

        if ($_POST['array'][$i]['equipo'] != '') {
            if ($i == 0) {
                $sqlabuscarjugador = $_POST['array'][$i]['idjugador'];
            } else {
                $sqlabuscarjugador = $sqlabuscarjugador . ',' . $_POST['array'][$i]['idjugador'];
            }
        }
    }

    $querypartidos = "SELECT count(pd.idjugador) as cant, p.fechapartido FROM partido_detalle pd, partidos p WHERE pd.idjugador in (" . $sqlabuscarjugador . ") and pd.idpartido=p.idpartido group by pd.idpartido order by cant desc;";
    $resultpartidos = $SqlSrv->dbQuery($querypartidos);
    $totalpartidos = $SqlSrv->dbNumRows($resultpartidos);

    for ($i = 0; $i < $totalpartidos; $i++) {
        $rowpartidos = $SqlSrv->dbArray($resultpartidos);

        echo 'Cantidad de Jugadores seleccionados:' . $rowpartidos['cant'] . ' | Fecha partido: ' . $rowpartidos['fechapartido']. PHP_EOL;
        
    }


    // print_r($_POST);
}
