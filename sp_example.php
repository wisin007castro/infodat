<!-- DROP PROCEDURE IF EXISTS buscaInventarios;
DELIMITER $$
CREATE PROCEDURE buscaInventarios 
(
	IN caja VARCHAR(20), IN desc1 VARCHAR(250), IN desc2 VARCHAR(250), IN desc3 VARCHAR(250)
) 
BEGIN 
	SELECT * FROM inventarios 
    WHERE CAJA like CONCAT('%',caja,'%')  AND DESC_1 like CONCAT('%', desc1, '%') AND DESC_2 like CONCAT('%', desc2, '%') AND DESC_3 like CONCAT('%',desc3,'%') ;
END $$
DELIMITER ;
 -->
<?php 
require_once 'conexionClass.php';
$conexion = new MiConexion();

// $sql = "SELECT * FROM inventarios LIMIT 5";
$inv = $conexion->llamadaSP();
var_dump($inv);

?>
