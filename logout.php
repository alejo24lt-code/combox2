<?php
//toda esta parte es para cerrar la sesion del usuario, basicamente destruimos la sesion y redirigimos al login
session_start();

$_SESSION = array();

session_destroy();

header("Location: login.html");
exit;