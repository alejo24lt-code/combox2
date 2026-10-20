<?php

$supa_host = "aws-0-us-west-2.pooler.supabase.com";
$supa_port = "6543";
$supa_user = "postgres.rzybdadcqjmlonauahdr";
$supa_dbname = "postgres";
$supa_password = "unicesmag@@";

$supa_conn = pg_connect(
    "host=$supa_host " .
    "port=$supa_port " .
    "dbname=$supa_dbname " .
    "user=$supa_user " .
    "password=$supa_password " .
    "sslmode=require"
);

if (!$supa_conn) {
    exit("No se pudo conectar con Supabase.");
}
