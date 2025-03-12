<?php
function parseListOfCities($str):array 
{
    $cities = explode( ",", $str, PHP_INT_MAX);
    $result = array();
    foreach ($cities as $city)
    {
        $canAdd = true;
        $city = trim($city);
        if (!ctype_upper($city[0]))
        {
            $canAdd = false;
        }
        for ($i = 1; $i < strlen($city); $i++)
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
phpinfo();
