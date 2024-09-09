<?php

namespace Catalog;

class Session {

    /**
     * @var array
     */
    private $settings = [
        'lifetime'     => 3600,
        'path'         => '/',
        'domain'       => null,
        'secure'       => false,
        'httponly'     => false,
        'name'         => 'catalog_session',
    ];
    
     /**
     * Constructor
     *
     */
    public function __construct() 
    {
        
        if (ini_get('session.gc_maxlifetime') < $this->settings['lifetime']) {
            ini_set('session.gc_maxlifetime', $this->settings['lifetime']);
        }
	}

    public function getIsActive()
    {
        return session_status() == PHP_SESSION_ACTIVE;
    }
    
     /**
     * Start session
     */
    public function startSession()
    {
        if ($this->getIsActive()) return;

        $settings = $this->settings;
        $name = $settings['name'];

        //Устанавливает параметры сессионной cookie
        session_set_cookie_params(
            $settings['lifetime'],
            $settings['path'],
            $settings['domain'],
            $settings['secure'],
            $settings['httponly']
        );

        session_name($name);

        //Получить и/или установить текущий режим кеширования
        session_cache_limiter('public');
        session_start();
    }


    /**
     * Get a session variable.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->exists($key) ? $_SESSION[$key] : $default;
    }

      /**
     * Set a session variable.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        if(!empty($key) && !empty($value)){
            return $_SESSION[$key] = $value;
        }
        throw new Exception("key and value are required");
    }

    /**
     * Delete a session variable.
     *
     * @param string $key
     *
     * @return $this
     */
    public function delete($key)
    {
        if ($this->exists($key)) {
            unset($_SESSION[$key]);
        }

        return $this;
    }

     /**
     * Clear all session variables.
     *
     * @return $this
     */
    public function clear()
    {
        $_SESSION = [];

        return $this;
    }
    
    /**
     * Check if a session variable is set.
     *
     * @param string $key
     *
     * @return bool
     */
    public function exists($key)
    {
        return array_key_exists($key, $_SESSION);
    }

	/**
     * Gets the session ID.
     * This is a wrapper for [PHP session_id()](http://php.net/manual/en/function.session-id.php).
     * @return string the current session ID
     */
    public function getId()
    {
        return session_id();
    }

    /**
     * Sets the session ID.
     * This is a wrapper for [PHP session_id()](http://php.net/manual/en/function.session-id.php).
     * @param string $value the session ID for the current session
     */
    public function setId($value)
    {
        session_id($value);
    }

    /**
     * Updates the current session ID with a newly generated one .
     * Please refer to <http://php.net/session_regenerate_id> for more details.
     * @param boolean $deleteOldSession Whether to delete the old associated session file or not.
     */
    public function regenerateID($deleteOldSession = false)
    {
        // add @ to inhibit possible warning due to race condition
        // https://github.com/yiisoft/yii2/pull/1812
        session_regenerate_id($deleteOldSession);
	}

    public function destroy()
    {
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        session_start();
    }

}
