<?php

class HomeController extends Controller {

    public function index() {
        $this->render('home/index', array(
            'title' => 'Accueil'
        ));
    }
}