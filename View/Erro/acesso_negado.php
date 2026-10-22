<?php
    include VIEW . "/Includes/header.php";
    include VIEW . "/Includes/navbar.php";
?>

<div class="p-5 text-center">
    <i class="bi bi-shield-lock display-1 text-danger"></i>
    <h1 class="mt-3">403 - Acesso negado</h1>
    <p class="text-secondary">
        Seu perfil (<strong><?= htmlspecialchars($usuario->perfil ?? '') ?></strong>)
        não tem permissão para acessar esta página.
    </p>
    <a href="/infotech/" class="btn btn-dark">Voltar ao início</a>
</div>

<?php
   include VIEW . "/Includes/footer.php";
?>
