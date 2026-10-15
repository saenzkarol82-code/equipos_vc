import sys
import subprocess
import json

def ejecutar_comando(comando):
    """Función auxiliar para ejecutar comandos de sistema y capturar la salida."""
    try:
        # Ejecuta el comando, captura el texto y le da un tiempo límite de 5 segundos
        resultado = subprocess.run(comando, capture_output=True, text=True, timeout=5, shell=True)
        return resultado.stdout.strip()
    except subprocess.TimeoutExpired:
        return ""
    except Exception as e:
        return ""

# 1. Validar que PHP nos envíe una IP
if len(sys.argv) < 2:
    print(json.dumps({"error": "No se proporcionó IP"}))
    sys.exit(1)

ip_equipo = sys.argv[1]
puerto = "5555" # Puerto por defecto de ADB por Wi-Fi
objetivo = f"{ip_equipo}:{puerto}"

# 2. Intentar conectar por ADB
ejecutar_comando(f"adb connect {objetivo}")

# 3. Comprobar si realmente está conectado y responde
comprobacion = ejecutar_comando(f"adb -s {objetivo} shell echo 'activo'")

if "activo" in comprobacion:
    estado = "Encendido"
    
    # Extraer la MAC (buscamos en la tarjeta Wi-Fi, si falla, buscamos en la de red cableada)
    mac = ejecutar_comando(f"adb -s {objetivo} shell cat /sys/class/net/wlan0/address")
    if not mac or "No such file" in mac:
        mac = ejecutar_comando(f"adb -s {objetivo} shell cat /sys/class/net/eth0/address")
    mac = mac.strip().upper() if "No such" not in mac else "MAC_DESCONOCIDA"
    
    # Extraer versión del Sistema Operativo
    version_os = ejecutar_comando(f"adb -s {objetivo} shell getprop ro.build.version.release")
    
    # Extraer aplicaciones instaladas por el usuario (filtro -3)
    apps_crudas = ejecutar_comando(f"adb -s {objetivo} shell pm list packages -3")
    # Limpiar el texto para que solo queden los nombres limpios (ej: com.netflix.ninja)
    apps = [linea.replace("package:", "") for linea in apps_crudas.splitlines() if linea]
    
else:
    estado = "Apagado"
    mac = "N/A"
    version_os = "N/A"
    apps = []

# Desconectar por limpieza
ejecutar_comando(f"adb disconnect {objetivo}")

# 4. Construir el diccionario y empaquetarlo en JSON
datos_recolectados = {
    "ip": ip_equipo,
    "mac": mac,
    "estado": estado,
    "version_os": f"Android {version_os}" if version_os != "N/A" else "N/A",
    "aplicaciones": apps
}

# 5. Imprimir la salida (Esto es lo único que PHP verá)
print(json.dumps(datos_recolectados))