-- Tabla principal de cotizaciones asociadas a requisiciones
CREATE TABLE cotizaciones_requisicion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_requisicion INT NOT NULL,
    proveedor VARCHAR(255) NOT NULL,
    monto DECIMAL(12,2) NOT NULL,
    detalle TEXT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_requisicion) REFERENCES requisiciones(id)
);

-- Tabla de productos/ofertas asociadas a cada cotización
CREATE TABLE cotizaciones_productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cotizacion INT NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    cantidad INT NOT NULL,
    descripcion VARCHAR(255),
    precio DECIMAL(12,2) NOT NULL,
    descuento DECIMAL(12,2) DEFAULT 0,
    subtotal DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (id_cotizacion) REFERENCES cotizaciones_requisicion(id)
);