<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

require_once '../config/conexao.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id > 0) {
    $stmtSelect = $pdo->prepare("SELECT imagem FROM projetos WHERE id = :id");
    $stmtSelect->execute([':id' => $id]);
    $projeto = $stmtSelect->fetch();

    if ($projeto && $projeto['imagem'] !== 'default.jpg') {
        $caminhoImagem = '../uploads/' . $projeto['imagem'];
        if (file_exists($caminhoImagem)) {
            unlink($caminhoImagem);
        }
    }

    $stmtDelete = $pdo->prepare("DELETE FROM projetos WHERE id = :id");
    $stmtDelete->execute([':id' => $id]);
}

header("Location: index.php");
exit();
?>