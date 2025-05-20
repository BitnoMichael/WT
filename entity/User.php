<?php

require_once './.constants/Constants.php';
class User {
    private int $id;
    private string $username;
    private string $passwordHash;
    private string $email;
    private bool $rememberMe;
    private bool $isVerified;
    private string $salt;
    private ?string $token;

    public function __construct(
        array $args
    ) {
        $this->id = $args['id'];
        $this->username = $args['username'];
        $this->passwordHash = $args['password_hash'];
        $this->rememberMe = $args['remember_me'];
        $this->isVerified = $args['is_verified'];
        $this->salt = $args['salt'];
        $this->token = $args['token'];
        $this->email = $args['email'];
    }
    public function getID(): int {
        return $this->id;
    }
    public function getUsername(): string {
        return $this->username;
    }
    public function getIsVerified(): bool {
        return $this->isVerified;
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
    public function getToken(): string {
        return $this->token;
    }
    public function getEmail(): ?string {
        return $this->email;
    }
}