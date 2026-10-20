<?php
    // Get data from the form (cliente)

    //get data base conecction 
    require('config/database.php');

    $f_name = $_POST['firstname'];
    $l_name = $_POST['lastname'];
    $e_mail = $_POST['email'];
    $p_assword = $_POST['password'];
    $c_password = $_POST['confirm_password'];
    $m_phone = $_POST['phone'];
    if ($p_assword !== $c_password) {
    echo "<script>alert('Las contraseñas no coinciden'); window.history.back();</script>";
    exit;
}

    //vamos a encriptar la contraseña
    $pass_enc = password_hash($p_assword, PASSWORD_DEFAULT);

    $sql_validate_email = "SELECT * FROM users WHERE email = '$e_mail'";
    $res_validate_email = pg_query($local_conn, $sql_validate_email);
    if (pg_num_rows($res_validate_email) > 0) {
        echo "<script>alert('El correo electrónico ya está registrado'); window.history.back();</script>";
        exit;
    }
    //validar si el telefono ya esta registrado en la base de datos local y en la base de datos supabase, si es asi imprimimos un mensaje de error y salimos del script
    if (!empty($m_phone)) {
        $sql_validate_phone_local = "SELECT * FROM users WHERE mobile_phone = '$m_phone'";
        $res_validate_phone_local = pg_query($local_conn, $sql_validate_phone_local);
        if (pg_num_rows($res_validate_phone_local) > 0) {
            echo "<script>alert('El número de teléfono ya está registrado en la base de datos local'); window.history.back();</script>";
            exit;
        }

        $sql_validate_phone_supa = "SELECT * FROM users WHERE mobile_phone = '$m_phone'";
        $res_validate_phone_supa = pg_query($supa_conn, $sql_validate_phone_supa);
        if (pg_num_rows($res_validate_phone_supa) > 0) {
            echo "<script>alert('El número de teléfono ya está registrado en la base de datos Supabase'); window.history.back();</script>";
            exit;
        }
    }
    
    
    //prepare query es basicamente preparar la consulta para insertar los datos en la base de datos, es como un borrador de la consulta, luego se ejecuta con pg_query_params
    $sql = "INSERT INTO users (
        firstname, lastname, mobile_phone, email, password) 
        VALUES ('$f_name', '$l_name', '$m_phone', '$e_mail', '$pass_enc')";
    //el tunder usamos en caso de que no tengamos frontend cuanod no tengamos interfaz grafica, es basicamente un mensaje que nos dice que la consulta se ejecuto correctamente o no, si no se ejecuto correctamente nos dice el error que ocurrio
    	$local_res = pg_query($local_conn, $sql);

	$supa_res = pg_query($supa_conn, $sql);


    // aqui verificamos si la consulta se ejecuto correctamente en la base de datos local y en la base de datos supabase, si es asi imprimimos un mensaje de exito, si no imprimimos un mensaje de error
    
if ($local_res) {   
    echo "User has been created successfully into local database<br>";
} else {
    echo "User has not been created in local database<br>";
}

if ($supa_res) {
    echo "User has been created successfully into Supabase database<br>";
} else {
    echo "User has not been created in Supabase database<br>";
}

if ($local_res && $supa_res) {
    header("Location: login.html"); 
    exit;
}

?>
