# asir.fandoms.com

Este proyecto es una plataforma web para la gestión y consulta de cursos, cuestionarios y materiales educativos de Digitech FP y otros módulos de ASIR. Incluye herramientas para importar, visualizar y extraer preguntas y respuestas de tests en formato HTML, generando archivos JSON estructurados para su posterior análisis o uso didáctico.

## Estructura principal

- `public_html/` — Raíz de la web, contiene los scripts PHP, assets, datos y herramientas.
  - `tools/tests.php` — Herramienta para extraer preguntas y respuestas de tests HTML y guardarlas en JSON.
  - `data/` — Carpeta con archivos JSON generados (configuración, tests extraídos, etc).
  - `files/` — Carpeta con los tests en formato HTML por curso y módulo.
  - `course_cards/` — Scripts y vistas para mostrar información de cursos.
- `storage/` — Carpeta para logs y datos persistentes.

## Extracción de tests

La herramienta principal para extraer preguntas y respuestas es `public_html/tools/tests.php`. Permite procesar archivos HTML de tests y generar archivos JSON estructurados con el siguiente formato:

```json
[
  {
    "enunciado": "Texto de la pregunta",
    "respuestas": [
      { "opcion": "a.", "texto": "Respuesta A", "correcta": false },
      { "opcion": "b.", "texto": "Respuesta B", "correcta": true }
    ]
  },
  ...
]
```

### Uso web

Accede a la herramienta desde la web autenticado como `popnoart` y selecciona el test a procesar. El script generará el JSON correspondiente en `public_html/data/tests/`.

### Uso manual (CLI)

Para pruebas locales, puedes crear un script temporal que importe la función `extraerPreguntasRespuestas` y procese un HTML:

```php
require 'public_html/tools/tests.php';
$html = file_get_contents('public_html/files/TE/TESTS/14076.html');
$res = extraerPreguntasRespuestas($html);
echo json_encode($res, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
```

## Dependencias

- PHP >= 7.4
- Extensión DOM (incluida en la mayoría de instalaciones PHP)
- Bootstrap y FontAwesome para la interfaz web

## Autor y contacto

Desarrollado por popnoart. Para dudas, mejoras o reportes, contacta por el canal habitual.

---