# Resumen de Estudio — IAW (Implantación de Aplicaciones Web)
> Basado en los tests de examen (Diciembre, Enero, Febrero). Lo que aparece aquí **va a caer**.

---

## 1. JavaScript — Introducción

| Concepto | Respuesta correcta |
|----------|-------------------|
| ¿Qué es JavaScript? | Lenguaje de marcas (interpretado, no compilado) |
| Extensión de archivo | `.js` |
| ¿Necesita archivo HTML? | **Sí**, necesita un HTML que lo incruste |
| ¿Tipado? | **No** se especifica el tipo → hace **conversión implícita** |
| Orientado a... | **Objetos y eventos** |

### Cómo incluir JS en HTML

```html
<!-- Archivo externo (recomendado) -->
<script src="js/codigo.js"></script>

<!-- Dentro del mismo HTML (se guarda como .html) -->
<script type="text/javascript">
    alert("mensaje");
</script>
```

- Atributo para enlazar el archivo: **`src`** (no `rel`, no `type`, no `href`)
- Si el código JS está dentro del HTML, el archivo se guarda con extensión **`.html`**

---

## 2. Variables

```javascript
let nombre = "Ana";      // ámbito local, puede cambiar → USO CORRECTO
const PI = 3.14;         // no puede cambiar
var numero = 5;          // ámbito global, consumo excesivo de recursos
```

| Concepto | Respuesta |
|----------|-----------|
| Variable que puede cambiar | `let` |
| Variable de ámbito **local** (menos recursos) | `let` |
| ¿Hay que declarar el tipo? | **No** (conversión implícita automática) |
| Tipo de texto | `String` |
| Un solo carácter | `String` (no existe `char` en JS) |
| Array | Colección de datos |

### Truco: conversión de tipos

```javascript
"5" + 3  →  "53"   // string + número = concatenación (no suma)
```

---

## 3. Operadores

| Operador | Qué hace |
|----------|----------|
| `=` | Asignación |
| `==` | Compara **valor** (sin importar tipo) |
| `===` | Compara **valor Y tipo** |
| `!=` | Distinto que |

> **Clave de examen:** `==` compara solo valor, `===` compara valor **y tipo**.

---

## 4. Estructuras de Control

### if / else if / else

```javascript
if (condicion) {
    // si es true
} else if (otraCondicion) {
    // segunda condición
} else {
    // si todo lo anterior es false
}
```

> La cláusula es `else if` (no `elseif`, no `elif`).

### Bucle `for` — número determinado de veces

```javascript
for (let i = 0; i < 10; i++) {
    // código
}
```

- Sintaxis correcta: `for(let i=0; i<10; i++) {}`
- Al salir del bucle, `i` alcanza el valor de `array.length` (no se queda en el último índice válido)
- Se puede interrumpir con **`break`** aunque la condición siga siendo true
- **Puede que no se entre nunca** si la condición es false desde el inicio
- Mínimo una ejecución **no garantizado** (depende de la condición)

### Bucle `while` — mientras se cumpla una condición

```javascript
while (condicion) {
    // código
}
```

- **Puede que nunca se entre** al bucle (si la condición es false al inicio)
- Si la condición **nunca cambia** dentro del bucle → **bucle infinito**

```javascript
numero = 0;
while (numero == 0) {
    window.alert("Dentro del bucle");
}
// → Nunca se sale del bucle (bucle infinito)
```

### Diferencia `for` vs `while`

| | `for` | `while` |
|--|-------|---------|
| Cuándo usarlo | Número determinado de veces | Mientras se cumpla condición |
| ¿Puede no entrar nunca? | Sí | Sí |

> **Ambas son correctas** cuando preguntan la diferencia: for = veces determinadas, while = condición.

### `break` y `continue`

- **`break`**: interrumpe el bucle aunque la condición siga cumpliéndose
- **`continue`**: salta a la siguiente iteración (no sale del bucle)

---

## 5. Arrays

```javascript
let dias = ["Lunes", "Martes", "Miércoles"];
console.log(dias[0]);      // "Lunes" (índice empieza en 0)
console.log(dias.length);  // 3
```

- Los índices empiezan en **0**
- `array.length` → número de elementos
- Recorrer con for: `for(let i=0; i < array.length; i++)`
- Cuando el bucle termina, `i` vale `array.length` (no el último índice)

---

## 6. Funciones

```javascript
// Sin parámetros
function saludar() {
    alert("Hola");
}

// Con parámetros de ENTRADA
function suma(a, b) {
    return a + b;
}

// Con parámetros de SALIDA (return)
function calculaTotal(precio) {
    return precio * 1.21;
}
```

| Concepto | Respuesta |
|----------|-----------|
| Función que **recibe** valores | Necesita parámetros de **entrada** |
| Función que **devuelve** valores | Necesita parámetros de **salida** (`return`) |

---

## 7. Eventos

```javascript
// Forma recomendada
elemento.addEventListener("click", function() {
    // código
});

// Evento de carga del documento HTML
document.addEventListener("DOMContentLoaded", function() {
    // el DOM está listo
});
```

| Concepto | Respuesta |
|----------|-----------|
| ¿Qué hace `addEventListener`? | Ejecuta una función cuando ocurre un evento específico |
| Evento de carga del HTML | **`DOMContentLoaded`** (no `load`, no `DOMContent`, no `DOMReadyLoaded`) |
| Evento de clic | `click` |

### Tipos de eventos (tabla resumen)

| Evento | Descripción |
|--------|-------------|
| `DOMContentLoaded` | HTML cargado (sin esperar CSS/imágenes) |
| `load` | Página completa cargada (incluye CSS e imágenes) |
| `click` | Clic en un elemento |
| `submit` | Envío de formulario |
| `input` | Cambio de valor en tiempo real |
| `keydown` / `keyup` | Pulsar / soltar tecla |

---

## 8. DOM — Trabajo con Nodos

### ¿Qué es un nodo?
> Cada **elemento, texto o comentario** dentro del árbol HTML. Una entidad en la **estructura jerárquica** del documento.

### Seleccionar elementos

```javascript
// Por ID — devuelve el primer elemento con ese id
let elem = document.getElementById("titulo");
// Si el id NO existe → devuelve null (no false, no error, no array vacío)

// Por selector CSS
let primero = document.querySelector(".clase");        // primer match
let todos = document.querySelectorAll(".clase");       // todos (NodeList)
```

### Leer y modificar contenido

```javascript
let elem = document.getElementById("mensaje");
elem.innerHTML = "Hola";       // cambia el HTML interno
elem.innerText = "Hola";       // accede/modifica el texto del nodo
elem.textContent = "Hola";     // similar a innerText
```

> **Clave de examen:** Para cambiar texto → `innerHTML` o `innerText`. Para acceder al contenido de un nodo de texto → `innerText`.

### Crear y añadir nodos

```javascript
// 1. Crear elemento
let titulo = document.createElement("h3");

// 2. Añadir contenido
titulo.textContent = "Mi título";

// 3. Añadir al DOM
document.body.appendChild(titulo);

// Añadir dentro de otro elemento
let contenedor = document.createElement("div");
document.body.appendChild(contenedor);
contenedor.appendChild(titulo);  // ← referencia al elemento padre
```

> `appendChild()` (no `append()`, no `createElement()`)

### Modificar atributos

```javascript
// setAttribute(atributo, valor) — modifica ATRIBUTOS HTML (no CSS)
img.setAttribute("src", "foto.jpg");   // correcto
// Puede modificar TODOS los atributos HTML
```

| Método | Qué hace |
|--------|----------|
| `setAttribute("attr", "val")` | Añade o modifica un atributo HTML |
| `getAttribute("attr")` | Lee el valor de un atributo |
| `removeChild(nodo)` | Elimina un nodo hijo |

---

## 9. Objetos

```javascript
// Crear objeto
let persona = { nombre: "Ana", edad: 20 };   // con llaves {}

// Acceder a propiedades
persona.nombre         // notación punto
persona["nombre"]      // notación corchetes → devuelve "Ana"

// Añadir nueva propiedad
persona.altura = 180;  // directamente con punto y asignación
```

| Concepto | Sintaxis correcta |
|----------|-------------------|
| Crear objeto | `let obj = { clave: valor }` |
| Acceder a propiedad | `obj.propiedad` o `obj["propiedad"]` |
| Añadir propiedad con valor | `obj.nuevaPropiedad = valor` |

### Formularios y arrays de elementos

```javascript
// Acceder a elementos de formulario por índice
document.miformulario.edad[0].checked   // primer radio button
document.miformulario.edad[1].value     // valor del segundo
```

---

## 10. Preguntas tipo trampa (caen siempre)

| Pregunta | Respuesta correcta | Error común |
|----------|--------------------|-------------|
| `"5" + 3` | `"53"` (string) | ~~8~~ |
| Bucle infinito con `while(numero==0)` sin cambiar `numero` | Nunca se sale | ~~se entra una vez~~ |
| `i` al salir del `for(i=0; i<arr.length; i++)` | `i == arr.length` | ~~último índice~~ |
| Evento de carga del DOM | `DOMContentLoaded` | ~~`DOMContent`~~, ~~`load`~~ |
| Variable ámbito local | `let` | ~~`var`~~ |
| ¿JS especifica tipo? | No, conversión **implícita** | ~~conversión explícita~~ |
| Atributo `<script>` para JS externo | `src` | ~~`rel`~~, ~~`type`~~ |
| `getElementById` si no existe el id | Devuelve `null` | ~~false~~, ~~array vacío~~ |
| `setAttribute` modifica... | Todos los atributos **HTML** | ~~CSS~~, ~~propiedades JS~~ |
| `appendChild()` añade... | Nodo como **hijo** del elemento | ~~`append()`~~ |
| Guardar archivo con JS dentro de HTML | Extensión `.html` | ~~`.js`~~ |

---

## Código de examen — fragmentos que aparecen

### Bucle for recorriendo array

```javascript
for (i = 0; i < array.length; i++) {
    if (parseInt(array[i]) > mayor)
        mayor = array[i];
}
// Al salir: i alcanza el valor de array.length
```

### Bucle infinito

```javascript
numero = 0;
while (numero == 0) {
    window.alert("Dentro del bucle");
}
// → Nunca se sale del bucle
```

### Crear y anidar elementos

```javascript
let titulo1 = document.createElement("h3");
let body = document.body;
body.appendChild(titulo1);  // ← falta appendChild()

// Anidar dentro de otro div
const contenedor = document.createElement("div");
document.body.appendChild(contenedor);
const titulo = document.createElement("div");
contenedor.appendChild(titulo);  // ← "contenedor", no "body", no "div"
```

### Formulario con radio buttons

```javascript
function casillas() {
    if (document.miformulario.edad[0].checked == true) {  // ← edad[0]
        alert("La edad seleccionada es: " + document.miformulario.edad[0].value)
    }
    // ...
}
```

### else if

```javascript
if (resto == 0) {
    document.write("El número es cero");
} else if (resto > 0) {   // ← "else if" (no elseif, no elif)
    document.write("El número es mayor que cero");
}
```

### Objeto

```javascript
let persona = { "nombre": "Ana", "edad": 20 };   // ← llaves, no corchetes
persona["nombre"];  // → "Ana"
persona.altura = 180;  // → añadir propiedad
```
