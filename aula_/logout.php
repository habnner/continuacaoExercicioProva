<?php
session_start(); // inicia a sessão (precisa para destruí-la)
session_unset(); // limpa todas as variáveis da sessão
session_destroy(); // encerra a sessão

// Redireciona de volta para a página de login
header("Location: index.php");
exit;
?>
