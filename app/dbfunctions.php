<?php

function getMe($pdo){
    $sql = "SELECT * FROM my_table";
    try {
        $db = $pdo;
        $stmt = $db->query($sql);
        
        $me = $stmt->fetch(PDO::FETCH_ASSOC);
        $db = null;
        return $me;
    } 
    catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
        return null;
    }

}