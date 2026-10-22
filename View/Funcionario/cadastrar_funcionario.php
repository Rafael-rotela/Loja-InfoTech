<?php
    include VIEW . "/Includes/header.php";
    include VIEW . "/Includes/navbar.php";
    $editando = !empty($model->id_funcionario);
?>

<div class="p-5 center">
    <h1> <?= $editando ? 'Editar' : 'Cadastrar' ?> Funcionário </h1>
</div>

<form method="POST" action="/infotech/funcionario/cadastro">
  <input type="hidden" name="id_funcionario" value="<?= $model->id_funcionario ?? '' ?>">

  <div class="mb-3">
    <label for="nome" class="form-label">Nome</label>
    <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($model->nome ?? '') ?>" required>
  </div>

  <div class="mb-3">
    <label for="email" class="form-label">E-mail</label>
    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($model->email ?? '') ?>" required>
  </div>

  <div class="mb-3">
    <label for="senha" class="form-label">
      Senha <?= $editando ? '<small class="text-muted">(deixe em branco para manter a atual)</small>' : '' ?>
    </label>
    <input type="password" class="form-control" id="senha" name="senha" <?= $editando ? '' : 'required' ?>>
  </div>

  <!-- RBAC: aqui o admin define o PERFIL do funcionário -->
  <div class="mb-3">
    <label for="id_perfil" class="form-label">Perfil</label>
    <select class="form-select" name="id_perfil" id="id_perfil" required>
      <option value="" disabled <?= $editando ? '' : 'selected' ?>>Selecione o perfil</option>
      <?php foreach ($perfis as $perfil): ?>
        <option value="<?= $perfil->id_perfil ?>" <?= ($model->id_perfil ?? null) == $perfil->id_perfil ? 'selected' : '' ?>>
          <?= htmlspecialchars($perfil->nome) ?> - <?= htmlspecialchars($perfil->descricao ?? '') ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="mb-3">
    <label for="status_funcionario" class="form-label">Status</label>
    <select class="form-select" name="status_funcionario" id="status_funcionario">
      <option value="ATIVO"   <?= ($model->status_funcionario ?? 'ATIVO') === 'ATIVO'   ? 'selected' : '' ?>>Ativo</option>
      <option value="INATIVO" <?= ($model->status_funcionario ?? '') === 'INATIVO' ? 'selected' : '' ?>>Inativo</option>
    </select>
  </div>

  <button type="submit" class="btn btn-primary">Salvar</button>
  <a href="/infotech/funcionario/listar" class="btn btn-secondary">Cancelar</a>
</form>

<?php
   include VIEW . "/Includes/footer.php";
?>
