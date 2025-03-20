
# IDS-TFG
## Sistema de Detección de Intrusiones (IDS) - TFG ASIR
Este proyecto tiene como objetivo desarrollar un IDS para monitorear y proteger redes internas

### **Introducción:**

El proyecto tiene como objetivo el desarrollo de un **Sistema de Detección de Intrusiones (IDS)** para monitorear y proteger la red interna de una organización. El IDS analizará el tráfico de red entrante y saliente buscando patrones de tráfico anómalos que puedan indicar posibles ataques, como intrusiones, intentos de acceso no autorizado, ataques de denegación de servicio (DoS), o malware. Utilizando herramientas de análisis de tráfico como **Wireshark** o **Suricata** y técnicas de **machine learning**, el sistema será capaz de identificar comportamientos sospechosos en tiempo real y generar alertas cuando se detecte una posible intrusión.

---

### **Finalidad:**

La finalidad de este proyecto es mejorar la **seguridad de la red** de una organización mediante un sistema automatizado que pueda identificar y alertar de posibles amenazas en tiempo real. Esto ayudará a los administradores de red a reaccionar rápidamente ante incidentes, minimizando el riesgo de una brecha de seguridad. Además, permitirá mejorar la visibilidad de los posibles ataques que podrían pasar desapercibidos en una red sin monitoreo.

El IDS buscará patrones de tráfico malicioso como **escaneos de puertos**, **infecciones de malware**, y **comportamientos de botnets**. Además, se proporcionará un sistema de alertas que notifique a los administradores sobre eventos de seguridad críticos.

---

### **Objetivos:**

- **Implementación de Análisis de Tráfico de Red**:
    
    - Utilizar herramientas como **Wireshark**, **Suricata** o **tcpdump** para capturar y analizar el tráfico de la red.
    - Filtrar el tráfico para identificar patrones sospechosos, como picos inusuales en el tráfico, paquetes anómalos o intentos de acceso a puertos no autorizados.
    
- **Integración del IDS con Herramientas de Monitoreo**:
    
    - Integrar el sistema IDS con herramientas como **Grafana** o **Kibana** para visualizar en tiempo real el tráfico de red y las alertas de seguridad.
    - Configurar **alertas automáticas** (por ejemplo, mediante correo electrónico o sistemas como **Slack**) cuando se detecte tráfico sospechoso.
    
- **Análisis de Amenazas y Generación de Reportes**:
    
    - Realizar un análisis profundo del tráfico de red y los incidentes de seguridad detectados, generando informes detallados sobre los posibles ataques y vulnerabilidades en la red.
    - Implementar un sistema para almacenar y gestionar los informes de eventos de seguridad, para su posterior análisis y mejora de la red.

- **Documentación del Proyecto**:
    
    - Crear documentación detallada del sistema, explicando su arquitectura, configuración y cómo utilizar las herramientas implementadas.
    - Desarrollar un manual de uso para los administradores de red y cómo responder ante una alerta de seguridad generada por el IDS.

---

### **Medios necesarios:**

**Medios físicos (hardware):**

- **Máquina o servidor** para instalar las herramientas de captura y análisis de tráfico (puede ser una máquina virtual o servidor físico).
- **Red interna** que permita realizar las pruebas de tráfico, como una red LAN controlada. Si no se dispone de una red de pruebas, se puede crear una red virtualizada utilizando **máquinas virtuales**.

**Medios lógicos (software):**

- **Wireshark** o **Suricata**: Herramientas de análisis de tráfico de red que capturan y examinan paquetes de red.
- **Python**: Para desarrollar scripts de machine learning y procesar los datos de tráfico de red.
- **Librerías de machine learning en Python**: **Scikit-learn** o **TensorFlow** para crear y entrenar modelos predictivos sobre tráfico de red.
- **Grafana/Kibana**: Herramientas de visualización para crear dashboards de tráfico y alertas.
- **Base de datos**: Para almacenar eventos y generar reportes. Puede usarse **Elasticsearch** si se usa Kibana.
- **Sistemas de alertas**: Como **Slack** o un servidor de **correo electrónico** para generar alertas automáticas.
---

### **Planificación:**

| **Tarea**                                              | **Descripción**                                                                                             | **Duración estimada** |
| ------------------------------------------------------ | ----------------------------------------------------------------------------------------------------------- | --------------------- |
| **Investigación sobre IDS y herramientas de análisis** | Investigación de las mejores herramientas y tecnologías (Wireshark, Suricata, machine learning).            | 1 semana              |
| **Captura y análisis de tráfico de red**               | Configuración de herramientas para capturar tráfico y analizar paquetes de red.                             | 2 semana              |
| **Desarrollo del modelo de machine learning**          | Preparación de los datos y desarrollo de los algoritmos de machine learning.                                | 3 semanas             |
| **Integración con herramientas de monitoreo**          | Configuración de Grafana o Kibana para la visualización de tráfico y alertas.                               | 3 semana              |
| **Generación de alertas y reportes**                   | Implementación de un sistema de alertas automáticas y generación de informes sobre las amenazas detectadas. | 1 semana              |
| **Pruebas y ajustes**                                  | Realización de pruebas en diferentes condiciones de red y ajuste de los algoritmos.                         | 1 semana              |
| **Documentación del proyecto**                         | Creación de la documentación técnica y manual de usuario.                                                   | 1 semana              |

**Duración total estimada**: 12 semanas.




















### Estructura del proyecto
- `/src` → Código fuente
- `/docs` → Documentación del sistema
- `/datos` → Conjuntos de datos de tráfico de red
- `/config` → Archivos de configuración
- `/tests` → Tests unitarios y de integración
