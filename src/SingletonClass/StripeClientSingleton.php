<?php
namespace App\SingletonClass;

class StripeClientSingleton
{
    private $public_key;
    private $private_key;
    private $stripe_client;

    /**
     * @var StripeClientSingleton
     * @access private
     * @static
     */
    private static $_instance = null;

    /**
     * Constructeur de la classe
     *
     * @param void
     * @return void
     */
    private function __construct()
    {
        $this->public_key = $_ENV["STRIPE_PUBLIC_KEY"];
        $this->private_key = $_ENV["STRIPE_PRIVATE_KEY"];

        $this->stripe_client = new \Stripe\StripeClient($this->private_key);
    }

    /**
     * 
     * @param void
     * @return StripeClientSingleton
     */
    public static function getInstance()
    {

        if (is_null(self::$_instance)) {
            self::$_instance = new StripeClientSingleton();
        }

        return self::$_instance;
    }

    public function getStripeClient(){
        return $this->stripe_client;
    }
}
