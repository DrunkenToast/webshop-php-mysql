<?php
require_once 'user.php';

/**Methods specifies needed role for a page.
 * Note: Session needs to be started
 * 
 * If access level does not meet expectations the user will be redirected to p_location
 * @param {string} p_location - Specifies where to redirect the user to.
 * @example (new RequireRole())->requireLoggedIn()
 */
Class RequireRole {
    // private $redirect_header;

    static function requireAdmin($p_location = '') {
        if (!User::isAdmin())
        {
            if ($p_location == '')
            { 
                http_response_code(403);
                die('Forbidden');
            }
            else
            {
                header("location: " . $p_location);
                exit;
            }
        }
    }

    static function requireLoggedIn($p_location = 'index.php') {
        if (!User::isLoggedIn())
        {
            header("location: " . $p_location);
            exit;
        }
    }

    static function requireNoLogin($p_location = 'index.php') {
        if (User::isLoggedIn())
        {
            header("location: " . $p_location);
            exit;
        }
    }
}
?>