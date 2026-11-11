# SPEC 03 — Diseño y Normalización de Base de Datos

> **Status:** Approved  
> **Depends on:** SPEC 02  
> **Date:** 2026-11-10  
> **Objective:** Documentar técnicamente el proceso de análisis y normalización paso a paso (desde las fichas operativas desnormalizadas hasta la Tercera Forma Normal - 3FN), derivando la arquitectura relacional de 8 tablas requerida para el Sistema de Gestión Bibliotecaria.

---

## 1. Contexto

Este documento corresponde a la especificación técnica del **Entregable 3: Diseño y Normalización de BD** descrito en `ABOUT.md`. Toma como insumo los requerimientos funcionales y reglas de negocio formalizados en `docs/02-requerimientos-del-sistema.md`. 

El propósito central es documentar de forma clara, didáctica y formal el proceso de descomposición y estructuración de la información del sistema bibliotecario, partiendo de los datos operativos reales que maneja la biblioteca universitaria hasta alcanzar un esquema relacional depurado en **Tercera Forma Normal (3FN)** compuesto por **8 tablas** (cumpliendo con el requerimiento de 7 a 9 tablas), garantizando la integridad de datos y eliminando redundancias y anomalías de actualización, inserción y borrado.

---

## 2. Alcance

**In:**

- Creación del documento formal en `docs/03-diseno-y-normalizacion-bd.md`.
- **Enfoque metodológico:** Exposición estructurada mediante un análisis natural y progresivo de las necesidades de datos, orientado a resolver problemas prácticos de duplicidad e inconsistencia.
- **Punto de partida (Forma No Normalizada - 0FN):**
  - Modelado de dos estructuras u hojas operativas base:
    1. *Ficha de Catálogo Bibliográfico desnormalizada* (título, ISBN, autor, nacionalidad, categoría, ejemplares físicos, ubicación).
    2. *Ficha de Registro de Circulación y Préstamos desnormalizada* (usuario, tipo de usuario, datos de contacto, ejemplar prestado, libro, fechas, adeudos y sanciones).
- **Desarrollo paso a paso de las Formas Normales:**
  - **Primera Forma Normal (1FN):**
    - Identificación de atributos compuestos y multivaluados (múltiples copias/ejemplares en un solo registro, datos combinados).
    - Desglose en valores atómicos individuales.
    - Definición de claves primarias (PK) candidatas preliminares.
  - **Segunda Forma Normal (2FN):**
    - Análisis de dependencias funcionales sobre claves compuestas.
    - Detección y eliminación de dependencias funcionales parciales (atributos que dependen solo de una parte de la PK).
    - Creación de entidades intermedias para desacoplar catálogo de copias físicas y préstamos de usuarios.
  - **Tercera Forma Normal (3FN):**
    - Detección y eliminación de dependencias transitivas (atributos no clave que dependen funcionalmente de otros atributos no clave).
    - Desacoplamiento de autores, categorías temáticas, roles/políticas de usuario y sanciones económicas independientes.
- **Definición de la arquitectura relacional final (8 tablas normalizadas):**
  1. `tipos_usuario`: Clasificación de usuarios (Alumno, Docente) y límites de préstamo.
  2. `usuarios`: Padrón institucional de prestatarios.
  3. `autores`: Registro depurado de autores de obras.
  4. `categorias`: Clasificación temática/género de los libros.
  5. `libros`: Catálogo bibliográfico central (obra intelectual).
  6. `ejemplares`: Copias físicas inventariables con estado operativo individual.
  7. `prestamos`: Registro transaccional de circulación (salidas y retornos).
  8. `multas`: Registro y seguimiento transaccional independiente de sanciones financieras por mora.
- **Demostración de resolución de anomalías:**
  - Explicación de cómo la estructura en 3FN previene anomalías de inserción, modificación y eliminación en el catálogo y en la circulación.
- **Esquema relacional consolidado:**
  - Expresión formal del esquema resultante indicando tablas, claves primarias (`[PK]`) y claves foráneas (`[FK]`).

**Out of scope (delimitación estricta con entregables posteriores):**

- Elaboración de diagramas gráficos Entidad-Relación (DER) en notación Chen, Crow's Foot o herramientas visuales (corresponde a SPEC 04 / Entregable 4).
- Diccionario de datos exhaustivo con tipos de datos exactos SQL, longitudes, restricciones de dominio `CHECK`, `NULL/NOT NULL` y scripts DDL (`CREATE TABLE`) de base de datos (corresponde a SPEC 05 / Entregable 5).
- Redacción y optimización de las 5 consultas relacionales complejas con `JOIN` (corresponde a SPEC 05 / Entregable 5 y código aplicativo).
- Código backend PHP o frontend de la aplicación web.

---

## 3. Modelo de datos

El entregable documenta el proceso analítico que concluye en el siguiente esquema relacional de 8 tablas en 3FN:

```text
1. TIPOS_USUARIO (id_tipo [PK], nombre_tipo, limite_libros, dias_prestamo)
2. USUARIOS      (id_usuario [PK], matricula_num_emp, nombre_completo, correo, telefono, estado, id_tipo [FK])
3. AUTORES       (id_autor [PK], nombre_autor, nacionalidad)
4. CATEGORIAS    (id_categoria [PK], nombre_categoria, descripcion)
5. LIBROS        (id_libro [PK], titulo, isbn, anio_publicacion, editorial, id_autor [FK], id_categoria [FK])
6. EJEMPLARES    (id_ejemplar [PK], id_libro [FK], codigo_inventario, ubicacion_estante, estado_conservacion, estado_disponibilidad)
7. PRESTAMOS     (id_prestamo [PK], id_usuario [FK], id_ejemplar [FK], fecha_salida, fecha_compromiso, fecha_devolucion_real, estado_prestamo)
8. MULTAS        (id_multa [PK], id_prestamo [FK], dias_retraso, monto_total, fecha_generacion, estado_pago)
```

---

## 4. Plan de implementación

1. **Estructura base del documento:** Crear `docs/03-diseno-y-normalizacion-bd.md` con membrete institucional, ficha técnica de metadatos, tabla de contenido y marco conceptual del proceso de normalización de bases de datos.
2. **Definición de las Fichas Operativas Desnormalizadas (0FN):**
   - Presentar la ficha no normalizada del *Catálogo Bibliográfico* con datos repetitivos y no atómicos de libros, autores y ejemplares.
   - Presentar la ficha no normalizada de *Control de Préstamos y Sanciones* con datos mezclados de usuarios, préstamos, ejemplares y adeudos.
   - Diagnosticar los problemas de redundancia y riesgo de inconsistencia operativa inherentes a la forma 0FN.
3. **Desarrollo de la Primera Forma Normal (1FN):**
   - Aplicar el principio de atomicidad de atributos (descomposición de campos multivaluados y listas de copias).
   - Eliminar grupos repetitivos y establecer claves primarias compuestas temporales para ambas fichas.
   - Mostrar el estado tabular resultante tras aplicar 1FN.
4. **Desarrollo de la Segunda Forma Normal (2FN):**
   - Analizar las dependencias funcionales respecto a las claves primarias compuestas.
   - Identificar dependencias parciales (atributos que sólo dependen de una parte de la clave compuesta).
   - Separar las entidades para que cada atributo no clave dependa de la totalidad de la clave primaria.
   - Mostrar las tablas intermedias resultantes tras aplicar 2FN.
5. **Desarrollo de la Tercera Forma Normal (3FN):**
   - Identificar dependencias funcionales transitivas (atributos no clave que dependen funcionalmente de otros atributos no clave).
   - Desacoplar `autores`, `categorias`, `tipos_usuario` y `multas` en tablas independientes con sus respectivas llaves foráneas (`FK`).
   - Demostrar que todas las tablas cumplen con 3FN (dependencia estricta de la clave, de toda la clave y de nada más que de la clave).
6. **Consolidación del Esquema Relacional de 8 Tablas:**
   - Presentar la matriz resumen del esquema de 8 tablas con sus claves primarias, foráneas y cardinalidades lógicas.
   - Incluir una sección explicativa que justifique cómo este diseño resuelve las tres anomalías clásicas de bases de datos (inserción, modificación y eliminación).
7. **Revisión de consistencia y formato:**
   - Validar que el contenido mantenga un tono profesional y técnico pero sin sobrecarga matemática abstracta, asegurando compatibilidad con el conversor `scripts/export_md_to_docx.py`.

---

## 5. Criterios de aceptación

- [x] El archivo `docs/03-diseno-y-normalizacion-bd.md` existe en el directorio `docs/`.
- [x] Incluye encabezado formal institucional y metadatos del proyecto universitario.
- [x] Presenta con claridad las 2 fichas operativas desnormalizadas de partida (0FN) basadas en el flujo real de una biblioteca.
- [x] Desarrolla el proceso paso a paso explicando la transición a 1FN (atomicidad y eliminación de grupos repetitivos).
- [x] Desarrolla la transición a 2FN demostrando la eliminación de dependencias parciales sobre claves compuestas.
- [x] Desarrolla la transición a 3FN demostrando la eliminación de dependencias transitivas entre atributos no clave.
- [x] Deriva con exactitud el conjunto de **8 tablas normalizadas**: `tipos_usuario`, `usuarios`, `autores`, `categorias`, `libros`, `ejemplares`, `prestamos` y `multas`.
- [x] Mantiene un enfoque analítico natural y justificado, evitando notaciones matemáticas pesadas o abstractas.
- [x] Demuestra cómo el esquema resultante erradica las anomalías de inserción, actualización y borrado.
- [x] Presenta el esquema relacional formal identificando claramente `[PK]` y `[FK]`.
- [x] Respeta la delimitación con los entregables posteriores: no incluye diagramas visuales DER (Entregable 4) ni el diccionario de datos SQL exhaustivo con DDL (Entregable 5).

---

## 6. Decisiones

- **Sí:** Redactar en `docs/03-diseno-y-normalizacion-bd.md` para mantener la convención numérica establecida en los entregables anteriores (`01-planteamiento-del-problema.md`, `02-requerimientos-del-sistema.md`).
- **Sí:** Partir de 2 fichas operativas desnormalizadas (Catálogo y Circulación) en lugar de una tabla universal monstruosa. Representa fielmente cómo surgen los datos en la realidad física de una biblioteca antes de su sistematización.
- **Sí:** Diseñar exactamente 8 tablas en 3FN con `multas` como entidad independiente. Satisface el requisito mandatorio de 7 a 9 tablas de `ABOUT.md` y asegura una auditoría limpia de sanciones por morosidad desacoplada del ciclo de préstamo.
- **Sí:** Enfoque analítico natural en lugar de formalismo matemático denso. Permite una lectura ejecutiva y didáctica centrada en la resolución de anomalías reales de negocio.
- **No:** Incluir diagramas visuales DER (Chen o patas de gallo) en este documento. Se reservan estrictamente para el Entregable 4 (`docs/04-diagrama-entidad-relacion.md`).
- **No:** Incluir diccionario de datos exhaustivo ni scripts DDL (`CREATE TABLE`). Se reservan estrictamente para el Entregable 5 (`docs/05-diccionario-de-datos.md`).

---

## 7. Qué NO está en esta especificación

- Diagramas Entidad-Relación gráficos o visuales (Entregable 4).
- Especificación física de tipos de datos de motor SQL, tamaños en bytes, índices y scripts DDL (Entregable 5).
- Formulación y ejecución de las 5 consultas complejas relacionales con `JOIN` (Entregable 5 / Aplicativo).
- Implementación de controladores, modelos o vistas PHP de la aplicación web.
