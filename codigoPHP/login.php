<?php
        //datos para la conexion de la base de datos
        include ("../config/confDB.php");
        require '../core/libreriaValidacion.php'; //Importamos la libreria de validacion
                
        $entradaOK = true; //Inicializamos una variable que nos ayudara a controlar si todo esta correcto
        

        //Inicializamos un array que se encargara de recoger los errores(Campos vacios)
        $aErrores = [
            'codUsuario' => null,
            'password' => null,  
        ];
        
        //Inicializamos un array que se encargara de recoger los datos del formulario(Campos vacios)
        $aFormulario = [
            'codUsuario' => null,
            'password' => null, 
        ];

        if (isset($_POST['enviar'])) { //Si se ha pulsado enviar
            //La posición del array de errores recibe el mensaje de error si hubiera
            $aErrores['codUsuario'] = validacionFormularios::comprobarAlfabetico($_POST['codUsuario'], 50, 1, 1);  //maximo, mínimo y opcionalidad
            $aErrores['password'] = validacionFormularios::comprobarAlfaNumerico($_POST['password'], 20, 1, 1); //maximo, mínimo y opcionalidad
            foreach ($aErrores as $campo => $error) { //Recorre el array en busca de mensajes de error
                if ($error != null) { //Si lo encuentra vacia el campo y cambia la condiccion
                    $entradaOK = false; //Cambia la condiccion de la variable
                }
            }
        } else {
            $entradaOK = false; //Cambiamos el valor de la variable porque no se ha pulsado el botón
        }

        if ($entradaOK) { //Si el valor es true procesamos los datos que hemos recogido
                 //PASAR LOS DATOS DEL SERVIDOR AL ARRAY
      
            $aFormulario["password"] = strtolower($_POST["password"]); //Funcion que pone en minusculas el texto

         //CONEXIÓN CON BASE DE DATOS
            try{
                //objeto PDO = mi base de datos
                $miBD = new PDO(HOST,USER, PASSWD);
					// set the PDO error mode to exception
                                                    //PDO::ERRMODE_EXCEPTION - Además de establecer el código de error, PDO lanzará una excepción PDOException y establecerá sus propiedades para luego poder reflejar el error y su información.
                $miBD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
               $user = $_POST['codUsuario'];
               $pwd = $_POST['password'];
               
               $SQL = "SELECT * FROM T01_Usuario WHERE T01_CodUsuario = :user AND T01_Password = :hash"; //es el query para buscar un usuario en la base de datos mediante su codigo de usuario y su hash
             
                $resultadoSQL = $miBD->prepare($SQL); //preparamos la consulta preparada
                //asignacion de valores con bindValue (te puede dar error en este caso con bindParam)
                $resultadoSQL->bindValue(':user', $user);
                $resultadoSQL->bindValue(':hash', hash('sha256', $user . $pwd)); //la contraseña es paso, pero para resumirla -> sha + contraseña=concatenacion de nombre+password
                $resultadoSQL->execute();
              
             if ($resultadoSQL->rowCount() == 1) {
                    //$_SESSION['usuarioDAW203AppLoginLogoff'] = $user;
                    $aObjetos = $resultadoSQL->fetchObject();//transforma los valores en objetos y me permite seleccionarlos                
                    session_start();  //te inicia la sesion
                    $_SESSION['usuarioDAW212DBProyectoTema5'] = $aObjetos->T01_CodUsuario;
                    //$_SESSION['descUsuario212'] = $aObjetos->T01_DescUsuario;
                    $_SESSION['ultimaConexion212'] = $aObjetos->T01_FechaHoraUltimaConexion;
                    //$_SESSION['numConexiones212'] = $aObjetos->T01_NumConexiones+1;
                    
                    $fechaSQL = "UPDATE T01_Usuario SET T01_FechaHoraUltimaConexion = " . time() . " WHERE T01_CodUsuario = :codigo;";
                    $actualizarFechaSQL = $miBD->prepare($fechaSQL);
                    $actualizarFechaSQL->execute(array(':codigo' => $aObjetos->T01_CodUsuario));

                    $conexionesSQL = "UPDATE T01_Usuario SET T01_NumConexiones = T01_NumConexiones + 1 WHERE T01_CodUsuario = :codigo;";
                    $actualizarConexionesSQL = $miBD->prepare($conexionesSQL);
                    $actualizarConexionesSQL->execute(array(':codigo' => $aObjetos->T01_CodUsuario));
                    header("Location: programa.php");
                } else {
                    header('Location: login.php');
                }
            } catch (PDOException $mensajeError) { //Cuando se produce una excepcion se corta el programa y salta la excepción con el mensaje de error
                echo "<h3>Mensaje de ERROR</h3>";
                echo "Error: " . $mensajeError->getMessage() . "<br>";
                echo "Código de error: " . $mensajeError->getCode();
                    
                    //finalmente, sales de la base de datos cerrando tambien el usuario
                } finally {
                    unset($miBD);
                }
            }
if (isset($_GET['idioma'])) {
    if ($_GET['idioma'] === "en") {
        setcookie('idioma', "en", time() + 7 * 24 * 60 * 60); //La Cookie tiene un periodo de vida de 7 días
        header("Location: login.php");
    }
     if ($_GET['idioma'] === "fr") {
        setcookie('idioma', "fr", time() + 7 * 24 * 60 * 60); //La Cookie tiene un periodo de vida de 7 días
        header("Location: login.php");
    }
    if ($_GET['idioma'] === "es") {
        setcookie('idioma', "es", time() + 7 * 24 * 60 * 60); //La Cookie tiene un periodo de vida de 7 días
        header("Location: login.php");
    }
}
if (!isset($_COOKIE['idioma'])) {
    setcookie('idioma', "es", time() + 7 * 24 * 60 * 60); //La Cookie tiene un periodo de vida de 7 días
    header("Location: login.php");
}           
else{
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
                         <div id="idiomas">
                            <nav class="idioma">
                                <a href="<?php echo $_SERVER['PHP_SELF'] ?>?idioma=es"><img src="../webroot/media/images/espanol.png" alt="Español" width="35" class="icono_es"/></a>
                                <a href="<?php echo $_SERVER['PHP_SELF'] ?>?idioma=en"><img src="../webroot/media/images/ingles.png" alt="Inglés" width="35" class="icono_en"/></a>
                                <a href="<?php echo $_SERVER['PHP_SELF'] ?>?idioma=fr"><img src="../webroot/media/images/frances.png" alt="Inglés" width="35" class="icono_fr"/></a>
                            </nav>
                             
                         </div>
                         
                        <form style="text-align: center;" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <fieldset>
                                <?php
                                    if (isset($_COOKIE['idioma'])) {
                    //  -----  IDIOMA FRANCES INGLÉS
                                        if ($_COOKIE['idioma'] === "en") {
                                            echo '<a href="#" class="seleccionado"><legend><h2>Name and password</h2></legend></a>';
                                ?>        
                                            </br>
                                        <div class="obligatorio">
                                            Name: 
                                            <input type="text" name="codUsuario" placeholder="Name" value="<?php if($aErrores['codUsuario'] == NULL && isset($_POST['codUsuario'])){ echo $_POST['codUsuario'];} ?>"><br> <!--//Si el valor es bueno, lo escribe en el campo-->
                                            <?php if ($aErrores['codUsuario'] != NULL) { ?>  
                                        <?php } ?>                
                                        </div>
                                        </br>
                                        <div class="obligatorio">
                                            Password: 
                                            <input type="password" name="password" placeholder="Password" value="<?php if($aErrores['password'] == NULL && isset($_POST['password'])){ echo $_POST['password'];} ?>"><br> <!--//Si el valor es bueno, lo escribe en el campo-->
                                            <?php if ($aErrores['password'] != NULL) { ?>  
                                        <?php } ?>                
                                        </div>
                                        <br>

                                        <div class="obligatorio">               
                                        <input type="submit" id="botonAceptar" name="enviar" value="LOG IN">
                                        </div>        
                                        
                                <?php    
                                        }
                    //  -----  IDIOMA FRANCES
                                        if ($_COOKIE['idioma'] === "fr") {
                                            echo '<a href="#" class="seleccionado"><legend><h2>Nom et mot de passe</h2></legend></a>';  
                                ?>        
                                            </br>

                                        <div class="obligatorio">
                                            Non: 
                                            <input type="text" name="codUsuario" placeholder="Non" value="<?php if($aErrores['codUsuario'] == NULL && isset($_POST['codUsuario'])){ echo $_POST['codUsuario'];} ?>"><br> <!--//Si el valor es bueno, lo escribe en el campo-->
                                            <?php if ($aErrores['codUsuario'] != NULL) { ?>  
                                        <?php } ?>                
                                        </div>
                                        </br>
                                        <div class="obligatorio">
                                            Mot de passe: 
                                            <input type="password" name="password" placeholder="Mot de passe" value="<?php if($aErrores['password'] == NULL && isset($_POST['password'])){ echo $_POST['password'];} ?>"><br> <!--//Si el valor es bueno, lo escribe en el campo-->
                                            <?php if ($aErrores['password'] != NULL) { ?>  
                                        <?php } ?>                
                                        </div>
                                        <br>

                                        <div class="obligatorio">               
                                        <input type="submit" id="botonAceptar" name="enviar" value="S'IDENTIFIER">
                                        </div>    
                                        
                                <?php            
                                        }  
                    //  -----  IDIOMA ESPAÑOL
                                        if ($_COOKIE['idioma'] === "es") {
                                                echo '<a href="#" class="seleccionado"><legend><h2>Nombre y contraseña</h2></legend></a>';
                                ?>        
                                                </br>

                                            <div class="obligatorio">
                                                Nombre: 
                                                <input type="text" name="codUsuario" placeholder="Nombre" value="<?php if($aErrores['codUsuario'] == NULL && isset($_POST['codUsuario'])){ echo $_POST['codUsuario'];} ?>"><br> <!--//Si el valor es bueno, lo escribe en el campo-->
                                                <?php if ($aErrores['codUsuario'] != NULL) { ?>  
                                            <?php } ?>                
                                            </div>
                                            </br>
                                            <div class="obligatorio">
                                               Contraseña: 
                                                <input type="password" name="password" placeholder="Contraseña" value="<?php if($aErrores['password'] == NULL && isset($_POST['password'])){ echo $_POST['password'];} ?>"><br> <!--//Si el valor es bueno, lo escribe en el campo-->
                                                <?php if ($aErrores['password'] != NULL) { ?>  
                                            <?php } ?>                
                                            </div>
                                            <br>

                                            <div class="obligatorio">               
                                            <input type="submit" id="botonAceptar" name="enviar" value="INICIAR SESION">
                                            </div>        
                                 <?php                       
                                            }
                                    } else if ($_COOKIE['idioma'] === "es"){
                                        echo '<a href="#" class="seleccionado"><legend><h2>Elija un idioma</h2></legend></a>';
                                    }
                                ?>                 
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

        
      