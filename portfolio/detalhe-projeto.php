<?php
require_once 'config/conexao.php';
require_once 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM projetos WHERE id = :id");
$stmt->execute([':id' => $id]);
$p = $stmt->fetch();

if (!$p) {
    header("Location: index.php");
    exit();
}
?>

<div class="container py-5">
    <a href="index.php#projetos" class="btn btn-outline-secondary mb-4">← Voltar para os projetos</a>
    <div class="row g-4">
        <div class="col-md-6">
            <img src="uploads/<?= htmlspecialchars($p['imagem']) ?>" class="img-fluid rounded shadow" alt="<?= htmlspecialchars($p['titulo']) ?>">
        </div>
        <div class="col-md-6">
            <h1 class="fw-bold mb-3"><?= htmlspecialchars($p['titulo']) ?></h1>
            <p class="badge bg-primary fs-6 mb-3"><?= htmlspecialchars($p['tecnologias']) ?></p>
            <p class="text-muted leading-relaxed"><?= nl2br(htmlspecialchars($p['descricao'])) ?></p>
            
            <div class="d-flex gap-2 mt-4">
                <?php if ($p['link_github']): ?>
                    <a href="<?= htmlspecialchars($p['link_github']) ?>" target="_blank" class="btn btn-dark"><i class="bi bi-github me-1"></i> GitHub</a>
                <?php endif; ?>
                <?php if ($p['link_deploy']): ?>
                    <a href="<?= htmlspecialchars($p['link_deploy']) ?>" target="_blank" class="btn btn-primary"><i class="bi bi-box-arrow-up-right me-1"></i> Acessar Projeto</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>