-- DAW2XXProyectoTema5 || DAW2XXLoginLogoffTema5 || DAW2XXMtoDepartamentosPDOTema5 --

USE dbs272023;

-- Crear tablas. --
CREATE TABLE IF NOT EXISTS T01_Usuario(
    T01_CodUsuario varchar(15) PRIMARY KEY,
    T01_DescUsuario varchar(250) NOT null,
    T01_Password varchar(64) NOT null,
    T01_Perfil enum('administrador', 'usuario') default 'usuario', -- Valor por defecto usuario
    T01_FechaHoraUltimaConexion int null,
    T01_NumConexiones int default 0,
    T01_ImagenUsuario mediumblob
)ENGINE = INNODB;


CREATE TABLE IF NOT EXISTS T02_Departamento(
    T02_CodDepartamento varchar(3) PRIMARY KEY,
    T02_DescDepartamento varchar(255) NOT null,
    T02_FechaBajaDepartamento int DEFAULT null, -- Valor por defecto null, ya que cuando lo creas no puede estar de baja logica
    T02_FechaCreacionDepartamento int null,
    T02_VolumenNegocio float NOT null
)ENGINE = INNODB;

    


