<?php

include 'Classes/config.php';
include 'Classes/clsSqlServer.php';

$SqlSrv            = new SqlServer();

$SqlSrv->dbConnect();

$sqltorneos = "SELECT j.idjugador, j.nombre, count(pd.equipo) as partidosJugados, SUM(CASE WHEN pd.resultado = 'G' THEN 1 ELSE 0 END) AS ganados, SUM(CASE WHEN resultado = 'P' THEN 1 ELSE 0 END) AS perdidos, SUM(CASE WHEN resultado = 'E' THEN 1 ELSE 0 END) AS empatados, SUM(pd.difgoles) as difgoles FROM jugadores j, partido_detalle pd, partidos p WHERE j.idjugador=pd.idjugador AND pd.idpartido=p.idpartido group by j.idjugador order by partidosJugados desc, difgoles desc;";

$Sql_result = $SqlSrv->dbQuery($sqltorneos);
$total = $SqlSrv->dbNumRows($Sql_result);

//---------------------------------------------------------------------------------------//

$ultimospartidos = "SELECT p.idpartido, DATE_FORMAT(p.fechapartido, '%d/%m/%Y') as fechapartido FROM partidos p WHERE p.status=1 ORDER BY p.fechapartido desc;";
$sql_ultimospartidos = $SqlSrv->dbQuery($ultimospartidos);
$totalpartidos = $SqlSrv->dbNumRows($sql_ultimospartidos);

//---------------------------------------------------------------------------------------//
//stats mayor cantidad ganados y perdidos
$sqldatos = "SELECT pd.idjugador, p.idpartido, pd.resultado, j.nombre FROM partidos p, partido_detalle pd, jugadores j WHERE p.status=1 and p.idpartido=pd.idpartido and pd.idjugador=j.idjugador order by pd.idjugador, p.idpartido;";
$resultdatos = $SqlSrv->dbQuery($sqldatos);
$numrowsdatos = $SqlSrv->dbNumRows($resultdatos);

$Racha = 0;
$idjugador = 1;
$MaxRachaGanados = 0;
$MaxRachaPerdidos = 0;

for ($i = 0; $i < $numrowsdatos; $i++) {
  $rowdatos = $SqlSrv->dbArray($resultdatos);

  if ($rowdatos['idjugador'] == $idjugador) {
    if ($rowdatos['resultado'] == 'G' or $rowdatos['resultado'] == 'E') {
      $Racha += 1;
      $nombreanterior = $rowdatos['nombre'];
    } else {
      if ($MaxRachaGanados < $Racha) {
        $nombreMaxRachaGanados = $rowdatos['nombre'];
        $MaxRachaGanados = $Racha;
        $Racha = 0;
      } else {
        $Racha = 0;
      }
    }
  } else {
    $idjugador = $rowdatos['idjugador'];
    if ($MaxRachaGanadosGanados < $Racha) {
      $nombreMaxRachaGanados = $nombreanterior;
      $MaxRachaGanados = $Racha;
      $Racha = 0;
    } else {
      $Racha = 0;
    }

    if ($rowdatos['resultado'] == 'G' or $rowdatos['resultado'] == 'E') {
      $Racha += 1;
    } else {
      if ($MaxRachaGanados < $Racha) {
        $nombreMaxRachaGanados = $rowdatos['nombre'];
        $MaxRachaGanados = $Racha;
        $Racha = 0;
      }
    }
  }

  // echo '<script type="text/javascript">console.log("NombreMaxRachaGanados: ' . $nombreMaxRachaGanados . ' / MaxRachaGanados: ' . $MaxRachaGanados . ' / Racha: ' . $Racha . ' / RowdatosIdJugador: ' . $rowdatos['idjugador'] . ' / Idjugador: ' . $idjugador . '");</script>';
}


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
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
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
  </style>
  <script>
    function MostrarModal(idjugador) {
      $('#ModalJugadores').modal('show');

      var parametros = {
        "idjugador": idjugador
      };

      $.ajax({
        data: parametros,
        url: 'historialjugadorpartido.php',
        type: 'post',
        success: function(response) {
          creadatos(response);
        }
      });
    }

    function creadatos(objeto) {
    contenedorDataTable = '';
    document.getElementById('ContenedorPartidos').innerHTML = '';
    // -------------------------------------------
    var contenedor = document.getElementById('ContenedorPartidos');
    var titulos = Array('Fecha', 'Equipo', 'Resultado');
    var table = document.createElement('table');
    table.setAttribute('class', 'table-custom table-standings table-classic');
    // table.setAttribute('style', 'width:100%');
    table.setAttribute('id', 'TablaPartidos');
    var thead = document.createElement('thead');
    var trTitulo = document.createElement('tr');
    for (var iTitulos = 0; iTitulos < titulos.length; iTitulos++) {
      var th = document.createElement('th');
      var thTexto = document.createTextNode(titulos[iTitulos]);
      th.appendChild(thTexto);
      trTitulo.appendChild(th);
    }
    thead.appendChild(trTitulo);
    table.appendChild(thead);
    /* ---------------------------------------- */
    if (objeto != '') {
      var tbody = document.createElement('tbody');
      for (var iDatos = 0; iDatos < objeto.length; iDatos++) {
        var trDatos = document.createElement('tr');
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
        if (DifGoles > 0) {
          tdDifGoles.setAttribute('style', 'color: green');
        } else if (DifGoles < 0) {
          tdDifGoles.setAttribute('style', 'color: red');
        } else {
          tdDifGoles.setAttribute('style', 'color: white');
        }
        var tdDifGolesTexto = document.createTextNode(DifGoles);
        // ----------------------------------------
        tdDifGoles.appendChild(tdDifGolesTexto);
        trDatos.appendChild(tdDifGoles);
        // ----------------------------------------
        // ----------------------------------------
        tbody.appendChild(trDatos);
      }
      table.appendChild(tbody);
    }
    /* ---------------------------------------- */
    // -------------------------------------------
    contenedor.appendChild(table);
    contenedorDataTable = dataTable('TablaPartidos');
  }

  function dataTable(objeto) {
    $('#' + objeto).DataTable({
      'paging': true,
      'ordering': false,
      searching: false,
      "dom": 'rtip'
    })
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
    <?php include 'Classes/header.php' ;?>
    
    <!-- Latest News-->
    <section class="section section-md bg-gray-100">
      <div class="container">
        <div class="row row-50">
          <div class="col-lg-8">
            <div class="main-component">
              <!-- Heading Component-->
              <article class="heading-component">
                <div class="heading-component-inner">
                  <h5 class="heading-component-title">Tablas</h5>
                  <a class="button button-xs button-gray-outline" href="tablas.php">All</a>
                </div>
              </article>
              <div class="row row-30">
                <div class="col-md-8">
                  <!-- Post Future-->
                  <article class="heading-component">
                    <div class="heading-component-inner">
                      <h5 class="heading-component-title">Tabla general</h5>
                      <a class="button button-xs button-gray-outline" href="#" onclick="alert('Hace 2 meses que no pierdo!');">Click Aqui</a>
                    </div>
                  </article>
                  <!-- Table team-->
                  <div class="table-custom-responsive">
                    <table class="table-custom table-standings table-classic" id="tablageneral">
                      <thead>
                        <tr>
                          <th>Nombre</th>
                          <th>Partidos Jugados</th>
                          <th>Ganados</th>
                          <th>Empatados</th>
                          <th>Perdidos</th>
                          <th>Dif Goles</th>
                        </tr>
                      </thead>

                      <tfoot>
                        <tr>
                          <th>Nombre</th>
                          <th>Partidos Jugados</th>
                          <th>Ganados</th>
                          <th>Empatados</th>
                          <th>Perdidos</th>
                          <th>Dif Goles</th>
                        </tr>
                      </tfoot>

                      <tbody>

                        <?php
                        for ($i = 0; $i < $total; $i++) {
                          $rows = $SqlSrv->dbArray($Sql_result);
                        ?>

                          <tr onclick="MostrarModal('<?php echo $rows['idjugador']; ?>');">
                            <td><?php echo $rows['nombre']; ?></td>
                            <td><?php echo $rows['partidosJugados']; ?></td>
                            <td style="color: green"><?php echo $rows['ganados']; ?></td>
                            <td><?php echo $rows['empatados']; ?></td>
                            <td style="color: red"><?php echo $rows['perdidos']; ?></td>
                            <?php if ($rows['difgoles'] > 0) {
                              $colordifgoles = 'green';
                            } else {
                              $colordifgoles = 'red';
                            }
                            ?>
                            <td style="color: <?php echo $colordifgoles; ?>"><?php echo $rows['difgoles']; ?></td>
                          </tr>
                        <?php
                        }
                        ?>

                      </tbody>
                    </table>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <!-- Aside Block-->
          <div class="col-lg-4">
            <aside class="aside-components">
              <div class="aside-component">
                <div class="owl-carousel-outer-navigation-1">
                  <!-- Heading Component-->
                  <article class="heading-component">
                    <div class="heading-component-inner">
                      <h5 class="heading-component-title">Ultimos Partidos</h5>
                      <div class="owl-carousel-arrows-outline">
                        <div class="owl-nav">
                          <button class="owl-arrow owl-arrow-prev"></button>
                          <button class="owl-arrow owl-arrow-next"></button>
                        </div>
                      </div>
                    </div>
                  </article>
                  <!-- Owl Carousel-->

                  <div class="owl-carousel owl-spacing-1" data-items="1" data-dots="false" data-nav="true" data-autoplay="true" data-autoplay-speed="4000" data-stage-padding="0" data-loop="true" data-margin="30" data-mouse-drag="false" data-animation-in="fadeIn" data-animation-out="fadeOut" data-nav-custom=".owl-carousel-outer-navigation-1">
                    <!-- Game Result Creative-->
                    <?php for ($i = 0; $i < $totalpartidos; $i++) {
                      $rowpartido = $SqlSrv->dbArray($sql_ultimospartidos);

                      $selectpartido = "SELECT idpartido, idjugador, equipo, resultado, difgoles FROM partido_detalle WHERE idpartido=" . $rowpartido['idpartido'] . " and resultado='G' limit 1;";
                      $sql_selectpartido = $SqlSrv->dbQuery($selectpartido);
                      $rowequipos = $SqlSrv->dbArray($sql_selectpartido);

                    ?>

                      <article class="game-result game-result-creative">
                        <div class="game-result-main-vertical">
                          <div class="game-result-team game-result-team-horizontal game-result-team-first">
                            <figure class="game-result-team-figure"><img src="images/whiteteam.png" alt="" width="101" height="111" />
                            </figure>
                            <div class="game-result-team-title">
                              <div class="game-result-team-name"><?php echo 'Equipo blanco' ?></div>
                              <!-- <div class="game-result-team-country">Los angeles</div> -->
                            </div>
                            <?php if ($rowequipos['equipo'] == 'Blanco' and $rowequipos['resultado'] == 'G') { ?>
                              <div class="game-result-score game-result-score-big game-result-team-win"><?php echo $rowequipos['difgoles']; ?>
                                <span class="game-result-team-label game-result-team-label-right">Win</span>
                              <?php } else {
                              ?>
                                <div class="game-result-score game-result-score-big game-result-team-win">0
                                <?php
                              }
                                ?>

                                </div>
                              </div><span class="game-result-team-divider">VS</span>
                              <div class="game-result-team game-result-team-horizontal game-result-team-second">
                                <figure class="game-result-team-figure"><img src="images/blackteam.png" alt="" width="101" height="111" />
                                </figure>
                                <div class="game-result-team-title">
                                  <div class="game-result-team-name"><?php echo 'Equipo negro' ?></div>
                                  <!-- <div class="game-result-team-country">Spain</div> -->
                                </div>
                                <?php if ($rowequipos['equipo'] == 'Negro' and $rowequipos['resultado'] == 'G') { ?>
                                  <div class="game-result-score game-result-score-big game-result-team-win"><?php echo $rowequipos['difgoles']; ?>
                                    <span class="game-result-team-label game-result-team-label-right">Win</span>
                                  <?php } else {
                                  ?>
                                    <div class="game-result-score game-result-score-big game-result-team-win">0
                                    <?php
                                  }
                                    ?>
                                    </div>
                                  </div>
                                  <div class="game-result-footer">
                                    <ul class="game-result-details">
                                      <li>Club 3 Puntos, CABA</li>
                                      <li>
                                        <time datetime="2020-04-14"><?php echo $rowpartido['fechapartido']; ?></time>
                                      </li>
                                    </ul>
                                  </div>
                      </article>

                    <?php } ?>

                  </div>
                </div>

                <!-- <div class="aside-component">
                  
                  <article class="heading-component">
                    <div class="heading-component-inner">
                      <h5 class="heading-component-title">Follow us</h5>
                    </div>
                  </article>
                  
                  <div class="group-sm group-flex"><a class="button-media button-media-facebook" href="#">
                      <h4 class="button-media-title">50k</h4>
                      <p class="button-media-action">Like<span class="icon material-icons-add_circle_outline icon-sm"></span></p><span class="button-media-icon fa-facebook"></span>
                    </a><a class="button-media button-media-twitter" href="#">
                      <h4 class="button-media-title">120k</h4>
                      <p class="button-media-action">Follow<span class="icon material-icons-add_circle_outline icon-sm"></span></p><span class="button-media-icon fa-twitter"></span>
                    </a><a class="button-media button-media-google" href="#">
                      <h4 class="button-media-title">15k</h4>
                      <p class="button-media-action">Follow<span class="icon material-icons-add_circle_outline icon-sm"></span></p><span class="button-media-icon fa-google"></span>
                    </a><a class="button-media button-media-instagram" href="#">
                      <h4 class="button-media-title">85k</h4>
                      <p class="button-media-action">Follow<span class="icon material-icons-add_circle_outline icon-sm"></span></p><span class="button-media-icon fa-instagram"></span>
                    </a></div>
                </div> -->
                <!-- <div class="aside-component">
                  <article class="heading-component">
                    <div class="heading-component-inner">
                      <h5 class="heading-component-title">Premios</h5>
                    </div>
                  </article>
                  <div class="owl-carousel owl-carousel-dots-modern awards-carousel" data-items="1" data-autoplay="true" data-autoplay-speed="4000" data-dots="true" data-nav="false" data-stage-padding="0" data-loop="true" data-margin="0" data-mouse-drag="true">
                    <div class="awards-item">
                      <div class="awards-item-main">
                        <h4 class="awards-item-title"><span class="text-accent">Mejor</span>Racha
                        </h4>
                        <div class="divider"></div>
                        <h5 class="awards-item-time">Noviembre 2023</h5>
                      </div>
                      <div class="awards-item-aside"> <img src="images/thumbnail-minimal-1-67x147.png" alt="" width="67" height="147" />
                      </div>
                    </div>
                    <div class="awards-item">
                      <div class="awards-item-main">
                        <h4 class="awards-item-title"><span class="text-accent">Peor</span>Racha
                        </h4>
                        <div class="divider"></div>
                        <h5 class="awards-item-time">Noviembre 2023</h5>
                      </div>
                      <div class="awards-item-aside"> <img src="images/thumbnail-minimal-2-68x126.png" alt="" width="68" height="126" />
                      </div>
                    </div>
                    <div class="awards-item">
                      <div class="awards-item-main">
                        <h4 class="awards-item-title"><span class="text-accent">Mejor</span>Armador
                        </h4>
                        <div class="divider"></div>
                        <h5 class="awards-item-time">Noviembre 2023</h5>
                      </div>
                      <div class="awards-item-aside"> <img src="images/thumbnail-minimal-3-73x135.png" alt="" width="73" height="135" />
                      </div>
                    </div>
                  </div>
                </div> -->

            </aside>
          </div>
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

    <!-- Modal Video-->
    <div class="modal modal-video fade" id="modal1" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <div class="embed-responsive embed-responsive-16by9">
              <iframe class="embed-responsive-item" width="560" height="315" data-src="https://www.youtube.com/embed/uSzNA2_y46c"></iframe>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <section class="container-fluid">
    <div class="modal fade" id="ModalJugadores">
      <div class="modal-dialog" style="width: 100%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h3 class="modal-title" align="center">Ultimos partidos jugados</h3>
          </div>
          <div class="modal-body" id="ContenedorPartidos" >

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
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

  <script>
    new DataTable('#tablamodal', {
      responsive: true,
      searching: false,
      ordering: false
    });
  </script>

</body>

</html>