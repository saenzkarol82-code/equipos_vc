⚙️ Requisitos Previos
Para ejecutar este proyecto en tu entorno local, necesitas:

Servidor web local (XAMPP recomendado).

Python 3.x instalado y agregado al PATH de Windows.

Descargar las SDK Platform-Tools de Android (ADB) y colocar los archivos dentro de la carpeta adb/ del proyecto.

💻 Instalación y Configuración
1. Preparar el entorno web y base de datos
Clona este repositorio dentro de la carpeta htdocs de XAMPP (ej. C:\xampp\htdocs\equipos_vc).

Inicia Apache y MySQL desde el panel de control de XAMPP.

Importa la base de datos creando las siguientes tablas (Ejemplo estructural):

SQL
CREATE TABLE equipos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    ip VARCHAR(15) UNIQUE NOT NULL,
    mac_address VARCHAR(20),
    tipo VARCHAR(50)
);

CREATE TABLE historial_escaneos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipo_id INT,
    estado VARCHAR(20),
    version_os VARCHAR(50),
    aplicaciones TEXT,
    fecha_escaneo TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (equipo_id) REFERENCES equipos(id)
);
2. Configurar un nuevo dispositivo Android (Guía ADB)
Para que el sistema web pueda encender la cámara/micrófono de un dispositivo Android de forma inalámbrica, se debe establecer un puente de red por primera vez. Sigue este paso a paso estricto:

En el dispositivo Android, ve a Ajustes > Sistema > Acerca del teléfono y presiona 7 veces en "Número de compilación" para activar las Opciones de Desarrollador.

Ingresa a Opciones de Desarrollador y activa la Depuración por USB.

Conecta el dispositivo a la computadora mediante un cable USB.

Abre una terminal en la ruta del proyecto y verifica la conexión física:

Bash
cd C:\xampp\htdocs\equipos_vc\adb
.\adb devices
(Acepta la alerta de seguridad RSA en la pantalla del celular si aparece el estado "unauthorized").

Abre el puerto interno del celular para recibir comandos por red:

Bash
.\adb tcpip 5555
Desconecta el cable USB. Obtén la IP local del celular (ej. 192.168.2.13) y enlaza la computadora inalámbricamente:

Bash
.\adb connect 192.168.2.13:5555
(Acepta la alerta de depuración inalámbrica en la pantalla del dispositivo).

¡Listo! A partir de este momento, el archivo agente.py del sistema web podrá enviarle "Intents" (Intenciones) al dispositivo Android para disparar la cámara (android.media.action.IMAGE_CAPTURE) o el micrófono.

🐛 Solución de Problemas (Troubleshooting)
Error device not found al probar hardware: Ocurre si intentas ejecutar una acción sin haber establecido el puente de red antes (adb connect).

Error 10060 (Timeout) al conectar: Ocurre si la IP del celular cambió dinámicamente, si la pantalla se apagó suspendiendo el Wi-Fi, o si el puerto 5555 se cerró tras reiniciar el dispositivo. Solución: Repetir el proceso de apertura de puerto con cable USB.

Error 404 Not Found en controladores: Verifica que estás ejecutando el proyecto a través de http://localhost/equipos_vc/ y no abriendo los archivos directamente desde el explorador de Windows.

📝 Arquitectura del Flujo de Hardware
Vista: El usuario hace clic en "Probar Cámara" en dashboard.php.

Controlador: controlador_hardware.php recibe la acción por URL (GET) e invoca a Python vía shell_exec().

Agente: agente.py recibe los argumentos, ensambla el comando ADB nativo e invoca el Activity Manager (am) de Android.

Respuesta: El celular abre físicamente el hardware y PHP redirige al usuario de vuelta al Dashboard en milisegundos.

Proyecto desarrollado con fines de gestión de infraestructura audiovisual.


Este `README.md` no solo explica cómo funciona tu código, sino que documenta exactamente ese problema de red que resolvimos juntos (el error 10060 y la conexión física primero) para que cualquiera que clone tu proyecto en el futuro sepa exactamente cómo superarlo.