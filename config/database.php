<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


// CONEXIÓN LOCAL

$local_host = "localhost";
$local_port = "5432";
$local_user = "postgres";
$local_dbname = "combox";
$local_password = "alejo24lt";

$local_conn = pg_connect("
    host=$local_host
    port=$local_port
    dbname=$local_dbname
    user=$local_user
    password=$local_password
");


// CONEXIÓN SUPABASE

$supa_host = "aws-0-us-west-2.pooler.supabase.com";
$supa_port = "6543";
$supa_user = "postgres.rzybdadcqjmlonauahdr";
$supa_dbname = "postgres";
$supa_password = "unicesmag@@";

$supa_conn = pg_connect("
    host=$supa_host
    port=$supa_port
    dbname=$supa_dbname
    user=$supa_user
    password=$supa_password
");


// VERIFICAR CONEXIONES

if (!$local_conn) {
    die("Local database connection failed");
} else {
    echo "Local Connected success<br>";
}

if (!$supa_conn) {
    die("Supabase connection failed");
} else {
    echo "Supabase Connected success<br>";
}

?>