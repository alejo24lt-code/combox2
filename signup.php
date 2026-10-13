<?php
    // Get data from the form (cliente)

    //get data base conecction 
    require('../config/database.php');

    $f_name = $_POST['firstname'];
    $l_name = $_POST['lastname'];
    $e_mail = $_POST['email'];
    $p_assword = $_POST['password'];
    $m_phone = $_POST['phone'];
    //vamos a encriptar la contraseña
    $pass_enc = md5($p_assword);


    //prepare query es basicamente preparar la consulta para insertar los datos en la base de datos, es como un borrador de la consulta, luego se ejecuta con pg_query_params
    $sql = "INSERT INTO users (
        firstname, lastname, mobile_phone, email, password) 
        VALUES ('$f_name', '$l_name', '$e_mail', '$pass_enc' '$m_phone',)";
    //el tunder usamos en caso de que no tengamos frontend cuanod no tengamos interfaz grafica, es basicamente un mensaje que nos dice que la consulta se ejecuto correctamente o no, si no se ejecuto correctamente nos dice el error que ocurrio
    	$local_res = pg_query($local_conn, $sql);

	$supa_res = pg_query($supa_conn, $sql);


    // aqui verificamos si la consulta se ejecuto correctamente en la base de datos local y en la base de datos supabase, si es asi imprimimos un mensaje de exito, si no imprimimos un mensaje de error
    if($local_res) {
        echo "User has been created sucessfully into local database<br>";
    } else {
        echo "User has not been created to local database !! <br>" ;
    }

    // aqui verificamos si la consulta se ejecuto correctamente en la base de datos supabase, si es asi imprimimos un mensaje de exito, si no imprimimos un mensaje de error

    if($supa_res) {
        echo "User has been created sucessfully into supabase d atabase<br>";
    } else {
        echo "User has not been created to supabase database !! <br>" ;
    }

    echo "<script>alert('User has been created successfylly:::')</script>";
    header('refresh:0;url=register.html');

?>
