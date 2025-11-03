
CREATE DATABASE IF NOT EXISTS crud_aluno;

USE crud_aluno;

CREATE TABLE alunos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricula VARCHAR(20) NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO alunos (matricula, nome, email) VALUES
('2023001', 'Maria da Silva', 'maria.silva@exemplo.com'),
('2023002', 'João Oliveira', 'joao.oliveira@exemplo.com');