# **IAW**

## Implantación de aplicaciones web

# Unidades

* T1 \- Unidad 0: Conceptos generales de la arquitectura aplicaciones web  
* T1 \- Unidad 1.1: JavaScript Introducción  
* T1 \- Unidad 1.2: JavaScript. Operadores  
* T1 \- Unidad 1.3: JavaScript. Bucles y estructuras de control  
* T1 \- Unidad 1.4: JavaScript. Arrays  
* T1 \- Unidad 1.4: JavaScript. Funciones  
* T1 \- Unidad 2: JavaScript. Gestión de eventos  
* T1 \- Unidad 3: JavaScript. Gestión de nodos  
* T2 \- Unidad 4: JavaScript. Objetos  
* T2 \- Unidad 5: JavaScript. Clases  
* T2 \- Unidad 6,1: JavaScript. Array de objetos  
* T2 \- Unidad 6.2: JavaScript. Json  
* T3 \- Unidad 6.3: JavaScript. Ajax  
* T3 \- Unidad 7: XML

---

# T1-U1.1. JavaScript \- Introducción

## ¿Qué es JavaScript?

* JavaScript es un lenguaje de programación **interpretado**, lo que significa que no necesita compilación.  
* Se utiliza principalmente para crear **páginas web dinámicas**.  
* Fue desarrollado por **Netscape** y estandarizado por **ECMA** bajo el nombre de **ECMAScript** (estándar ECMA-262).

## Cómo incluir JavaScript en HTML

### Dentro del mismo documento HTML

Se usa la etiqueta \<script\> y se recomienda colocarla:

* En \<head\>: si el script debe cargarse antes de mostrar la página.  
* Antes de cerrar \</body\>: si el script es pesado y no debe bloquear la carga.

\<script type="text/javascript"\>  
	alert("Un mensaje de prueba");  
\</script\>

### En un archivo externo

Se crea un archivo con extensión .js y se enlaza con src:

\<script src="js/codigo.js"\>\</script\>

Clausula defer: nos asegura que se ha cargado todo:

\<scrip defer src=”[scrip.js](http://scrip.js)\>\<script\>

**Ventajas:**

* Código más limpio.  
* Reutilizable en varias páginas.  
* Cambios reflejados en todas las páginas que lo enlazan.

  ### Dentro de elementos HTML (eventos)

Se usa en atributos como onclick, aunque **no es recomendable** por mezclar lógica con presentación.

\<p onclick="alert('Mensaje')"\>Párrafo\</p\>

### Etiqueta \<noscript\>

Muestra contenido alternativo si el navegador **no ejecuta JavaScript**.

\<noscript\>  
	\<p\>Activa JavaScript para ver esta página correctamente.\</p\>  
\</noscript\>

## Sintaxis básica de JavaScript

* No se tienen en cuenta **espacios en blanco** ni **saltos de línea**.  
* **Distingue mayúsculas y minúsculas**.  
* No es necesario declarar el **tipo de las variables**.  
* El **punto y coma (;)** es opcional, pero recomendable.  
* Se pueden incluir **comentarios**:  
  * Una línea: // comentario  
  * Varias líneas: /\* comentario \*/

## Variables

### Declaración

Se usa var, let o const (aunque let y const se ven más adelante).

var numero1 \= 3;  
var numero2 \= 1;  
var resultado \= numero1 \+ numero2;

No es obligatorio declararlas, pero **se recomienda hacerlo**.

### Reglas para nombres de variables

* Solo letras, números, $ y \_.  
* No pueden empezar con número.  
* Ejemplos válidos: $precio, \_nombre, total1.

## Tipos de variables

### Numéricas

Enteros o decimales (con punto).

var iva \= 16;          // entero  
var total \= 234.65;    // decimal

### Cadenas de texto

Se escriben entre comillas simples o dobles.

var mensaje \= "Hola";  
var producto \= 'Camisa';

Si el texto contiene comillas, se alternan:

var texto \= "Texto con 'simples' dentro";

### Arrays

Colección de valores.

var dias \= \["Lunes", "Martes", "Miércoles"\];  
console.log(dias\[0\]); // "Lunes"

Los índices empiezan en **0**.

### Booleanos

Solo pueden ser true o false.

var registrado \= false;  
var incluido \= true;.

---

# T1-U1.2. JavaScript \- Operadores

## ¿Qué son los operadores?

Los operadores permiten **manipular el valor de las variables**, realizar **operaciones matemáticas**, **comparar valores** y tomar **decisiones lógicas** en un programa.

## Operadores de asignación

### Asignación simple (\=)

Guarda un valor en una variable.

var numero \= 3;

 No confundir con el operador de comparación \==.

### Asignación combinada

Permiten abreviar operaciones matemáticas con asignación.

numero \+= 3;  // numero \= numero \+ 3  
numero \-= 2;  // numero \= numero \- 2  
numero \*= 4;  // numero \= numero \* 4  
numero /= 2;  // numero \= numero / 2  
numero %= 3;  // numero \= numero % 3

## Operadores de incremento y decremento

Solo para variables **numéricas**.

### Incremento (\++)

Aumenta el valor en 1\.

var a \= 5;  
a++;  // a \= 6  
\++a;  // a \= 7

### Decremento (\--)

Disminuye el valor en 1\.

var b \= 5;  
b--;  // b \= 4  
\--b;  // b \= 3

### Diferencia entre prefijo y sufijo

- **Prefijo (\++a)**: primero incrementa, luego evalúa.  
- **Sufijo (a++)**: primero evalúa, luego incrementa.

var x \= 5;  
var y \= 2;  
var z \= x++ \+ y;  // z \= 7, x \= 6  
var x \= 5;  
var y \= 2;  
var z \= \++x \+ y;  // z \= 8, x \= 6

## Operadores matemáticos

| Operador | Descripción |
| :---- | :---- |
| \+ | Suma |
| \- | Resta |
| \* | Multiplicación |
| / | División |
| % | Módulo (resto de división entera) |

var a \= 10;  
var b \= 3;  
var suma \= a \+ b;      // 13  
var resta \= a \- b;     // 7  
var multi \= a \* b;     // 30  
var div \= a / b;       // 3.333…  
var modulo \= a % b;    // 1

## Operadores relacionales (de comparación)

Comparan dos valores y devuelven un **booleano** (true o false).

| Operador | Significado |
| :---- | :---- |
| \> | Mayor que |
| \< | Menor que |
| \>= | Mayor o igual |
| \<= | Menor o igual |
| \== | Igual que |
| \!= | Distinto que |

var a \= 5;  
var b \= 3;  
console.log(a \> b);   // true  
console.log(a \== b);  // false  
console.log(a \!= b);  // true

**Cuidado**: No confundir \= (asignación) con \== (comparación).

## Operadores lógicos

Se usan para tomar decisiones en función de condiciones. Siempre devuelven un valor **booleano**.

### Negación (\!)

Invierte el valor booleano.

var visible \= true;  
console.log(\!visible);  // false

Conversión automática a booleano:

- Números: 0 → false, cualquier otro → true  
- Cadenas: "" → false, cualquier otra → true

var cantidad \= 0;  
console.log(\!cantidad);  // true  
var texto \= "Hola";  
console.log(\!texto);     // false

### AND (&&)

Devuelve true sólo si **ambos** operandos son true.

true && true   // true  
true && false  // false

### OR (||)

Devuelve true si **al menos uno** de los operandos es true.

true || false  // true  
false || false // false

---

# T1-U1.3. JavaScript \- Estructuras de Control y Bucles

## ¿Qué son las estructuras de control de flujo?

Permiten que un programa **tome decisiones** o **repita instrucciones** según ciertas condiciones. Sin ellas, los programas solo ejecutarían instrucciones de forma lineal, sin capacidad de adaptarse a diferentes situaciones.

## Estructura if

### if simple

Ejecuta un bloque de código solo si la condición es true.

if (condicion) {    
	// código si la condición es true  
}

**Ejemplo:**

var mostrarMensaje \= true;  
if (mostrarMensaje) {    
	alert("Hola Mundo");

}

También se puede escribir con comparación:

if (mostrarMensaje \== true) {  
	alert("Hola Mundo");  
}

### Condiciones complejas

Se pueden combinar operadores lógicos (&&, ||, \!).

var mostrado \= false;  
var usuarioPermite \= true;  
if (\!mostrado && usuarioPermite) {    
	alert("Primera vez que se muestra");  
}

### Estructura if...else

Ejecuta un bloque si la condición es true y otro si es false.

if (condicion) {  
	// código si true  
} else {  
	// código si false  
}

**Ejemplo:**

var edad \= 18;  
if (edad \>= 18\) {  
  alert("Eres mayor de edad");  
} else {  
  alert("Eres menor de edad");  
}

### Estructura if...else if...else

Para múltiples condiciones encadenadas.

if (edad \< 12\) {    
	alert("Muy pequeño");  
} else if (edad \< 19\) {    
	alert("Adolescente");}   
else if (edad \< 35\) {    
	alert("Joven");  
} else {   
	alert("Cuídate más");  
}

## Estructura for (bucles)

Permite **repetir un bloque de código** un número determinado de veces.

### Sintaxis:

for (inicialización; condición; actualización) {    
	// código a repetir  
}

- **Inicialización**: se ejecuta una vez al entrar al bucle (ej: var i \= 0).  
- **Condición**: se evalúa antes de cada repetición. Si es true, se ejecuta el bloque.  
- **Actualización**: se ejecuta después de cada repetición (ej: i++).  
  **Ejemplo:**

for (var i \= 0; i \< 5; i++) {    
	alert("Vuelta número " \+ i);  
}

### Recorrer un array con for:

var dias \= \["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"\];  
for (var i \= 0; i \< dias.length; i++) {    
	alert(dias\[i\]);  
}

## Estructura while (bucles indefinidos)

Permite repetir un bloque de código mientras se cumpla una condición. 

- Es útil cuando no sabes exactamente cuántas veces se repetirá.  
- **Condición**: se evalúa antes de cada repetición. Si es true, se ejecuta el bloque.

  ### Sintaxis:

while (condición) {    
    // código a repetir  
    // (incluir algo que cambie la condición para no entrar en bucle infinito)  
}

**Ejemplo:**

var dias \= \["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"\];  
var i \= 0;  
while (i \< dias.length) {  
    alert(dias\[i\]);  
    i++;  
}

## Estructura forEach(bucle específico para arrays)

Es una forma más moderna y legible de recorrer arrays.

- No necesitas un índice manualmente..

  ### Sintaxis:

array.forEach(function(elemento, indice, arrayCompleto) {  
    // código a ejecutar para cada elemento  
});

- **elemento →** valor actual del array (obligatorio en la práctica)  
- **indice →** posición actual (opcional)  
- **arrayCompleto →** el array original (casi nunca se usa)

**Ejemplo:**

var dias \= \["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"\];  
dias.forEach(function(dia) {  
    alert(dia);  
});

## Comparativa bucles

var dias \= \["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"\];

// FOR clásico  
for (var i \= 0; i \< dias.length; i++) {  
    alert(dias\[i\]);  
}

// WHILE  
var i \= 0;  
while (i \< dias.length) {  
    alert(dias\[i\]);  
    i++;  
}

// FOREACH  
dias.forEach(function(dia) {  
    alert(dia);  
});

## Funciones útiles para cadenas de texto

### length

Devuelve el número de caracteres de una cadena.

var mensaje \= "Hola Mundo";  
var longitud \= mensaje.length;  // 10

### Operador \+ para concatenación

Une dos o más cadenas de texto.

var saludo \= "Hola " \+ "Mundo";  // "Hola Mundo"  
var nombre \= "Ana";  
var mensaje \= "Bienvenida, " \+ nombre;  // "Bienvenida, Ana"

---

# T1-U1.4. JavaScript \- Arrays

## ¿Qué es un array?

Un **array** (también llamado vector, matriz o arreglo) es una **colección de variables** agrupadas bajo un mismo nombre. Pueden almacenar valores del mismo tipo o de tipos diferentes.

### Ejemplo sin array (ineficiente):

var dia1 \= "Lunes";  
var dia2 \= "Martes";  
var dia3 \= "Miércoles";  
// ... hasta 7 variables

### Ejemplo con array (eficiente):

var dias \= \["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"\];

Un array permite manejar muchos datos relacionados con una sola variable.

## Sintaxis para definir un array

Se usan corchetes \[\] para delimitar el array y comas , para separar los elementos.

var nombre\_array \= \[valor1, valor2, valor3, ..., valorN\];

**Ejemplo:**

var numeros \= \[10, 20, 30, 40\];  
var mixto \= \["Hola", 42, true, null\];

## Acceso a los elementos del array

Se accede a un elemento mediante su **índice** o posición, usando corchetes \[\].

 **Importante**: Los índices empiezan en **0** (no en 1).

var dias \= \["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"\];  
var primero \= dias\[0\];   // "Lunes"  
var sexto \= dias\[5\];     // "Sábado"  
var ultimo \= dias\[6\];    // "Domingo"

## Propiedad útil: length

Devuelve el número de elementos de un array.

var dias \= \["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"\];  
var cantidad \= dias.length;  // 7

Es muy útil para recorrer arrays con bucles:

for (var i \= 0; i \< dias.length; i++) {  
	console.log(dias\[i\]);  
}

## Tipos de datos en arrays

Los arrays pueden contener cualquier tipo de dato: números, cadenas, booleanos, objetos, incluso otros arrays (arrays multidimensionales).

var mixto \= \["Texto", 100, true, null, \["otro", "array"\]\];

## Modificar elementos de un array

Se puede cambiar el valor de un elemento accediendo a su índice y asignando un nuevo valor.

var colores \= \["Rojo", "Verde", "Azul"\];  
colores\[1\] \= "Amarillo";  // ahora es \["Rojo", "Amarillo", "Azul"\]

## Recorrer un array con for

Es la forma más común de trabajar con todos los elementos de un array.

var frutas \= \["Manzana", "Pera", "Plátano", "Naranja"\];  
for (var i \= 0; i \< frutas.length; i++) {  
	console.log("Fruta " \+ i \+ ": " \+ frutas\[i\]);  
}

---

# T1-U1.5. JavaScript \- Funciones

## ¿Qué son las funciones?

Las funciones son **bloques de código reutilizables** que agrupan instrucciones para realizar una tarea concreta. Permiten:

- Evitar la repetición de código.  
- Facilitar el mantenimiento (si hay que cambiar algo, se cambia solo en la función).  
- Organizar mejor el programa.

  ### Ejemplo sin función (código repetido):

var resultado;  
var numero1 \= 3;  
var numero2 \= 5;  
resultado \= numero1 \+ numero2;  
alert("El resultado es " \+ resultado);  
numero1 \= 10;  
numero2 \= 7;  
resultado \= numero1 \+ numero2;  
alert("El resultado es " \+ resultado);

### Ejemplo con función:

function suma\_y\_muestra() {  
	resultado \= numero1 \+ numero2;  
	alert("El resultado es " \+ resultado);  
}  
var numero1 \= 3;  
var numero2 \= 5;  
suma\_y\_muestra();  
numero1 \= 10;  
numero2 \= 7;  
suma\_y\_muestra();

## Definir una función

Se usa la palabra clave function, seguida del nombre, paréntesis () y llaves {} con el código.

function nombreFuncion() {  
  // instrucciones  
}

**Ejemplo básico:**

function saludo() {  
  alert("Hola, mundo");  
}

## Llamar a una función

Se invoca escribiendo su nombre seguido de paréntesis ().

saludo();  // ejecuta la función

## Argumentos o parámetros

Permiten pasar **valores a la función** para que trabaje con ellos.

### Definición con parámetros:

function suma\_y\_muestra(primerNumero, segundoNumero) {  
  var resultado \= primerNumero \+ segundoNumero;  
  alert("El resultado es " \+ resultado);  
}

### Llamada con argumentos:

suma\_y\_muestra(3, 5);   // 3 → primerNumero, 5 → segundoNumero  
suma\_y\_muestra(10, 7);

 **Reglas importantes:**

- El **orden** de los argumentos es fundamental.  
- Pueden usarse variables o valores directos.  
- JavaScript no da error si se pasan más o menos argumentos (aunque no es recomendable).

## Valores de retorno (return)

Las funciones pueden **devolver un resultado** con la palabra return.

function calculaPrecioTotal(precio) {  
  var impuestos \= 1.16;  
  var gastosEnvio \= 10;  
  var precioTotal \= (precio \* impuestos) \+ gastosEnvio;  
  return precioTotal;  
}  
var total \= calculaPrecioTotal(23.34);  // guarda el resultado

return finaliza la ejecución de la función (lo que va después no se ejecuta).

### Ejemplo más completo:

function calculaPrecioTotal(precio, porcentajeImpuestos) {  
	var gastosEnvio \= 10;  
	var precioConImpuestos \= (1 \+ porcentajeImpuestos / 100\) \* precio;  
	var precioTotal \= precioConImpuestos \+ gastosEnvio;  
	return precioTotal.toFixed(2);  // redondea a 2 decimales  
}  
var total \= calculaPrecioTotal(23.34, 16);

## Ámbito de las variables (scope)

El **ámbito** determina desde dónde se puede acceder a una variable.

### Variables locales

- Se declaran **dentro de una función** con var. Aunque mejor let  
- Solo existen **dentro de esa función**.

function creaMensaje() {  
  var mensaje \= "Hola";  // local  
  alert(mensaje);        // funciona  
}  
alert(mensaje);  // ❌ error, no existe fuera

### Variables globales

- Se declaran **fuera de cualquier función**.  
- Están disponibles en **todo el programa**.

var mensaje \= "Hola global";  
function muestra() {  
  alert(mensaje);  // ✅ funciona  
}

### Variables sin var dentro de una función

Si se asigna un valor a una variable **sin usar var** dentro de una función, se convierte en **global**.

function crea() {  
	mensaje \= "Soy global";  // sin var → global  
}  
crea();  
alert(mensaje);  // ✅ "Soy global"

### Conflicto entre local y global

Si una variable local tiene el **mismo nombre** que una global, dentro de la función **prevalece la local**.

var mensaje \= "fuera";  
function mostrar() {  
  var mensaje \= "dentro";  
  alert(mensaje);  // "dentro"  
}  
mostrar();  
alert(mensaje);    // "fuera"

---

# T1-U2. JavaScript \- Eventos en JavaScript

## ¿Qué son los eventos?

Los **eventos** son acciones o sucesos que ocurren en la página web y que JavaScript puede **escuchar** para ejecutar código como respuesta.

Ejemplos de eventos:

- El usuario hace **clic** en un botón.  
- El usuario **escribe** en un campo de texto.  
- La página **termina de cargarse**.  
- El usuario **mueve el ratón**.

## ¿Cómo funcionan?

JavaScript puede "estar atento" a estos eventos mediante **listeners (escuchadores)**. Cuando el evento ocurre, se ejecuta una función (llamada **manejador o handler**).

### Esquema básico:

1. **Seleccionamos** el elemento HTML que queremos observar.  
2. **Asignamos** un evento a ese elemento.  
3. **Definimos** qué función se ejecutará cuando ocurra el evento.

## Formas de asignar eventos

### Con addEventListener (recomendada)

const boton \= document.getElementById("miBoton");  
boton.addEventListener("click", function() {  
  alert("¡Botón pulsado\!");  
});

✅ Permite añadir varios eventos al mismo elemento. ✅ Separa el HTML del JavaScript.

### Con atributos HTML (no recomendada)

\<button onclick="alert('Hola')"\>Pulsa aquí\</button\>

❌ Mezcla HTML con JavaScript (peor mantenimiento).

### Con propiedades del elemento

boton.onclick \= function() {  
  alert("¡Hola\!");  
};

❌ Solo permite una función por evento.

## El parámetro event

Cuando ocurre un evento, JavaScript pasa automáticamente un **objeto evento** a la función manejadora. Este objeto contiene información útil:

boton.addEventListener("click", function(event) {  
  console.log(event.type);     // "click"  
  console.log(event.target);   // El elemento que recibió el clic  
});

Propiedades útiles:

- event.type: tipo de evento.  
- event.target: elemento que disparó el evento.  
- event.preventDefault(): evita el comportamiento por defecto (ej: enviar un formulario).  
- event.stopPropagation(): evita que el evento se propague a elementos padres.

## Fases de un evento (burbujeo y captura)

Los eventos en DOM tienen **dos fases**:

1. **Captura**: el evento viaja desde la raíz hasta el elemento objetivo.  
2. **Burbujeo (bubbling)**: el evento "sube" desde el elemento objetivo hasta la raíz.  
   Por defecto, los eventos se manejan en la fase de **burbujeo**.

// Para usar captura, se añade un tercer parámetro true  
elemento.addEventListener("click", miFuncion, true);

## Ejemplo práctico completo

\<button id="saludo"\>Saludar\</button\>  
\<p id="mensaje"\>\</p\>

const boton \= document.getElementById("saludo");  
const parrafo \= document.getElementById("mensaje");  
boton.addEventListener("click", function(event) {  
  parrafo.textContent \= "¡Hola\! Gracias por hacer clic.";  
  console.log("Evento tipo: " \+ event.type);  
});

## Listado de eventos

### Eventos del ratón (Mouse Events)

| Evento | Descripción |
| :---- | :---- |
| click | Clic izquierdo |
| dblclick | Doble clic |
| mousedown | Al pulsar el botón del ratón |
| mouseup | Al soltar el botón del ratón |
| mousemove | Movimiento del ratón |
| mouseenter | Entra en un elemento (no burbujea) |
| mouseover | Entra en un elemento (burbujea) |
| mouseleave | Sale de un elemento (no burbujea) |
| mouseout | Sale de un elemento (burbujea) |
| contextmenu | Clic derecho |

**Ejemplo:**

elemento.addEventListener("click", function() {  
  console.log("Clic detectado");  
});

### Eventos del teclado (Keyboard Events)

| Evento | Descripción |
| :---- | :---- |
| keydown | Al presionar una tecla |
| keyup | Al soltar una tecla |

**Ejemplo:**

document.addEventListener("keyup", function(event) {   
	console.log("Tecla soltada: " \+ event.key);  
});

### Eventos de formulario (Form Events)

| Evento | Descripción |
| :---- | :---- |
| submit | Cuando se envía un formulario |
| input | Cambia el valor de un input en tiempo real |
| change | Cambia el valor (cuando pierde el foco) |
| focus | El input recibe foco |
| blur | El input pierde foco |
| reset | Reset del formulario |

**Ejemplo:**

form.addEventListener("submit", function(event) {  
event.preventDefault();  
  console.log("Formulario enviado");  
});

### Eventos de la ventana (Window Events)

| Evento | Descripción |
| :---- | :---- |
| load | Página cargada (incluye CSS/IMG) |
| DOMContentLoaded | HTML cargado (sin CSS/IMG) |
| resize | Cambio de tamaño de ventana |
| scroll | Desplazamiento |
| beforeunload | Antes de salir de la página |

**Ejemplo:**

window.addEventListener("load", function() {  
  console.log("Página completamente cargada");  
});

### Eventos del DOM (Relacionados con el documento)

| Evento | Descripción |
| :---- | :---- |
| DOMContentLoaded | El DOM está listo (HTML cargado) |
| animationstart | Inicio de animación CSS |
| animationend | Fin de animación CSS |
| transitionend | Fin de transición CSS |

**Ejemplo:**

document.addEventListener("DOMContentLoaded", function() {  
  console.log("El DOM está listo");  
});

---

# T1-U3. JavaScript \- Trabajo con Nodos (DOM)

## Introducción al DOM

El **DOM (Document Object Model)** es una representación de la página web en forma de **árbol de nodos**, que permite manipular fácilmente el contenido, estructura y estilo de una página mediante JavaScript.

Los navegadores transforman automáticamente el código HTML en un árbol de nodos para facilitar su acceso y modificación.

## Árbol de nodos

Cuando el navegador carga una página, convierte cada parte del HTML en un **nodo**. Estos nodos se organizan de forma jerárquica, formando un árbol.

### Ejemplo de HTML:

\<\!DOCTYPE html\>  
\<html\>  
	\<head\>  
    \<title\>Página sencilla\</title\>  
  \</head\>  
  \<body\>  
    \<p\>Esta página es \<strong\>muy sencilla\</strong\>\</p\>  
  \</body\>  
\</html\>

### Estructura de nodos generada:

- **Documento** (nodo raíz)  
  - **html** (nodo elemento)  
    - **head** (nodo elemento)  
      - **title** (nodo elemento)  
        - **Texto**: "Página sencilla"  
    - **body** (nodo elemento)  
      - **p** (nodo elemento)  
        - **Texto**: "Esta página es "  
        - **strong** (nodo elemento)  
          - **Texto**: "muy sencilla"

   Reglas de transformación:

- Cada etiqueta HTML se convierte en un **nodo de tipo Element**.  
- El texto dentro de una etiqueta se convierte en un **nodo de tipo Text**, hijo del nodo Element.  
- Las etiquetas anidadas generan nodos hijos dentro del nodo padre correspondiente.

## Acceso directo a nodos: getElementById()

Es la función **más utilizada** para acceder a un elemento específico de la página.

var elemento \= document.getElementById("id\_del\_elemento");

**Ejemplo:**

var cabecera \= document.getElementById("cabecera");  
\<div id="cabecera"\>  
  \<a href="\#"\>Logo\</a\>  
\</div\>

Devuelve el nodo completo, permitiendo leer o modificar sus propiedades y atributos.

## Creación de nuevos nodos

Para añadir un nuevo elemento a la página, se necesitan **cuatro pasos**:

### Paso 1: Crear el nodo Element

var parrafo \= document.createElement("p");

### Paso 2: Crear el nodo Text

var contenido \= document.createTextNode("Hola Mundo\!");

### Paso 3: Añadir el texto al elemento

parrafo.appendChild(contenido);

### Paso 4: Añadir el elemento a la página

document.body.appendChild(parrafo);

### Código completo:

var parrafo \= document.createElement("p");  
var contenido \= document.createTextNode("Hola Mundo\!");  
parrafo.appendChild(contenido);  
document.body.appendChild(parrafo);

 El nuevo párrafo aparecerá al final del body.

## Funciones útiles para crear nodos

| Función | Descripción |
| :---- | :---- |
| document.createElement("etiqueta") | Crea un nodo Element de la etiqueta indicada |
| document.createTextNode("texto") | Crea un nodo Text con el contenido indicado |
| nodoPadre.appendChild(nodoHijo) | Añade un nodo como hijo de otro nodo |

## Eliminación de nodos

Para eliminar un nodo se usa removeChild(), pero debe invocarse desde su **nodo padre**.

La forma más segura es usar parentNode para acceder al padre:

var parrafo \= document.getElementById("provisional");  
parrafo.parentNode.removeChild(parrafo);  
\<p id="provisional"\>Este párrafo será eliminado\</p\>

Al eliminar un nodo, también se eliminan **todos sus nodos hijos** automáticamente.

## Acceso a atributos HTML

Una vez obtenido un nodo, se puede acceder a sus atributos directamente por su nombre:

var enlace \= document.getElementById("miEnlace");  
console.log(enlace.href);  // Muestra la URL del enlace  
console.log(enlace.id);    // Muestra "miEnlace"  
\<a id="miEnlace" href="https://ejemplo.com"\>Enlace\</a\>

Excepción: el atributo class se accede como className (por ser palabra reservada).

console.log(enlace.className);  // En lugar de enlace.class

## Acceso a propiedades CSS

Se accede mediante la propiedad style del nodo.

var imagen \= document.getElementById("logo");  
console.log(imagen.style.margin);  // Muestra el margen  
\<img id="logo" style="margin:10px; border:0;" src="logo.png"\>

### Propiedades CSS compuestas:

Los nombres con guiones se transforman a **camelCase**:

| CSS | JavaScript |
| :---- | :---- |
| font-weight | fontWeight |
| line-height | lineHeight |
| border-top-style | borderTopStyle |
| list-style-image | listStyleImage |

**Ejemplo:**

var parrafo \= document.getElementById("texto");  
console.log(parrafo.style.fontWeight);  // "bold"  
\<p id="texto" style="font-weight: bold;"\>Texto\</p\>

---

# T2-U4. JavaScript \- Objetos

## ¿Qué es un objeto?

Un **objeto** es una entidad que agrupa datos y funcionalidad. Se crea a partir de una clase, pero en JavaScript podemos crear objetos directamente sin necesidad de definir una clase previa.

Un objeto puede tener:

- **Propiedades**: son variables que almacenan datos (pueden ser de cualquier tipo: string, number, boolean, array, etc.).  
- **Métodos**: son funciones que pertenecen al objeto y agrupan instrucciones para realizar una tarea.

## Sintaxis básica de un objeto

Para declarar un objeto en JavaScript, se usa una variable (normalmente const o let) y se asignan propiedades y métodos entre llaves {}.

### Ejemplo básico:

const persona \= {  
  nombre: "Ana",  
  edad: 25,  
  saludar: function() {  
    alert("Hola, soy " \+ this.nombre);  
  }  
};

## Ejemplo completo de objeto

En este ejemplo, creamos un objeto alumno1 con propiedades y un método:

const alumno1 \= {  
  nombre: "Luis",  
  apellidos: "Gómez Huertas",  
  edad: 20,  
  debe: false,  
  mostrar\_edad: function() {  
    alert(this.nombre \+ " tiene " \+ this.edad \+ " años");  
  }  
};

**Nota**: Se usa this para hacer referencia a las propiedades del propio objeto.

## Acceso a propiedades y métodos

### Para acceder a una propiedad:

alumno1.nombre;      // "Luis"  
alumno1.apellidos;   // "Gómez Huertas"  
alumno1.edad;        // 20  
alumno1.debe;        // false

### Para ejecutar un método:

alumno1.mostrar\_edad();  // Muestra un alert: "Luis tiene 20 años"

## Diferencia entre const y let en objetos

- Con const no se puede reasignar el objeto completo, pero sí modificar sus propiedades internas.  
- Con let se puede reasignar el objeto completo si es necesario.

  // Con const  
  const alumno1 \= { nombre: "Luis" };  
  alumno1.nombre \= "Carlos";  // ✅ Permitido (modificar propiedad)  
  alumno1 \= { nombre: "Ana" }; // ❌ Error: no se puede reasignar

  // Con let  
  let alumno2 \= { nombre: "Luis" };  
  alumno2 \= { nombre: "Ana" }; // ✅ Permitido (reasignar objeto completo)

## Uso de this dentro de un método

La palabra clave this se refiere al **objeto actual**. Permite acceder a sus propias propiedades desde dentro de sus métodos.

const alumno \= {  
  nombre: "Luis",  
  mostrarNombre: function() {  
    alert(this.nombre);  // "Luis"  
  }  
};

## Ejemplo con llamada desde evento

Podemos usar objetos dentro de eventos como DOMContentLoaded para ejecutar métodos cuando la página esté cargada.

document.addEventListener("DOMContentLoaded", function() {  
  alumno1.mostrar\_edad();  // Llama al método del objeto  
});

---

# T2-U5. JavaScript \- Clases

## ¿Qué es una clase?

Una **clase** es un **molde o plantilla** que sirve para crear objetos. Permite definir:

- **Propiedades**: atributos donde almacenamos valores.  
- **Métodos**: conjuntos de instrucciones (pueden devolver valores, recibir parámetros, no devolver nada, o ser estáticos).

## Objetivos de crear una clase

1. **Reutilizar código**: la clase se define en un archivo externo y se usa para crear múltiples objetos.  
2. **Organizar el flujo del código**: estructura clara y mantenible.  
3. **Aplicar POO (Programación Orientada a Objetos)**.

## Sintaxis básica de una clase

class Alumno {  
  // Propiedades  
  nombre;  
  apellidos;  
  // Constructor: se ejecuta al crear un objeto  
  constructor(nombre, apellidos) {  
    this.nombre \= nombre;  
    this.apellidos \= apellidos;  
  }  
  // Método  
  saludar() {  
    alert("Hola, soy " \+ this.nombre);  
  }  
}

### Crear objetos a partir de la clase:

let ana \= new Alumno("Ana", "Gómez Huertas");  
let pedro \= new Alumno("Pedro", "Burgos Martín");  
console.log(ana.nombre);      // "Ana"  
console.log(pedro.apellidos);   // "Burgos Martín"  
maria.saludar();                // alert: "Hola, soy Ana"

La palabra clave new se usa para **instanciar** (crear) un objeto a partir de una clase.

## Programación Orientada a Objetos (POO)

### Encapsulación

Significa que **desde fuera de la clase no se puede acceder directamente a algunas propiedades** (privadas). Para ello se usan:

- **Propiedades privadas**: se declaran con \# delante del nombre.  
- **Métodos getter/setter**: permiten acceder y modificar propiedades privadas de forma controlada.

class Cuenta {  
  nombre;  
  numeroCuenta;  
  \#saldo \= 0;  // Propiedad privada  
  ingresar(cantidad) {  
    this.\#saldo \+= cantidad;  
  }  
  verSaldo() {  
    return this.\#saldo;  
  }  
}  
let miCuenta \= new Cuenta();  
miCuenta.ingresar(100);  
console.log(miCuenta.verSaldo());  // 100  
// console.log(miCuenta.\#saldo);    ❌ Error: es privada

### Herencia y polimorfismo

- **Herencia**: una clase puede heredar de otra (clase madre o padre).  
- **Polimorfismo**: un método puede tener diferentes comportamientos en clases hijas.  
- **Métodos abstractos**: se declaran en la clase madre y se implementan en las hijas.  
  Ejemplo de jerarquía:  
  Animal → AnimalVertebrado → Mamifero → Domestico

## Clases predefinidas en JavaScript

JavaScript tiene clases ya creadas que podemos usar directamente.

### Array

Trabajar con listas de valores.

let notas \= new Array("notable", "sobresaliente", "suficiente");  
console.log(notas.length);  // 3  
notas.push("insuficiente"); // añade un elemento

Métodos útiles: pop(), push(), length, etc.

### String

Trabajar con cadenas de texto. Cuando creamos una variable de texto, automáticamente es un objeto String.

let nombre \= "pepe";  
console.log(nombre.toUpperCase());      // "PEPE"  
console.log(nombre.substring(0, 2));    // "pe"

Métodos: substring(), toUpperCase(), indexOf(), concat(), etc.

### Math

Operaciones matemáticas. **No se puede instanciar**, se usa directamente.

Math.sqrt(25);     // 5 (raíz cuadrada)  
Math.PI;           // 3.1416  
Math.random();     // número aleatorio

### Date

Trabajar con fechas y horas.

let hoy \= new Date();  
console.log(hoy.getFullYear());  // año actual  
console.log(hoy.getMonth());     // mes (0 \= enero)  
console.log(hoy.getDate());      // día del mes

Métodos: now(), getDate(), getMonth(), getHours(), etc.

### RegExp (Expresiones regulares)

Para validaciones y búsqueda de patrones en textos.

let expresion \= /\\d{3}/;  // busca 3 dígitos seguidos  
console.log(expresion.test("123"));  // true  
console.log(expresion.test("abc"));  // false

---

# T2-U6.1 JavaScript \- Array de objetos

Estructura de datos que permite almacenar **objetos** en cada una de sus celdas. Puede ser:

- **Unidimensional (vector)**  
- **Bidimensional (matriz)**

  ### Características importantes

- Permite **almacenar y estructurar datos complejos**.  
- Facilita la **búsqueda y filtrado** de información.  
- Cada celda del array representa una **entidad de datos** (ej: un alumno, un producto, etc.).  
- Es **compatible con JSON y otras APIs**.  
- Fácil de implementar y **escalable**.  
  **Ejemplo básico: Array simple**

let nombres \= \["ana", "luis", "maría"\];  
for (let i \= 0; i \< nombres.length; i++) {  
  console.log(nombres\[i\]);  
}

### **Ejemplo: Array de objetos**

let alumnos \= \[  
  { nombre: "David", edad: 23, curso: "ASIR" },  
  { nombre: "Esther", edad: 26, curso: "DAM" },  
  { nombre: "Manuel", edad: 34, curso: "ASIR" }  
\];

### Recorrer un array de objetos con forEach

alumnos.forEach(alumno \=\> {  
  console.log(alumno.nombre \+ " \- " \+ alumno.edad \+ " \- " \+ alumno.curso);  
});

En cada iteración, la variable alumno representa un objeto del array.

### Convertir array de objetos a JSON

let datosJSON \= JSON.stringify(alumnos);  
console.log(datosJSON);

**JSON.stringify()** convierte el array en una cadena JSON.

### Convertir JSON a array de objetos

let datosJson \= '\[{"nombre":"pepe","edad":23,"apellidos":"Huertas Martin"},{"nombre":"silvia","edad":34,"apellidos":"Sanchez Sanchez"}\]';  
let resultado \= JSON.parse(datosJson);  
console.log(resultado);

**JSON.parse()** convierte una cadena JSON en un array de objetos JavaScript.

---

# T2-U6.2 JavaScript \- Json

JSON (JavaScript Object Notation) es un **formato ligero de intercambio de datos** basado en texto, que permite estructurar información para transmitirla entre sistemas (cliente-servidor, aplicaciones móviles, etc.).

*JSON \= DOTACIÓN OCTAL DE OBJETOS*

### Características principales:

- Formato de datos **independiente del lenguaje** (compatible con casi todos los lenguajes de programación)  
- **Legible para personas** (estructura clave-valor)  
- Extensión típica: **.json**  
- Más ligero y rápido que XML  
- Nativo de JavaScript, pero usable en cualquier lenguaje

  ### Sintaxis Básica

{  
  "clave": "valor",  
  "clave2": "valor2"  
}

### Reglas fundamentales:

- Las **claves (keys)** deben ir **entre comillas dobles**  
- Los **valores (values)** pueden ser de distintos tipos  
- Se usan **llaves {}** para objetos y **corchetes \[\]** para arrays  
- Cada par clave-valor va separado por **coma (,)**  
- Después de cada clave van **dos puntos (:)**

## Tipos de Datos en JSON

### Tipos simples

| Tipo | Ejemplo |
| :---- | :---- |
| **String** | "nombre": "Juan Pérez" |
| **Número** | "edad": 30 |
| **Booleano** | "activo": true |
| **Null** | "telefono": null |

### Tipos compuestos

| Tipo | Ejemplo |
| :---- | :---- |
| **Objeto** | "direccion": {"calle": "Gran Via", "numero": 10} |
| **Array** | "hobbies": \["leer", "nadar", "viajar"\] |

## Estructuras JSON

### Objeto JSON

{  
  "nombre": "Tom",  
  "apellido": "Jackson",  
  "edad": 25  
}

### Array JSON

{  
  "estudiantes": \[  
    {"nombre": "Ana", "edad": 22},  
    {"nombre": "Luis", "edad": 24},  
    {"nombre": "Carlos", "edad": 23}  
  \]  
}

## Acceso a Datos JSON en JavaScript

### Crear objeto JSON

var puerta \= {  
  "id": 1,  
  "nombre": "puerta de roble",  
  "precio": 250.50,  
  "etiquetas": \["puerta", "madera", "roble"\]  
};

### Acceder a propiedades

// Notación por corchetes  
console.log(puerta\["nombre"\]);

// Notación por punto  
console.log(puerta.precio);

// Acceder a array  
console.log(puerta.etiquetas\[2\]);  // "roble"

## Funciones en Objetos JSON

### Definir métodos

var puerta \= {  
  "id": 1,  
  "nombre": "puerta de roble",  
  "getDatos": function() {  
    alert([this.id](http://this.id));  
    alert(this.nombre);  
    alert(this.precio);  
  },  
  "adios": function() {  
    alert("adios");  
  }  
};

// Llamar métodos  
puerta.getDatos();  
puerta.adios();

//Métodos con parámetros  
var calculadora \= {  
  "sumar": function(a, b) {  
    return a \+ b;  
  }  
};

console.log(calculadora.sumar(5, 3));  // 8

## JSON vs XML

| Característica | JSON | XML |
| :---- | :---- | :---- |
| Peso | Ligero | Pesado |
| Velocidad | Alta | Baja |
| Legibilidad | Sencilla | Compleja |
| Parseo | Rápido | Lento |
| Extensibilidad | No | Sí |
| Soporte de tipos | Sí | Sí |

## Conversión JSON ⇄ JavaScript

### De JavaScript a JSON (stringify)

let alumnos \= \[  
  {nombre: "David", edad: 23},  
  {nombre: "Esther", edad: 26}  
\];

let jsonAlumnos \= JSON.stringify(alumnos);

console.log(jsonAlumnos);  
// \[{"nombre":"David","edad":23},{"nombre":"Esther","edad":26}\]

### De JSON a JavaScript (parse)

let datosJson \= '\[{"nombre":"pepe","edad":23},{"nombre":"silvia","edad":34}\]';

let alumnos \= JSON.parse(datosJson);

console.log(alumnos\[0\].nombre);  // "pepe"

## Almacenamiento Local (LocalStorage)

### Guardar JSON en localStorage

let usuario \= {  
  "nombre": "María",  
  "email": "maria@email.com"  
};

localStorage.setItem("usuario", JSON.stringify(usuario));

### Recuperar JSON de localStorage

let datos \= localStorage.getItem("usuario");  
let usuario \= JSON.parse(datos);

console.log(usuario.nombre);  // "María"

## JSON en PHP

### json\_encode() \- Convertir PHP a JSON

\<?php

$alumnos \= array(  
  array("nombre" \=\> "David", "edad" \=\> 23),  
  array("nombre" \=\> "Esther", "edad" \=\> 26\)  
);

echo json\_encode($alumnos);  
// \[{"nombre":"David","edad":23},{"nombre":"Esther","edad":26}\]

?\>

### json\_decode() \- Convertir JSON a PHP

\<?php

$json \= '\[{"nombre":"David","edad":23}, {"nombre":"Esther","edad":26}\]';

$alumnos \= json\_decode($json);

// Como objeto  
echo $alumnos\[0\]-\>nombre;  // "David"

// Como array asociativo (segundo parámetro true)  
$alumnos \= json\_decode($json, true);

echo $alumnos\[0\]\["nombre"\];  // "David"

?\>

## Buenas Prácticas

* Usar siempre **comillas dobles** en claves y strings  
* Evitar comas al final del último elemento  
* Validar JSON antes de usarlo  
* Para datos complejos, estructurar con objetos y arrays anidados  
* Usar JSON.parse() con try-catch para manejar errores

## Resumen Rápido

| Concepto | Sintaxis |
| :---- | :---- |
| Objeto JSON | {"clave": "valor"} |
| Array JSON | \["valor1", "valor2"\] |
| Objeto con array | {"datos": \[1, 2, 3\]} |
| Array de objetos | \[{"id":1}, {"id":2}\] |
| JS → JSON | JSON.stringify(objeto) |
| JSON → JS | JSON.parse(jsonString) |

---

# T3-U6.3 JavaScript \- Ajax

## Método 1: XMLHttpRequest (AJAX clásico)

### 1\. Gestionar el evento de carga de la página

Usamos el evento DOMContentLoaded para asegurarnos de que el DOM esté completamente cargado antes de ejecutar el código JavaScript.

document.addEventListener("DOMContentLoaded", function() {  
    // Aquí irá el código de la conexión AJAX  
});

### 2\. Declarar el objeto de conexión

Creamos un objeto de tipo XMLHttpRequest, que es un objeto nativo de JavaScript encargado de realizar peticiones HTTP/HTTPS de forma asíncrona.

let conexion \= new XMLHttpRequest();

### 3\. Verificar el estado de la conexión

Cuando la conexión cambia de estado(onreadystatechange), comprobamos dos propiedades clave:

- readyState \== 4 → la petición se ha completado.  
- status \== 200 → la respuesta del servidor fue exitosa (código HTTP 200 OK).

// Definir la función que maneja los cambios de estado  
conexion.onreadystatechange \= function () {  
    if (conexion.readyState \=== 4\) {  
        if (conexion.status \=== 200\) {  
            console.log("Respuesta recibida:", xhr.responseText);        } else {  
            console.error("Error en la solicitud. Estado:", conexion.status);  
        }  
    }  
};

### 4\. Abrir el archivo de datos

Usamos el método open() para especificar el tipo de petición y la ruta del archivo que contiene los datos (JSON o XML).

- Primer parámetro: método HTTP (generalmente "GET" para leer datos).  
- Segundo parámetro: nombre o ruta del archivo.  
  conexion.open("GET", "concesionario.json");

  ### 5\. Enviar la petición al servidor

  El método send() envía la petición al servidor de forma asíncrona.

  conexion.send();

  ### 6\. Volcar los datos del archivo

Usamos responseText para obtener el contenido del archivo como texto. Lo guardamos en una variable para trabajar con él.

let listadoCoches \= conexion.responseText;

#### Alternativas para mostrar los datos:

- **Mostrar en consola** (para depuración):  
  console.log(listadoCoches);  
- **Mostrar en un elemento HTML** (como un \<div\> con id capa):  
  document.getElementById("capa").innerHTML \= listadoCoches;

**Nota:** Si los datos están en JSON, normalmente se parsean con JSON.parse() antes de usarlos como objetos.

### 7\. Seleccionar elementos del DOM con querySelector y querySelectorAll

Estos métodos permiten seleccionar elementos del DOM usando la misma sintaxis que los selectores CSS.

- **querySelector**: devuelve **el primer elemento** que coincide con el selector.  
- **querySelectorAll**: devuelve **todos los elementos** que coinciden con el selector, en forma de NodeList (similar a un array).  
  let primerCoche \= document.querySelector(".coche");       // primer elemento con clase "coche"  
  let todosLosCoches \= document.querySelectorAll(".coche"); // todos los elementos con clase "coche"

  ### Ejemplo completo

  document.**addEventListener**("**DOMContentLoaded**", function () {  
  // Crear el objeto XMLHttpRequest  
  const xhr \= **new XMLHttpRequest();**  
  // Configurar la petición (true \= asíncrono)  
  xhr.**open**('GET', 'coches.json', true);  
  // Definir qué hacer cuando se reciba la respuesta  
  xhr.**onreadystatechange** \= function () {  
  // Verificar que la solicitud esté completa (readyState 4\)  
      if (xhr.**readyState \=== 4**) {  
          // Verificar que la respuesta sea exitosa (status 200\)  
          if (xhr.**status \=== 200**) {  
              // Convertir el JSON a objeto JavaScript  
              const coches \= **JSON.parse**(xhr.responseText);  
              // Ahora puedes acceder a los datos  
              console.log(coches.bmw.model);        // "Serie 3 320d"  
              console.log(coches.fiat.price);       // "14,500"  
              // Ejemplo: Mostrar todos los coches  
              for (let marca in coches) {  
                  console.log(\`${coches\[marca\].brand} ${coches\[marca\].model} \- ${coches\[marca\].price}€\`);  
              }  
          } else {  
              console.error('Error al cargar el archivo. Estado:', xhr.status);  
          }  
      }  
  };  
  // Enviar la petición  
  **xhr.send();**

  });

### Método 2: Fetch API (más moderno) (Ni caso a esto, no lo ha dado)

// Fetch es más limpio que XMLHttpRequest  
fetch('coches.json')  
   .then(response \=\> {  
       if (\!response.ok) {  
           throw new Error('Error al cargar el archivo');  
       }  
       return response.json(); // Convertir a objeto  
   })  
   .then(coches \=\> {  
       // Aquí ya tienes los datos del JSON  
       console.log(coches);  
       // Acceder a datos específicos  
       console.log(\`El BMW ${coches.bmw.model} tiene ${coches.bmw.kms} kms\`);  
       // Filtrar coches diésel  
       const dieselCoches \= Object.values(coches).filter(coche \=\> coche.fuel \=== "Diesel");  
       console.log("Coches diésel:", dieselCoches);  
   })  
   .catch(error \=\> {  
       console.error('Error:', error);  
   });

---

# T3-U7: XML

## ¿Qué es XML?

- **XML** (e**X**tensible **M**arkup **L**anguage) es un estándar creado por el **W3C** (World Wide Web Consortium).  
- Está basado en **SGML** (Standard Generalized Markup Language).  
- **No es un lenguaje de programación**, sino un **lenguaje de marcado** que describe la estructura y características de un documento.  
- Su objetivo principal es **guardar y organizar datos**.

  ## Características principales

- Usa **marcadores** o **etiquetas** (tags) para declarar elementos y su contenido.  
- Es **extensible** y **abierto**.  
- **No presenta información visual**, solo la almacena de forma jerárquica.  
- Es **case sensitive** (distingue mayúsculas/minúsculas).

  ## Usos de XML

- Estructurar datos para **almacenamiento** o **intercambio entre plataformas**.  
- Transferir información entre sistemas.  
- Integrar información de múltiples aplicaciones.  
- Simplificar documentos HTML.  
- Guardar información en bases de datos.  
- Describir interfaces de usuario (por ejemplo, en Android).

  ## Lenguajes de Marcado (Markup)

- Son lenguajes que usan **marcadores** para codificar documentos.  
- Los marcadores aportan **información semántica**:  
  - Identifican partes del documento.  
  - Indican cómo se relacionan esas partes.

  ## Etiquetas (Tags)

- Se escriben entre \< \>.  
- Necesitan **apertura y cierre**: \<etiqueta\>contenido\</etiqueta\>.  
- Si no tienen contenido: \<etiqueta/\>.  
- También se llaman **nodos** o **elementos**.  
- El texto dentro de una etiqueta se llama **contenido**.

  ### Ejemplo:

  \<saludos\>  
    \<frase\>Hola\</frase\>  
    \<frase\>¿Cómo estás?\</frase\>  
  \</saludos\>

  ## Declaración XML

- Es opcional pero **recomendable**.  
- Debe ser el **primer elemento** del documento.  
- Atributos principales:  
  - version: versión de XML utilizada.  
  - encoding: tipo de codificación (ej. utf-8).

  ### Ejemplo:

  \<?xml version="1.0" encoding="utf-8"?\>

  ## Anidamiento y Jerarquía

- Un elemento puede contener a otros (elementos **hijo**).  
- Esto define la **estructura interna** del documento.  
- Hay que tener cuidado con el **cierre correcto** de etiquetas.

  ## Elemento Raíz

- Solo puede haber **un elemento raíz**.  
- Es el nodo principal del que derivan todos los demás.  
- Todo documento XML debe tener un elemento raíz.  
- La estructura es similar a la de un **árbol**.

  ### Ejemplo:

  \<alumno\>  
    \<nombre\>Oscar\</nombre\>  
    \<carrera generacion="2020"\>ITI\</carrera\>  
    \<matricula\>652569\</matricula\>  
  \</alumno\>

  Aquí \<alumno\> es el elemento raíz.

  ## Caracteres Especiales y Referencias

  XML tiene caracteres reservados que no pueden usarse directamente en el texto:

- \< , \> , & , ' , "  
  Para usarlos, se emplean **referencias**:  
- Comienzan con & y terminan con ;.

  ### Tipos de referencias:

- **Entidades**: usan un nombre.  
  - Ejemplo: \&lt; → \<  
  - \&gt; → \>  
  - \&amp; → &  
  - \&apos; → '  
  - \&quot; → "  
- **Caracteres**: usan \# \+ número Unicode.  
  - Decimal: &\#60; → \<  
  - Hexadecimal: &\#x3C; → \<

  ## Espacios Significativos e Insignificantes

- **Espacios significativos**: forman parte del contenido del texto.  
  - Ejemplo: \<ciudad\>Ciudad de México\</ciudad\> (el espacio entre "Ciudad" y "de" es significativo).  
- **Espacios insignificantes**: no afectan el contenido.  
  - Ejemplo: espacios entre atributos o etiquetas.  
  - \<alumno id="001"\> es equivalente a \<alumno id="001" \>.

  ## Resumen de reglas importantes

- Un solo elemento raíz.  
- Cierre correcto de etiquetas.  
- Sensible a mayúsculas/minúsculas.  
- Los atributos van entre comillas.  
- Los caracteres especiales deben reemplazarse por referencias.  
- La declaración XML es opcional pero recomendada.  
  ---

    
  