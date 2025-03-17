### **Topología de Red para el Proyecto IDS:**

1. **Máquinas Virtuales (VMs):**

- **VM 1 (Servidor/IDS)**: En esta máquina instalarás **Suricata** (el IDS) y **Wireshark** para analizar el tráfico.
- **VM 2 (Cliente A - Vulnerable)**: Esta máquina simula un cliente vulnerable que puede ser atacado o generar tráfico malicioso.
- **VM 3 (Cliente B - Sospechoso)**: Este cliente simula un comportamiento sospechoso, como un botnet o malware.
- **VM 4 (Máquina de Análisis)**: Esta máquina captura el tráfico con **Wireshark** para visualizar los datos.

2. **Red Interna Aislada:**

- Conecta todas las máquinas virtuales a una **red interna** (sin conexión a Internet). Esto asegura que todo el tráfico entre ellas sea capturable sin afectar tu red real.

3. **Flujo de Datos:**

- **Wireshark** captura el tráfico entre las máquinas.
- **Suricata** en el servidor (VM 1) analiza el tráfico y genera alertas si detecta actividades sospechosas.

4. **Pruebas:**

- Genera tráfico legítimo y malicioso (escaneos de puertos, DDoS, malware) entre las máquinas para probar cómo Suricata detecta las intrusiones.
