<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit;
}

$arquivo_posts = "posts.json";

// Se o arquivo não existir, cria um vazio
if (!file_exists($arquivo_posts)) {
    file_put_contents($arquivo_posts, json_encode([]));
}

// Carrega os posts
$posts = json_decode(file_get_contents($arquivo_posts), true);

// --- Criar novo post ---
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["novo_post"])) {
    $conteudo = trim($_POST["novo_post"]);

    if (!empty($conteudo)) {
        $novo_post = [
            "usuario" => $_SESSION["nome"],
            "username" => $_SESSION["usuario"],
            "conteudo" => htmlspecialchars($conteudo),
            "curtidas" => 0
        ];
        array_unshift($posts, $novo_post); // adiciona no início
        file_put_contents($arquivo_posts, json_encode($posts, JSON_PRETTY_PRINT));
        header("Location: feed.php"); // evita repostar ao atualizar
        exit;
    } else {
        $erro_post = "O campo de post não pode estar vazio.";
    }
}

// --- Curtir post ---
if (isset($_GET["curtir"])) {
    $indice = (int)$_GET["curtir"];
    if (isset($posts[$indice])) {
        $posts[$indice]["curtidas"]++;
        file_put_contents($arquivo_posts, json_encode($posts, JSON_PRETTY_PRINT));
    }
    header("Location: feed.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Feed de Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark p-3">
    <div class="container d-flex justify-content-between">
        <span class="navbar-brand">Bem-vindo, <?php echo $_SESSION["nome"]; ?>!</span>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Sair</a>
    </div>
</nav>

<div class="container mt-4">
    <div class="card p-3 mb-4">
        <form method="POST">
            <textarea name="novo_post" class="form-control mb-2" placeholder="O que você está pensando?" rows="2"></textarea>
            <button type="submit" class="btn btn-dark">Postar</button>
            <?php if (!empty($erro_post)): ?>
                <div class="text-danger mt-2"><?php echo $erro_post; ?></div>
            <?php endif; ?>
        </form>
    </div>

    <?php if (empty($posts)): ?>
        <p class="text-center text-muted">Nenhum post ainda. Seja o primeiro!</p>
    <?php endif; ?>

    <?php foreach ($posts as $indice => $post): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="fw-bold"><?php echo htmlspecialchars($post["usuario"]); ?> <span class="text-muted">@<?php echo htmlspecialchars($post["username"]); ?></span></h6>
                <p class="mt-2"><?php echo htmlspecialchars($post["conteudo"]); ?></p>
                <a href="feed.php?curtir=<?php echo $indice; ?>" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-heart-fill"></i> Curtir (<?php echo $post["curtidas"]; ?>)
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
