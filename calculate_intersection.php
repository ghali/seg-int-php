<?php
    // tag::api[]
    $data = json_decode(file_get_contents('php://input'));

    include 'geom.php';

    $seg1 = new Segment($data->seg1);
    $seg2 = new Segment($data->seg2);
    $result = intersect($seg1, $seg2);
    
    header('Content-Type: application/json');
    echo json_encode($result);
    // end::api[]
?>
