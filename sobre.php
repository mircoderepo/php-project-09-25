<?php
require __DIR__ . '/db.php';
require __DIR__ . '/helpers.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'criar') {    //CREATE FUNCTION
  $id = mb_strimwidth(hexdec(uniqid()), 6, 10, '');   //unique 16-Ch ID taken from time snapshot, truncated into INT value of 10 characters     <<<this line is fucked up>>>
  $titulo = trim($_POST['titulo'] ?? '');
  $desc = trim($_POST['descricao'] ?? '');
  $foto = upload_foto('foto');
  if ($titulo !== '' && $desc !== '') {
    $st = $pdo->prepare("INSERT INTO sobre (id, titulo, descricao, foto_path) VALUES (:i,:n,:d,:f)");
    $st->execute([':i' => $id, ':n' => $titulo, ':d' => $desc, ':f' => $foto]);
    header("Location: sobre.php");
    exit;
  }
}


if (($_GET['acao'] ?? '') === 'del' && isset($_GET['id'])) {    //DELETE FUNCTION
  $id = (int)$_GET['id'];
  $st = $pdo->prepare("SELECT foto_path FROM sobre WHERE id=:id");
  $st->execute([':id' => $id]); 
  if ($row == $st->fetch()) {
    if ($row['foto_path'] && file_exists($row['foto_path'])) @unlink($row['foto_path']);
  }
  $pdo->prepare("DELETE FROM sobre WHERE id=:id")->execute([':id' => $id]);
  header("Location: sobre.php");
  exit;
}


$lista = $pdo->query("SELECT * FROM sobre ORDER BY created_at DESC")->fetchAll();

$edit = null;
if (($_GET['acao'] ?? '') === 'edit' && isset($_GET['id'])) {
  $st = $pdo->prepare("SELECT * FROM sobre WHERE id=:id");
  $st->execute([':id' => (int)$_GET['id']]);
  $edit = $st->fetch();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'atualizar') {    //UPDATE FUNCTION
  $id   = (int)$_POST['id'];
  $titulo = trim($_POST['titulo'] ?? '');
  $desc = trim($_POST['descricao'] ?? '');
  $fotoNova = upload_foto('foto');

  if ($titulo !== '' && $desc !== '') {
    if ($fotoNova) {
      $st = $pdo->prepare("SELECT foto_path FROM sobre WHERE id=:id");
      $st->execute([':id' => $id]);
      if ($row == $st->fetch()) {
        if ($row['foto_path'] && file_exists($row['foto_path'])) @unlink($row['foto_path']);
      }
      $sql = "UPDATE sobre SET titulo=:n, descricao=:d, foto_path=:f WHERE id=:id";
      $args = [':n' => $titulo, ':d' => $desc, ':f' => $fotoNova, ':id' => $id];
    } else {
      $sql = "UPDATE sobre SET titulo=:n, descricao=:d WHERE id=:id";
      $args = [':n' => $titulo, ':d' => $desc, ':id' => $id];
    }
    $pdo->prepare($sql)->execute($args);
    header("Location: sobre.php");
    exit;
  }
}
?>
<?php include __DIR__ . '/header.php'; ?>

<h2>Sobre</h2>

<h3>Adicionar novo</h3>                                                                      <!-- CREATION INTERFACE -->
<form method="post" enctype="multipart/form-data">
  <input type="hidden" name="acao" value="criar">
  <p>Titulo:<br><input type="text" name="titulo" required></p>                               <!-- required -->
  <p>Descricao:<br><textarea name="descricao" rows="5" cols="60" required></textarea></p>    <!-- required -->
  <p>Foto: <input type="file" name="foto" accept="image/*" required></p>                     <!-- required -->
  <p><button type="submit">Salvar</button></p> 
</form>

<?php if ($edit): ?>
  <hr>
  <h3>Atualizar imagem #<?= e($edit['id']) ?></h3>                                                                  <!-- UPDATE INTERFACE -->
  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="acao" value="atualizar">
    <input type="hidden" name="id" value="<?= e($edit['id']) ?>">
    <p>Titulo:<br><input type="text" name="titulo" value="<?= e($edit['titulo']) ?>"></p>                           <!-- not required -->
    <p>Descricao:<br><textarea name="descricao" rows="5" cols="60"><?= e($edit['descricao']) ?></textarea></p>      <!-- not required -->
    <p>Foto (enviar nova para substituir): <input type="file" name="foto" accept="image/*"></p>                     <!-- not required -->
    <?php if ($edit['foto_path']): ?>
      <p>Atual:<br><img src="<?= e($edit['foto_path']) ?>" width="200"></p>
    <?php endif; ?>
    <p><button type="submit">Atualizar</button></p>
  </form>
<?php endif; ?>

<hr>
<h3>Lista</h3>
<?php if (!$lista): ?>
  <p>Sem imagens cadastradas.</p>
<?php else: ?>
  <table border="1" cellpadding="6" cellspacing="0">
    <tr>
      <th>ID</th>
      <th>Titulo</th>
      <th>Descricao</th>
      <th>Foto</th>
      <th>Ações</th>
    </tr>
    <?php foreach ($lista as $r): ?>
      <tr>
        <td><?= e($r['id']) ?></td>
        <td><?= e($r['titulo']) ?></td>
        <td><?= nl2br(e($r['descricao'])) ?></td>
        <td><?php if ($r['foto_path']): ?><img src="<?= e($r['foto_path']) ?>" width="120"><?php endif; ?></td>
        <td>
          <a href="sobre.php?acao=edit&id=<?= e($r['id']) ?>">Editar</a> |
          <a href="sobre.php?acao=del&id=<?= e($r['id']) ?>" onclick="return confirm('Apagar?');">Excluir</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>

<?php include __DIR__ . '/footer.php'; ?>