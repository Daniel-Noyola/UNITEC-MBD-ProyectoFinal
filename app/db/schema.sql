-- =============================================================================
-- SISTEMA DE GESTIÓN BIBLIOTECARIA - ESQUEMA EN TERCERA FORMA NORMAL (3FN)
-- Base de datos: biblioteca_db
-- Motor: InnoDB | Codificación: utf8mb4 | Cotejamiento: utf8mb4_unicode_ci
-- =============================================================================

CREATE DATABASE IF NOT EXISTS biblioteca_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE biblioteca_db;

-- 1. TABLA: tipos_usuario
CREATE TABLE IF NOT EXISTS tipos_usuario (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_tipo VARCHAR(50) NOT NULL UNIQUE,
    limite_libros TINYINT UNSIGNED NOT NULL,
    dias_prestamo TINYINT UNSIGNED NOT NULL,
    CHECK (limite_libros > 0),
    CHECK (dias_prestamo > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. TABLA: usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tipo_id INT UNSIGNED NOT NULL,
    matricula_num_emp VARCHAR(20) NOT NULL UNIQUE,
    nombre_completo VARCHAR(120) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    telefono VARCHAR(15) NULL,
    estado ENUM('Activo', 'Suspendido', 'Baja') NOT NULL DEFAULT 'Activo',
    CHECK (correo LIKE '%@%'),
    FOREIGN KEY (tipo_id) REFERENCES tipos_usuario(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. TABLA: bibliotecarios
CREATE TABLE IF NOT EXISTS bibliotecarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    num_empleado VARCHAR(20) NOT NULL UNIQUE,
    nombre_completo VARCHAR(120) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    estado ENUM('Activo', 'Inactivo') NOT NULL DEFAULT 'Activo',
    CHECK (correo LIKE '%@%')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. TABLA: autores
CREATE TABLE IF NOT EXISTS autores (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_autor VARCHAR(100) NOT NULL,
    nacionalidad VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. TABLA: categorias
CREATE TABLE IF NOT EXISTS categorias (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. TABLA: libros
CREATE TABLE IF NOT EXISTS libros (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    autor_id INT UNSIGNED NOT NULL,
    categoria_id INT UNSIGNED NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    isbn VARCHAR(20) NOT NULL UNIQUE,
    anio_publicacion SMALLINT UNSIGNED NOT NULL,
    editorial VARCHAR(80) NOT NULL,
    CHECK (anio_publicacion BETWEEN 1000 AND 2100),
    FOREIGN KEY (autor_id) REFERENCES autores(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. TABLA: ejemplares
CREATE TABLE IF NOT EXISTS ejemplares (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    libro_id INT UNSIGNED NOT NULL,
    codigo_inventario VARCHAR(30) NOT NULL UNIQUE,
    ubicacion_estante VARCHAR(50) NOT NULL,
    estado_conservacion ENUM('Excelente', 'Bueno', 'Regular', 'Dañado') NOT NULL DEFAULT 'Bueno',
    estado_disponibilidad ENUM('Disponible', 'Prestado', 'En Mantenimiento', 'Baja') NOT NULL DEFAULT 'Disponible',
    FOREIGN KEY (libro_id) REFERENCES libros(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. TABLA: prestamos
CREATE TABLE IF NOT EXISTS prestamos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    ejemplar_id INT UNSIGNED NOT NULL,
    bibliotecario_id INT UNSIGNED NOT NULL,
    fecha_salida DATE NOT NULL,
    fecha_compromiso DATE NOT NULL,
    fecha_devolucion_real DATE NULL,
    estado_prestamo ENUM('Activo', 'Devuelto', 'Vencido') NOT NULL DEFAULT 'Activo',
    CHECK (fecha_compromiso >= fecha_salida),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    FOREIGN KEY (ejemplar_id) REFERENCES ejemplares(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    FOREIGN KEY (bibliotecario_id) REFERENCES bibliotecarios(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. TABLA: multas
CREATE TABLE IF NOT EXISTS multas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    prestamo_id INT UNSIGNED NOT NULL UNIQUE,
    bibliotecario_id INT UNSIGNED NULL,
    dias_retraso SMALLINT UNSIGNED NOT NULL,
    monto_total DECIMAL(8, 2) NOT NULL,
    fecha_generacion DATE NOT NULL,
    estado_pago ENUM('Pendiente', 'Pagada', 'Condonada') NOT NULL DEFAULT 'Pendiente',
    CHECK (dias_retraso >= 0),
    CHECK (monto_total >= 0.00),
    FOREIGN KEY (prestamo_id) REFERENCES prestamos(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (bibliotecario_id) REFERENCES bibliotecarios(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- USUARIO BIBLIOTECARIO INICIAL (ENTORNO DE DESARROLLO LOCAL)
-- Correo: admin@biblioteca.local | Contraseña dev: Admin123!
-- =============================================================================
INSERT IGNORE INTO bibliotecarios (num_empleado, nombre_completo, correo, password_hash, estado)
VALUES (
    'EMP-001',
    'Administrador de Biblioteca',
    'admin@biblioteca.local',
    '$2y$12$Yu0/KkMbDES21RDf.CRZhOpCMi.wNM1A5c/cBi1a/s2fMHm5Ntmzm',
    'Activo'
);
