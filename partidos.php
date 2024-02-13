<?php

include 'Classes/config.php';
include 'Classes/clsSqlServer.php';


$SqlSrv            = new SqlServer();

$SqlSrv->dbConnect();

$sqltorneos = "SELECT * FROM jugadores WHERE status=0;";

$Sql_result = $SqlSrv->dbQuery($sqltorneos);
$total = $SqlSrv->dbNumRows($Sql_result);

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

            $sqlpartidos = "SELECT * FROM partidos WHERE status=0;";

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
    
    <button class="btn btn-warning btn-sm" onclick="enviarjugadores();">Enviar</button>

    <table id="example" class="table table-dark table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>idJugador</th>
                <th>Nombre</th>
                <th>Equipo</th>
            </tr>
        </thead>

        <tfoot>
            <tr>
                <th>idJugador</th>
                <th>Nombre</th>
                <th>Equipo</th>
            </tr>
        </tfoot>

        <tbody>

            <?php
            for ($i = 0; $i < $total; $i++) {
                $rows = $SqlSrv->dbArray($Sql_result);

                echo "<tr>";
                echo "<td>" . $rows['idjugador'] . "</td>";
                echo "<td>" . $rows['nombre'] . "</td>";
                echo "<td>
                <select name='equipo'>
                <option value=''></option>
                <option value='Blanco'>Blanco</option>
                <option value='Negro'>Negro</option>
                </select>
                </td>";
                echo "</tr>";
            }
            ?>

        </tbody>
    </table>

    

    <script>
        
        new DataTable('#example', {
            paging: false,
            ordering: false
        });
    
        var CantidadAnotados = Number('0');

        function enviarjugadores() {

            CantidadAnotados = Number('0');

            var partido = document.getElementById('idpartido').value;
            var arrayjugadores = Array();

            var selectores = document.getElementsByName('equipo');

            for (var i = 0; i < selectores.length; i++) {

                var valorSeleccionado = selectores[i].value;

                if (valorSeleccionado != '') {
                    CantidadAnotados += 1;
                }

                arrayjugadores.push({
                    "idjugador": Number(i + 1),
                    "equipo": valorSeleccionado
                });
            }

            FaltanAnotar = Number(10 - CantidadAnotados);

            if (CantidadAnotados < '10') {
                alert('Faltan ' + FaltanAnotar + ' jugadores');
            } else {

                var parametros = {
                    "array": arrayjugadores,
                    "idpartido": partido,
                    "action": 'partido'
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

        }
    </script>

</body>

</html>