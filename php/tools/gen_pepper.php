<?php
    $pepper = bin2hex(random_bytes(32));
    echo "Pepper: ".$pepper;
?>