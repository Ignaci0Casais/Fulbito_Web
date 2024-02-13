<?php
include 'Classes/config.php';
include 'Classes/clsSqlServer.php';

$SqlSrv            = new SqlServer();

//SET  sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

$SqlSrv->dbConnect();

if ($_POST['accion'] == 'buscardoparti') {

    $mes = $_POST['mes'];

    if ($mes == '10') {
        $fechaini = '2023-10-01';
        $fechafin = '2023-10-31';
    } else if ($mes == '11') {
        $fechaini = '2023-11-01';
        $fechafin = '2023-11-30';
    } else if ($mes == '12') {
        $fechaini = '2023-12-01';
        $fechafin = '2023-12-31';
    }

    $sqlpartido = "SELECT DATE_FORMAT(p.fechapartido, '%d/%m/%Y') as fechapartido, pd.equipo, pd.difgoles, p.idpartido FROM partidos p, partido_detalle pd WHERE p.idpartido=pd.idpartido AND p.fechapartido BETWEEN '$fechaini' AND '$fechafin' AND p.status=1 AND pd.resultado in ('G','E') GROUP BY fechapartido, pd.equipo, pd.difgoles, p.idpartido;";
    $Sql_result = $SqlSrv->dbQuery($sqlpartido);
    $total = $SqlSrv->dbNumRows($Sql_result);


    for ($i = 0; $i < $total; $i++) {
        $array = $SqlSrv->dbArray($Sql_result);

        $list[$i] = array($array['fechapartido'], $array['equipo'], $array['difgoles'], $array['idpartido']);
    }

    header('Content-Type: application/json');
    echo json_encode($list);
} else if ($_POST['accion'] == 'buscarjugadordoparti') {

    $querylistado = "select pd.equipo, j.nombre from partido_detalle pd, jugadores j where pd.idpartido=" . $_POST['idpartido'] . " and pd.idjugador=j.idjugador order by pd.equipo;";
    $result_Resultado = $SqlSrv->dbQuery($querylistado);
    $totalResult = $SqlSrv->dbNumRows($result_Resultado);

    for ($i = 0; $i < $totalResult; $i++) {
        $arraytotal = $SqlSrv->dbArray($result_Resultado);

        if ($arraytotal['equipo'] == 'Blanco') {
            $list[] = array($arraytotal['equipo'], $arraytotal['nombre']);
        } else {
            $listing[] = array($arraytotal['equipo'], $arraytotal['nombre']);
        }
    }

    $datos['blanco'] = $list;
    $datos['negro'] = $listing;

    header('Content-Type: application/json');
    echo json_encode($datos);
}
