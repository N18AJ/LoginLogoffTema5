<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Tragen Sie hier Ihre Datenbankinformationen ein und den Namen der Backup-Datei
$mysqlDatabaseName ='dbs272023';
$mysqlUserName ='dbu287774';
$mysqlPassword ='Covid1234$';
$mysqlHostName ='db5000278679.hosting-data.io';
$mysqlImportFilename ='CrearDAW212DBProyectoTema5_1.sql';

//Por favor, no haga ningún cambio en los siguientes puntos
//Importación de la base de datos y salida del status
$command='mysql -h' .$mysqlHostName .' -u' .$mysqlUserName .' -p' .$mysqlPassword .' ' .$mysqlDatabaseName .' < ' .$mysqlImportFilename;
exec($command,$output=array(),$worked);
switch($worked){
case 0:
echo 'Los datos del archivo <b>' .$mysqlImportFilename .'</b> se han importado correctamente a la base de datos <b>' .$mysqlDatabaseName .'</b>';
break;
case 1:
echo 'Se ha producido un error durante la importación. Por favor, compruebe si el archivo está en la misma carpeta que este script. Compruebe también los siguientes datos de nuevo: <br/><br/><table><tr><td>Nombre de la base de datos MySQL:</td><td><b>' .$mysqlDatabaseName .'</b></td></tr><tr><td>Nombre de usuario MySQL:</td><td><b>' .$mysqlUserName .'</b></td></tr><tr><td>Contraseña MySQL:</td><td><b>NOTSHOWN</b></td></tr><tr><td>Nombre de host MySQL:</td><td><b>' .$mysqlHostName .'</b></td></tr><tr><td>Nombre de archivo de la importación de MySQL:</td><td><b>' .$mysqlImportFilename .'</b></td></tr></table>';
break;
}
?>