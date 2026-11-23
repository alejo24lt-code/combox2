<?php
session_start();

echo "<pre>";
echo "ID de sesión: " . session_id() . "\n";
echo "Estado: " . session_status() . "\n";
print_r($_SESSION);
echo "</pre>";
?>