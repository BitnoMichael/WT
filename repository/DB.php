<?php
require_once 'repository/AttractionsRepository.php';
require_once 'repository/ReviewsRepository.php';
require_once 'repository/UsersRepository.php';
require_once './.constants/Constants.php';

class DB
{
    private AttractionsRepository $attractions;
    private ReviewsRepository $reviews;
    private UsersRepository $users;
    public function __construct(mysqli $db)
    {
        $this->attractions = new AttractionsRepository($db);
        $this->reviews = new ReviewsRepository($db);
        $this->users = new UsersRepository($db);
    }
    public function getAttractions(): AttractionsRepository
    {
        return $this->attractions;
    }
    public function getReviews(): ReviewsRepository
    {
        return $this->reviews;
    }
    public function getUsers(): UsersRepository
    {
        return $this->users;
    }
}