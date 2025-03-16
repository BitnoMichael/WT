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
    
    function sortCities($array):array
    {
        natcasesort($array);
        return $array;
    }
    public function getSortedCities()   
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $input = $_POST['textfield'] ?? '';
            $cities = $this->parseListOfCities($input);
            include '../templates/HTML/index.html';
            return $this->sortCities($cities);
        }
        include '../templates/HTML/index.html';
        return [];

    }
}