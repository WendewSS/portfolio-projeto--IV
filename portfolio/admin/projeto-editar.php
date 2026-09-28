<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/conexao.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM projetos WHERE id = :id");
$stmt->execute([':id' => $id]);
$projeto = $stmt->fetch();

if (!$projeto) {
    header("Location: index.php");
    exit();
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $tecnologias = trim($_POST['tecnologias']);
    $link_github = trim($_POST['link_github']);
    $link_deploy = trim($_POST['link_deploy']);
    $imagemNome = $projeto['imagem'];

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $tamanhoMaximo = 2 * 1024 * 1024; // 2MB
        if ($_FILES['imagem']['size'] > $tamanhoMaximo) {
            $erro = 'A nova imagem excede o limite permitido de 2MB.';
        } else {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $_FILES['imagem']['tmp_name']);
            finfo_close($finfo);

            $mimesPermitidos = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp'
            ];

            if (array_key_exists($mimeType, $mimesPermitidos)) {
                $extensao = $mimesPermitidos[$mimeType];
                $novoNome = uniqid() . '.' . $extensao;

                if (move_uploaded_file($_FILES['imagem']['tmp_name'], '../uploads/' . $novoNome)) {
                    if ($projeto['imagem'] !== 'default.jpg') {
                        $imagemAntiga = '../uploads/' . $projeto['imagem'];
                        if (file_exists($imagemAntiga)) {
                            unlink($imagemAntiga);
                        }
                    }
                    $imagemNome = $novoNome;
                }
            } else {
                $erro = 'Formato de imagem inválido! Apenas JPG, PNG e WEBP são aceitos.';
            }
        }
    }

    if (empty($erro)) {
        $stmtUpdate = $pdo->prepare("UPDATE projetos SET titulo = :titulo, descricao = :descricao, tecnologias = :tecnologias, imagem = :imagem, link_github = :link_github, link_deploy = :link_deploy WHERE id = :id");
        $stmtUpdate->execute([
            ':titulo' => $titulo,
            ':descricao' => $descricao,
            ':tecnologias' => $tecnologias,
            ':imagem' => $imagemNome,
            ':link_github' => $link_github,
            ':link_deploy' => $link_deploy,
            ':id' => $id
        ]);

        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Projeto - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">

<div class="container" style="max-width: 600px;">
    <div class="card shadow-sm p-4">
        <h3 class="fw-bold mb-4">Editar Projeto #<?= $projeto['id'] ?></h3>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?= $erro ?></div>
        <?php endif; ?>

        <form action="projeto-editar.php?id=<?= $projeto['id'] ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label fw-semibold">Título do Projeto *</label>
                <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($projeto['titulo']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Descrição *</label>
                <textarea name="descricao" class="form-control" rows="3" required><?= htmlspecialchars($projeto['descricao']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Tecnologias *</label>
                <input type="text" name="tecnologias" class="form-control" value="<?= htmlspecialchars($projeto['tecnologias']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Imagem Atual</label>
                <div class="mb-2">
                    <img src="../uploads/<?= htmlspecialchars($projeto['imagem']) ?>" alt="Imagem Atual" style="max-height: 100px;" class="img-thumbnail">
                </div>
                <input type="file" name="imagem" class="form-control" accept="image/jpeg,image/png,image/webp">
                <small class="text-muted">Selecione um arquivo apenas se desejar substituir a imagem atual.</small>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Link do GitHub</label>
                <input type="url" name="link_github" class="form-control" value="<?= htmlspecialchars($projeto['link_github']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Link do Projeto Online (Deploy)</label>
                <input type="url" name="link_deploy" class="form-control" value="<?= htmlspecialchars($projeto['link_deploy']) ?>">
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-warning fw-bold flex-grow-1">Atualizar Projeto</button>
                <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>