<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/UserRepository.php';

class SecurityController extends AppController {
    private $userRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    // TODO dekarator, który definiuje, jakie metody HTTP są dostępne
    public function login() {

        if($this->isGet()) {
            return $this->render("login");
        }

        $email = $_POST["email"] ?? '';
        $password = $_POST["password"] ?? '';

        if(empty($email) || empty($password)) {
            return $this->render("login", ["message" => "Fill all fields"]);
        }

        $user = $this->userRepository->getUserByEmail($email);

        if(!$user){
            return $this->render("login", ["message" => "User not found"]);

        }
       if(!password_verify($password, $user['password'])){
           return $this->render("login", ["message" => "Wrong password"]);
       }

       // TODO create user session / cookie

        return $this->render("dashboard");
    }

    public function register() {
        // TODO pobranie z formularza email i hasła
        // TODO insert do bazy danych
        // TODO zwrocenie informajci o pomyslnym zarejstrowaniu

        if ($this->isGet()) {
            return $this->render("register");
        }

        $email = $_POST["email"] ?? '';
        $password1 = $_POST["password1"] ?? '';
        $password2 = $_POST["password2"] ?? '';
        $firstname = $_POST["firstname"] ?? '';
        $lastname = $_POST["lastname"] ?? '';

        if (empty($email) || empty($password1) || empty($firstname) ||  empty($password2) || empty($lastname)) {
            return $this->render('register', ['messages' => ['Fill all fields']]);
        }

        if ($password1 !== $password2) {
            return $this->render('register', ['messages' => ['Passwords should be the same!']]);
        }

        // TODO check if user with this email already exists

        $hashedPassword = password_hash($password1, PASSWORD_BCRYPT);

        $this->userRepository->createUser(
            $email,
            $hashedPassword,
            $firstname,
            $lastname
        );

        return $this->render("login", ["message" => "Zarejestrowano uytkownika ".$email]);
    }
}