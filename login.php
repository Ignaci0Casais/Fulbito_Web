<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link rel="stylesheet" href="css/master.css">
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
  <script>
    function login() {
      var user = document.getElementById('usuario').value;
      var pass = document.getElementById('contrasena').value;

      var parametros = {
        "user": user,
        "pass": pass
      };

      $.ajax({
        data: parametros,
        url: 'logiandoando.php',
        type: 'post',
        success: function(response) {
          if (response == 'ok') {
            location.href = 'https://fulbito.latiendaweb.com.ar/stats.php';
          } else {
            alert('Segui participando!');
          }
        }
      });

    }
  </script>
</head>

<body>

  <div class="login-box">
    <img src="images/logosoccer.png" class="avatar" alt="Avatar Image">
    <h1>Ingresa Aqui</h1>
    <!-- USERNAME INPUT -->
    <label for="username">Usuario</label>
    <input id="usuario" type="text" placeholder="Ingrese Usuario">
    <!-- PASSWORD INPUT -->
    <label for="password">Contraseña</label>
    <input id="contrasena" type="password" onchange="login();" placeholder="Ingrese Contraseña">
    <input type="submit" value="Log In" onclick="login();">
    <a href="index.php">Perdio su contraseña?</a><br>
    <a href="index.php">No tienes una cuenta?</a>

  </div>
</body>

</html>