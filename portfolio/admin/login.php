<?php
session_start();
require_once '../config/conexao.php';

$erro = '';

if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (!empty($email) && !empty($senha)) {
        $stmt = $pdo->prepare("SELECT id, nome, senha FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            header("Location: index.php");
            exit();
        } else {
            $erro = 'E-mail ou senha incorreta.';
        }
    } else {
        $erro = 'Preencha todos os campos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Área Administrativa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">

<div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
    <h3 class="text-center fw-bold mb-4">Área Restrita</h3>

    <?php if ($erro): ?>
        <div class="alert alert-danger py-2"><?= $erro ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email</label>
            <input type="email" class="form-control" id="email" name="email" required placeholder="portfolio@portfolio.com">
        </div>

        <div class="mb-3">
            <label for="senha" class="form-label fw-semibold">Senha</label>
            <input type="password" class="form-control" id="senha" name="senha" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Entrar</button>
    </form>

    <div class="text-center mt-3">
        <a href="../index.php" class="text-decoration-none text-muted small">← Voltar ao Portfólio</a>
    </div>
</div>

</body>
</html>