<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus�">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

  <title>Menu</title>
  <style>
    hr {
      display: block;
      height: 1px;
      border: 0;
      border-top: 1px solid #ccc;
      margin: 1em 0;
      padding: 0;
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <br>
    <div class="btn-group btn-group-justified">
      <a href="?funcion=jogatore"><button type="button" class="btn btn-primary">Cargar Jugadores</button></a>
      <a href="?funcion=partidensio"><button type="button" class="btn btn-success">Cargar Partido</button></a>
      <a href="?funcion=resultadex"><button type="button" class="btn btn-info">Cargar Resultado</button></a>
      <a href="?funcion=stadistic"><button type="button" class="btn btn-warning">Ver Estadistica</button></a>

    </div>
    <br>
    <br>
    <?php
    if ($_GET['funcion'] == "jogatore") {
      include "jogo.php";
    } else if ($_GET['funcion'] == "partidensio") {
      include "partidos.php";
    } else if ($_GET['funcion'] == "resultadex") {
      include "resultadex.php";
    } else if ($_GET['funcion'] == "stadistic") {
      include "stadistic.php";
    }

    ?>

  </div>
</body>

</html>