<?php

require_once 'src/controllers/AppController.php';

class SecurityController extends AppController
{
    public function login()
    {
        return $this->render('login');
    }
}
