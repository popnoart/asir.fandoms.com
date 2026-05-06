# **RESUMEN IAW**

## Implantación de aplicaciones web

---

## T1-U1.1: JavaScript \- Introducción

- Lenguaje **interpretado**, **dinámico**, **case sensitive**.  
- Inclusión en HTML:  
  1. `<script>` (en `<head>` o antes de `</body>`)  
  2. Archivo externo: `<script src="..."></script>`  
  3. `defer` asegura carga completa del DOM.  
- Variables: `var`, `let`, `const`.  
- Tipos: numéricos, strings, arrays, booleanos.

---

## T1-U1.2: JavaScript \- Operadores

- **Asignación**: `=`, `+=`, `-=`, `*=`, `/=`, `%=`.  
- **Incremento/decremento**: `++`, `--` (prefijo vs sufijo).  
- **Matemáticos**: `+`, `-`, `*`, `/`, `%`.  
- **Relacionales**: `>`, `<`, `>=`, `<=`, `==`, `!=`.  
- **Lógicos**: `!` (NOT), `&&` (AND), `||` (OR).

---

## T1-U1.3: Estructuras de control y bucles

- `if`, `if...else`, `if...else if...else`.  
- Bucle `for`:  
  `for (inicialización; condición; actualización) { ... }`  
  `var dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];`  
  `for (var i = 0; i < dias.length; i++) {`    
  	`alert(dias[i]);`  
  `}`  
- Métodos útiles: `.length`, concatenación con `+`.

---

## T1-U1.4: JavaScript \- Arrays

- Definición: `let array = [valor1, valor2, ...]`  
- Acceso por índice empezando en `0`.  
- Propiedad `.length`.  
- Recorrido típico con `for`.  
- Pueden contener cualquier tipo de dato.

---

## T1-U1.5: JavaScript \- Funciones

- Declaración: `function nombre(parámetros) { ... }`  
- Llamada: `nombre(argumentos)`  
- `return` devuelve un valor.  
- Ámbito de variables:  
  1. **Local** (dentro de función con `var`).  
  2. **Global** (fuera de función).  
  3. Sin `var` dentro de función → global.  
- JavaScript no da error si se pasan más o menos argumentos (aunque no es recomendable).

---

## T1-U2: Eventos en JavaScript

- Escuchar eventos con `addEventListener` (recomendado).  
- Objeto `event`: `.type`, `.target`, `.preventDefault()`, `.stopPropagation()`.  
- Fases:   
  1. **Burbujeo** es útil para delegación de eventos: escuchar eventos en un padre sin poner listeners en cada hijo. Por defecto  
     `elemento.addEventListener("click", miFuncion);`  
  2. **Captura** es menos común, pero útil si quieres que un padre actúe antes de que el hijo reciba el evento.  
     `elemento.addEventListener("click", miFuncion, true);`  
- Eventos comunes: `click`, `keyup`, `submit`, `load`, `DOMContentLoaded`.

---

## T1-U3: Trabajo con nodos (DOM)

- DOM \= árbol de nodos del HTML.  
- Tipos:   
  1. Element \-\> cada etiqueta  
  2. Text \-\> el contenido de la etiqueta  
- Obtener nodo: `getElementById()`.  
- Crear nodos:  
1. `var parrafo = document.createElement("p");`  
2. `var contenido = document.createTextNode("Hola Mundo!");`  
3. `parrafo.appendChild(contenido);`  
4. `document.body.appendChild(parrafo);`  
- Eliminar: `parentNode.removeChild()`.  
- Acceder a atributos: `.href`, `.id`, `.className`.  
- Acceder a CSS: `.style.propiedadCamelCase`.

---

## T2-U4: Objetos en JavaScript

- Definición: `{ propiedad: valor, metodo: function() {...} }`  
- Acceso: `objeto.propiedad` o `objeto["propiedad"]`.  
- `this` se refiere al propio objeto.  
- `const` (puedes cambiar propiedades, pero no el objeto entero) vs `let` (puedes hacer ambas).  
- Ejemplo:

  `const alumno1 = {`  
    `nombre: "Luis",`  
    `apellidos: "Gómez Huertas",`  
    `edad: 20,`  
    `debe: false,`  
    `mostrar_edad: function() {`  
      `alert(this.nombre + " tiene " + this.edad + " años");`  
    `}`  
  `};`

  `alumno1.nombre;`    
  `alumno1.mostrar_edad();` 

---

## T2-U5: Clases en JavaScript

- Plantilla para crear objetos:  
  `class Nombre { propiedades; constructor() {...} metodo() {...} }`  
  `class Alumno {`  
    `// Propiedades`  
  	 `nombre;`  
  	`apellidos;`  
    `// Constructor: se ejecuta al crear un objeto`  
    `constructor(nombre, apellidos) {`  
      `this.nombre = nombre;`  
      `this.apellidos = apellidos;`  
    `}`  
    `// Método`  
    `saludar() {`  
      `alert("Hola, soy " + this.nombre);`  
    `}`  
  `}`  
- Instanciar:  `let ana = new Alumno("Ana", "Gómez Huertas");`  
- **Encapsulación**: propiedades privadas con `#`.  
- **Herencia** y **polimorfismo**.  
- Clases predefinidas: `Array`, `String`, `Math`, `Date`, `RegExp`.

---

## T2-U6.1: Array de objetos

- `let alumnos = [{}, {}, ...]`  
  `let alumnos = [`  
    `{ nombre: "David", edad: 23, curso: "ASIR" },`  
    `{ nombre: "Manuel", edad: 34, curso: "ASIR" }`  
  `];`  
- Recorrer con `forEach`.  
  `alumnos.forEach(alumno => {`  
    `console.log(alumno.nombre + " - " + alumno.edad + " - " + alumno.curso);`  
  `});`  
- Filtrado y búsqueda fácil.  
- Escalable y compatible con JSON.


---

## T2-U6.2: JSON

-  JSON*(*JavaScript Object Notation)  *\= DOTACIÓN OCTAL DE OBJETOS*  
- Formato ligero de intercambio de datos.  
- Claves con **comillas dobles**.  
- Se usan llaves {} para objetos y corchetes \[\] para arrays  
- Tipos: string, número, booleano, null, objeto, array.  
- Conversiones:  
  `JSON.stringify()` convierte un array en una cadena JSON.  
  `JSON.parse()` convierte una cadena JSON en un array de objetos JavaScript.  
- Acceso: `clave["valor"]` o `clave.valor`   
- Ejemplo: `{“clave": "valor",  "clave2": "valor2"}`  
- Almacenar en `localStorage`. `localStorage.setItem.  localStorage.getItem("usuario");`

---

## T3-U6.3: AJAX (conexión asíncrona)

- Permite cargar datos desde un archivo (JSON/XML) sin recargar la página.  
- Método clásico: `XMLHttpRequest`  
  `document.addEventListener("DOMContentLoaded", function() {`  
      `let conexion = new XMLHttpRequest();`  
      `conexion.open("GET", "datos.json", true);`  
      `conexion.onreadystatechange = function() {`  
          `if (conexion.readyState === 4 && conexion.status === 200) {`  
              `let datos = JSON.parse(conexion.responseText);`  
              `console.log(datos);`  
          `}`  
      `};`  
      `conexion.send();`  
  `});`  
- Explicación propiedades:  
- `readyState == 4` → petición completada.  
- `status == 200` → respuesta correcta del servidor.  
- `responseText` → contenido del archivo como texto.  
- Mostrar datos en HTML:  `document.getElementById("capa").innerHTML = datos[0].nombre;`  
- Selectores del DOM:  
- `querySelector(".clase")` → primer elemento que coincide.  
- `querySelectorAll(".clase")` → todos los elementos (similar a un array).

---

## T3-U7: XML

- Lenguaje de marcado, **no visual**, solo estructura.  
- Etiquetas personalizables, **case sensitive**.  
- Un solo **elemento raíz**.  
- Caracteres especiales: `&lt;`, `&gt;`, `&amp;`, etc.  
- Jerarquía de nodos tipo árbol.

---

