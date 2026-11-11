# Entregable 3: Diseño y Normalización de Base de Datos

> **Materia:** Modelos de Bases de Datos  
> **Proyecto:** Sistema de Gestión Bibliotecaria  
> **Entregable:** 3 — Proceso de Análisis y Normalización de Base de Datos (1FN a 3FN)  
> **Fecha:** Noviembre 2026  

---

## Índice

1. [Introducción y Enfoque Metodológico](#1-introducción-y-enfoque-metodológico)
2. [Punto de Partida: Fichas Operativas Desnormalizadas (0FN)](#2-punto-de-partida-fichas-operativas-desnormalizadas-0fn)
   - [2.1 Ficha Operativa 1: Catálogo Bibliográfico y Ejemplares](#21-ficha-operativa-1-catálogo-bibliográfico-y-ejemplares)
   - [2.2 Ficha Operativa 2: Registro de Circulación, Préstamos y Sanciones](#22-ficha-operativa-2-registro-de-circulación-préstamos-y-sanciones)
   - [2.3 Diagnóstico Crítico de la Forma No Normalizada (0FN)](#23-diagnóstico-crítico-de-la-forma-no-normalizada-0fn)
3. [Primera Forma Normal (1FN): Atomicidad y Eliminación de Grupos Repetitivos](#3-primera-forma-normal-1fn-atomicidad-y-eliminación-de-grupos-repetitivos)
   - [3.1 Criterios Técnicos de la 1FN](#31-criterios-técnicos-de-la-1fn)
   - [3.2 Aplicación de 1FN a la Ficha de Catálogo Bibliográfico](#32-aplicación-de-1fn-a-la-ficha-de-catálogo-bibliográfico)
   - [3.3 Aplicación de 1FN a la Ficha de Circulación y Préstamos](#33-aplicación-de-1fn-a-la-ficha-de-circulación-y-préstamos)
   - [3.4 Evaluación del Estado en 1FN](#34-evaluación-del-estado-en-1fn)
4. [Segunda Forma Normal (2FN): Eliminación de Dependencias Funcionales Parciales](#4-segunda-forma-normal-2fn-eliminación-de-dependencias-funcionales-parciales)
   - [4.1 Criterios Técnicos de la 2FN](#41-criterios-técnicos-de-la-2fn)
   - [4.2 Análisis de Dependencias en Catálogo: Separación de Obras y Ejemplares Físicos](#42-análisis-de-dependencias-en-catálogo-separación-de-obras-y-ejemplares-físicos)
   - [4.3 Análisis de Dependencias en Circulación: Separación de Usuarios y Transacciones](#43-análisis-de-dependencias-en-circulación-separación-de-usuarios-y-transacciones)
   - [4.4 Evaluación del Estado en 2FN](#44-evaluación-del-estado-en-2fn)
5. [Tercera Forma Normal (3FN): Eliminación de Dependencias Transitivas](#5-tercera-forma-normal-3fn-eliminación-de-dependencias-transitivas)
   - [5.1 Criterios Técnicos de la 3FN](#51-criterios-técnicos-de-la-3fn)
   - [5.2 Desacoplamiento de Entidades Catálogo: Autores y Categorías](#52-desacoplamiento-de-entidades-catálogo-autores-y-categorías)
   - [5.3 Desacoplamiento de Políticas: Tipos de Usuario](#53-desacoplamiento-de-políticas-tipos-de-usuario)
   - [5.4 Desacoplamiento Financiero: Gestión Independiente de Multas](#54-desacoplamiento-financiero-gestión-independiente-de-multas)
6. [Consolidación de la Arquitectura Relacional en 3FN (8 Tablas)](#6-consolidación-de-la-arquitectura-relacional-en-3fn-8-tablas)
   - [6.1 Catálogo de Tablas Resultantes](#61-catálogo-de-tablas-resultantes)
   - [6.2 Esquema Relacional Formal (PK y FK)](#62-esquema-relacional-formal-pk-y-fk)
   - [6.3 Matriz de Trazabilidad y Normalización](#63-matriz-de-trazabilidad-y-normalización)
7. [Análisis de Erradicación de Anomalías de Datos](#7-análisis-de-erradicación-de-anomalías-de-datos)
   - [7.1 Anomalías de Inserción Resueltas](#71-anomalías-de-inserción-resueltas)
   - [7.2 Anomalías de Modificación / Actualización Resueltas](#72-anomalías-de-modificación--actualización-resueltas)
   - [7.3 Anomalías de Eliminación Resueltas](#73-anomalías-de-eliminación-resueltas)
8. [Conclusión y Vinculación con los Siguientes Entregables](#8-conclusión-y-vinculación-con-los-siguientes-entregables)

---

## 1. Introducción

El diseño adecuado de una base de datos relacional es el pilar fundamental que garantiza el funcionamiento óptimo, consistente y duradero de cualquier sistema de información. En el contexto de un **Sistema de Gestión Bibliotecaria Universitaria**, la base de datos no solo debe almacenar información sobre volúmenes impresos y personas, sino que debe gobernar de manera estricta los procesos operativos cotidianos: circulación de préstamos, control de inventario de ejemplares físicos, aplicación de políticas institucionales y cobranza de penalizaciones por retraso.

El presente documento constituye el **Entregable 3** del proyecto de la materia **Modelos de Bases de Datos**, fundamentado en la problemática diagnosticada en el [Entregable 1](01-planteamiento-del-problema.md) y los requerimientos funcionales y reglas de negocio formalizados en el [Entregable 2](02-requerimientos-del-sistema.md).

## 2. Punto de Partida: Fichas Operativas Desnormalizadas (0FN)

Antes de informatizar una biblioteca universitaria mediante un motor relacional, los datos se recaban típicamente mediante hojas de cálculo, libros contables o libretas de mostrador. En este estado primitivo, conocido formalmente como **Forma No Normalizada (0FN)**, la información se agrupa en dos grandes registros operativos cotidianos:

### 2.1 Ficha Operativa 1: Catálogo Bibliográfico y Ejemplares

La primera ficha registra los libros que adquiere la universidad y las copias físicas que se colocan en los estantes:

| ISBN | Titulo | Autor | Nacionalidad_Autor | Categoria | Descripcion_Categoria | Editorial | Anio_Pub | Ejemplares_Fisicos | Ubicaciones_Estante | Estados_Fisicos |
|---|---|---|---|---|---|---|---|---|---|---|
| 978-0131103627 | El Lenguaje de Programación C | Brian W. Kernighan | Estadounidense | Ciencias Computacionales | Libros de desarrollo de software y sistemas | Prentice Hall | 1988 | LIB-C-001, LIB-C-002, LIB-C-003 | Estante A-1, Estante A-1, Estante A-2 | Bueno, Regular, Excelente |
| 978-0201633610 | Design Patterns | Erich Gamma | Suizo | Ingenieria de Software | Arquitectura y patrones de diseno | Addison-Wesley | 1994 | DP-001, DP-002 | Estante B-3, Estante B-3 | Excelente, Bueno |
| 978-6071503152 | Fundamentos de Bases de Datos | Abraham Silberschatz | Estadounidense | Bases de Datos | Diseno, modelado y administracion de datos | McGraw-Hill | 2011 | BD-001 | Estante C-2 | Excelente |

### 2.2 Ficha Operativa 2: Registro de Circulación, Préstamos y Sanciones

La segunda ficha documenta los movimientos diarios en el mostrador de atención cuando un estudiante o profesor solicita material:

| Folio_Mov | Matricula_NumEmp | Nombre_Usuario | Tipo_Usuario | Max_Libros | Dias_Permitidos | Correo | Telefono | Codigo_Ejemplar | Titulo_Libro | Fecha_Salida | Fecha_Compromiso | Fecha_Devolucion | Dias_Retraso | Monto_Multa | Estado_Multa |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| P-101 | ALU-2024-001 | Carlos Mendoza | Alumno | 3 | 7 | carlos.mendoza@alumnos.unitec.mx | 5512345678 | LIB-C-001 | El Lenguaje de Programación C | 2026-10-01 | 2026-10-08 | 2026-10-08 | 0 | 0.00 | Sin Multa |
| P-102 | DOC-1045 | Dra. Laura Peña | Docente | 5 | 14 | laura.pena@docentes.unitec.mx | 5587654321 | DP-001 | Design Patterns | 2026-10-05 | 2026-10-19 | 2026-10-23 | 4 | 60.00 | Pagada |
| P-103 | ALU-2024-001 | Carlos Mendoza | Alumno | 3 | 7 | carlos.mendoza@alumnos.unitec.mx | 5512345678 | BD-001 | Fundamentos de Bases de Datos | 2026-11-01 | 2026-11-08 | Pendiente | 2 | 30.00 | Pendiente |
| P-104 | ALU-2024-089 | Sofía Morales | Alumno | 3 | 7 | sofia.morales@alumnos.unitec.mx | 5543219876 | LIB-C-002 | El Lenguaje de Programación C | 2026-11-02 | 2026-11-09 | 2026-11-09 | 0 | 0.00 | Sin Multa |

---

### 2.3 Diagnóstico Crítico de la Forma No Normalizada (0FN)

Un análisis de las dos fichas operativas evidencia de inmediato graves deficiencias estructurales:

1. **Grupos repetitivos y atributos multivaluados:** En la Ficha de Catálogo, las columnas `Ejemplares_Fisicos`, `Ubicaciones_Estante` y `Estados_Fisicos` almacenan listas separadas por comas en una sola celda. Esto hace imposible que una consulta SQL consulte, ordene o bloquee de manera independiente la disponibilidad de una copia específica sin parsear cadenas de texto.
2. **Redundancia incontrolada de datos:** En la Ficha de Circulación, cada vez que el alumno Carlos Mendoza solicita un libro, se vuelven a capturar su nombre, correo, teléfono, tipo de usuario, límite de libros y días permitidos. Si Carlos solicita 10 préstamos durante el cuatrimestre, su información personal se reescribe 10 veces idéntica.
3. **Alto riesgo de corrupción por inconsistencia:** Si la política institucional aumenta el préstamo de alumnos de 7 a 10 días, habría que modificar cientos de registros históricos uno por uno. Si en un préstamo se captura "Carlos Mendoza" y en otro "Carlos Mndoza" por error tipográfico, el sistema asumirá que son personas diferentes.
4. **Vulnerabilidad a anomalías operativas:**
   - *Anomalía de Inserción:* No se puede dar de alta a un usuario nuevo en el sistema hasta que solicite su primer libro en préstamo (o habría que dejar campos vacíos o `NULL` en los datos del préstamo).
   - *Anomalía de Eliminación:* Si se cancela o purga el único préstamo de la profesora Laura Peña, se borrarán simultáneamente su número de empleado, correo, teléfono y el historial de que es docente.
   - *Anomalía de Modificación:* Si el alumno cambia su número de teléfono, se debe actualizar en múltiples filas. Si alguna fila se omite, la base de datos entra en un estado corrupto e inconsistente.

---

## 3. Primera Forma Normal (1FN): Atomicidad y Eliminación de Grupos Repetitivos

### 3.1 Criterios Técnicos de la 1FN

Una estructura de datos se encuentra formalmente en **Primera Forma Normal (1FN)** cuando satisface los siguientes tres principios:
1. **Atomicidad:** Cada columna contiene únicamente valores atómicos indivisibles (un solo dato elemental por celda, prohibiendo arreglos, listas separadas por comas o campos combinados).
2. **Eliminación de grupos repetitivos:** Cada registro representa una instancia única y homogénea de información.
3. **Identificador único:** Existe una clave primaria (`PRIMARY KEY`) simple o compuesta que identifica unívocamente a cada fila de la tabla.

---

### 3.2 Aplicación de 1FN a la Ficha de Catálogo Bibliográfico

Para cumplir con 1FN, separamos las listas de copias físicas de la Ficha 1. Ahora, cada fila representará la combinación de un libro con un ejemplar físico específico:

#### Tabla: `CATALOGO_1FN`
- **Clave Primaria Compuesta:** `(ISBN, Codigo_Inventario)`

| ISBN [PK] | Codigo_Inventario [PK] | Titulo | Autor | Nacionalidad_Autor | Categoria | Descripcion_Categoria | Editorial | Anio_Pub | Ubicacion_Estante | Estado_Conservacion |
|---|---|---|---|---|---|---|---|---|---|---|
| 978-0131103627 | LIB-C-001 | El Lenguaje de Programación C | Brian W. Kernighan | Estadounidense | Ciencias Computacionales | Libros de desarrollo de software y sistemas | Prentice Hall | 1988 | Estante A-1 | Bueno |
| 978-0131103627 | LIB-C-002 | El Lenguaje de Programación C | Brian W. Kernighan | Estadounidense | Ciencias Computacionales | Libros de desarrollo de software y sistemas | Prentice Hall | 1988 | Estante A-1 | Regular |
| 978-0131103627 | LIB-C-003 | El Lenguaje de Programación C | Brian W. Kernighan | Estadounidense | Ciencias Computacionales | Libros de desarrollo de software y sistemas | Prentice Hall | 1988 | Estante A-2 | Excelente |
| 978-0201633610 | DP-001 | Design Patterns | Erich Gamma | Suizo | Ingenieria de Software | Arquitectura y patrones de diseno | Addison-Wesley | 1994 | Estante B-3 | Excelente |
| 978-0201633610 | DP-002 | Design Patterns | Erich Gamma | Suizo | Ingenieria de Software | Arquitectura y patrones de diseno | Addison-Wesley | 1994 | Estante B-3 | Bueno |
| 978-6071503152 | BD-001 | Fundamentos de Bases de Datos | Abraham Silberschatz | Estadounidense | Bases de Datos | Diseno, modelado y administracion de datos | McGraw-Hill | 2011 | Estante C-2 | Excelente |

---

### 3.3 Aplicación de 1FN a la Ficha de Circulación y Préstamos

En la Ficha de Circulación, cada fila se estabiliza de modo que registre un único movimiento transaccional asociado a un código de inventario específico:

#### Tabla: `CIRCULACION_1FN`
- **Clave Primaria:** `Folio_Mov`

| Folio_Mov [PK] | Matricula_NumEmp | Nombre_Usuario | Tipo_Usuario | Max_Libros | Dias_Permitidos | Correo | Telefono | Codigo_Inventario | Titulo_Libro | Fecha_Salida | Fecha_Compromiso | Fecha_Devolucion | Dias_Retraso | Monto_Multa | Estado_Multa |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| P-101 | ALU-2024-001 | Carlos Mendoza | Alumno | 3 | 7 | carlos.mendoza@alumnos.unitec.mx | 5512345678 | LIB-C-001 | El Lenguaje de Programación C | 2026-10-01 | 2026-10-08 | 2026-10-08 | 0 | 0.00 | Sin Multa |
| P-102 | DOC-1045 | Dra. Laura Peña | Docente | 5 | 14 | laura.pena@docentes.unitec.mx | 5587654321 | DP-001 | Design Patterns | 2026-10-05 | 2026-10-19 | 2026-10-23 | 4 | 60.00 | Pagada |
| P-103 | ALU-2024-001 | Carlos Mendoza | Alumno | 3 | 7 | carlos.mendoza@alumnos.unitec.mx | 5512345678 | BD-001 | Fundamentos de Bases de Datos | 2026-11-01 | 2026-11-08 | Pendiente | 2 | 30.00 | Pendiente |
| P-104 | ALU-2024-089 | Sofía Morales | Alumno | 3 | 7 | sofia.morales@alumnos.unitec.mx | 5543219876 | LIB-C-002 | El Lenguaje de Programación C | 2026-11-02 | 2026-11-09 | 2026-11-09 | 0 | 0.00 | Sin Multa |

---

### 3.4 Evaluación del Estado en 1FN

Aunque ahora todas las celdas contienen datos atómicos y no existen listas múltiples:
- **En `CATALOGO_1FN`:** Para registrar 3 copias físicas de "El Lenguaje de Programación C", tuvimos que repetir el título, el autor, la nacionalidad, la categoría, la descripción de la categoría, la editorial y el año de publicación exactamente iguales 3 veces. Si la biblioteca adquiere 50 copias de un libro de texto, esos metadatos se clonarían 50 veces.
- **En `CIRCULACION_1FN`:** Toda la información demográfica del usuario y el título del libro siguen mezclados con la transacción de salida y el cálculo de la multa.

Esta redundancia es una señal inconfundible de que existen **dependencias funcionales parciales** que violan la Segunda Forma Normal.

---

## 4. Segunda Forma Normal (2FN): Eliminación de Dependencias Funcionales Parciales

### 4.1 Criterios Técnicos de la 2FN

Una base de datos se encuentra en **Segunda Forma Normal (2FN)** cuando cumple rigurosamente con dos condiciones:
1. Se encuentra en **Primera Forma Normal (1FN)**.
2. **Ausencia de dependencias parciales:** Todos los atributos que no forman parte de la clave primaria deben depender funcionalmente de la **totalidad de la clave primaria**, y no de una parte de ella.

> [!NOTE]
> Las dependencias parciales surgen típicamente en tablas cuya clave primaria es **compuesta** (formada por más de un atributo). Si un atributo depende solo de uno de los componentes de la clave, debe segregarse en su propia entidad.

---

### 4.2 Análisis de Dependencias en Catálogo: Separación de Obras y Ejemplares Físicos

En la tabla `CATALOGO_1FN`, la clave primaria identificada fue `(ISBN, Codigo_Inventario)`. Al analizar atributo por atributo:

- `Titulo`, `Autor`, `Nacionalidad_Autor`, `Categoria`, `Descripcion_Categoria`, `Editorial`, `Anio_Pub`:
  - ¿Dependen de `Codigo_Inventario`? **No.** El título "Design Patterns" sigue siendo el mismo sin importar si la copia física es la DP-001 o la DP-002.
  - Dependen **exclusivamente de `ISBN`** (dependencia parcial).
- `Ubicacion_Estante`, `Estado_Conservacion`:
  - Dependen de la copia física específica identificada por `Codigo_Inventario` perteneciente a dicho `ISBN` (dependencia total).

#### Descomposición 2FN:

Para erradicar la dependencia parcial, descomponemos `CATALOGO_1FN` en dos tablas relacionadas:
1. `LIBROS_2FN`: Representa la obra conceptual/intelectual. Clave primaria: `ISBN`.
2. `EJEMPLARES_2FN`: Representa la copia física inventariable en el estante. Clave primaria: `Codigo_Inventario`, con `ISBN` como clave foránea (`FK`).

#### Tabla: `LIBROS_2FN`
- **Clave Primaria:** `ISBN`

| ISBN [PK] | Titulo | Autor | Nacionalidad_Autor | Categoria | Descripcion_Categoria | Editorial | Anio_Pub |
|---|---|---|---|---|---|---|---|
| 978-0131103627 | El Lenguaje de Programación C | Brian W. Kernighan | Estadounidense | Ciencias Computacionales | Libros de desarrollo de software y sistemas | Prentice Hall | 1988 |
| 978-0201633610 | Design Patterns | Erich Gamma | Suizo | Ingenieria de Software | Arquitectura y patrones de diseno | Addison-Wesley | 1994 |
| 978-6071503152 | Fundamentos de Bases de Datos | Abraham Silberschatz | Estadounidense | Bases de Datos | Diseno, modelado y administracion de datos | McGraw-Hill | 2011 |

#### Tabla: `EJEMPLARES_2FN`
- **Clave Primaria:** `Codigo_Inventario`
- **Clave Foránea:** `ISBN` → referencia a `LIBROS_2FN(ISBN)`

| Codigo_Inventario [PK] | ISBN [FK] | Ubicacion_Estante | Estado_Conservacion |
|---|---|---|---|
| LIB-C-001 | 978-0131103627 | Estante A-1 | Bueno |
| LIB-C-002 | 978-0131103627 | Estante A-1 | Regular |
| LIB-C-003 | 978-0131103627 | Estante A-2 | Excelente |
| DP-001 | 978-0201633610 | Estante B-3 | Excelente |
| DP-002 | 978-0201633610 | Estante B-3 | Bueno |
| BD-001 | 978-6071503152 | Estante C-2 | Excelente |

---

### 4.3 Análisis de Dependencias en Circulación: Separación de Usuarios y Transacciones

En la tabla `CIRCULACION_1FN`, la clave primaria es `Folio_Mov`. Sin embargo, al inspeccionar el significado de los datos:
- Los atributos `Nombre_Usuario`, `Tipo_Usuario`, `Max_Libros`, `Dias_Permitidos`, `Correo` y `Telefono` pertenecen de forma natural al usuario identificado por su `Matricula_NumEmp`.
- El atributo `Titulo_Libro` pertenece al libro asociado al ejemplar prestado, no al movimiento de salida.
- Mantener los datos demográficos del usuario en el movimiento de préstamo genera una anomalía donde no se puede conocer al usuario si nunca ha pedido prestado un libro.

#### Descomposición 2FN:

Separamos la información de personas de los movimientos de préstamo:
1. `USUARIOS_2FN`: Almacena el padrón institucional. Clave primaria: `Matricula_NumEmp`.
2. `PRESTAMOS_2FN`: Almacena el acto transaccional de préstamo, vinculando al usuario mediante `Matricula_NumEmp` y al libro físico mediante `Codigo_Inventario`.

#### Tabla: `USUARIOS_2FN`
- **Clave Primaria:** `Matricula_NumEmp`

| Matricula_NumEmp [PK] | Nombre_Usuario | Tipo_Usuario | Max_Libros | Dias_Permitidos | Correo | Telefono |
|---|---|---|---|---|---|---|
| ALU-2024-001 | Carlos Mendoza | Alumno | 3 | 7 | carlos.mendoza@alumnos.unitec.mx | 5512345678 |
| DOC-1045 | Dra. Laura Peña | Docente | 5 | 14 | laura.pena@docentes.unitec.mx | 5587654321 |
| ALU-2024-089 | Sofía Morales | Alumno | 3 | 7 | sofia.morales@alumnos.unitec.mx | 5543219876 |

#### Tabla: `PRESTAMOS_2FN`
- **Clave Primaria:** `Folio_Mov`
- **Claves Foráneas:** `Matricula_NumEmp` → `USUARIOS_2FN`, `Codigo_Inventario` → `EJEMPLARES_2FN`

| Folio_Mov [PK] | Matricula_NumEmp [FK] | Codigo_Inventario [FK] | Fecha_Salida | Fecha_Compromiso | Fecha_Devolucion | Dias_Retraso | Monto_Multa | Estado_Multa |
|---|---|---|---|---|---|---|---|---|
| P-101 | ALU-2024-001 | LIB-C-001 | 2026-10-01 | 2026-10-08 | 2026-10-08 | 0 | 0.00 | Sin Multa |
| P-102 | DOC-1045 | DP-001 | 2026-10-05 | 2026-10-19 | 2026-10-23 | 4 | 60.00 | Pagada |
| P-103 | ALU-2024-001 | BD-001 | 2026-11-01 | 2026-11-08 | Pendiente | 2 | 30.00 | Pendiente |
| P-104 | ALU-2024-089 | LIB-C-002 | 2026-11-02 | 2026-11-09 | 2026-11-09 | 0 | 0.00 | Sin Multa |

---

### 4.4 Evaluación del Estado en 2FN

En este punto contamos con 4 tablas coherentes: `LIBROS_2FN`, `EJEMPLARES_2FN`, `USUARIOS_2FN` y `PRESTAMOS_2FN`. 

Sin embargo, todavía existen fallas de diseño causadas por **dependencias transitivas**:
- En `USUARIOS_2FN`: El atributo `Tipo_Usuario` (Alumno, Docente) determina funcionalmente los valores de `Max_Libros` y `Dias_Permitidos`. Si la biblioteca tiene 5,000 alumnos, el par `(3, 7)` se repite 5,000 veces.
- En `LIBROS_2FN`: El autor determina su nacionalidad, y el nombre de la categoría determina su descripción. Si tenemos 20 libros de Ciencias Computacionales, la descripción se repite 20 veces.
- En `PRESTAMOS_2FN`: Las multas solo se generan cuando un préstamo entra en mora. Guardar columnas de multa (`Dias_Retraso`, `Monto_Multa`, `Estado_Multa`) en la tabla de préstamos causa filas llenas de ceros o valores nulos para préstamos devueltos en tiempo y forma, e impide auditar la cobranza financiera con rigor contable.

Para resolver estas deficiencias avanzamos a la Tercera Forma Normal.

---

## 5. Tercera Forma Normal (3FN): Eliminación de Dependencias Transitivas

### 5.1 Criterios Técnicos de la 3FN

Una base de datos se encuentra formalmente en **Tercera Forma Normal (3FN)** cuando satisface dos reglas fundamentales:
1. Se encuentra en **Segunda Forma Normal (2FN)**.
2. **Ausencia de dependencias transitivas:** Ningún atributo no clave debe depender funcionalmente de otro atributo no clave.

En palabras del célebre aforismo de Bill Kent sobre la regla de Codd:
> *"Cada atributo no clave debe proporcionar un hecho sobre la clave primaria, la totalidad de la clave primaria, y nada más que la clave primaria."*

Cualquier relación en la que $X \rightarrow Y$ y luego $Y \rightarrow Z$ (siendo $X$ la clave primaria y $Y, Z$ atributos no clave) introduce una dependencia transitiva $X \rightarrow Z$ que debe eliminarse creando una nueva tabla para $Y \rightarrow Z$.

---

### 5.2 Desacoplamiento de Entidades Catálogo: Autores y Categorías

En la tabla `LIBROS_2FN`:
- $\text{ISBN} \rightarrow \text{Autor} \rightarrow \text{Nacionalidad\_Autor}$ (Dependencia transitiva).
- $\text{ISBN} \rightarrow \text{Categoria} \rightarrow \text{Descripcion\_Categoria}$ (Dependencia transitiva).

Si un autor escribe varios libros, repetir su nacionalidad en cada libro es redundante. Si una categoría cambia de descripción o nombre, no debe obligar a modificar registros de libros individuales.

#### Solución en 3FN:
1. Creamos la entidad `AUTORES`:
   - `id_autor` [PK]
   - `nombre_autor`
   - `nacionalidad`
2. Creamos la entidad `CATEGORIAS`:
   - `id_categoria` [PK]
   - `nombre_categoria`
   - `descripcion`
3. Refactorizamos `LIBROS`: Reemplazamos los datos redundantes por claves foráneas hacia `id_autor` e `id_categoria`.

---

### 5.3 Desacoplamiento de Políticas: Tipos de Usuario

En la tabla `USUARIOS_2FN`:
- $\text{Matricula\_NumEmp} \rightarrow \text{Tipo\_Usuario} \rightarrow (\text{Max\_Libros}, \text{Dias\_Permitidos})$ (Dependencia transitiva).

El límite máximo de libros simultáneos y los días de préstamo permitidos no son una propiedad personal del alumno Carlos Mendoza, sino una **política institucional** asociada a su rol o categoría académica.

#### Solución en 3FN:
1. Creamos la entidad `TIPOS_USUARIO`:
   - `id_tipo` [PK]
   - `nombre_tipo` (Alumno, Docente)
   - `limite_libros` (3 para Alumnos, 5 para Docentes)
   - `dias_prestamo` (7 para Alumnos, 14 para Docentes)
2. Refactorizamos `USUARIOS`: Conserva únicamente los atributos propios del individuo (`nombre_completo`, `correo`, `telefono`, `estado`) y se asocia mediante la llave foránea `id_tipo`.

---

### 5.4 Desacoplamiento Financiero: Gestión Independiente de Multas

En la tabla `PRESTAMOS_2FN`:
- $\text{Folio\_Mov} \rightarrow (\text{Dias\_Retraso}, \text{Monto\_Multa}, \text{Estado\_Multa})$.

Aunque el cálculo de la sanción proviene de un préstamo excedido en fecha, la multa es una **transacción económica independiente**:
- Un préstamo devuelto en tiempo jamás debe albergar atributos de multa.
- Una multa puede ser pagada en una fecha posterior a la devolución real.
- Mantener la multa en la misma tabla fuerza a almacenar ceros, valores por defecto artificiales o columnas no aplicables (`NULL`), vulnerando el principio de diseño limpio.

#### Solución en 3FN:
1. Conservamos `PRESTAMOS` enfocado exclusivamente en la trazabilidad del movimiento de circulación (quién se llevó qué ejemplar, cuándo se lo llevó, cuándo prometió entregarlo, cuándo lo devolvió realmente y el estado del préstamo).
2. Creamos la entidad `MULTAS` vinculada mediante llave foránea a `id_prestamo`:
   - `id_multa` [PK]
   - `id_prestamo` [FK]
   - `dias_retraso`
   - `monto_total`
   - `fecha_generacion`
   - `estado_pago` (Pendiente, Pagada, Exonerada)

---

## 6. Consolidación de la Arquitectura Relacional en 3FN (8 Tablas)

Tras completar el proceso de análisis y descomposición, se obtiene una arquitectura relacional sólida y equilibrada conformada por **exactamente 8 tablas** en **Tercera Forma Normal (3FN)**.

### 6.1 Catálogo de Tablas Resultantes

#### 1. Tabla: `TIPOS_USUARIO`
Define los perfiles de usuario y las políticas operativas de préstamo autorizadas por la institución.
- `id_tipo` [PK]: Identificador entero autonumérico del tipo de usuario.
- `nombre_tipo`: Nombre del rol (`Alumno`, `Docente`).
- `limite_libros`: Cantidad máxima de ejemplares simultáneos permitidos (Regla de Negocio RN-04).
- `dias_prestamo`: Plazo legal máximo de préstamo en días naturales (Regla de Negocio RN-04).

#### 2. Tabla: `USUARIOS`
Padrón institucional de alumnos y profesores habilitados para solicitar servicios en la biblioteca.
- `id_usuario` [PK]: Identificador único del usuario en el sistema.
- `matricula_num_emp`: Clave única institucional (Matrícula para alumnos, Número de empleado para docentes).
- `nombre_completo`: Nombre y apellidos del usuario.
- `correo`: Correo electrónico de contacto institucional.
- `telefono`: Teléfono móvil para recordatorios y notificaciones de cobranza.
- `estado`: Condición operativa del usuario (`Activo`, `Bloqueado`).
- `id_tipo` [FK]: Llave foránea hacia `TIPOS_USUARIO(id_tipo)`.

#### 3. Tabla: `AUTORES`
Directorio de autores literarios e intelectuales de las obras existentes.
- `id_autor` [PK]: Identificador único del autor.
- `nombre_autor`: Nombre completo o de pluma del autor.
- `nacionalidad`: País o nacionalidad de origen.

#### 4. Tabla: `CATEGORIAS`
Clasificación temática del acervo bibliográfico.
- `id_categoria` [PK]: Identificador único de la categoría.
- `nombre_categoria`: Nombre del área de conocimiento (e.g. *Bases de Datos*, *Ingeniería de Software*).
- `descripcion`: Reseña del tipo de literatura clasificada.

#### 5. Tabla: `LIBROS`
Catálogo de obras intelectuales registradas en el sistema (título y metadatos bibliográficos).
- `id_libro` [PK]: Identificador único del título.
- `titulo`: Nombre completo de la obra.
- `isbn`: Número Estándar Internacional del Libro (único a nivel editorial).
- `anio_publicacion`: Año de edición del material.
- `editorial`: Casa editorial responsable de la publicación.
- `id_autor` [FK]: Llave foránea hacia `AUTORES(id_autor)`.
- `id_categoria` [FK]: Llave foránea hacia `CATEGORIAS(id_categoria)`.

#### 6. Tabla: `EJEMPLARES`
Representa cada una de las copias físicas reales que se encuentran en las estanterías de la biblioteca.
- `id_ejemplar` [PK]: Identificador único de la copia.
- `id_libro` [FK]: Llave foránea hacia `LIBROS(id_libro)`.
- `codigo_inventario`: Código de barras o marbete patrimonial único pegado físicamente al libro.
- `ubicacion_estante`: Código de pasillo/estante físico (e.g. `Estante A-1`).
- `estado_conservacion`: Condición física (`Excelente`, `Bueno`, `Regular`, `Dañado`).
- `estado_disponibilidad`: Situación de circulación (`Disponible`, `Prestado`, `En Mantenimiento`, `Extraviado`).

#### 7. Tabla: `PRESTAMOS`
Transacciones de circulación que asocian a un usuario con un ejemplar físico específico en una ventana de tiempo.
- `id_prestamo` [PK]: Identificador único del movimiento de préstamo.
- `id_usuario` [FK]: Llave foránea hacia `USUARIOS(id_usuario)`.
- `id_ejemplar` [FK]: Llave foránea hacia `EJEMPLARES(id_ejemplar)`.
- `fecha_salida`: Fecha y hora en que se entrega el libro en mostrador.
- `fecha_compromiso`: Fecha límite fijada para la devolución sin sanción.
- `fecha_devolucion_real`: Fecha en que el libro reingresó físicamente a la biblioteca (`NULL` si sigue prestado).
- `estado_prestamo`: Condición del préstamo (`Activo`, `Devuelto`, `Vencido`).

#### 8. Tabla: `MULTAS`
Registro y seguimiento financiero de sanciones económicas originadas por incumplimiento de plazos de devolución.
- `id_multa` [PK]: Identificador único de la sanción.
- `id_prestamo` [FK]: Llave foránea hacia `PRESTAMOS(id_prestamo)`.
- `dias_retraso`: Días transcurridos entre la fecha de compromiso y la devolución real (o fecha actual).
- `monto_total`: Importe monetario a pagar calculado a razón de $15.00 MXN por día de retraso.
- `fecha_generacion`: Momento en que el sistema determina la penalización.
- `estado_pago`: Estatus de cobro (`Pendiente`, `Pagada`, `Exonerada`).

---

### 6.2 Esquema Relacional Formal (PK y FK)

A continuación se presenta la notación estándar del modelo lógico relacional resultante, donde se identifican las Claves Primarias (`[PK]`) y las Claves Foráneas (`[FK]`):

```text
TIPOS_USUARIO (
    id_tipo [PK],
    nombre_tipo,
    limite_libros,
    dias_prestamo
)

USUARIOS (
    id_usuario [PK],
    matricula_num_emp,
    nombre_completo,
    correo,
    telefono,
    estado,
    id_tipo [FK] -> TIPOS_USUARIO.id_tipo
)

AUTORES (
    id_autor [PK],
    nombre_autor,
    nacionalidad
)

CATEGORIAS (
    id_categoria [PK],
    nombre_categoria,
    descripcion
)

LIBROS (
    id_libro [PK],
    titulo,
    isbn,
    anio_publicacion,
    editorial,
    id_autor [FK] -> AUTORES.id_autor,
    id_categoria [FK] -> CATEGORIAS.id_categoria
)

EJEMPLARES (
    id_ejemplar [PK],
    id_libro [FK] -> LIBROS.id_libro,
    codigo_inventario,
    ubicacion_estante,
    estado_conservacion,
    estado_disponibilidad
)

PRESTAMOS (
    id_prestamo [PK],
    id_usuario [FK] -> USUARIOS.id_usuario,
    id_ejemplar [FK] -> EJEMPLARES.id_ejemplar,
    fecha_salida,
    fecha_compromiso,
    fecha_devolucion_real,
    estado_prestamo
)

MULTAS (
    id_multa [PK],
    id_prestamo [FK] -> PRESTAMOS.id_prestamo,
    dias_retraso,
    monto_total,
    fecha_generacion,
    estado_pago
)
```

---

### 6.3 Matriz de Trazabilidad y Normalización

La siguiente tabla resume cómo cada una de las 8 tablas de la base de datos alcanzó formalmente la 3FN y qué regla de negocio del sistema sustenta:

| Tabla Resultante | Forma Alcanzada | Justificación Teórica de la Descomposición | Regla de Negocio / Propósito Operativo |
|---|---|---|---|
| `TIPOS_USUARIO` | **3FN** | Erradica la dependencia transitiva de políticas (`limite_libros`, `dias_prestamo`) respecto a los datos personales del usuario. | **RN-04:** Límites diferenciados por tipo de prestatario. |
| `USUARIOS` | **3FN** | Centraliza los datos del individuo en un único registro atómico. Su clave primaria es simple (`id_usuario`). | **RN-03 y RN-05:** Control del padrón, contacto y suspensión por adeudos. |
| `AUTORES` | **3FN** | Erradica la dependencia transitiva de la nacionalidad del autor respecto al libro. | Integridad de catálogo; un autor puede tener múltiples obras sin duplicar su biografía. |
| `CATEGORIAS` | **3FN** | Aísla la descripción y nomenclatura temática para que no dependan del libro. | Clasificación temática consistente y filtros de búsqueda rápida. |
| `LIBROS` | **3FN** | Representa la obra intelectual con atributos dependientes únicamente de la obra (`id_libro`). Sus foráneas apuntan a autor y categoría en 3FN. | **RN-01:** Desacoplamiento entre la obra y sus copias físicas en anaquel. |
| `EJEMPLARES` | **3FN** | Elimina la dependencia parcial del estado y ubicación respecto a la obra. Cada copia física tiene identidad propia e irrepetible. | **RN-01 y RN-02:** Gestión de copias individuales, ubicación y estatus de disponibilidad. |
| `PRESTAMOS` | **3FN** | Registra el evento transaccional puro entre un usuario y un ejemplar. Elimina redundancia de datos personales o bibliográficos. | **RN-02 y RN-03:** Préstamo de copias disponibles y control de fechas de retorno. |
| `MULTAS` | **3FN** | Elimina valores nulos y dependencias transitivas en préstamos sin mora, formalizando el cobro financiero como entidad propia. | **RN-05:** Cálculo de penalizaciones ($15.00/día) y trazabilidad del estado de pago. |

---

## 7. Análisis de Erradicación de Anomalías de Datos

El valor práctico fundamental de alcanzar la Tercera Forma Normal reside en que **el motor de base de datos protege la integridad de la información de forma automática**, eliminando de raíz las tres anomalías clásicas de los sistemas de almacenamiento:

### 7.1 Anomalías de Inserción Resueltas

- **Situación previa (0FN / 1FN):** No se podía dar de alta a un usuario nuevo hasta que no solicitara su primer libro en préstamo, porque de lo contrario los campos de préstamo quedarían vacíos. De igual forma, no se podía registrar a un autor o a una categoría si aún no se había comprado ningún libro de ellos.
- **Resolución en 3FN:** Cada entidad es soberana e independiente:
  - Es posible registrar a un nuevo estudiante en `USUARIOS` el primer día de clases sin necesidad de generarle un registro forzado en `PRESTAMOS`.
  - Es posible crear categorías temáticas (e.g. *Inteligencia Artificial*) y dar de alta a nuevos autores en `CATEGORIAS` y `AUTORES` con anticipación a la llegada física de las obras a la biblioteca.
  - Es posible registrar un libro en `LIBROS` antes de que el personal de encuadernación etiquete los ejemplares físicos en `EJEMPLARES`.

---

### 7.2 Anomalías de Modificación / Actualización Resueltas

- **Situación previa (0FN / 1FN):** Si el alumno Carlos Mendoza cambiaba de número de teléfono o correo electrónico, había que buscar y actualizar todas y cada una de las filas donde Carlos hubiera pedido un libro prestado a lo largo de su carrera. Si una sola fila no se actualizaba, la base de datos quedaba inconsistente y con información contradictoria. De igual forma, si la política universitaria decidía aumentar el plazo de los docentes de 14 a 20 días, había que reescribir miles de registros antiguos.
- **Resolución en 3FN:** Principio de unicidad del dato (*Single Source of Truth*):
  - El teléfono y correo de un usuario residen en **un único renglón** de la tabla `USUARIOS`. Una simple sentencia `UPDATE` modifica el dato instantáneamente para todo el sistema.
  - El plazo de préstamo de los docentes se almacena en **una sola celda** de la tabla `TIPOS_USUARIO`. Modificar ese valor actualiza la política para toda la comunidad docente de forma transparente y sin tocar a los usuarios individuales.

---

### 7.3 Anomalías de Eliminación Resueltas

- **Situación previa (0FN / 1FN):** Si se depuraba el registro de un préstamo antiguo para liberar espacio en disco o cancelar un movimiento erróneo, y dicho registro era el único que tenía asociada a la profesora Laura Peña, se eliminaba accidentalmente a la usuaria del sistema. Igualmente, si se borraba la única copia prestada de un libro, se perdía del catálogo la existencia de la obra y su autor.
- **Resolución en 3FN:** La integridad referencial separa el ciclo de vida de los datos:
  - Si una copia física se daña irreversiblemente o se extravía, se elimina o actualiza únicamente la fila correspondiente en `EJEMPLARES`, manteniendo intacta la ficha de la obra en `LIBROS` y todo el catálogo histórico.
  - Si un préstamo devuelto hace años concluye su periodo de auditoría, puede archivarse sin afectar el registro del usuario prestatario ni la ficha de inventario del libro físico.

---

## 8. Conclusión y Vinculación con los Siguientes Entregables

El proceso de análisis y normalización documentado en este entregable demuestra cómo, a partir de dos registros operacionales dispersos y desordenados (0FN), se deriva una estructura de datos robusta, escalable y depurada en **Tercera Forma Normal (3FN)** conformada por **8 tablas**.

Este diseño cumple cabalmente con los requisitos obligatorios del proyecto:
1. **Rango de tablas:** Se sitúa con exactitud en el rango normativo de 7 a 9 tablas.
2. **Nivel formal:** Alcanza 3FN garantizando que cada atributo dependa de la clave, de toda la clave y de nada más que de la clave.
3. **Respaldo operativo:** Brinda soporte directo a los 5 módulos funcionales del sistema (Catálogo, Usuarios, Préstamos, Devoluciones y Multas).

### Conexión con los Entregables Siguientes

- **Hacia el Entregable 4 (Diagrama Entidad-Relación - DER):** Este documento proporciona las entidades (`TIPOS_USUARIO`, `USUARIOS`, `AUTORES`, `CATEGORIAS`, `LIBROS`, `EJEMPLARES`, `PRESTAMOS`, `MULTAS`), sus llaves primarias y las relaciones (1:N) que serán representadas visualmente mediante la notación gráfica formal de cardinalidades y atributos.
- **Hacia el Entregable 5 (Diccionario de Datos y DDL):** La estructura aquí normalizada servirá como base para definir el Diccionario de Datos técnico (especificando los tipos de datos exactos `INT`, `VARCHAR`, `DECIMAL`, `DATETIME`, restricciones `CHECK`, `NOT NULL`, `UNIQUE` y motores de almacenamiento `InnoDB`) y la programación de las **5 consultas relacionales complejas con `JOIN`** exigidas en la rúbrica del proyecto.
