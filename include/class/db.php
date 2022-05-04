<?php
/**Inspired by:
 * https://codeshack.io/super-fast-php-mysql-database-class/
 * 
 * Fetching etc done differently to what I need. Written by myself so I understand what I am doing.
*/
require_once __DIR__ . '/../errors.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// TODO error handling
class Database {
        private $link;
        private $stmt;

        public function __construct(
            $p_host = "database",
            $p_user = "Webuser",
            $p_passwd = "Lab2021",
            $p_name = "webshop"
        )
        {
            // try {
                $this->link = mysqli_connect($p_host, $p_user, $p_passwd, $p_name);
            // } catch (\Exception $e) {
            //     Throw(new Exception($this->link->error, $this->link->errno));
            //     throw(new Exception("Can't connect to host " . $p_host . ". \nSQL error: " . mysqli_connect_error() . "\n" . $e));
            // }
        }

        public function __destruct()
        {
            // print('Closing database connection');
            if (isset($this->stmt) && !is_bool($this->stmt)) // TODO make better
            {
                mysqli_stmt_close($this->stmt);
            }

            if (isset($this->link))
            {
                mysqli_close($this->link);
            }
        }

        public function query($query, ...$args)
        {
            $this->prepare($query);

            if ($this->stmt)
            {
                $types = '';
                if (count($args) > 0)
                {
                    foreach ($args as $e)
                    {
                        $types .= $this->getType($e);
                    }
                    mysqli_stmt_bind_param($this->stmt, $types, ...$args);
                }
                mysqli_stmt_execute($this->stmt);
                if ($this->stmt->errno)
                {
                    die("Error check param");
                }
            }
            else
            {
                die("Error to prepare, check syntax");
            }

            return $this;
        }

        /** Bind arguments to results from fetch */
        public function bind(&...$args)
        {
            mysqli_stmt_bind_result($this->stmt, ...$args);
            return $this;
        }
        
        /** Fetch rows, returns false when no rows are left */
        public function fetch()
        {
            return mysqli_stmt_fetch($this->stmt);
        }
        
        /** Returns the amount of rows */
        public function numRows() {
            return mysqli_num_rows($this->stmt->get_result()); // TODO? check if result exists 
        }

        /** Returns the last inserted ID */
        public function lastInsertId() {
            return mysqli_insert_id($this->link);
        }

        /** Calls mysqli::real_escape_string, returns escaped string */
        public function escapeString($str) {
            return $this->link->real_escape_string($str);
        }
        
        private function prepare($query)
        {
            $this->stmt = mysqli_prepare($this->link, $query);
            if (!$this->stmt) {
                Throw(new Exception($this->link->error, $this->link->errno));
            }
        }

        private function getType($var) {
            if (is_string($var)) return 's';
            if (is_float($var)) return 'd';
            if (is_int($var)) return 'i';
            return 'b';
        }
    }
?>