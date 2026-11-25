# Propuesta de arquitectura

Este proyecto debe ser lo mas sencillo posible ya que el equipo que lo desarrolla no tiene conocimientos tecnicos en programacion, sin embargo quiero mantener una estructura un poco mas ordenada que simples archivos php con todo lo que necesita la pagina en especifico, asi que quiero hacer la siguiente separacion:

## Estructura principal

- El codigo del proyecto estara en una subcarpeta app/ pero con el index.php en la raiz del proyecto
- Dentro de esta sub carpeta estaran las siguientes carpetas:
    - db/ -> Tendra tanto el archivo de conexion reutilizable como las sub carpetas y sub archivos repositorio que realicen las consultas necesarias a la base de datos, asi como los archivos de schema y seeder .sql para creaer y poblar la base de datos.
    - styles/ -> Archivos css puros con el diseño de todo el proyecto siguiendo el sistema de diseño de stitch (mcp).
    Quiero separar la logica de cada vista, manteniendo como maximo 2 archivos por cada vista, no se como definir eso, me ayudas a definirlo, pero quiero que la vista reciba un  objeto con los datos necesarios para que funcione.
    - Debido a que  voy a usar stitch para el diseño de las vistas y una libreria js para mostrar graficos en algunas pantallas, la vista tambien contendra el js necesario para funcionar. Si se pueden reutilizar algunas funciones guardalas en otra carpeta.
    - up/ -> Contiene los archivos subidos por los usuarios
    - helpers/ funciones reutilizables agrupados en archivos por modulos (autenticacion, parseo, etc)

Si crees que puedo utilizar otra carpeta dimelo, pero quiero mantenerlo simple claro y funcional. sin conceptos tecnicos complicados que confundan a alguien que esta entrando al desarrollo de software