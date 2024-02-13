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
        <button class="btn btn-warning btn-sm" onclick="enviarjugadores();">Enviar</button>
    </div>

    <div class="btn-toolbar mb-3" role="toolbar" aria-label="Toolbar with button groups">
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text" id="btnGroupAddon">Nombre Jugador</div>
            </div>
            <input type="text" class="form-control" aria-label="Input group example" aria-describedby="btnGroupAddon">
        </div>
    </div>
    
    <div class="btn-toolbar mb-3" role="toolbar" aria-label="Toolbar with button groups">
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text" id="btnGroupAddon">Dorsal</div>
            </div>
            <input type="number" class="form-control" aria-label="Input group example" aria-describedby="btnGroupAddon">
        </div>
    </div>


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