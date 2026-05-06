# Resumen — CB 

## Ciberseguridad

---

# CONCEPTOS GENERALES DE SEGURIDAD

| Concepto | Definición |
| :---- | :---- |
| **Seguridad pasiva** | Medidas que no actúan en tiempo real (copias de seguridad, SAI…) |
| **Seguridad activa** | Medidas que actúan en tiempo real (antivirus, firewall…) |
| **SAI** | Sistema de Alimentación **In**interrumpida (no "interrumpida") |
| **AAA** | **Autenticación, Autorización y Contabilización** |
| **Objetivo primordial de la seguridad** | Proteger la **información** |
| **Clave criptográfica** | Conjunto de códigos para transmitir un mensaje cuyo contenido se quiere ocultar |

---

# TIPOS DE ATAQUES

| Ataque | Descripción |
| :---- | :---- |
| **Phishing** | El atacante se hace pasar por persona/empresa de confianza para robar info (contraseñas, datos bancarios). |
| **Spoofing** | Técnica de **suplantación de identidad.** Falsificación de direcciones IP, DNS, etc. |
| **Sniffing** | Análisis del tráfico de red, interceptación de paquetes en la red habitualmente para recabar contraseñas. |
| **Ingeniería social** | Manipulación psicológica para obtener información. |
| **Ataques de fuerza bruta** | Intentos masivos de adivinar contraseñas. |
| **Denegación de servicios (DoS)** | Saturación de servicios que causa la caída del servicio → usuarios legítimos no pueden usarlo. |

---

# 

# ASA 5505 (CORTAFUEGOS CISCO)

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

  `  Inspect icmp                     ← OBLIGATORIO`

`Exit`

`Service-policy`

---

VPN (Router Cisco 2811\)

### Conceptos clave

| Protocolo | Qué hace |
| :---- | :---- |
| **ISAKMP** | Encripta los datos **en el propio router** (administra las claves) |
| **IPSEC** | Encripta los datos que **viajan por el cable** (de extremo a extremo) |

- **`show crypto ipsec sa`** → comando para verificar que los datos llegan encriptados y para hacer ping entre los routers conectados por vpn

### Variables en una VPN — se declaran **3**

| Variable | Dónde se usa |
| :---- | :---- |
| **VAR1** | `crypto isakmp key <VAR1> address <IP_destino>` |
| **VAR2** | `crypto ipsec transform-set <VAR2>` |
| **VAR3** | `crypto map <VAR2>` |

### Configuración completa (igual en ambos routers \- peers)

**Parte 1 — ISAKMP (Fase 1 — negocia el túnel de gestión)**

`crypto isakmp policy 10 #de 1 a 10.000; 1 la prioridad más alta`  
  `authentication pre-share #método de autentificación: pre-share / pre-share key`  
  `hash sha #algoritmo de hash a utilizar. Mejor sha256`  
  `encryption aes 256 #AES: 128 / 192 / 256`  
  `group 5 #grupos: 1, 2 o 5 (más seguro: 5, estándar: 2). Grupo 2 está obsoleto`  
  `lifetime 86400 #Tiempo que tarda en cambiar algoritmo — igual en ambos routers (peer)`  
`exit`

`crypto isakmp key <VAR1> address <IP_pública_destino> # La clave secreta <VAR1> y la IP del peer`

**Parte 2 — IPSEC (Fase 2 — define cómo cifrar los datos)**

`crypto ipsec transform-set <VAR2> esp-aes esp-sha-hmac # Protocolos de cifrado+hash (Mejor esp-aes-256)`  
`access-list 110 permit ip 192.168.10.0 0.0.0.255 192.168.20.0 0.0.0.255`  
`#                            ^red local origen    ^red destino remota`  
`#Máscaras wildcard!.`  
`#101 es otra variable(obl. numérica y diferente en cada vpn), actúa como nombre de la ACL`

**Parte 3 — CRYPTO MAP (une todo: qué, cómo y con quién)**

`crypto map <VAR3> 10 ipsec-isakmp # Entrada 10 del crypto map <VAR3>. El 10 es simplemente un número identificador que permite tener múltiples reglas diferentes dentro del mismo crypto map.`   
  `set peer <IP_pública_destino> # IP pública del otro extremo`  
  `match address 110 # Qué tráfico cifrar (la ACL)`  
  `set transform-set <VAR2> # Qué transform-set usar`  
`exit`

`interface fa0/1 # Interfaz outside (VLAN 2)`  
  `crypto map <VAR3> # Activar el crypto map en la salida`  
`exit`

### Preguntas clave de examen

- `authentication` → **pre-share** y **pre-share key**  
- `encryption aes` → opciones: **128, 192, 256**  
- `match address` → va seguido del número de la **lista de control de acceso**  
- `set transform-set` → va seguido del nombre de la **segunda variable** (VAR2, definida en `crypto ipsec transform-set`)  
- Variables totales: **3**  
- Verificar encriptación: `show crypto ipsec sa`

---

## SEGURIDAD POR MAC (Switch)

### Comandos obligatorios (siempre hay que poner los dos)

`interface fa0/1`  
  `switchport mode access        ! OBLIGATORIO`  
  `switchport port-security      ! OBLIGATORIO`

### Modo Sticky (aprende la MAC automáticamente)

`switchport port-security mac-address sticky`  
`switchport port-security maximum 1`  
`switchport port-security violation shutdown`

La primera mac que se conecta se queda, y si se conecta otra bloquea.

### Modo manual (especificas tú la MAC)

`switchport port-security maximum 3`  
`switchport port-security mac-address 0111.2233.44ca`  
`switchport port-security violation shutdown # o protect / restrict`

### Opciones ante infracción

| Opción | Qué hace |
| :---- | :---- |
| **shutdown** | Bloquea el puerto e incrementa el contador de infracciones |
| **protect** | Bloquea tramas sin bloquear el puerto, NO incrementa el contador |
| **restrict** | Bloquea tramas sin bloquear el puerto, SÍ incrementa el contador y envía avisos SNMP |

### Recuperar puerto bloqueado

Ejecutar **shutdown** y luego **no shutdown** en la interfaz — no hay otra forma

---

## PROXY con ACL \- Denegar tráfico (Router Cisco)

Filtro para determinar si un equipo se debe ver con otro de otra red (si es de la misma se hace de otra forma)

`ip access-list extended <VAR>`  
  `deny ip host <ip_origen> host <ip_destino> # Esto cierra todo desde ip_origen(maquina que no puede acceder) a ip_destino (máquina a la que no puede acceder)`   
`# Para no ser tan antisociales en vez de la anterior puedes denegar servicios`  
  `deny tcp host <ip_origen> host <ip_destino> eq www  deny tcp host <ip_origen> host <ip_destino> eq ftp`  
  `deny icmp host <IP_origen> host <IP_destino> eq echo #Denegar ping`  
  `permit ip any any # Permite el resto —  SIEMPRE al final porque las ACLs se evalúan en orden secuencial (primera coincidencia gana)`  
`exit`

`interface fa0/0`  
  `ip access-group <VAR> in`  
`exit`

- `in` \= el origen es quien entra a la interfaz (los que quieren acceder)  
- `permit ip any any` siempre al final para no bloquear todo

---

## NAT (Network Address Translation)

- NAT \= traduce IP **privada → pública**  
- Oculta la estructura interna de la red

`ip nat pool <VAR1> <IP_publica_inicio> <IP_publica_fin> netmask <máscara> #VAR1 número/nombre 1 random.`   
`ip nat inside source list <VAR1> pool <VAR1> overload`  
`ip nat inside source static <IP_privada> <IP_publica_falsa> #Esta es la línea que natea.`  
`access-list <VAR1> permit <red_privada> <wildcard> #Permite el tráfico de la ACL <VAR1>`  
`interface fa0/0`  
  `ip nat inside`  
`interface s0/3/0`  
  `ip nat outside`

- La `<IP_publica_inicio>` es la del router donde estamos y la `<IP_publica_fin>` es la contraria pública de la interfaz sospechosa  
- `<máscara>` suele ser 255.255.255.0 (se pone la mayor)  
- Para otra source list pon `<VAR1>` diferente.  
- Ver traducciones: `show ip nat translations`  
- Las interfaces en la privada es `inside`, en la pública `outside`

---

## SSH

- **SSH** \= Acceso remoto **seguro** a router/switch  
- **Telnet** \= Acceso remoto **no seguro** (no encripta)

`hostname <router_name>`  
`ip domain-name <domain.tdl>`  
`crypto key generate rsa # elegir 1024 – 2048 – 4096`  
`username <username> privilege 15 secret <contraseña>`  
`line vty 0 4 # 4 puertos para ssh`  
  `transport input SSH`  
  `login local`  
`exit`

Conectar desde PC: `ssh -l <router_name> <IP_router> # OJO! Sin la arroba delante de IP` 

---

## TFTP

Copiar SO al servidor TFTP:  
`show ver  #anotar nombre del archivo flash <nombre_flash>`  
`copy flash tftp`  
  `Source filename? <nombre_flash>`  
 ` Address or name of remote host? <IP_TFTP>`

Recuperar imagen desde TFTP:  
`copy tftp flash`  
 ` Address or name of remote host? <IP_TFTP>`  
  `<nombre_archivo>`

---

## SEGURIDAD POR TACACS (AAA)

| Protocolo | Uso |
| :---- | :---- |
| **TACACS** | Contraseñas al entrar en router físico (ej. Cisco 2811\) |
| **RADIUS** | Seguridad inalámbrica |

`aaa new-model`  
`aaa authentication login default group tacacs Esat dice que se puede ver la contraseña`  
`aaa authentication enable default group tacacs`  
`tacacs-server host [ip donde se guardan] key [user]`

---

## MONITORIZACIÓN CISCO

| Herramienta | Tipo | Para qué |
| :---- | :---- | :---- |
| **Syslog** | Software (servidor) | Logs de eventos del router |
| **Netflow** | Software (servidor) | Análisis de tráfico de red |
| **Sniffer** | Aparato **físico** | Captura tráfico entre VLANs (lo que Syslog/Netflow no ven) |

Ping entre VLANs: Syslog y Netflow **no se enteran** → necesitas el **Sniffer**

### Netflow

`ip flow-export source fa0/0`  
`ip flow-export destination <IP_servidor> 9996`  
`interface fa0/0`  
  `ip flow ingress`

### Syslog

`ip inspect name <VAR> icmp audit-trail on`  
`ip inspect name <VAR> http audit-trail on`  
`ntp server <IP_servidor>`  
`ntp update-calendar`  
`logging host <IP_servidor>`  
`service timestamps log datetime msec`  
`interface fa0/0 # interface a monitorizar`  
  `ip inspect <VAR> in`

### Sniffer (Switch)

`monitor session 1 source interface Fa0/1`  
`monitor session 1 source interface Fa0/2`  
`monitor session 1 destination interface Fa0/24 #interfaz conectada al sniffer`

---

## NMAP

| Comando | Función |
| :---- | :---- |
| `nmap -sP 192.168.2.0/24` | Detecta dispositivos activos (sin detalle de puertos) |
| `nmap -O <IP>` | Detecta sistema operativo |
| `nmap -sV <IP>` | Versiones de servicios |
| `nmap -sS <IP>` | Escaneo **silencioso** |
| `nmap -F <IP>` | Escaneo rápido |
| `nmap -T4 <IP>` | Escaneo rápido (agresividad T) |
| `nmap -p 1-1000 <IP>` | Escanea rango de puertos |
| `nmap -p- <IP>` | Escanea **todos** los puertos |
| `nmap --exclude <IP>` | Excluye una IP del escaneo |

---

## WIRESHARK

| Filtro | Función |
| :---- | :---- |
| `icmp` / `http` / `dns` / `tcp` / `udp` | Por protocolo |
| `ip.addr == <IP>` | Tráfico de/hacia esa IP |
| `ip.src == <IP>` | Tráfico **origen** |
| `ip.dst == <IP>` | Tráfico **destino** |
| `tcp.port == 443` | Por puerto |
| `eth.addr == MAC` | Por dirección MAC |
| `not ip.addr == <IP>` | Excluir esa IP |
| `frame.contains "texto"` | Buscar contenido |
| `A || B` / `A && B` | O / Y lógico |

Rangos de puertos:

- `0 – 1024` → puertos bien conocidos  
- `1024 – 49.151` → puertos registrados  
- `49.151 – 65.535` → puertos dinámicos/privados

---

RESUMEN EXPRESS — DATOS NUMÉRICOS QUE CAEN EN EXAMEN

| Dato | Valor |
| :---- | :---- |
| VLANs máximo ASA 5505 | **3** |
| VLAN por defecto ASA 5505 | **VLAN 1**, IP **192.168.1.1** |
| Seguridad inside | **100** |
| Seguridad outside | **0** |
| Seguridad DMZ | **50** |
| Primera IP DHCP inside por defecto | **192.168.1.5** |
| Puertos NetBIOS | **137, 138, 139** |
| Variables VPN | **3** (VAR1, VAR2, VAR3) |
| AES en VPN: bits disponibles | **128 / 192 / 256** |
| Authentication VPN: opciones | **pre-share** / **pre-share key** |
| Grupos ISAKMP | 1, 2 o 5 (estándar: **2**, más seguro: **5**) |
| Lifetime por defecto VPN | **86400** seg (24h) |
| Puerto Netflow | **9996** |
| Máxima seguridad WiFi | **WPA2** |
| TACACS vs RADIUS | TACACS \= router físico, RADIUS \= WiFi |
| Recuperar puerto MAC bloqueado | **shutdown \+ no shutdown** |
| Verificar VPN encriptada | `show crypto ipsec sa` |

## 

