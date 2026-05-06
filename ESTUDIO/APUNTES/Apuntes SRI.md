# Resumen — SRI 

## Servicios de Red e Internet

---

# SUBNETTING IPV4

## Dividir red

`2 ^ ELEVADO >= PETICIÓN (División)`

`BITS MÄSCARA ORIGINAL + ELEVADO = BITS DE RED NUEVA MÁSCARA DE DIVISIONES`

*`Se desea dividir la Network 192.168.1.0/24 en 64 subredes ¿Cuántos bits de red le pertenecerían?`*

1. `Sacar elevado sin restar 2`  
   `2 ^ 6 = 64 (lo que nos piden)`  
2. `Bits de red: 24 + 6= 30`  
3. `/30 o 255.255.255.252 (8+8+8+6(128+64+32+16+8+4))`

## Rango de IPs disponibles (Via salto)

`SACAR MÁSCARA`  
`CONSTANTE - MÁSCARA = SALTO - 2 (N&B) = Nº primer octeto apagado`

*`¿Cuál es la última IP disponible que le corresponde a la Network 192.168.1.0/26?`*  
*`Encendemos 26`*  
`11111111.11111111.11111111.11000000`  
`255.255.255.(128+64 = 192)`  
`256-192=64-2=62`

`Network 192.168.1.0 Broadcast 192.168.1.63`   
`Salto: Network 192.168.1.64 …`  
**`192.168.1.1 / 192.168.1.62`**

#### Hosts totales

`2 ^ (32 - BITS RED) 32 son los bits de una IPv4`

*`¿Cuántos hosts le pertenecen a la Network 172.172.0.0/20?`*  
`32 - 20 = 12`   
`2^12 =  4096 totales, 4094 disponibles (-2 Network & Broadcast)`

## SUBNETTING IPV6

Una IPv6 tiene **8 hextetos** de 16 bits cada uno \= **128 bits** totales.

- Para conectividad con host de destino se usan los cuatro últimos hextetos  
- Para subnetear se usa  el 4º hexteto   
- En ipv6 siempre hay que poner (en config, no interfaces) `ipv6 unicast-routing` Por cualquier sistema que enrutemos: estática, alias, ospf,..., para avisar de que vamos a enrutar.

| Parte | Hextetos | Uso |
| :---- | :---- | :---- |
| Red (prefijo) | 1-3 | Identifican la red |
| **Subneteo** | **4º** | Solo el 4º hexteto se usa para subnetear |
| Conectividad/host | 5º y 6º | Conectividad con host de destino |
| Host | 7-8 | Identificación del host |

### Contar bits en una IPv6  `16 x ENCENDIDOS`

- Cada hexteto encendido (no abreviado con ::) \= **16 bits**  
- `1995:A:B:C:D:E:FFFF::` → 7 hextetos encendidos → **7 × 16 \= 112 bits** ✓

### Subnetear IPv6 

Usamos el 4º hexteto (máximo 4x15). 

- Tiene **4 posiciones** del hexteto (columnas) y **15 valores** hexadecimales (1-F, sin el 0\)

- Contamos posiciones hasta 15  en hexadecimal sin usar el 0

- Si necesitas más de 15 entradas → usas otra columna (siguiente posición del hexteto)

### **Ejemplo:** `Red: 2000:AABB:BBAA::/48 ¿Cuál es la ip de Ciudad 15, Oficina 14 y departamento 25?`

- Ciudad 15 \= F  
- Oficina 14 \= E  
- Dpto 25 → 15 posiciones col 1 (se pone 0\)  \+ 10 col2 \= A  
- La IP es `FE0A` → **2000:AABB:BBAA:FE0A::** ✓

**Máscara:** base: **/48** (los 3 primeros hextetos 16x3)

- Cada columna del 4º hexteto usada \= **\+4 bits**  
- Hemos usados 4:  48+4+4+4+4 \= /64  
- **Resultado: 2000:AABB:BBAA:FE0A::/64** ✓

---

# ENRUTAMIENTOS

## Enrutar por Estática

IPV4  
`ip route <red_destino> <máscara>  <IP_siguiente_salto)>`

- La red destino termina en **.0**  
- La IP del siguiente salto es la IP de la interfaz **más cercana del router vecino**

**Ejemplo:** 

`ip route 192.168.3.0 255.255.255.224 192.168.20.2`

IPV6  
`ipV6 route <red_destino>/<bits red>  <IP_siguiente_salto)>`

- La IP siguiente salto NO lleva prefijo /64 al final  
- La red destino tampoco lleva la IP del host (termina en ::)

**Ejemplo:**

`ipv6 route 2017:1234:5FFF:AAB0::/64 2026:A:A:A::1`   ← CORRECTO

`ipv6 route 2017:1234:5FFF:AAB0::/64 2026:A:A:A::1/64` ← INCORRECTO (no lleva máscara)

---

## Enrutar por RIP

`router rip`

`network <red_propia>`  ← Sin máscara

- `red_propia` son todas tus redes directamente conectadas (privadas y públicas)  
- Añades una línea por cada una

**Ejemplo:**

`router rip`

`network 192.168.50.0`

`network 20.0.0.0`

`network 30.0.0.0`

- Sin máscara (a diferencia de OSPF)

---

## Enruta por Alias (RIP) (solo IPv6)

## `int fa0/0`

  `ipv6 add 2001:A:A:A::1/64 Añadimos la ip a la interface`

  `ipv6 rip <nombre_alias> enable Activamos`

`int s0/0/0`

  `ipv6 add 2019:A:A:A::1/64`

  `ipv6 rip <nombre_alias> enable`

`ipv6 unicast-routing`

`ipv6 router rip <nombre_alias> Enrutamos`

- `nombre_alias` es el mismo tanto en interfaces como al enrutar.

---

## Enrutar por OSPF

### Máscaras en formato wildcard:

**Wildcard** \= inverso de la máscara: `255.255.255.255 - máscara`  
**Ejemplo:** `255.255.255.255 - 255.255.128.0 = 0.0.127.255`

### IPv4

`router ospf <1-65535>`  ← El número NO tiene que coincidir entre routers

`router-id X.X.X.X`  ← Obligatorio que sea ÚNICO por router (no es obligatorio escribirlo, el sistema asigna uno, pero es recomendable al no poder repetirse es mejor saber cual es)

`network <red> <wildcard> area <número>`

**Reglas clave:**

- El **área sí debe coincidir** entre routers para tener conectividad  
- El **número de `router ospf`** NO tiene que coincidir  
- El **router-id** debe ser **único** (distinto en cada router)  
- Si dos routers tienen el mismo router-id → **no hay conectividad**  
- El router-id **no es obligatorio** (el sistema lo asigna solo si no se escribe)

**Ejemplo OSPF IPv4:**

`router ospf 1`

`router-id 1.1.1.1`

`network 192.168.1.0 0.0.0.255 area 0`

`network 10.0.0.0 0.255.255.255 area 0`

### IPv6 OSPF

`ipv6 router ospf 1`

`router-id 1.1.1.1`

`exit`

`int fa0/0`

`ipv6 ospf 1 area 0`

`exit`

`int s0/0/0`

`ipv6 ospf 1 area 0`

- El número en `ipv6 ospf <n> area 0` en las interfaces debe coincidir con `ipv6 router ospf <n>`  
- El router-id debe ser único  
- En IPv6 entramos a las interfaces

---

## Enrutar por EIGRP

### IPv4

`router eigrp <número>` ← Mismo número en routers conectados

`network <red_propia>` ← Sin máscara, en clase A/B/C

- `red_propia` son todas tus redes directamente conectadas (privadas y públicas)  
- Añades una línea por cada una  
- En IPv6 entramos a las interfaces

**Ejemplo:**

`router eigrp 20`

`network 151.151.0.0`

`network 40.0.0.0`

`network 20.0.0.0`

### IPv6 EIGRP

`ipv6 router eigrp 2`

`eigrp router-id 2.0.0.0 ← Distinto en cada router`

`no shutdown`

`int fa0/0`

`ipv6 eigrp 2`

`int s0/3/0`

`ipv6 eigrp 2`

---

# SUMARIZAR

Sumarizar en IPv4

### Método

1. Convierte las IPs a binario.   
2. Busca los bits comunes desde la izquierda (en cuanto no coincida uno paras y pones X hasta el final)  
3. Máscara \= El número de bits comunes  
4. IP red sumarizada \= Base común .  parte común (en decimal) .rellenas a cero

### Ejemplos de examen

**Sumarizar: 195.168.10.0 y 195.168.20.0**

10.0 \= 00001010

20.0 \= 00010100

Base común: 195.168. → 16 bits (8x2)

Comunes encontrados 3er octeto: 000XXXXX → \+3 bits para la máscara

**Resultado: 195.168.0.0 /19 → máscara 255.255.224.0  ✓**

NOTA:  000XXXXX Como lo común son  0s no hace falta pasar a decimal, pero si fuera 100XXXXX sería 195.168.128.0

**Sumarizar: 192.168.91.0, 192.168.59.0, 192.168.39.0** 

91 \= 01011011

59 \= 00111011

39 \= 00100111

Común: 0XXXXXXXX  
Máscara :  16 \+1 bit común  
**Resultado: 192.168.0.0 /17 (255.255.128.0) ✓**  
---

## Sumarizar en IPv6

- Igual, pero primero traducimos a decimal y luego binario C \=\> 12 \=\> 1100 (y vuelta)  
- Para sumar 15 sólo necesitamos los últimos 4 números de la tabla binaria (8 4 2 1\)  
- Para encontrar iguales el número se formatea 1100, no 00001100 o la máscara no te saldrá.

### Ejemplos de examen

**Sumarizar: 2025:CAFE:AAAA:ABCD y 2025:CAFE:AAAA:AEFF**

ABCD \= 1010 1011 1100 1101

AEFF \= 1010 1110 1111 1111

Base común: 2025:CAFE:AAAA: →48 bits (16x3)

Comunes encontrados: 1010 1XXX XXXX XXXX → \+5 bits para la máscara \= /53

Traducimos de vuelta a hex: 

- 1010 \= 8 on, 4 off, 2 on 1 off \= 10 dec. \= A hex.

- 1000 \= 8 on, 4 off, 2 off 1 off \= 8 dec. \= 8 hex.

**Resultado: 2025:CAFE:AAAA:A800::/53 ✓**

**Sumarizar: 2000:A:A:F::/64, 2000:A:A:C::/64, 2000:A:A:E::/64**  
Importante no olvides que aunque abreviada los 0 cuentan para la máscara

000F \= 0000 0000 0000 1111  
000C \= 0000 0000 0000 1100

000E \= 0000 0000 0000 1110

Común: 0000 0000 0000 11XX → \+14 bits para la máscara \= /62

Traducimos de vuelta a hex: 

- 0000 \= 8 off, 4 off, 2 on 1 off \= 0 dec. \= 0 hex.

- 1100 \= 8 on, 4 on, 2 off 1 off \= 12 dec. \= C hex.

**Resultado: 2000:A:A:C::/62 ✓**

---

# VLAN

##### Comandos router VLAN IPv4

`ip dhcp pool azul`  
`network 192.168.4.0 255.255.255.0`  
`default-router 192.168.4.1`

`#encapsulación`  
`int fa0/1.40 #Subinterfaz para VLAN 40`  
`encapsulation dot1q 40 #Etiqueta VLAN 40. debe ser igual a la anterior`  
`ip add 192.168.4.1 255.255.255.0 #IP para VLAN 40 (gateway)`

**Errores frecuentes de examen:**

- ❌ `int fa0/0.1` \+ `encapsulation dot1q 1` → **NO SE PUEDE encapsular VLAN 1** (está reservada)  
- ❌ `int fa0/0.10` \+ `encapsulation dot1q 11` → **No coinciden** (error)  
- ❌ `int fa0/0.1` directamente → **No se puede usar la VLAN 1** en router 2811  
- ❌ `default-router 192.168.1.1 255.255.255.0` → **default-router NO lleva máscara**  
- ✓ `int fa0/1.10` \+ `encapsulation dot1Q 10` \+ `IP address X.X.X.X máscara` → correcto

**VLAN reservada:** En el router 2811, la **VLAN 1 no puede ser utilizada** por el usuario.

##### Comandos switch VLAN IPv4

`#le informamos de que hemos cocinado en el router`  
`vlan 40`  
`name azul`

`#configuramos rango interfaz`  
`int range fa0/1-15 #copia todo al rango indicado 1-15`  
`switchport access vlan 40`  
`switchport mode acc #access es datos, le decimos que estamos en modo datos`

`#SI VOZ: configuramos rango voz que abarca todas`  
`int range fa0/1-23`  
`switchport voice vlan 30`

`int fa0/24 #sin el rango, solo la int del switch a la que llega el cable con las 3 redes desde el router`  
`switchport mode trunk`

**Errores frecuentes:**

- ❌ `switchport access vlan 1` → No se puede usar VLAN 1  
- ✓ La interfaz trunk es la que conecta al router

##### Comandos router VLAN IPv6

`ipv6 unicast-routing`  
`ipv6 dhcp pool rojo`  
`prefix-delegation pool rojo`  
`exit`  
`ipv6 general-prefix rojo 2025:a:a:a::/64 #Sin 1 final, es una network`  
`ipv6 local pool rojo 2025:a:a:a::/40 64`

`#encapsulación`  
`int fa0/0.10`  
`ipv6 dhcp server rojo`  
`ipv6 add 2025:a:a:a::1/64 #En la encapsulación sí va el 1`

## Telefonía IP (VoIP)

### Configuración dial-peer

`dial-peer voice 20 voip #Llamada a la VLAN de telefonía del destino`

`destination-pattern 3.. #Extensiones de los teléfonos destino (ej. 3XX)`

`session target ipv4:192.168.2.1 #IP de la voz del destino (NO port 1000)`

**Errores frecuentes:**

- ❌ `session target ipv4:X port 1000` → Puerto 1000 es **incorrecto**  
- ✓ Puerto telefonía: 2000 (no 5060, no 1000\)

### Configuración completa VLAN con telefonía

`#dhcp voz`  
`ip dhcp pool voz`  
`network 192.168.3.0 255.255.255.0`  
`option 150 ip 192.168.3.1  #Especial red de voz`  
`default-router 192.168.3.1`  
`#llamamos fuera`  
`dial-peer voice 40 voip #es una llamada al destino`  
`destination-pattern 2... #patrón de los teléfonos, empiezan por 2, los puntos es cuantos 0 tiene detrás`  
`session target ipv4:192.168.40.1 #ip del destino`

`#configuramos teléfonos`  
`telephony-service`   
`max-dn 2 #num. teléfonos que tengo`  
`max-ephone 2 #num. teléfonos que tengo`  
`ip source-add 192.168.3.1 port 2000 #le decimos donde está la voz(siempre puerto 2000)`  
`auto assign 1 to 2 #se auto configuran los dos teléfonos solos`  
`#ahora le ponemos los números a los dos teléfonos`  
`ephone-dn 1 #telefono num. 1`  
`number 1000 #número teléfono num. 1`  
`ephone-dn 2`  
`number 1001`  
`#Ahora que no se pierda, lo grabamos`  
`do w #do write es la versión extendida`

---

# SWITCH 3 CAPAS

Para asignar IP a una salida física de un switch de 3 capas:

`int fa0/15`  
  `no switchport ← OBLIGATORIO: convierte el puerto en capa 3`  
  `ip add 192.168.1.1 255.255.255.0`  
  `no shut`

**Configuración completa con DHCP:**

`ip routing ← Activa el enrutamiento en el switch`

`int vlan 10`  
  `ip add 192.168.10.1 255.255.255.0`  
  `no shut`

`ip dhcp pool vlan10`  
  `network 192.168.10.0 255.255.255.0`  
  `default-router 192.168.10.1`  
  `dns-server 8.8.8.8`

`ip dhcp excluded-address 192.168.10.1 192.168.10.31`

# ---

Tunnel IPv6 sobre IPv4

El túnel permite pasar tráfico IPv6 a través de una red IPv4. La IPv6 **solo existe en el túnel**, no en el serial porque no dejaría hacer el tunel.

`int tunnel 0                    ← Crea el túnel con nombre 0`  
`ipv6 add 500:3421::1/64       ← IP IPv6 del router origen (solo en el túnel)ipv6 enable                   ← Activa el servicio IPv6 en el túnel`  
`tunnel source s0/3/0          ← El serial por donde circula IPv4 e IPv6`  
`tunnel destination 18.0.0.2   ← IP IPv4 del otro extremo (debe existir en el serial)`  
`tunnel mode ipv6ip            ← Modo: IPv6 sobre IPv4`

**Enrutamiento sobre el túnel:**

`ipv6 route 2001:A:B:B::/64 5000:3421::2   ← IP del túnel del router destino`

# ---

REDES INALÁMBRICAS

- **AAA (triple A)** Authorization Authentication Accounting  
- AAA Server type (protocolos de seguridad) :   
  - Radius (Remote Authentication Dial-In User Service) \=\> Autenticación de usuarios finales para acceder a la red (Wi-Fi, VPN)  
  - Tacacs+ (Terminal Access Controller Access Control System Plus) \=\> Autenticación de administradores para gestionar dispositivos de red (routers, switches)  
- Autenticación de **máxima seguridad** en router inalámbrico: **WPA2**  
- WEP \< WPA \< **WPA2**


# ---

CISCO CLOUD

- En la **Cloud-PT**, el valor máximo del **DLCI** en los seriales es **1007**  
- DLCI 1008 o superior → no permitido

# ---

SERVIDOR FTP

- `put recetas.txt #put subir, get bajar`

---

DHCP

- En los routers que no estén en la red del DCHP Relay añade `ip helper-add xxx.xxx.xxx.xxx` primero la ip del DCHP relay y luego la del router donde está.  
  `int fa0/0`  
  `ip helper-add 192.168.1.2`  
  `ip helper-add 192.168.1.1`

# ---

ASA 5505 (CORTAFUEGOS CISCO) (Ciberseguridad)

### Estructura de zonas de seguridad

- **Seguridad 0** → Inside (zona exterior/baja seguridad)  
- **Seguridad 50** → DMZ (zona desmilitarizada, por defecto)  
- **Seguridad 100** → Outside (zona interna/alta seguridad)

### Ley de seguridad del cortafuegos

**Seguridad superior puede comunicarse con seguridad inferior por defecto (outbound).** **Seguridad inferior NO puede comunicarse con seguridad superior por defecto (inbound); debe permitirse explícitamente.**

Para que outside (seg. 0\) acceda a inside (seg. 100\) hace falta configuración explícita (NAT/VPN/webvpn).

## VLANs del ASA 5505

Solo admite **3 VLANs** y tiene **8 salidas**: 

- VLAN 1 (INSIDE) →Nivel 100\. Salidas del 1 al 7 (Red por defecto: `192.168.1.0/24`. IP del ASA 192.168.1.1. Primera IP DHCP 192.168.1.5)  
- VLAN 2 (OUTSIDE) → Nivel 0\. Salida 0  
- VLAN 3 (DMZ) → Nivel 50\. Sin salida preasignada.

### VPN: en ASA 5505

Acceso de los ordenadores en outside(0) a servidores en inside (100)

`webvpn`

`enable outside`

Ambas son **obligatorias** para usuarios **VPN**.

### Conectividad entre seg. 0 y seg. 100: acceso a servidores web

- Se usa la **IP del Default-Gateway de la zona 0 (en clase terminado en 1**) (el propio cortafuegos hace el resto) ✓  
- NO: la IP de los servidores, NO: la IP del DG de la zona 100

### Dar de alta usuarios (webvpn)

- **No se pueden dar de alta usuarios desde la interfaz gráfica** — sólo por CLI  
- **Sí se pueden dar de alta enlaces de páginas web (bookmarks) desde la interfaz gráfica**

### Conectividad entre seg. 100 y seg. 0 

### `Class-map NOMBRE1`

  `Match default-inspection-traffic   ← OBLIGATORIO`

`Exit`

`Policy-map NOMBRE2`

  `Class NOMBRE1`

    `Inspect icmp                     ← OBLIGATORIO`

`Exit`

`Service-policy`

---

# MODELO OSI — 7 CAPAS

| Capa | Nombre | Dispositivos / Protocolos |
| :---- | :---- | :---- |
| 1 | **Física** | Cables, RJ45, conectores físicos |
| 2 | **Enlace de datos** | Switch, puentes, MAC, 802.3 (Ethernet), 802.11 (WiFi), Seguridad MAC |
| 3 | **Red** | Router, Switch 3 capas, IP, IPSec (VPN), NAT, ICMP, enrutamiento |
| 4 | **Transporte** | TCP, UDP, puertos, tiempos de paquetes, monitorización (Wireshark, Netflow) |
| 5 | **Sesión** | NetBIOS (puertos 137/138/139), RPC, PAP, PPTP |
| 6 | **Presentación** | Encriptado, formato de datos para la capa 7 |
| 7 | **Aplicación** | Navegadores web, DNS, HTTP/S, SMTP, FTP, Teams |

**Truco para recordarlas:**  *No he encontrado ninguno que sea capaz de recordar*

### Incidencias por capa

| Problema | Capa |
| :---- | :---- |
| Cable doblado / RJ45 roto | **1 — Física** |
| Switch seguridad MAC mal config / luz apagada | **2 — Enlace de datos** |
| IP mal configurada / VPN / NAT / enrutamiento / DHCP pool mal | **3 — Red** |
| Paquetes lentos / monitorización | **4 — Transporte** |
| NetBIOS | **5 — Sesión** |
| DNS mal configurado / FTP-TFTP / usuario y contraseña / correo / HTTP | **7 — Aplicación** |
| ASA mal config \+ IP mal → **Capa 3 y Capa 7** (ambas) | **3 \+ 7** |

---

## PUERTOS DE SERVICIOS

| Servicio | Puerto |
| :---- | :---- |
| FTP | **21** |
| SSH | **22** |
| Telnet | **23** |
| SMTP | **25** |
| DNS | **53** |
| HTTP | **80** |
| HTTPS | **443** |
| Netflow | **9996** |
| Telefonía IP (Cisco) | **2000** |
| Syslog | **514** |
| SNMP | **161** |

---

# DNS

- **DNS directo:** Nombre de dominio → IP (para navegar)  
- **DNS inverso:** IP → Nombre de dominio (para verificar legitimidad)  
- Para configurar servidores de correo **es obligatorio** configurar DNS  
- Puerto: **53**  
- **Nota DNS:** Si usas IP estática sin configurar DNS, pierdes acceso a internet → solución: escribir DNS preferido y alternativo.


---

# VARIOS

- **IP 0.0.0.0** NO es segura: `ip route 0.0.0.0 0.0.0.0 s0/0/0`    ← Inseguro, no recomendado  
- La red **127.0.0.0/8** completa es loopback (comunicación consigo mismo). No se puede usar para trabajos cotidianos de red  
- **Estándares IEEE**:  
  - 802.1 → Todas las redes cableadas e inalámbricas  
  - 802.3 → Redes cableadas (Ethernet)  
  - 802.11 → Wi-Fi

---

# Errores de código típicos en examen

| Código con error | Error |
| :---- | :---- |
| `int fa0/0.1` \+ `encapsulation dot1q 1` | No se puede encapsular VLAN 1 |
| `int fa0/0.10` \+ `encapsulation dot1q 11` | Subinterfaz y dot1q no coinciden |
| `int fa0/0.1` (sin encapsulación) | Falta `encapsulation dot1q` |
| `ip add 192.168.1.0 255.255.255.248` | IP termina en .0 → incorrecta |
| `default-router 192.168.1.1 255.255.255.0` | Default-router no lleva máscara |
| `switchport access vlan 1` | No se puede usar VLAN 1 |
| `session target ipv4:X port 1000` | Puerto VoIP incorrecto (debe ser 2000\) |
| `Router-id 1.1.1.1` igual en dos routers OSPF | Router-id debe ser único |
| `ip route 0.0.0.0 0.0.0.0 s0/0/0` | Inseguro (enrutamiento por defecto sin restricciones) |

---

---

## Resumen Ultra-Rápido para el Examen

DLCI máximo: 1007

VLAN 1: NO se puede usar en router 2811

OSI: Física(1) Enlace(2) Red(3) Transporte(4) Sesión(5) Presentación(6) Aplicación(7)

IPv6: 4º hexteto \= subneteo | 5-6 \= conectividad host

Bits IPv6: hextetos\_encendidos × 16

OSPF: área coincide | router-id único | número ospf no importa | wildcard \= inverso máscara

Tunnel: IPv6 solo en túnel, no en serial | destination \= IP IPv4 del serial destino

ASA: DMZ=50 | 3 VLANs | acceso seg0→100 \= DG zona 0 | VPN: webvpn \+ enable outside

Telefonía puerto: 2000

DNS: directo=navegar | inverso=verificar legitimidad | puerto 53

# 

