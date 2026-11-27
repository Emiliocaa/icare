-- Ejecutar una sola vez en bases de datos existentes antes de actualizar la aplicacion.
CREATE TABLE IF NOT EXISTS `role`(
    id_role INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `role` VARCHAR(50) NOT NULL,
    UNIQUE KEY uq_role_name (`role`)
) ENGINE=InnoDB
DEFAULT COLLATE=utf8mb4_unicode_ci CHARSET=utf8mb4;

INSERT IGNORE INTO `role` (`role`)
VALUES ('usuario'), ('administrador');

ALTER TABLE users
    ADD COLUMN id_role INT UNSIGNED NULL AFTER password,
    ADD INDEX idx_users_id_role (id_role);

UPDATE users AS u
INNER JOIN `role` AS r ON r.`role` = u.`role`
SET u.id_role = r.id_role;

ALTER TABLE users
    MODIFY COLUMN id_role INT UNSIGNED NOT NULL DEFAULT 1,
    DROP COLUMN role,
    ADD CONSTRAINT fk_users_role
        FOREIGN KEY (id_role) REFERENCES `role`(id_role);
