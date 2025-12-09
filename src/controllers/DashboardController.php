<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/UserRepository.php';
require_once __DIR__.'/../repository/CardsRepository.php';
class DashboardController extends AppController {

    private $cardsRepository;
    public function __construct()
    {
        $this->cardsRepository = new CardsRepository();
    }
    public function index(){
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
        $userRepository = new UserRepository();
        $users = $userRepository->getUser();
        $cards = $this->cardsRepository->getCards();
        var_dump($users);

        $this->render('dashboard', ['cards' => $cards]);
    }

    public function search()
    {
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

        if($contentType() !== 'application/json'){
            echo json_encode(['message' => 'it is not endpoit for this method']);
            return;
        }

        if(!$this->isPost()){
            echo json_encode(['message' => 'method not allowed']);
        }
        $content = trim(file_get_contents("php://input"));
        $decoded = json_decode($content, true);

        http_response_code(200);

        echo json_encode($this->cardsRepository->getCardsByTitle($decoded['search']));
        return;
    }

}