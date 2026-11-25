# Sistema de Gestión Bibliotecaria

> **Materia:** Modelos de Bases de Datos  
> **Proyecto:** Proyecto Final

---

## 1. Descripción y Objetivo

Desarrollar un sistema para la administración integral de los procesos operativos de una biblioteca (gestión del catálogo de libros, usuarios, préstamos, devoluciones y cálculo de multas).

El núcleo del proyecto consiste en la **planeación, diseño y normalización de la base de datos relacional**, complementada con una aplicación web funcional montada sobre un entorno local para operar el sistema.

---

## 2. Requisitos Obligatorios del Sistema

Para que el proyecto sea válido, debe cumplir estrictamente con los siguientes criterios:

- **Estructura de la BD:** Diseñar e implementar entre **7 y 9 tablas** en total.
- **Nivel de normalización:** Alcanzar formalmente la **Tercera Forma Normal (3FN)**.
- **Operaciones CRUD:** Implementar todas las operaciones básicas (Crear, Leer, Actualizar y Eliminar) en las distintas entidades del sistema.
- **Consultas relacionales complejas:** Implementar un mínimo de **5 consultas SQL** que relacionen **2 o más tablas** (`JOIN`).
- **Validación de datos en dos niveles:**
  - **En Base de Datos:** Restricciones de integridad (`NOT NULL`, `UNIQUE`, `CHECK`, llaves foráneas y tipos de datos adecuados) para prevenir registros inconsistentes o duplicados.
  - **En Interfaz / Formularios:** Validaciones en frontend y backend (PHP) para evitar datos incompletos, formatos incorrectos o registros duplicados antes de la inserción.
- **Entorno de despliegue:** Compatible y ejecutable sobre **WampServer** (Apache + MySQL/MariaDB + PHP).

---

## 3. Alcance y Stack Tecnológico

- **Enfoque principal:** Modelado conceptual/lógico, normalización formal (1FN a 3FN), integridad referencial y consultas relacionales.
- **Alcance:** Producto Mínimo Viable (MVP) delimitado; cubre el flujo esencial sin extensiones posteriores.
- **Tecnologías y Entorno:**
  - **Servidor local:** WampServer.
  - **Base de Datos:** MySQL / MariaDB (SQL).
  - **Backend:** PHP.
  - **Frontend:** HTML5, CSS3 y JavaScript (básico).

---

## 4. Entregables del Proyecto

| # | Entregable | Descripción |
|---|---|---|
| **1** | **Planteamiento del Problema** | Definición de la problemática actual, propuesta de solución y objetivos del proyecto. |
| **2** | **Requerimientos Funcionales** | Lista detallada de requerimientos del MVP, delimitación del alcance y restricciones del sistema. |
| **3** | **Diseño y Normalización de BD** | Documentación técnica del proceso de normalización paso a paso (desde 1FN hasta 3FN). |
| **4** | **Diagrama Entidad-Relación (DER)** | Representación gráfica de entidades, atributos, llaves (PK/FK) y cardinalidad de relaciones. |
| **5** | **Diccionario de Datos** | Especificación detallada de las tablas (7 a 9), columnas, tipos de datos, restricciones y descripciones funcionales. |
| **6** | **Pruebas de Funcionamiento** | Evidencias visuales (capturas de pantalla, grabaciones o bitácoras) que validen el sistema en operación bajo WAMP, el CRUD y las 5 consultas relacionales. |

---

> [!IMPORTANT]
> Si bien se entrega una aplicación web funcional sobre WAMP, el valor y rigor principal del proyecto recaen en la **planeación, diseño arquitectónico, normalización y consistencia de la base de datos**.
