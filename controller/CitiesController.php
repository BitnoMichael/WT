<?php

require_once '/home/mihail/WT/src/Services/TemplateRenderer.php';
require_once '/home/mihail/WT/src/Services/CitiesParser.php';
class CitiesController
{
    private $trDI;
    private $citiesParser = new CitiesParser();
    public function __construct($tr)
    {
        $this->trDI = $tr;
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $input = $_POST['cities'] ?? '';
            $cities = $this->citiesParser->parseListOfCities($input);
            sort($cities, SORT_STRING);

            $this->trDI->assign('haveCities', true);
            $this->trDI->assign('cities', $cities);
            echo $this->trDI->render("/home/mihail/WT/templates/HTML/index.html");
            $this->trDI->clear();
            return;
        }
    }
}