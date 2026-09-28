<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/conexao.php';

$stmt = $pdo->query("SELECT * FROM projetos ORDER BY data_criacao DESC");
$projetos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - Portfólio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Painel Administrativo</a>
        <div class="d-flex align-items-center gap-3">
            <span class="text-light">Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></span>
            <a href="logout.php" class="btn btn-outline-danger btn-sm">Sair</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Gerenciar Projetos</h2>
        <a href="projeto-novo.php" class="btn btn-success fw-bold"><i class="bi bi-plus-lg me-1"></i> Novo Projeto</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Tecnologias</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($projetos) > 0): ?>
                            <?php foreach ($projetos as $p): ?>
                                <tr>
                                    <td><?= $p['id'] ?></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($p['titulo']) ?></td>
                                    <td><span class="badge bg-info text-dark"><?= htmlspecialchars($p['tecnologias']) ?></span></td>
                                    <td>
                                        <a href="projeto-editar.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm fw-semibold me-1"><i class="bi bi-pencil"></i> Editar</a>
                                        
                                        <form action="projeto-excluir.php" method="POST" class="d-inline" onsubmit="return confirm('Deseja realmente remover este projeto?');">
                                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm fw-semibold">
                                                <i class="bi bi-trash"></i> Remover
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Nenhum projeto cadastrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>