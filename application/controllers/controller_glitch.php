<?php
class Controller_Main extends Controller
{
    function __construct() {
        $this->model = new Model_main();
        $this->view = new View();
    }
    function action_index()
    {   
        $this->view->generate('glitch_view.php', 'tamplate_view.php', "");
    }
}