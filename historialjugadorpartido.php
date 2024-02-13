<?php
include 'Classes/config.php';
include 'Classes/clsSqlServer.php';

$SqlSrv            = new SqlServer();

//SET  sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

$SqlSrv->dbConnect();


$sqlpartido = "SELECT DATE_FORMAT(p.fechapartido, '%d/%m/%Y') as fechapartido, pd.equipo, pd.difgoles FROM partidos p, partido_detalle pd WHERE p.idpartido=pd.idpartido and pd.idjugador=" . $_POST['idjugador'] . " order by p.idpartido;";
$Sql_result = $SqlSrv->dbQuery($sqlpartido);
$total = $SqlSrv->dbNumRows($Sql_result);

for ($i = 0; $i < $total; $i++) {
    $array = $SqlSrv->dbArray($Sql_result);

    $list[$i] = array($array['fechapartido'], $array['equipo'], $array['difgoles']);
}


header('Content-Type: application/json');
echo json_encode($list);
