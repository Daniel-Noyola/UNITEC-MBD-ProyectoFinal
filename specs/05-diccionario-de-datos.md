# SPEC 05 — Diccionario de Datos

> **Status:** Approved  
> **Depends on:** SPEC 04  
> **Date:** 2026-11-11  
> **Objective:** Documentar exhaustivamente el Diccionario de Datos para las 8 tablas en 3FN del Sistema de Gestión Bibliotecaria bajo MySQL/MariaDB (InnoDB, utf8mb4), detallando la especificación por columna, dominios, restricciones de integridad y sentencias DDL completas dentro del documento técnico y su versión Word formal.

---

## 1. Contexto

Este documento establece las especificaciones para el desarrollo del **Entregable 5: Diccionario de Datos** definido en `ABOUT.md`. Toma como base directa el esquema normalizado en Tercera Forma Normal (3FN) consolidado en `docs/03-diseno-y-normalizacion-bd.md` (SPEC 03) y la estandarización de identificadores (`id` como PK y `[entidad]_id` como FK) aprobada en `docs/04-diagrama-entidad-relacion.md` (SPEC 04).

El propósito central es proporcionar la ficha técnica detallada de cada una de las 8 entidades relacionales, traduciendo los conceptos lógicos a tipos de datos concretos de almacenamiento en MySQL/MariaDB sobre el entorno WampServer, definiendo reglas de validación en el motor (restricciones `NOT NULL`, `UNIQUE`, `CHECK`, llaves foráneas con reglas de acción referencial `ON DELETE` / `ON UPDATE` y valores predeterminados) y acompañando cada tabla con su bloque DDL (`CREATE TABLE`) embebido y comentado.

---

## 2. Alcance

**In:**

- Redacción del documento técnico formal en `docs/05-diccionario-de-datos.md`.
- **Estructura y Fichas Técnicas del Diccionario de Datos (8 tablas):**
  - Ficha técnica exhaustiva para cada entidad:
    1. `tipos_usuario`
    2. `usuarios`
    3. `autores`
    4. `categorias`
    5. `libros`
    6. `ejemplares`
    7. `prestamos`
    8. `multas`
  - Campos detallados por columna en cada ficha:
    - Nombre del campo.
    - Tipo de dato físico en MySQL/MariaDB (`INT`, `TINYINT`, `SMALLINT`, `VARCHAR`, `DATE`, `DECIMAL`, `ENUM`).
    - Longitud / Precisión y escala.
    - Nulidad (`NOT NULL` / `NULL`).
    - Clave / Rol (`PK`, `FK`, `UK` - Unique Key, Ninguna).
    - Valor por defecto (`DEFAULT`).
    - Restricciones de integridad (`CHECK`, `UNIQUE`, rangos de valores válidos).
    - Descripción funcional y regla de negocio asociada.
- **Especificación de Sentencias DDL (`CREATE TABLE`):**
  - Bloque SQL documentado y comentado para cada una de las 8 tablas dentro del documento Markdown.
  - Estandarización de motor de almacenamiento: `ENGINE=InnoDB`.
  - Estandarización de juego de caracteres y cotejamiento: `DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci`.
  - Definición formal de claves foráneas con acciones referenciales justificadas (`ON DELETE RESTRICT` / `ON UPDATE CASCADE`, y `ON DELETE CASCADE` solo en multas por préstamo).
- **Compilación institucional a formato Word:**
  - Integración y renderizado en `docs/word/05-diccionario-de-datos.docx` mediante `scripts/export_md_to_docx.py`.
  - Aplicación de diseño editorial universitario (paleta cromática Unitec, tablas cebradas, encabezados y pies de página formales).

**Out of scope (delimitación con otros entregables y fases):**

- Creación de archivos `.sql` independientes en el repositorio en esta fase (las sentencias DDL se documentan íntegramente dentro de los documentos Markdown y Word).
- Especificación y pruebas de las 5 consultas relacionales complejas con `JOIN` (quedan formalmente postergadas para el Entregable 6 / desarrollo del backend en PHP).
- Implementación de código de interfaz de usuario o scripts PHP de conexión a base de datos.

---

## 3. Modelo de datos

El diccionario formaliza las 8 tablas bajo la nomenclatura estandarizada:

```text
1. tipos_usuario (id [PK], nombre_tipo, limite_libros, dias_prestamo)
2. usuarios (id [PK], tipo_id [FK], matricula_num_emp, nombre_completo, correo, telefono, estado)
3. autores (id [PK], nombre_autor, nacionalidad)
4. categorias (id [PK], nombre_categoria, descripcion)
5. libros (id [PK], autor_id [FK], categoria_id [FK], titulo, isbn, anio_publicacion, editorial)
6. ejemplares (id [PK], libro_id [FK], codigo_inventario, ubicacion_estante, estado_conservacion, estado_disponibilidad)
7. prestamos (id [PK], usuario_id [FK], ejemplar_id [FK], fecha_salida, fecha_compromiso, fecha_devolucion_real, estado_prestamo)
8. multas (id [PK], prestamo_id [FK/UK], dias_retraso, monto_total, fecha_generacion, estado_pago)
```

### 3.1 Especificación de Dominios y Restricciones por Tabla

#### Tabla 1: `tipos_usuario`
- `id`: `INT UNSIGNED AUTO_INCREMENT PRIMARY KEY`.
- `nombre_tipo`: `VARCHAR(50) NOT NULL UNIQUE`.
- `limite_libros`: `TINYINT UNSIGNED NOT NULL CHECK (limite_libros > 0)`.
- `dias_prestamo`: `TINYINT UNSIGNED NOT NULL CHECK (dias_prestamo > 0)`.

#### Tabla 2: `usuarios`
- `id`: `INT UNSIGNED AUTO_INCREMENT PRIMARY KEY`.
- `tipo_id`: `INT UNSIGNED NOT NULL` (FK referenciando `tipos_usuario(id)` `ON UPDATE CASCADE ON DELETE RESTRICT`).
- `matricula_num_emp`: `VARCHAR(20) NOT NULL UNIQUE`.
- `nombre_completo`: `VARCHAR(120) NOT NULL`.
- `correo`: `VARCHAR(100) NOT NULL UNIQUE CHECK (correo LIKE '%@%')`.
- `telefono`: `VARCHAR(15) NULL`.
- `estado`: `ENUM('Activo', 'Suspendido', 'Baja') NOT NULL DEFAULT 'Activo'`.

#### Tabla 3: `autores`
- `id`: `INT UNSIGNED AUTO_INCREMENT PRIMARY KEY`.
- `nombre_autor`: `VARCHAR(100) NOT NULL`.
- `nacionalidad`: `VARCHAR(50) NOT NULL`.

#### Tabla 4: `categorias`
- `id`: `INT UNSIGNED AUTO_INCREMENT PRIMARY KEY`.
- `nombre_categoria`: `VARCHAR(50) NOT NULL UNIQUE`.
- `descripcion`: `VARCHAR(255) NULL`.

#### Tabla 5: `libros`
- `id`: `INT UNSIGNED AUTO_INCREMENT PRIMARY KEY`.
- `autor_id`: `INT UNSIGNED NOT NULL` (FK referenciando `autores(id)` `ON UPDATE CASCADE ON DELETE RESTRICT`).
- `categoria_id`: `INT UNSIGNED NOT NULL` (FK referenciando `categorias(id)` `ON UPDATE CASCADE ON DELETE RESTRICT`).
- `titulo`: `VARCHAR(150) NOT NULL`.
- `isbn`: `VARCHAR(20) NOT NULL UNIQUE`.
- `anio_publicacion`: `SMALLINT UNSIGNED NOT NULL CHECK (anio_publicacion BETWEEN 1000 AND 2100)`.
- `editorial`: `VARCHAR(80) NOT NULL`.

#### Tabla 6: `ejemplares`
- `id`: `INT UNSIGNED AUTO_INCREMENT PRIMARY KEY`.
- `libro_id`: `INT UNSIGNED NOT NULL` (FK referenciando `libros(id)` `ON UPDATE CASCADE ON DELETE RESTRICT`).
- `codigo_inventario`: `VARCHAR(30) NOT NULL UNIQUE`.
- `ubicacion_estante`: `VARCHAR(50) NOT NULL`.
- `estado_conservacion`: `ENUM('Excelente', 'Bueno', 'Regular', 'Dañado') NOT NULL DEFAULT 'Bueno'`.
- `estado_disponibilidad`: `ENUM('Disponible', 'Prestado', 'En Mantenimiento', 'Baja') NOT NULL DEFAULT 'Disponible'`.

#### Tabla 7: `prestamos`
- `id`: `INT UNSIGNED AUTO_INCREMENT PRIMARY KEY`.
- `usuario_id`: `INT UNSIGNED NOT NULL` (FK referenciando `usuarios(id)` `ON UPDATE CASCADE ON DELETE RESTRICT`).
- `ejemplar_id`: `INT UNSIGNED NOT NULL` (FK referenciando `ejemplares(id)` `ON UPDATE CASCADE ON DELETE RESTRICT`).
- `fecha_salida`: `DATE NOT NULL`.
- `fecha_compromiso`: `DATE NOT NULL CHECK (fecha_compromiso >= fecha_salida)`.
- `fecha_devolucion_real`: `DATE NULL`.
- `estado_prestamo`: `ENUM('Activo', 'Devuelto', 'Vencido') NOT NULL DEFAULT 'Activo'`.

#### Tabla 8: `multas`
- `id`: `INT UNSIGNED AUTO_INCREMENT PRIMARY KEY`.
- `prestamo_id`: `INT UNSIGNED NOT NULL UNIQUE` (FK referenciando `prestamos(id)` `ON UPDATE CASCADE ON DELETE CASCADE`).
- `dias_retraso`: `SMALLINT UNSIGNED NOT NULL CHECK (dias_retraso >= 0)`.
- `monto_total`: `DECIMAL(8, 2) NOT NULL CHECK (monto_total >= 0.00)`.
- `fecha_generacion`: `DATE NOT NULL`.
- `estado_pago`: `ENUM('Pendiente', 'Pagada', 'Condonada') NOT NULL DEFAULT 'Pendiente'`.

---

## 4. Plan de implementación

1. **Estructura base del documento Markdown:** Crear `docs/05-diccionario-de-datos.md` con membrete formal institucional, datos de la materia (Modelos de Bases de Datos), proyecto, autoría e índice general directo.
2. **Elaboración de las 8 Fichas Técnicas del Diccionario de Datos:**
   - Construir la ficha técnica exhaustiva por cada entidad con las 8 columnas requeridas (Campo, Tipo de dato MySQL, Longitud, Nulidad, Llaves PK/FK/UK, Default, Restricción, Descripción funcional).
3. **Redacción de Sentencias DDL (`CREATE TABLE`) comentadas:**
   - Incorporar dentro del documento el bloque SQL sintácticamente válido para cada tabla, incluyendo llaves primarias, llaves foráneas (`ON DELETE` / `ON UPDATE`), índices de unicidad (`UNIQUE`) y restricciones de verificación (`CHECK`), con `ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci`.
   - Aplicar el orden correcto de creación para respetar la integridad referencial.
4. **Matriz de Integridad y Validación en Dos Niveles:**
   - Resumen conciso de restricciones en base de datos vs. validaciones en interfaz/formularios web (PHP).
5. **Compilación a formato Word institucional:**
   - Ejecutar `scripts/export_md_to_docx.py` para procesar `docs/05-diccionario-de-datos.md` y producir `docs/word/05-diccionario-de-datos.docx`.
6. **Revisión y validación de consistencia:**
   - Validar coherencia con `docs/04-diagrama-entidad-relacion.md` y verificar que el documento Word se genere correctamente.

---

## 5. Criterios de aceptación

- [x] El archivo `docs/05-diccionario-de-datos.md` existe en la carpeta `docs/`.
- [x] Contiene encabezado formal universitario institucional con metadatos completos de entrega.
- [x] Presenta un formato directo y conciso, omitiendo secciones de justificación teórica y enfocándose de lleno en las fichas técnicas y DDL.
- [x] Contiene las 8 fichas técnicas completas correspondientes a: `tipos_usuario`, `usuarios`, `autores`, `categorias`, `libros`, `ejemplares`, `prestamos` y `multas`.
- [x] Cada ficha técnica desglosa exhaustivamente: Nombre, Tipo de dato MySQL, Longitud/Precisión, Nulidad, Llaves (PK/FK/UK), Default, Restricciones (`CHECK`/`UNIQUE`) y Descripción funcional.
- [x] Incluye los bloques de código DDL (`CREATE TABLE`) completos y comentados para cada una de las 8 tablas dentro del documento.
- [x] Las restricciones de integridad referencial definen explícitamente `ON UPDATE CASCADE` y `ON DELETE RESTRICT` (o `CASCADE` en `multas`).
- [x] Las 5 consultas relacionales complejas quedan explícitamente excluidas del alcance de este entregable, dejándolas para el Entregable 6.
- [x] No se generan scripts `.sql` externos en esta etapa (las sentencias residen dentro de la documentación).
- [x] El script `scripts/export_md_to_docx.py` compila exitosamente `docs/word/05-diccionario-de-datos.docx` sin errores.
- [x] Mantiene total coherencia y trazabilidad con el modelo 3FN de SPEC 03 y el DER de SPEC 04.

---

## 6. Decisiones tomadas y descartadas

- **Decisión:** Documentar las sentencias DDL únicamente dentro del documento Markdown y Word sin crear archivos `.sql` externos en el repositorio por ahora.
  - *Justificación:* Elección explícita del usuario para centrar el Entregable 5 en la rigurosa especificación documental y fichas técnicas requeridas por la rúbrica académica, reservando la creación y despliegue de scripts SQL físicos para el entorno funcional del Entregable 6.
- **Decisión:** Postergar la formulación de las 5 consultas relacionales complejas con `JOIN` para el Entregable 6.
  - *Justificación:* Elección explícita del usuario para delimitar el alcance del Diccionario de Datos exclusivamente a la estructura estática de almacenamiento, atributos y restricciones, integrando las consultas dinámicas junto a la implementación del backend en PHP y las pruebas de funcionamiento en WAMP.
- **Decisión:** Adoptar fichas técnicas exhaustivas con 8 atributos descriptivos por columna.
  - *Justificación:* Garantiza el máximo rigor técnico académico, cubriendo tanto la tipificación estricta de base de datos como las reglas de negocio y restricciones operativas asociadas.
- **Decisión:** Estandarizar `ENGINE=InnoDB` con `CHARSET=utf8mb4` y `COLLATE=utf8mb4_unicode_ci`.
  - *Justificación:* `InnoDB` es indispensable para garantizar el cumplimiento de propiedades ACID y soporte nativo de llaves foráneas; `utf8mb4` previene problemas de codificación con caracteres especiales, acentos y signos ortográficos en español.
- **Decisión:** Omitir secciones de justificación teórica y capítulos de estándares técnicos en el documento Markdown/Word.
  - *Justificación:* Solicitud expresa del usuario para priorizar un entregable directo, conciso y de alta utilidad técnica, pasando de inmediato a las fichas del diccionario de datos de las 8 tablas, las sentencias DDL y la matriz de validación en dos niveles.
- **Descartada:** Usar tipos de datos genéricos o abreviados sin precisión (ej. solo `VARCHAR` sin longitud o `NUMBER` genérico).
  - *Motivo:* El diccionario de datos debe reflejar las capacidades reales y óptimas del motor MySQL/MariaDB para un entorno de producción local sobre WAMP.

---

## 7. Riesgos identificados

- **Riesgo:** Soporte limitado de sintaxis `CHECK` en versiones muy antiguas de MySQL (anteriores a MySQL 8.0.16).
  - *Mitigación:* Se documentará en el texto que las restricciones `CHECK` son evaluadas por MySQL 8.0+ y MariaDB 10.2+, y que de forma complementaria se implementarán validaciones a nivel de backend en PHP para garantizar integridad en versiones legadas.
- **Riesgo:** Desbordamiento horizontal de tablas con 8 columnas en la conversión a documento Word (.docx).
  - *Mitigación:* El script `export_md_to_docx.py` aplica formateo automático de anchos de columna en tablas y tamaños de fuente compactos adecuados para impresión en hoja tamaño carta estándar.
