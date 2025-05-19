## 📝 ANTEPROYECTO – Panel Web de Administración Linux Multiusuario 

### 📌 **Introducción**

En entornos profesionales, administrar uno o varios servidores Linux requiere experiencia técnica y acceso por terminal. Esta dependencia puede generar errores o limitar el control por parte de ciertos perfiles. Con este proyecto se pretende desarrollar un **panel web seguro y accesible** que permita **gestionar tanto el servidor local como otros servidores Linux remotos** mediante tareas automatizadas y controladas por permisos de usuario.

---

### 🎯 **Finalidad**

Proporcionar un sistema centralizado desde el que distintos usuarios, con diferentes permisos, puedan administrar uno o más servidores Linux. El sistema permitirá ejecutar tareas administrativas de mantenimiento, supervisión y seguridad sin necesidad de acceso directo por terminal.

---

### 🎯 **Objetivos**

#### Objetivo General:

Desarrollar un **panel web interactivo y seguro** que permita a usuarios autenticados ejecutar tareas administrativas sobre el servidor local y otros servidores remotos.

#### Objetivos Específicos:

- Implementar login con contraseña cifrada y roles personalizados.
- Controlar permisos por usuario para limitar o permitir acciones específicas.
- Permitir al administrador:
	- Gestionar usuarios del panel (crear, borrar, cambiar contraseña).
	- Asignar permisos dinámicamente mediante checkboxes.
- Funcionalidades principales del panel:
	- Gestión de backups.
	- Visualización de CPU/RAM/Disco con gráficos en tiempo real.
	- Supervisión de procesos activos y posibilidad de finalizar procesos.
	- Verificación de logs del sistema y Apache.
	- Diagnóstico de red y revisión de conexiones activas.
	- Gestión de reglas del firewall UFW.
	- Visualización y edición de tareas programadas (cron).
	- Administración de servicios básicos (Apache, SSH, MySQL, etc.).
	- Ejecución remota de tareas sobre otros servidores registrados.

---

### 🧰 **Medios necesarios**

- **Servidor principal Linux** con Apache2, PHP y permisos `sudo`
- **PHP 7+**, HTML5, CSS3 (estilo unificado con `style.css`)
- **Chart.js** para gráficos dinámicos
- **Bot de Telegram** para alertas de login
- **Scripts Bash personalizados** para tareas como backups, verificación de integridad o limpieza del sistema
- **Archivos `usuarios.php` y `permisos.php`** para gestión dinámica desde el panel

---

### 🗓️ **Planificación (70 horas estimadas)**

| Fase | Actividad | Horas |
| --- | --- | --- |
| 1 | Diseño inicial del panel y autenticación con login (admin) | 6h |
| 2 | Estructura modular del panel, separación por secciones | 6h |
| 3 | Implementación de backups (crear, listar, eliminar, descargar) | 5h |
| 4 | Monitorización del sistema con gráficos: CPU, RAM, disco | 6h |
| 5 | Gestión de procesos y tareas crontab | 5h |
| 6 | Diagnóstico de red, conexiones activas, intentos fallidos | 5h |
| 7 | Gestión de firewall UFW (ver estado, añadir/quitar reglas) | 4h |
| 8 | Administración de usuarios del panel (crear, borrar, cambiar contraseña) | 5h |
| 9 | Sistema de permisos dinámicos con checkboxes y `permisos.php` | 5h |
| 10 | Integración de ejecución remota en servidores externos (vía SSH/Ansible) | 7h |
| 11 | Estética y estilo del panel (CSS, estructura, navegación) | 3h |
| 12 | Pruebas, correcciones, documentación interna y comentarios | 3h |

**Total estimado: 70 horas**
