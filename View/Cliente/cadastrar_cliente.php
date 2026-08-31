<?php
    include VIEW . "/Includes/header.php";
    include VIEW . "/Includes/navbar.php";
?>

<div class="p-5 center">
    <h1> Cadastrar Clientes </h1>
</div>

<form method="POST" action="/infotech/cliente/cadastro">
  <?php if ($model !== null): ?>
    <input type="hidden" name="id_cliente" value="<?= (int) $model->id_cliente ?>">
  <?php endif; ?>
  <div class="mb-3">
    <label for="nome" class="form-label">Nome</label>
    <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($model->nome ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
  </div>
  <div class="mb-3">
    <label for="telefone" class="form-label">Fone</label>
    <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($model->telefone ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
  </div>

  <div class="mb-3">
    <label for="email" class="form-label">E-mail</label>
    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($model->email ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
  </div>

  <div class="mb-3">
            <select class="form-select" id="status" name="status" required>
                <option value="" <?= empty($model->status_cliente) ? 'selected' : '' ?> disabled>Selecione o Status</option>
                <option value="ATIVO" <?= ($model->status_cliente ?? '') === 'ATIVO' ? 'selected' : '' ?>> Ativo </option>
                <option value="INATIVO" <?= ($model->status_cliente ?? '') === 'INATIVO' ? 'selected' : '' ?>> Inativo </option>
            </select>
  </div>
  
  <button type="submit" name="salvar" id="salvar" class="btn btn-primary">Salvar</button>
</form>

<?php
   include VIEW . "/Includes/footer.php";
?>
