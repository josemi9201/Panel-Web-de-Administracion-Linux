### **Topología de Red para el Proyecto IDS:**

1. **Máquinas Virtuales (VMs):**

- **VM 1 (Servidor/IDS)**: En esta máquina instalarás **Suricata** (el IDS) para analizar el tráfico y generar alertas.
- **VM 2 (Cliente A - Vulnerable)**: Esta máquina simula un cliente vulnerable que puede ser atacado o generar tráfico malicioso.
- **VM 3 (Cliente B - Sospechoso)**: Este cliente simula un comportamiento sospechoso, como un botnet o malware.
- **VM 4 (Máquina de Análisis)**: Esta máquina analizará el trafico con **Wireshark** para visualizar los datos y con Kibana/Elasticsearch podrá almacenar logs de eventos y visualizarlos a largo plazo, generando gráficos y dashboards para detectar patrones más complejos..

2. **Red Interna Aislada:**

- Conecta todas las máquinas virtuales a una **red interna** (sin conexión a Internet). Esto asegura que todo el tráfico entre ellas sea capturable sin afectar tu red real.

3. **Flujo de trabajo:**
Monitoreo del tráfico (VM1):

Suricata en la VM1 captura el tráfico entre las máquinas y lo analiza en tiempo real.
Generación de tráfico (VM3):

La VM3 (Cliente B) realiza ataques (escaneos de puertos, DoS, etc.) que deben ser detectados por Suricata en VM1.
Detección de ataques (VM1):

Suricata genera alertas si detecta tráfico sospechoso. Por ejemplo, si la VM3 intenta hacer un escaneo de puertos en la VM2, Suricata debería detectar esta actividad y generar una alerta.
Análisis de tráfico y alertas (VM4):

Wireshark en la VM4 permite analizar las capturas de tráfico y revisar las alertas generadas por Suricata.
Si usas Kibana/Elasticsearch, se pueden almacenar logs de eventos y analizarlos más tarde para generar dashboards que muestren tendencias de tráfico malicioso a lo largo del tiempo.
