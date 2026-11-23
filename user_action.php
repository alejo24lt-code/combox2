<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

require('config/database.php');

$id = $_GET['id'] ?? '';

if (!ctype_digit((string)$id)) {
    die("ID de usuario no válido.");
}

$sql = "SELECT id, firstname, lastname, email, status
        FROM users
        WHERE id = $1";

$result = pg_query_params($supa_conn, $sql, [$id]);

if (!$result || pg_num_rows($result) === 0) {
    die("Usuario no encontrado.");
}

$user = pg_fetch_assoc($result);
$activo = $user['status'] === 't';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acción sobre usuario - COMBOX2</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header">
            <h5 class="font-weight-bold text-primary">
                ¿Qué deseas hacer?
            </h5>
        </div>

        <div class="card-body">

            <p>
                Usuario:
                <strong>
                    <?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?>
                </strong>
            </p>

            <p>
                Estado actual:
                <strong><?= $activo ? 'Active' : 'Inactive' ?></strong>
            </p>

            <form action="process_user_action.php" method="POST">

                <input type="hidden" name="id"
                       value="<?= htmlspecialchars($user['id']) ?>">

                <button class="btn btn-danger btn-block mb-3"
                        type="submit"
                        name="action"
                        value="delete"
                        onclick="return confirm('¿Seguro que deseas eliminar definitivamente este usuario?');">
                    <i class="fas fa-trash"></i>
                    Eliminar definitivamente
                </button>

                <?php if ($activo): ?>
                    <button class="btn btn-warning btn-block mb-3"
                            type="submit"
                            name="action"
                            value="deactivate"
                            onclick="return confirm('¿Seguro que deseas desactivar este usuario?');">
                        <i class="fas fa-user-slash"></i>
                        Desactivar usuario
                    </button>
                <?php else: ?>
                    <button class="btn btn-success btn-block mb-3"
                            type="submit"
                            name="action"
                            value="activate"
                            onclick="return confirm('¿Seguro que deseas activar este usuario?');">
                        <i class="fas fa-user-check"></i>
                        Activar usuario
                    </button>
                <?php endif; ?>

            </form>

            <a href="list_users.php" class="btn btn-secondary btn-block">
                Cancelar
            </a>

        </div>
    </div>
</div>

</body>
</html>