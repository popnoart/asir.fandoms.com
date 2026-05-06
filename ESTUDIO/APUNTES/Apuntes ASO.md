## Apuntes — ASO 

## Administración de sistemas operativos

## ---

# Unidades

* T1 \- Clase 1: Conectar windows 10  
* T1 \- Clase 2: Permisos windows  
* T1 \- Clase 3: Comandos linux  
* T1 \- Clase 4: Bash, script consola linux  
* T1 \- Clase 5: Batch, script consola windows  
* T1 \- Clase 6: Repaso T1  
* T2 \- Clase 7: Powershell  
* T2 \- Clase 8: Repaso Enero  
* T2 \- Clase 9: Relación de confianza \+ Duda ip 127  
* T2 \- Clase 10: Compartir entre Windows y Ubuntu  
* T2 \- Clase 11: Repaso T2  
* T3 \- Clase 12: DNS & FTP  
* T3 \- Clase 13: Auditoria  
* T3 \- Clase 14: Auditoria: carpetas  
*   
*   
  ---

# T1-C1: Conectar windows 10

Clonar MV: acordarse de cambiar la MAC y nombre de equipo

## Conectar 2 windows 10 en red: 

* (Sólo MV) Adaptador de red virtualbox \=\> Red interna  
* Panel de control \> Centro de redes \> Cambiar configuración del adaptador de red \> Botón derecho Ethernet \> TCP/IPv4:  
* Cambiamos ip a p.e. 192.168.5.11 y puerta a 192.168.5.1

DNS 

* Panel de control \> Centro de redes \> Cambiar configuración de uso compartido avanzado:  
  Activar redes y uso compartido (públicas y todas las redes, en esta última desactivar el uso compartido con contraseña)  
* Consola: ipconfig y comprobar ip  
* Hacer ping entre ellas  
* Crear carpeta  \> botón derecho:  
  Compartir (por ahora con Todos, pero eso es EL MAL)  
* Uso compartido avanzado \> Marcar compartir \> Pulsar Permisos \> Dar control total al grupo Todos (EL MAL)

## Conectar  windows 10 al server: 

* Poner puerta de enlace y dns1 la ip del server  
* Panel de control \> Sistema \> Configuración avanzada del sistema \> Nombre de equipo \> Cambiar  
* Marcar dominio y poner el que pusiste en el server. Te pedirá usuario y contraseña, debes poner el Administrador del server.

---

# T1-C2 – Permisos windows

Los usuarios que se crean en Active Directory no pueden autenticarse en el propio Windows Server sin ser administrador, pueden autenticarse en remoto.

## Permisos sobre carpetas y sus archivos. Tarea X

* A primera vista solo hay permisos como Control total, Cambiar y Leer. Para permisos extra Botón derecho \> Propiedades \>Seguridad \> EDITAR  
  Seleccionar usuario \> Agregar \> Aceptar  
* Volvemos a la pestaña de Seguridad \>Opciones avanzadas \> Agregar \> Seleccionar entidad de seguridad \> Opciones avanzadas \> Seleccionas el usuario  
  En Tipo seleccionar Permitir o Denegar . Aparecen los permisos y tienes la opción de dar a Mostrar permisos avanzados. Ahí aparecen ya el resto(permiso no habituales) entre ellos el de Toma de posesión.  
* **Toma de posesión** es un permiso especial que permite a un usuario convertirse en el propietario de un archivo, carpeta o registro, incluso si no tiene permisos iniciales para acceder a él. El dueño original pierde los permisos.  
* **Compartir son permisos sobre red**, algo que compartes vía red.  
* **Seguridad son permisos locales.**  
* Entre ambos el más restrictivo es el que manda, da igual si accedes vía red o local.

---

# T1-C3 – Comandos de linux

## Permisos

* `ls -l` \- Visualiza contenido con permisos:  
  `-rw-rw-r--  1 usuario grupo 59259 sep 30 21:40 composer-setup.php`  
  `drwxr-xr-x  5 usuario grupo  4096 nov  2 08:50 Descargas`

### Estructura: 

* El primer carácter indica si es archivo `-` o directorio `d`  
* 3 siguientes Permisos Propietario ( u \- Owner) u de user  
* 3 siguientes Permisos Grupo ( g \- Group)  
* 3 siguientes Permisos Resto( o \- Others)

### Posibles permisos:

* `r` (read) \= `4`  
* `w` (write) \= `2`  
* `x` (execute) \= `1` 

Si no tiene permisos aparece `-`  
Ejecutar es abrir, copiar., ..

`drwxrwxrwx => 777` por tanto es acceso total  
`drwxr-xr-x => 755`  Owner total | Group Write & Execute | Others Write & Execute

## Comandos básicos

### Navegación y Localización

| Comando | Descripción |
| :---- | :---- |
| `pwd` | Muestra la ruta del directorio actual (Print Working Directory). |
| `cd` | Accede a un directorio (Change Directory). Si se usa solo, va al home. |
| `cd ..` | Retrocede al directorio madre (sube un nivel). |
| `cd ../..` | Retrocede dos niveles en el árbol de directorios. |
| `cd /` | Te lleva directamente al directorio raíz del sistema. |
| `cd /home/usuario` | Te sitúa en la carpeta del usuario especificado. |

###  Visualización y Gestión de Archivos y Directorios

| Comando | Descripción |
| :---- | :---- |
| `ls` | Lista el contenido de un directorio. (List) |
| `ls -l` | Lista el contenido mostrando información detallada (permisos, propietario, tamaño, etc.). |
| `mkdir` | Crea un nuevo directorio (carpeta). |
| `touch` | Crea un archivo vacío o actualiza la fecha de modificación de uno existente. |
| `cp` | Copia un archivo o directorio. |
| `cp -r` | Copia directorios de forma recursiva (copia todo su contenido). |
| `mv` | Mueve un archivo o directorio. También se usa para renombrar. |
| `rm` | Elimina archivos. (Remove) |
| `rm -r` | Elimina directorios y su contenido de forma recursiva. |
| `rmdir` | Elimina un directorio vacío. |
| `cat` | Muestra el contenido completo de un archivo. También sirve para concatenar archivos. |
| `less` | Muestra el contenido de un archivo página por página (permite navegación, no edición). |
| `nano` | Editor de texto sencillo para crear o modificar archivos desde la terminal. |
| `gedit` | Editor de texto gráfico (de GNOME) para crear o modificar archivos. |

### 

### Información del Sistema

| Comando | Descripción |
| :---- | :---- |
| `uname` | Muestra el nombre del kernel (sistema operativo). |
| `uname -a` | Muestra toda la información del sistema (kernel, nombre de host, etc.). |
| `uptime` | Muestra el tiempo que lleva el sistema encendido y la carga media. |
| `date` | Muestra la fecha y hora actual del sistema. |
| `cal` | Muestra un calendario del mes actual. |
| `df -h` | Muestra el espacio usado y disponible en los discos duros (en formato legible). |
| `free -h` | Muestra el uso de la memoria RAM y la swap (en formato legible). |
| `top` | Muestra los procesos en ejecución y el uso de recursos en tiempo real. |
| `ps` | Muestra una instantánea de los procesos actuales. |

### 

### Red y Conectividad

| Comando | Descripción |
| :---- | :---- |
| `ping` | Envía paquetes a un host para comprobar la conectividad (como en Windows). |
| `ifconfig o ip a` | Muestra o configura la información de las interfaces de red (IP, MAC, etc.). |
| `traceroute` | Muestra la ruta que siguen los paquetes hasta llegar a un destino. |
| `nslookup o dig` | Consulta servidores DNS para obtener información sobre dominios. |
| `sudo apt-get install samba` | Instala herramientas para compartir archivos e impresoras en red con Windows. |

### 

### Gestión de Usuarios y Grupos

| Comando | Descripción |
| :---- | :---- |
| `sudo adduser usuario` | Crea un nuevo usuario en el sistema. |
| `sudo userdel usuario*sudo deluser usuario` | Elimina un usuario del sistema. |
| `sudo userdel -r usuario` | Elimina un usuario y su directorio personal. |
| `sudo addgroup grupo*sudo groupadd grupo` | Crea un nuevo grupo. |
| `sudo groupdel grupo*sudo delgroup grupo` | Elimina un grupo. |
| `sudo adduser usuario grupo` | Agrega un usuario existente a un grupo. |
| `groupmod -n nuevonombre viejonombre` | Cambia el nombre de un grupo. |
| `usermod -l nuevonombre viejonombre` | Cambia el nombre de un usuario. |
| `su usuario` | Cambia al usuario especificado (Switch User). |
| `sudo su` | Te convierte en superusuario (root). Para salir, escribe exit. |
| `passwd` | Cambia la contraseña |
| `cat /etc/passwd` | Muestra la lista de todos los usuarios del sistema. |
| `cat /etc/group` | Muestra la lista de todos los grupos del sistema. |

#### Diferencia orden:

**\*** userdel y groupdel son comandos universales de linux, que operan a bajo nivel, mientras que deluser y delgroup son específicos de distros basadas en Debian como Ubuntu y son más completos y amigables al realizar la tarea de forma segura.

### Permisos y Propietarios

| Comando | Descripción |
| :---- | :---- |
| `chmod` | Cambia los permisos de lectura, escritura y ejecución de archivos o carpetas. (Change Mode) |
| `chmod 777` | Ejemplo de asignación de permisos (lectura, escritura y ejecución para todos). |
| `chgrp grupo archivo.txt` | Cambia el grupo propietario de un archivo o directorio. (Change Group) |
| `chown`  | Cambia el usuario propietario de un archivo o directorio. (Change Owner) |

### 

### Búsqueda y Procesamiento de Texto

| Comando | Descripción |
| :---- | :---- |
| `find` | Busca archivos y directorios en una jerarquía de directorios. |
| `grep` | Busca patrones de texto dentro de archivos. |
| `echo` | Muestra una línea de texto en la terminal. Muy usado en scripts. |

### Enlaces (Links)

| Comando | Descripción |
| :---- | :---- |
| `ln origen destino` | Crea un enlace duro (hard link). El archivo destino apunta directamente a los datos del origen. |
| `ln -s origen destino` | Crea un enlace simbólico (symbolic link o soft link). Es como un acceso directo. |

#### Diferencia enlaces (ln sin \-s):

| Enlace Simbólico (-s) | Enlace Duro (sin \-s) |
| :---- | :---- |
| Puede apuntar a directorios | No puede apuntar a directorios |
| Puede cruzar sistemas de archivos | Solo mismo sistema de archivos |
| Si se borra el original, se rompe | Mantiene el archivo hasta que no queden enlaces |
| Muestra la ruta al original | Es otra entrada directa al mismo inodo |

### 

### Ayuda y Mantenimiento

| Comando | Descripción |
| :---- | :---- |
| `man comando` | Muestra el manual de ayuda de un comando específico. |
| `comando --help` | Muestra una ayuda resumida sobre el uso de un comando. |
| `clear` | Limpia la pantalla de la terminal. |
| `reset` | Reinicia la terminal si muestra caracteres extraños o se ha quedado colgada. |
| `sudo apt-get install paquete` | Instala un paquete desde los repositorios de Ubuntu/Debian. |
| `sudo apt-get install gnome-system-tools` | Instala herramientas gráficas para administrar el sistema. |
| `shutdown` | Apaga o reinicia el sistema. (Ej: shutdown now, shutdown \-r now). |
| `apt-get moo` | Comando de easter egg (huevo de pascua) que muestra una vaquita simpática. |

### 

### Atajos de Teclado

| Atajo | Descripción |
| :---- | :---- |
| `Alt + Ctrl + T` | Abre una nueva ventana de terminal (en entornos gráficos como GNOME). |
| `Alt + F2` | Abre un cuadro de diálogo para ejecutar un comando rápidamente. |
| `Ctrl + D` | En gedit, guarda y cierra. En la terminal, cierra la sesión actual. |

---

# T1-C.4 – Bash, script consola linux

### La primera línea (Shebang)

Todo script bash empieza con:

`#!/bin/bash`

Esto le dice al sistema "usando el intérprete bash, ejecuta esto".

### Leer variables del usuario

Para pedir datos al usuario usamos `read`:

`#!/bin/bash`

`# Preguntar al usuario`  
`echo "¿Cómo te llamas?"`  
`read nombre`

`# Mostrar lo que escribió`  
`echo "Hola $nombre"`

### Tipos de variables

`#!/bin/bash`

`# Variable que tú pones`  
`edad=25`

`# Variable que pide al usuario`  
`echo "¿De qué ciudad eres?"`  
`read ciudad`

`# Variable del sistema (ya existen)`  
`echo "Estás en: $PWD"  # Directorio actual`  
`echo "Tu usuario: $USER"  # Nombre del usuario`

`# Mostrar todo junto`  
`echo "$nombre tiene $edad años y es de $ciudad"`

### Cómo terminar un script

Los scripts terminan solos cuando llegan al final, pero puedes forzar la salida con `exit`:

`#!/bin/bash`

`echo "Esto se ejecuta"`  
`exit 0  # Termina aquí, el 0 significa "todo bien"`

`echo "Esto NO se ejecuta"`

`# Los números:`  
`# exit 0  -> Todo correcto`  
`# exit 1  -> Algo salió mal (o cualquier número del 1 al 255)`

### Ejemplo práctico completo

Vamos a hacer un script que saluda y pregunta la edad:

`#!/bin/bash`

`# Pedir datos`  
`echo "=== SCRIPT DE SALUDO ==="`  
`echo "¿Cómo te llamas?"`  
`read nombre`

`echo "¿Qué edad tienes?"`  
`read edad`

`# Hacer algo con los datos`  
`echo ""`  
`echo "Hola $nombre"`  
`echo "Tienes $edad años"`

`if [ $edad -ge 18 ]; then`      
	`echo "Eres mayor de edad"`  
`else`  
    `echo "Eres menor de edad"`  
`fi`

`echo ""`  
`echo "Script terminado"`  
`exit 0`

## Cómo crear y ejecutar un script

1. **Crear el archivo**:  
   `nano miscript.sh`

2. **Escribir el código** (con el `#!/bin/bash` al principio)

3. **Dar permiso de ejecución**:

   `chmod +x miscript.sh`

4. **Ejecutarlo**:  
   `./miscript.sh`

Cuando usas la notación simbólica con operadores como \+, \-, \=, estás siendo relativo. No estás diciendo *"poner esto exactamente así"*, sino *"añadir esto a lo que ya existe"*.

El comando chmod \+x miscript.sh significa literalmente: "Añade (+) el permiso de ejecución (x) a todos los usuarios (implícitamente, a 'a' o 'all')". Por lo tanto, chmod \+x es equivalente a chmod a+x.

## Resumen rápido

| Concepto | Cómo se hace | Ejemplo |
| :---- | :---- | :---- |
| Shebang | `#!/bin/bash` | Primera línea siempre |
| Variable | `nombre=valor` | `edad=25` |
| Leer variable | `read variable` | `read nombre` |
| Usar variable | `$variable` | `echo $nombre` |
| Preguntar | `echo "texto"` | `echo "¿Edad?"` |
| Terminar | `exit 0` | Cuando quieras parar |

---

# T1-C.5 – Batch, script consola windows

Pendiente de maquetar Batch y PowerShell que he añadido por mi cuenta

## ¿Qué es un script Batch?

Es un archivo con extensión **`.bat`** o **`.cmd`** que contiene comandos de MS-DOS/CMD. El sistema los ejecuta línea por línea.

## Lo más básico para empezar

### 1\. La primera línea: `@echo off`

@echo off

**¿Qué hace?**

- **`echo off`** : Apaga la visualización de los comandos (solo se ven los resultados)  
- **`@`** : Aplica el "echo off" también a esta misma línea  
  Sin `@echo off`, verías algo feo:  
  C:\\Users\>echo Hola mundo  
  Hola mundo  
  C:\\Users\>pause  
  Presione una tecla...  
  Con `@echo off`, solo ves lo limpio:  
  Hola mundo  
  Presione una tecla...

  ### 2\. Leer variables del usuario

  Usamos `set /p` para preguntar:

  @echo off

  set /p nombre=¿Cómo te llamas? 

  echo Hola %nombre%

  pause

  ### 3\. Las variables en Batch

- Se crean con `set variable=valor`  
- Se usan con `%variable%` (siempre entre %%)  
  @echo off  
  :: Esto es un comentario (los dos puntos)  
    
  set edad=25  
  set /p ciudad=¿De qué ciudad eres?   
    
  echo Tu edad es %edad%  
  echo Eres de %ciudad%  
    
  :: Variables del sistema  
  echo Tu usuario es %username%  
  echo Estás en %cd%  (directorio actual)  
  pause

  ### 4\. Cómo terminar un script

  @echo off

  echo Esto se ejecuta

  exit

  echo Esto NO se ejecuta


  :: O con códigos de error

  exit /b 0   :: Todo bien

  exit /b 1   :: Error

  ## Ejemplo práctico completo

  El mismo script de saludo pero en Batch:

  @echo off

  title Script de Saludo

  cls


  echo \===== SCRIPT DE SALUDO \=====

  echo.


  set /p nombre=¿Cómo te llamas? 

  set /p edad=¿Qué edad tienes? 


  echo.

  echo Hola %nombre%

  echo Tienes %edad% años


  if %edad% geq 18 (

      echo Eres mayor de edad

  ) else (

      echo Eres menor de edad

  )


  echo.

  pause

  exit

  ## Cómo crear y ejecutar un script Batch

1. **Crear el archivo**:  
   notepad miscript.bat

2. **Escribir el código** (guárdalo con extensión `.bat`)

3. **Ejecutarlo**:

   - Doble clic en el archivo  
   - O desde CMD: `miscript.bat`  
   - O con ruta: `C:\ruta\miscript.bat`

   ## Comandos esenciales de Batch

| Comando | Qué hace | Ejemplo |
| :---- | :---- | :---- |
| `echo` | Muestra texto | `echo Hola` |
| `set /p` | Pregunta al usuario | `set /p var=Texto:` |
| `set` | Crea variable | `set var=valor` |
| `%variable%` | Usa variable | `echo %var%` |
| `cls` | Limpia pantalla | `cls` |
| `pause` | Pausa "Presione una tecla..." | `pause` |
| `exit` | Termina el script | `exit` |
| `if` | Condiciones | `if %edad%==18` |
| `goto` | Salta a una etiqueta | `goto fin` |
| `:` | Etiqueta para goto | `:inicio` |
| `::` | Comentario | `:: Esto es un comentario` |
| `%cd%` | Directorio actual | `echo %cd%` |
| `%username%` | Usuario actual | `echo %username%` |
| `%date%` | Fecha actual | `echo %date%` |
| `%time%` | Hora actual | `echo %time%` |

   ## Ejemplo con condiciones y goto

   @echo off

   cls

   

   echo MENÚ PRINCIPAL

   echo 1\. Saludar

   echo 2\. Despedirse

   echo 3\. Salir

   echo.

   

   set /p opcion=Elige una opción: 

   

   if %opcion%==1 goto saludar

   if %opcion%==2 goto despedir

   if %opcion%==3 goto fin

   goto error

   

   :saludar

   echo Hola amigo\!

   pause

   goto fin

   

   :despedir

   echo Adiós, hasta luego\!

   pause

   goto fin

   

   :error

   echo Opción no válida

   pause

   

   :fin

   exit

   ## Mini-ejercicio para practicar

   Haz un script Batch que:

1. Pregunte "¿Qué directorio quieres ver?"  
2. Guarde la respuesta en `%ruta%`  
3. Muestre el contenido con `dir %ruta%`  
   @echo off  
   set /p ruta=¿Qué directorio quieres ver?   
   dir %ruta%  
   pause  
   ¿Te queda claro? ¿Quieres que profundice en algún comando específico?  
   ¡Claro que sí\! Vamos a explicar los **scripts en PowerShell** desde cero, igual que hicimos con bash. La filosofía es similar, pero la sintaxis cambia.

   ## ¿Qué es un script en PowerShell?

   Es un archivo de texto con extensión **`.ps1`** que contiene comandos de PowerShell. Los escribes una vez y los ejecutas cuando quieras.

   ## Lo más básico para empezar

   ### 1\. La primera línea (opcional pero recomendada)

   \<\#

   .SYNOPSIS

       Breve descripción del script

   .DESCRIPTION

       Explicación más detallada

   .NOTES

       Autor: Tu nombre

   \#\>

   Esto es un comentario de ayuda. En PowerShell no hay "shebang", pero sí estos bloques de documentación.

   ### 2\. Política de ejecución (importante)

   Windows bloquea los scripts por seguridad. Primero debes permitirlos (una sola vez):

   \# Abre PowerShell como ADMINISTRADOR y ejecuta:

   Set-ExecutionPolicy RemoteSigned

   ### 3\. Leer variables del usuario

   Para pedir datos usamos `Read-Host`:

   \# Preguntar al usuario

   $nombre \= Read-Host "¿Cómo te llamas?"

   

   \# Mostrar lo que escribió

   Write-Host "Hola $nombre"

   ### 4\. Tipos de variables

   En PowerShell, las variables siempre empiezan con `$`:

   \# Variable que tú pones

   $edad \= 25

   

   \# Variable que pide al usuario

   $ciudad \= Read-Host "¿De qué ciudad eres?"

   

   \# Variables del sistema (ya existen)

   Write-Host "Estás en: $PWD"  \# Directorio actual (Get-Location)

   Write-Host "Tu usuario: $env:USERNAME"  \# Las variables de entorno van con $env:

   

   \# Mostrar todo junto

   Write-Host "$nombre tiene $edad años y es de $ciudad"

   ### 5\. Cómo terminar un script

   Write-Host "Esto se ejecuta"

   exit 0  \# Termina aquí

   Write-Host "Esto NO se ejecuta"

   

   \# exit 0  \-\> Todo bien

   \# exit 1  \-\> Error

   ## Ejemplo práctico completo

   Vamos a hacer el mismo script de saludo que en Linux, pero en PowerShell:

   \<\#

   .SYNOPSIS

       Script de saludo para usuarios

   .DESCRIPTION

       Pregunta el nombre y la edad, y responde según corresponda

   \#\>

   

   \# Limpiar pantalla

   Clear-Host

   

   \# Pedir datos

   Write-Host "=== SCRIPT DE SALUDO \===" \-ForegroundColor Green

   $nombre \= Read-Host "¿Cómo te llamas?"

   $edad \= Read-Host "¿Qué edad tienes?"

   

   \# Convertir edad a número (Read-Host siempre devuelve texto)

   $edadNumero \= \[int\]$edad

   

   \# Hacer algo con los datos

   Write-Host ""

   Write-Host "Hola $nombre" \-ForegroundColor Cyan

   Write-Host "Tienes $edad años"

   

   if ($edadNumero \-ge 18\) {

       Write-Host "Eres mayor de edad" \-ForegroundColor Green

   } else {

       Write-Host "Eres menor de edad" \-ForegroundColor Yellow

   }

   

   Write-Host ""

   Write-Host "Script terminado" \-ForegroundColor Red

   exit 0

   ## Cómo crear y ejecutar un script

1. **Crear el archivo**:  
   \# Desde PowerShell:  
   notepad miscript.ps1  
   \# O desde cualquier editor (VS Code, bloc de notas)

2. **Escribir el código** (guárdalo con extensión `.ps1`)

3. **Ejecutarlo**:

   \# Desde la misma carpeta:

   .\\miscript.ps1

   

   \# O con la ruta completa:

   C:\\scripts\\miscript.ps1

   ## Diferencias clave con Bash (para que te sitúes)

| Concepto | En Bash | En PowerShell |
| :---- | :---- | :---- |
| Variable | `nombre=Juan` | `$nombre = "Juan"` |
| Usar variable | `$nombre` | `$nombre` (igual) |
| Leer input | `read nombre` | `$nombre = Read-Host` |
| Mostrar | `echo "texto"` | `Write-Host "texto"` |
| Comparar números | `if [ $edad -ge 18 ]` | `if ($edad -ge 18)` |
| Comentario | `# texto` | `# texto` |
| Comentario largo | No hay fácil | `<# texto #>` |
| Directorio actual | `$PWD` | `$PWD` o `Get-Location` |
| Usuario | `$USER` | `$env:USERNAME` |

   ## Comandos útiles de PowerShell

   Get-ChildItem        \# ls o dir

   Set-Location         \# cd

   Get-Content archivo  \# cat

   Copy-Item            \# cp

   Move-Item            \# mv

   Remove-Item          \# rm

   New-Item             \# touch o mkdir

   Clear-Host           \# clear

   Get-Help comando     \# man

   ## Mini-ejercicio para practicar

   Intenta hacer un script PowerShell que:

1. Pregunte "¿Qué ruta quieres explorar?"  
2. Guarde la respuesta en `$ruta`  
3. Muestre todos los archivos de esa carpeta con `Get-ChildItem`  
   ¿Te animas a probarlo? La estructura sería:  
   $ruta \= Read-Host "¿Qué ruta quieres ver?"  
   Get-ChildItem $ruta  
   ¿Necesitas ayuda con algún comando específico de PowerShell?

## Permisos MS2

* `attrib` Muestra permisos de archivos y permite añadir y quitar  
  `attrib -r patatas.txt` Añade permiso de lectura.  
  `attrib +h patatas.txt` Lo oculta, no se puede ni copiar.  
* Nunca poner `attrib +s` porque le dicen que es de sistema. Al arrancar va a buscar estos archivos de sistema y se quedará parado hasta que des intro.  
* `del` eliminar  
  `del *.*`  eliminar todo  
* `copy con nombrearchivo.txt`  lo que queremos escribir dentro \> Enter \> Ctrl+Z 


  ## Comparativa de Comandos Linux vs Windows

| DEFINICIÓN | LINUX | WINDOWS |
| :---- | :---- | :---- |
| Listar archivos | `ls` o `dir` | `dir` |
| Cambiar directorio | `cd` | `cd` |
| Crear archivo vacío | `touch` | `copy nul archivo.txt` (o `echo. > archivo.txt`) |
| Mostrar contenido de archivo | `cat` o `less` | `type` o `more` |
| Limpiar pantalla | `clear` | `cls` |
| Crear directorio | `mkdir` | `md` o `mkdir` |
| Eliminar archivo | `rm` | `del` |
| Eliminar carpeta | `rm -r` | `rmdir /s` |
| Copiar archivo | `cp` | `copy` |
| Copiar carpetas | `cp -r` | `xcopy` o `robocopy` |
| Mover o renombrar | `mv` | `move` |
| Ver configuración de red | `ifconfig` o `ip a` | `ipconfig` |
| Ver procesos | `ps` o `top` | `tasklist` |
| Terminar procesos | `kill` | `taskkill` |
| Buscar texto en archivos | `grep` | `findstr` |
| Mostrar fecha y hora | `date` | `date` / `time` |
| Mostrar directorio actual | `pwd` | `cd` (sin argumentos) |
| Mostrar mensaje | `echo` | `echo` |
| Ver ayuda de un comando | `man` o `comando --help` | `comando /?` |
| Cambiar permisos de archivo | `chmod` | `icacls` |
| Cambiar propietario de archivo | `chown` | `takeown` / `icacls` |
| Buscar archivos | `find` | `dir /s` o `where` |
| Mostrar espacio en disco | `df -h` | `wmic logicaldisk get size,freespace,caption` o `fsutil volume diskfree` |
| Mostrar uso de memoria | `free -h` | `systeminfo` o `wmic memorychip` |
| Comprobar conectividad (ping) | `ping` | `ping` |
| Trazar ruta de red | `traceroute` | `tracert` |
| Consultar DNS | `nslookup` o `dig` | `nslookup` |
| Apagar o reiniciar sistema | `shutdown` | `shutdown` |

---

# T1-C.6 – Repaso T1

---

# T2-C.7 – Powershell

## Comandos básicos

### Información Del Sistema (Identidad Y Red)

* Ver el nombre del servidor:   
  `Hostname`

* Información básica de red (IP, máscara y gateway):   
  `Ipconfig`

* Información de red detallada (DNS, DHCP, MAC, concesión DHCP, etc.):   
  `Ipconfig /all`

  ### Configuración de red

* Listar todos los adaptadores de red:   
  `Get-NetAdapter`

* Ver la configuración detallada de una interfaz de red:   
  `Get-NetIPConfiguration` 

* Asignar una dirección IP, máscara y puerta de enlace (usando índice de interfaz):   
  `New-NetIPAddress –InterfaceIndex 4 –IPAddress 192.168.1.2 –PrefixLength 24 –DefaultGateway 192.168.1.1`

* Asignar una dirección IP y máscara (usando alias de interfaz):   
  `New-NetIPAddress -InterfaceAlias "Ethernet" -IPAddress 148.148.0.5 -PrefixLength 20`

* Agregar una IP adicional a una misma interfaz:   
  `New-NetIPAddress –InterfaceIndex 4 –IPAddress 172.16.0.5 –PrefixLength 24`

* Eliminar una dirección IP específica:   
  `Remove-NetIPAddress –IPAddress 192.168.1.2`

* Configurar los servidores DNS:   
  `Set-DnsClientServerAddress –InterfaceIndex 4 -ServerAddresses 192.168.10.100`

* Configurar un DNS específico (ej. 8.8.8.8):   
  `Set-DnsClientServerAddress -InterfaceIndex 6 -ServerAddresses 8.8.8.8`

  #### Configuración de red (con legado / netsh)

  Comandos tradicionales de la consola `netsh`, también funcionales en PowerShell.

* Ver la configuración detallada de una interfaz de red:   
  `Netsh interface ipv4 show config`

* Asignar IP estática, máscara y gateway con netsh:   
  `Netsh interface ipv4 set address name="ethernet" static 192.168.1.2 255.255.255.0 192.168.12.1`


  ### Administración del servidor

  Comandos para gestionar el estado y la configuración general del servidor.

* Cambiar el nombre del servidor:   
  `Rename-Computer –ComputerName WIN-nnnnnnnnnnn –NewName win-123456`

* Reiniciar el servidor:   
  `Restart-Computer`

* Interfaz de configuración simple del servidor:   
  `sconfig`


  ### Gestión de archivos y carpetas compartidas (SMB)

  Comandos para crear y administrar recursos compartidos en la red.

* Crear una carpeta local:   
  `New-Item -Path "C:\nombrecarpeta" -ItemType Directory`

* Compartir una carpeta en red con acceso total para "Todos":   
  `New-SmbShare -Name "nombrecarpeta" -Path "C:\nombrecarpeta" -FullAccess "Todos"`

* Compartir una carpeta en red con acceso de solo lectura para "Todos":   
  `New-SmbShare -Name "nombrecarpeta" -Path "C:\nombrecarpeta" -ReadAccess "Todos"`

* Eliminar un recurso compartido de red:   
  `Remove-SmbShare -Name carpeta`

* Listar todos los recursos compartidos del equipo:   
  `Get-SmbShare`


  ### Módulo de Active Directory (AD DS)

  Comandos para gestionar objetos en un dominio de Active Directory.

* Importar el módulo de Active Directory (si no se carga automáticamente):   
  `Import-Module ActiveDirectory`

* Listar todos los cmdlets disponibles del módulo AD:   
  `Get-Command -Module ActiveDirectory`

* Obtener ayuda sobre un comando específico de AD:   
  `Get-Help Set-ADGroup`

  **Sobre Unidades Organizativas (OU)**

* Crear una OU llamada "Alumnos" en la raíz del dominio:   
  `New-ADOrganizationalUnit -Name "Alumnos" -Path "DC=asir,DC=local"`

* Crear una sub-OU llamada "Primero" dentro de "Alumnos":   
  `New-ADOrganizationalUnit -Name "Primero" -Path "OU=Alumnos,DC=asir,DC=local"`

* Crear una OU (ej. "Informáticos") en la raíz del dominio:   
  `New-ADOrganizationalUnit -DisplayName "Informáticos" -Name "Informáticos" -Path "DC=DOMINIO,DC=ES"`

* Crear una sub-OU (ej. "smr") dentro de otra OU (ej. "Informáticos"): `New-ADOrganizationalUnit -DisplayName "smr" -name "smr" -path "OU=Informáticos,DC=dominio,DC=es"`

* Listar todas las OU del dominio de forma resumida:   
  `Get-ADOrganizationalUnit -Filter * | ft Name`

* Deshabilitar la protección de eliminación accidental en una OU:   
  `Set-ADOrganizationalUnit -Identity "OU=Técnicos,DC=asir1,DC=local" -ProtectedFromAccidentalDeletion:$false`

  **Sobre Usuarios**

* Crear un usuario básico (en el contenedor "Users" por defecto):   
  `New-ADUser -displayName "peque 5 años" -Name "pg"`

* Crear un usuario dentro de una OU específica:   
  `New-ADUser -displayname "pequeñines no gracias debes dejarles crecer" -name "pq2" -path "ou=unidad,ou=unidad,ou=unidad,dc=dominio,dc=es"`

* Crear un usuario y forzar el cambio de contraseña en el próximo inicio de sesión:   
  `New-ADUser –name “sergitin” -ChangePasswordAtLogon $true`

* Listar todos los usuarios del dominio con detalles:   
  `Get-ADUser -Filter *`

* Listar todos los usuarios del dominio (solo nombre):   
  `Get-ADUser -Filter * | Ft name`

  **Sobre Grupos**

* Agregar un usuario al grupo "Administradores" en AD:   
  `Add-ADGroupMember "Administradores" –Members "farf"`

  ### Gestión de usuarios locales

  Comandos para administrar usuarios que no son de dominio, sino locales al equipo.

* Crear un usuario local sin contraseña:   
  `New-LocalUser -name “pepa” -description “tecnica” -NoPassword`

* Crear un usuario local con contraseña:   
  *(Primero se guarda la contraseña de forma segura en una variable)*   
  `$password = Read-Host -AsSecureString`   
  *(Luego se crea el usuario con esa contraseña)*   
  `New-LocalUser -name “pepa3” -description “tecnicos” -password $password -fullname “tecnicos de administradores”`

* Establecer que la contraseña de un usuario local no expire:   
  `Set-LocalUser “Farfan” –PasswordNeverExpires $true`

* Agregar un usuario local al grupo "Administradores":   
  `Add-LocalGroupMember –Group “Administradores” –Member "GomezFarfan"`

* Ver los miembros de un grupo local:   
  `Get-LocalGroupMember –Group “Administradores”`

* Establecer restricción horaria para un usuario local (usando el comando legacy `net user`):   
  `Net user usuario /times: l-v,08:00-18:00`

## ---

# T2-C.8 – Repaso Enero

---

# T2-C.9 – Relación de confianza entre dos servers \+ Duda ip 127

## Relación de confianza entre dos servers

* Ambos tienen que estar subidos a controlador de dominio.  
* Ambos tiene que tener nombres de equipo diferentes.  
* Anotar sus ips `ipconfig`. Si están en la misma red mejor.

  ### Reenviadores (relays)

  (Ambos) Herramientas DNS \> Botón dechado en nombre del equipo (O directamente Reenviadores) \> Pestaña Reenviadores \> Editar y poner la ip del otro server 

  ### Zona directa

  (Ambos) Herramientas DNS \> Zona directa \> Zona nueva:

* Zona de rutas internas (podría ser también principal)  
* Nombre de zona: pon el del dominio de la otra máquina

  ### Zona inversa

  No es necesario para la relación de confianza, pero ya que estamos vamos a hacerla:

  (Ambos) Herramientas DNS \> Zona inversa \> Zona nueva:

* Zona de rutas internas (podría ser también principal)  
* Nombre de zona: primero red solo sin el último octeto  
* Servidores maestros ya pones la ip completa del  otro server(Aparece en rojo, pero no importa)

(Ambos) Herramientas DNS \> Botón dechado en nombre del equipo \> Todas las tareas \> Reiniciar

### Dominios y confianzas

Seguimos con la RC

Panel de Administrador del servidor \> Herramientas \> Dominios y confianzas de Active Directory

Botón dcho. dominio \> Propiedades \> Pestaña Confianzas \> Nueva confianza

* Nombre: dominio contrario  
* Tipo de confianza: Confianza con un dominio Windows (Dominio especificado)  
* Parece que en el segundo server salen más opciones:  
  * Tipo: Confianza externa  
  * Dirección de confianza: bidireccional(para que se compartan ambos entre sí)  
  * Partes: ambos dominios  
  * Nivel de autenticación: Autenticación en todo el dominio   
  * Nivel para local lo mismo  
  * Confirmar confianza: sí en ambos

En el primero no había salido nada, pero en el segundo sale a la primera. Sin embargo, con cerrar la ventana en el primero y volver  darle a Propiedades \> Confianzas ya aparece.

Para comprobar que van, vamos a compartir carpeta: Botón dcho. \> Propiedades \> Compartir \> Uso compartido avanzado:

* Marcar Compartir esta carpeta  
* Permisos: Agregar \> Ubicaciones \> Te tiene que salir el otro server, seleccionas, Aceptar. Opciones avanzadas \> Buscar ahora (tiene que tener la misma hora)

## Duda ip 127.0.0.1

*¿Puedo coger cualquier ip de 127.0.0.0 para trabajar con nosotros?*  
No, la Network 127.0.0.0 es utilizada para que el PC se comunique consigo mismo, lo que se conoce como loopback

---

# T2-C.10 – Compartir entre Windows y Ubuntu

En VMs poner Red interna, mejor que adaptador puente (esta está más pensada para conectar con física, para dos máquinas virtuales Red interna)

## Método 1

### Configurar samba

Instalar samba:  
`sudo apt install samba`

Configurar samba:  
`sudo nano /etc/samba/smb.conf`

Antes de \#\#\#\# Networking \#\#\#\# añadir  
`name resolver order = lmhosts hosts wins bcast`

Configurar Name Server Switch:  
`sudo nano /etc/nsswitch.conf`

En esta línea:  
`hosts:          files mdns4_minimal [NOTFOUND=return] dns myhostname`

Añadir `wins` si no está:  
`hosts:          files mdns4_minimal [NOTFOUND=return] wins dns myhostname`

### Configurar la misma red entre Windows y Ubuntu, por ejemplo:

WINDOWS

IP: 192.168.1.49 255.255.255.0 puerta de enlace 192.168.1.1

    • No olvidar desactivar uso compartido con protección con contraseña

    • Activar detección de redes

    • Crear una carpeta compartida para el grupo todos con control total

UBUNTU

    • IP 192.168.1.50 255.255.255.0 puerta de enlace 192.168.1.1 DNS: 192.168.1.49 (la IP de Windows)

    • Compartir una carpeta con todos los permisos

PARA ACCEDER DESDE UBUNTU A WINDOWS

    • Explorador de archivos

    • Otras ubicaciones, conectar al servidor, escribir smb://IP DE WINDOWS

PARA ACCEDER DESDE WINDOWS A UBUNTU

    • Explorador de archivos

    • En barra de navegación \\\\IP DE UBUNTU

## Método 2

### Configurar samba

Instalar samba:  
`sudo apt install samba`

Configurar samba:  
`sudo nano /etc/samba/smb.conf`

Después de write list  
`[WINDOWS]`  
`path=/home/popnoart/Escritorio/WINDOWS`  
`writeable = yes`  
`guest ok = yes`  
`browsable = yes`

---

# T2-C.11 – Repaso T2

---

# T3-C.12 – DNS & FTP

Creo, pendiente de ver

---

# T3-C.13 – Auditorías

Directivas de grupo(GPO)

\[Volvemos a CUENTA LOCAL, no de DOMINIO\]

Cliente: Ejecutar gpedit.msc para abrir las directivas de grupo local 

Configuración del equipo \> Configuración de seguridad \> Directivas de cuentas \> Directiva de contraseñas 

NOTA: en la tarea de esta unidad pide ejecutar las directivas de grupo local, y luego desde otro ordenador conectado al servidor de dominio hacer intentos fallidos.

Las directivas de grupo local se aplican localmente, pero al ser un servidor digamos que es la primera barrera y puede usarse sin irte a las directivas del controlador de dominio.

Cuando un equipo Windows 10 se une a un dominio, las directivas se aplican en este orden:

1. Directiva de Grupo Local (del equipo Windows 10\) ↓  
2. Directivas de Grupo a nivel de sitio ↓  
3. Directivas de Grupo a nivel de dominio ↓  
4. Directivas de Grupo a nivel de unidad organizativa (OU)

Las **directivas posteriores sobrescriben a las anteriores** si hay conflictos.

---

# T3-C.14 – Auditorías

Sigue o vuelve a explicar como activar y ver los eventos que queremos auditar. 

Esta y la anterior clase es básicamente el ejercicio de auditorías.

---

---

# 

