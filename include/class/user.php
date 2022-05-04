<?php
class User {
    static public function isLoggedIn() {
        return (isset($_SESSION['user']) && !empty($_SESSION['user']));
      }
      
    static public function isAdmin() {
        $test = (isset($_SESSION['user']) && !empty($_SESSION['user']) && $_SESSION['user']['roleId'] == 1);
        return $test;
    }
}
?>