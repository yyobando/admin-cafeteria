<?php
require_once 'http_status.php';
class Rest extends HttpStatus
{
    public  function response($status_http = 200, $message = 'success', $data = null, $status_code = null, $errors = null) 
    {
        $data = (empty($data)) ? null: $data;
        $array_response = array(
            "status" => (empty($status_code)) ? $status_http : $status_code,
            "message" => $message,
            "data" => $data 
        ); 

        if (!empty($errors)) {
            $array_response->error = $errors;
        }

        header("HTTP/1.1  200 OK");
        echo json_encode($array_response);
        exit();
    }
}
