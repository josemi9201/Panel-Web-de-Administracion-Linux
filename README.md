Despliegue de un servidor web seguro con panel de administración remota y sistema de alertas ante incidentes

## 🧾 GUIÓN DEL PROYECTO

### 📌 **1. Introducción**

En este proyecto se desarrolla una solución práctica para la administración y monitorización de un servidor Linux a través de una interfaz web. Se implementa un panel de control accesible por navegador desde el que se pueden ejecutar tareas comunes de mantenimiento, visualizar el estado del sistema y activar scripts predefinidos. Además, se configura un sistema básico de alertas que notifica posibles accesos indebidos o intentos de ataque, permitiendo al administrador actuar rápidamente. Todo el sistema se despliega en un entorno real mediante un servidor virtual en la nube.

---

### 🎯 **2. Finalidad**

El objetivo general del proyecto es ofrecer una herramienta sencilla y eficaz para la gestión remota de servidores Linux, especialmente útil en entornos educativos, domésticos o pequeñas empresas. El proyecto también permite mostrar en tiempo real cómo se pueden detectar accesos no autorizados y responder ante ellos, sirviendo como práctica integradora de seguridad, administración de sistemas y desarrollo web.

---

### 🛠️ **3. Objetivos técnicos**

- Desplegar un servidor Linux con servicios básicos (Apache, PHP).
    
- Desarrollar un panel de administración web funcional con autenticación.
    
- Ejecutar desde la web scripts de mantenimiento como backups o visualización de estado del sistema.
    
- Configurar el sistema para generar alertas ante ciertos eventos (fallos de login, escaneos, etc.).
    
- Simular ataques desde una máquina Kali Linux y analizar su impacto.
    
- Documentar el entorno, pruebas y resultados obtenidos.


---

### 💻 **4. Medios necesarios**

#### a) Hardware:

- 1 ordenador doméstico con Linux o Windows para pruebas locales.
    
- 1 PC antiguo o portátil usado como servidor local opcional.
    
- Cuenta en DigitalOcean (con 200$ de crédito), para crear al menos:
    
    - 1 Droplet con Ubuntu Server (servidor web y panel).
        
    - 1 Droplet adicional para pruebas o simulación de ataques (opcional).
        
    - 1 Máquina virtual local o en Kali Linux para realizar escaneos o ataques simulados.
        

#### b) Software:

- Ubuntu Server 22.04 LTS.
    
- Apache2, PHP y herramientas CLI de Linux.
    
- Bash para scripting.
    
- PHP para el desarrollo del panel web.
    
- `fail2ban` o herramientas para bloquear accesos no autorizados.
    
- `mailutils` o Telegram para envío de alertas (opcional).
    
- Kali Linux para pruebas de ataque (nmap, nikto, sqlmap, etc.).


---

### 🗓️ **5. Planificación del proyecto**

| Semana | Tarea a realizar                                                            |
| ------ | --------------------------------------------------------------------------- |
| 1      | Preparación del entorno: creación de droplet, instalación de servicios base |
| 2      | Desarrollo del panel web básico con interfaz PHP                            |
| 3      | Integración de scripts del sistema (backup, logs, estado del servidor)      |
| 4      | Configuración de alertas y notificaciones (vía log o email)                 |
| 5      | Simulación de ataques desde Kali y análisis de los eventos detectados       |
| 6      | Documentación, capturas, pruebas finales y redacción de la memoria          |
| 7      | Presentación y revisión del proyecto final                                  |
