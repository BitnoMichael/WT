<?php
require_once 'src/repository/Attractions.php';
require_once 'src/repository/Reviews.php';
require_once 'src/repository/Users.php';

class DB
{
    private Attractions $attractions;
    private Reviews $reviews;
    private Users $users;
    public function __construct(mysqli $db)
    {
        $this->attractions = new Attractions($db);
        $this->reviews = new Reviews($db);
        $this->users = new Users($db);
    }
    public function getAttractions(): Attractions
    {
        return $this->attractions;
    }
    public function getReviews(): Reviews
    {
        return $this->reviews;
    }
    public function getUsers(): Users
    {
        return $this->users;
    }
}