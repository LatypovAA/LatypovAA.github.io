<?php
include 'main.php';

try {
    $main = new Main();
    $result = $main->send();
    
    echo json_encode($result);
    
} catch (Exception $exc) {
    $result = array(
        'success'=> false,
        'error' => $exc->getMessage()
    );
    echo json_encode($result);
}
