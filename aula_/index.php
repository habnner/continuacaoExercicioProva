<?php

session_start();

$erro = "";

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];
 

if(empty($email) || empty($senha)){
    $erro = "Por favor, preencha todos os campos.";
} else{
    $usuarios = file("usuarios.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $login_valido = false;

    foreach ($usuarios as $linha) {
        list($nome, $username, $email_salvo, $senha_hash) = explode("|", $linha);

        if ($email === $email_salvo && password_verify($senha, $senha_hash)){

    
            $login_valido = true;
        $_SESSION["usuario"] = $username;
        $_SESSION["nome"] = $username;
        break;
        }
    }

if ($login_valido) { 
    header("Location: feed.php");
    exit;
} else { 
    $erro = "e-mail ou senha incorreta.";
    }

   }
}

?>

<DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset = "UTF-8>
    <title>Login</title>
    <link href = ""https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class = "bg-light">
    <div class="container mt-5">
    <div class="card p-4 mx-auto" style="max-width:400px;">
        <h3 class="text-center mb-3">Login</h3>

        <?php if ($erro): ?>
            <div class="alert alert-danger"><?php echo $erro; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label>Senha:</label>
                <input type="password" name="senha" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-dark w-100">Entrar</button>
        </form>

        <p class="mt-3 text-center">
            <a href="cadastro.php">Não tem conta? Cadastre-se</a>
        </p>
    </div>
</div>
</body>

</html>
