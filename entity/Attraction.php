<?php
class Attraction {
    public string $id;
    public string $name;
    public string $description;
    public string $location;
    public string $createdAt;
    public function __construct(array $array) {
        $this->id = $array['id'];
        $this->name = $array['name'];
        $this->description = $array['description'];
        $this->location = $array['location'];
        $this->createdAt = $array['created_at'];
    }

}