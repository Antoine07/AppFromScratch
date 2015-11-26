<?php namespace Cart;

class SessionStorage implements Storable
{

    protected $sessionName;

    public function __construct($sessionName = 'product')
    {
        session_start();
        if (!isset($_SESSION[$sessionName])) {
            $_SESSION[$sessionName] = [];
        }
        $this->sessionName = $sessionName;
    }

    /**
     * @param $name
     * @param $value
     */
    public function setValue($name, $value)
    {
        if (empty($_SESSION[$this->sessionName][$name])) $_SESSION[$this->sessionName][$name] = 0;

        $_SESSION[$this->sessionName][$name] += $value;

    }

    /**
     * @param $name
     */
    public function getValue($name)
    {
        if (!empty($_SESSION[$this->sessionName][$name])) {
            return $_SESSION[$this->sessionName][$name];
        }
    }

    /**
     * @param $name
     */
    public function restore($name)
    {
        if (!empty($_SESSION[$this->sessionName][$name])) {
            unset($_SESSION[$this->sessionName][$name]);
        }
    }

    /**
     * @return number
     */
    public function total()
    {
        return array_sum($_SESSION[$this->sessionName]);
    }

    /**
     * restore Cart
     */
    public function reset()
    {
        $_SESSION[$this->sessionName] = [];
    }

    public function all()
    {
        return $_SESSION[$this->sessionName];
    }

}