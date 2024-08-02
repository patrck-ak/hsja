<!DOCTYPE html>
<html lang="pt-br">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/index.css">
    <title>Consulta de documentos</title>

    <script src="./js/mascaras.js"></script>
    <script src="../js/util.js"></script>

    <!-- styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <!--   -->

  </head>

  <body style="display: flex; align-items: center; justify-content: center;">
    <?php include_once ("db.php"); ?>

    <form class="form" method="POST">
      <!-- cabeçalho do formulário -->
      <div class="header">
        <select name="usuario" class="form-select selectUser">
          <?php
          $query_usuarios = "SELECT NM_USUARIO FROM usuario ORDER BY CD_USUARIO";
          $query_ids = "SELECT CD_USUARIO FROM usuario ORDER BY CD_USUARIO";
          $query_permissoes = "SELECT * FROM usuario_documento";

          //* query de listagem de usuarios
          $req = $mysqli->query($query_usuarios) or die("Erro ao conectar ao banco." . $mysqli->error);
          $reqIds = $mysqli->query($query_ids) or die("Erro ao conectar ao banco." . $mysqli->error);
          $numUsuarios = mysqli_num_rows($req); //? quantidade de usuarios
          $ids = mysqli_fetch_all($reqIds);
          $usuarios = mysqli_fetch_all($req);

          for ($i = 0; $i < $numUsuarios; $i++) {
            echo "<option value=" . $i + 1 . " >" . implode($usuarios[$i]) . "</option>";
          }
          ?>
          </optgroup>
        </select>
        <button class="btn btn-primary selectButton" style="position: absolute; left: 200px;" type="submit" id="search">
          Selecionar</button>

        <?php
        //? Define o usuario selecionado
        $selectedUser = @$_POST['usuario'];
        setcookie("usuario", $selectedUser, time() + 15000);
        ?>

        <a href="cadastro.php/">
          <button class=" btn btn-dark" onclick="nav(0)" style="height: fit-content;" type="button"
            id="cadastro">Cadastrar</button>
        </a>
      </div>

      <!--  filtros de busca  -->
      <p style="position: relative; left: 20px; top: 20px;">Período do registro</p>
      <div class="filtros">
        <input type="text" onkeypress="mascara(this, mdata)" maxlength="10" name="data1" class="form-control"
          style="width: 130px; text-align: center;" placeholder="00/00/0000">
        Á
        <input type="text" onkeypress="mascara(this, mdata)" maxlength="10" name="data2" class="form-control"
          style="width: 130px; text-align: center;" placeholder="00/00/0000">
        <input type="text" name="n_registro" class="form-control" style="position: relative; left: 30px; width: 300px;"
          placeholder="Número de Registro">


      </div>
      <hr />

      <div class="margemTab">
        <h4> <?php echo $selectedUser ?></h4> <!-- debug -->
        <table class="table table-bordered table-striped text-center table-hover">
          <thead>
            <tr>
              <th scope="col">Registros</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>a</td>
            </tr>
          </tbody>
        </table>

        <table class="table table-bordered table-striped text-center table-hover">
          <thead>
            <tr>
              <th scope="col">Cadastros</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>a</td>
            </tr>
          </tbody>
        </table>
      </div>

    </form>



    <?php
    $option = @$_POST['selecao'];
    if (isset($option)) {
      echo '<p style="position: absolute; top: 30px;">' . $option . '</p';
    }
    ?>
  </body>

</html>