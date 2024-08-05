<!DOCTYPE html>
<html lang="pt-br">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/index.css">
    <title>Consulta de documentos</title>

    <script src="./js/mascaras.js"></script>
    <script src="./js/util.js" defer></script>

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

    <form class="form" method="GET">
      <!-- cabeçalho do formulário -->
      <div class="header">
        <input class="btn btn-success" value="Voltar" type="button" onclick="nav(3)">
        <?php

        $selectedUser = @$_COOKIE['usuario'];

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
            case 'O':
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

        function formatarDsDOC($entrada)
        {
          $r = '';
          switch ($entrada) {
            case 1:
              $r = "'Certidão Nasc.'";
              break;
            case 2:
              $r = "'Comp. INSS'";
              break;
            default:
              $r = "'Nome não encontrado.'";
              break;
          }
          return $r;
        }
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
      <!-- Cabeçalho -->

      <div class="tableLimiter">
        <table class="table table-bordered table-striped text-center">
          <thead class="table-dark" style="height: 30px;">
            <tr>
              <th scope="col">Registros</th>
            </tr>
          </thead>
          <tbody class="table-bordered table-striped">
            <form method="get">
              <?php
              $qrDocs = "SELECT CD_DOCUMENTO_REGISTRO FROM documento_registro ";
              if (isset($selectedUser) && $selectedUser <= 4) {
                $qr = $mysqli->query($qrDocs) or die('Erro ao listar documentos.');
                $listaRows = mysqli_num_rows($qr);
                $listaDocs = mysqli_fetch_all($qr);
              } else {
                echo '<div style="position: relative; display: flex; justify-content: center; top: 5px; text-align: center; color: red;">Escolha um usuário.</div> <hr />';
                exit();
              }
              for ($i = 0; $i < $listaRows; $i++) {
                $itemDocReg = implode($listaDocs[$i]); // CD DOC REGISTRO
              
                // retorna id do documento
                $q0 = $mysqli->query("SELECT CD_DOCUMENTO FROM documento_registro WHERE CD_DOCUMENTO_REGISTRO = $itemDocReg") or die('');
                $idDoc = implode(mysqli_fetch_all($q0)[0]);

                // retorna data do registro
                $q1 = $mysqli->query("SELECT DT_REGISTRO FROM documento_registro WHERE CD_DOCUMENTO_REGISTRO = $itemDocReg") or die('');
                $dataReg = implode(mysqli_fetch_all($q1)[0]);

                // retorna n de registro
                $q3 = $mysqli->query("SELECT NR_REGISTRO FROM documento_registro WHERE CD_DOCUMENTO_REGISTRO = $itemDocReg") or die('');
                $nReg = implode(mysqli_fetch_all($q3)[0]);

                echo "
              <tr>
                <td>
                  <span style='display: flex; flex-direction: row; gap: 5px;'>
                  <button value='$itemDocReg' name='ver' class='btn btn-sm btn-primary'> <i data-lucide='eye' ></i> </button>
                  <button value='$itemDocReg' name='editar' class='btn btn-sm btn-success'> <i data-lucide='pencil'></i> </button>
                  <button value='$itemDocReg' name='apagar' class='btn btn-sm btn-danger'> <i data-lucide='trash'></i> </button>
                  <input class='form-control' disabled type='text' value=" . formatarDsDOC($idDoc) . " />
                  <input class='form-control' disabled type='date' value='$dataReg' />
                  <input class='form-control' disabled type='number' value='$nReg' />
                  </span>
                </td>
              </tr>";
              }
              ?>
          </tbody>
        </table>

        <!-- Tabela de perguntas -->
        <table class="table table-bordered table-striped text-center">
          <thead class="table-dark" style="height: 30px;">
            <tr>
              <th scope="col">Perguntas</th>
            </tr>
          </thead>
          <tbody class="table-bordered table-striped">
            <?php
            $ver = @$_GET['ver'];
            $editar = @$_GET['editar'];
            $apagar = @$_GET['apagar'];

            if (isset($editar)) {


            }

            // lógida de visualizar registros
            if (isset($ver)) {
              $q = $mysqli->query("SELECT CD_DOCUMENTO_PERGUNTA FROM resposta WHERE CD_DOCUMENTO_REGISTRO = $ver") or die();
              $r = mysqli_num_rows($q);
              $i2 = mysqli_fetch_all($q);
              for ($v = 0; $v < $r; $v++) { //? Retorna todos os códigos de pergunta
                $ctl = 0;
                $vl = implode($i2[$v]); //! código de cada CD_DOCUMENTO_PERGUNTA em string
                // listar descricao, tipo e valor de cada pergunta
                //? Código documento
                $q2 = $mysqli->query("SELECT CD_PERGUNTA FROM documento_pergunta WHERE CD_DOCUMENTO_PERGUNTA = $vl");
                $r2 = mysqli_num_rows($q2);
                $cdP = implode(mysqli_fetch_all($q2)[$ctl]);
                $ctl++;
                for ($n = 0; $n < $r2; $n++) { // retorna descrição de cada pergunta
                  //? descrição de cada pergunta
                  $qrDesc = $mysqli->query("SELECT DS_PERGUNTA FROM pergunta WHERE CD_PERGUNTA = $cdP");
                  $idPgt = implode(mysqli_fetch_all($qrDesc)[0]);
                  //? tipo de cada pergunta
                  $qpgt = $mysqli->query("SELECT TP_REPOSTA FROM pergunta WHERE CD_PERGUNTA = $cdP");
                  $tpPgt = tpResposta(implode(mysqli_fetch_all($qpgt)[0]));
                  //? valor de cada resposta
                  $vlPgt = '';
                  $vlPgt2 = '';
                  switch ($tpPgt) {
                    case 'number':
                      $q1 = $mysqli->query("SELECT DS_RESPOSTA_NUMERO FROM resposta WHERE CD_DOCUMENTO_REGISTRO = $ver AND DS_RESPOSTA_NUMERO IS NOT NULL;") or die();
                      $vlPgt = implode(mysqli_fetch_all($q1)[0]);
                      echo "
                        <tr>
                        <td>
                        <span style='display: flex; flex-direction: row; gap: 10px;'>
                        <input class='form-control' type='text' disabled value='$idPgt'/>
                        <input name='numero' class='form-control' type='$tpPgt' value='$vlPgt'  />
                        </span>
                        </td>
                        </tr>
                        ";
                      break;
                    case 'date':
                      $q5 = $mysqli->query("SELECT DS_RESPOSTA_DATA FROM resposta WHERE CD_DOCUMENTO_REGISTRO= $ver AND DS_RESPOSTA_DATA IS NOT NULL;") or die();
                      $vlPgt = implode(mysqli_fetch_all($q5)[0]);
                      echo "
                        <tr>
                        <td>
                        <span style='display: flex; flex-direction: row; gap: 10px;'>
                        <input class='form-control' type='text' disabled value='$idPgt'/>
                        <input name='data' class='form-control' type='$tpPgt' value='$vlPgt'  />
                        </span>
                        </td>
                        </tr>
                        ";
                      break;
                    case 'file':
                      $q6 = $mysqli->query("SELECT DS_RESPOSTA_ANEXO FROM resposta WHERE CD_DOCUMENTO_REGISTRO = $ver AND DS_RESPOSTA_ANEXO IS NOT NULL;") or die();
                      $vlPgt = implode(mysqli_fetch_all($q6)[0]);
                      echo "
                        <tr>
                        <td>
                        <span style='display: flex; flex-direction: row; gap: 10px;'>
                        <input class='form-control' type='text' disabled value='$idPgt'/>
                        <input name='' class='form-control' type='text' value='$vlPgt'  />
                        <input name='anexo' class='form-control' type='$tpPgt' value='$vlPgt'  />
                        </span>
                        </td>
                        </tr>
                        ";
                      break;
                    case 'text':
                      if ($cdP == 5 || $cdP == 6) {
                        $q7 = $mysqli->query("SELECT DS_RESPOSTA_TEXTO FROM resposta WHERE CD_DOCUMENTO_REGISTRO = $ver AND DS_RESPOSTA_TEXTO IS NOT NULL AND (CD_DOCUMENTO_PERGUNTA = 6 OR CD_DOCUMENTO_PERGUNTA = 5);") or die();
                        $vlPgt = implode(mysqli_fetch_all($q7)[0]);
                        echo "
                          <tr>
                          <td>
                          <span style='display: flex; flex-direction: row; gap: 10px;'>
                          <input class='form-control' type='text' disabled value='$idPgt'/>
                          <input name='obs' class='form-control' type='$tpPgt' value='$vlPgt'  />
                          </span>
                          </td>
                          </tr>
                          ";
                        break;
                      } elseif ($cdP = 2) {
                        $q8 = $mysqli->query("SELECT DS_RESPOSTA_TEXTO FROM resposta WHERE CD_DOCUMENTO_REGISTRO = $ver AND DS_RESPOSTA_TEXTO IS NOT NULL AND CD_DOCUMENTO_PERGUNTA = 2;") or die();
                        $vlPgt = implode(mysqli_fetch_all($q8)[0]);
                        echo "
                          <tr>
                          <td>
                          <span style='display: flex; flex-direction: row; gap: 10px;'>
                          <input class='form-control' type='text' disabled value='$idPgt'/>
                          <input name='nome' class='form-control' type='$tpPgt' value='$vlPgt'  />
                          </span>
                          </td>
                          </tr>
                          ";
                        break;
                        //? CASO CD_DOCUMENTO_PERGUNTA = 5 OU 6 É OBSERVAÇÃO
                        //? CASO CD_DOCUMENTO_PERGUNTA = 2 É NOME
                      }
                    default:
                      echo 'Erro ao listar dados';
                  }


                }
              }



            } elseif (isset($editar)) {
              //? verificar se o usuário selecionado tem permissão.
              if (isset($selectedUser)) {
                $userPerm = $mysqli->query("SELECT CD_DOCUMENTO FROM usuario_documento WHERE CD_USUARIO = $selectedUser AND TP_ACESSO = 'CA'");
                $permsRows = mysqli_num_rows($userPerm);
                $perms = mysqli_fetch_all($userPerm);

                $nome = @$_GET['nome'];
                $num = @$_GET['numero'];
                $data = @$_GET['data'];
                $anexo = @$_GET['anexo'];
                $obs = @$_GET['obs'];

                if (isset($nome)) {
                  $qrcdderesposta = $mysqli->query("SELECT CD_RESPOSTA FROM resposta WHERE CD_DOCUMENTO_REGISTRO=$editar AND CD_DOCUMENTO_PERGUNTA=2");
                  $idResposta0 = implode(mysqli_fetch_all($qrcdderesposta)[0]);
                  $q11 = $mysqli->query("UPDATE resposta SET DS_RESPOSTA_TEXTO='$nome' WHERE CD_RESPOSTA='$idResposta0' AND CD_DOCUMENTO_PERGUNTA = 2");
                }
                if (isset($num)) {
                  $qrcdderespostanum = $mysqli->query("SELECT CD_RESPOSTA FROM resposta WHERE CD_DOCUMENTO_REGISTRO=$editar AND CD_DOCUMENTO_PERGUNTA=1");
                  $idResposta1 = implode(mysqli_fetch_all($qrcdderespostanum)[0]);
                  $q12 = $mysqli->query("UPDATE resposta SET DS_RESPOSTA_NUMERO='$num' WHERE CD_RESPOSTA='$idResposta1' AND CD_DOCUMENTO_PERGUNTA=1");
                }
                if (isset($data)) {
                  $qrcdderespostadata = $mysqli->query("SELECT CD_RESPOSTA FROM resposta WHERE CD_DOCUMENTO_REGISTRO=$editar AND CD_DOCUMENTO_PERGUNTA=3");
                  $idResposta2 = implode(mysqli_fetch_all($qrcdderespostadata)[0]);
                  $q13 = $mysqli->query("UPDATE resposta SET DS_RESPOSTA_NUMERO='$data' WHERE CD_RESPOSTA='$idResposta2' AND CD_DOCUMENTO_PERGUNTA=3");
                }
                if (isset($anexo)) {
                  $qrcdderespostaanex = $mysqli->query("SELECT CD_RESPOSTA FROM resposta WHERE CD_DOCUMENTO_REGISTRO=$editar AND CD_DOCUMENTO_PERGUNTA=4");
                  $idResposta3 = implode(mysqli_fetch_all($qrcdderespostaanex)[0]);
                  $q13 = $mysqli->query("UPDATE resposta SET DS_RESPOSTA_NUMERO='$data' WHERE CD_RESPOSTA='$idResposta3' AND CD_DOCUMENTO_PERGUNTA=4");
                }
                if (isset($obs)) {
                  $qrcdderespostaobs = $mysqli->query("SELECT CD_RESPOSTA FROM resposta WHERE CD_DOCUMENTO_REGISTRO=$editar AND (CD_DOCUMENTO_PERGUNTA=5 OR CD_DOCUMENTO_PERGUNTA=6)");
                  $idResposta4 = implode(mysqli_fetch_all($qrcdderespostaobs)[0]);
                  $q13 = $mysqli->query("UPDATE resposta SET DS_RESPOSTA_NUMERO='$data' WHERE CD_RESPOSTA='$idResposta4' AND CD_DOCUMENTO_PERGUNTA=5");
                }

              }
            } elseif (isset($apagar)) {
              $qrDelResposta = $mysqli->query("DELETE from resposta WHERE CD_DOCUMENTO_REGISTRO='$apagar';");
              $qrDelRegistro = $mysqli->query("DELETE from documento_registro WHERE CD_DOCUMENTO_REGISTRO='$apagar';");
              if (isset($qrDelResposta) || isset($qrDelRegistro)) {
                echo "Registro apagado do banco: $apagar";
              }
            }
            ?>
    </form>
    </tbody>
    </table>
    </div>
    </form>

    <!-- icones -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
    lucide.createIcons();
    </script>
  </body>

</html>