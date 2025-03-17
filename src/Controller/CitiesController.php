<?php

class CitiesController
{
    function parseListOfCities($str):array 
    {
        $cities = explode( ",", $str, PHP_INT_MAX);
        $result = [];
        foreach ($cities as $city)
        {
            $canAdd = true;
            $city = trim($city);
            if (strlen($city) == 0)
            {
                continue;
            }
            if (!ctype_upper($city[0]))
            {
                $canAdd = false;
            }
            for ($i = 1; $i < strlen($city) && $canAdd; $i++)
            {
                if (ctype_upper($city[$i]))
                {
                    $canAdd = false;
                }
            }
            if ($canAdd)
            {
                array_push($result, $city);
            }
        }
        return array_unique($result, SORT_STRING);
    }  

    public function handleRequest()   
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $input = $_POST['cities'] ?? '';
            $cities = $this->parseListOfCities($input);
            sort($cities, SORT_STRING);
            include '../templates/HTML/index.html';
            return;
        }
        include '../templates/HTML/index.html';
    }
}