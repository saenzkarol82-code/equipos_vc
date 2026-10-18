<?php
/*
  CONTROLADOR DE HARDWARE
  Su única misión es recibir peticiones de la vista (Dashboard), 
  traducirlas a comandos de Windows, ejecutar Python y devolverte al inicio.
*/

// 1. CAPTURA DE DATOS (Leer la URL)
// Usamos $_GET porque los datos viajan visibles en la barra de direcciones
if (isset($_GET['ip']) && isset($_GET['accion'])) {
    
    $ip = $_GET['ip'];
    $accion = $_GET['accion'];

    /* 
      2. PREPARACIÓN DEL COMANDO
      escapeshellarg() es un escudo de seguridad. Envuelve la IP y la acción 
      entre comillas para que el sistema operativo las trate como texto inofensivo 
      y no como comandos maliciosos.
    */
    $ip_segura = escapeshellarg($ip);
    $accion_segura = escapeshellarg($accion);

    /*
      3. ENSAMBLAJE (La carrera de relevos)
      Armamos el texto exacto que escribirías tú a mano en la consola de Windows.
      Ejemplo resultante: python agente.py "192.168.1.15" "camara"
    */
    $comando = "python agente.py $ip_segura $accion_segura";

    // 4. EJECUCIÓN 
    // shell_exec abre una consola invisible, pega el comando, presiona "Enter" y espera.
    $resultado = shell_exec($comando);

    /*
      5. RETORNO (Cerrar el ciclo MVC)
      Una vez que Python termina su trabajo en el celular, PHP te devuelve 
      al Dashboard para que sigas operando.
    */
    header("Location: dashboard.php?alerta=accion_enviada");
    exit();

} else {
    echo "Faltan parámetros de red o acción.";
}
?>