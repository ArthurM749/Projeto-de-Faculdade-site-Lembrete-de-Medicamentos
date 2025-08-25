<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');

define('DB_PATH', __DIR__ . '/../db/database.db');

function conectarDB() {
    try {
        $db = new PDO('sqlite:' . DB_PATH);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        die("Erro ao conectar ao banco de dados: " . $e->getMessage());
    }
}

function criarTabelas() {
    $db = conectarDB();
    
    $db->exec('CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        senha TEXT NOT NULL
    )');

    $db->exec('CREATE TABLE IF NOT EXISTS medicamentos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        usuario_id INTEGER NOT NULL,
        nome TEXT NOT NULL,
        dose TEXT NOT NULL,
        horario TEXT NOT NULL,
        anotacao TEXT DEFAULT NULL,  -- NOVA COLUNA PARA ANOTAÇÕES
        frequencia TEXT DEFAULT "diario",
        dias_intervalo INTEGER DEFAULT 1,
        FOREIGN KEY(usuario_id) REFERENCES usuarios(id)
    )');

    $db->exec('CREATE TABLE IF NOT EXISTS historico (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        medicamento_id INTEGER NOT NULL,
        data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(medicamento_id) REFERENCES medicamentos(id)
    )');
}

criarTabelas();