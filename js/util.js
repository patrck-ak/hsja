function nav(l) {
  switch (l) {
    case "cadastro":
      window.location.replace("http://localhost/hsja/cadastro.php");
      break;
    case 1:
      window.location.replace("http://localhost/hsja/listagem.php");
      break;
    default:
      alert("erro na navegação.");
      break;
  }
}
