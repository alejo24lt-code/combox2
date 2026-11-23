
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit;
}

require('config/database.php');
$id = $_GET['id'] ?? '';

if (!ctype_digit((string)$id)) {
    die("ID de usuario no válido.");
}

$sql = "SELECT id, firstname, lastname, mobile_phone,
               email, address, birth_date, status, photo_url
        FROM users
        WHERE id = $1";

$result = pg_query_params($supa_conn, $sql, [$id]);

if (!$result || pg_num_rows($result) === 0) {
    die("Usuario no encontrado.");
}

$user = pg_fetch_assoc($result);

// Resolver la fotografía
$foto = $user['photo_url'] ?? '';

$storage_prefix = 'https://rzybdadcqjmlonauahdr.supabase.co/storage/v1/object/public/profile-photos/';

if (!empty($foto) && strpos($foto, $storage_prefix) === 0) {
    $rutaFoto = $foto;
} else {
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

$estado = $user['status'] === 't' ? 'Active' : 'Inactive';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar usuario</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px #ccc;
        }

        h2 {
            text-align: center;
        }

        .foto {
            display: block;
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            margin: 15px auto 25px;
        }

        .dato {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .boton {
            display: block;
            width: fit-content;
            margin: 25px auto 0;
            padding: 10px 20px;
            background: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Información del usuario</h2>

        <img class="foto"
             src="<?php echo htmlspecialchars($rutaFoto); ?>"
             alt="Fotografía del usuario">

        <div class="dato">
            <strong>ID:</strong>
            <?php echo htmlspecialchars($user['id']); ?>
        </div>

        <div class="dato">
            <strong>Nombre:</strong>
            <?php echo htmlspecialchars($user['firstname']); ?>
        </div>

        <div class="dato">
            <strong>Apellido:</strong>
            <?php echo htmlspecialchars($user['lastname']); ?>
        </div>

        <div class="dato">
            <strong>Celular:</strong>
            <?php echo htmlspecialchars($user['mobile_phone']); ?>
        </div>

        <div class="dato">
            <strong>Correo:</strong>
            <?php echo htmlspecialchars($user['email']); ?>
        </div>

        <div class="dato">
            <strong>Dirección:</strong>
            <?php echo htmlspecialchars($user['address'] ?? 'No registrada'); ?>
        </div>

        <div class="dato">
            <strong>Fecha de nacimiento:</strong>
            <?php echo htmlspecialchars($user['birth_date'] ?? 'No registrada'); ?>
        </div>

        <div class="dato">
            <strong>Estado:</strong>
            <?php echo htmlspecialchars($estado); ?>
        </div>

        <a class="boton" href="list_users.php">Volver al listado</a>
    </div>
</body>
</html>
