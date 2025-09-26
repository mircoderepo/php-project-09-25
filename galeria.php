<?php
include __DIR__ . '/header.php';
require __DIR__ . '/db.php';

$lista = $pdo->query("SELECT * FROM sobre ORDER BY created_at DESC")->fetchAll();

?>

<h2>Galeria</h2>

<?php if (!$lista): ?>
    <p>Sem imagens cadastradas.</p>
<?php else: ?>
    <h3>Lista</h3>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>Titulo</th>
            <th>Descricao</th>
            <th>Foto</th>
        </tr>
        <?php foreach ($lista as $r): ?>
            <tr>
                <td><?= e($r['titulo']) ?></td>
                <td><?= nl2br(e($r['descricao'])) ?></td>
                <td><?php if ($r['foto_path']): ?><img src="<?= e($r['foto_path']) ?>" width="120"><?php endif; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php include __DIR__ . '/footer.php'; ?>