
# Aws-Monitoring-TFG

### **1\. Introducción**

En este proyecto se va a diseñar e implementar una infraestructura en la nube utilizando Amazon Web Services (AWS), con el objetivo de establecer un sistema de monitorización centralizado mediante **Zabbix** y su visualización avanzada a través de **Grafana**.

El proyecto consistirá en la creación de una **infraestructura virtual** compuesta por varias instancias EC2 alojadas en una **VPC personalizada**. Una de estas instancias actuará como servidor de monitorización, donde se instalará Zabbix Server y Grafana. Las demás serán monitorizadas mediante Zabbix Agent. Este tipo de configuración es habitual en entornos de producción donde se requiere supervisar el estado, rendimiento y disponibilidad de servicios críticos.

A través de este proyecto se aprenderá a configurar de forma segura una infraestructura cloud básica, a desplegar herramientas de monitorización y a visualizar métricas en tiempo real. Todo ello cumpliendo con los principios de administración de sistemas y redes.

---

### **2\. Finalidad**

La finalidad del proyecto es ofrecer una solución práctica y funcional para monitorizar el estado de una infraestructura IT en la nube, que permita:

- Supervisar múltiples servidores desde un único punto de control.
- Visualizar el estado de los sistemas en tiempo real con paneles gráficos atractivos.
- Detectar fallos o comportamientos anómalos de forma anticipada.
- Establecer alertas automáticas para mejorar la respuesta ante incidencias.

Esta solución puede ser utilizada tanto por administradores de sistemas en entornos corporativos, como por pequeñas empresas o estudiantes que deseen aprender a gestionar infraestructuras distribuidas.

---

### **3\. Objetivos**

- Crear una **VPC personalizada** con una subred pública para alojar las instancias virtuales.
- Desplegar un **servidor EC2** con **Zabbix Server** y **Grafana** correctamente configurados.
- Instalar y configurar **Zabbix Agent** en otras instancias EC2.
- Asegurar la **comunicación segura entre instancias**, utilizando **grupos de seguridad personalizados**.
- Integrar **Grafana con Zabbix** para representar visualmente los datos monitorizados.
- Configurar **dashboards personalizados** en Grafana con métricas como CPU, memoria, disco o red.
- Establecer **alertas automáticas** desde Zabbix.
- Documentar el proceso de instalación, configuración y puesta en marcha.

---

### **4\. Medios necesarios**

#### **Medios físicos (Hardware)**:

- Un ordenador personal con conexión a internet.
- Cuenta activa en AWS con acceso al panel de gestión de EC2 y VPC.

#### **Medios lógicos (Software)**:

- AWS EC2, VPC y grupos de seguridad.
- Zabbix Server y Zabbix Agent.
- Grafana (última versión estable).
- Sistema operativo Ubuntu Server (en las instancias EC2).
- Cliente SSH (como PuTTY o terminal de Linux).
- Navegador web para acceder a interfaces web de Zabbix y Grafana.

---

### **5\. Planificación**

| **Fase** | **Duración estimada** | **Descripción** |
| --- | --- | --- |
| Diseño de la arquitectura | 3 días | Crear el diagrama, definir la red y componentes. |
| Creación de la VPC y subred | 2 días | Configurar VPC, subred pública y rutas. |
| Despliegue de instancias EC2 | 2 días | Lanzar instancias necesarias desde la consola de AWS. |
| Instalación de Zabbix Server | 3 días | Instalar base de datos, Zabbix y configurar el servidor. |
| Instalación de Zabbix Agents | 2 días | Configurar monitorización de otras instancias. |
| Instalación e integración de Grafana | 3 días | Instalar Grafana y conectarlo a Zabbix. |
| Configuración de dashboards | 2 días | Crear paneles visuales con métricas útiles. |
| Configuración de alertas | 2 días | Crear reglas de notificación ante fallos o eventos. |
| Pruebas y validaciones | 3 días | Verificar funcionamiento completo del sistema. |
| Documentación y conclusiones | 5 días | Redactar el informe del proyecto. |
