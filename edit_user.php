
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

require('config/database.php');

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    die("ID de usuario no válido.");
}

$id = $_GET['id'];

$sql = "SELECT id, firstname, lastname, mobile_phone, email, photo_url
        FROM users
        WHERE id = $1";

$res = pg_query_params($supa_conn, $sql, [$id]);

if (!$res) {
    die("Error al consultar el usuario.");
}

$user = pg_fetch_assoc($res);

if (!$user) {
    die("Usuario no encontrado.");
}

$foto = $user['photo_url'] ?? '';
$nombreFoto = basename($foto);
$rutaFoto = 'photos/' . $nombreFoto;

if (
    empty($foto) ||
    $nombreFoto === '.' ||
    $nombreFoto === '..' ||
    !is_file(__DIR__ . '/' . $rutaFoto)
) {
    $rutaFoto = 'photos/usuario.png';
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar usuario - COMBOX2</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid mt-4">

    <h1 class="h3 mb-4 text-gray-800">Editar usuario</h1>

    <div class="card shadow mb-4">

        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Información del usuario
            </h6>
        </div>

        <div class="card-body">

            <form action="update_user.php" method="POST" enctype="multipart/form-data">

                <input type="hidden" name="id"
                       value="<?= htmlspecialchars($user['id']) ?>">

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="firstname"
                           class="form-control"
                           value="<?= htmlspecialchars($user['firstname']) ?>"
                           required>
                </div>

                <div class="form-group">
                    <label>Apellido</label>
                    <input type="text" name="lastname"
                           class="form-control"
                           value="<?= htmlspecialchars($user['lastname']) ?>"
                           required>
                </div>

                <div class="form-group">
                    <label>Correo electrónico</label>
                    <input type="email" name="email"
                           class="form-control"
                           value="<?= htmlspecialchars($user['email']) ?>"
                           required>
                </div>

                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="mobile_phone"
                           class="form-control"
                           value="<?= htmlspecialchars($user['mobile_phone']) ?>"
                           required>
                </div>

                <div class="form-group">
                    <label>Fotografía actual</label>
                    <br>

                    <img src="<?= htmlspecialchars($rutaFoto) ?>"
                         alt="Foto del usuario"
                         width="120"
                         height="120"
                         style="object-fit: cover; border-radius: 50%;">

                </div>

                <div class="form-group">
                    <label>Actualizar fotografía</label>
                    <input type="file"
                           name="photo"
                           class="form-control-file"
                           accept=".jpg,.jpeg,.png">
                    <small class="form-text text-muted">
                        Formatos permitidos: JPG, JPEG y PNG. Máximo 5 MB.
                    </small>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar cambios
                </button>

                <a href="list_users.php" class="btn btn-secondary">
                    Cancelar
                </a>

            </form>

        </div>
    </div>

</div>

</body>
</html>
