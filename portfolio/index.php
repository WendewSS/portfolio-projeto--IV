<?php
require_once 'config/conexao.php';
require_once 'includes/header.php';

$mensagemSucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_contato'])) {
    $nome = trim($_POST['nome']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $mensagem = trim($_POST['mensagem']);

    if ($nome && $email && $mensagem) {
        $mensagemSucesso = 'Mensagem enviada com sucesso! Obrigado pelo contato.';
    }
}

$stmt = $pdo->query("SELECT * FROM projetos ORDER BY data_criacao DESC");
$projetos = $stmt->fetchAll();
?>

<header class="bg-primary text-white text-center py-5 shadow-sm">
    <div class="container py-4">
        <h1 class="display-4 fw-bold">Olá, sou Desenvolvedor Web</h1>
        <p class="lead">Bem-vindo ao meu portfólio de projetos e aplicações web.</p>
        <a href="#projetos" class="btn btn-light btn-lg mt-3 fw-semibold">Ver Meus Projetos</a>
    </div>
</header>

<section id="sobre" class="py-5 bg-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4 text-center mb-4 mb-md-0">
                <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='200' height='200' viewBox='0 0 200 200'><circle cx='100' cy='100' r='100' fill='%236c757d'/><text x='50%' y='55%' fill='%23ffffff' font-family='sans-serif' font-size='20' text-anchor='middle'>Perfil</text></svg>" class="rounded-circle img-fluid shadow" alt="Foto de Perfil">
            </div>
            <div class="col-md-8">
                <h2 class="fw-bold mb-3">Sobre Mim</h2>
                <p class="text-muted">Desenvolvedor focado em criar soluções funcionais utilizando PHP, MySQL, HTML5, CSS3 e Bootstrap.</p>
                <div class="d-flex gap-2">
                    <span class="badge bg-secondary fs-6">PHP</span>
                    <span class="badge bg-secondary fs-6">MySQL</span>
                    <span class="badge bg-secondary fs-6">Bootstrap</span>
                    <span class="badge bg-secondary fs-6">HTML/CSS</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="projetos" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Meus Projetos</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php if (count($projetos) > 0): ?>
                <?php foreach ($projetos as $p): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0">
                            <img src="uploads/<?= htmlspecialchars($p['imagem']) ?>" class="card-img-top" alt="<?= htmlspecialchars($p['titulo']) ?>">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold"><?= htmlspecialchars($p['titulo']) ?></h5>
                                <p class="card-text text-muted flex-grow-1"><?= htmlspecialchars(mb_strimwidth($p['descricao'], 0, 100, '...')) ?></p>
                                <p class="mb-2"><small class="text-primary fw-semibold"><?= htmlspecialchars($p['tecnologias']) ?></small></p>
                                <a href="detalhe-projeto.php?id=<?= $p['id'] ?>" class="btn btn-outline-primary mt-auto w-100">Ver Detalhes</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted fs-5">Nenhum projeto cadastrado até o momento.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section id="contacto" class="py-5 bg-white">
    <div class="container" style="max-width: 600px;">
        <h2 class="text-center fw-bold mb-4">Entre em Contacto</h2>

        <?php if ($mensagemSucesso): ?>
            <div class="alert alert-success text-center"><?= $mensagemSucesso ?></div>
        <?php endif; ?>

        <form action="index.php#contacto" method="POST">
            <input type="hidden" name="enviar_contato" value="1">
            <div class="mb-3">
                <label for="nome" class="form-label font-weight-bold">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" required placeholder="Seu Nome">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label font-weight-bold">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" required placeholder="seuemail@exemplo.com">
            </div>
            <div class="mb-3">
                <label for="mensagem" class="form-label font-weight-bold">Mensagem</label>
                <textarea class="form-control" id="mensagem" name="mensagem" rows="4" required placeholder="Sua mensagem..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold">Enviar Mensagem</button>
        </form>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>