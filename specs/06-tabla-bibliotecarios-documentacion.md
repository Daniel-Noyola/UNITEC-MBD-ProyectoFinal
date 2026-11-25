# SPEC 06 — Incorporación de la Tabla `bibliotecarios` y Actualización Documental

> **Status:** Implemented 
> **Depends on:** SPEC 05  
> **Date:** 2026-11-18  
> **Objective:** Incorporar la tabla `bibliotecarios` (administradores/trabajadores de la biblioteca) y su relación con `prestamos` y `multas` en todo el diseño de la base de datos (9 tablas en 3FN), actualizando de forma coherente los documentos Markdown (`docs/01` a `docs/05`) y los diagramas PNG generados.

---

## 1. Contexto

El diseño actual del Sistema de Gestión Bibliotecaria contempla 8 tablas en Tercera Forma Normal (3FN) centradas en el catálogo bibliográfico, el padrón de prestatarios (`usuarios` y `tipos_usuario`), los `prestamos` y las `multas`. Aunque en los requerimientos se menciona al **Bibliotecario / Administrador** como actor operativo, hasta ahora no existía una entidad en la base de datos para almacenar sus cuentas de acceso ni para registrar qué trabajador atiende cada préstamo o multa.

Al agregar `bibliotecarios` como novena tabla, el esquema alcanza **9 tablas** (manteniéndose dentro del requisito de 7 a 9 tablas establecido en `ABOUT.md`), habilita la autenticación de administradores en la futura aplicación web y aporta trazabilidad operativa sobre `prestamos` y `multas`.

---

## 2. Alcance

**In:**

- Incorporación de la entidad `bibliotecarios` en el modelo relacional (9 tablas totales en 3FN).
- Relación de `bibliotecarios` (`1`) hacia `prestamos` (`0..N`) mediante `bibliotecario_id` (`FK`).
- Relación de `bibliotecarios` (`1`) hacia `multas` (`0..N`) mediante `bibliotecario_id` (`FK`).
- Actualización coherente de los 5 documentos Markdown en `docs/`:
  - `docs/01-planteamiento-del-problema.md`
  - `docs/02-requerimientos-del-sistema.md`
  - `docs/03-diseno-y-normalizacion-bd.md`
  - `docs/04-diagrama-entidad-relacion.md`
  - `docs/05-diccionario-de-datos.md`
- Regeneración de las imágenes PNG de los diagramas Mermaid (`docs/word/assets/der_conceptual.png` y `docs/word/assets/der_logico.png`) mediante `scripts/render_mermaid_diagrams.py`.

**Out of scope:**

- Recompilación de los archivos Word (`.docx`) en `docs/word/` y PDF (`.pdf`) en `docs/pdf/`.
- Creación de scripts `.sql` independientes en el repositorio.
- Implementación de código PHP, frontend o módulo de inicio de sesión.
- Adición de un campo `rol` en `bibliotecarios` (todos los registros representan personal administrador/bibliotecario de la biblioteca).

---

## 3. Modelo de datos

El esquema pasa de 8 a **9 tablas** en 3FN, conservando la convención de `id` como clave primaria y `[entidad]_id` para las claves foráneas.

### 3.1 Nueva tabla: `bibliotecarios`

| Campo | Tipo MySQL | Nulidad | Clave | Default | Restricción | Descripción |
|---|---|---|---|---|---|---|
| `id` | `INT UNSIGNED` | `NO` | `PK` | `AUTO_INCREMENT` | — | Identificador único del bibliotecario. |
| `num_empleado` | `VARCHAR(20)` | `NO` | `UK` | — | `UNIQUE` | Número de empleado institucional del trabajador. |
| `nombre_completo` | `VARCHAR(120)` | `NO` | — | — | — | Nombre completo del bibliotecario. |
| `correo` | `VARCHAR(100)` | `NO` | `UK` | — | `UNIQUE`, `CHECK (correo LIKE '%@%')` | Correo electrónico institucional de acceso. |
| `password_hash` | `VARCHAR(255)` | `NO` | — | — | — | Hash seguro de la contraseña de acceso al sistema. |
| `estado` | `ENUM('Activo', 'Inactivo')` | `NO` | — | `'Activo'` | — | Estado operativo de la cuenta del bibliotecario. |

### 3.2 Modificaciones en tablas existentes

1. **`prestamos`:**
   - Se agrega `bibliotecario_id` (`INT UNSIGNED NOT NULL`), clave foránea que referencia a `bibliotecarios(id)` con `ON UPDATE CASCADE ON DELETE RESTRICT`.
   - Cardinalidad: `BIBLIOTECARIOS ||--o{ PRESTAMOS` (un bibliotecario registra cero o muchos préstamos; cada préstamo es registrado por exactamente un bibliotecario).

2. **`multas`:**
   - Se agrega `bibliotecario_id` (`INT UNSIGNED NULL`), clave foránea que referencia a `bibliotecarios(id)` con `ON UPDATE CASCADE ON DELETE RESTRICT`.
   - Cardinalidad: `BIBLIOTECARIOS ||--o{ MULTAS` (un bibliotecario gestiona o liquida cero o muchas multas; una multa puede asociarse opcionalmente al bibliotecario que la registra o cobra).

### 3.3 Resumen de las 9 tablas resultantes

```text
1. tipos_usuario (id [PK], nombre_tipo, limite_libros, dias_prestamo)
2. usuarios (id [PK], tipo_id [FK], matricula_num_emp, nombre_completo, correo, telefono, estado)
3. bibliotecarios (id [PK], num_empleado, nombre_completo, correo, password_hash, estado)
4. autores (id [PK], nombre_autor, nacionalidad)
5. categorias (id [PK], nombre_categoria, descripcion)
6. libros (id [PK], autor_id [FK], categoria_id [FK], titulo, isbn, anio_publicacion, editorial)
7. ejemplares (id [PK], libro_id [FK], codigo_inventario, ubicacion_estante, estado_conservacion, estado_disponibilidad)
8. prestamos (id [PK], usuario_id [FK], ejemplar_id [FK], bibliotecario_id [FK], fecha_salida, fecha_compromiso, fecha_devolucion_real, estado_prestamo)
9. multas (id [PK], prestamo_id [FK/UK], bibliotecario_id [FK], dias_retraso, monto_total, fecha_generacion, estado_pago)
```

---

## 4. Plan de implementación

1. **Actualizar `docs/01-planteamiento-del-problema.md`:**
   - Ajustar el alcance y la descripción de la solución para reflejar que el personal bibliotecario cuenta con credenciales y registro propio dentro de la base de datos para operar el sistema web.
2. **Actualizar `docs/02-requerimientos-del-sistema.md`:**
   - Incorporar la gestión/autenticación básica de bibliotecarios y la trazabilidad del operador en préstamos y multas dentro de los requerimientos funcionales, reglas de negocio y matriz de trazabilidad, manteniendo la coherencia con las 9 tablas.
3. **Actualizar `docs/03-diseno-y-normalizacion-bd.md`:**
   - Actualizar las referencias de 8 a **9 tablas** en 3FN.
   - Documentar la inclusión de `bibliotecarios` y la integración de `bibliotecario_id` en `prestamos` y `multas` dentro del análisis de normalización (1FN, 2FN, 3FN) y el esquema relacional final.
4. **Actualizar `docs/04-diagrama-entidad-relacion.md`:**
   - Agregar `BIBLIOTECARIOS` y sus relaciones con `PRESTAMOS` y `MULTAS` en el diagrama Mermaid conceptual (`flowchart TD`) y en el diagrama lógico Crow's Foot (`erDiagram`).
   - Actualizar la lista de claves foráneas, la matriz detallada de relaciones/cardinalidades (de 7 a 9 relaciones) y las políticas de integridad referencial (`ON UPDATE CASCADE / ON DELETE RESTRICT`).
5. **Actualizar `docs/05-diccionario-de-datos.md`:**
   - Actualizar el índice y encabezados de 8 a **9 tablas**.
   - Agregar la ficha técnica y sentencia `CREATE TABLE bibliotecarios`.
   - Agregar la columna `bibliotecario_id` y su `CONSTRAINT FOREIGN KEY` en las fichas técnicas y DDL de `prestamos` y `multas`.
6. **Regenerar diagramas PNG:**
   - Ejecutar `python scripts/render_mermaid_diagrams.py` para actualizar `docs/word/assets/der_conceptual.png` y `docs/word/assets/der_logico.png`.

---

## 5. Criterios de aceptación

- [ ] Los 5 documentos (`docs/01` a `docs/05`) referencian consistentemente **9 tablas** (sin menciones residuales a "8 tablas" o "7 relaciones").
- [ ] La tabla `bibliotecarios` está documentada en 3FN, en el DER conceptual/lógico y en el Diccionario de Datos (ficha técnica + DDL) con exactamente las columnas: `id`, `num_empleado`, `nombre_completo`, `correo`, `password_hash` y `estado` (sin columna `rol`).
- [ ] Las tablas `prestamos` y `multas` incluyen `bibliotecario_id` como clave foránea hacia `bibliotecarios(id)` con `ON UPDATE CASCADE ON DELETE RESTRICT` en `docs/03`, `docs/04` y `docs/05`.
- [ ] Los archivos `docs/word/assets/der_conceptual.png` y `docs/word/assets/der_logico.png` se regeneran sin errores y reflejan la entidad `BIBLIOTECARIOS` y sus relaciones.

---

## 6. Decisiones tomadas y descartadas

- **Tabla `bibliotecarios` separada vs. reutilizar `usuarios`:** Se decidió crear una tabla independiente `bibliotecarios` en lugar de mezclar trabajadores con prestatarios en `usuarios`, manteniendo la separación de responsabilidades y cumpliendo el límite de 7 a 9 tablas (queda en 9).
- **Omisión del campo `rol` en `bibliotecarios`:** Se descartó incluir una columna `rol` porque todos los registros de esta tabla representan administradores/trabajadores de la biblioteca con acceso operativo.
- **Trazabilidad en `prestamos` y `multas` (`bibliotecario_id`):** Se decidió relacionar `bibliotecarios` con ambas tablas; en `prestamos` es `NOT NULL` (todo préstamo se atiende en mostrador) y en `multas` es `NULL` (permite que una multa se genere automáticamente al vencer el plazo y se asocie al bibliotecario al registrarse su cobro o gestión).
- **Alcance documental sin compilación Word/PDF ni código PHP:** Se acordó actualizar únicamente los Markdown (`docs/01` a `docs/05`) y los PNGs de diagramas, dejando la recompilación `.docx`/`.pdf` y el desarrollo PHP/SQL ejecutable fuera de este spec.

---

## 7. Riesgos identificados

- **Desfase temporal con archivos compilados:** Al no regenerar los `.docx` en `docs/word/` ni los `.pdf` en `docs/pdf/` en este spec, esos binarios quedarán temporalmente desactualizados respecto a los Markdown de `docs/` hasta que se ejecute posteriormente `scripts/export_md_to_docx.py`.
