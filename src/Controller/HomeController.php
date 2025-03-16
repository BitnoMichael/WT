<?php

class HomeController
{
    public function showMainPage()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
            include '../templates/HTML/index.html';
    }
}