# ESTUDIO CB — Ciberseguridad
> Resumen orientado a examen. Basado en los tests de diciembre, enero y febrero.

---

## 1. CONCEPTOS GENERALES DE SEGURIDAD

| Concepto | Definición |
|---|---|
| **Seguridad pasiva** | Medidas que no actúan en tiempo real (copias de seguridad, SAI…) |
| **Seguridad activa** | Medidas que actúan en tiempo real (antivirus, firewall…) |
| **SAI** | Sistema de Alimentación **In**interrumpida (no "interrumpida") |
| **AAA** | **Autenticación, Autorización y Contabilización** |
| **Objetivo primordial de la seguridad** | Proteger la **información** |
| **Clave criptográfica** | Conjunto de códigos para transmitir un mensaje cuyo contenido se quiere ocultar |

---

## 2. TIPOS DE ATAQUES

| Ataque | Descripción |
|---|---|
| **Phishing** | El atacante se hace pasar por persona/empresa de confianza para robar info (contraseñas, datos bancarios). Ejemplo: "me llama un amigo de un amigo" |
| **Spoofing** | Técnica de **suplantación de identidad** |
| **Sniffer** | Análisis del tráfico de red, habitualmente para recabar contraseñas |
| **Denegación de servicios (DoS)** | Saturación de información que causa la caída del servicio → usuarios legítimos no pueden usarlo |

---

## 3. CORTAFUEGOS FÍSICO — ASA 5505

### Niveles de seguridad
- Escala: **0** (mínimo) → **100** (máximo)
- **VLAN 1 (inside)** → seguridad **100** — puertos 1 al 7
- **VLAN 2 (outside)** → seguridad **0** — puerto 0 (internet)
- **VLAN 3 (DMZ)** → seguridad **50**
- **Máximo de VLANs en un ASA 5505: 3**

### Ley de seguridad del cortafuegos
> **Seguridad superior puede comunicarse con seguridad inferior por defecto.**
> **Seguridad inferior NO puede comunicarse con seguridad superior por defecto.**

Para que outside (seg. 0) acceda a inside (seg. 100) hace falta configuración explícita (NAT/VPN/webvpn).

### ASA 5505 por defecto
- Viene con **VLAN 1** y la IP **192.168.1.1** ya asignada
- La primera IP del rango DHCP inside es **192.168.1.5**

### Dar de alta usuarios (webvpn)
- **No se pueden dar de alta usuarios desde la interfaz gráfica** — sólo por CLI
- **Sí se pueden dar de alta enlaces de páginas web (bookmarks) desde la interfaz gráfica**

### Acceso web desde outside a inside (WebVPN)
```
conf t
webvpn
enable outside
exit
username pepe password 1234
username pepe attributes
```
- `webvpn` + `enable outside` → permite acceder a seg. 100 desde seg. 0 por web
- Desde outside se usa la **IP pública del firewall** (la de la interfaz outside), nunca la IP interna del servidor

### DMZ
```
int vlan 3
name dmz
no forward int vlan 1   ! (por licencia Cisco no deja comunicarse con VLAN 1)
security-level 50
ip add 192.168.3.1 255.255.255.0
exit

! Conectividad DMZ ↔ outside:
access-list VAR extended permit icmp any any
access-group VAR out interface DMZ
```

### Conectividad entre seg. 100 y seg. 0 (inspect icmp)
```
class-map NOMBRE1
  match default-inspection-traffic
exit
policy-map NOMBRE2
  class NOMBRE1
    inspect icmp
exit
service-policy NOMBRE2 global
```

---

## 4. VPN (Router Cisco 2811)

### Conceptos clave
| Protocolo | Qué hace |
|---|---|
| **ISAKMP** | Encripta los datos **en el propio router** (administra las claves) |
| **IPSEC** | Encripta los datos que **viajan por el cable** (de extremo a extremo) |

- **`show crypto ipsec sa`** → comando para verificar que los datos llegan encriptados

### Variables en una VPN — se declaran **3**
| Variable | Dónde se usa |
|---|---|
| **NOMBRE1** | `crypto isakmp key NOMBRE1 address <IP_destino>` |
| **NOMBRE2** | `crypto ipsec transform-set NOMBRE2` |
| **NOMBRE3** | `crypto map NOMBRE3` |

### Configuración completa (igual en ambos routers)

**Parte 1 — ISAKMP (política de claves)**
```
crypto isakmp policy 10
  authentication pre-share          ! opciones: pre-share / pre-share key
  hash sha
  encryption aes 256                ! AES: 128 / 192 / 256
  group 2                           ! grupos: 1, 2 o 5 (más seguro: 5, estándar: 2)
  lifetime 86400                    ! 24h — igual en ambos routers
exit
crypto isakmp key NOMBRE1 address <IP_pública_destino>
```

**Parte 2 — IPSEC (protección del tráfico en cable)**
```
crypto ipsec transform-set NOMBRE2 esp-aes esp-sha-hmac
access-list 101 permit ip 192.168.10.0 0.0.0.255 192.168.20.0 0.0.0.255
crypto map NOMBRE3 10 ipsec-isakmp
  set peer <IP_pública_destino>
  match address 101              ! ← número de la access-list (lista de control de acceso)
  set transform-set NOMBRE2      ! ← segunda variable (NOMBRE2)
exit

interface fa0/1
  crypto map NOMBRE3
exit
```

### Preguntas clave de examen
- `authentication` → **pre-share** y **pre-share key**
- `encryption aes` → opciones: **128, 192, 256**
- `match address` → va seguido del número de la **lista de control de acceso**
- `set transform-set` → va seguido del nombre de la **segunda variable** (NOMBRE2, definida en `crypto ipsec transform-set`)
- Variables totales: **3**
- Verificar encriptación: `show crypto ipsec sa`

---

## 5. SEGURIDAD POR MAC (Switch)

### Comandos obligatorios (siempre hay que poner los dos)
```
interface fa0/1
  switchport mode access        ! OBLIGATORIO
  switchport port-security      ! OBLIGATORIO
```

### Modo Sticky (aprende la MAC automáticamente)
```
switchport port-security mac-address sticky
switchport port-security maximum 1
switchport port-security violation shutdown
```

### Modo manual (especificas tú la MAC)
```
switchport port-security maximum 3
switchport port-security mac-address 0111.2233.44ca
switchport port-security violation shutdown   ! o protect / restrict
```

### Opciones de violación
| Opción | Qué hace |
|---|---|
| **shutdown** | Bloquea el puerto e incrementa el contador de violaciones |
| **protect** | Bloquea tramas sin bloquear el puerto, NO incrementa el contador |
| **restrict** | Bloquea tramas sin bloquear el puerto, SÍ incrementa el contador y envía avisos SNMP |

### Recuperar puerto bloqueado
> Ejecutar **shutdown** y luego **no shutdown** en la interfaz — no hay otra forma

---

## 6. REDES INALÁMBRICAS

- Autenticación de **máxima seguridad** en router inalámbrico: **WPA2**
- WEP < WPA < **WPA2**

---

## 7. NetBIOS

- Al deshabilitar NetBIOS se cierran los puertos: **137, 138 y 139**

---

## 8. MONITORIZACIÓN CISCO

| Herramienta | Tipo | Para qué |
|---|---|---|
| **Syslog** | Software (servidor) | Logs de eventos del router |
| **Netflow** | Software (servidor) | Análisis de tráfico de red |
| **Sniffer** | Aparato **físico** | Captura tráfico entre VLANs (lo que Syslog/Netflow no ven) |

> Ping entre VLANs: Syslog y Netflow **no se enteran** → necesitas el **Sniffer**

### Netflow
```
ip flow-export source fa0/0
ip flow-export destination <IP_servidor> 9996
interface fa0/0
  ip flow ingress
```

### Syslog
```
ip inspect name VAR icmp audit-trail on
ip inspect name VAR http audit-trail on
ntp server <IP_servidor>
ntp update-calendar
logging host <IP_servidor>
service timestamps log datetime msec
interface fa0/0
  ip inspect VAR in
```

### Sniffer (Switch)
```
monitor session 1 source interface Fa0/1
monitor session 1 source interface Fa0/2
monitor session 1 destination interface Fa0/24   ! interfaz conectada al sniffer
```

---

## 9. SSH

- **SSH** = Acceso remoto **seguro** a router/switch
- **Telnet** = Acceso remoto **no seguro** (no encripta)
- Diferencia: **SSH encripta, Telnet no**

```
hostname router1
ip domain-name cafecito.es
crypto key generate rsa          ! elegir 1024 o 2048
username router1 privilege 15 secret <contraseña>
line vty 0 4
  transport input SSH
  login local
exit
```

Conectar desde PC:
```
ssh -l router1 <IP_router>
```

---

## 10. AAA — TACACS

| Protocolo | Uso |
|---|---|
| **TACACS** | Contraseñas al entrar en router físico (ej. Cisco 2811) |
| **RADIUS** | Seguridad inalámbrica |

```
aaa new-model
aaa authentication login default group tacacs
aaa authentication enable default group tacacs
tacacs-server host <IP_servidor> key <clave>
```

En el servidor AAA configurar: Client Name, IP, Secret, Server Type: **Tacacs**

---

## 11. PROXY / ACL (Denegar tráfico)

```
ip access-list extended NOMBRE
  deny tcp host <IP_origen> host <IP_destino> eq www
  deny tcp host <IP_origen> host <IP_destino> eq ftp
  deny icmp host <IP_origen> host <IP_destino> eq echo
  permit ip any any              ! SIEMPRE al final — permite el resto
exit

interface fa0/0
  ip access-group NOMBRE in
exit
```

- `in` = el origen es quien entra a la interfaz (los que quieren acceder)
- `permit ip any any` siempre al final para no bloquear todo

---

## 12. NAT

- NAT = traduce IP **privada → pública**
- Oculta la estructura interna de la red

```
ip nat pool 5 <IP_publica_inicio> <IP_publica_fin> netmask 255.255.255.0
ip nat inside source list 5 pool 5 overload
ip nat inside source static <IP_privada> <IP_publica_falsa>
access-list 5 permit <red_privada> <wildcard_ospf>

interface fa0/0
  ip nat inside
interface s0/3/0
  ip nat outside
```

Ver traducciones: `show ip nat translations`

---

## 13. NMAP

| Comando | Función |
|---|---|
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

## 14. WIRESHARK

| Filtro | Función |
|---|---|
| `icmp` / `http` / `dns` / `tcp` / `udp` | Por protocolo |
| `ip.addr == 192.168.1.1` | Tráfico de/hacia esa IP |
| `ip.src == IP` | Tráfico **origen** |
| `ip.dst == IP` | Tráfico **destino** |
| `tcp.port == 443` | Por puerto |
| `eth.addr == MAC` | Por dirección MAC |
| `not ip.addr == IP` | Excluir esa IP |
| `frame.contains "texto"` | Buscar contenido |
| `A \|\| B` / `A && B` | O / Y lógico |

Rangos de puertos:
- `0 – 1024` → puertos bien conocidos
- `1024 – 49.151` → puertos registrados
- `49.151 – 65.535` → puertos dinámicos/privados

---

## 15. TFTP

```
! Copiar SO al servidor TFTP:
show ver                    ! anotar nombre del archivo flash
copy flash tftp
  Source filename? <nombre_flash>
  Address or name of remote host? <IP_TFTP>

! Recuperar imagen desde TFTP:
copy tftp flash
  Address or name of remote host? <IP_TFTP>
  <nombre_archivo>
```

---

## RESUMEN EXPRESS — DATOS NUMÉRICOS QUE CAEN EN EXAMEN

| Dato | Valor |
|---|---|
| VLANs máximo ASA 5505 | **3** |
| VLAN por defecto ASA 5505 | **VLAN 1**, IP **192.168.1.1** |
| Seguridad inside | **100** |
| Seguridad outside | **0** |
| Seguridad DMZ | **50** |
| Primera IP DHCP inside por defecto | **192.168.1.5** |
| Puertos NetBIOS | **137, 138, 139** |
| Variables VPN | **3** (NOMBRE1, NOMBRE2, NOMBRE3) |
| AES en VPN: bits disponibles | **128 / 192 / 256** |
| Authentication VPN: opciones | **pre-share** / **pre-share key** |
| Grupos ISAKMP | 1, 2 o 5 (estándar: **2**, más seguro: **5**) |
| Lifetime por defecto VPN | **86400** seg (24h) |
| Puerto Netflow | **9996** |
| Máxima seguridad WiFi | **WPA2** |
| TACACS vs RADIUS | TACACS = router físico, RADIUS = WiFi |
| Recuperar puerto MAC bloqueado | **shutdown + no shutdown** |
| Verificar VPN encriptada | `show crypto ipsec sa` |
