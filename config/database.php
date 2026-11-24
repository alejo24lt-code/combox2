<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Detectar sistema operativo
if (PHP_OS_FAMILY === 'Windows') {
    $config_path = 'C:/xampp1/private/combox-database.php';
} else {
    $config_path = '/etc/combox-database.php';
}

require_once($config_path);

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
