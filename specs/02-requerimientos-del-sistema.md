# SPEC 02 — Requerimientos del Sistema y Matriz de Historias de Usuario

> **Status:** Implemented  
> **Depends on:** SPEC 01  
> **Date:** 2026-11-09  
> **Objective:** Redactar el documento formal del Entregable 2 con las reglas de negocio, requerimientos funcionales bajo el estándar IEEE 830 simplificado, historias de usuario y su matriz de trazabilidad para el Sistema de Gestión Bibliotecaria.

---

## 1. Contexto

Este entregable corresponde al **Entregable 2: Requerimientos Funcionales** establecido en `ABOUT.md`. Toma como base directa el diagnóstico de problemáticas y objetivos definidos en `docs/01-planteamiento-del-problema.md`. Establece la base funcional detallada que posteriormente guiará el diseño relacional de la base de datos (7 a 9 tablas normalizadas en 3FN) y la implementación de las vistas y operaciones CRUD de la aplicación web sobre WAMP.

---

## 2. Alcance

**In:**

- Creación del documento formal en `docs/02-requerimientos-del-sistema.md`.
- Definición de actores del sistema (Bibliotecario / Administrador, Alumno, Docente).
- Definición formal de las **Reglas de Negocio (RN)**:
  - Políticas y límites de préstamo por tipo de usuario (cantidades máximas de ejemplares y plazos en días).
  - Condiciones de mora, cálculo de multas diarias por retraso y bloqueo automático ante adeudos.
  - Gestión de ejemplares físicos (estados válidos: Disponible, Prestado, En Mantenimiento, Extraviado).
- Catálogo de **Requerimientos Funcionales (RF)** estructurados bajo el estándar formal IEEE 830 simplificado:
  - Módulo 1: Catálogo Bibliográfico y Control de Ejemplares (CRUD de libros, registro individual de copias).
  - Módulo 2: Padrón de Usuarios (CRUD de usuarios diferenciando Alumno/Docente con matrícula/número de empleado).
  - Módulo 3: Préstamos y Devoluciones (generación de préstamos, validación de políticas y recepción de devoluciones).
  - Módulo 4: Sanciones y Multas (cálculo de adeudos, registro de liquidación de multas y desbloqueo de usuarios).
  - Módulo 5: Consultas Generales de Reportería (visión global de métricas del sistema para diseño posterior de JOINs).
- Conjunto de **Historias de Usuario (HU)** correspondientes a los requerimientos funcionales:
  - Estructura: *"Como [rol], quiero [acción] para [beneficio]"*.
  - Criterios de aceptación estructurados en formato *Dado que / Cuando / Entonces* (Gherkin/BDD).
- **Matriz de Trazabilidad**: tabla comparativa bidireccional que mapea cada Requerimiento Funcional (RF) con su Historia de Usuario (HU) correspondiente y las Reglas de Negocio (RN) que impacta.

**Out of scope (delimitación con otros entregables):**

- Especificaciones de infraestructura técnica o requerimientos no funcionales de hardware/servidor (omitido por instrucción explícita).
- Redacción detallada de las consultas relacionales complejas SQL (JOINs) y sus scripts (se delega a SPEC 03 / Entregable 3 y SPEC 05 / Entregable 5).
- Proceso formal de normalización de tablas en 1FN, 2FN y 3FN (corresponde a SPEC 03 / Entregable 3).
- Diagrama Entidad-Relación y cardinalidades (corresponde a SPEC 04 / Entregable 4).
- Creación física del esquema de base de datos DDL o desarrollo de código backend en PHP.
- Pasarelas de cobro electrónico, módulos de apartado/reserva remota o tecnología RFID (excluidos desde el alcance general del proyecto).

---

## 3. Modelo de datos

Este entregable no genera tablas físicas de base de datos ni scripts SQL; es de naturaleza analítica y descriptiva de requerimientos. No obstante, formaliza las entidades conceptuales y relaciones lógicas que el sistema debe soportar:
- `Usuario` (con tipos Alumno y Docente, datos de contacto y estado de morosidad).
- `Libro` (datos bibliográficos universales: título, autor, editorial, año, ISBN).
- `Ejemplar` (instancia física del libro, código de inventario y estado).
- `Prestamo` (asociación de usuario, ejemplar, fechas de salida, compromiso y devolución real).
- `Multa` (vínculo con préstamo en mora, monto calculado, días de retraso y estado de pago).

---

## 4. Plan de implementación

1. **Estructura base del documento:** Crear `docs/02-requerimientos-del-sistema.md` con metadatos institucionales (Materia, Proyecto, Entregable 2, Fecha), índice y breve introducción que conecte con el Entregable 1.
2. **Definición de Actores y Reglas de Negocio (RN):**
   - Especificar los perfiles de usuario que interactúan con el sistema.
   - Redactar de forma concisa y numerada las reglas de negocio (RN-01 a RN-06) que rigen préstamos, plazos, montos de penalización y bloqueos.
3. **Redacción de Requerimientos Funcionales (IEEE 830 simplificado):**
   - Documentar cada requerimiento con código identificador (`RF-01`, `RF-02`, etc.), nombre descriptivo, módulo, prioridad (Alta, Media, Baja), descripción funcional, entradas requeridas, salidas generadas y reglas de negocio aplicadas.
   - Cubrir los módulos: Catálogo de Libros y Ejemplares, Gestión de Usuarios, Circulación (Préstamos y Devoluciones), Control de Multas y Consultas de Monitoreo.
4. **Desarrollo de Historias de Usuario (HU):**
   - Redactar las historias de usuario correspondientes (`HU-01`, `HU-02`, etc.) utilizando la plantilla estándar del rol, acción y propósito.
   - Definir para cada HU sus escenarios y criterios de aceptación con formato *Dado / Cuando / Entonces*.
5. **Construcción de la Matriz de Trazabilidad:**
   - Elaborar una tabla cruzada que vincule de forma transparente: `ID RF` ↔ `Nombre del Requerimiento` ↔ `ID HU` ↔ `Reglas de Negocio (RN)` ↔ `Prioridad`.
6. **Revisión de consistencia y formato:**
   - Verificar ortografía, redacción formal, cumplimiento con la delimitación del proyecto y preparación para exportación limpia a PDF/Word.

---

## 5. Criterios de aceptación

- [x] El archivo `docs/02-requerimientos-del-sistema.md` existe en el directorio `docs/`.
- [x] Contiene los metadatos institucionales y referencia al Entregable 1 (`docs/01-planteamiento-del-problema.md`).
- [x] Presenta una sección formal de **Reglas de Negocio (RN)** claramente numeradas (límites de libros, plazos, cálculo de multas y estados de mora).
- [x] Incluye la lista completa de **Requerimientos Funcionales (RF)** estructurados bajo el estándar IEEE 830 simplificado con identificador, módulo, prioridad, descripción, entradas y salidas.
- [x] Contiene las **Historias de Usuario (HU)** correspondientes con criterios de aceptación en formato *Dado / Cuando / Entonces*.
- [x] Incluye una **Matriz de Trazabilidad** que mapea inequívocamente cada RF con su respectiva HU y las RN aplicables.
- [x] Omite detalles de infraestructura no solicitados y no anticipa el código SQL ni diagramas DER (respetando los entregables posteriores).

---

## 6. Decisiones

- **Sí:** Redactar en `docs/02-requerimientos-del-sistema.md` para mantener concordancia con la nomenclatura de `docs/01-planteamiento-del-problema.md`.
- **Sí:** Utilizar el estándar IEEE 830 simplificado complementado con Historias de Usuario (HU) y matriz de trazabilidad, satisfaciendo la rigurosidad técnica académica y la claridad ágil de desarrollo.
- **Sí:** Incluir una sección explícita de Reglas de Negocio (RN) previa a los requerimientos funcionales para desacoplar las políticas de biblioteca de la interfaz de software.
- **No:** Incluir requerimientos de infraestructura técnica o no funcionales de despliegue, a petición explícita del usuario para mantener el foco en la lógica de negocio.
- **No:** Definir la sintaxis o estructura de las 5 consultas complejas con `JOIN` en este entregable; se declaran como requerimiento general de reportería operativa y se detallarán técnicamente en el diseño de base de datos (Entregables 3 y 5).

---

## 7. Qué NO está en esta especificación

- Requerimientos no funcionales de infraestructura y hardware.
- Diseño y diagramación del modelo entidad-relación (DER) (Entregable 4).
- Proceso formal de normalización 1FN a 3FN (Entregable 3).
- Diccionario de datos y scripts de creación de base de datos DDL (Entregable 5).
- Código backend en PHP o frontend en HTML/CSS/JS.
