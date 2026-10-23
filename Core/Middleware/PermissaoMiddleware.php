<?php

namespace InfoTech\Core\Middleware;

/**
 * 2ª barreira: AUTORIZAÇÃO ("você PODE fazer isso?").
 *
 * É aqui que o RBAC acontece. A rota declara a permissão exigida:
 *
 *     PermissaoMiddleware::pode('cliente.excluir')
 *     PermissaoMiddleware::podeQualquer('cliente.cadastrar', 'cliente.editar')
 *     PermissaoMiddleware::pode('relatorio.ver')->json()
 *
 * As chaves ('cliente.excluir') são as mesmas da tabela `permissao`.
 */
final class PermissaoMiddleware extends Middleware
{
    private const TODAS    = 'todas';    // precisa de TODAS as chaves (AND)
    private const QUALQUER = 'qualquer'; // basta UMA das chaves (OR)

    /** @param string[] $chaves */
    private function __construct(private array $chaves, private string $modo)
    {
    }

    /** Exige todas as permissões informadas */
    public static function pode(string ...$chaves): self
    {
        return new self($chaves, self::TODAS);
    }

    /** Exige ao menos uma das permissões informadas */
    public static function podeQualquer(string ...$chaves): self
    {
        return new self($chaves, self::QUALQUER);
    }

    public function handle(): bool
    {
        $usuario = $this->usuario();

        // Defesa em profundidade: sem sessão nunca libera,
        // mesmo que alguém esqueça o AuthMiddleware na rota.
        if ($usuario === null) {
            $auth = AuthMiddleware::exigir();
            if ($this->json) {
                $auth->json();
            }
            return $auth->handle();
        }

        if ($this->autorizado($usuario->permissoes)) {
            return true;
        }

        return $this->recusar(
            403,
            'Você não tem permissão para esta ação.',
            '/Erro/acesso_negado.php'
        );
    }

    /** @param string[] $permissoesDoUsuario */
    private function autorizado(array $permissoesDoUsuario): bool
    {
        $tem = fn(string $chave): bool => in_array($chave, $permissoesDoUsuario, true);

        return $this->modo === self::TODAS
            ? array_reduce($this->chaves, fn($ok, $c) => $ok && $tem($c), true)
            : array_reduce($this->chaves, fn($ok, $c) => $ok || $tem($c), false);
    }
}
