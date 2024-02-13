<?php

include 'Classes/config.php';
include 'Classes/clsSqlServer.php';


$SqlSrv            = new SqlServer();

$SqlSrv->dbConnect();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futbol</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- nose que pao aca esto iba al fondo y ahora tiene que ir aca-->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</head>

<body>
    <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Partido =</label>
        <select id="idpartido">
            <?php

            $sqlpartidos = "SELECT * FROM partidos WHERE status=2;";

            $resultpartidos = $SqlSrv->dbQuery($sqlpartidos);
            $totalpartidos = $SqlSrv->dbNumRows($resultpartidos);


            for ($i = 0; $i < $totalpartidos; $i++) {
                $rowpartido = $SqlSrv->dbArray($resultpartidos);

                $fecha = date_create($rowpartido['fechapartido']);

            ?>
                <option value="<?php echo $rowpartido['idpartido']; ?>"><?php echo date_format($fecha, "l d-m-Y"); ?></option>

            <?php
            }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Quien Gano</label>
        <select id="equipoganador">
            <option value="Blanco">Blanco</option>
            <option value="Negro">Negro</option>
        </select>
        <input type="number" class="form-control" id="numerodegoles">
    </div>
    <button class="btn btn-warning btn-sm" onclick="enviarresultado();">Enviar</button>

    <script>
        function enviarresultado() {

            var partido = document.getElementById('idpartido').value;
            var equipoganado = document.getElementById('equipoganador').value;
            var numerodegoles = document.getElementById('numerodegoles').value;

            var parametros = {
                "idpartido": partido,
                "equipoganado": equipoganado,
                "goles": numerodegoles,
                "action": 'resultadex'
            };

            $.ajax({
                data: parametros,
                url: 'recibedatos.php',
                type: 'post',
                success: function(response) {
                    location.reload();
                }
            });
        }
    </script>

</body>

</html>