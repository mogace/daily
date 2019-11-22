<?php 
/**
 * 小型数据库实现
 *  
 */

$fp = fopen("data.dat", "wb");
fwrite($fp, 12, 4);
fclose($fp);

?>