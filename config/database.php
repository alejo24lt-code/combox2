<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cargar configuración según el sistema operativo
if (PHP_OS_FAMILY === 'Windows') {
    $required_env = [
        'SUPABASE_DB_HOST',
        'SUPABASE_DB_PORT',
        'SUPABASE_DB_USER',
        'SUPABASE_DB_NAME',
        'SUPABASE_DB_PASSWORD',
        'SUPABASE_URL',
        'SUPABASE_SECRET_KEY',
        'SUPABASE_BUCKET'
    ];

    foreach ($required_env as $name) {
        $value = getenv($name);
        if ($value === false || $value === '') {
            die("Falta configurar la variable de entorno: " . $name);
        }
        define($name, $value);
    }
} else {
    require_once('/etc/combox-config.php');
}

// Conexión a Supabase
$supa_conn = pg_connect("
    host=" . SUPABASE_DB_HOST . "
    port=" . SUPABASE_DB_PORT . "
    dbname=" . SUPABASE_DB_NAME . "
    user=" . SUPABASE_DB_USER . "
    password=" . SUPABASE_DB_PASSWORD . "
    sslmode=require
");

if (!$supa_conn) {
    error_log("Error de conexión a Supabase: " . pg_last_error());
    die("No se pudo conectar con Supabase.");
}

?>
