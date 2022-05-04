<?php

class Logger {
    private $errno;
    private $errMsg;
    private $errFile;
    private $errLine;
    const FILE = __DIR__ . '/../../logs/errors.log';
    public function __construct($errno = 0, $errMsg = "", $errFile = "", $errLine = "") {
        $this->errno = $errno;
        $this->errMsg = $errMsg;
        $this->errFile = $errFile;
        $this->errLine = $errLine;
    }
    
    public function error() {
        $error = "[ Error ]" . '[ ' . date("Y-m-d H:i:s") . ' ]';
        $error .= "[ " . $this->errno . " ]: ";
        $error .= $this->errMsg;
        $error .= " in file " . $this->errFile;
        $error .= " on line " . $this->errLine ."\n";
        // Log details of error in a file
        error_log($error, 3, self::FILE);
    }

    static public function info($msg) {
        $info = "[ Info ]" . '[ ' . date("Y-m-d H:i:s") . ' ]: ';
        $info .= $msg . "\n";
        // Log details of info in a file
        error_log($info, 3, self::FILE);
    }

    static public function warning($msg) {
        $warning = "[ Warning ]" . '[ ' . date("Y-m-d H:i:s") . ' ]: ';
        $warning .= $msg . "\n";
        // Log details of warning in a file
        error_log($warning, 3, self::FILE);
    }
}


?>