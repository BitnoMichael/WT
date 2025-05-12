<?php

class User {
    private int $id;
    private string $username;
    private string $passwordHash;
    private bool $rememberMe;
    private string $salt;

    public function __construct(
        array $args
    ) {
        $this->id = $args['id'];
        $this->username = $args['username'];
        $this->passwordHash = $args['password_hash'];
        $this->rememberMe = $args['remember_me'];
        $this->salt = $args['salt'];
    }
    public function getID(): int {
        return $this->id;
    }
    public function getUsername(): string {
        return $this->username;
    }

    public function getPasswordHash(): string {
        return $this->passwordHash;
    }
    public function getRememberMe(): bool {
        return $this->rememberMe;
    }
    public function getSalt(): string {
        return $this->salt;
    }
}