<?php
include_once ("db.php");
date_default_timezone_set('America/Sao_Paulo');
?>

<!DOCTYPE html>
<html lang="pt-br">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/cadastro.css">
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
    <div class="containerExt">

      <!-- cabeçalho do formulário -->
      <form method="get" action="">
        <div class="header">
          <?php
          //? validar se o usuário selecionado tem permissao para editar documentos.
          $usuario = @$_COOKIE['usuario'];
          if (isset($usuario)) {
            $query_doc_acesso = "SELECT CD_DOCUMENTO FROM usuario_documento WHERE CD_USUARIO = $usuario AND TP_ACESSO = 'CO'";
            $req_acesso = $mysqli->query($query_doc_acesso) or die('deu pau');
            $acesso_rows = mysqli_num_rows($req_acesso);
            $acesso = mysqli_fetch_array($req_acesso);
            if (is_array($acesso)) {
              $docId = $acesso[0];
            } else {
              echo '<b>Usuário não tem permissão para cadastrar documentos.</b> <br/>';
              echo "<input class='btn btn-success' style='margin-left: 10px;' value='Voltar para listagem' type='button' onclick='nav(2)'>";
              exit(); //? para a renderização da página.
            }
          }
          ?>

          <select title="Documento" id="documento" name="documento" class="form-select selectUser">
            <?php
            if (isset($usuario) && isset($docId)) {

              //? verificação se o usuario selecionado tem permissão de CA
              $query_docs = "SELECT DS_DOCUMENTO FROM documento WHERE CD_DOCUMENTO = $docId;";
              //* query de listagem de usuarios
              $req = $mysqli->query($query_docs) or die("Erro ao conectar com o banco.: $mysqli->error");
              $rows = mysqli_num_rows($req); //? quantidade de usuarios
              $docs = mysqli_fetch_all($req);

              for ($i = 0; $i < $rows; $i++) { //? loop para listar nome dos docs
                echo "<option value=" . $docId . ">" . implode($docs[$i]) . "</option>";
              }
              var_dump($rows);
            } else { //? caso n seja encontrado nenhum usuário selecionado, mostra mensagem
              echo '<option>Nenhum usuário selecionado.</option>';
            }

            ?>

          </select>

          <button class="btn btn-primary" style="position: relative; margin-left: 30px;" type="submit"> Selecionar
          </button>
        </div>
      </form>
      <form method="post" name="registro">
        <span class="containerHeader">
          <label class="labelData" for="dataAtual">Data</label>
          <input class="form-control " type="date" name="dataAtual" id="dataAtual">
          <label class="labelNr" for="dataAtual">N° Registro</label>
          <input class="form-control" type="number" name="nrRegistro" id="nrRegistro">
        </span>
        <hr>
        <table class="limiter table text-center rounded p-10">
          <thead class="table-dark">
            <tr>
              <th scope="col">Pergunta</th>
              <th scope="col">Resposta</th>
            </tr>
          </thead>
          <tbody>

            <?php
            function tpResposta($tp) // Retorno mysql -> Type input
            {
              $r = '';
              switch ($tp) { //? função para retornar de acordo com o banco, qual tipo de input será renderizado.
                case 'N':
                  $r = 'number';
                  break;
                case 'T':
                  $r = 'text';
                  break;
                case 'D':
                  $r = 'date';
                  break;
                case 'X':
                  $r = 'file';
                  break;
                default: //? caso ocorra algum problema será texto
                  $r = 'text';
              }
              return $r;
            }

            $doc = @$_GET["documento"]; // recebe cd_documento
            if (isset($doc)) {
              $req = $mysqli->query("SELECT CD_PERGUNTA FROM documento_pergunta WHERE CD_DOCUMENTO = $doc"); // perguntas do doc selecionado.
              $lista = mysqli_fetch_all($req);
              $rows = mysqli_num_rows($req);
              $ind = 1;
              for ($i = 0; $i < $rows; $i++) { // loop para retonar e printar todas as perguntas de cada doc
            
                $ind++;
                $a = implode($lista[$i]); // recebe lista dos CD_PERGUNTA
                $r = $mysqli->query("SELECT DS_PERGUNTA FROM pergunta WHERE CD_PERGUNTA = $a"); // recebe titulo das perguntas
                $f = mysqli_fetch_all($r);
                $c = implode($f[0]);

                //? retornar tp de pergunta
                if (isset($c)) {
                  $pgt = $mysqli->query("SELECT TP_REPOSTA FROM pergunta WHERE CD_PERGUNTA = $a"); // recebe TP_PERGUNTA
                  $tpgt = implode(mysqli_fetch_all($pgt)[0]);
                  echo "<tr> <td> <input disabled class='form-control' type='text' id='$a' value='$c'> </td> <td> <input name='$a' class='form-control' type=" . tpResposta($tpgt) . "> </td> </tr>";
                }
              }
            }

            $num = @$_POST['1']; // 1 number
            $text = @$_POST['2']; // 2 Nome
            $valid = @$_POST['3']; // 3 Data
            $anexo = @$_POST['4']; // 4 Anexo
            $obs = @$_POST['5']; // 5 Obs
            $data = @$_POST['dataAtual'];
            $nrRegistro = @$_POST['nrRegistro'];

            function stringToInt($stg)
            {
              $a = 0;
              switch ($stg) {
                case '1':
                  $a = 1;
                  break;
                case '2':
                  $a = 2;
                  break;
              }
              return $a;
            }

            if (isset($doc)) {
              $formated = stringToInt($doc);
              if ($num || $text || $valid || $anexo || $obs) {
                //? registrar dado no documento_registro
                $q = $mysqli->query("INSERT INTO documento_registro (CD_DOCUMENTO, DT_REGISTRO, NR_REGISTRO) VALUES ('$formated', '$data', '$nrRegistro')");
                if ($q) {
                  echo '<div style="position: relative; display: flex; justify-content: center;"><p">Cadastro Efetuado</p></div>';
                } else {
                  echo '<div style="position: relative; display: flex; justify-content: center;"><p">Erro ao cadastrar</p></div>';
                }

                //? retornar ultimo CD_DOCUMENTO
                $h = $mysqli->query("SELECT CD_DOCUMENTO_REGISTRO FROM documento_registro ORDER BY CD_DOCUMENTO_REGISTRO DESC LIMIT 1");
                $ultimoRegistro = implode(mysqli_fetch_all($h)[0]);

                //? registrar resposta
                if ($num) { // numero de declaração
                  $q = $mysqli->query("INSERT INTO resposta (CD_DOCUMENTO_PERGUNTA, CD_DOCUMENTO_REGISTRO, DS_RESPOSTA_NUMERO) VALUES (1, $ultimoRegistro, $num)");
                }
                if ($text) { // nome
                  $q = $mysqli->query("INSERT INTO resposta (CD_DOCUMENTO_PERGUNTA, CD_DOCUMENTO_REGISTRO, DS_RESPOSTA_TEXTO) VALUES (2, $ultimoRegistro, '$text')");
                }
                if ($valid) { // data de validade
                  $q = $mysqli->query("INSERT INTO resposta (CD_DOCUMENTO_PERGUNTA, CD_DOCUMENTO_REGISTRO, DS_RESPOSTA_DATA) VALUES (3, $ultimoRegistro, '$valid')");
                }
                if ($anexo) {
                  $q = $mysqli->query("INSERT INTO resposta (CD_DOCUMENTO_PERGUNTA, CD_DOCUMENTO_REGISTRO, DS_RESPOSTA_ANEXO) VALUES (4, $ultimoRegistro, '$anexo')");
                }
                if ($obs) {
                  if ($formated == 1) {
                    $q = $mysqli->query("INSERT INTO resposta (CD_DOCUMENTO_PERGUNTA, CD_DOCUMENTO_REGISTRO, DS_RESPOSTA_TEXTO) VALUES (5, $ultimoRegistro, '$obs')");
                  } elseif ($formated == 2) {
                    $q = $mysqli->query("INSERT INTO resposta (CD_DOCUMENTO_PERGUNTA, CD_DOCUMENTO_REGISTRO, DS_RESPOSTA_TEXTO) VALUES (6, $ultimoRegistro, '$obs')");
                  }
                }
              }
            } else {
              echo '<p style="text-align: center;">Selecione um documento.</p>';
              exit();
            }

            ?>

          </tbody>
        </table>
        <div class="containerBotoes">
          <input class="btn btn-success" type="submit" id="inserirDoc" value="Cadastrar">
          <input class="btn btn-success" type="reset" id="limpar">
          <input class="btn btn-success" value="Voltar" type="button" onclick="nav(2)">
        </div>



      </form>
    </div>

  </body>

</html>