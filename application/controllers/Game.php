<?php
/**
 * This is the portfolio(profile) controller used to pass data
 * to display profile information.
 */

class Game extends Application {

    /**
     * Grabs both models to use one for the transactions and
     * since if a player has no transactions, use player model
     * to get player table values to fill in dropdown
     * Portfolio constructor.
     */
    function __construct() {

        parent::__construct();

        $this->load->model('transactions');
        $this->load->model('players');
        $this->load->model('stocks');
        $this->load->model('stocks_held');
        $this->load->library('form_validation');
    }

    //-------------------------------------------------------------
    //  The normal pages
    //-------------------------------------------------------------

    /**
     * Shows the player whose currently logged in and if not logged,
     * shows Donald. Uses a different pagebody for a user profile as
     */
    function index() {

        //If there is no user logged in just picks a random profile from database
        if($this->session->userdata('user') == null) {

        }
            $this -> one($this->session->userdata('user')["name"]);

    }

    /**
     * Shows the data for one player
     * @param $player the name of the player
     */
    function one($player) {
        $name = ucfirst($player);
        $currentPlayer = $this->players->get_player($player);
        $stocks_held = $this->stocks_held->get_player_stocks($player);
        $player_transactions = $this->transactions->get_player_transactions($player);

        $this->data['pageTitle'] = 'Welcome to the game ' . $name . '!';

        // this is the view we want shown
        $this->data['pagebody'] = 'game';

        //Data to fill transactions table

        if($player_transactions == null) {
            $this->data['transactions'] = array(array
            ("DateTime" => '', "Player" => '', 'Stock' => '', 'Trans' => '', 'Quantity' => ''));

        } else {
            $this->data['transactions'] = $player_transactions;
        }

        //Active Stocks
        $this->data['stocks']= $this->stocks->get_stocks();

        //get current player
        $this->data['cash'] = $currentPlayer->Cash;

        //Player's current holdings in each stock
        if($stocks_held == null) {
            $this->data['stocks_held'] = array(array("stock" => '', "amount" => ''));
        } else {
            $this->data['stocks_held'] = $stocks_held;
        }

        $this->render();
    }

}