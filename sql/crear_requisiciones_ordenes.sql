-- Script para crear tablas de Requisiciones y Ordenes de Compra

CREATE TABLE IF NOT EXISTS requisiciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  productos TEXT NOT NULL,
  total DECIMAL(15,2) NOT NULL DEFAULT 0,
  fecha DATE NOT NULL,
  hora TIME NOT NULL,
  id_usuario INT NOT NULL,
  estado ENUM('pendiente','aprobada','rechazada') NOT NULL DEFAULT 'pendiente',
  observaciones TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX (id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ordenes_compra (
  id INT AUTO_INCREMENT PRIMARY KEY,
  requisicion_id INT NULL,
  productos TEXT NOT NULL,
  total DECIMAL(15,2) NOT NULL DEFAULT 0,
  fecha DATE NOT NULL,
  hora TIME NOT NULL,
  id_proveedor INT NULL,
  id_usuario INT NOT NULL,
  estado ENUM('abierta','en_proceso','recepcionada','anulada') NOT NULL DEFAULT 'abierta',
  observaciones TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX (requisicion_id),
  INDEX (id_proveedor),
  INDEX (id_usuario),
  CONSTRAINT fk_orden_requisicion FOREIGN KEY (requisicion_id) REFERENCES requisiciones(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Nota: Asegúrese de que las tablas referenciadas (usuarios, proveedor) existan antes de crear las claves foráneas si desea agregarlas.
-- Si quieres, puedo añadir las foreign keys hacia la tabla usuarios y proveedor si existen en tu esquema.
