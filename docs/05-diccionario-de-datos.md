# Entregable 5: Diccionario de Datos

> **Materia:** Modelos de Bases de Datos  
> **Proyecto:** Sistema de Gestión Bibliotecaria  
> **Entregable:** 5 — Diccionario de Datos y Especificación DDL  
> **Fecha:** Noviembre 2026  

---

## Índice

1. [Fichas Técnicas del Diccionario de Datos (9 Tablas en 3FN)](#1-fichas-técnicas-del-diccionario-de-datos-9-tablas-en-3fn)
   - [1.1 Tabla: `tipos_usuario`](#11-tabla-tipos_usuario)
   - [1.2 Tabla: `usuarios`](#12-tabla-usuarios)
   - [1.3 Tabla: `bibliotecarios`](#13-tabla-bibliotecarios)
   - [1.4 Tabla: `autores`](#14-tabla-autores)
   - [1.5 Tabla: `categorias`](#15-tabla-categorias)
   - [1.6 Tabla: `libros`](#16-tabla-libros)
   - [1.7 Tabla: `ejemplares`](#17-tabla-ejemplares)
   - [1.8 Tabla: `prestamos`](#18-tabla-prestamos)
   - [1.9 Tabla: `multas`](#19-tabla-multas)
2. [Sentencias DDL de Creación (`CREATE TABLE`)](#2-sentencias-ddl-de-creación-create-table)
3. [Matriz de Integridad y Validación en Dos Niveles](#3-matriz-de-integridad-y-validación-en-dos-niveles)

---

## 1. Fichas Técnicas del Diccionario de Datos (9 Tablas en 3FN)

### 1.1 Tabla: `tipos_usuario`

- **Propósito:** Catálogo de perfiles de usuario que define los límites de libros y plazos de préstamo autorizados.
- **Motor:** `InnoDB` | **Cotejamiento:** `utf8mb4_unicode_ci`

| Campo | Tipo | Longitud | Nulo | Clave | Default | Restricción | Descripción Funcional |
|---|---|---|---|---|---|---|---|
| `id` | `INT UNSIGNED` | 10 | `NO` | `PK` | `AUTO_INCREMENT` | — | Identificador único del tipo de usuario. |
| `nombre_tipo` | `VARCHAR` | 50 | `NO` | `UK` | — | `UNIQUE` | Nombre del perfil (ej. Alumno, Docente, Administrativo). |
| `limite_libros` | `TINYINT UNSIGNED` | 3 | `NO` | — | — | `CHECK (limite_libros > 0)` | Cantidad máxima de ejemplares prestados en simultáneo. |
| `dias_prestamo` | `TINYINT UNSIGNED` | 3 | `NO` | — | — | `CHECK (dias_prestamo > 0)` | Plazo estándar de días naturales permitidos por préstamo. |

---

### 1.2 Tabla: `usuarios`

- **Propósito:** Almacena el padrón de usuarios registrados con derecho a servicios bibliotecarios.
- **Motor:** `InnoDB` | **Cotejamiento:** `utf8mb4_unicode_ci`

| Campo | Tipo | Longitud | Nulo | Clave | Default | Restricción | Descripción Funcional |
|---|---|---|---|---|---|---|---|
| `id` | `INT UNSIGNED` | 10 | `NO` | `PK` | `AUTO_INCREMENT` | — | Identificador unívoco del usuario en el sistema. |
| `tipo_id` | `INT UNSIGNED` | 10 | `NO` | `FK` | — | `REFERENCES tipos_usuario(id)` | Rol institucional del usuario. `ON UPDATE CASCADE ON DELETE RESTRICT`. |
| `matricula_num_emp` | `VARCHAR` | 20 | `NO` | `UK` | — | `UNIQUE` | Matrícula de estudiante o número de empleado docente. |
| `nombre_completo` | `VARCHAR` | 120 | `NO` | — | — | — | Nombre completo y apellidos del usuario. |
| `correo` | `VARCHAR` | 100 | `NO` | `UK` | — | `UNIQUE`, `CHECK (correo LIKE '%@%')` | Correo institucional o personal de contacto y avisos. |
| `telefono` | `VARCHAR` | 15 | `SÍ` | — | `NULL` | — | Número de teléfono de contacto (opcional). |
| `estado` | `ENUM` | — | `NO` | — | `'Activo'` | `'Activo'`, `'Suspendido'`, `'Baja'` | Estado de cuenta del usuario para solicitar préstamos. |

---

### 1.3 Tabla: `bibliotecarios`

- **Propósito:** Almacena al personal administrativo/trabajadores de la biblioteca con acceso al sistema para operar el mostrador.
- **Motor:** `InnoDB` | **Cotejamiento:** `utf8mb4_unicode_ci`

| Campo | Tipo | Longitud | Nulo | Clave | Default | Restricción | Descripción Funcional |
|---|---|---|---|---|---|---|---|
| `id` | `INT UNSIGNED` | 10 | `NO` | `PK` | `AUTO_INCREMENT` | — | Identificador único del bibliotecario en el sistema. |
| `num_empleado` | `VARCHAR` | 20 | `NO` | `UK` | — | `UNIQUE` | Número de empleado institucional del trabajador. |
| `nombre_completo` | `VARCHAR` | 120 | `NO` | — | — | — | Nombre completo y apellidos del bibliotecario. |
| `correo` | `VARCHAR` | 100 | `NO` | `UK` | — | `UNIQUE`, `CHECK (correo LIKE '%@%')` | Correo electrónico institucional de acceso. |
| `password_hash` | `VARCHAR` | 255 | `NO` | — | — | — | Hash seguro de la contraseña de autenticación. |
| `estado` | `ENUM` | — | `NO` | — | `'Activo'` | `'Activo'`, `'Inactivo'` | Estado operativo de la cuenta del bibliotecario. |

---

### 1.4 Tabla: `autores`

- **Propósito:** Catálogo de autores intelectuales de las obras registradas.
- **Motor:** `InnoDB` | **Cotejamiento:** `utf8mb4_unicode_ci`

| Campo | Tipo | Longitud | Nulo | Clave | Default | Restricción | Descripción Funcional |
|---|---|---|---|---|---|---|---|
| `id` | `INT UNSIGNED` | 10 | `NO` | `PK` | `AUTO_INCREMENT` | — | Identificador único del autor. |
| `nombre_autor` | `VARCHAR` | 100 | `NO` | — | — | — | Nombre y apellidos o denominación del autor. |
| `nacionalidad` | `VARCHAR` | 50 | `NO` | — | — | — | País de origen o nacionalidad del autor. |

---

### 1.5 Tabla: `categorias`

- **Propósito:** Clasificación temática y disciplinaria del acervo bibliográfico.
- **Motor:** `InnoDB` | **Cotejamiento:** `utf8mb4_unicode_ci`

| Campo | Tipo | Longitud | Nulo | Clave | Default | Restricción | Descripción Funcional |
|---|---|---|---|---|---|---|---|
| `id` | `INT UNSIGNED` | 10 | `NO` | `PK` | `AUTO_INCREMENT` | — | Identificador único de la categoría. |
| `nombre_categoria` | `VARCHAR` | 50 | `NO` | `UK` | — | `UNIQUE` | Denominación del área temática (ej. Ciencias Computacionales). |
| `descripcion` | `VARCHAR` | 255 | `SÍ` | — | `NULL` | — | Descripción detallada de los temas cubiertos (opcional). |

---

### 1.6 Tabla: `libros`

- **Propósito:** Registra la obra bibliográfica a nivel conceptual e intelectual (título, autor, editorial e ISBN).
- **Motor:** `InnoDB` | **Cotejamiento:** `utf8mb4_unicode_ci`

| Campo | Tipo | Longitud | Nulo | Clave | Default | Restricción | Descripción Funcional |
|---|---|---|---|---|---|---|---|
| `id` | `INT UNSIGNED` | 10 | `NO` | `PK` | `AUTO_INCREMENT` | — | Identificador único del libro en catálogo. |
| `autor_id` | `INT UNSIGNED` | 10 | `NO` | `FK` | — | `REFERENCES autores(id)` | Autor principal. `ON UPDATE CASCADE ON DELETE RESTRICT`. |
| `categoria_id` | `INT UNSIGNED` | 10 | `NO` | `FK` | — | `REFERENCES categorias(id)` | Categoría temática. `ON UPDATE CASCADE ON DELETE RESTRICT`. |
| `titulo` | `VARCHAR` | 150 | `NO` | — | — | — | Título completo de la publicación bibliográfica. |
| `isbn` | `VARCHAR` | 20 | `NO` | `UK` | — | `UNIQUE` | Código normalizado internacional (ISBN-10 o ISBN-13). |
| `anio_publicacion` | `SMALLINT UNSIGNED` | 4 | `NO` | — | — | `CHECK (anio_publicacion BETWEEN 1000 AND 2100)` | Año cronológico de edición de la obra. |
| `editorial` | `VARCHAR` | 80 | `NO` | — | — | — | Empresa o sello editorial que publica la obra. |

---

### 1.7 Tabla: `ejemplares`

- **Propósito:** Representa las copias físicas tangibles existentes en estantería para cada obra del catálogo.
- **Motor:** `InnoDB` | **Cotejamiento:** `utf8mb4_unicode_ci`

| Campo | Tipo | Longitud | Nulo | Clave | Default | Restricción | Descripción Funcional |
|---|---|---|---|---|---|---|---|
| `id` | `INT UNSIGNED` | 10 | `NO` | `PK` | `AUTO_INCREMENT` | — | Identificador único de la copia física en inventario. |
| `libro_id` | `INT UNSIGNED` | 10 | `NO` | `FK` | — | `REFERENCES libros(id)` | Obra bibliográfica padre. `ON UPDATE CASCADE ON DELETE RESTRICT`. |
| `codigo_inventario` | `VARCHAR` | 30 | `NO` | `UK` | — | `UNIQUE` | Código de barras o marbete único adherido al ejemplar físico. |
| `ubicacion_estante` | `VARCHAR` | 50 | `NO` | — | — | — | Localización física (sala, pasillo, estante o repisa). |
| `estado_conservacion` | `ENUM` | — | `NO` | — | `'Bueno'` | `'Excelente'`, `'Bueno'`, `'Regular'`, `'Dañado'` | Condición física y desgaste del material tangible. |
| `estado_disponibilidad` | `ENUM` | — | `NO` | — | `'Disponible'` | `'Disponible'`, `'Prestado'`, `'En Mantenimiento'`, `'Baja'` | Estatus de circulación y disponibilidad del ejemplar. |

---

### 1.8 Tabla: `prestamos`

- **Propósito:** Registro transaccional de los movimientos de salida y retorno de ejemplares solicitados por los usuarios y atendidos por un bibliotecario.
- **Motor:** `InnoDB` | **Cotejamiento:** `utf8mb4_unicode_ci`

| Campo | Tipo | Longitud | Nulo | Clave | Default | Restricción | Descripción Funcional |
|---|---|---|---|---|---|---|---|
| `id` | `INT UNSIGNED` | 10 | `NO` | `PK` | `AUTO_INCREMENT` | — | Identificador único del evento transaccional de préstamo. |
| `usuario_id` | `INT UNSIGNED` | 10 | `NO` | `FK` | — | `REFERENCES usuarios(id)` | Usuario solicitante. `ON UPDATE CASCADE ON DELETE RESTRICT`. |
| `ejemplar_id` | `INT UNSIGNED` | 10 | `NO` | `FK` | — | `REFERENCES ejemplares(id)` | Copia física prestada. `ON UPDATE CASCADE ON DELETE RESTRICT`. |
| `bibliotecario_id` | `INT UNSIGNED` | 10 | `NO` | `FK` | — | `REFERENCES bibliotecarios(id)` | Bibliotecario que registra el préstamo. `ON UPDATE CASCADE ON DELETE RESTRICT`. |
| `fecha_salida` | `DATE` | — | `NO` | — | — | — | Fecha en que el ejemplar es retirado de biblioteca. |
| `fecha_compromiso` | `DATE` | — | `NO` | — | — | `CHECK (fecha_compromiso >= fecha_salida)` | Fecha límite estipulada para la entrega del material. |
| `fecha_devolucion_real` | `DATE` | — | `SÍ` | — | `NULL` | — | Fecha real en que el material ingresó a biblioteca (`NULL` si está activo). |
| `estado_prestamo` | `ENUM` | — | `NO` | — | `'Activo'` | `'Activo'`, `'Devuelto'`, `'Vencido'` | Situación operativa del préstamo en circulación. |

---

### 1.9 Tabla: `multas`

- **Propósito:** Cuantificación y seguimiento financiero de sanciones económicas generadas por préstamos devueltos fuera de plazo.
- **Motor:** `InnoDB` | **Cotejamiento:** `utf8mb4_unicode_ci`

| Campo | Tipo | Longitud | Nulo | Clave | Default | Restricción | Descripción Funcional |
|---|---|---|---|---|---|---|---|
| `id` | `INT UNSIGNED` | 10 | `NO` | `PK` | `AUTO_INCREMENT` | — | Identificador unívoco de la multa generada. |
| `prestamo_id` | `INT UNSIGNED` | 10 | `NO` | `FK, UK` | — | `UNIQUE`, `REFERENCES prestamos(id)` | Préstamo en mora asociado (relación 1:0..1). `ON UPDATE CASCADE ON DELETE CASCADE`. |
| `bibliotecario_id` | `INT UNSIGNED` | 10 | `SÍ` | `FK` | `NULL` | `REFERENCES bibliotecarios(id)` | Bibliotecario que gestiona o cobra la multa (`NULL` si se genera automáticamente). `ON UPDATE CASCADE ON DELETE RESTRICT`. |
| `dias_retraso` | `SMALLINT UNSIGNED` | 5 | `NO` | — | — | `CHECK (dias_retraso >= 0)` | Cantidad de días de demora acumulados tras la fecha compromiso. |
| `monto_total` | `DECIMAL` | 8, 2 | `NO` | — | — | `CHECK (monto_total >= 0.00)` | Saldo total a liquidar ($15.00 MXN por cada día de mora). |
| `fecha_generacion` | `DATE` | — | `NO` | — | — | — | Fecha en que se emite la sanción en el sistema. |
| `estado_pago` | `ENUM` | — | `NO` | — | `'Pendiente'` | `'Pendiente'`, `'Pagada'`, `'Condonada'` | Estatus contable de la penalización financiera. |

---

## 2. Sentencias DDL de Creación (`CREATE TABLE`)

A continuación se presentan las sentencias en lenguaje DDL (*Data Definition Language*) para la creación de las 9 tablas en MySQL/MariaDB. Las sentencias se ordenan respetando rigurosamente las dependencias de integridad referencial: primero las entidades base e independientes, y posteriormente las entidades dependientes y transaccionales.

```sql
-- =============================================================================
-- SISTEMA DE GESTIÓN BIBLIOTECARIA - ESQUEMA EN TERCERA FORMA NORMAL (3FN)
-- Motor: InnoDB | Codificación: utf8mb4 | Cotejamiento: utf8mb4_unicode_ci
-- =============================================================================

-- 1. TABLA: tipos_usuario (Entidad independiente)
CREATE TABLE tipos_usuario (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_tipo VARCHAR(50) NOT NULL,
    limite_libros TINYINT UNSIGNED NOT NULL,
    dias_prestamo TINYINT UNSIGNED NOT NULL,
    CONSTRAINT uq_tipos_usuario_nombre UNIQUE (nombre_tipo),
    CONSTRAINT chk_tipos_limite CHECK (limite_libros > 0),
    CONSTRAINT chk_tipos_dias CHECK (dias_prestamo > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. TABLA: usuarios (Depende de tipos_usuario)
CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tipo_id INT UNSIGNED NOT NULL,
    matricula_num_emp VARCHAR(20) NOT NULL,
    nombre_completo VARCHAR(120) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    telefono VARCHAR(15) NULL,
    estado ENUM('Activo', 'Suspendido', 'Baja') NOT NULL DEFAULT 'Activo',
    CONSTRAINT uq_usuarios_matricula UNIQUE (matricula_num_emp),
    CONSTRAINT uq_usuarios_correo UNIQUE (correo),
    CONSTRAINT chk_usuarios_correo CHECK (correo LIKE '%@%'),
    CONSTRAINT fk_usuarios_tipo FOREIGN KEY (tipo_id)
        REFERENCES tipos_usuario(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. TABLA: bibliotecarios (Entidad independiente - personal operativo)
CREATE TABLE bibliotecarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    num_empleado VARCHAR(20) NOT NULL,
    nombre_completo VARCHAR(120) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    estado ENUM('Activo', 'Inactivo') NOT NULL DEFAULT 'Activo',
    CONSTRAINT uq_bibliotecarios_num_empleado UNIQUE (num_empleado),
    CONSTRAINT uq_bibliotecarios_correo UNIQUE (correo),
    CONSTRAINT chk_bibliotecarios_correo CHECK (correo LIKE '%@%')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. TABLA: autores (Entidad independiente)
CREATE TABLE autores (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_autor VARCHAR(100) NOT NULL,
    nacionalidad VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. TABLA: categorias (Entidad independiente)
CREATE TABLE categorias (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(50) NOT NULL,
    descripcion VARCHAR(255) NULL,
    CONSTRAINT uq_categorias_nombre UNIQUE (nombre_categoria)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. TABLA: libros (Depende de autores y categorias)
CREATE TABLE libros (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    autor_id INT UNSIGNED NOT NULL,
    categoria_id INT UNSIGNED NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    isbn VARCHAR(20) NOT NULL,
    anio_publicacion SMALLINT UNSIGNED NOT NULL,
    editorial VARCHAR(80) NOT NULL,
    CONSTRAINT uq_libros_isbn UNIQUE (isbn),
    CONSTRAINT chk_libros_anio CHECK (anio_publicacion BETWEEN 1000 AND 2100),
    CONSTRAINT fk_libros_autor FOREIGN KEY (autor_id)
        REFERENCES autores(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT fk_libros_categoria FOREIGN KEY (categoria_id)
        REFERENCES categorias(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. TABLA: ejemplares (Depende de libros)
CREATE TABLE ejemplares (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    libro_id INT UNSIGNED NOT NULL,
    codigo_inventario VARCHAR(30) NOT NULL,
    ubicacion_estante VARCHAR(50) NOT NULL,
    estado_conservacion ENUM('Excelente', 'Bueno', 'Regular', 'Dañado') NOT NULL DEFAULT 'Bueno',
    estado_disponibilidad ENUM('Disponible', 'Prestado', 'En Mantenimiento', 'Baja') NOT NULL DEFAULT 'Disponible',
    CONSTRAINT uq_ejemplares_codigo UNIQUE (codigo_inventario),
    CONSTRAINT fk_ejemplares_libro FOREIGN KEY (libro_id)
        REFERENCES libros(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. TABLA: prestamos (Depende de usuarios, ejemplares y bibliotecarios)
CREATE TABLE prestamos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    ejemplar_id INT UNSIGNED NOT NULL,
    bibliotecario_id INT UNSIGNED NOT NULL,
    fecha_salida DATE NOT NULL,
    fecha_compromiso DATE NOT NULL,
    fecha_devolucion_real DATE NULL,
    estado_prestamo ENUM('Activo', 'Devuelto', 'Vencido') NOT NULL DEFAULT 'Activo',
    CONSTRAINT chk_prestamos_fechas CHECK (fecha_compromiso >= fecha_salida),
    CONSTRAINT fk_prestamos_usuario FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT fk_prestamos_ejemplar FOREIGN KEY (ejemplar_id)
        REFERENCES ejemplares(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT fk_prestamos_bibliotecario FOREIGN KEY (bibliotecario_id)
        REFERENCES bibliotecarios(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. TABLA: multas (Depende de prestamos y bibliotecarios - Relación 1:0..1)
CREATE TABLE multas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    prestamo_id INT UNSIGNED NOT NULL,
    bibliotecario_id INT UNSIGNED NULL,
    dias_retraso SMALLINT UNSIGNED NOT NULL,
    monto_total DECIMAL(8, 2) NOT NULL,
    fecha_generacion DATE NOT NULL,
    estado_pago ENUM('Pendiente', 'Pagada', 'Condonada') NOT NULL DEFAULT 'Pendiente',
    CONSTRAINT uq_multas_prestamo UNIQUE (prestamo_id),
    CONSTRAINT chk_multas_dias CHECK (dias_retraso >= 0),
    CONSTRAINT chk_multas_monto CHECK (monto_total >= 0.00),
    CONSTRAINT fk_multas_prestamo FOREIGN KEY (prestamo_id)
        REFERENCES prestamos(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_multas_bibliotecario FOREIGN KEY (bibliotecario_id)
        REFERENCES bibliotecarios(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 3. Matriz de Integridad y Validación en Dos Niveles

Para satisfacer los requisitos obligatorios de validación de datos en dos niveles estipulados en `ABOUT.md`, la siguiente matriz resume el mecanismo de protección implementado tanto a nivel de motor de base de datos como en los formularios y controladores de la aplicación web (frontend y backend en PHP):

| Entidad / Campo | Regla de Negocio | Nivel 1: Motor de Base de Datos (SQL / DDL) | Nivel 2: Interfaz y Aplicación Web (PHP / Frontend) |
|---|---|---|---|
| `tipos_usuario.limite_libros` | Valores estrictamente positivos. | `CHECK (limite_libros > 0)` | Input HTML `min="1"`, validación PHP `filter_var(..., FILTER_VALIDATE_INT)`. |
| `tipos_usuario.dias_prestamo` | Plazos positivos. | `CHECK (dias_prestamo > 0)` | Input HTML `min="1"`, validación PHP `intval($dias) > 0`. |
| `usuarios.matricula_num_emp` | Clave única de alumno o empleado. | `CONSTRAINT uq_usuarios_matricula UNIQUE` | Validación con regex institucional y consulta previa de existencia en PHP. |
| `usuarios.correo` | Formato de correo válido y sin duplicados. | `UNIQUE`, `CHECK (correo LIKE '%@%')` | Input `type="email"`, validación PHP `filter_var($email, FILTER_VALIDATE_EMAIL)`. |
| `usuarios.estado` | Estatus restringido a catálogo cerrado. | `ENUM('Activo', 'Suspendido', 'Baja')` | Menú `<select>` predefinido y validación con `in_array()` en PHP. |
| `bibliotecarios.num_empleado` | Clave única de trabajador. | `CONSTRAINT uq_bibliotecarios_num_empleado UNIQUE` | Validación de formato y consulta previa de existencia en PHP. |
| `bibliotecarios.correo` | Correo único para inicio de sesión. | `UNIQUE`, `CHECK (correo LIKE '%@%')` | Input `type="email"`, validación `filter_var($email, FILTER_VALIDATE_EMAIL)` en PHP. |
| `bibliotecarios.password_hash` | Almacenamiento seguro de credenciales. | `VARCHAR(255) NOT NULL` | Generación y verificación exclusiva con `password_hash()` / `password_verify()` en PHP. |
| `bibliotecarios.estado` | Control de acceso operativo. | `ENUM('Activo', 'Inactivo')` | Bloqueo de login en PHP si el estado es `'Inactivo'`. |
| `libros.isbn` | Identificador bibliográfico unívoco. | `CONSTRAINT uq_libros_isbn UNIQUE` | Validación de máscara ISBN (10 o 13 dígitos) y sanitización de guiones. |
| `libros.anio_publicacion` | Año histórico razonable. | `CHECK (anio_publicacion BETWEEN 1000 AND 2100)` | Input `min="1000" max="2100"`, comprobación con año actual en PHP. |
| `ejemplares.codigo_inventario` | Marbete físico único e irrepetible. | `CONSTRAINT uq_ejemplares_codigo UNIQUE` | Validación de unicidad previa y formato alfanumérico en backend. |
| `ejemplares.disponibilidad` | Estatus operativo de circulación. | `ENUM('Disponible', 'Prestado', ...)` | Comprobación de estado `'Disponible'` antes de autorizar préstamo. |
| `prestamos.fechas` | Fecha compromiso igual o posterior a salida. | `CHECK (fecha_compromiso >= fecha_salida)` | Validación frontend `min="fecha_salida"` y verificación cronológica `strtotime()` en PHP. |
| `multas.prestamo_id` | Máximo una multa por préstamo (1:0..1). | `CONSTRAINT uq_multas_prestamo UNIQUE` | Verificación de que no exista sanción previa antes del insert en PHP. |
| `multas.monto_total` | Importes no negativos con centavos exactos. | `DECIMAL(8,2)`, `CHECK (monto_total >= 0)` | Cálculo server-side en PHP (`$dias * 15.00`) sin permitir edición manual en front. |
| **Relaciones (FK)** | Prohibición de registros huérfanos. | `ON UPDATE CASCADE ON DELETE RESTRICT` | Manejo de excepciones `PDOException` (código 23000) mostrando mensajes amigables. |
