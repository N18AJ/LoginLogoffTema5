<?php
    session_start();        
    $entradaOK = true; //Inicializamos una variable que nos ayudara a controlar si todo esta correcto

   //datos para la conexion de la base de datos
    include ("../config/confDB.php");
    require '../core/libreriaValidacion.php'; //Importamos la libreria de validacion

    if (!isset($_SESSION['usuarioDAW212DBProyectoTema5'])) { //Si no has pasado por el login, te redirige para allá
        header("Location: login.php");
    }

    if (isset($_POST["cancelar"])) {
        header('Location: programa.php');
        exit;
    }

    if (isset($_POST["editarPass"])) {
        header('Location: editarPassword.php');
        exit;
    }
        
    $aErrores = [
    'desc' => null
];

try {
    //objeto PDO = mi base de datos
    $miBD = new PDO(HOST,USER, PASSWD);
                            // set the PDO error mode to exception
                                        //PDO::ERRMODE_EXCEPTION - Además de establecer el código de error, PDO lanzará una excepción PDOException y establecerá sus propiedades para luego poder reflejar el error y su información.
    $miBD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $resultado = $miBD->query("SELECT * FROM T01_Usuario WHERE T01_CodUsuario = '" . $_SESSION['usuarioDAW212DBProyectoTema5'] . "';");
    $aObjeto = $resultado->fetchObject();
    $datos = [
        'codigo' => $aObjeto->T01_CodUsuario,
        'descripcion' => $aObjeto->T01_DescUsuario,
        'tipo' => $aObjeto->T01_Perfil,
        'ultConex' => $aObjeto->T01_FechaHoraUltimaConexion,
        'conexiones' => $aObjeto->T01_NumConexiones
    ];
} catch (PDOException $mensajeError) { //Cuando se produce una excepcion se corta el programa y salta la excepciÃ³n con el mensaje de error
    echo "<h3>Mensaje de ERROR</h3>";
    echo "Error: " . $mensajeError->getMessage() . "<br>";
    echo "Codigo de error: " . $mensajeError->getCode();
}

if (isset($_POST['enviar'])) { //Si se ha pulsado enviar
    //La posiciÃ³n del array de errores recibe el mensaje de error si hubiera
    $aErrores['descripcion'] = validacionFormularios::comprobarAlfabetico($_POST['descripcion'], 250, 1, 1);  //maximo, mÃ­nimo y opcionalidad
    foreach ($aErrores as $campo => $error) { //Recorre el array en busca de mensajes de error
        if ($error != null) { //Si lo encuentra vacia el campo y cambia la condiccion
            $entradaOK = false; //Cambia la condiccion de la variable
        }
    }
} else {
    $entradaOK = false; //Cambiamos el valor de la variable porque no se ha pulsado el botÃ³n 
}

if ($entradaOK) {

    $sentenciaSQL = "UPDATE T01_Usuario SET T01_DescUsuario = :descripcion WHERE T01_CodUsuario = :codigo;";
    $resultadoSQL = $miBD->prepare($sentenciaSQL);
    $resultadoSQL->execute(array(':codigo' => $_SESSION['usuarioDAW212DBProyectoTema5'], ':descripcion' => $_POST['descripcion']));
    $_SESSION['descUser212'] = $_POST['descripcion'];

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
                                    <div class="obligatorio">
                                        <strong>Nombre del Usuario:</strong>
                                        <input type="text" id="nombre" style="border: 1px solid black" name="nombre" value="<?php echo $datos['codigo']; ?>" disabled><br><br> 
                                    </div>
                                    <div class="obligatorio">
                                        <strong>Descripción del Usuario:</strong>
                                        <input type="text" id="descripcion" style="border: 1px solid black" name="descripcion" value="<?php echo $datos['descripcion']; ?>"><br> <br>   
                                    </div>
                                    <div class="obligatorio">
                                        <strong>Tipo de Usuario:</strong>
                                        <input type="text" id="tipo" style="border: 1px solid black" name="tipo" value="<?php echo $datos['tipo']; ?>" disabled><br>   <br> 
                                    </div>
                                    <div class="obligatorio">
                                        <strong>Número de conexiones:</strong>
                                        <input type="text" id="conexiones" style="border: 1px solid black" name="conexiones" value="<?php echo $datos['conexiones']; ?>" disabled><br>  <br>  
                                    </div>
                                    <div class="obligatorio">
                                        <strong>Última conexión:</strong>
                                        <input type="text" id="ultConex" style="border: 1px solid black" name="ultConex" value="<?php echo date('d/m/Y - H:i:s', $datos['ultConex']) ?>" disabled><br>  <br>  
                                    </div>
                                    <div class="obligatorio">
                                        <input type="submit" name="enviar" id="botonAceptar" value="Aceptar">
                                        <input type="submit" name="cancelar" id="botonCancelar" value="Cancelar"><br><br>
                                        <input type="submit" name="editarPass" id="botonEditar" value="Editar Contraseña">
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

        
      
