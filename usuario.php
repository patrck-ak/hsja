<!DOCTYPE html>
<html lang="pt-br">
  <?php include_once ("db.php"); ?>

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
    function nav() {
      window.location.replace("http://localhost/hsja/listagem.php")
    }
    </script>
    <!--   -->
    <title>Selecionar usuário</title>
  </head>

  <body>
    <div style=" display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 10px;">
      <form method="post"
        style="margin-top: 25px; border-radius: 10px; display: flex; flex-direction: column; gap: 15px; height: 190px; background-color: #e4e4e7; width: 210px; padding: 15px;">
        <select name="usuario" class="form-select">
          <?php
          $query_usuarios = "SELECT NM_USUARIO FROM usuario ORDER BY CD_USUARIO";
          $query_ids = "SELECT CD_USUARIO FROM usuario ORDER BY CD_USUARIO";
          $query_permissoes = "SELECT * FROM usuario_documento";

          //* query de listagem de usuarios
          $req = $mysqli->query($query_usuarios) or die($mysqli->error);
          $reqIds = $mysqli->query($query_ids) or die($mysqli->error);
          $numUsuarios = mysqli_num_rows($req); //? quantidade de usuarios
          $ids = mysqli_fetch_all($reqIds);
          $usuarios = mysqli_fetch_all($req);

          for ($i = 0; $i < $numUsuarios; $i++) {
            echo "<option value=" . $i + 1 . " >" . implode($usuarios[$i]) . "</option>";
          }
          ?>

        </select>

        <button class="btn btn-primary" type="submit" id="select">Selecionar Usuário</button>
        <?php
        //? Define o usuario selecionado
        $opcao = @$_POST['usuario'];
        if (isset($opcao)) {
          setcookie("usuario", $opcao, time() + (60 * 24));
          $opc = @$_COOKIE['usuario'];
        }
        ?>

      </form>
      <button class="btn btn-success" onclick="nav()">Ir para Listagem</button>
    </div>

  </body>

</html>