# Entregable 2: Requerimientos del Sistema y Matriz de Historias de Usuario

> **Materia:** Modelos de Bases de Datos  
> **Proyecto:** Sistema de Gestión Bibliotecaria  
> **Entregable:** 2 — Requerimientos Funcionales, Reglas de Negocio e Historias de Usuario  
> **Fecha:** Noviembre 2026  

---

## Índice

1. [Introducción y Propósito](#1-introducción-y-propósito)
2. [Catálogo de Actores del Sistema](#2-catálogo-de-actores-del-sistema)
3. [Reglas de Negocio (RN)](#3-reglas-de-negocio-rn)
4. [Especificación de Requerimientos Funcionales (IEEE 830)](#4-especificación-de-requerimientos-funcionales-ieee-830)
   - [4.1 Módulo 1: Catálogo Bibliográfico y Ejemplares](#41-módulo-1-catálogo-bibliográfico-y-ejemplares)
   - [4.2 Módulo 2: Padrón de Usuarios](#42-módulo-2-padrón-de-usuarios)
   - [4.3 Módulo 3: Préstamos y Devoluciones (Circulación)](#43-módulo-3-préstamos-y-devoluciones-circulación)
   - [4.4 Módulo 4: Sanciones y Multas](#44-módulo-4-sanciones-y-multas)
   - [4.5 Módulo 5: Reportería y Monitoreo Operativo](#45-módulo-5-reportería-y-monitoreo-operativo)
5. [Historias de Usuario (HU)](#5-historias-de-usuario-hu)
6. [Matriz de Trazabilidad (RF ↔ HU ↔ RN)](#6-matriz-de-trazabilidad-rf--hu--rn)
7. [Delimitación y Alcance del MVP](#7-delimitación-y-alcance-del-mvp)

---

## 1. Introducción y Propósito

El presente documento constituye el **Entregable 2** del proyecto final para la materia **Modelos de Bases de Datos**. Toma como punto de partida el diagnóstico institucional establecido en el [Entregable 1 (Planteamiento del Problema)](01-planteamiento-del-problema.md), donde se identificaron deficiencias operativas causadas por registros manuales, pérdida de ejemplares físicos, falta de trazabilidad en los préstamos y cobro inexacto de sanciones por mora.

El propósito central de este documento es formalizar:
1. Las **Reglas de Negocio (RN)** que rigen la operación de la biblioteca universitaria.
2. Los **Requerimientos Funcionales (RF)** estructurados bajo el estándar formal IEEE 830 simplificado.
3. El catálogo de **Historias de Usuario (HU)** redactadas con criterios de aceptación en formato BDD (*Given / When / Then*).
4. La **Matriz de Trazabilidad** que vincula cada requerimiento funcional con su respectiva historia de usuario y las reglas de negocio aplicadas.

Esta especificación actúa como contrato funcional directo para las siguientes fases del proyecto: el diseño y normalización de la base de datos (7 a 9 tablas en 3FN), la construcción del Diagrama Entidad-Relación (DER), el Diccionario de Datos y el posterior desarrollo de la aplicación web sobre WAMP.

---

## 2. Catálogo de Actores del Sistema

| Actor | Tipo | Descripción y Responsabilidades |
|---|---|---|
| **Bibliotecario / Administrador** | Humano (Operador interno) | Personal operativo de la biblioteca que gestiona el catálogo de obras, registra ejemplares físicos, da de alta a usuarios, efectúa los registros de préstamo y devolución, y tramita la liquidación de sanciones económicas. |
| **Alumno** | Humano (Beneficiario final) | Estudiante universitario matriculado que solicita libros en préstamo, sujeto a plazos de devolución y límites estrictos de ejemplares simultáneos. |
| **Docente** | Humano (Beneficiario final) | Profesor universitario activo identificado por número de empleado que requiere material bibliográfico con políticas de préstamo extendidas para apoyo académico y de investigación. |
| **Sistema Bibliotecario** | Automatizado | Proceso computacional que evalúa las fechas pactadas, calcula automáticamente días de retraso, genera los importes de multas e inhabilita de forma inmediata a usuarios con deudas o material vencido. |

---

## 3. Reglas de Negocio (RN)

Las siguientes reglas modelan las políticas operativas de la biblioteca universitaria y definen las restricciones de integridad y validación que la base de datos y la aplicación web deben hacer cumplir de forma estricta:

### RN-01: Desacoplamiento entre Título Bibliográfico y Ejemplar Físico
- La entidad `Libro` representa únicamente la obra intelectual y sus metadatos universales (Título, Autor, Editorial, Año de Publicación, Edición e ISBN).
- La entidad `Ejemplar` representa cada copia física individual existente en los estantes de la biblioteca. Cada ejemplar posee un código de inventario único (código de barras o identificador patrimonial), ubicación física (estante/pasillo) y condición física actual.
- Un libro puede existir con cero o múltiples ejemplares asociados; ningún ejemplar puede existir sin pertenecer a un título registrado.

### RN-02: Estados Válidos del Ejemplar Físico
- Cada ejemplar solo puede encontrarse en uno de los siguientes estados excluyentes:
  - **Disponible:** Ubicado en estantería y apto para ser prestado.
  - **Prestado:** En posesión activa de un usuario universitario.
  - **En Mantenimiento:** En encuadernación, revisión física o restauración.
  - **Extraviado / Baja:** Copia no localizada o dada de baja por deterioro irrecuperable.
- Únicamente los ejemplares con estatus **Disponible** pueden ser asignados a un nuevo préstamo.

### RN-03: Políticas y Límites de Préstamo por Perfil de Usuario
Las condiciones de préstamo se rigen estrictamente según el tipo de usuario:
- **Alumnos:**
  - Límite máximo: **hasta 3 libros simultáneos**.
  - Plazo de préstamo: **7 días naturales** a partir de la fecha de salida.
- **Docentes:**
  - Límite máximo: **hasta 5 libros simultáneos**.
  - Plazo de préstamo: **14 días naturales** a partir de la fecha de salida.

### RN-04: Bloqueo Automático por Mora o Adeudos Pendientes
- Un usuario queda automáticamente en estatus **Bloqueado** para solicitar nuevos préstamos si:
  1. Posee al menos un ejemplar cuya fecha límite de devolución ya venció y no ha sido entregado.
  2. Tiene una o más multas económicas registradas con estatus **Pendiente** de pago.
- El desbloqueo es automático únicamente cuando todos los libros en mora han sido devueltos y la totalidad de los saldos pendientes han sido liquidados en el sistema.

### RN-05: Cálculo Automatizado de Sanciones por Retraso
- Al devolverse un ejemplar después de la fecha compromiso pactada, el sistema calcula de forma automática los días naturales de desfase:
  $$\text{Días de Retraso} = \text{Fecha Real de Devolución} - \text{Fecha Pactada de Entrega}$$
- Se aplica una cuota fija de sanción de **$10.00 MXN por cada día natural de retraso por cada ejemplar** en mora:
  $$\text{Monto de Multa} = \text{Días de Retraso} \times \$10.00\text{ MXN}$$
- Si la devolución se realiza en o antes de la fecha pactada, el monto de sanción es $0.00 MXN y no se genera registro de multa.

### RN-06: Liquidación y Comprobación de Multas
- Toda multa generada inicia con estatus **Pendiente**.
- Solo el personal bibliotecario puede registrar el pago de una multa, cambiando su estatus a **Pagada** y registrando la fecha exacta de cobro.
- Ninguna multa registrada puede ser eliminada físicamente de la base de datos para preservar la trazabilidad contable y auditoría de ingresos.

---

## 4. Especificación de Requerimientos Funcionales (IEEE 830)

Los requerimientos funcionales describen los comportamientos y servicios fundamentales que debe proveer el sistema, organizados por módulos operativos.

### 4.1 Módulo 1: Catálogo Bibliográfico y Ejemplares

#### RF-01: Gestión de Catálogo de Libros (CRUD)
- **Módulo:** Catálogo Bibliográfico
- **Prioridad:** Alta
- **Descripción:** El sistema permitirá al bibliotecario dar de alta, consultar, actualizar y dar de baja lógica títulos bibliográficos.
- **Entradas:** Título, Autor(es), Editorial, Año de edición, Número de edición, ISBN (código de 10 o 13 dígitos numéricos) y Género/Categoría.
- **Salidas:** Registro de libro creado/actualizado, mensaje de confirmación y catálogo actualizado.
- **Reglas Asociadas:** RN-01. El ISBN debe ser único en el sistema.

#### RF-02: Control y Registro de Ejemplares Físicos
- **Módulo:** Catálogo Bibliográfico
- **Prioridad:** Alta
- **Descripción:** El sistema permitirá asociar copias físicas individuales a un libro registrado, asignando identificadores de inventario y ubicación física.
- **Entradas:** Selección de libro (ID o ISBN), Código de Inventario/Barras, Ubicación física (Estante/Sección), Estado inicial (por defecto *Disponible*) y Observaciones de estado físico.
- **Salidas:** Ejemplar dado de alta con estatus *Disponible* asociado al libro correspondiente.
- **Reglas Asociadas:** RN-01, RN-02. El código de inventario del ejemplar debe ser único a nivel global.

#### RF-03: Búsqueda y Filtro de Acervo Bibliográfico
- **Módulo:** Catálogo Bibliográfico
- **Prioridad:** Media
- **Descripción:** El sistema permitirá consultar el catálogo mediante filtros de búsqueda, mostrando el total de ejemplares existentes y su disponibilidad en tiempo real.
- **Entradas:** Criterio de búsqueda (título, autor, ISBN, categoría) o filtro por estado de ejemplar.
- **Salidas:** Lista de libros coincidentes con resumen de copias totales, copias disponibles y ubicación en estante.
- **Reglas Asociadas:** RN-01, RN-02.

---

### 4.2 Módulo 2: Padrón de Usuarios

#### RF-04: Gestión de Usuarios Universitarios (CRUD)
- **Módulo:** Padrón de Usuarios
- **Prioridad:** Alta
- **Descripción:** El sistema permitirá registrar, consultar, actualizar y desactivar usuarios de la comunidad universitaria, distinguiendo entre Alumnos y Docentes.
- **Entradas:** Nombre completo, Apellidos, Tipo de usuario (`Alumno` / `Docente`), Identificador institucional (Matrícula para alumnos, Número de empleado para docentes), Carrera o Departamento académico, Correo electrónico y Teléfono de contacto.
- **Salidas:** Usuario registrado con estatus inicial *Activo/Habilitado* y ficha de información de usuario.
- **Reglas Asociadas:** RN-03. La matrícula o número de empleado debe ser único en el sistema.

#### RF-05: Verificación de Estado y Solvencia de Usuario
- **Módulo:** Padrón de Usuarios
- **Prioridad:** Alta
- **Descripción:** El sistema permitirá consultar el historial activo del usuario: cantidad de libros en préstamo, préstamos vencidos, multas acumuladas y su estatus operativo (*Habilitado* o *Bloqueado*).
- **Entradas:** Matrícula o Número de empleado del usuario.
- **Salidas:** Ficha de solvencia que muestra préstamos vigentes, multas pendientes y dictamen de habilitación para nuevo préstamo.
- **Reglas Asociadas:** RN-03, RN-04.

---

### 4.3 Módulo 3: Préstamos y Devoluciones (Circulación)

#### RF-06: Registro de Préstamo de Ejemplares
- **Módulo:** Circulación
- **Prioridad:** Alta
- **Descripción:** El sistema permitirá formalizar el préstamo de uno o varios ejemplares físicos a un usuario, validando automáticamente sus límites y solvencia antes de autorizar la transacción.
- **Entradas:** Identificador del usuario, Código(s) de inventario del ejemplar o ejemplares solicitados.
- **Salidas:** Comprobante de préstamo con ID de transacción, fecha/hora de salida, fecha límite de devolución pactada y cambio de estatus de cada ejemplar a *Prestado*.
- **Reglas Asociadas:** RN-02, RN-03, RN-04. Si el usuario está bloqueado o supera el límite permitido, la transacción se rechaza de inmediato.

#### RF-07: Registro de Devolución de Ejemplares
- **Módulo:** Circulación
- **Prioridad:** Alta
- **Descripción:** El sistema registrará la recepción física de un ejemplar prestado, comparará la fecha real de entrega contra la fecha límite pactada y liberará el ejemplar a estatus *Disponible*.
- **Entradas:** Código de inventario del ejemplar o ID de préstamo, Estado físico del ejemplar al devolver (normal o dañado).
- **Salidas:** Fecha y hora real de devolución registrada en el préstamo, cambio del ejemplar a estatus *Disponible* (o *En Mantenimiento* si fue devuelto con daños), y cálculo inmediato de sanción si la entrega fue extemporánea.
- **Reglas Asociadas:** RN-02, RN-04, RN-05.

---

### 4.4 Módulo 4: Sanciones y Multas

#### RF-08: Determinación y Registro Automático de Multas por Mora
- **Módulo:** Sanciones y Multas
- **Prioridad:** Alta
- **Descripción:** Si una devolución se registra después de la fecha pactada, el sistema generará automáticamente un registro de multa vinculado al préstamo y colocará al usuario en estatus *Bloqueado*.
- **Entradas:** Datos del préstamo devuelto con retraso (fecha pactada vs fecha actual del sistema).
- **Salidas:** Registro de sanción con folio, días de retraso, monto total adeudado ($10.00 MXN por día), estatus *Pendiente* y bloqueo inmediato del usuario.
- **Reglas Asociadas:** RN-04, RN-05, RN-06.

#### RF-09: Cobro y Liquidación de Multas
- **Módulo:** Sanciones y Multas
- **Prioridad:** Alta
- **Descripción:** El sistema permitirá al bibliotecario registrar la liquidación de las multas pendientes de un usuario, actualizando su estatus de pago y restableciendo la habilitación del usuario si no restan otros adeudos.
- **Entradas:** Folio de multa o Matrícula/No. Empleado del usuario, Confirmación de cobro recibido.
- **Salidas:** Estatus de la multa actualizado a *Pagada*, fecha/hora de liquidación registrada y actualización del estatus del usuario a *Habilitado* (si no adeuda otros libros o multas).
- **Reglas Asociadas:** RN-04, RN-06.

---

### 4.5 Módulo 5: Reportería y Monitoreo Operativo

#### RF-10: Consultas de Monitoreo Operativo y Estadísticas Generales
- **Módulo:** Reportería
- **Prioridad:** Media
- **Descripción:** El sistema proveerá una vista de monitoreo operativo que permita al personal bibliotecario consultar indicadores clave consolidados: préstamos activos, usuarios con entregas demoradas, libros con mayor frecuencia de préstamo y recaudación total de multas.
- **Entradas:** Selección de reporte o filtro temporal (fecha inicio / fecha fin).
- **Salidas:** Tablas de resultados consolidados con información combinada de libros, préstamos, usuarios y multas.
- **Reglas Asociadas:** RN-01 a RN-06. Este requerimiento establece la base para las 5 consultas relacionales complejas con `JOIN` que se desarrollarán en el diseño de base de datos.

---

## 5. Historias de Usuario (HU)

A continuación se presentan las Historias de Usuario redactadas bajo la perspectiva de los roles involucrados, complementadas con criterios de aceptación en formato BDD (*Dado que / Cuando / Entonces*):

### HU-01: Registro de Nuevas Obras en el Catálogo
- **Como** Bibliotecario,  
- **Quiero** registrar nuevos libros con su información bibliográfica y su ISBN,  
- **Para** mantener actualizado el acervo de la biblioteca y permitir que la comunidad conozca los títulos disponibles.
- **Criterios de Aceptación:**
  - **Escenario 1 (Alta exitosa):**  
    *Dado que* el bibliotecario ingresa un ISBN que no existe en el sistema y completa todos los campos obligatorios (título, autor, editorial, año),  
    *Cuando* presiona "Guardar Libro",  
    *Entonces* el sistema almacena el libro en la base de datos y muestra un mensaje de confirmación.
  - **Escenario 2 (ISBN duplicado):**  
    *Dado que* el bibliotecario ingresa un ISBN que ya está registrado,  
    *Cuando* intenta guardar,  
    *Entonces* el sistema rechaza la operación e indica que el libro ya existe en el catálogo.

### HU-02: Alta de Ejemplares Físicos Individuales
- **Como** Bibliotecario,  
- **Quiero** dar de alta copias físicas con su código de barras y estante correspondiente vinculadas a un libro,  
- **Para** conocer con exactitud cuántos ejemplares reales existen en la biblioteca y en qué ubicación física encontrarlos.
- **Criterios de Aceptación:**
  - **Escenario 1 (Ejemplar vinculado correctamente):**  
    *Dado que* se selecciona un libro previamente registrado y se captura un código de barras único y su estante,  
    *Cuando* se confirma el alta del ejemplar,  
    *Entonces* el sistema lo registra con estado inicial "Disponible" y lo asocia al título correspondiente.
  - **Escenario 2 (Código de inventario repetido):**  
    *Dado que* se intenta ingresar un código de inventario asignado a otro ejemplar existente,  
    *Cuando* se envía el formulario,  
    *Entonces* el sistema impide la inserción y alerta sobre la duplicidad del código.

### HU-03: Búsqueda Rápida de Libros y Ejemplares
- **Como** Bibliotecario o Usuario consultante,  
- **Quiero** buscar libros por título, autor o categoría y visualizar cuántas copias hay disponibles,  
- **Para** responder de forma inmediata a los usuarios que solicitan material en mostrador.
- **Criterios de Aceptación:**
  - **Escenario 1 (Búsqueda con coincidencias):**  
    *Dado que* el operador ingresa un término de búsqueda (ej. "Cálculo"),  
    *Cuando* ejecuta la consulta,  
    *Entonces* el sistema muestra la lista de libros que coinciden, indicando cuántos ejemplares están disponibles para préstamo inmediato y en qué estantería se localizan.

### HU-04: Registro y Padrón de Usuarios Universitarios
- **Como** Bibliotecario,  
- **Quiero** dar de alta a alumnos y docentes con sus datos de contacto y su matrícula o número de empleado,  
- **Para** tener un padrón confiable y verificar sus privilegios al momento de solicitar libros.
- **Criterios de Aceptación:**
  - **Escenario 1 (Registro de alumno exitoso):**  
    *Dado que* se capturan los datos de un alumno con matrícula institucional válida y sin duplicados,  
    *Cuando* se guarda el registro,  
    *Entonces* el usuario queda registrado con perfil "Alumno" y estatus "Habilitado".
  - **Escenario 2 (Matrícula ya registrada):**  
    *Dado que* se introduce una matrícula existente en el sistema,  
    *Cuando* se intenta registrar,  
    *Entonces* el sistema notifica el error y cancela el duplicado.

### HU-05: Validación de Solvencia de Usuario
- **Como** Bibliotecario,  
- **Quiero** consultar en pantalla el estatus de un usuario ingresando su matrícula,  
- **Para** saber de inmediato si tiene libros vencidos o multas que le impidan sacar material nuevo.
- **Criterios de Aceptación:**
  - **Escenario 1 (Usuario sin adeudos):**  
    *Dado que* el alumno no tiene libros vencidos ni multas pendientes y tiene menos de 3 libros activos,  
    *Cuando* se consulta su estado,  
    *Entonces* el sistema muestra estatus "Habilitado" y el número de préstamos restantes que puede solicitar.
  - **Escenario 2 (Usuario con sanción pendiente):**  
    *Dado que* el usuario tiene una multa pendiente de pago,  
    *Cuando* se consulta su estado,  
    *Entonces* el sistema muestra estatus "Bloqueado", el saldo adeudado y el motivo del bloqueo.

### HU-06: Préstamo de Libros con Validación de Políticas
- **Como** Bibliotecario,  
- **Quiero** registrar el préstamo de ejemplares disponibles a un alumno o docente aplicando sus límites de días y cantidad,  
- **Para** asegurar que se cumplan las políticas institucionales y tener registrado quién tiene cada libro.
- **Criterios de Aceptación:**
  - **Escenario 1 (Préstamo a alumno concedido):**  
    *Dado que* un alumno habilitado con 1 préstamo activo solicita 1 ejemplar en estado "Disponible",  
    *Cuando* el bibliotecario registra el préstamo,  
    *Entonces* el sistema genera el préstamo con fecha compromiso calculada a 7 días naturales, y cambia el ejemplar a estado "Prestado".
  - **Escenario 2 (Préstamo denegado por límite excedido):**  
    *Dado que* un alumno ya cuenta con 3 libros en su haber,  
    *Cuando* se intenta prestar un 4to libro,  
    *Entonces* el sistema rechaza la operación e indica que el usuario ha alcanzado su límite máximo de 3 ejemplares.
  - **Escenario 3 (Préstamo a docente con plazo extendido):**  
    *Dado que* un docente habilitado solicita un ejemplar disponible,  
    *Cuando* se procesa el préstamo,  
    *Entonces* el sistema asigna una fecha compromiso de 14 días naturales.

### HU-07: Devolución Oportuna de Libros
- **Como** Bibliotecario,  
- **Quiero** registrar la entrega física de un libro prestado en o antes de la fecha límite,  
- **Para** reintegrarlo a estantería como disponible sin aplicar sanción alguna al usuario.
- **Criterios de Aceptación:**
  - **Escenario 1 (Entrega en tiempo y forma):**  
    *Dado que* el usuario entrega el ejemplar dentro de la fecha pactada,  
    *Cuando* el bibliotecario confirma la devolución,  
    *Entonces* el sistema actualiza la fecha real de devolución, cambia el ejemplar a "Disponible", no genera cargos económicos y actualiza la cuenta del usuario.

### HU-08: Devolución Extemporánea y Generación de Multa
- **Como** Bibliotecario,  
- **Quiero** que el sistema calcule automáticamente los días de mora y la multa cuando un libro se entrega tarde,  
- **Para** evitar cálculos erróneos manuales y registrar el importe exacto que el usuario debe cubrir.
- **Criterios de Aceptación:**
  - **Escenario 1 (Entrega con 4 días de retraso):**  
    *Dado que* un ejemplar tenía fecha límite de entrega el 01 de noviembre y se devuelve el 05 de noviembre (4 días naturales de retraso),  
    *Cuando* el bibliotecario procesa la devolución,  
    *Entonces* el sistema registra la entrega, genera automáticamente una multa de $40.00 MXN ($10.00 x 4) con estatus "Pendiente", cambia el ejemplar a "Disponible" y bloquea al usuario para nuevos préstamos.

### HU-09: Cobro y Liquidación de Sanciones
- **Como** Bibliotecario,  
- **Quiero** registrar el cobro de una multa pendiente cuando el usuario presenta su pago en mostrador,  
- **Para** saldar su deuda económica y rehabilitar su derecho a préstamo si ya no tiene pendientes.
- **Criterios de Aceptación:**
  - **Escenario 1 (Pago exitoso y desbloqueo):**  
    *Dado que* un alumno bloqueado con una multa pendiente de $40.00 MXN realiza el pago en caja,  
    *Cuando* el bibliotecario registra el cobro,  
    *Entonces* la multa pasa a estatus "Pagada" con la fecha actual, y al no existir más adeudos ni libros vencidos, el sistema cambia el usuario a estatus "Habilitado".

### HU-10: Monitoreo de Préstamos Activos y Morosidad
- **Como** Bibliotecario o Coordinador de Biblioteca,  
- **Quiero** visualizar en un panel los préstamos vigentes, las devoluciones vencidas y los usuarios morosos,  
- **Para** tener un control integral del acervo y gestionar oportunamente la recuperación de libros.
- **Criterios de Aceptación:**
  - **Escenario 1 (Consulta de préstamos vencidos):**  
    *Dado que* existen usuarios con libros cuya fecha límite ya transcurrió,  
    *Cuando* el bibliotecario consulta la vista de préstamos vencidos,  
    *Entonces* el sistema lista el nombre del usuario, título del libro, código de ejemplar, fecha pactada y días de retraso acumulados.

---

## 6. Matriz de Trazabilidad (RF ↔ HU ↔ RN)

La siguiente matriz evidencia la consistencia integral del sistema, demostrando cómo cada Requerimiento Funcional (IEEE 830) se encuentra respaldado por una Historia de Usuario orientada al usuario y alineado con las Reglas de Negocio institucionales:

| ID RF | Nombre del Requerimiento Funcional | ID HU | Título de Historia de Usuario | Reglas de Negocio (RN) | Prioridad |
|:---:|---|:---:|---|:---:|:---:|
| **RF-01** | Gestión de Catálogo de Libros (CRUD) | **HU-01** | Registro de Nuevas Obras en el Catálogo | RN-01 | Alta |
| **RF-02** | Control y Registro de Ejemplares Físicos | **HU-02** | Alta de Ejemplares Físicos Individuales | RN-01, RN-02 | Alta |
| **RF-03** | Búsqueda y Filtro de Acervo Bibliográfico | **HU-03** | Búsqueda Rápida de Libros y Ejemplares | RN-01, RN-02 | Media |
| **RF-04** | Gestión de Usuarios Universitarios (CRUD) | **HU-04** | Registro y Padrón de Usuarios Universitarios | RN-03 | Alta |
| **RF-05** | Verificación de Estado y Solvencia | **HU-05** | Validación de Solvencia de Usuario | RN-03, RN-04 | Alta |
| **RF-06** | Registro de Préstamo de Ejemplares | **HU-06** | Préstamo de Libros con Validación de Políticas | RN-02, RN-03, RN-04 | Alta |
| **RF-07** | Registro de Devolución de Ejemplares | **HU-07** | Devolución Oportuna de Libros | RN-02, RN-04, RN-05 | Alta |
| **RF-08** | Determinación y Registro Automático de Multas | **HU-08** | Devolución Extemporánea y Generación de Multa | RN-04, RN-05, RN-06 | Alta |
| **RF-09** | Cobro y Liquidación de Multas | **HU-09** | Cobro y Liquidación de Sanciones | RN-04, RN-06 | Alta |
| **RF-10** | Consultas de Monitoreo y Reportería Operativa | **HU-10** | Monitoreo de Préstamos Activos y Morosidad | RN-01 a RN-06 | Media |

---

## 7. Delimitación y Alcance del MVP

Para salvaguardar la viabilidad técnica y asegurar el rigor del diseño conceptual y relacional de la base de datos exigido en la materia, el alcance del Producto Mínimo Viable (MVP) queda formalmente delimitado:

### Lo que incluye esta especificación:
1. Catálogo completo de libros con gestión de múltiples ejemplares físicos independientes por obra.
2. Padrón unificado de usuarios clasificados en Alumnos y Docentes con atributos diferenciados de control.
3. Control transaccional de préstamos y devoluciones con validación automática de topes, plazos y bloqueos.
4. Cálculo automático de sanciones económicas por día natural de mora ($10.00 MXN/día) y administración de cobros.
5. Base funcional para la generación de reportes operativos que posteriormente alimentarán las 5 consultas relacionales complejas con `JOIN`.

### Lo que explícitamente NO incluye esta especificación:
1. **Requerimientos de Infraestructura de Servidor:** Especificaciones de hardware o configuración de red (el proyecto se ejecutará sobre el entorno local WAMP estándar).
2. **Pasarelas de Pago Electrónico:** No se contempla integración con Stripe, PayPal ni cobros con tarjetas bancarias; los pagos de multas se registran de forma presencial y manual en mostrador.
3. **Módulo de Reservas / Apartado Remoto:** No se contempla reserva de ejemplares vía portal web por parte de los estudiantes en este MVP.
4. **Tecnología RFID o Sensores de Puerta:** El sistema opera mediante códigos de barra, identificadores numéricos o captura manual de códigos de inventario.
5. **Préstamos Interbibliotecarios:** La administración se limita de forma estricta a un único acervo bibliográfico universitario local.
