<?php
session_start(); //recupero la sesion creada en login.php
if (!isset($_SESSION['usuarioDAW212DBProyectoTema5'])) { //si la sesion no se ha recuperado, te manda a login.php para logearte
    header('location: login.php');
}

if (isset($_POST["detalle"])) {
    header('Location: detalle.php');
    exit;
}

if (isset($_POST["cerrar"])) {
    session_destroy();
    header('location: login.php');
}

//datos para la conexion de la base de datos
        include ("../config/confDB.php");
        require '../core/libreriaValidacion.php'; //Importamos la libreria de validacion
        
$entradaOK = true; //Creamos e inicializamos $entradaOK a true

try { // Bloque de código que puede tener excepciones en el objeto PDO
    $miBD = new PDO(HOST,USER, PASSWD);
                            // set the PDO error mode to exception
                                        //PDO::ERRMODE_EXCEPTION - Además de establecer el código de error, PDO lanzará una excepción PDOException y establecerá sus propiedades para luego poder reflejar el error y su información.
    $miBD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $consultaSQL = "SELECT T01_NumConexiones, T01_DescUsuario FROM T01_Usuario WHERE T01_CodUsuario=:codigo";
    $resultadoSQL = $miBD->prepare($consultaSQL); // prepara la consulta
    $resultadoSQL->bindParam(':codigo', $_SESSION['usuarioDAW212DBProyectoTema5']);
    $resultadoSQL->execute(); // ejecuto la consulta pasando los parametros del array de parametros

    $aObjetos = $resultadoSQL->fetchObject();
    $numConexiones = $aObjetos->T01_NumConexiones;
    $descUsuario = $aObjetos->T01_DescUsuario;
    
} catch (PDOException $mensajeError) {
    echo "<h4>Se ha producido un error. Disculpe las molestias</h4>";
} finally { // codigo que se ejecuta haya o no errores
    unset($miBD); // destruyo la variable 
}


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
                    <div id="cont" style="text-align: center;">
                            <!-- 
                            @author: Nerea Álvarez Justel
                              @since: 30/11/2020 
                              @description: LoginLogoff - PROGRAMA.
                             -->
                        <h3>Usuario aceptado</h3>
                        <h3>¡Bienvenido <?php echo $descUsuario; ?>!</h3>
                       <?php
                        if ($_SESSION['ultimaConexion212'] === null) {
                            echo "<h3>Esta es la primera vez que te conectas.</h3>";
                        } else {
                            ?>
                            <h3>Usted se ha conectado <?php echo $numConexiones . " veces"; ?></h3>
                            <h3>Se ha conectado por última vez el día <?php echo date('d/m/Y', $_SESSION['ultimaConexion212']); ?> a las <?php echo date('H:i:s', $_SESSION['ultimaConexion212']); ?></h3>
                        <?php } ?>
                        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                            <div class="obligatorio">
                                
                                <a href="detalle.php"><input type="button" id="botonAceptar" name="detalle" value="Detalle"></a>
                                <a href="login.php"><input type="button" id="botonCancelar" name="cerrar" value="Cerrar sesion"></a>
                            </div>
                        </form>
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
