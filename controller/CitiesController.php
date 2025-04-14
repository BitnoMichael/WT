<?php

require_once 'services/TemplateRenderer.php';
require_once 'services/CitiesParser.php';
class CitiesController
{
    private $trDI;
    private $citiesParser;
    public function __construct($tr)
    {
        $this->trDI = $tr;
        $this->citiesParser = new CitiesParser();
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
            echo $this->trDI->render("public/templates/views/index.html");
            $this->trDI->clear();
            return;
        }
    }
}