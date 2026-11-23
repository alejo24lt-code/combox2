<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

require('config/database.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: list_users.php");
    exit;
}

$id = $_POST['id'] ?? '';
$action = $_POST['action'] ?? '';

if (!ctype_digit((string)$id)) {
    die("ID de usuario no válido.");
}

if ($action === 'delete') {

    $sql = "DELETE FROM users WHERE id = $1";
    $result = pg_query_params($supa_conn, $sql, [$id]);

    if ($result && pg_affected_rows($result) > 0) {
        $message = "Usuario eliminado correctamente.";
    } else {
        $message = "No se pudo eliminar el usuario.";
    }

} elseif ($action === 'deactivate') {

    $sql = "UPDATE users SET status = false WHERE id = $1";
    $result = pg_query_params($supa_conn, $sql, [$id]);

    if ($result && pg_affected_rows($result) > 0) {
        $message = "Usuario desactivado correctamente.";
    } else {
        $message = "No se pudo desactivar el usuario.";
    }

} elseif ($action === 'activate') {

    $sql = "UPDATE users SET status = true WHERE id = $1";
    $result = pg_query_params($supa_conn, $sql, [$id]);

    if ($result && pg_affected_rows($result) > 0) {
        $message = "Usuario activado correctamente.";
    } else {
        $message = "No se pudo activar el usuario.";
    }

} else {
    header("Location: list_users.php");
    exit;
}

echo "<script>
    alert(" . json_encode($message) . ");
    window.location.href = 'list_users.php';
</script>";

exit;
?>

