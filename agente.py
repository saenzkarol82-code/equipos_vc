import sys
import subprocess
import json

# 1. LECTURA DE ARGUMENTOS (Lo que envió PHP)
# sys.argv es una lista que captura las palabras escritas en la consola.
# sys.argv[0] es "agente.py"
# sys.argv[1] es "192.168.1.15" (La IP)
# sys.argv[2] es "camara" o "microfono" (La Acción - Opcional)

ip_dispositivo = sys.argv[1]
puerto_adb = "5555" # Puerto estándar de depuración inalámbrica Android
objetivo = f"{ip_dispositivo}:{puerto_adb}"

# Verificamos si PHP nos envió una acción específica (sys.argv tiene 3 elementos)
if len(sys.argv) > 2:
    accion = sys.argv[2]
    
    # 2. EVALUACIÓN DE LA ACCIÓN
    if accion == "camara":
        """
          LÓGICA ADB PARA CÁMARA:
          - adb -s [IP]: Le dice a ADB con qué teléfono hablar.
          - shell am start: Ordena al Activity Manager (am) iniciar una pantalla.
          - -a android.media.action.IMAGE_CAPTURE: Es el "Intent" nativo universal de Android 
            que despierta el hardware de la cámara sin importar la marca del celular.
        """
        comando_adb = f"adb -s {objetivo} shell am start -a android.media.action.IMAGE_CAPTURE"
        subprocess.run(comando_adb, shell=True, capture_output=True)
        
        # Devolvemos un JSON a PHP confirmando que se dio la orden
        print(json.dumps({"estado_hardware": "Comando de cámara enviado"}))
        
    elif accion == "microfono":
        """
          LÓGICA ADB PARA MICRÓFONO:
          RECORD_SOUND es el Intent que abre la app de grabadora de voz nativa.
        """
        comando_adb = f"adb -s {objetivo} shell am start -a android.provider.MediaStore.RECORD_SOUND"
        subprocess.run(comando_adb, shell=True, capture_output=True)
        
        print(json.dumps({"estado_hardware": "Comando de micrófono enviado"}))

else:
    # 3. MODO ESCANEO (El comportamiento original)
    # Si no hay acción (len(sys.argv) == 2), Python hace lo que ya sabía hacer: 
    # conectarse, leer apps, leer SO, empaquetar en JSON y devolver la lectura.
    pass