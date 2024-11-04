-- Criação do banco de dados, se ainda não existir
CREATE DATABASE IF NOT EXISTS bot;

-- Selecionar o banco de dados
USE bot;

-- Criação da tabela usuario
CREATE TABLE IF NOT EXISTS usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    telefone VARCHAR(20) NOT NULL UNIQUE,
    nome VARCHAR(100) DEFAULT '',
    status INT NOT NULL DEFAULT 0, 
    msg VARCHAR(10)
);