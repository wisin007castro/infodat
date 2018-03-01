<!-- DROP PROCEDURE IF EXISTS buscaInventarios;
DELIMITER $$
CREATE PROCEDURE buscaInventarios 
(
	IN caja VARCHAR(20), IN desc1 VARCHAR(250), IN desc2 VARCHAR(250), IN desc3 VARCHAR(250)
) 
BEGIN
	IF caja = NULL THEN SET caja = '%'; END IF;
    IF desc1 = NULL THEN SET desc1 = '%'; END IF;
    IF desc2 = '' THEN SET desc2 = '%'; END IF;
    IF desc3 = '' THEN SET desc3 = '%'; END IF;
	SELECT * FROM inventarios
    WHERE CAJA like CONCAT('%', caja,'%')  AND DESC_1 like CONCAT('%', desc1, '%') AND DESC_2 like CONCAT('%', desc2, '%') AND DESC_3 like CONCAT('%', desc3,'%') ;
END $$
DELIMITER ; -->


<?php 
require_once 'conexionClass.php';
$conexion = new MiConexion();
$caja = "";
$desc1 = "";
$desc2 = "";
$desc3 = "";
// $sql = "SELECT * FROM inventarios LIMIT 5";
$inv = $conexion->llamadaSP($caja, $desc1, $desc2, $desc3);
var_dump($inv);

?>
