# Resumen de Estudio — SRI (Servicios de Red e Internet)
> Basado en los tests de examen. Lo que aparece aquí **va a caer**.

---

## 1. Modelo OSI — 7 Capas

| Capa | Nombre | Dispositivos / Protocolos |
|------|--------|--------------------------|
| 1 | **Física** | Cables, RJ45, conectores físicos |
| 2 | **Enlace de datos** | Switch, puentes, MAC, 802.3 (Ethernet), 802.11 (WiFi), Seguridad MAC |
| 3 | **Red** | Router, Switch 3 capas, IP, IPSec (VPN), NAT, ICMP, enrutamiento |
| 4 | **Transporte** | TCP, UDP, puertos, tiempos de paquetes, monitorización (Wireshark, Netflow) |
| 5 | **Sesión** | NetBIOS (puertos 137/138/139), RPC, PAP, PPTP |
| 6 | **Presentación** | Encriptado, formato de datos para la capa 7 |
| 7 | **Aplicación** | Navegadores web, DNS, HTTP/S, SMTP, FTP, Teams |

**Truco para recordarlas:** *"For Ejemplo Redes Tremendas Son Perfectamente Aplicables"* (Física, Enlace, Red, Transporte, Sesión, Presentación, Aplicación)

### Incidencias por capa (tipo test frecuente)

| Problema | Capa |
|----------|------|
| Cable doblado / RJ45 roto | **1 — Física** |
| Switch seguridad MAC mal config / luz apagada | **2 — Enlace de datos** |
| IP mal configurada / VPN / NAT / enrutamiento / DHCP pool mal | **3 — Red** |
| Paquetes lentos / monitorización | **4 — Transporte** |
| NetBIOS | **5 — Sesión** |
| DNS mal configurado / FTP-TFTP / usuario y contraseña / correo / HTTP | **7 — Aplicación** |
| ASA mal config + IP mal → **Capa 3 y Capa 7** (ambas) | **3 + 7** |

**Estándares IEEE:**
- **802.1** → Todas las redes cableadas e inalámbricas
- **802.3** → Redes cableadas (Ethernet)
- **802.11** → Wi-Fi

---

## 2. Puertos de Servicios (caen mucho)

| Servicio | Puerto |
|----------|--------|
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

> **Nota DNS:** Si usas IP estática sin configurar DNS, pierdes acceso a internet → solución: escribir DNS preferido y alternativo.

---

## 3. DNS

- **DNS directo:** Nombre de dominio → IP (para navegar)
- **DNS inverso:** IP → Nombre de dominio (para verificar legitimidad)
- Para configurar servidores de correo **es obligatorio** configurar DNS
- Puerto: **53**

---

## 4. Subnetting IPv4

### Fórmula básica
```
2^(bits_disponibles) >= subredes_pedidas
bits_nuevos = log2(subredes) → redondear arriba
nueva_máscara = máscara_original + bits_nuevos
```

### Tabla de máscaras rápida

| CIDR | Máscara | Salto | Hosts totales | Hosts disponibles |
|------|---------|-------|---------------|-------------------|
| /24 | 255.255.255.0 | 256 | 256 | 254 |
| /25 | 255.255.255.128 | 128 | 128 | 126 |
| /26 | 255.255.255.192 | 64 | 64 | 62 |
| /27 | 255.255.255.224 | 32 | 32 | 30 |
| /28 | 255.255.255.240 | 16 | 16 | 14 |
| /29 | 255.255.255.248 | 8 | 8 | 6 |
| /30 | 255.255.255.252 | 4 | 4 | 2 |
| /18 | 255.255.192.0 | — | 16384 | 16382 |
| /19 | 255.255.224.0 | — | 8192 | 8190 |
| /20 | 255.255.240.0 | — | 4096 | 4094 |

### Ejemplos de examen

**Dividir 192.168.1.0/24 en 50 subredes:**
- 2^6 = 64 ≥ 50 → 6 bits nuevos
- Nueva máscara: 24 + 6 = **/30** → 255.255.255.252 ✓

**Dividir 192.168.1.0/24 en 4 subredes:**
- 2^2 = 4 → 2 bits nuevos
- Nueva máscara: **/26** ✓

**Dividir 170.0.0.0/16 en 4 subredes:**
- 2^2 = 4 → 2 bits nuevos → **/18** ✓

**Dividir 172.168.1.0/16 en 6 subredes:**
- 2^3 = 8 ≥ 6 → 3 bits nuevos → **/19** ✓

### Rango de IPs disponibles
- Primera IP = Network + 1
- Última IP = Broadcast - 1
- Broadcast = Network + Salto - 1

**Ejemplos:**
- `192.168.1.0/28` → Salto 16 → Broadcast .15 → Rango: **.1 → .14** ✓
- `192.168.1.0/27` → Salto 32 → Broadcast .31 → Rango: **.1 → .30** ✓
- `192.168.1.0/29` → Salto 8 → Broadcast .7 → Rango: **.1 → .6** ✓
- `192.168.1.0/25` → Salto 128 → Broadcast .127 → Rango: **.1 → .126** ✓
- `192.168.1.0/26` → Salto 64 → Broadcast .63 → Rango: **.1 → .62** ✓
- `172.172.0.0/19` → Salto 32 en 3er octeto → Rango: **172.172.0.1 → 172.172.31.254** ✓

**Hosts totales:** `2^(32 - CIDR)`
- `/20` → 2^12 = 4096 totales, **4094** disponibles
- `/18` → 2^14 = 16384 totales, **16382** disponibles

### Dirección loopback
- La red `127.0.0.0/8` completa es loopback (comunicación consigo mismo)
- **No se puede usar** para trabajos cotidianos de red

---

## 5. Sumarización IPv4

### Método
1. Convierte las IPs a binario
2. Busca los bits comunes desde la izquierda
3. El número de bits comunes = nueva máscara
4. La red sumaria = la parte común + ceros

### Ejemplos de examen

**Sumarizar: 195.168.10.0 y 195.168.20.0**
```
10.0 = 00001010
20.0 = 00010100
Bits comunes: 195.168. → 3er octeto: 000XXXXX → 3 bits de 195.168
→ 195.168.0.0 /19 → máscara 255.255.224.0  ✓
```

**Sumarizar: 192.168.10.0, 192.168.20.0, 192.168.30.0**
→ **192.168.0.0 /19** (255.255.224.0) ✓

**Sumarizar: 192.168.10.0, 192.168.30.0, 192.168.40.0**
→ **192.168.0.0 /19** (255.255.192.0) ✓

**Sumarizar: 192.168.91.0, 192.168.59.0, 192.168.39.0**
→ **192.168.0.0 /17** (255.255.128.0) ✓

---

## 6. IPv6 — Estructura

Una IPv6 tiene **8 hextetos** de 16 bits cada uno = **128 bits** totales.

```
XXXX : XXXX : XXXX : XXXX : XXXX : XXXX : XXXX : XXXX
 1      2      3      4      5      6      7      8
```

| Parte | Hextetos | Uso |
|-------|----------|-----|
| Red (prefijo) | 1-3 | Identifican la red |
| **Subneteo** | **4º** | Solo el 4º hexteto se usa para subnetear |
| Conectividad/host | 5º y 6º | Conectividad con host de destino |
| Host | 7-8 | Identificación del host |

### Contar bits en una IPv6
- Cada hexteto encendido (no abreviado con ::) = **16 bits**
- `1995:A:B:C:D:E:FFFF::` → 7 hextetos encendidos → **7 × 16 = 112 bits** ✓

### Subnetting IPv6
El 4º hexteto tiene **4 posiciones** (columnas) y **15 valores** (1-F, sin el 0):

| Dec | Hex |
|-----|-----|
| 1 | 1 |
| 2 | 2 |
| ... | ... |
| 9 | 9 |
| 10 | A |
| 11 | B |
| 12 | C |
| 13 | D |
| 14 | E |
| 15 | F |

- Base: **/48** (los 3 primeros hextetos)
- Cada columna del 4º hexteto usada = **+4 bits**
- Si necesitas más de 15 entradas → usas otra columna (siguiente posición del hexteto)

### Ejemplo examen — 3 niveles (ciudad/oficina/dpto)
**Red: 2000:AABB:BBAA::/48, 15 ciudades, 15 oficinas, 30 departamentos**

Distribuir en el 4º hexteto: `[Ciudad][Oficina][Dpto1][Dpto2]`

- Ciudad 11 = B, Oficina 13 = D → `BD00` → **2000:AABB:BBAA:BD00::/56** ✓
- Ciudad 15 = F, Oficina 14 = E, Dpto 25 → dpto en 2 columnas → `FE0A` → **2000:AABB:BBAA:FE0A::/64** ✓

La máscara depende de cuántas columnas se usan del 4º hexteto:
- 1 columna = /52, 2 columnas = /56, 3 columnas = /60, 4 columnas = /64

---

## 7. Sumarización IPv6

### Ejemplos de examen

**Sumarizar: 2025:CAFE:AAAA:ABCD y 2025:CAFE:AAAA:AEFF**
```
ABCD = 1010 1011 1100 1101
AEFF = 1010 1110 1111 1111
Bits comunes: 1010 1 → 5 bits → 48+5 = /53
Red: 2025:CAFE:AAAA:A800::/53 ✓
```

**Sumarizar: 2000:A:A:F::, 2000:A:A:C::, 2000:A:A:E::**
```
F = 1111, C = 1100, E = 1110
Común: 11 → 2 bits → 64-2 = /62
Red: 2000:A:A:C::/62 ✓
```

**Sumarizar: 2000:B:C:D:E:1234::, 2000:B:C:D:E:12A4::, 2000:B:C:D:E:12FF::**
```
1234 = 0001 0010 0011 0100
12A4 = 0001 0010 1010 0100
12FF = 0001 0010 1111 1111
Común: 0001 0010 0 → 9 bits del 6º hexteto → 80+9 = /89... 
→ Red: 2000:B:C:D:E:1200::/88 ✓
```

---

## 8. Enrutamiento Estático

### IPv4
```
ip route [red_destino] [máscara] [IP_siguiente_salto]
```
- La red destino termina en **.0**
- La IP del siguiente salto es la IP de la interfaz **más cercana del router vecino**

**Ejemplo:** Del router Málaga al de Cádiz (IP serial 192.168.20.2):
```
ip route 192.168.3.0 255.255.255.224 192.168.20.2
```

**Ruta por defecto (NO es segura):**
```
ip route 0.0.0.0 0.0.0.0 s0/0/0    ← Inseguro, no recomendado
```

### IPv6
```
ipv6 route [red_destino]/[bits] [IP_siguiente_salto]
```
- La IP siguiente salto NO lleva prefijo /64 al final
- La red destino tampoco lleva la IP del host (termina en ::)

**Ejemplo:**
```
ipv6 route 2017:1234:5FFF:AAB0::/64 2026:A:A:A::1   ← CORRECTO
ipv6 route 2017:1234:5FFF:AAB0::/64 2026:A:A:A::1/64 ← INCORRECTO (no lleva máscara)
```

---

## 9. OSPF

### IPv4
```
router ospf [1-65535]        ← El número NO tiene que coincidir entre routers
router-id X.X.X.X            ← Obligatorio que sea ÚNICO por router (no es obligatorio escribirlo, el sistema asigna uno)
network [red] [wildcard] area [número]
```

**Reglas clave:**
- El **área sí debe coincidir** entre routers para tener conectividad
- El **número de `router ospf`** NO tiene que coincidir
- El **router-id** debe ser **único** (distinto en cada router)
- Si dos routers tienen el mismo router-id → **no hay conectividad**
- El router-id **no es obligatorio** (el sistema lo asigna solo si no se escribe)

**Wildcard OSPF** = inverso de la máscara: `255.255.255.255 - máscara`

| Máscara | Wildcard OSPF |
|---------|---------------|
| 255.255.255.0 | 0.0.0.255 |
| 255.255.255.224 | 0.0.0.31 |
| 255.255.255.192 | 0.0.0.63 |
| 255.255.255.128 | 0.0.0.127 |
| 255.255.255.248 | 0.0.0.7 |
| 255.255.255.252 | 0.0.0.3 |
| 255.255.128.0 | 0.0.127.255 |
| 255.255.192.0 | 0.0.63.255 |
| 255.255.224.0 | 0.0.31.255 |
| 255.255.64.0 | 0.0.191.255 |
| 255.224.0.0 | 0.31.255.255 |
| 10.0.0.0/11 | 0.31.255.255 |

**Ejemplo OSPF IPv4 correcto:**
```
router ospf 1
router-id 1.1.1.1
network 192.168.1.0 0.0.0.255 area 0
network 10.0.0.0 0.255.255.255 area 0
```

### IPv6 OSPF
```
ipv6 router ospf 1
router-id 1.1.1.1
exit
int fa0/0
ipv6 ospf 1 area 0
exit
int s0/0/0
ipv6 ospf 1 area 0
```
- El número en `ipv6 ospf [n] area 0` en las interfaces debe coincidir con `ipv6 router ospf [n]`
- El router-id debe ser único

---

## 10. EIGRP

### IPv4
```
router eigrp [número]       ← Mismo número en routers conectados
network [red_en_clase]      ← Sin máscara, en clase A/B/C
```

**Ejemplo:**
```
router eigrp 20
network 151.151.0.0
network 40.0.0.0
network 20.0.0.0
```

### IPv6 EIGRP
```
ipv6 router eigrp 2
eigrp router-id 2.0.0.0   ← Distinto en cada router
no shutdown
int fa0/0
ipv6 eigrp 2
int s0/3/0
ipv6 eigrp 2
```

---

## 11. RIP

```
router rip
network [red]       ← Sin máscara, en clase
```

**Ejemplo:**
```
router rip
network 192.168.50.0
network 20.0.0.0
network 30.0.0.0
```
- Sin wildcard (a diferencia de OSPF)
- La red va en su clase natural

---

## 12. VLAN — Configuración

### Router (inter-VLAN routing IPv4)

```
ip dhcp pool [nombre]
  network [red] [máscara]
  default-router [gateway]

int fa0/0.10                  ← Subinterfaz para VLAN 10
  encapsulation dot1q 10      ← El número DEBE coincidir con la subinterfaz (.10 → dot1q 10)
  ip add 192.168.1.1 255.255.255.0
```

**Errores frecuentes de examen:**
- ❌ `int fa0/0.1` + `encapsulation dot1q 1` → **NO SE PUEDE encapsular VLAN 1** (está reservada)
- ❌ `int fa0/0.10` + `encapsulation dot1q 11` → **No coinciden** (error)
- ❌ `int fa0/0.1` directamente → **No se puede usar la VLAN 1** en router 2811
- ❌ `default-router 192.168.1.1 255.255.255.0` → **default-router NO lleva máscara**
- ✓ `int fa0/1.10` + `encapsulation dot1Q 10` + `IP address X.X.X.X máscara` → correcto

**VLAN reservada:** En el router 2811, la **VLAN 1 no puede ser utilizada** por el usuario.

### Router (inter-VLAN routing IPv6)

```
ipv6 unicast-routing
ipv6 dhcp pool NOMBRE
  prefix-delegation pool NOMBRE
exit
ipv6 general-prefix NOMBRE 2001:A:A::/64
ipv6 local pool NOMBRE 2001:A:A::/40 64
int fa0/0.10
  encapsulation dot1q 10
  ipv6 add 2001:A:A:A::1/64   ← Aquí SÍ va el ::1
  ipv6 dhcp server NOMBRE
```

### Switch

```
vlan 10
  name datos
int range fa0/1-5
  switchport mode access
  switchport access vlan 10    ← NO se puede usar vlan 1
int fa0/24
  switchport mode trunk
```

**Errores frecuentes:**
- ❌ `switchport access vlan 1` → No se puede usar VLAN 1
- ✓ La interfaz trunk es la que conecta al router

---

## 13. Switch 3 Capas (Layer 3 Switch)

Para asignar IP a una salida física de un switch de 3 capas:

```
int fa0/15
  no switchport              ← OBLIGATORIO: convierte el puerto en capa 3
  ip add 192.168.1.1 255.255.255.0
  no shut
```

**Configuración completa con DHCP:**
```
ip routing                   ← Activa el enrutamiento en el switch

int vlan 10
  ip add 192.168.10.1 255.255.255.0
  no shut

ip dhcp pool vlan10
  network 192.168.10.0 255.255.255.0
  default-router 192.168.10.1
  dns-server 8.8.8.8

ip dhcp excluded-address 192.168.10.1 192.168.10.31
```

---

## 14. Tunnel IPv6 sobre IPv4

El túnel permite pasar tráfico IPv6 a través de una red IPv4. La IPv6 **solo existe en el túnel**, no en el serial.

```
int tunnel 0                          ← 0 es el nombre del túnel
  ipv6 add 500:3421::1/64            ← IP del túnel (NO está en el serial)
  ipv6 enable                        ← Activa el servicio
  tunnel source s0/3/0               ← El serial por donde circula IPv4 e IPv6
  tunnel destination 18.0.0.2        ← IP IPv4 del serial del router destino (SÍ EXISTE en el serial)
  tunnel mode ipv6ip                 ← Modo: IPv6 sobre IPv4
```

**Cada línea del túnel:**
| Comando | Función |
|---------|---------|
| `int tunnel 1` | Crea el túnel con nombre 1 |
| `ipv6 add X::1/64` | IP IPv6 del router origen (solo en el túnel) |
| `ipv6 enable` | Activa el servicio IPv6 en el túnel |
| `tunnel source s0/3/0` | Serial por donde circula el tráfico |
| `tunnel destination X.X.X.X` | IP IPv4 del otro extremo (debe existir en el serial) |
| `tunnel mode ipv6ip` | Modo IPv6 sobre IPv4 |

**Enrutamiento sobre el túnel:**
```
ipv6 route 2001:A:B:B::/64 5000:3421::2   ← IP del túnel del router destino
```

---

## 15. Enrutamiento IPv6 por Alias (RIP)

```
ipv6 unicast-routing
ipv6 router rip [nombre_alias]
int fa0/0
  ipv6 add 2001:A:A:A::1/64
  ipv6 rip [nombre_alias] enable
int s0/0/0
  ipv6 add 2019:A:A:A::1/64
  ipv6 rip [nombre_alias] enable
```

---

## 16. ASA 5505 (Cortafuegos Cisco)

### Estructura de zonas de seguridad
- **Seguridad 0** → Inside (zona exterior/baja seguridad)
- **Seguridad 50** → DMZ (zona desmilitarizada, por defecto)
- **Seguridad 100** → Outside (zona interna/alta seguridad)

### VLANs del ASA 5505
- Solo admite **3 VLANs**: VLAN 1 (inside), VLAN 2 (outside), VLAN 3 (dmz)
- Red por defecto VLAN 1: `192.168.1.0` (IP por defecto empieza en .5)

### Acceso a servidores web sin DNS (desde seg. 0 hacia seg. 100)
- Se usa la **IP del Default-Gateway de la zona 0** (el propio cortafuegos hace el resto) ✓
- NO: la IP de los servidores, NO: la IP del DG de la zona 100

### Usuarios
- **No se puede crear usuarios con interfaz gráfica** (no con ASDM) → Solo por consola

### VPN en ASA 5505 — órdenes obligatorias
```
webvpn
  enable outside
```
Ambas son **obligatorias** para usuarios VPN.

### Inspección de tráfico — Código obligatorio (class-map)
```
Class-map NOMBRE1
  Match default-inspection-traffic   ← OBLIGATORIO
Exit
Policy-map NOMBRE2
  Class NOMBRE1
    Inspect icmp                     ← OBLIGATORIO
Exit
Service-policy
```

---

## 17. Cloud PT (Cisco Packet Tracer)

- En la **Cloud-PT**, el valor máximo del **DLCI** en los seriales es **1007**
- DLCI 1008 o superior → no permitido

---

## 18. Telefonía IP (VoIP)

### Configuración dial-peer
```
dial-peer voice 20 voip         ← Llamada a la VLAN de telefonía del destino
  destination-pattern 3..       ← Extensiones de los teléfonos destino (ej. 3XX)
  session target ipv4:192.168.2.1  ← IP de la voz del destino (NO port 1000)
```

| Línea | Función |
|-------|---------|
| `dial-peer voice 29 voip` | Llamada a la VLAN de telefonía destino |
| `destination-pattern 2...` | Extensión de los teléfonos destino |
| `session target ipv4:X.X.X.X` | IP de la voz del destino |

**Puerto telefonía:** **2000** (no 5060, no 1720)
**Error frecuente:** `session target ipv4:X port 1000` → Puerto 1000 es **incorrecto**

### Configuración completa VLAN con telefonía
```
ip dhcp pool datos
  network 192.168.1.0 255.255.255.0
  default-router 192.168.1.1
  option 150 ip 192.168.1.1     ← Para teléfonos IP

telephony-service
  max-dn 2
  max-ephones 2
  ip source-address 192.168.2.1 port 2000
  auto assign 1 to 2

ephone-dn 1
  number 100
```

---

## 19. Errores de código típicos en examen

| Código con error | Error |
|-----------------|-------|
| `int fa0/0.1` + `encapsulation dot1q 1` | No se puede encapsular VLAN 1 |
| `int fa0/0.10` + `encapsulation dot1q 11` | Subinterfaz y dot1q no coinciden |
| `int fa0/0.1` (sin encapsulación) | Falta `encapsulation dot1q` |
| `ip add 192.168.1.0 255.255.255.248` | IP termina en .0 → incorrecta |
| `default-router 192.168.1.1 255.255.255.0` | Default-router no lleva máscara |
| `switchport access vlan 1` | No se puede usar VLAN 1 |
| `session target ipv4:X port 1000` | Puerto VoIP incorrecto (debe ser 2000) |
| `Router-id 1.1.1.1` igual en dos routers OSPF | Router-id debe ser único |
| `ip route 0.0.0.0 0.0.0.0 s0/0/0` | Inseguro (enrutamiento por defecto sin restricciones) |

---

## 20. DHCP — Resumen rápido

```
ip dhcp pool NOMBRE
  network [red] [máscara]
  default-router [IP_gateway]        ← Sin máscara
  dns-server [IP_DNS]

ip dhcp excluded-address [primera] [última]
```

- `network` → lleva máscara
- `default-router` → **NO** lleva máscara
- Red con `/29` → Hosts .1 a .6 (8 IPs, -2 por N&B = 6)

---

## Resumen Ultra-Rápido para el Examen

```
DLCI máximo: 1007
VLAN 1: NO se puede usar en router 2811
OSI: Física(1) Enlace(2) Red(3) Transporte(4) Sesión(5) Presentación(6) Aplicación(7)
IPv6: 4º hexteto = subneteo | 5-6 = conectividad host
Bits IPv6: hextetos_encendidos × 16
OSPF: área coincide | router-id único | número ospf no importa | wildcard = inverso máscara
Tunnel: IPv6 solo en túnel, no en serial | destination = IP IPv4 del serial destino
ASA: DMZ=50 | 3 VLANs | acceso seg0→100 = DG zona 0 | VPN: webvpn + enable outside
Telefonía puerto: 2000
DNS: directo=navegar | inverso=verificar legitimidad | puerto 53
```
