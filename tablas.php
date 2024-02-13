<?php

include 'Classes/config.php';
include 'Classes/clsSqlServer.php';

$SqlSrv            = new SqlServer();

$SqlSrv->dbConnect();




?>
<!DOCTYPE html>
<html class="wide wow-animation" lang="en">

<head>
  <!-- Site Title-->
  <title>Futbol 3 ...</title>
  <meta name="format-detection" content="telephone=no">
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta charset="utf-8">
  <link rel="icon" href="images/favicon.ico" type="image/x-icon">
  <!-- Stylesheets-->
  <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Kanit:300,400,500,500i,600,900%7CRoboto:400,900">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/fonts.css">
  <link rel="stylesheet" href="css/style.css">
  <style>
    .ie-panel {
      display: none;
      background: #212121;
      padding: 10px 0;
      box-shadow: 3px 3px 5px 0 rgba(0, 0, 0, .3);
      clear: both;
      text-align: center;
      position: relative;
      z-index: 1;
    }

    html.ie-10 .ie-panel,
    html.lt-ie-10 .ie-panel {
      display: block;
    }

    <style>body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      background-color: #2ecc71;
    }

    .cancha {
      position: relative;
      width: 600px;
      height: 360px;
      background-color: #4CAF50;
      border-radius: 15px;
      border: 10px solid #fff;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
    }

    .arco {
      position: absolute;
      width: 100px;
      height: 200px;
      background-color: #fff;
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    #arco-izquierdo {
      left: 0;
      top: 80px;
    }

    #arco-derecho {
      right: 0;
      top: 80px;
    }

    .linea-central {
      position: absolute;
      width: 2px;
      height: 100%;
      background-color: #fff;
      left: 50%;
      transform: translateX(-50%);
    }

    .punto-central {
      position: absolute;
      width: 10px;
      height: 10px;
      background-color: #fff;
      border-radius: 50%;
      left: 50%;
      top: 50%;
      transform: translate(-50%, -50%);
    }

    .nombres-jugadores {
      position: absolute;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: space-between;
      color: #fff;
      font-size: 14px;
      text-align: center;
    }

    #nombres-izquierda {
      left: 10px;
      top: 50%;
      transform: translateY(-35%);
    }

    #nombres-derecha {
      right: 10px;
      top: 50%;
      transform: translateY(-35%);
    }

    .nombre-jugador {
      margin-bottom: 5px;
    }
  </style>
  <script>
    function buscar(mes) {

      var parametros = {
        "accion": "buscardoparti",
        "mes": mes
      };

      $.ajax({
        data: parametros,
        url: 'buscardoparti.php',
        type: 'post',
        success: function(response) {
          Crear(response);
        }
      });

    }

    function Crear(objeto) {
      // console.log(objeto);
      document.getElementById('relleno').innerHTML = '';
      var contenedor = document.getElementById('relleno');
      for (var iDatos = 0; iDatos < objeto.length; iDatos++) {
        var trDatos = document.createElement('tr');
        trDatos.setAttribute('onclick', 'ModalCanchita(' + objeto[iDatos][3] + ');');
        var Fecha = String(objeto[iDatos][0]);
        var Equipo = String(objeto[iDatos][1]);
        var DifGoles = String(objeto[iDatos][2]);
        // ----------------------------------------
        var tdFecha = document.createElement('td');
        var tdFechaTexto = document.createTextNode(Fecha);
        // ----------------------------------------
        tdFecha.appendChild(tdFechaTexto);
        trDatos.appendChild(tdFecha);
        // ----------------------------------------
        // ----------------------------------------
        var tdEquipo = document.createElement('td');
        var tdEquipoTexto = document.createTextNode(Equipo);
        // ----------------------------------------
        tdEquipo.appendChild(tdEquipoTexto);
        trDatos.appendChild(tdEquipo);
        // ----------------------------------------
        var tdDifGoles = document.createElement('td');
        var tdDifGolesTexto = document.createTextNode("+" + DifGoles);
        // ----------------------------------------
        tdDifGoles.appendChild(tdDifGolesTexto);
        trDatos.appendChild(tdDifGoles);
        // ----------------------------------------
        // ----------------------------------------
        contenedor.appendChild(trDatos);
      }

    }

    function ModalCanchita(idpartido) {
      $('#ModalCancha').modal('show');

      var parametros = {
        "accion": "buscarjugadordoparti",
        "idpartido": idpartido
      };

      $.ajax({
        data: parametros,
        url: 'buscardoparti.php',
        type: 'post',
        success: function(response) {
          CrearJugadoresEnCancha(response);
        }
      });
    }

    function CrearJugadoresEnCancha(objeto) {

      var recorrerBlanco = Number(objeto['blanco'].length);
      var recorrerNegro = Number(objeto['negro'].length);

      var contblanco = document.getElementById('nombres-izquierda');
      var contnegro = document.getElementById('nombres-derecha');
      document.getElementById('nombres-izquierda').innerHTML = '';
      document.getElementById('nombres-derecha').innerHTML = '';

      for (let index = 0; index < recorrerBlanco; index++) {
        var div = document.createElement('div');
        div.setAttribute('class', 'nombre-jugador');
        // ----------------------------------------
        var input = document.createElement('input');
        input.setAttribute('type', 'text');
        input.setAttribute('disabled', 'true');
        input.setAttribute('style', 'width: 95px');
        input.setAttribute('value', objeto['blanco'][index][1]);
        // ----------------------------------------
        div.appendChild(input);
        contblanco.appendChild(div);
      }

      for (let index = 0; index < recorrerNegro; index++) {
        var div = document.createElement('div');
        div.setAttribute('class', 'nombre-jugador');
        // ----------------------------------------
        var input = document.createElement('input');
        input.setAttribute('type', 'text');
        input.setAttribute('disabled', 'true');
        input.setAttribute('style', 'width: 95px');
        input.setAttribute('value', objeto['negro'][index][1]);
        // ----------------------------------------
        div.appendChild(input);
        contnegro.appendChild(div);
      }
    }
  </script>
</head>

<body>
  <div class="ie-panel">
    <a href="http://windows.microsoft.com/en-US/internet-explorer/">
      <img src="images/ie8-panel/warning_bar_0000_us.jpg" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today.">
    </a>
  </div>
  <div class="preloader">
    <div class="preloader-body">
      <div class="preloader-item"></div>
    </div>
  </div>
  <!-- Page-->
  <div class="page">
    <?php include 'Classes/header.php'; ?>
    <!-- Latest News-->
    <section class="section section-md bg-gray-100">
      <div class="container">
        <div class="row row-50">
          <div class="col-lg-12">
            <div class="main-component">
              <!-- Heading Component-->
              <article class="heading-component">
                <div class="heading-component-inner">
                  <h5 class="heading-component-title">Tablas</h5>
                </div>
              </article>
              <div class="row row-30">
                <div class="col-md-12">
                  <!-- Table team-->
                  <article class="heading-component">
                    <div class="heading-component-inner">
                      <a class="button button-xs button-gray" onclick="buscar('10');">Octubre</a>
                      <a class="button button-xs button-gray" onclick="buscar('11');">Noviembre</a>
                      <a class="button button-xs button-gray" onclick="buscar('12');">Diciembre</a>
                      <!-- <a class="button button-xs button-gray" onclick="buscar('12');">Diciembre</a> -->
                    </div>
                  </article>
                  <div class="table-custom-responsive">
                    <table class="table-custom table-standings table-classic">
                      <thead>
                        <tr>
                          <th>Fecha</th>
                          <th>Equipo Ganador</th>
                          <th>Resultado</th>
                        </tr>
                      </thead>

                      <tfoot>
                        <tr>
                          <th>Fecha</th>
                          <th>Equipo Ganador</th>
                          <th>Resultado</th>
                        </tr>
                      </tfoot>

                      <tbody id="relleno">
                        <tr>
                          <td>-</td>
                          <td>-</td>
                          <td>-</td>
                        <tr>
                      </tbody>
                    </table>
                    </table>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <!-- Aside Block-->
        </div>
      </div>
    </section>
    <!-- Page Footer<a class="d-block" href="https://www.templatemonster.com/website-templates/allstar-multi-sports-website-template-63853.html" target="blank"><img class="d-block w-100" src="images/banner-free-02.jpg" alt=""></a> -->
    <footer class="section footer-classic footer-classic-dark context-dark">
      <div class="footer-classic-aside footer-classic-darken">
        <div class="container">
          <div class="layout-justify">
            <!-- Rights-->
            <p class="rights"><span>Futbol 3 Puntos</span><span>&nbsp;&copy;&nbsp;</span><span class="copyright-year"></span><span>.&nbsp;</span><span>Diseñado por <a href="">ProyectosWeb.</a></span></p>
            <nav class="nav-minimal">
              <ul class="nav-minimal-list">
                <li class="active"><a href="index.php">Home</a></li>
                <!-- <li><a href="#">Features</a></li>
                <li><a href="#">Statistics</a></li>
                <li><a href="#">Team</a></li>
                <li><a href="#">News</a></li>
                <li><a href="#">Shop</a></li> -->
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </footer>
  </div>

  <!-- Modal Players-->
  <section class="container-fluid">
    <div class="modal fade" id="ModalCancha">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="cancha">
            <div class="arco" id="arco-izquierdo"></div>
            <div class="arco" id="arco-derecho"></div>
            <div class="linea-central"></div>
            <div class="punto-central"></div>
            <div class="nombres-jugadores" id="nombres-izquierda">
            </div>
            <div class="nombres-jugadores" id="nombres-derecha">
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Global Mailform Output-->
  <div class="snackbars" id="form-output-global"></div>
  <!-- Javascript-->
  <script src="js/core.min.js"></script>
  <script src="js/script.js"></script>
</body>

</html>