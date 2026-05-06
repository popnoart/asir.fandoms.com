# DAPS - Digitalización Aplicada al Sistema Productivo
## Resumen de estudio basado en los tests de examen

---

## BLOQUE RAPIDO - Lo que MAS cae

| Concepto | Definicion exacta |
|---|---|
| **Digitalizacion** | Pasar de papel/manual a digital. **Cambia el formato**, no el proceso |
| **Transformacion Digital** | Cambio profundo en la forma de trabajar con tecnologia. **Cambia los procesos** |
| **IT** | Tecnologias de la Informacion: gestiona datos, comunicacion, oficina (ERP, CRM, correo) |
| **OT** | Tecnologias de Operacion: controla maquinaria y procesos fisicos (PLCs, SCADA, robots) |
| **Cuarta Rev. Industrial** | Comienza en **2011**. Se llama **Industria 4.0** |
| **Tercera Rev. Industrial** | Anos 70. Se llama **Industria 3.0** |
| **THD** | Tecnologias Habilitadoras Digitales: habilitan nuevos productos, servicios y formas de trabajar |
| **Nube** | Lugar en internet para guardar datos y usar apps sin instalarlas. Necesita internet y cuenta |
| **IaaS** | Infraestructura como servicio (AWS, Azure, Google Cloud) |
| **PaaS** | Plataforma como servicio → para desarrolladores (Google App Engine, Heroku) |
| **SaaS** | Software como servicio → apps listas para usar (Gmail, Canva, Microsoft 365) |
| **Fog Computing** | Capa intermedia entre dispositivos y nube. Procesa datos cerca del origen |
| **Edge Computing** | Similar a fog, en dispositivos mas grandes como routers |
| **Mist Computing** | Procesamiento directamente en el sensor o dispositivo pequeño |
| **Big Data** | Analisis de **grandes volumenes** de datos para extraer informacion util |
| **IoT** | Objetos conectados a internet que recopilan y comparten datos |

---

## TEMA 1 - Digitalizacion vs Transformacion Digital

### Digitalizacion

- **Definicion**: Pasar de formato fisico o manual a digital.
- **Clave**: Solo cambia el soporte, **no cambia el proceso**.
- **Ejemplos correctos**:
  - Escanear facturas y guardarlas en PDF
  - Llevar inventarios en Excel en lugar de libretas
  - **Nominas digitales en app** (esto es digitalizacion, no transformacion)
  - Pedidos en formulario online en lugar de papel

### Transformacion Digital

- **Definicion**: Cambio integral en la forma de trabajar mediante tecnologia.
- **Clave**: Modifica procesos, no solo formatos.
- **Ejemplos correctos**:
  - Sistema que envia recordatorios de pago automaticamente
  - Uso de realidad aumentada para formacion o ventas
  - Gestion de stock en tiempo real
  - Produccion automatizada por demanda

> **Trampa del examen**: Digitalizar nominas a papel → digital = digitalizacion. Pero si el sistema manda mensajes automaticos a clientes para pagar = transformacion digital.

### Ventajas de implantar tecnologia en la empresa

1. Mejora de procesos internos (automatizacion de tareas repetitivas)
2. Comunicacion interna y colaboracion en tiempo real
3. **Toma de decisiones basada en analisis de datos** ← respuesta frecuente
4. Flexibilidad y adaptacion al mercado
5. Nuevos roles (tecnico de sistemas, especialista en datos)
6. Coordinacion interdepartamental automatica

---

## TEMA 2 - Entornos IT y OT

### IT (Tecnologia de la Informacion)

- **Gestion**: Datos, informacion y comunicacion
- **Ubicacion**: Oficina, administracion, areas comerciales
- **Tecnologias**: Excel, correo electronico, ERP, CRM, bases de datos, videoconferencias, Drive
- **Departamentos**: Finanzas y contabilidad, RRHH, Marketing, Compras, Soporte Tecnico

### OT (Tecnologia de Operacion)

- **Control**: Procesos fisicos y maquinaria de la empresa
- **Ubicacion**: Planta de produccion, talleres, fabricacion
- **Tecnologias**: Robots, sensores, PLCs, **SCADA**, IoT, cinta transportadora
- **Ventajas**: Eficiencia productiva, reduccion de errores, seguridad, control en tiempo real

### Integracion IT-OT (Transformacion Digital Integral)

- Conecta la oficina con la planta.
- **Flujo ejemplo**: Pedido online (IT) → reposicion automatica almacen (OT) → activacion maquinas (OT)
- Permite **visibilidad total** y toma de decisiones integrada

### Digitalizacion por area

| Area | Tecnologias | Objetivo |
|------|-------------|----------|
| **En Planta** | PLCs, sensores, robots, SCADA, IoT, impresion 3D, gemelos digitales | Automatizar produccion |
| **En Negocio** | ERP, CRM, correo, cloud, e-commerce, herramientas colaborativas | Gestionar informacion |

> **Trampa**: ERP y CRM son **negocio (IT)**, no planta (OT). Los robots industriales y SCADA son **planta (OT)**.

---

## TEMA 3 - Las Cuatro Revoluciones Industriales

### 1a Revolucion Industrial (1760–1840)

- **Motor**: Maquina de vapor (James Watt)
- **Transicion**: Economia rural/agricola → urbana/industrial
- **Impacto**: Surge el proletariado (clase obrera)

### 2a Revolucion Industrial (1870–1914)

- **Energias**: Electricidad y petroleo
- **Innovaciones**: Motor de combustion interna, produccion en cadena, telefono, radio, avion
- **Impacto**: Mecanizacion y division del trabajo

### 3a Revolucion Industrial (anos 70) → **Industria 3.0**

- **Eje**: Electronica e informatica
- **Elementos clave**: Automatizacion, **TIC**, sistemas electronicos, energias renovables
- **Impacto**: Primeras maquinas programables, maquinas CNC

### 4a Revolucion Industrial (**2011**–actualidad) → **Industria 4.0**

- **Caracteristicas**: Conectividad, automatizacion avanzada, **analisis de datos**, robotica
- **Tecnologias clave**: Las THD
- **Diferencia**: No solo maquinas, sino sistemas inteligentes interconectados

> **Cae mucho**: La cuarta revolucion comienza en **2011**. La tercera = **Industria 3.0**. La cuarta = **Industria 4.0**.

---

## TEMA 4 - Tecnologias Habilitadoras Digitales (THD)

### ¿Por que se llaman "habilitadoras"?

Porque **permiten la aparicion de nuevos productos, servicios y formas de trabajar**.

> **NO** porque sean antiguas, ni porque usen electricidad, ni porque sustituyan al trabajo humano.

### Las 9 THD

| THD | Definicion clave | Ejemplo |
|-----|-----------------|---------|
| **Sistemas Ciberfisicos** | Integran mundo fisico y digital mediante sensores, actuadores y controladores | Vehiculos autonomos |
| **IoT** | Dispositivos conectados a internet que recopilan y comparten datos | Relojes inteligentes |
| **Robotica Avanzada** | Robots con sensores, procesadores y actuadores que realizan tareas complejas | Robot con vision artificial |
| **Inteligencia Artificial** | Sistemas que realizan tareas que requieren inteligencia humana | Control de procesos y analisis predictivo |
| **Impresion 3D** | Creacion de objetos tridimensionales capa por capa desde diseno digital | Protesis personalizadas |
| **Computacion en la Nube** | Almacenamiento y uso de apps a traves de internet | Google Drive, iCloud |
| **Big Data** | Analisis de grandes volumenes de datos para extraer informacion util | Personalizacion de servicios |
| **Ciberseguridad** | Proteccion de sistemas, redes y datos contra accesos no autorizados | Antivirus, encriptacion |
| **Realidad Virtual (RV) y Realidad Aumentada (RA)** | RV: inmersion total. RA: superposicion digital sobre mundo real | Juegos VR, app IKEA |

> **TIC NO es una THD**. Las TIC son de la 3a revolucion.

### Diferencia RV vs RA

- **RV (Realidad Virtual)**: Te sumerge en un entorno 100% digital. Necesita gafas VR.
- **RA (Realidad Aumentada)**: Anade elementos digitales sobre el mundo real. No necesita gafas VR, puede usarse con smartphone.

### THD en desarrollo de productos y servicios

- **IoT**: Electrodomesticos inteligentes, mantenimiento predictivo
- **IA**: Recomendaciones personalizadas, diagnostico medico
- **Impresion 3D**: Protesis personalizadas, crear piezas usando solo el material necesario
- **Cloud**: Servicios de streaming, colaboracion online
- **Big Data**: Predecir demandas y personalizar servicios
- **RV/RA**: Visualizar productos en entorno real antes de comprar, formacion, simulaciones

### Componentes de un sistema ciberfisico

- Sensores
- Actuadores
- Controladores

> **NO forma parte**: Bateria solar.

### Ventajas de las THD en IT y OT

1. Mayor conectividad y visibilidad
2. Optimizacion de procesos
3. Seguridad mejorada
4. Toma de decisiones basada en datos
5. Flexibilidad y escalabilidad
6. Innovacion y competitividad

---

## TEMA 5 - La Nube (Cloud Computing)

### Concepto

Lugar en Internet donde se guarda informacion y se usan aplicaciones **sin necesidad de instalarlas** en tu dispositivo local.

- **Requisitos**: Cuenta de acceso + conexion a internet
- **Proveedores**: Google Cloud, Microsoft Azure, AWS

### Funciones principales

1. Almacenamiento de archivos (Google Drive, iCloud)
2. Compartir archivos facilmente (sin USB)
3. Trabajo colaborativo en tiempo real (Google Docs)
4. Uso de programas sin instalacion (Canva)
5. **Copia de seguridad automatica** ← ventaja clave que cae en examen
6. Contenido en linea (Netflix, videojuegos)
7. Actualizaciones automaticas
8. Integracion con IA
9. Procesamiento sin hardware potente
10. Acceso desde cualquier lugar y dispositivo

> **Lo que NO hace la nube**: Enviar USB automaticamente, funcionar sin internet.

### Ventajas de la Nube

- Acceso remoto desde cualquier dispositivo
- **Copias de seguridad automaticas** (recordar: esto ES una ventaja)
- Ahorro de espacio local
- Facilidad para colaborar en equipo
- Escalabilidad, seguridad y actualizaciones automaticas

### Los 3 Niveles de la Nube (IaaS, PaaS, SaaS)

| Nivel | Nombre | Que ofrece | Ejemplos |
|-------|--------|------------|---------|
| **IaaS** | Infraestructura como Servicio | Servidores, almacenamiento, red | AWS, Azure, Google Cloud |
| **PaaS** | Plataforma como Servicio | Entorno listo para desarrollar | **Google App Engine**, Heroku |
| **SaaS** | Software como Servicio | Aplicaciones completas listas para usar | Gmail, Canva, Microsoft 365, Dropbox |

> **Que cae**: SaaS = apps listas para usar. PaaS = para desarrolladores. Google App Engine = **PaaS**.

### La Nube de Google

| Aplicacion | Para que sirve |
|------------|----------------|
| **Google Drive** | Almacenamiento de archivos |
| **Google Docs** | Documentos de texto colaborativos |
| **Google Sheets** | Hojas de calculo online |
| **Google Slides** | Presentaciones |
| **Google Forms** | Encuestas |
| **Google Calendar** | Gestion del tiempo |
| **Gmail** | Correo electronico |
| **Google Meet** | Videoconferencias |
| **Google Keep** | Notas |
| **Google Photos** | Almacenamiento de imagenes |

> **Genially NO es de Google**. Canva tampoco.

---

## TEMA 6 - Edge, Fog y Mist Computing

### ¿Por que existen?

Para procesar datos cerca de donde se generan, con **baja latencia**. Ideales para IoT, industria 4.0, ciudades inteligentes y realidad aumentada.

### Los tres niveles (de mas cercano a mas lejano del sensor)

| Tipo | Donde se procesa | Ejemplo |
|------|-----------------|---------|
| **Mist Computing** | Directamente en el sensor o dispositivo muy pequeño | Sensor industrial, coche autonomo |
| **Edge Computing** | En dispositivo mas grande cercano | **Router** |
| **Fog Computing** | Capa intermedia entre dispositivos y la nube | Camaras de trafico |
| **Cloud** | En servidores remotos en internet | Google Cloud, AWS |

> **Clave**: Fog = capa intermedia. Edge = router. Mist = sensor. Los tres tienen baja latencia.

---

## TEMA 7 - Herramientas Digitales para Empleabilidad

### Videocurriculum

- **Duracion recomendada**: ~2 minutos
- **Diferencia con el CV**: Formato video que muestra personalidad y habilidades comunicativas verbales y no verbales
- **Estructura**:
  1. Presentacion personal (nombre, profesion/titulacion)
  2. Introduccion (motivacion, aportacion a la oferta)
  3. Experiencia laboral (si la hay)
  4. Formacion academica
  5. Habilidades (minimo 3)
  6. Otros datos (idiomas, carnet, disponibilidad)
  7. Contacto (redes, telefono, email)
  8. Despedida
- **Clave**: Complementar con imagenes o presentacion visual. Cuidar iluminacion y sonido.

### Portfolio Digital

- **Definicion**: Recopilacion de trabajos, proyectos, logros y habilidades
- **Objetivo**: Mostrar experiencia y competencias profesionales
- **Estructura**:
  1. Sobre mi
  2. Conocimientos
  3. Actividades y proyectos
  4. Curriculum vitae
  5. Datos de contacto
- **Herramientas**: Weebly (subdominio gratuito), **Canva**

### Padlet

- Tablero colaborativo en linea para compartir texto, imagenes, videos, enlaces
- Ideal para portfolios colaborativos, organizacion de tareas y trabajo en equipo

### Herramientas de Presentacion Digital

| Herramienta | Caracteristica clave |
|-------------|---------------------|
| **Prezi** | Lienzo infinito con navegacion dinamica y zoom. No usa diapositivas lineales |
| **Gamma** | Crea presentaciones con **Inteligencia Artificial** rapida y visualmente atractivas |
| **Canva** | Diseno grafico versatil: presentaciones, posters, CV, redes sociales. Plantillas predefinidas |
| **Genially** | Contenidos **interactivos**: infografias, juegos educativos, gamificacion |

---

## TRAMPA DEL EXAMEN - Errores frecuentes

| Pregunta tipica | Respuesta correcta | Error comun |
|-----------------|-------------------|-------------|
| ¿Que es digitalizacion? | Pasar de papel a digital (solo el formato) | Confundir con transformacion digital |
| ¿Que hace OT? | Controla procesos fisicos y maquinaria | Decir que gestiona datos (eso es IT) |
| ¿SCADA es IT u OT? | **OT** | Pensar que es IT porque parece software |
| ¿Cuarto Rev. Industrial? | **2011**, Industria **4.0** | Confundir con 2010 o 2012 |
| ¿TIC es THD? | **NO**, TIC es de la 3a revolucion | Incluirla en la lista de THD |
| ¿Copia de seguridad es ventaja nube? | **SI**, es automatica | Creer que no se hacen automaticamente |
| ¿Genially es de Google? | **NO** | Confundir con herramientas Google |
| ¿PaaS ejemplo? | **Google App Engine** | Confundir con Gmail (SaaS) |
| ¿Fog computing? | Capa intermedia dispositivos-nube | Confundir con mist o edge |
| ¿Mist computing? | En el propio sensor/dispositivo pequeno | Confundir con edge (router) |
| ¿RA necesita gafas VR? | **NO**, la RV si las necesita | Confundir RV y RA |
| Bateria solar en sistema ciberfisico | **NO forma parte** | No saber los componentes del sistema |
