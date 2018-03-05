<!-- 
DROP PROCEDURE IF EXISTS buscaInventarios;
DELIMITER $$
CREATE PROCEDURE buscaInventarios (
	IN desc1 VARCHAR(250), IN desc2 VARCHAR(250), IN desc3 VARCHAR(250), IN mes VARCHAR(2), IN anio VARCHAR(4), IN caja INT
)
BEGIN
	
    IF desc1 = '' THEN SET desc1 = '%'; END IF;
    IF desc2 = '' THEN SET desc2 = '%'; END IF;
    IF desc3 = '' THEN SET desc3 = '%'; END IF;
    IF mes = '0' THEN SET mes = '%'; END IF;
    IF anio = '0' THEN SET anio = '%'; END IF;
    SET @consulta = CONCAT('SELECT * FROM inventarios 
                           WHERE DESC_1 like ''%', desc1,'%''
                           AND DESC_2 like ''%', desc2,'%''
                           AND DESC_3 like ''%', desc3,'%''
                           AND MES_I like ''', mes,'''
                           AND ANO_I like ''%', anio,'%''
                           AND CAJA like ''%', caja,'%''
                           ');
    PREPARE ejecutar FROM @consulta;
    EXECUTE ejecutar;

END$$
DELIMITER ;


DROP PROCEDURE IF EXISTS pruebas;
DELIMITER $$
CREATE PROCEDURE pruebas()
BEGIN
	SELECT * FROM inventarios
    WHERE CAJA = 25666;
    SELECT * FROM inventarios
    WHERE DESC_1 LIKE 'GRUPO 1,2,3';
END $$
DELIMITER ; -->


<?php 
require_once 'conexionClass.php';
$conexion = new MiConexion();
$caja = "233";
$desc1 = "facturas";
$desc2 = "";
$desc3 = "tomo 1-2";
$mes = "0";
$anio = "0";
// $sql = "SELECT * FROM inventarios LIMIT 5";
$inv = $conexion->llamadaSP($desc1, $desc2, $desc3, $mes, $anio, $caja);
var_dump($inv);
?>
