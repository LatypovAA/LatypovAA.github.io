<?php
class Controller_Main extends Controller
{
    function __construct() {
        $this->model = new Model_main();
        $this->view = new View();
    }
    function action_index()
    {   
        $this->view->generate('main_view.php', 'tamplate_view.php', "");
    }
    function action_auth()
    {
        $access_token = $this->model->get_data();
        echo $access_token;die;
        $this->view->generate('auth_view.php', 'tamplate_view.php',$access_token);
        
    }
}