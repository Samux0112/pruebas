-- Tabla principal de cheques
CREATE TABLE IF NOT EXISTS cheques (
    id INT PRIMARY KEY AUTO_INCREMENT,
    numero_cheque VARCHAR(20) NOT NULL,
    id_banco INT NOT NULL,
    id_proveedor INT NOT NULL,
    concepto TEXT NOT NULL,
    monto DECIMAL(15,2) NOT NULL,
    fecha_emision DATE NOT NULL,
    fecha_vencimiento DATE,
    estado ENUM('emitido', 'entregado', 'cobrado', 'anulado') DEFAULT 'emitido',
    id_usuario INT NOT NULL,
    motivo_anulacion TEXT NULL,
    fecha_anulacion DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX (id_banco),
    INDEX (id_proveedor),
    INDEX (numero_cheque)
);

-- Tabla detalle debe/haber (partida contable)
CREATE TABLE IF NOT EXISTS detalle_cheque (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_cheque INT NOT NULL,
    cuenta_contable VARCHAR(20) NOT NULL,
    tipo ENUM('Debe', 'Haber') NOT NULL,
    monto DECIMAL(15,2) NOT NULL,
    concepto TEXT,
    INDEX (id_cheque)
);

-- Tabla correlativos por banco
CREATE TABLE IF NOT EXISTS correlativos_cheques (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_banco INT NOT NULL UNIQUE,
    correlativo_actual INT DEFAULT 1,
    prefijo VARCHAR(10) DEFAULT '',
    longitud INT DEFAULT 8,
    INDEX (id_banco)
);

-- Insertar correlativos para bancos existentes
INSERT INTO correlativos_cheques (id_banco, correlativo_actual, prefijo, longitud)
SELECT id, 1, '', 8 FROM bancos WHERE estado = 1
ON DUPLICATE KEY UPDATE correlativo_actual = correlativo_actual;
