-- creamos la bdd y espeficifamos la codificacion de caracteres y en las busquedas no distinguimos entre mayusculas y minusculas, tildes,...
CREATE DATABASE IF NOT EXISTS icare
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE icare;

-- catalogo de roles de usuario
CREATE TABLE IF NOT EXISTS `role`(
    id_role INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `role` VARCHAR(50) NOT NULL,
    UNIQUE KEY uq_role_name (`role`)
) ENGINE=InnoDB
DEFAULT COLLATE=utf8mb4_unicode_ci CHARSET=utf8mb4;

INSERT IGNORE INTO `role` (`role`)
VALUES ('usuario'), ('administrador');

-- tabla para usuarios 
CREATE TABLE IF NOT EXISTS users(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20) NOT NULL,
    surnames VARCHAR(40) NOT NULL,
    email VARCHAR(40) NOT NULL,
    password VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_role INT UNSIGNED NOT NULL DEFAULT 1,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    UNIQUE KEY uq_users_email (email),
    INDEX idx_users_id_role (id_role),
    CONSTRAINT fk_users_role
        FOREIGN KEY (id_role) REFERENCES `role`(id_role)
)  ENGINE=InnoDB

DEFAULT COLLATE=utf8mb4_unicode_ci CHARSET=utf8mb4;

ALTER TABLE users
    ADD COLUMN IF NOT EXISTS activo BOOLEAN NOT NULL DEFAULT TRUE;

-- tabla para empresas
CREATE TABLE IF NOT EXISTS empresas(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    activa BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE=InnoDB
DEFAULT COLLATE=utf8mb4_unicode_ci CHARSET=utf8mb4;

-- relacion de usuarios con empresas
CREATE TABLE IF NOT EXISTS usuario_empresa(
    user_id INT UNSIGNED NOT NULL,
    empresa_id INT UNSIGNED NOT NULL,
    rol VARCHAR(50) NOT NULL,
    PRIMARY KEY (user_id, empresa_id),
    INDEX idx_usuario_empresa_empresa_id (empresa_id),
    CONSTRAINT fk_usuario_empresa_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_usuario_empresa_empresa
        FOREIGN KEY (empresa_id) REFERENCES empresas(id)
        ON DELETE CASCADE
) ENGINE=InnoDB
DEFAULT COLLATE=utf8mb4_unicode_ci CHARSET=utf8mb4;

-- tabla para tokens de activacion de usuarios
CREATE TABLE IF NOT EXISTS activaciones(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    token_hash VARCHAR(255) NOT NULL,
    expira_en DATETIME NOT NULL,
    usado_en DATETIME NULL,
    UNIQUE KEY uq_activaciones_token_hash (token_hash),
    INDEX idx_activaciones_user_id (user_id),
    CONSTRAINT fk_activaciones_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB
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

-- tabla para guardar los datos enviados desde el formulario de contacto
CREATE TABLE IF NOT EXISTS contact_requests(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    email VARCHAR(200) NOT NULL,
    perfil ENUM('Empresa','Particular') NOT NULL,
    empresa VARCHAR(200) NULL,
    cargo VARCHAR(150) NULL,
    servicio ENUM('Coaching','Formación','Datos','IA') NOT NULL,
    telefono VARCHAR(40) NULL,
    mensaje VARCHAR(4000) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    users_id INT UNSIGNED NULL,
    INDEX idx_contact_requests_users_id (users_id),
    CONSTRAINT fk_contact_requests_users
        FOREIGN KEY (users_id) REFERENCES users(id)
        ON DELETE SET NULL
) ENGINE=InnoDB
DEFAULT COLLATE=utf8mb4_unicode_ci CHARSET=utf8mb4;

