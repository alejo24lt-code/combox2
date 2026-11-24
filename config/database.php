<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('SUPABASE_DB_HOST', 'aws-0-us-west-2.pooler.supabase.com');
define('SUPABASE_DB_PORT', '6543');
define('SUPABASE_DB_USER', 'postgres.rzybdadcqjmlonauahdr');
define('SUPABASE_DB_NAME', 'postgres');
define('SUPABASE_DB_PASSWORD', 'unicesmag@@');
define('SUPABASE_URL', 'https://rzybdadcqjmlonauahdr.supabase.co');
define('SUPABASE_PUBLISHABLE_KEY', 'sb_publishable__KOXhXi02vNB7b5lKB59iQ_A3CRwclQ');
define('SUPABASE_BUCKET', 'profile-photos');

$supa_conn = pg_connect(
    "host=" . SUPABASE_DB_HOST .
    " port=" . SUPABASE_DB_PORT .
    " dbname=" . SUPABASE_DB_NAME .
    " user=" . SUPABASE_DB_USER .
    " password=" . SUPABASE_DB_PASSWORD .
    " sslmode=require"
);

if (!$supa_conn) {
    die("No se pudo conectar con Supabase.");
}
