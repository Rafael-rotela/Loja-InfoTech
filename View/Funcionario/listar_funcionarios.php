<?php
    include VIEW . "/Includes/header.php";
    include VIEW . "/Includes/navbar.php";
?>

<div class="p-5 center d-flex justify-content-between align-items-center">
    <h1> Funcionários </h1>
    <a class="btn btn-primary" href="/infotech/funcionario/cadastro"><i class="bi bi-plus-lg"></i> Novo</a>
</div>

<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>Id</th><th>Nome</th><th>E-mail</th><th>Perfil</th><th>Status</th><th>Ação</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($model->rows as $func): ?>
      <tr>
        <th><?= $func->id_funcionario ?></th>
        <td><?= htmlspecialchars($func->nome) ?></td>
        <td><?= htmlspecialchars($func->email) ?></td>
        <td><span class="badge bg-secondary"><?= htmlspecialchars($func->perfil ?? 'sem perfil') ?></span></td>
        <td><?= $func->status_funcionario ?></td>
        <td>
          <a class="btn btn-dark" href="/infotech/funcionario/cadastro?id_funcionario=<?= $func->id_funcionario ?>">
            <i class="bi bi-pencil-square"></i>
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php
   include VIEW . "/Includes/footer.php";
?>
