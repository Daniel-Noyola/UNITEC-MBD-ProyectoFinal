# SPEC 04 — Diagrama Entidad-Relación (DER)

> **Status:** Approved  
> **Depends on:** SPEC 03  
> **Date:** 2026-11-11  
> **Objective:** Documentar y representar formalmente el Diagrama Entidad-Relación (DER) en sus niveles conceptual y lógico para las 8 tablas de la base de datos de gestión bibliotecaria, simplificando las claves primarias a `id`, estandarizando las claves foráneas a `[entidad]_id` y exportando los diagramas Mermaid a imágenes PNG de alta resolución integrables en el documento Word institucional.

---

## 1. Contexto

Este documento define las especificaciones técnicas para el desarrollo del **Entregable 4: Diagrama Entidad-Relación (DER)** establecido en `ABOUT.md`. Toma como fundamento el esquema en Tercera Forma Normal (3FN) derivado en `docs/03-diseno-y-normalizacion-bd.md` (SPEC 03).

El objetivo es proporcionar la modelación gráfica y conceptual completa de la base de datos, estructurada en dos niveles formales de abstracción:
1. **Nivel Conceptual:** Enfoque semántico de alto nivel que ilustra las entidades del dominio bibliotecario, sus asociaciones directas mediante verbos de negocio y las cardinalidades fundamentales (1:1, 1:N).
2. **Nivel Lógico (Relacional):** Enfoque detallado bajo notación Crow's Foot (Pata de Gallo) que describe exhaustivamente las 8 entidades normalizadas con la totalidad de sus atributos, tipos de datos abstractos, participación obligatoria/opcional, claves primarias simplificadas a `id` y claves foráneas bajo la convención moderna `[entidad]_id`.

---

## 2. Alcance

**In:**

- Creación del documento técnico formal en `docs/04-diagrama-entidad-relacion.md`.
- **Marco teórico del modelado Entidad-Relación:**
  - Definición y distinción de niveles de abstracción (Conceptual vs. Lógico).
  - Explicación de la notación gráfica Crow's Foot (uno a uno, uno a muchos, opcionalidad y obligatoriedad).
- **Diagrama Entidad-Relación a Nivel Conceptual:**
  - Representación visual con sintaxis nativa Mermaid.
  - Relaciones semánticas y verbos de negocio entre entidades (`clasifica`, `escribe`, `agrupa`, `contiene`, `solicita`, `sanciona`).
  - Cardinalidad simplificada de alto nivel (1:N, 1:1, 1:0..1).
- **Diagrama Entidad-Relación a Nivel Lógico (Relacional Crow's Foot):**
  - Representación exhaustiva con sintaxis nativa Mermaid `erDiagram`.
  - Definición explícita de las 8 tablas con todos sus atributos y dominios de datos.
  - Estandarización de llaves primarias: `id [PK]` para todas las entidades.
  - Estandarización de llaves foráneas: `[entidad]_id [FK]` (ej. `usuario_id`, `ejemplar_id`, `libro_id`, `autor_id`, `categoria_id`, `tipo_id`, `prestamo_id`).
  - Símbolos precisos de cardinalidad (cero o muchos `o{`, uno o muchos `|{`, exactamente uno `||`, cero o uno `o|`).
- **Matriz y Diccionario de Relaciones y Cardinalidades:**
  - Tabla detallada con entidad origen, cardinalidad mínima/máxima, entidad destino, clave foránea y descripción funcional.
  - Justificación de la cardinalidad 1 a (0..1) entre `PRESTAMOS` y `MULTAS` (sanción única por morosidad acumulada).
  - Justificación de la relación 1 a N entre `LIBROS` y `EJEMPLARES` (desacoplamiento de título intelectual vs. soporte físico).
  - Justificación de la relación de circulación entre `USUARIOS`, `EJEMPLARES` y `PRESTAMOS`.
- **Reglas de Integridad Referencial asociadas:**
  - Restricciones de borrado (`ON DELETE RESTRICT` / `ON DELETE CASCADE` justificadas).
  - Restricciones de actualización (`ON UPDATE CASCADE`).
- **Exportación e Integración de Diagramas a PNG y Word:**
  - Exportación automatizada de los bloques Mermaid a archivos de imagen PNG de alta resolución (`docs/word/assets/der_conceptual.png` y `docs/word/assets/der_logico.png`).
  - Adaptación en `scripts/export_md_to_docx.py` para incrustar de forma centrada y dimensionada las imágenes PNG generadas dentro del documento Word compilado (`docs/word/04-diagrama-entidad-relacion.docx`).

**Out of scope (delimitación con otros entregables):**

- Redacción exhaustiva del Diccionario de Datos con sentencias DDL completas (`CREATE TABLE`, scripts `.sql`) y tipos de almacenamiento específicos de motor (corresponde a SPEC 05 / Entregable 5).
- Elaboración y ejecución de las 5 consultas complejas relacionales con `JOIN` (corresponde a SPEC 05 y desarrollo del backend).
- Implementación de interfaz web o controladores en PHP.

---

## 3. Modelo de datos

El modelo lógico relacional adopta la simplificación solicitada de claves primarias a `id` y estandariza las foráneas con el sufijo `_id`. Las 8 entidades quedan conformadas de la siguiente manera:

```text
1. TIPOS_USUARIO (
     id [PK], 
     nombre_tipo, 
     limite_libros, 
     dias_prestamo
   )

2. USUARIOS (
     id [PK], 
     tipo_id [FK], 
     matricula_num_emp, 
     nombre_completo, 
     correo, 
     telefono, 
     estado
   )

3. AUTORES (
     id [PK], 
     nombre_autor, 
     nacionalidad
   )

4. CATEGORIAS (
     id [PK], 
     nombre_categoria, 
     descripcion
   )

5. LIBROS (
     id [PK], 
     autor_id [FK], 
     categoria_id [FK], 
     titulo, 
     isbn, 
     anio_publicacion, 
     editorial
   )

6. EJEMPLARES (
     id [PK], 
     libro_id [FK], 
     codigo_inventario, 
     ubicacion_estante, 
     estado_conservacion, 
     estado_disponibilidad
   )

7. PRESTAMOS (
     id [PK], 
     usuario_id [FK], 
     ejemplar_id [FK], 
     fecha_salida, 
     fecha_compromiso, 
     fecha_devolucion_real, 
     estado_prestamo
   )

8. MULTAS (
     id [PK], 
     prestamo_id [FK], 
     dias_retraso, 
     monto_total, 
     fecha_generacion, 
     estado_pago
   )
```

### Relaciones y Cardinalidades:

| Entidad Origen | Cardinalidad Origen | Relación (Verbo) | Cardinalidad Destino | Entidad Destino | Clave Foránea (`FK`) | Justificación |
|---|---|---|---|---|---|---|
| `TIPOS_USUARIO` | `1` (Obligatorio) | Clasifica | `0..N` (Opcional) | `USUARIOS` | `tipo_id` | Un tipo de usuario puede tener registrados cero o muchos usuarios; todo usuario debe poseer exactamente un rol asignado. |
| `AUTORES` | `1` (Obligatorio) | Escribe | `0..N` (Opcional) | `LIBROS` | `autor_id` | Un autor registrado puede tener cero o muchas obras en catálogo; cada libro se atribuye a un autor principal en el MVP. |
| `CATEGORIAS` | `1` (Obligatorio) | Clasifica | `0..N` (Opcional) | `LIBROS` | `categoria_id` | Una categoría temática agrupa cero o muchos libros; cada libro pertenece a una única categoría. |
| `LIBROS` | `1` (Obligatorio) | Posee copias | `1..N` (Obligatorio) | `EJEMPLARES` | `libro_id` | Un libro en catálogo debe contar con al menos un ejemplar físico tangible para existir operativamente en la biblioteca. |
| `USUARIOS` | `1` (Obligatorio) | Solicita | `0..N` (Opcional) | `PRESTAMOS` | `usuario_id` | Un usuario puede tener historial de cero o muchos préstamos; un préstamo transaccional corresponde a un único usuario. |
| `EJEMPLARES` | `1` (Obligatorio) | Circula en | `0..N` (Opcional) | `PRESTAMOS` | `ejemplar_id` | Un ejemplar individual puede prestarse a lo largo del tiempo cero o múltiples veces; cada registro de préstamo ampara un ejemplar físico. |
| `PRESTAMOS` | `1` (Obligatorio) | Genera | `0..1` (Opcional) | `MULTAS` | `prestamo_id` | Un préstamo solo genera sanción si incurre en mora; de haber retraso, se genera a lo sumo una multa total consolidada. |

---

## 4. Plan de implementación

1. **Estructura base del documento:** Crear `docs/04-diagrama-entidad-relacion.md` con membrete institucional universitario, datos de la materia, autoría, índice general y ficha descriptiva del entregable.
2. **Fundamentación teórica y metodológica del DER:**
   - Exponer los principios del modelado conceptual y lógico según Peter Chen y la evolución hacia la notación de Ingeniería de la Información (Crow's Foot).
   - Detallar la simbología gráfica de cardinalidad y restricciones de participación (total/parcial).
3. **Desarrollo del Diagrama Entidad-Relación Conceptual:**
   - Elaborar bloque Mermaid con las 8 entidades y sus relaciones semánticas primordiales.
   - Acompañar con descripción narrativa de las interacciones entre los subsistemas de Catálogo, Usuarios y Circulación.
4. **Desarrollo del Diagrama Entidad-Relación Lógico (Crow's Foot):**
   - Construir el bloque Mermaid `erDiagram` detallado con las 8 tablas, incluyendo:
     - PK simplificada a `id` para todas las entidades.
     - FKs estructuradas con formato `[entidad]_id`.
     - Atributos completos con su tipo de dato conceptual (`int`, `varchar`, `date`, `decimal`, `enum`).
     - Relaciones directas con conectores de cardinalidad exacta (`||--o{`, `||--|{`, `||--o|`).
5. **Matriz de Cardinalidades y Reglas de Negocio:**
   - Desarrollar la tabla comparativa exhaustiva de relaciones, roles, llaves de enlace y cardinalidades mínimas/máximas.
   - Redactar el análisis de decisiones estructurales (cardinalidad 1 a 0..1 en multas, 1 a N en copias físicas, etc.).
6. **Políticas de Integridad Referencial y Restricciones:**
   - Definir formalmente los comportamientos ante eventos de borrado y modificación en cascada o restricción (`ON DELETE / ON UPDATE`) para proteger la consistencia de los datos.
7. **Exportación de Diagramas Mermaid a PNG:**
   - Implementar un mecanismo/script automatizado en Python que procese los bloques Mermaid y genere las imágenes PNG en alta definición en `docs/word/assets/der_conceptual.png` y `docs/word/assets/der_logico.png`.
8. **Integración en compilador DOCX:**
   - Actualizar `scripts/export_md_to_docx.py` para detectar las imágenes del DER e insertarlas centradas, con ancho ajustado a los márgenes y borde sutil, acompañadas de su respectivo título/epígrafe en `docs/word/04-diagrama-entidad-relacion.docx`.
9. **Revisión, validación cruzada y compatibilidad:**
   - Contrastar la coherencia absoluta entre `docs/03-diseno-y-normalizacion-bd.md` y este nuevo DER, asegurando que no existan discrepancias estructurales ni entidades huérfanas.
   - Ejecutar la exportación completa a Word y verificar la correcta visualización de las imágenes incrustadas.

---

## 5. Criterios de aceptación

- [x] El archivo `docs/04-diagrama-entidad-relacion.md` existe en la carpeta `docs/`.
- [x] Incluye encabezado formal institucional y metadatos de entrega académica (Materia, Proyecto, Alumno/Equipo, Fecha).
- [x] Incorpora el marco conceptual y teórico que fundamenta el modelado DER y la notación Crow's Foot.
- [x] Contiene el Diagrama Entidad-Relación a Nivel Conceptual en bloque Mermaid con entidades y relaciones semánticas claras.
- [x] Contiene el Diagrama Entidad-Relación a Nivel Lógico completo en bloque Mermaid `erDiagram` con notación Crow's Foot.
- [x] Las 8 tablas normalizadas (`tipos_usuario`, `usuarios`, `autores`, `categorias`, `libros`, `ejemplares`, `prestamos`, `multas`) están presentes en el diagrama lógico.
- [x] Todas las claves primarias están simplificadas estrictamente al nombre `id` (`id [PK]`).
- [x] Todas las claves foráneas siguen la convención estandarizada `[entidad]_id` (`tipo_id`, `autor_id`, `categoria_id`, `libro_id`, `usuario_id`, `ejemplar_id`, `prestamo_id`).
- [x] La relación entre `PRESTAMOS` y `MULTAS` refleja la cardinalidad de uno a cero o uno (`1` a `0..1`).
- [x] La relación entre `LIBROS` y `EJEMPLARES` refleja la cardinalidad de uno a uno o muchos (`1` a `1..N`).
- [x] Incluye la tabla de relaciones y cardinalidades con descripción y justificación de reglas de negocio.
- [x] Incluye la especificación de reglas de integridad referencial (`ON DELETE RESTRICT`, `ON UPDATE CASCADE`).
- [x] Los diagramas Mermaid se exportan exitosamente a PNG en `docs/word/assets/der_conceptual.png` y `docs/word/assets/der_logico.png`.
- [x] El script `scripts/export_md_to_docx.py` compila `docs/word/04-diagrama-entidad-relacion.docx` incrustando de forma nítida y centrada las imágenes PNG generadas.
- [x] Mantiene total coherencia con el diseño en 3FN documentado en el Entregable 3 (`docs/03-diseno-y-normalizacion-bd.md`).

---

## 6. Decisiones tomadas y descartadas

- **Decisión:** Simplificar el nombre de todas las claves primarias a `id` en lugar de prefijadas (`id_usuario`, `id_libro`).
  - *Justificación:* Solicitud expresa del usuario y estándar de la industria (Rails, Laravel, Django, buenas prácticas REST). En el contexto de cada tabla o entidad, el identificador `id` es unívoco y evita redundancias léxicas como `libros.id_libro`.
- **Decisión:** Estandarizar las claves foráneas como `[entidad]_id` (ej. `libro_id`, `usuario_id`).
  - *Justificación:* Al simplificar la PK a `id`, la convención natural y más legible para las llaves foráneas es `nombre_entidad_id`, facilitando la lectura directa del origen referencial en sentencias `JOIN`.
- **Decisión:** Incluir ambos niveles de modelado (Conceptual y Lógico).
  - *Justificación:* Proporciona una visión integral: el nivel conceptual ofrece un panorama rápido para stakeholders y evaluación pedagógica del dominio, mientras que el nivel lógico proporciona la base rigurosa necesaria para los desarrolladores y la posterior creación de scripts DDL.
- **Decisión:** Modelar la relación `PRESTAMOS` - `MULTAS` como 1 a (0..1) opcional.
  - *Justificación:* Cada registro de préstamo vencido calcula y consolida una única multa económica por los días totales de retraso acumulados, evitando dispersión y duplicidad transaccional.
- **Decisión:** Exportar los diagramas Mermaid a imágenes PNG de alta resolución para su integración en Word.
  - *Justificación:* Asegura que el reporte universitario en formato `.docx` contenga diagramas gráficos perfectamente legibles, diagramados y formateados, sin depender del soporte de bloques de código Markdown o visores externos.
- **Decisión:** Depurar y compactar la estructura del documento final eliminando secciones introductorias teóricas redundantes (1.1, 1.2), listas repetitivas de atributos en prosa (4.1), prosa duplicada de reglas de negocio (5.2) y secciones de cierre (7 y 8).
  - *Justificación:* Solicitud expresa del usuario para otorgar máxima agilidad, concisión y foco operativo al entregable, priorizando los diagramas visuales (conceptual y lógico Crow's Foot), la simplificación de claves, la matriz de cardinalidad y las políticas de integridad referencial.
- **Descartada:** Usar nombres genéricos para las relaciones en el nivel conceptual (como "se relaciona con").
  - *Motivo:* Se prefirieron verbos semánticos activos (`escribe`, `clasifica`, `posee copias`, `solicita`, `sanciona`) que aportan valor descriptivo real al modelo de negocio.

---

## 7. Riesgos identificados

- **Riesgo:** Confusión al referenciar `id` en consultas SQL complejas con múltiples `JOIN` si no se utilizan alias de tabla claros.
  - *Mitigación:* En la documentación técnica y en los ejemplos de consultas SQL posteriores se enfatizará el uso de alias calificados explícitos (ej. `l.id AS libro_id`, `u.id AS usuario_id`).
- **Riesgo:** Incompatibilidad de renderizado de bloques Mermaid complejos en versiones antiguas de visores markdown o en la conversión directa a DOCX.
  - *Mitigación:* Se estructuran las tablas analíticas y explicativas en formato Markdown estándar para que toda la información semántica, atributos y cardinalidades estén plenamente legibles en texto además del bloque gráfico.
