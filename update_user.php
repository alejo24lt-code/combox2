
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

require('config/database.php');

// Detectar sistema operativo para cargar Storage
if (PHP_OS_FAMILY === 'Windows') {
    require_once('C:/xampp1/private/combox-storage.php');
} else {
    require_once('/etc/combox-storage.php');
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: list_users.php");
    exit;
}

$id = $_POST['id'] ?? '';
$firstname = trim($_POST['firstname'] ?? '');
$lastname = trim($_POST['lastname'] ?? '');
$email = trim($_POST['email'] ?? '');
$mobile_phone = trim($_POST['mobile_phone'] ?? '');

if (
    !ctype_digit((string)$id) ||
    $firstname === '' ||
    $lastname === '' ||
    $email === '' ||
    $mobile_phone === ''
) {
    die("Todos los campos son obligatorios.");
}

// Consultar fotografía actual.
$sql = "SELECT photo_url FROM users WHERE id = $1";
$res = pg_query_params($supa_conn, $sql, [$id]);

if (!$res) {
    die("Error al consultar el usuario.");
}

$user = pg_fetch_assoc($res);

if (!$user) {
    die("Usuario no encontrado.");
}

$photo_url = $user['photo_url'] ?? '';

// Subir fotografía nueva a Supabase Storage.
if (
    isset($_FILES['photo']) &&
    $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE
) {
    if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
        die("Error al recibir la fotografía.");
    }

    if ($_FILES['photo']['size'] > 5 * 1024 * 1024) {
        die("La fotografía supera el límite de 5 MB.");
    }

    $imagen = getimagesize($_FILES['photo']['tmp_name']);

    if ($imagen === false) {
        die("El archivo no es una imagen válida.");
    }

    $mime = $imagen['mime'];

    $formatos = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];

    if (!isset($formatos[$mime])) {
        die("Formato permitido: JPG, PNG o WEBP.");
    }

    $nombreFoto = bin2hex(random_bytes(12)) . '.' . $formatos[$mime];

    $objectPath = $id . '/' . $nombreFoto;

    $encodedPath = implode(
        '/',
        array_map('rawurlencode', explode('/', $objectPath))
    );

    $contenido = file_get_contents($_FILES['photo']['tmp_name']);

    if ($contenido === false) {
        die("No se pudo leer la fotografía.");
    }

    $urlSubida = SUPABASE_URL .
        '/storage/v1/object/' .
        SUPABASE_BUCKET . '/' .
        $encodedPath;

    $ch = curl_init($urlSubida);

    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $contenido,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . SUPABASE_SECRET_KEY,
            'apikey: ' . SUPABASE_SECRET_KEY,
            'Content-Type: ' . $mime,
            'x-upsert: true'
        ],
        CURLOPT_CONNECTTIMEOUT => 15,
        CURLOPT_TIMEOUT => 60
    ]);

    $respuesta = curl_exec($ch);
    $codigoHTTP = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $errorCurl = curl_error($ch);

    curl_close($ch);

    if ($respuesta === false || !in_array($codigoHTTP, [200, 201], true)) {
        die(
            "Error al subir la fotografía a Supabase. HTTP: " .
            $codigoHTTP . " " . htmlspecialchars($errorCurl ?: (string)$respuesta)
        );
    }

    // Guardar URL pública compartida.
    $photo_url = SUPABASE_URL .
        '/storage/v1/object/public/' .
        SUPABASE_BUCKET . '/' .
        $encodedPath;
}

// Actualizar datos del usuario.
$sql = "UPDATE users
        SET firstname = $1,
            lastname = $2,
            email = $3,
            mobile_phone = $4,
            photo_url = $5
        WHERE id = $6";

$res = pg_query_params(
    $supa_conn,
    $sql,
    [$firstname, $lastname, $email, $mobile_phone, $photo_url, $id]
);

if (!$res) {
    die("Error al actualizar el usuario: " .
        htmlspecialchars(pg_last_error($supa_conn)));
}

header("Location: list_users.php");
exit;
?>
