<?php

class dbh extends Dom {

        private $servername;
        private $username;
        private $password;
        private $dbname;

        protected function connect (){
#<<<<<<< HEAD
                $this->servername = "127.0.0.1";
                $this->username = "admin";
                $this->password = "taco";
                $this->dbname = "asatech";
#=======
#                $this->servername = "localhost";
#                $this->username = "";
#                $this->password = "";
#                $this->dbname = "";
#>>>>>>> d132bb35b5cff5974bbefa538662f5851bfbd402

                $conn = new mysqli($this->servername, $this->username,$this->password, $this->dbname);
                return $conn;




        }

}
