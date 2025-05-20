<?php

require_once './.constants/Constants.php';
class Attraction {
    private string $id;
    private string $name;
    private string $description;
    private string $location;
    private string $createdAt;
    public function __construct(array $array) {
        $this->id = $array['id'];
        $this->name = $array['name'];
        $this->description = $array['description'];
        $this->location = $array['location'];
        $this->createdAt = $array['created_at'];
    }
    public function getID(): string {
        return $this->id;
    }
    public function getName(): string {
        return $this->name;
    }
    public function getDescription(): string {
        return $this->description;
    }
    public function getLocation(): string {
        return $this->location;
    }
    public function getDateOfCreation(): string {
        return $this->createdAt;
    }

}