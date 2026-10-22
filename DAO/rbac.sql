-- =========================================================
-- RBAC - InfoTech
-- Rodar DEPOIS do banco.sql
-- =========================================================
USE infotech;

-- ---------------------------------------------------------
-- PASSO 1.0: ajustes em funcionario
-- ---------------------------------------------------------
ALTER TABLE funcionario
    ADD PRIMARY KEY (id_funcionario),
    ADD UNIQUE KEY uk_funcionario_email (email),
    MODIFY id_funcionario INT(11) NOT NULL AUTO_INCREMENT,
    MODIFY status_funcionario VARCHAR(10) DEFAULT 'ATIVO',
    MODIFY senha VARCHAR(255) NOT NULL;

-- ---------------------------------------------------------
-- PASSO 1.1: perfil e permissao
-- ---------------------------------------------------------
CREATE TABLE perfil (
    id_perfil INT(11)      NOT NULL AUTO_INCREMENT,
    nome      VARCHAR(50)  NOT NULL,
    descricao VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (id_perfil),
    UNIQUE KEY uk_perfil_nome (nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE permissao (
    id_permissao INT(11)      NOT NULL AUTO_INCREMENT,
    chave        VARCHAR(100) NOT NULL,
    descricao    VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (id_permissao),
    UNIQUE KEY uk_permissao_chave (chave)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- PASSO 1.2: tabela pivô perfil_permissao (N:N)
-- ---------------------------------------------------------
CREATE TABLE perfil_permissao (
    id_perfil    INT(11) NOT NULL,
    id_permissao INT(11) NOT NULL,
    PRIMARY KEY (id_perfil, id_permissao),
    CONSTRAINT fk_pp_perfil FOREIGN KEY (id_perfil)
        REFERENCES perfil (id_perfil) ON DELETE CASCADE,
    CONSTRAINT fk_pp_permissao FOREIGN KEY (id_permissao)
        REFERENCES permissao (id_permissao) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- PASSO 1.3: ligar funcionario ao perfil
-- ---------------------------------------------------------
ALTER TABLE funcionario
    ADD COLUMN id_perfil INT(11) DEFAULT NULL,
    ADD CONSTRAINT fk_funcionario_perfil
        FOREIGN KEY (id_perfil) REFERENCES perfil (id_perfil);

-- ---------------------------------------------------------
-- PASSO 1.4: perfis e permissões
-- ---------------------------------------------------------
INSERT INTO perfil (id_perfil, nome, descricao) VALUES
(1, 'admin',    'Acesso total ao sistema'),
(2, 'gerente',  'Gerencia clientes e categorias'),
(3, 'vendedor', 'Consulta e cadastra clientes');

INSERT INTO permissao (id_permissao, chave, descricao) VALUES
(1, 'cliente.listar',        'Ver a lista de clientes'),
(2, 'cliente.cadastrar',     'Cadastrar novos clientes'),
(3, 'cliente.editar',        'Editar clientes'),
(4, 'cliente.excluir',       'Excluir clientes'),
(5, 'categoria.listar',      'Ver categorias'),
(6, 'categoria.cadastrar',   'Cadastrar categorias'),
(7, 'funcionario.gerenciar', 'Cadastrar funcionários e definir perfis');

-- ---------------------------------------------------------
-- PASSO 1.5: quem pode o quê (a matriz)
-- ---------------------------------------------------------
-- admin: todas
INSERT INTO perfil_permissao
SELECT 1, id_permissao FROM permissao;
-- gerente: cliente + categoria
INSERT INTO perfil_permissao VALUES
(2,1),(2,2),(2,3),(2,4),(2,5),(2,6);
-- vendedor
INSERT INTO perfil_permissao VALUES
(3,1),(3,2),(3,5);
-- funcionário existente vira admin
UPDATE funcionario SET id_perfil = 1
 WHERE id_funcionario = 1;

-- funcionários de teste (senha 123)
INSERT INTO funcionario (nome, email, senha, id_perfil) VALUES
('Gerente Teste',  'gerente@gmail.com',  '123', 2),
('Vendedor Teste', 'vendedor@gmail.com', '123', 3);

-- ---------------------------------------------------------
-- PASSO 1.6: conferir
-- ---------------------------------------------------------
SELECT f.nome, p.nome AS perfil, pe.chave
  FROM funcionario f
  JOIN perfil p            ON p.id_perfil     = f.id_perfil
  JOIN perfil_permissao pp ON pp.id_perfil    = p.id_perfil
  JOIN permissao pe        ON pe.id_permissao = pp.id_permissao
 WHERE f.email = 'vendedor@gmail.com';
