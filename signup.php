
<?php

require('config/database.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: signup.html");
    exit;
}

if (!$supa_conn) {
    die("No se pudo conectar con Supabase.");
}

$f_name = trim($_POST['firstname'] ?? '');
$l_name = trim($_POST['lastname'] ?? '');
$e_mail = trim($_POST['email'] ?? '');
$p_assword = $_POST['password'] ?? '';
$c_password = $_POST['confirm_password'] ?? '';
$m_phone = trim($_POST['phone'] ?? '');

if ($p_assword !== $c_password) {
    echo "<script>alert('Las contraseñas no coinciden'); window.history.back();</script>";
    exit;
}

$pass_enc = password_hash($p_assword, PASSWORD_DEFAULT);

// Validar correo en Supabase
$sql = "SELECT id FROM users WHERE email = $1";
$result = pg_query_params($supa_conn, $sql, array($e_mail));

if (!$result) {
    die("Error al validar el correo.");
}

if (pg_num_rows($result) > 0) {
    echo "<script>alert('El correo electrónico ya está registrado'); window.history.back();</script>";
    exit;
}

// Validar teléfono en Supabase
if (!empty($m_phone)) {
    $sql = "SELECT id FROM users WHERE mobile_phone = $1";
    $result = pg_query_params($supa_conn, $sql, array($m_phone));

    if (!$result) {
        die("Error al validar el teléfono.");
    }

    if (pg_num_rows($result) > 0) {
        echo "<script>alert('El número de teléfono ya está registrado'); window.history.back();</script>";
        exit;
    }
}

// Registrar usuario únicamente en Supabase
$sql = "INSERT INTO users (
    firstname,
    lastname,
    mobile_phone,
    email,
    password
) VALUES ($1, $2, $3, $4, $5)";

$result = pg_query_params(
    $supa_conn,
    $sql,
    array($f_name, $l_name, $m_phone, $e_mail, $pass_enc)
);

if ($result) {
    header("Location: login.html");
    exit;
}

error_log("Error al registrar usuario: " . pg_last_error($supa_conn));

echo "<script>
    alert('No se pudo registrar el usuario.');
    window.history.back();
</script>";

exit;
?>
