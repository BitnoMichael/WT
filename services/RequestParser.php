<?php

class RequestParser
{
    public function parseQueryString(string $queryString)
    {
        $_GET = [];
        if (empty($queryString)) {
            return $queryString;
        }

        $pairs = explode('&', $queryString);
        foreach ($pairs as $pair) {
            
            $parts = explode('=', $pair, 2); 
            
            if (count($parts) === 2) {
                $key = trim($parts[0]);
                $value = trim($parts[1]); 
                
                $key = urldecode($key);
                $value = urldecode($value);

                $_GET[$key] = $value;
            }
        }
    }
    public function parsePostBody()
    {
        $json = file_get_contents('php://input');

        $_POST = json_decode($json, true);

        if ($_POST === null && json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Некорректный JSON.']);
            return;
        }
    }
}