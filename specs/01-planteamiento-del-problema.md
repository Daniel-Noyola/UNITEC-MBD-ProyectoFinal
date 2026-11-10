# SPEC 01 — Planteamiento del Problema

> **Status:** Implemented
> **Depends on:** None
> **Date:** 2026-11-09
> **Objective:** Redactar el documento formal del Entregable 1 con la problemática actual, propuesta de solución y objetivos para el sistema de gestión bibliotecaria universitaria.

---

## 1. Contexto

Este entregable establece los cimientos del proyecto final de la materia **Modelos de Bases de Datos**. Define el diagnóstico situacional, la justificación operativa y los objetivos que delimitan el alcance del Sistema de Gestión Bibliotecaria para un entorno universitario (alumnos, docentes, préstamos y sanciones).

---

## 2. Alcance

**In:**

- Redacción del documento formal en `docs/01-planteamiento-del-problema.md`.
- Sección de **Antecedentes y Justificación**: contexto operativo de una biblioteca universitaria y motivos para digitalizar el control de acervos.
- Sección de **Descripción de la Problemática Actual**: análisis de deficiencias por registros manuales o dispersos (duplicidad, pérdida de ejemplares, falta de trazabilidad en préstamos y cobro inexacto de multas).
- Sección de **Propuesta de Solución**: justificación del enfoque centrado en una base de datos relacional normalizada y un aplicativo web administrativo.
- Sección de **Objetivos del Proyecto**:
  - Objetivo General (redactado bajo enfoque SMART).
  - Objetivos Específicos (delimitados a modelado conceptual, normalización formal, catálogo bibliográfico, control de usuarios y registro de sanciones).
- Formato estructurado en Markdown con tipografía técnica y tablas de apoyo, optimizado para conversión limpia a PDF/Word.

**Out of scope (para entregables posteriores):**

- Lista y clasificación de Requerimientos Funcionales y No Funcionales (corresponde a SPEC 02 / Entregable 2).
- Proceso de normalización formal 1FN a 3FN (corresponde a SPEC 03 / Entregable 3).
- Diagrama Entidad-Relación y cardinalidades (corresponde a SPEC 04 / Entregable 4).
- Diccionario de datos y scripts DDL SQL (corresponde a SPEC 05 / Entregable 5).
- Código backend PHP o interfaz web funcional (fases posteriores de desarrollo).

---

## 3. Modelo de datos

Este entregable no introduce estructuras de datos, esquemas de tablas ni código ejecutable. Su naturaleza es puramente documental y analítica.

---

## 4. Plan de implementación

1. **Estructura base del documento:** Crear el directorio `docs/` y el archivo `docs/01-planteamiento-del-problema.md` con encabezados institucionales, metadatos y tabla de contenido.
2. **Desarrollo de Antecedentes y Justificación:** Redactar el contexto académico institucional, el rol del acervo bibliográfico y el impacto del descontrol operativo en los usuarios universitarios.
3. **Redacción de la Problemática Actual:** Detallar los puntos de dolor operativos clasificados por área (inventario, préstamos/devoluciones, sanciones económicas y reportería).
4. **Formulación de la Propuesta de Solución:** Describir la arquitectura conceptual de la solución basada en un SGBD relacional que garantice consistencia transaccional y reglas de negocio íntegras.
5. **Definición de Objetivos (General y Específicos):** Redactar el objetivo general y al menos 4 objetivos específicos cuantificables orientados a la arquitectura de datos.
6. **Revisión de consistencia y formato:** Verificar redacción ortográfica, tono formal académico, validez de enlaces relativos y legibilidad para exportación a PDF.

---

## 5. Criterios de aceptación

- [x] El archivo `docs/01-planteamiento-del-problema.md` existe en la raíz del repositorio.
- [x] Contiene los metadatos institucionales requeridos (Materia, Proyecto, Entregable 1).
- [x] Incluye la sección de **Antecedentes y Justificación** adaptada al entorno universitario.
- [x] Describe explícitamente los puntos de dolor de la problemática actual (control de inventario, préstamos, moras).
- [x] Plantea la **Propuesta de Solución** con énfasis en la integridad referencial y normalización relacional.
- [x] El **Objetivo General** está formulado en una sola oración con verbo en infinitivo, alcance y propósito claro.
- [x] Contiene al menos 4 **Objetivos Específicos** que desglosan las etapas de diseño de la base de datos y validación funcional.
- [x] No incluye tablas de base de datos preliminares ni diagramas DER (respetando la delimitación con los entregables 2 al 5).

---

## 6. Decisiones

- **Sí:** Redactar el entregable en `docs/01-planteamiento-del-problema.md`. Facilita el versionado en Git y su posterior conversión a formato institucional.
- **Sí:** Contexto universitario/académico. Permite justificar atributos reales como matrículas estudiantiles, números de empleado docente y reglas de préstamo diferenciadas.
- **No:** Incluir requerimientos funcionales o entidades preliminares. Se descartan de este entregable para cumplir con la delimitación estricta de la rúbrica y no duplicar contenido con el Entregable 2.
- **No:** Crear scripts de base de datos o plantillas PHP en esta etapa. El rigor de la materia exige completar el marco conceptual antes de codificar.

---

## 7. Qué NO está en esta especificación

- Requerimientos funcionales del sistema.
- Diagramas DER conceptuales o lógicos.
- Scripts de creación de esquemas SQL.
- Implementación de vistas o controladores PHP.
