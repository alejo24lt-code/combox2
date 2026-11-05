
<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.html");
    exit;
}

if (empty($_POST['email']) || empty($_POST['password'])) {
    header("Location: login.html?error=1");
    exit;
}

session_start();
echo "Estado de sesión: " . session_status();

echo "<pre>";
print_r($_SESSION);
echo "</pre>";
// Conectar con Supabase
require('config/supabase.php');

// Recibir los datos del formulario
$email = $_POST['email'];
$password = $_POST['password'];

// Buscar el usuario// Buscar el usuario por su correo
$sql = "SELECT id, firstname, lastname, email, password FROM users WHERE email = $1";
$result = pg_query_params($supa_conn, $sql, [$email]);

if ($result && pg_num_rows($result) === 1) {

    $user = pg_fetch_assoc($result);
  

    // Comprobar contraseña
    $valid_password = password_verify($password, $user['password']);

    // Comprobar contraseña antigua MD5
    if (!$valid_password && md5($password) === $user['password']) {
        $valid_password = true;

        $new_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql_update = "UPDATE users SET password = $1 WHERE id = $2";
        pg_query_params($supa_conn, $sql_update, [$new_hash, $user['id']]);
    }

    if ($valid_password) {

    session_regenerate_id(true);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['firstname'] = trim($user['firstname']);
    $_SESSION['lastname'] = trim($user['lastname']);


    session_write_close();

header("Location: index.php");
exit;



} else {
        echo "<script>
            alert('La contraseña es incorrecta');
            window.location.href = 'login.html';
        </script>";
        exit;
    }

} else {

    echo "<script>
        alert('El correo electrónico no está registrado');
        window.location.href = 'login.html';
    </script>";
    exit;
}
// Si los datos son incorrectos, volver al login
echo "<script>
    alert('Correo o contraseña incorrectos');
    window.location.href = 'login.html';
</script>";
exit;