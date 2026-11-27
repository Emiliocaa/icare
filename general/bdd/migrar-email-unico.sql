-- Ejecutar una sola vez en bases de datos existentes.
ALTER TABLE users
    ADD UNIQUE KEY uq_users_email (email);
