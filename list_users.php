
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

require('config/database.php');

$sql = "SELECT
            id,
            firstname,
            lastname,
            mobile_phone,
            email,
            photo_url,
            status
        FROM users
        ORDER BY lastname ASC";

$res = pg_query($supa_conn, $sql);

if (!$res) {
    die("Error al consultar usuarios: " . htmlspecialchars(pg_last_error($supa_conn)));
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Listado de usuarios - COMBOX2</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid mt-4">

    <h1 class="h3 mb-4 text-gray-800">
        Listado de usuarios
    </h1>

    <div class="card shadow mb-4">

        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Usuarios registrados
            </h6>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered" width="100%" cellspacing="0">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Foto</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Teléfono</th>
                            <th>Correo electrónico</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php while ($row = pg_fetch_assoc($res)):

                        $foto = $row['photo_url'] ?? '';

                            $urlStorage = 'https://rzybdadcqjmlonauahdr.supabase.co/storage/v1/object/public/profile-photos/';

                            if (
                                !empty($foto) &&
                                strpos($foto, $urlStorage) === 0
                            ) {
                                // Fotografía almacenada en Supabase Storage.
                                $rutaFoto = $foto;

                            } else {
                                // Compatibilidad con fotografías locales antiguas.
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
                            }

                        $estado = $row['status'] === 't' ? 'Active' : 'Inactive';

                    ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars($row['id']) ?>
                            </td>

                            <td class="text-center">
                                <img
                                    src="<?= htmlspecialchars($rutaFoto) ?>"
                                    alt="Foto de usuario"
                                    width="50"
                                    height="50"
                                    style="object-fit: cover; border-radius: 50%;"
                                >
                            </td>

                            <td>
                                <?= htmlspecialchars($row['firstname']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['lastname']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['mobile_phone']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['email']) ?>
                            </td>

                            <td>
                                <?php if ($estado === 'Active'): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Inactive</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">

                                <a href="view_user.php?id=<?= urlencode($row['id']) ?>"
                                   class="text-info mr-2"
                                   title="Ver usuario">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="edit_user.php?id=<?= urlencode($row['id']) ?>"
                                   class="text-primary mr-2"
                                   title="Editar usuario">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a href="user_action.php?id=<?= urlencode($row['id']) ?>"
                                   class="text-danger"
                                   title="Eliminar o desactivar usuario">
                                    <i class="fas fa-trash"></i>
                                </a>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    <a href="index.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Volver
    </a>

</div>

</body>
</html>

