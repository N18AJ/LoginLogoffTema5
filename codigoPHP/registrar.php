<?php
//datos para la conexion de la base de datos
    include ("../config/confDB.php");
    require '../core/libreriaValidacion.php'; //Importamos la libreria de validacion

$entradaOK = true; //Inicializamos una variable que nos ayudara a controlar si todo esta correcto
session_start();

try {
    
    //objeto PDO = mi base de datos
                $miDB = new PDO(HOST,USER, PASSWD);
					// set the PDO error mode to exception
                                                    //PDO::ERRMODE_EXCEPTION - Además de establecer el código de error, PDO lanzará una excepción PDOException y establecerá sus propiedades para luego poder reflejar el error y su información.
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        
} catch (PDOException $mensajeError) { //Cuando se produce una excepcion se corta el programa y salta la excepción con el mensaje de error
    echo "<h3>Mensaje de ERROR</h3>";
    echo "Error: " . $mensajeError->getMessage() . "<br>";
    echo "Código de error: " . $mensajeError->getCode();
}

$aErrores = [
    'nombre' => null,
    'descripcion' => null,
    'pass' => null,
    'pass2' => null
];

if (isset($_POST['enviar'])) { //Si se ha pulsado enviar
    //La posición del array de errores recibe el mensaje de error si hubiera
    $aErrores['nombre'] = validacionFormularios::comprobarAlfabetico($_POST['nombre'], 15, 1, 1);  //maximo, mínimo y opcionalidad
    $aErrores['descripcion'] = validacionFormularios::comprobarAlfabetico($_POST['descripcion'], 255, 1, 1);  //maximo, mínimo y opcionalidad
    $aErrores['pass'] = validacionFormularios::comprobarAlfaNumerico($_POST['pass'], 25, 4, 1); //maximo, mínimo y opcionalidad
    $aErrores['pass2'] = validacionFormularios::comprobarAlfaNumerico($_POST['pass2'], 25, 4, 1); //maximo, mínimo y opcionalidad
    
    if (isset($_POST['nombre']) && isset($_POST['pass']) && isset($_POST['pass2'])) {
        if ($_POST['pass'] === $_POST['pass2']) {
            $codUsuario = $_POST['nombre'];
            $password = $_POST['pass'];
            $consultaSQL1 = "SELECT * FROM T01_Usuario WHERE T01_CodUsuario LIKE '$codUsuario'";
            $resultadoSQL1 = $miDB->query($consultaSQL1);
            if ($resultadoSQL1->rowCount() === 1) {
                $aErrores['nombre'] = "Nombre de usuario ya existente";
            }
        }else{
            $aErrores['pass2'] = "Las contraseñas no coinciden";
        }
    }
    
    foreach ($aErrores as $campo => $error) { //Recorre el array en busca de mensajes de error
        if ($error != null) { //Si lo encuentra vacia el campo y cambia la condiccion
            $entradaOK = false; //Cambia la condiccion de la variable
        }
    }
        
} else {
    $entradaOK = false; //Cambiamos el valor de la variable porque no se ha pulsado el botón
}

if ($entradaOK) {
    
        $consultaSQL2 = "INSERT INTO T01_Usuario(T01_CodUsuario, T01_DescUsuario, T01_Password) VALUES (:codigo, :descripcion, SHA2(:pass,256));";
        $resultadoSQL2 = $miDB->prepare($consultaSQL2);
        $resultadoSQL2->execute(array(':codigo' => $_POST['nombre'], ':descripcion' => $_POST['descripcion'], ':pass' => $_POST['nombre'] . $_POST['pass']));
        
        $fechaSQL = "UPDATE T01_Usuario SET T01_FechaHoraUltimaConexion = " . time() . " WHERE T01_CodUsuario = :codigo;";
        $actualizarFechaSQL = $miDB->prepare($fechaSQL);
        $actualizarFechaSQL->execute(array(':codigo' => $_POST['nombre']));
            
        $conexionesSQL = "UPDATE T01_Usuario SET T01_NumConexiones = T01_NumConexiones + 1 WHERE T01_CodUsuario = :codigo;";
        $actualizarConexionesSQL = $miDB->prepare($conexionesSQL);
        $actualizarConexionesSQL->execute(array(':codigo' => $_POST['nombre']));
        
        $_SESSION['usuarioDAW212DBProyectoTema5'] = $_POST['nombre'];
        $_SESSION['ultimaConexion212'] = null;
        header("Location: programa.php");
    
} else {
 ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <meta name="author" content="Nerea Álvarez Justel">
        <meta name="robots" content="index, follow" />
        <title>DAW. Nerea Álvarez Justel</title>       
<!-- CSS -->
        <link href="../webroot/css/estilos.css" rel="stylesheet" type="text/css"/>
<!-- Favicon -->
        <link rel="icon" href="../../../../../favicon.png" type="x-icon">
<!-- Tipografía -->
        <link href="https://fonts.googleapis.com/css?family=ZCOOL+KuaiLe" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Caveat&display=swap" rel="stylesheet">
        <style>
            input{
                width: 185px;
                height: 30px;
                text-align: center;
                margin-bottom: 15px;
                border: 1px solid #8d82c4;
                border-radius:5px;
            }
            #botonAceptar{
                margin:20px;
                text-align: center;
                color: #18B618; 
                width: 120px; 
                height: 40px; 
                font-size: 10pt;
                border-radius: 10px;
                border: 2px solid #18B618;
            }
            #botonCancelar{
                margin:20px;
                text-align: center;
                color: #E72727; 
                width: 120px; 
                height: 40px; 
                font-size: 10pt;
                border-radius: 10px;
                border: 2px solid #E72727;
            }
        </style>
    </head>

    <body> 
        <!-- Header -->
        <header id="header">
            <a href="../../../../doc/cv.pdf" target="_blank"><img src="../../webroot/media/images/cv2.png" alt="CV" width="55" class="icono_link"/></a>
            <a href="http://daw212.ieslossauces.es/"><img src="../../webroot/media/images/logo2.png" alt="Logo" width="150" class="icono_logo"/></a>
            <a href="https://github.com/N18AJ/LoginLogoffTema5" target="_blank"><img src="../../webroot/media/images/git2.png" alt="GitHub" width="65" class="icono_git"/></a>
        </header>


        <!-- Main -->
        <div id="main">

            <!-- Tiles -->
            <section class="tiles">
                <article>
                    <header class="major">
                        <h3>LoginLogoff Tema 5</h3>
                    </header>
                    <div id="cont">
                        <!-- 
                        @author: Nerea Álvarez Justel
                          @since: 30/11/2020 
                          @description: LoginLogoff - LOGIN.
                         -->
                        
                         
                        <form style="text-align: center;" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <fieldset>
                                <div class="obligatorio">
                                    <label>Nombre de Usuario: </label>
                                    <input type="text" id="nombre" name="nombre" value="<?php if ($aErrores['nombre'] == NULL && isset($_POST['nombre'])) { echo $_POST['nombre'];} ?>"><br>
                                    <?php if ($aErrores['nombre'] != NULL) { ?>
                                        <div class="error">
                                            <?php echo "<b>" . $aErrores['nombre'] . "</b>"; //Mensaje de error que tiene el array aErrores   ?>
                                        </div>   
                                    <?php } ?>                
                                </div>
                                <br>
                                <div class="obligatorio">
                                    <label>Descripción de Usuario: </label>
                                    <input type="text" id="descripcion" name="descripcion"value="<?php if ($aErrores['descripcion'] == NULL && isset($_POST['descripcion'])) { echo $_POST['descripcion'];} ?>"><br>
                                    <?php if ($aErrores['descripcion'] != NULL) { ?>
                                        <div class="error">
                                            <?php echo "<b>" . $aErrores['descripcion'] . "</b>"; //Mensaje de error que tiene el array aErrores   ?>
                                        </div>   
                                    <?php } ?>                
                                </div>
                                <br>
                                <div class="obligatorio">
                                    <label>Introduzca Contraseña: </label> 
                                    <input type="password" id="pass" name="pass" value="<?php if ($aErrores['pass'] == NULL && isset($_POST['pass'])) { echo $_POST['pass'];} ?>"><br>
                                        <?php if ($aErrores['pass'] != NULL) { ?>
                                        <div class="error">
                                        <?php echo "<b>" . $aErrores['pass'] . "</b>"; //Mensaje de error que tiene el array aErrores   ?>
                                        </div>   
                                <?php } ?>                
                                </div>
                                <br>
                                <div class="obligatorio">
                                    <label>Confirmar Contraseña: </label> 
                                    <input type="password" id="pass2" name="pass2" value="<?php if ($aErrores['pass2'] == NULL && isset($_POST['pass2'])) { echo $_POST['pass2'];} ?>"><br> 
                                    <?php if ($aErrores['pass2'] != NULL) { ?>
                                        <div class="error">
                                        <?php echo "<b>" . $aErrores['pass2'] . "</b>"; //Mensaje de error que tiene el array aErrores   ?>
                                        </div>   
                                <?php } ?>    
                                </div>
                                <br>
                                <div class="obligatorio">
                                    <input type="submit" name="enviar" id="botonAceptar" value="ACEPTAR">
                                    <a href="login.php"><input type="button" name="cancelar" id="botonCancelar" value="CANCELAR"></a>
                                </div>

                            </fieldset>
                        </form>
                   </div> 
                </article>     
        </section>
        </div>
<?php } ?> 
        <!-- Footer -->
        <footer id="footer">
            <a href="../../../../index.html"><div class="copyright">&copy; Nerea Álvarez Justel</div></a>
        </footer>
    </body>
</html>   