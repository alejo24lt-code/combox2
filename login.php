
<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.html");
    exit;
}

if (empty($_POST['email']) || empty($_POST['password'])) {
    exit("Debes ingresar correo y contraseña.");
}

// Conectar con la base de datos
require('config/supabase.php');

// Recibir los datos del formulario
$email = $_POST['email'];
$password = $_POST['password'];



// Buscar el usuario por su correo en Supabase
$sql = "SELECT email, password FROM users WHERE email = $1";

// Ejecutar la consulta de forma segura
$result = pg_query_params($supa_conn, $sql, [$email]);




if ($result && pg_num_rows($result) === 1) {

    $user = pg_fetch_assoc($result);

    if (password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['email'] = $user['email'];

        header("Location: index.html");
        exit;
    }
}

echo "Correo o contraseña incorrectos";
