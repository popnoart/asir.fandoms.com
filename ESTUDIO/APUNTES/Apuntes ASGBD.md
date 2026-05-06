## Apuntes — ASGBD 

## Administración de sistemas gestores de base de datos

## ---

# Unidades

* T1 \- Unidad 1 y 2: Administración de sistemas gestores de base de datos  
* T1 \- Unidad 3\. Acceso a la información: DCL  
* T1 \- Unidad 3\. Acceso a la información: Vistas  
* T1 \- Unidad 4.1. Programación de bases de datos: conceptos iniciales  
* T2 \- Unidad 4.2. Procedimientos  
* T2 \- Unidad 4.3. Funciones  
* T3 \- Unidad 4.4. Triggers  
* T3 \- Unidad 4.5. Cursores  
* T3 \- Unidad 5.1. Optimización del rendimiento: TCL  
* T3 \- Unidad 5.2. Recuperación de errores: copias de seguridad  
* T3 \- Unidad 6\. Optimización y monitorización de una base de datos


# T1-U1-2 – Instalación de un Sistema Gestor de Bases de Datos

## Sistema Gestor de Bases de Datos (SGBD)

* Aplicación informática que permite **definir, construir, mantener y consultar** una base de datos, proporcionando un control de acceso.  
* Actúa como **interfaz** entre los datos físicos y los usuarios.  
* Maneja grandes volúmenes de información.  
* Garantiza **seguridad e integridad** de los datos.

### Objetivos de un SGBD

* Proporcionar una **visión abstracta** de los datos, ocultando detalles de cómo se  almacenan los datos de los que el usuario puede desentenderse.  
* Centralizar el acceso a la base de datos.

### Herramientas básicas de un SGBD

* **Definir \- DDL Data** **D**efinition **L**anguage:   
  * Tipos de datos, estructuras y restricciones.  
  * CREATE, ALTER, DROP, RENAME y TRUNCATE  
* **Construir y mantener** \-**DML D**ata **M**anipulation **L**anguage:  
  * Almacenar datos iniciales.  
  * Actualizar, eliminar o insertar datos.  
  * INSERT, UPDATE, MERGE y DELETE  
* **Consultar \- DQL D**ata **Q**uery **L**anguage:  
  * Recuperar información con criterios.  
  * SELECT  
* **Controlar accesos \- DCL D**ata **C**ontrol **L**anguage:   
  * GRANT y REVOKE  
* **DTL D**ata **T**ransaction **L**anguage: ROLLBACK y COMMIT

### Ejemplos de SGBD

* **Relacionales**: MySQL, PostgreSQL, Oracle, SQL Server, SQLite.  
* **Otros**: Access, DB2, H2, Teradata.

### Características de un SGBD

* Consultas complejas y no predefinidas.  
* Flexibilidad ante cambios.  
* Reducción de redundancia.  
* Integridad de datos.  
* Concurrencia de usuarios.  
* Seguridad y control de acceso.

### Ventajas y Desventajas de un SGBD

### Ventajas:

* Control de redundancia.  
* Gestión de integridad.  
* Seguridad con restricciones de acceso.  
* Múltiples interfaces de usuario.  
* Representación de relaciones complejas.  
* Respaldo y recuperación ante fallos.

### Desventajas:

* Complejidad de uso.  
* Tamaño y recursos necesarios.  
* Coste económico (en algunos casos).

## Instalación y configuración de un SGBD

* **SGBD más usado en web**: MySQL (relacional).  
* Alternativa: **PostgreSQL**.  
* **Fork de MySQL**: **MariaDB** (totalmente compatible y libre).  
* Arquitectura **cliente/servidor**.  
* Motores de almacenamiento comunes:  
  * **MyISAM**: rápido, sin transacciones.  
  * **InnoDB**: con transacciones ACID e integridad referencial.

### Configuración de MySQL

* Archivo de configuración: `/etc/mysql/my.cnf`.  
* Directorio de datos: `/var/lib/mysql`.  
* Logs: `/var/log/mysql`.  
* Asignar contraseña a root con `mysql_secure_installation`.  
* Configuración de **phpMyAdmin** en `/etc/phpmyadmin/config.inc.php`.

### Recuperación de contraseña de root en MySQL

1. Detener el servicio:  
   `service mysqld stop`  
2. Ejecutar en modo seguro:  
   `mysqld_safe --skip-grant-tables &`  
3. Acceder sin contraseña:  
   `mysql`  
4. Actualizar contraseña:  
   `UPDATE mysql.user SET Password=PASSWORD('nueva') WHERE User='root';`  
   `FLUSH PRIVILEGES;`  
5. Reiniciar servicio y probar acceso.

---

# T1-U3.1 – Acceso a la Información: DCL (Data Control Language)

DCL \= Data Control Language  
Conjunto de comandos para **otorgar o restringir permisos** a usuarios sobre objetos de la BD.  
Comandos principales: **GRANT y REVOKE**.  
La información de usuarios y permisos se almacena en tablas de la base de datos mysql.

## Gestión de Usuarios

*  Crear un usuario:

`CREATE USER 'usuario'@'localhost' IDENTIFIED BY 'clave';`

* Eliminar un usuario:

`DROP USER 'usuario'@'localhost';`

*  Modificar un usuario:

`ALTER USER 'usuario'@'localhost' IDENTIFIED BY 'nueva_clave';`  
`ALTER USER 'usuario'@'localhost' PASSWORD EXPIRE;`

* Consultar usuarios y privilegios:  
  `SELECT user FROM mysql.user;`  
  `SHOW GRANTS FOR 'usuario'@'localhost';`  
* Actualizar privilegios:  
  `FLUSH PRIVILEGES;`

**Privilegios completos con creación de usuarios:**  
CREATE USER es un privilegio global que requiere permisos sobre \*.\*, no sobre una BD en concreto.  
GRANT ALL PRIVILEGES ON \*.\* TO 'usuario'@'localhost' WITH GRANT OPTION;  
Ojo al eliminar el usuario no elimina los permisos. Si lo vuelves a crear vuelve a tener los permisos que tenía antes de eliminarlo.

## Gestión de Permisos

### Niveles:

* **Nivel 1**: Comprobación de conexión (usuario, host, contraseña).  
* **Nivel 2**: Comprobación de privilegios por cada operación.

### Tipos de permisos según nivel:

| Nivel | Alcance | Tablas del sistema |
| :---- | :---- | :---- |
| Global | Todas las BD y tablas (\*.\*) | `mysql.user` |
| Base de datos | Todos los objetos de una BD (db.\*) | `mysql.db`, `mysql.host` |
| Tabla | Todas las columnas de una tabla | `mysql.tables_priv` |
| Columna | Columnas específicas de una tabla | `mysql.columns_priv` |
| Rutina | Procedimientos y funciones | `mysql.procs_priv` |

Para usar GRANT o REVOKE, el usuario debe tener:

* El permiso GRANT OPTION (permisos totales) sobre el objeto.  
* Debe tener los permisos que está dando o quitando.

## Comando GRANT

Asigna permisos sobre el objeto indicado de la base de datos.  
El que ha creado el objeto es el propietario y por defecto tiene todos los permisos, incluido el permiso de concesión.  
Si un usuario tiene el **permiso de concesión** puede asignar permisos a otro usuario.

*Sintaxis básica:*  
`GRANT privilegios ON objeto TO usuario [WITH GRANT OPTION];`  
*Ejemplos por nivel:*

* Global (todas las BD):

`GRANT ALL PRIVILEGES ON *.* TO 'usuario'@'localhost';`  
`GRANT SELECT, UPDATE ON *.* TO 'usuario'@'localhost';`

* Base de datos:

`GRANT ALL ON basedatos.* TO 'usuario'@'localhost';`  
`GRANT SELECT, INSERT ON basedatos.* TO 'usuario'@'localhost';`

* Tabla:

`GRANT ALL ON basedatos.tabla TO 'usuario'@'localhost';`  
`GRANT SELECT, INSERT ON basedatos.tabla TO 'usuario'@'localhost';`

* Columna:

`GRANT SELECT (col1), INSERT (col1, col2)`   
`ON basedatos.tabla TO 'usuario'@'localhost';`

Privilegios comunes:

* **USAGE** (para usar un objeto específico de la base de datos)  
* **SELECT, INSERT, UPDATE, DELETE**  
* **CREATE, ALTER, DROP**  
* **INDEX, REFERENCES, TRIGGER**  
* **GRANT OPTION** (permite delegar permisos, permiso de concesión)

## Comando REVOKE

*Sintaxis:*  
`REVOKE privilegios ON objeto FROM usuario;`  
`REVOKE [GRANT OPTION FOR] privilegios ON objeto`  
`FROM usuarios { RESTRICT | CASCADE };`  
`RESTRICT` cuando existe en un solo objeto  
`CASCADE` por si acaso el usuario ha dado permisos a otro usuario (este caso si se usa `RESTRICT` daría fallo)

*Ejemplos:*  
`REVOKE ALL PRIVILEGES, GRANT OPTION FROM usuario;` Revoca todos los privilegios globalmente:  
`REVOKE ALL ON basedatos.* FROM 'usuario'@'localhost';`  
`REVOKE INSERT ON basedatos.tabla FROM 'usuario'@'localhost';`

**Ojo como revocas permisos.**  
Si has dado permisos por tabla:  
GRANT SELECT, INSERT, UPDATE ON pedidos.pedido, pedidos.lineapedido TO 'vendedor'@'localhost';  
GRANT SELECT ON pedidos.articulo TO 'vendedor'@'localhost';  
No puedes usar:  
REVOKE ALL ON pedidos TO 'vendedor'@'localhost';  
No puedes revocar los permisos sobre la BD, debes revocar por cada tabla:  
REVOKE ALL ON pedidos.pedido FROM 'vendedor'@'localhost';  
REVOKE ALL ON pedidos.lineapedido FROM 'vendedor'@'localhost';  
REVOKE ALL ON pedidos.articulo FROM 'vendedor'@'localhost';  
O globalmente con:  
REVOKE ALL PRIVILEGES, GRANT OPTION FROM 'vendedor'@'localhost';

## Roles

* Crear rol:

`CREATE ROLE nombre_rol [IDENTIFIED BY 'clave'];`

* Asignar privilegios al rol:

`GRANT CREATE TABLE TO nombre_rol;`

* Asignar rol a usuario:

`GRANT nombre_rol TO usuario [WITH GRANT OPTION];`

* Revocar privilegio del rol:

`REVOKE CREATE TABLE FROM nombre_rol;`

* Eliminar rol:

`DROP ROLE nombre_rol;`

* Activar rol:  
  * Tener un rol asignado ≠ Tener el rol activo  
  * Al conectarse, los roles NO se activan automáticamente (por seguridad)  
  * SET DEFAULT ROLE configura qué roles se activan al conectar

`SET DEFAULT ROLE nombre_rol FOR 'nombre_usuario'@'localhost';`  
SET DEFAULT ROLE es el "interruptor automático" que activa los permisos cuando el usuario se conecta. Sin él, los permisos existen pero están "apagados".

## Gestión de Procesos como DBA

Ver variables del servidor:  
`mysqladmin -u root -p variables`  
Ver procesos en ejecución:  
`mysqladmin -u root -p processlist`  
Ejecutar scripts SQL:

* Desde consola MySQL:

`source /ruta/script.sql`

* Desde línea de comandos:

`mysql -u usuario -p < script.sql`  
`mysql -u usuario -p < script.sql > resultado.txt`  
---

# T1-U3.2 – Acceso a la Información: Vistas

## Concepto de Vista

* **Vista**: Tabla virtual generada a partir de consultas sobre una o más tablas base.  
* **Características**:  
  * Misma estructura de filas/columnas que una tabla física  
  * No tiene correspondencia física  
  * Se puede consultar como tabla normal  
  * Las actualizaciones se transfieren a las tablas base (con limitaciones)  
  * Se almacena en la BD

## Sintaxis de Vistas

* Crear vista:

`CREATE [OR REPLACE] VIEW nombre_vista [(lista_columnas)] AS consulta`  
`[WITH CHECK OPTION];`

* Eliminar vista:

`DROP VIEW nombre_vista;`

* Mostrar definición (código) de vista:

`SHOW CREATE VIEW nombre_vista;`

* Mostrar estructura (campos) de vista:

`DESC VIEW nombre_vista; DESCRIBE`

* Listar vistas existentes:

`SHOW FULL TABLES WHERE table_type = 'VIEW';`

## Tipos de Vistas

* Vista Horizontal (filtra registros):

`CREATE OR REPLACE VIEW empleados_horizontal AS`  
`SELECT * FROM empleados`   
`WHERE job_id = 'PROGRAMADORES'`  
`WITH CHECK OPTION;`

* Vista Vertical (selecciona campos):

`CREATE OR REPLACE VIEW empleados_vertical AS`  
`SELECT id_empleado, nombre FROM empleados;`

* Vista Mixta (filtra registros y selecciona campos):

`CREATE OR REPLACE VIEW empleados_mixta AS`  
`SELECT id_empleado, nombre FROM empleados`   
`WHERE job_id = 'PROGRAMADORES'`  
`WITH CHECK OPTION;`

## Aplicaciones de las Vistas

* **Reutilización de consultas complejas**  
* **Mecanismo de seguridad** (mostrar solo datos específicos)  
* **Creación de esquemas externos**  
* **Simplificación** para usuarios finales  
* **Mantenimiento** de integridad en aplicaciones

## Ejemplos Prácticos

**Ejemplo 1:** Vista básica con filtro

`CREATE VIEW clientes_mexico AS`

`SELECT nombre_cliente, nombre_contacto` 

`FROM clientes` 

`WHERE pais = 'Mexico';`

**Ejemplo 2:** Vista con renombrado de columnas

`CREATE VIEW clientes_mexico (cliente, contacto) AS`

`SELECT nombre_cliente, nombre_contacto` 

`FROM clientes` 

`WHERE pais = 'Mexico';`

**Ejemplo 3:** Vista con JOIN y agregación  
`CREATE OR REPLACE VIEW resumen_pedidos (idpedido, fecha, cliente, total) AS`  
`SELECT`   
    `pedidos.id_pedido,`  
    `pedidos.fecha_pedido,`  
    `clientes.nombre_cliente,`  
    `SUM(detalle_pedidos.cantidad * detalle_pedidos.precio_unidad) AS total`  
`FROM clientes`   
`INNER JOIN pedidos ON clientes.codigo_cli = pedidos.codigo_cli`  
`INNER JOIN detalle_pedidos ON pedidos.id_pedido = detalle_pedidos.id_pedido`  
`GROUP BY pedidos.id_pedido;`

**Ejemplo 4:** Vista con WITH CHECK OPTION  
`CREATE VIEW vista_empleados AS`  
`SELECT apellido, nombre, sexo, seccion`   
`FROM empleados`   
`WHERE seccion = 'Administracion'`  
`WITH CHECK OPTION;`  
**WITH CHECK OPTION**:   
Impide modificaciones que excluyan registros de la vista. Es decir, no podemos modificar el campo "seccion" porque al hacerlo, tal registro ya no aparecería en la vista.Pero, sí podemos actualizar los demás campos.

## Restricciones en Vistas

* No puede contener subconsultas en FROM  
* No puede referenciar variables del sistema/usuario  
* No puede referenciar parámetros de sentencias preparadas  
* No puede hacer referencia a tablas TEMPORARY  
* No se pueden asociar triggers con vistas  
* Las tablas mencionadas deben existir siempre

## Ventajas de las Vistas

* Reusabilidad:  
  * Evita escribir consultas complejas repetidamente  
  * Mejora el rendimiento  
* Seguridad:  
  * Control de acceso a datos específicos  
  * Oculta datos confidenciales  
* Mantenimiento:  
  * Aislamiento de cambios estructurales  
  * Entorno de pruebas seguro  
* Integridad:  
  * Las aplicaciones no se rompen con cambios en BD

## Desventajas de las Vistas

* Dependencia estructural:  
  * Si se elimina un atributo usado en la vista, esta deja de funcionar  
*  Rendimiento:  
  * Consultas complejas no optimizadas consumen recursos  
  * Los usuarios no son conscientes de la complejidad subyacente

## Actualización de Vistas

* Vistas Actualizables:  
  * Se pueden usar INSERT, UPDATE, DELETE  
  * Deben contener todas las columnas NOT NULL de las tablas base  
  * Idealmente no usar para actualizaciones frecuentes  
* Vistas NO Actualizables (cuando contienen):  
  * Operadores de conjuntos (UNION, INTERSECT)  
  * DISTINCT  
  * Funciones de agregación (SUM, AVG, etc.)  
  * GROUP BY

---

# T1-U4.1 – Programación de bases de datos: conceptos iniciales

## Introducción

- Programar en una base de datos permite interactuar con ella de manera estructurada y eficiente.  
- Depende de:  
  - Tecnología (MySQL, Oracle, SQL Server, etc.)  
  - Compiladores disponibles  
  - Objetivos y naturaleza de la aplicación

### PL/SQL

- PL \= Procedural Language  
- Lenguaje de programación procedimental que extiende SQL.  
- Incluye:  
  - Variables y tipos de datos  
  - Estructuras de control (bucles, condiciones)  
  - Procedimientos, funciones, triggers  
- Los programas se almacenan en la base de datos y son reutilizables.

### Opciones de Programación

- **Procedimientos**: Ejecutados en el servidor con `CALL`, admiten parámetros.  
- **Funciones**: Retornan un valor escalar, se invocan dentro de comandos.  
- **Triggers**: Se ejecutan automáticamente ante eventos (INSERT, UPDATE, DELETE).

**Sintaxis:**  
`DELIMITER`  
`//`  
`CREATE [PROCEDURE|TRIGGER|FUNCTION] NOMBRE_PROGRAMA(PARAMETROS)`  
`BEGIN`  
`DECLARE VARIABLE1 TIPO_DATO [DEFAULT VALOR] [NOT NULL];`  
`DECLARE VARIABLE2 TIPO_DATO [DEFAULT VALOR] [NOT NULL];`  
`...`  
`DECLARE VARIABLEN TIPO_DATO [DEFAULT VALOR] [NOT NULL];`  
`SENTENCIAS DEL PROGRAMA;`  
`...`  
`END;`  
`//`  
`DELIMITER ;`

**Llamada:**  
`CALL NOMBRE_PROGRAMA(PARAMETROS)`

## Identificadores

- Nombres de objetos (variables, constantes, procedimientos, etc.).  
- Reglas:  
  - Longitud: 1 a 30 caracteres  
  - Primer carácter: letra  
  - Caracteres permitidos: letras, números, `$`, `#`, `_`  
  - No permitidos: espacios, signos de puntuación

**Ejemplos válidos**: `V_`, `AH$`, `X2`, `v_num`  
**No válidos**: `_V`, `#A`, `2X`, `v_num.`

## Variables

- Almacenan valores que pueden cambiar durante la ejecución.  
- Se declaran antes de usarse.

### Variables LOCALES (DECLARE)

Solo visible dentro del bloque donde se declara.  
Existe solo durante la ejecución del bloque.

`DECLARE nombre_variable tipo_dato [NOT NULL] [DEFAULT valor];`

*Ejemplos:*  
`DECLARE Importe INTEGER(4);`  
`DECLARE contador DECIMAL(2,0) DEFAULT 0;`  
`DECLARE nombre CHAR(20) NOT NULL DEFAULT 'MIGUEL';`

### Variables de SESIÓN/USUARIO (SET @variable)

Visible en toda la sesión de conexión actual.  
Existe hasta que cierres la conexión.

`SET @v_inversion:=1000;`  
`DELIMITER //`  
`CREATE PROCEDURE beneficio(INOUT v_inversion DECIMAL, IN v_anyos INTEGER, IN v_interes DECIMAL)`  
`BEGIN`  
`SET v_inversion:= v_inversion + (v_inversion*v_anyos*v_interes)/100;`  
`END;`  
`//`  
`DELIMITER ;`

`CALL beneficio(@v_inversion,2,10);`  
`SELECT @v_inversion; /* Para ver el resultado*/`

**Atributos:** 

- **`%TYPE` / `TYPE OF`**: Declara una variable con el tipo de otra variable o columna.  
  `nombre_variable TYPE OF objeto_original;`  
  `nombre_variable objeto_original%TYPE;`  
  `DECLARE total TYPE OF importe;`  
- **`%ROWTYPE` / `ROW TYPE OF`**: Declara una variable con la estructura de una tabla.  
  `nombre_variable ROW TYPE OF tabla_original;`  
  `nombre_variable tabla_original%ROWTYPE;`  
  `DECLARE moroso ROW TYPE OF clientes;`

## Constantes y Literales

- **Constantes**: Valores fijos durante la ejecución.  
  `nombre CONSTANT tipo_dato DEFAULT valor;`  
  `IVA CONSTANT DECIMAL(10,2) DEFAULT 0.21;`  
- **Literales**: Valores constantes escritos directamente:  
  - Cadenas: `'Hola'`  
  - Números: `123`, `45.67`  
  - Booleanos: `TRUE`, `FALSE`, `NULL`  
  - Fechas: `DATE '2023-10-05'`

## Operadores y Delimitadores

- **Operadores**:  
  - Asignación: `:=`  
  - Concatenación: `||`  
  - Comparación: `=`, `!=`, `<`, `>, <=, >=, IN, IS NULL, LIKE, BETWEEN`, etc.  
  - Aritméticos: `+`, `-`, `*`, `/`  
  - Lógicos: `AND`, `OR`, `NOT`


- **Delimitadores**:  
  - `()` para expresiones  
  - `''` para cadenas  
  - `<< >>` Etiquetas  
  - `:` Indicador de variables de transferencia  
  - `;` fin de instrucción  
  - `--` y `/* */` para comentarios

Al igual que en otros lenguajes de programación existe orden de precedencia en los operadores que se puede saltar usando paréntesis.

## Comentarios

- Documentan el código.  
- **De una línea**: `-- comentario`  
- **De varias líneas**: `/* comentario */`

## Estructuras de Control Alternativas

- Permiten ejecutar bloques de código según condiciones.

### IF-THEN-ELSE

`IF condicion THEN`  
    `sentencias;`  
`ELSEIF otra_condicion THEN`  
    `sentencias;`  
`ELSE`  
    `sentencias;`  
`END IF;`

*Ejemplo:*  
`BEGIN`  
`SELECT oficio INTO v_oficio FROM empleados`  
`WHERE emp_no = v_empleado_no;`  
`IF v_oficio = 'PRESIDENTE' THEN -- alternativa simple`  
`v_aumento := 30;`  
`END IF;`  
`SELECT COUNT(*) into v_c_empleados FROM empleados`  
`WHERE director = v_empleado_no;`  
`IF v_c_empleados = 0 THEN -- alternativa múltiple`  
`anidada`  
`v_aumento := v_aumento + 50;`  
`ELSIF v_c_empleados = 1 THEN`  
`v_aumento := v_aumento + 80;`  
`ELSIF v_c_empleados = 2 THEN`  
`v_aumento := v_aumento + 100;`  
`ELSE`  
`v_aumento := v_aumento + 110;`  
`END IF;`  
`UPDATE empleados SET salario = salario + v_aumento WHERE emp_no = v_empleado_no;`  
`END;`

### CASE

`CASE variable`  
    `WHEN valor1 THEN sentencias;`  
    `WHEN valor2 THEN sentencias;`  
    `ELSE sentencias;`  
`END CASE;`

*Ejemplo:*  
`CASE`  
`WHEN num_empleados = 1 THEN`  
`SET v_aumento := v_aumento + 50;`  
`WHEN num _empleados = 2 THEN`  
`SET v_aumento := v_aumento + 80;`  
`WHEN num _empleados = 3 THEN`  
`SET v_aumento := v_aumento + 100;`  
`ELSE`  
`SET v_aumento := v_aumento + 110;`  
`END CASE;`

## Estructuras de Control Repetitivas (Bucles)

- **WHILE**: Ejecuta mientras se cumpla la condición (puede no ejecutarse nunca).  
  `WHILE condicion DO`   
      `lista_sentencias;`  
  `END WHILE;`  
- **REPEAT**: Ejecuta hasta que se cumpla la condición (al menos una vez). Inversa de WHILE. La condición para, mientras que en WHILE ejecuta.  
  `REPEAT`  
      `lista_sentencias;`  
  `UNTIL condicion`  
  `END REPEAT;`  
- **LOOP**: Bucle infinito, requiere `LEAVE` para salir.  
  `etiqueta: LOOP`  
      `lista_sentencias;`  
      `IF condicion THEN`  
          `LEAVE etiqueta;`  
      `END IF;`  
      `lista_sentencias;`  
  `END LOOP etiqueta;`  
- **FOR**: Ejecuta un número conocido de veces.  
  `FOR variable IN val_inicial..val_final`  
      `DO`  
      `lista_sentencias;`  
  `END FOR;`  
- **FOR inverso**: comenzará el bucle por el valor especificado en segundo lugar val\_final e irá restando una unidad en cada iteración hasta llegar a val\_inicial.  
  `FOR variable IN REVERSE val_inicial..val_final`  
      `DO`  
      `lista_sentencias;`  
  `END FOR;`  
- **EXIT** Puede ser utilizada en los IF y en las estructuras repetitivas para salir de ellas en mitad de las sentencias.  
  `LOOP`  
      `instrucciones;`  
      `IF <condicion> THEN`  
          `EXIT;`  
      `END IF;`  
  `END LOOP;`

*Ejemplo de WHILE:*

`WHILE numero <= 10 DO`  
    `SELECT numero;`  
    `SET numero = numero + 1;`  
`END WHILE;`

## Etiquetas

- Marcan bloques de código para referenciarlos.  
- Sintaxis: `etiqueta:`

## Estructuras de Salto (Control de Flujo)

- **LEAVE**: Sale de un bucle o bloque etiquetado.   
- **ITERATE**: Para y vuelve al inicio del bucle.  
- **GOTO(Oracle)**: Salto a una etiqueta (no recomendado).

*Ejemplo con LEAVE:*

`etiqueta: LOOP`  
    `IF condición THEN`  
        `LEAVE etiqueta;`  
    `END IF;`  
`END LOOP etiqueta;`

*Ejemplo con REPEAT / LEAVE /ITERATE:*

`CREATE PROCEDURE repetir (param1 INT)`  
`BEGIN`  
    `etiqueta1: REPEAT`  
        `SET param1 = param1 + 1;`  
        `IF param1 < 20 THEN`  
            `ITERATE etiqueta1;`  
        `END IF;`  
        `LEAVE etiqueta1;`  
    `END REPEAT etiqueta1;`  
    `SELECT @param1;`  
`END;`

---

## Subprogramas

Los subprogramas son bloques de sentencias PL/SQL con nombre, que pueden recibir y/o devolver valores.  
Se almacenan en la base de datos y se ejecutan invocándolos desde otros programas o herramientas.

### Ventajas:

- Mejoran el rendimiento al enviar bloques completos al servidor.  
- Reducen operaciones de E/S.  
- Facilitan la reutilización del código.

### Tipos de subprogramas:

- **Procedimientos (Procedures)**  
- **Funciones (Functions)**  
- **Disparadores (Triggers)**

### Bloques de programación

La unidad básica en PL/SQL es el **bloque**. Pueden ser:

- **Anidados** (un bloque dentro de otro)  
- **Con nombre** (procedimientos, funciones, paquetes, triggers)  
- **Anónimos** (sin nombre, no se almacenan)

#### Bloques anónimos:

- Se construyen dinámicamente y se ejecutan una vez.  
- No se guardan en el servidor.  
- Comienzan con `DECLARE`.  
- **Solo existen en Oracle SQL**, no en MySQL.  
- Se carga en el buffer SQL y se ejecuta con `/` o `RUN`.

*Ejemplo de bloque anónimo (Oracle):*  
`DECLARE`   
  `v_precio NUMBER(8);`  
`BEGIN`  
  `SELECT PRECIO_ACTUAL INTO v_precio`   
  `FROM productos`   
  `WHERE PRODUCTO_NO = 70;`  
  `DBMS_OUTPUT.PUT_LINE(v_precio);`  
`END;`

##### Comandos útiles para bloques anónimos:

- `SAVE nombrefichero [REPLACE]` → Guarda el bloque en un archivo.  
- `GET nombrefichero` → Carga un bloque desde un archivo al buffer.  
- `START nombrefichero` o `@nombrefichero` → Carga y ejecuta.

#### Bloques nominados (named blocks):

- Similares a los bloques anónimos, pero se les asigna un nombre concreto.  
- Pueden ser procedimientos o funciones.

### Estructura de un programa

Todo subprograma consta de:  
**A. Cabecera (PROCEDURE):**

- Nombre del subprograma.  
- Parámetros (opcional).  
- Tipo de retorno (sólo para funciones).

**B. Cuerpo:**

1. **Declaraciones (DECLARE):** Variables locales.  
2. **Instrucciones ejecutables (BEGIN … END):** Lógica del programa.  
3. **Manejo de excepciones (EXCEPTION):** Opcional.

---

# T2-U4.2 – Procedimientos

Conjunto de instrucciones almacenadas con un nombre.  
Pueden tener parámetros de entrada y no es obligatorio que devuelvan valores.

**Sintaxis:**  
`DELIMITER //`  
`CREATE [OR REPLACE] PROCEDURE nombre_procedimiento`  
  `([param1 tipo1, param2 tipo2...])`  
`BEGIN`  
  `-- Cuerpo del procedimiento`  
`END //`  
`DELIMITER ;`

**Ejecución:**  
`CALL nombre_procedimiento(param1, param2...);`  
\-- o  
`EXECUTE nombre_procedimiento(param1, param2...)`;

### Parámetros de un procedimiento

Los parámetros son las variables  
Sintaxis: `modo(opcional: IN|OUT|INOUT) nombre tipo`

### Tipos de parámetros:

- **IN (entrada):** Valor se pasa al procedimiento (por defecto).  
- **OUT (salida):** El procedimiento devuelve un valor por este parámetro.  
- **INOUT (entrada/salida):** Permite entrada y modificación.

*Ejemplo con IN:*  
`CREATE PROCEDURE productoscaros(IN precio DECIMAL(5,2))`  
`BEGIN`  
  `SELECT * FROM articulo WHERE pvpart > precio;`  
`END;`  
`CALL productoscaros(100.0);`

*Ejemplo con OUT:*  
`CREATE PROCEDURE contararticli`  
  `(IN v_clicod VARCHAR(8), IN v_artcod VARCHAR(8), OUT v_cliuds INT)`  
`BEGIN`  
  `SELECT sum(ctd) INTO v_cliuds`   
  `FROM facturas`   
  `WHERE codc = v_clicod AND coda = v_artcod;`  
`END;`  
`CALL contararticli(’C9’,’A8’,@vtas);`  
`SELECT @vtas;`

*Ejemplo con INOUT:*  
`CREATE PROCEDURE beneficio`  
  `(INOUT v_inversion DECIMAL, IN v_anyos INTEGER, IN v_interes DECIMAL)`  
`BEGIN`  
  `SET v_inversion := v_inversion + (v_inversion * v_anyos * v_interes) / 100;`  
`END;`

## Operaciones con procedimientos

- **Ver código de un procedimiento:**  
  `SHOW CREATE PROCEDURE nombre_procedimiento;`  
- **Eliminar procedimiento:**  
  `DROP PROCEDURE nombre_procedimiento;`  
- **Listar procedimientos de una BD:**  
  `SHOW PROCEDURE STATUS WHERE Db = 'nombre_bd';`  
  \-- o  
  `SELECT name, type, definer FROM MYSQL.PROC WHERE DB = 'base_datos';`

## Procedimientos anidados

Un procedimiento puede llamar a otro.  
Se invoca con: `nombre_procedimiento(lista_parametros);`  
El control pasa al procedimiento llamado y retorna al punto siguiente tras su ejecución.

*Ejemplo de anidación:*

`DECLARE`  
  `...`  
`BEGIN`  
  `...`  
  `DECLARE -- Bloque interior`  
  `BEGIN`  
    `...`  
  `EXCEPTION`  
    `...`  
  `END;`  
  `...`  
`EXCEPTION`  
  `...`  
`END;`

---

# T2-U4.3 – Funciones

Similar a un procedimiento, pero **siempre devuelve un único valor** (obligatorio con `RETURN`).  
La lista de parámetros de entrada es opcional, pero sí que es obligatorio el uso de  
la cláusula RETURN que incluye uno y sólo un parámetro de salida.

### Sintaxis

`CREATE FUNCTION nombre_funcion (param tipo)`  
`RETURNS tipo_dato`  
`BEGIN`  
    `-- instrucciones`  
    `RETURN valor;`  
`END;`

### Ejecución

`SELECT nombre_funcion(param);`

### Ejemplo

`CREATE FUNCTION cuadrado (numero SMALLINT) RETURNS INTEGER`  
`BEGIN`  
    `RETURN numero * numero;`  
`END;`

### Ejemplo con condiciones

`CREATE FUNCTION calificacion(nota INTEGER) RETURNS CHAR(10)`  
`BEGIN`  
    `IF nota >= 5 THEN`  
        `RETURN 'APROBADO';`  
    `ELSE`  
        `RETURN 'SUSPENSO';`  
    `END IF;`  
`END;`

### Consultar funciones

`SHOW FUNCTION STATUS WHERE Db = 'nombre_bbdd';`

### Eliminar funciones

`DROP FUNCTION IF EXISTS nombrefuncion;`

### Devolver string

En funciones se hace concatenando  
`CONCAT('El total de pagos es: ', pagos_total);`  
frente a procedimientos que es con SELECT  
`SELECT v_i AS numero, 'Número impar' AS mensaje;`   
---

# T3-U4.4 – Triggers (disparadores)

Bloques de programa que se ejecutan **automáticamente** ante un evento en una tabla:

- **INSERT**  
- **UPDATE**  
- **DELETE**

### Sintaxis (MySQL/MariaDB)

`CREATE TRIGGER nombre_trigger`  
`[BEFORE | AFTER] [INSERT | UPDATE | DELETE]`  
`ON nombre_tabla`  
`FOR EACH ROW`  
`BEGIN`  
    `-- instrucciones`  
`END;`

### Pseudorregistros

- **NEW**: contiene los nuevos valores (en INSERT y UPDATE).  
- **OLD**: contiene los valores antiguos (en UPDATE y DELETE).

### Ejemplo básico

`CREATE TRIGGER audita_borra_empleado`  
`BEFORE DELETE ON empleado`  
`FOR EACH ROW`  
`BEGIN`  
    `SELECT CONCAT('Borrando empleado ', OLD.emp_no, ' - ', OLD.apellido);`  
`END;`

### Ejemplo con validación

`CREATE TRIGGER disparador1`  
`BEFORE INSERT ON alumnos`  
`FOR EACH ROW`  
`BEGIN`  
    `IF NEW.nota < 0 THEN`  
        `SET NEW.nota = 0;`  
    `ELSEIF NEW.nota > 10 THEN`  
        `SET NEW.nota = 10;`  
    `END IF;`  
`END;`

### Ejemplo con actualización automática

`CREATE TRIGGER actualizarPrecio`  
`BEFORE UPDATE ON productos`  
`FOR EACH ROW`  
`BEGIN`  
    `IF NEW.costo <> OLD.costo THEN`  
        `SET NEW.pvp = NEW.costo * 2;`  
    `END IF;`  
`END;`

### Consultar triggers

`SHOW TRIGGERS;`  
SHOW CREATE TRIGGER nombre\_trigger\\G;

**Nota extra**  
**; (Punto y coma):** Muestra los resultados en el formato clásico de tabla (formato tabular). Para tablas con muchas columnas, esto puede ser difícil de leer porque las filas se cortan o se envuelven de manera confusa.

**\\G (Backslash-G):** Muestra los resultados en formato vertical. Cada columna de la fila se muestra en una línea separada con su nombre y valor. Esto es increíblemente útil cuando una fila tiene muchas columnas o cuando el contenido de las columnas es largo (como en el caso del cuerpo de un TRIGGER, una VISTA o un PROCEDIMIENTO).

---

# T3-U4.5 – Cursores

## ¿Qué es un cursor?

Un **cursor** es una estructura de control que permite recorrer **fila por fila** los resultados de una consulta `SELECT` que devuelve múltiples registros.

**Técnicamente**: Es un área de memoria privada donde se almacena la información resultante de ejecutar una sentencia `SELECT`.

### Propiedades de los cursores:

- Se utilizan dentro de **procedimientos almacenados**, funciones, etc.  
- La sentencia `SELECT` **no puede contener INTO** (en cursores explícitos).  
- En MySQL/MariaDB:  
  - Son **de solo lectura** (READ ONLY).  
  - Son **no desplazables** (nonscrollable): solo se recorren hacia adelante.  
  - La declaración del cursor debe ir **después** de las variables y **antes** de los manejadores (handlers).

## Tipos de cursores

| Tipo | Descripción |
| :---- | :---- |
| **Implícitos** | Usados cuando la consulta devuelve **una sola fila**. Usan `SELECT ... INTO`. |
| **Explícitos** | Declarados y controlados por el programador. Usados cuando la consulta devuelve **múltiples filas**. Más rápidos. |

## Cursores implícitos

### Características

- No se declaran.  
- Solo pueden devolver **una fila**.  
- Si devuelven más de una fila → excepción `TOO_MANY_ROWS`.  
- Si no devuelven ninguna fila → excepción `NO_DATA_FOUND`.

### Sintaxis básica

`SELECT columna1, columna2 INTO variable1, variable2 FROM tabla WHERE condición;`

### Atributos de cursores implícitos (Oracle)

Se antepone `SQL` al atributo:

| Atributo | Significado |
| :---- | :---- |
| `SQL%NOTFOUND` | La última operación no afectó a ninguna fila |
| `SQL%FOUND` | La última operación afectó a una o más filas |
| `SQL%ROWCOUNT` | Número de filas afectadas |
| `SQL%ISOPEN` | Siempre falso (Oracle cierra automáticamente) |

### Ejemplo (Oracle)

`DECLARE`  
  `v_ape VARCHAR2(10);`  
  `v_oficio VARCHAR2(10);`  
`BEGIN`  
  `SELECT apellido, oficio INTO v_ape, v_oficio`  
  `FROM EMPLE WHERE EMP_NO = '7900';`  
  `DBMS_OUTPUT.PUT_LINE(v_ape || '*' || v_oficio);`  
`END;`

### Ejemplo (MariaDB)

`BEGIN`  
  `DECLARE c_hotel TYPE ROW OF hotel;`  
  `SELECT * INTO c_hotel FROM HOTEL WHERE ID = 99;`  
  `SELECT('Cód. Hotel: ', c_hotel.ID);`  
  `SELECT('Habitaciones: ', c_hotel.NHABS);`  
`END;`

## Cursores explícitos

### Pasos para trabajar con ellos

1. **Declarar** el cursor  
2. **Abrir** el cursor  
3. **Recuperar** filas (FETCH)  
4. **Cerrar** el cursor

### Sintaxis

**Declarar:**  
`DECLARE nombre_cursor CURSOR FOR SELECT ...;`

\-- Con parámetros:  
`DECLARE nombre_cursor(param1 tipo1, ...) CURSOR FOR SELECT ...;`

**Abrir:**  
`OPEN nombre_cursor;`

**Recuperar:**  
`FETCH nombre_cursor INTO variable1, variable2, ...;`

**Cerrar:**  
`CLOSE nombre_cursor;`

### Control de fin de cursor

Cuando no quedan filas, se lanza un error `NOT FOUND` (SQLSTATE '02000').  
Se debe declarar un **manejador (handler)** para controlarlo:  
`DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin = 1;`

### Ejemplo completo (MariaDB con REPEAT)

`CREATE PROCEDURE prueba()`  
`BEGIN`  
  `DECLARE v_cod VARCHAR(10);`  
  `DECLARE v_nha INTEGER(4);`  
  `DECLARE fin INTEGER DEFAULT 0;`  
  `DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin = 1;`  
  `DECLARE c_hotel CURSOR FOR SELECT * FROM Hotel;`  
  `OPEN c_hotel;`  
  `REPEAT`  
    `FETCH c_hotel INTO v_cod, v_nha;`  
    `IF NOT fin THEN`  
      `SELECT CONCAT('Cod. Hotel: ', v_cod);`  
      `SELECT CONCAT('Habitaciones: ', v_nha);`  
    `END IF;`  
  `UNTIL fin END REPEAT;`  
  `CLOSE c_hotel;`  
`END;`

### Ejemplo con actualización de precios

`CREATE PROCEDURE actualiza_precios()`  
`BEGIN`  
  `DECLARE cod integer;`  
  `DECLARE tip varchar(25);`  
  `DECLARE pre decimal(8,2);`  
  `DECLARE fin INTEGER DEFAULT 0;`  
  `DECLARE nombre_cursor CURSOR FOR SELECT codigo, tipo, precio FROM ARTICULOS;`  
  `DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin = 1;`  
  `OPEN nombre_cursor;`  
  `REPEAT`  
    `FETCH nombre_cursor INTO cod, tip, pre;`  
    `IF NOT fin THEN`  
      `IF tip = 'B' THEN`  
        `UPDATE ARTICULOS SET precio = 0.9 * precio WHERE codigo = cod;`  
      `ELSEIF tip = 'M' THEN`  
        `UPDATE ARTICULOS SET precio = 1.05 * precio WHERE codigo = cod;`  
      `ELSEIF pre > 10 THEN`  
        `UPDATE ARTICULOS SET precio = 1.5 * precio WHERE codigo = cod;`  
      `END IF;`  
    `END IF;`  
  `UNTIL fin END REPEAT;`  
  `CLOSE nombre_cursor;`  
`END;`

## CONSIDERACIONES IMPORTANTES

- Un cursor **cerrado** no se puede leer.  
- Al cerrar un cursor se libera memoria del servidor.  
- Si no se cierra, la siguiente apertura puede dar error `CURSOR_ALREADY_OPEN`.  
- El nombre del cursor es un **identificador**, no una variable.

---

## COMPARATIVA: PROCEDIMIENTOS VS FUNCIONES VS CURSORES

| Característica | Procedimiento | Función |
| :---- | :---- | :---- |
| **Valor de retorno** | Puede devolver 0, 1 o varios resultados (con OUT/INOUT). | **Siempre devuelve 1 valor** (usando `RETURN`). |
| **Uso en sentencias SQL** | Se invoca con `CALL procedimiento()`. **No se puede usar directamente en un `SELECT`, `WHERE`, etc.** | Se invoca como parte de una expresión SQL, típicamente en un `SELECT`. Ej: `SELECT funcion() FROM tabla`. |
| **Parámetros** | Soporta `IN`, `OUT` e `INOUT`. | Solo soporta parámetros de tipo `IN` (aunque se declaran sin la palabra `IN` explícitamente). |
| **Sentencias DML** | **Sí**, puede realizar cualquier operación de modificación de datos: `INSERT`, `UPDATE`, `DELETE`. | **Muy restringido. Generalmente, NO debe usarse para modificar datos.** En MySQL, por defecto, si una función realiza un `INSERT`, `UPDATE` o `DELETE`, puede generar errores o comportarse de forma no determinística, lo que la hace insegura para la replicación. |
| **Sentencias DDL** | **Sí**, puede crear, alterar o dropear tablas y otros objetos (con ciertas limitaciones según el contexto). | Generalmente **NO** se permiten sentencias de definición de datos (`CREATE`, `ALTER`, `DROP`). |
| **Uso de transacciones** | **Sí**, puede usar `START TRANSACTION`, `COMMIT`, `ROLLBACK`. | **No puede** usar sentencias de control de transacciones. |
| **Llamada a procedimientos** | **Sí**, un procedimiento puede llamar a otro procedimiento o a una función. | **Sí**, una función puede llamar a otra función, pero **NO** puede llamar a un procedimiento. |
| **Cláusula `RETURN`** | Opcional. Se usa para salir del procedimiento anticipadamente, pero no devuelve un valor al llamante de esa forma. | **Obligatoria.** Debe devolver un único valor del tipo declarado. |
|  |  |  |
| **Objetivo principal** | Encapsular una secuencia de operaciones complejas, como lógica de negocio que afecta a la base de datos. | Calcular y devolver un valor que pueda ser usado en consultas SQL. |

### Resumen de lo que **NO** se puede hacer en una función:

1. **Modificar datos:** No puedes hacer `UPDATE` (aunque en MySQL es técnicamente posible bajo ciertas configuraciones, es una mala práctica y puede romper la replicación). Por defecto, una función es de solo lectura.  
2. **Modificar esquemas:** No puedes hacer `CREATE TABLE` o `ALTER TABLE`.  
3. **Controlar transacciones:** No puedes hacer `COMMIT` o `ROLLBACK` dentro de una función.  
4. **Llamar a procedimientos:** `CALL otro_procedimiento()` no está permitido.

---

# T3-U5.1 – Optimización del rendimiento: TCL

## INTRODUCCIÓN A LAS TRANSACCIONES

Una **transacción** es un conjunto de operaciones sobre la base de datos que se ejecutan como una **única unidad lógica de trabajo**.

**Principio fundamental**: O se realizan **todas** las operaciones o **ninguna**.

### Ejemplo clásico:

Transferencia bancaria entre dos cuentas:

- Descontar dinero de una cuenta.  
- Ingresar dinero en otra cuenta.

Ambas operaciones deben ejecutarse juntas; si una falla, la otra no debe realizarse.

### Control de transacciones (TCL):

El SGBD utiliza **TCL** (Transaction Control Language) para gestionar este comportamiento.

### Tipos de transacciones:

| Tipo | Descripción |
| :---- | :---- |
| **Explícitas** | Se indican inicio (`BEGIN TRANSACTION`) y fin (`COMMIT` o `ROLLBACK`) |
| **Implícitas** | Modo autocommit: cada sentencia se confirma automáticamente |

## PROPIEDADES ACID

En 1983, Reuter y Härder acuñaron el término **ACID** para describir las propiedades esenciales de las transacciones confiables (basadas en el trabajo de Jim Gray en 1970).

| Propiedad | Significado |
| :---- | :---- |
| **Atomicidad** | Todo o nada. Si una operación falla, se deshacen todas. |
| **Consistencia** | La base de datos pasa de un estado válido a otro estado válido. |
| **Aislamiento** | Las transacciones concurrentes no interfieren entre sí. |
| **Durabilidad** | Una vez confirmada, la transacción es permanente (incluso ante fallos). |

### Atomicidad (Atomicity)

- Una transacción es una unidad **indivisible**.  
- Si falla una parte, se retrocede (**rollback**) al estado inicial.  
- Se aplica incluso ante caídas del sistema, errores, etc.

### Consistencia (Consistency)

- La transacción respeta todas las reglas de la BD:  
  - Tipos de datos.  
  - Claves primarias y foráneas.  
  - Restricciones (`CONSTRAINT`).  
  - Disparadores (`TRIGGERS`), cascadas, etc.

### Aislamiento (Isolation)

- Las transacciones concurrentes no ven los estados intermedios de otras.  
- El resultado final debe ser el mismo que si se ejecutaran en serie.

### Durabilidad (Durability)

- Una vez hecho `COMMIT`, los cambios son **permanentes**.  
- Se utilizan mecanismos como el **log de transacciones** para garantizarlo.

## COMANDOS DE CONTROL DE TRANSACCIONES (TCL)

En MySQL, por defecto el **autocommit** está activado. Para trabajar con transacciones explícitas:

SET AUTOCOMMIT \= 0;   \-- Desactivar autocommit

#### Comandos principales:

| Comando | Función |
| :---- | :---- |
| `START TRANSACTION` o `BEGIN TRANSACTION` | Inicia una transacción explícita |
| `COMMIT` | Confirma todos los cambios desde el inicio de la transacción |
| `ROLLBACK` | Deshace todos los cambios desde el inicio de la transacción |
| `SAVEPOINT nombre` | Crea un punto intermedio dentro de la transacción |
| `ROLLBACK TO SAVEPOINT nombre` | Deshace solo los cambios posteriores al savepoint |

**Nota importante**: TCL solo se usa con `INSERT`, `DELETE`, `UPDATE`. No afecta a comandos DDL como `CREATE TABLE` o `TRUNCATE`, que se confirman automáticamente.

## EJEMPLOS

### COMMIT

**Escenario**: Dos conexiones a la misma BD.

#### Paso 1 – Estado inicial:

\+------+

| tId  |

\+------+

| 1    |

\+------+

#### Paso 2 – Conexión 1: inserta registros sin confirmar

`SET autocommit = 0;`  
`INSERT INTO testTable VALUES (2), (3);`  
`SELECT * FROM testTable;  -- Ve 1, 2, 3`

#### Paso 3 – Conexión 2: consulta la misma tabla

`SELECT * FROM testTable;  -- Solo ve 1 (aún no se ha hecho COMMIT)`

#### Paso 4 – Conexión 1: ejecuta COMMIT

`COMMIT;`

#### Paso 5 – Conexión 2: consulta de nuevo

`SELECT * FROM testTable;  -- Ahora ve 1, 2, 3`

### ROLLBACK

#### Sin COMMIT previo:

`INSERT INTO testTable VALUES (2), (3);`  
`ROLLBACK;`  
`SELECT * FROM testTable;  -- Solo aparece el registro 1`

#### Con COMMIT previo (ROLLBACK no tiene efecto):

`INSERT INTO testTable VALUES (2), (3);`  
`COMMIT;`  
`ROLLBACK;`  
`SELECT * FROM testTable;  -- Siguen apareciendo 1, 2, 3`

### SAVEPOINT (Transferencia bancaria)

`SET AUTOCOMMIT = 0;`  
`START TRANSACTION;`

`-- Descontar de la cuenta origen`  
`UPDATE CUENTAS SET SALDO = SALDO - IMPORTE WHERE NUMCUENTA = CuentaOrigen;`

`-- Registrar movimiento de salida`  
`INSERT INTO MOVIMIENTOS (CuentaOrigen, ...);`

`-- Punto de guardado`  
`SAVEPOINT Punto1;`

`-- Incrementar cuenta destino`  
`UPDATE CUENTAS SET SALDO = SALDO + IMPORTE WHERE NUMCUENTA = CuentaDestino;`

`-- Registrar movimiento de entrada`  
`INSERT INTO MOVIMIENTOS (CuentaDestino, ...);`

`-- Si hay error en el destino, deshacemos solo lo posterior al SAVEPOINT`  
`ROLLBACK TO SAVEPOINT Punto1;`

`-- Confirmamos la transacción (solo se aplicó el descuento, no el ingreso)`  
`COMMIT;`

## RESUMEN DE BUENAS PRÁCTICAS

- Desactivar `autocommit` cuando se requieran transacciones multipaso.  
- Usar `SAVEPOINT` para recuperaciones parciales.  
- Siempre cerrar transacciones con `COMMIT` o `ROLLBACK`.  
- Recordar que los comandos DDL (crear tablas, truncar, etc.) no se pueden deshacer con `ROLLBACK`.  
- El aislamiento evita que otras conexiones vean estados intermedios.

---

# T3-U5.2. Recuperación de errores: copias de seguridad

## INTRODUCCIÓN

Además de las sentencias `INSERT` y `REPLACE` para añadir registros, existen **herramientas para cargas masivas** de datos desde ficheros con formato predeterminado.

Estas herramientas permiten:

- **Importar** datos desde ficheros externos.  
- **Exportar** datos desde la base de datos a ficheros.  
- **Ejecutar scripts** SQL completos.  
- **Realizar copias de seguridad** (backups) periódicas.

## IMPORTACIÓN DE DATOS (LOAD DATA)

### Sintaxis completa

`LOAD DATA [LOCAL] INFILE 'fichero_con_datos'`  
`[REPLACE | IGNORE]`  
`INTO TABLE tabla`  
`[FIELDS`  
    `[TERMINATED BY '\t']`  
    `[[OPTIONALLY] ENCLOSED BY '']`  
    `[ESCAPED BY '\\']`  
`]`  
`[LINES`  
    `[STARTING BY '']`  
    `[TERMINATED BY '\n']`  
`]`  
`[IGNORE number LINES]`  
`[(col_name,...)];`

### Sintaxis abreviada

`LOAD DATA INFILE 'ruta_fichero\fichero' INTO TABLE tabla_destino;`

### Parámetros principales

| Parámetro | Descripción |
| :---- | :---- |
| `LOCAL` | El fichero está en el ordenador del cliente. Si no se usa, se busca en el servidor. |
| `REPLACE` | Sustituye filas existentes con misma clave primaria/única. |
| `IGNORE` | Ignora filas duplicadas. |
| `INTO TABLE` | Tabla destino donde se insertan los datos. |
| `IGNORE número LINES` | Omite las primeras N líneas (útil para cabeceras). |

### Cláusula FIELDS (formato de columnas)

| Opción | Significado |
| :---- | :---- |
| `TERMINATED BY 'carácter'` | Delimitador entre columnas (por defecto: tabulador `\t`). |
| `ENCLOSED BY 'carácter'` | Carácter de entrecomillado de columnas. Con `OPTIONALLY`, solo se aplica a texto y fechas. |
| `ESCAPED BY 'carácter'` | Carácter de escape (por defecto: `\`). |

### Cláusula LINES (formato de filas)

| Opción | Significado |
| :---- | :---- |
| `STARTING BY 'carácter'` | Carácter con el que comienza cada línea. |
| `TERMINATED BY 'carácter'` | Carácter de fin de línea (por defecto: `\n`). |

### Ejemplo completo

**Tabla destino:**

`CREATE TABLE PERSONAS (`  
    `COD INT(2) PRIMARY KEY,`  
    `NOMBRE VARCHAR(50) NOT NULL,`  
    `LOCALIDAD VARCHAR(50) DEFAULT 'Madrid',`  
    `FECHANAC DATE`  
`);`

**Fichero `datos.txt`:**  
`cod,nombre,localidad,fchanac`  
`21,Alfredo,Valencina,1972-07-20`  
`22,Laura,Ubeda,NULL`  
`23,Ines,,`

**Sentencia de carga:**  
`LOAD DATA LOCAL INFILE 'ruta_del_archivo/datos.txt'`  
`INTO TABLE personas`  
`FIELDS TERMINATED BY ','`  
`LINES TERMINATED BY '\n'`  
`IGNORE 1 LINES;`

## EXPORTACIÓN DE DATOS (SELECT INTO OUTFILE)

### Sintaxis básica

`SELECT * INTO OUTFILE 'ruta_fichero\fichero'`  
`FROM tabla_a_exportar;`

**Importante:**

- El fichero de salida **no debe existir** previamente.  
- Se requieren permisos de escritura en la ruta.

### Sintaxis completa con opciones

`SELECT columnas`  
`INTO OUTFILE 'fichero'`  
`[FIELDS`  
    `[TERMINATED BY '\t']`  
    `[[OPTIONALLY] ENCLOSED BY '']`  
    `[ESCAPED BY '\\']`  
`]`  
`[LINES`  
    `[STARTING BY '']`  
    `[TERMINATED BY '\n']`  
`]`  
`FROM tabla;`

### Ejemplo de exportación

`SELECT * FROM PERSONAS`  
`INTO OUTFILE 'carpeta_destino/exportar.txt'`  
`FIELDS TERMINATED BY ';'`  
`LINES TERMINATED BY '\n';`

## CARGA DE SCRIPTS SQL

Desde la consola de MySQL, una vez conectados, se pueden ejecutar ficheros `.sql` de dos formas:  
`mysql> source ruta_fichero/fichero.sql`  
o bien:  
`mysql> \. ruta_fichero/fichero.sql`

Esto ejecuta **todas las sentencias SQL** contenidas en el fichero (creación de tablas, inserciones, etc.).

## COPIAS DE SEGURIDAD (BACKUP)

En MySQL, destacan dos comandos principales:

| Comando | Características |
| :---- | :---- |
| `mysqldump` | Genera un fichero `.sql` con todas las sentencias necesarias para recrear la BD. |
| `mysqlhotcopy` | Copia física de los archivos de la BD (más rápido, pero menos portable). |

### mysqldump – Sintaxis general

`# Copia de una base de datos completa`  
`mysqldump -u root -p nombre_basededatos > fichero.sql`

`# Especificando contraseña directamente`  
`mysqldump -u root -pcontraseña nombre_basededatos > fichero.sql`

### Otras opciones de mysqldump

| Objetivo | Comando |
| :---- | :---- |
| Todas las bases de datos | `mysqldump -u root -p --all-databases > fichero.sql` |
| Varias BD específicas | `mysqldump -u root -p --databases bd1 bd2 > fichero.sql` |
| Una sola tabla | `mysqldump -u root -p basededatos tabla > fichero.sql` |

### Restauración desde un backup

Para restaurar una base de datos desde un fichero `.sql` generado con `mysqldump`:

`mysql -u root -p nombre_basededatos < fichero.sql`

### mysqlhotcopy (copias físicas)

**Requisitos de permisos:** `SELECT`, `LOCK TABLES`, `RELOAD`.  
**Sintaxis:**  
`mysqlhotcopy basededatos --user=usuario --password=mipassword /ruta_destino`

**Nota:** `mysqlhotcopy` solo funciona con tablas **MyISAM** (no con InnoDB). En la práctica, `mysqldump` es más universal y recomendado.

## RESUMEN DE COMANDOS ÚTILES

| Acción | Comando |
| :---- | :---- |
| Importar datos desde fichero | `LOAD DATA INFILE ... INTO TABLE ...` |
| Exportar datos a fichero | `SELECT ... INTO OUTFILE ...` |
| Ejecutar script SQL | `source fichero.sql` o `\. fichero.sql` |
| Backup completo de BD | `mysqldump -u root -p bd > backup.sql` |
| Restaurar backup | `mysql -u root -p bd < backup.sql` |

---

# T3-U6. Optimización y monitorización de una base de datos