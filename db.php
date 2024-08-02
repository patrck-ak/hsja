<?php
$host = 'localhost';
$banco = 'hsja';
$usuario = 'root';
$senha = '';

$mysqli = new mysqli($host, $usuario, $senha, $banco);
if ($mysqli->connect_errno) {
  die('Falha na conexão com banco.');
}