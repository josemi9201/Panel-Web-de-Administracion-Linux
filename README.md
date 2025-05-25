#Documentación del Proyecto: Panel de Administración con Gestión de Conexión Remota


## Introducción

Este proyecto consiste en el desarrollo de un panel de administración web para sistemas Linux que facilita la gestión y monitorización tanto de un servidor local como de múltiples servidores remotos. La interfaz permite ejecutar comandos, gestionar usuarios, controlar el firewall, supervisar logs, y realizar diagnósticos, todo desde una única plataforma. Se ha implementado además la funcionalidad de conexión remota mediante SSH, permitiendo seleccionar y administrar diferentes servidores de forma segura y eficiente.

---

## Finalidad

La finalidad del proyecto es proporcionar una herramienta sencilla, accesible y centralizada para la administración de sistemas Linux, reduciendo la complejidad de tareas habituales y permitiendo a los administradores operar desde un entorno web intuitivo. Además, busca optimizar la gestión de servidores remotos, facilitando la ejecución de acciones administrativas sin necesidad de acceso directo por consola, aumentando así la eficiencia operativa y la seguridad.

---

## Objetivo

El objetivo principal es desarrollar un panel funcional y seguro que permita:

- Administrar sistemas Linux locales y remotos mediante comandos ejecutados por SSH.
- Gestionar múltiples servidores remotos con almacenamiento y activación dinámica de sus credenciales.
- Facilitar la ejecución de scripts y tareas comunes del sistema desde una interfaz gráfica.
- Implementar un sistema de roles y permisos para controlar el acceso a diferentes funcionalidades.
- Permitir la subida y despliegue automatizado de scripts en servidores remotos para mantener la funcionalidad del panel.

---

## Medios utilizados

Para llevar a cabo el proyecto se han utilizado las siguientes herramientas y tecnologías:

- **PHP**: Desarrollo del backend y lógica del panel.
- **HTML, CSS y JavaScript**: Construcción de la interfaz web y experiencia de usuario, con componentes dinámicos como acordeones.
- **SSH y sshpass**: Establecimiento de conexiones seguras y ejecución remota de comandos.
- **JSON**: Almacenamiento local de datos de configuración, como la lista de servidores remotos.
- **Sistemas Linux**: Entorno para el panel y servidores administrados.
- **Bash scripting**: Scripts auxiliares para tareas específicas (backups, limpieza, diagnósticos).
- **Gestión de sesiones PHP**: Para mantener el estado del usuario y la conexión remota activa.
- **Control de acceso basado en roles y permisos**: Para garantizar la seguridad y restricción de funcionalidades.

---

## Tiempo invertido

El desarrollo total del proyecto ha requerido aproximadamente **70 horas**, distribuidas en:

- Diseño y planificación: 8 horas
- Desarrollo backend y lógica PHP: 20 horas
- Construcción de la interfaz y usabilidad: 12 horas
- Implementación de conexiones remotas y gestión de servidores: 10 horas
- Funcionalidad para subida y despliegue de scripts remotos: 5 horas
- Pruebas, depuración y mejoras: 10 horas
- Documentación y presentación final: 5 horas
