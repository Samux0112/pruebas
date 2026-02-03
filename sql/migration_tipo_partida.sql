-- Migration: Crear tabla tipo_partida
-- Ejecutar en phpMyAdmin o línea de comandos

-- Crear tabla
CREATE TABLE IF NOT EXISTS tipo_partida (
    id VARCHAR(10) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- Insertar datos iniciales
INSERT INTO tipo_partida (id, nombre) VALUES 
('pdia', 'Partida de Diario'),
('pegr', 'Partida de Egreso'),
('prem', 'Partida de Remesa'),
('ping', 'Partida de Ingreso')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);
