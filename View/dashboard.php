<?php
include_once('header.php');
?>
<html>
<h2>Thông tin User</h2>
<ul>
    <?php 
    foreach ($dataUser as $key => $value) {
        echo "<li>$key: $value</li>";
    }
    ?>
</ul>
</html>