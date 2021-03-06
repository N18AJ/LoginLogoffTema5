<?php
session_start();
$entradaOK = true;
//datos para la conexion de la base de datos
    include ("../config/confDB.php");
    require '../core/libreriaValidacion.php'; //Importamos la libreria de validacion

if (!isset($_SESSION['usuarioDAW212DBProyectoTema5'])) { //Si no has pasado por el login, te redirige para allá
    header("Location: login.php");
}

if (isset($_POST["cancelar"])) {
    header('Location: editarPerfil.php');
    exit;
}

if (isset($_POST["editarPass"])) {
    header('Location: editarPassword.php');
    exit;
}

$aErrores = [
    'passVieja' => null,
    'passNueva' => null,
    'passNueva2' => null
];

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

if (isset($_POST['enviar'])) { //Si se ha pulsado enviar
    //La posición del array de errores recibe el mensaje de error si hubiera
    $aErrores['passVieja'] = validacionFormularios::comprobarAlfaNumerico($_POST['passVieja'], 25, 4, 1);  //maximo, mínimo y opcionalidad
    $aErrores['passNueva'] = validacionFormularios::comprobarAlfaNumerico($_POST['passNueva'], 25, 4, 1);  //maximo, mínimo y opcionalidad
    $aErrores['passNueva2'] = validacionFormularios::comprobarAlfaNumerico($_POST['passNueva2'], 25, 4, 1);  //maximo, mínimo y opcionalidad
    
    if (isset($_POST['passVieja']) && isset($_POST['passNueva']) && isset($_POST['passNueva2'])) {
        $passwordVieja = $_POST['passVieja'];
        $SQL = "SELECT T01_Password FROM T01_Usuario WHERE T01_CodUsuario = '" . $_SESSION['usuarioDAW212DBProyectoTema5'] . "';";
        $resultado = $miDB->query($SQL);
        $passUser = $resultado->fetchObject();
        
        if(hash('sha256', $_SESSION['usuarioDAW212DBProyectoTema5'] . $passwordVieja) !== $passUser->T01_Password){
            $aErrores['passVieja'] = "La contraseña antigua no coincide.";
        }
        
        if ($_POST['passNueva'] !== $_POST['passNueva2']) {
            $aErrores['passNueva2'] = "Las contraseñas no son iguales.";
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

    $sentenciaSQL = "UPDATE T01_Usuario SET T01_Password = SHA2(:pass,256) WHERE T01_CodUsuario = :codigo;";             
    $resultadoSQL = $miDB->prepare($sentenciaSQL);
    $resultadoSQL->execute(array(':codigo' => $_SESSION['usuarioDAW212DBProyectoTema5'], ':pass' => $_SESSION['usuarioDAW212DBProyectoTema5'] . $_POST['passNueva']));
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
            #botonEditar{
                margin:20px;
                text-align: center;
                color: #6100FF; 
                width: 120px; 
                height: 40px; 
                font-size: 10pt;
                border-radius: 10px;
                border: 2px solid #6100FF;
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
                          @since: 03/12/2020 
                          @description: LoginLogoff - EDITAR PERFIL.
                         -->
                         
                        <form style="text-align: center;" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <fieldset>
                               <div class="box">
                                    <div class="obligatorio">
                                        <label for="passVieja">Contraseña actual: </label>
                                        <input type="password" id="passVieja" name="passVieja" value="<?php if ($aErrores['passVieja'] == NULL && isset($_POST['passVieja'])) {echo $_POST['passVieja'];} ?>"><br>
                                        <?php if ($aErrores['passVieja'] != NULL) { ?>
                                            <?php echo "<b>" . $aErrores['passVieja'] . "</b>";?>
                                        <?php } ?>                
                                    </div>
                                    <br/>
                                    <div class="obligatorio">
                                        <label for="passNueva">Nueva Contraseña: </label>
                                        <input type="password" id="passNueva" name="passNueva" value="<?php if ($aErrores['passNueva'] == NULL && isset($_POST['passNueva'])) {echo $_POST['passNueva'];} ?>"><br>
                                        <?php if ($aErrores['passNueva'] != NULL) { ?>
                                            <?php echo "<b>" . $aErrores['passNueva'] . "</b>";?>
                                        <?php } ?>                
                                    </div>
                                    <br/>
                                    <div class="obligatorio">
                                        <label for="passNueva2">Confirme Nueva Contraseña: </label> 
                                        <input type="password" id="passNueva2" name="passNueva2" value="<?php if ($aErrores['passNueva2'] == NULL && isset($_POST['passNueva2'])) {echo $_POST['passNueva2'];} ?>"><br>
                                        <?php if ($aErrores['passNueva2'] != NULL) { ?>
                                             <?php echo "<b>" . $aErrores['passNueva2'] . "</b>";?>
                                        <?php } ?>                
                                    </div>
                                    <br/>
                                    <div class="obligatorio">
                                        <input type="submit" name="enviar" id="botonAceptar" value="Aceptar">
                                        <input type="submit" name="cancelar" id="botonCancelar" value="Cancelar">
                                    </div>
                                </div>        
                            </fieldset>
                        </form>
                    <?php } ?> 
                   </div> 
                </article>     
        </section>
        </div>

        <!-- Footer -->
        <footer id="footer">
            <a href="../../../../index.html"><div class="copyright">&copy; Nerea Álvarez Justel</div></a>
        </footer>
    </body>
</html>

        
      
