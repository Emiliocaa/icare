-- creamos la bdd y espeficifamos la codificacion de caracteres y en las busquedas no distinguimos entre mayusculas y minusculas, tildes,...
CREATE DATABASE IF NOT EXISTS icare
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE icare;

-- tabla para usuarios 
CREATE TABLE IF NOT EXISTS users(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20) NOT NULL,
    surnames VARCHAR(40) NOT NULL,
    email VARCHAR(40) NOT NULL,
    password VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    role ENUM('administrador','usuario') NOT NULL DEFAULT 'usuario'
)  ENGINE=InnoDB

DEFAULT COLLATE=utf8mb4_unicode_ci CHARSET=utf8mb4;

-- tabla para registrar tareas
CREATE TABLE IF NOT EXISTS tasks(
    id Int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description VARCHAR(1000) NOT NULL,
    user_id INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    end_task BOOLEAN NOT NULL DEFAULT false
)  ENGINE=InnoDB

DEFAULT COLLATE=utf8mb4_unicode_ci CHARSET=utf8mb4;

-- El id numerico identifica la fila; id_texto conserva el id actual de articulos.json
-- Para mantener el contrato actual, las consultas pueden exponerlo como id_texto AS id.
CREATE TABLE IF NOT EXISTS articulo(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_texto VARCHAR(100) NOT NULL UNIQUE,
    tematica VARCHAR(50) NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    resumen TEXT NOT NULL,
    categoria VARCHAR(100) NOT NULL,
    fecha DATE NOT NULL,
    tiempo_lectura SMALLINT UNSIGNED NOT NULL,
    imagen VARCHAR(500) NOT NULL,
    alt VARCHAR(500) NOT NULL,
    contenido JSON NOT NULL
) ENGINE=InnoDB
DEFAULT COLLATE=utf8mb4_unicode_ci CHARSET=utf8mb4;