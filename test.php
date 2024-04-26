<?php
    // $a = ['id' => 123];
    // echo("asd/{$a['id']}");

    class test {
        protected $a = '123';

        function __construct()
        {
            // echo "213123";
            $this->log();
        }

        function log() : string {
            $this->a = 12334456;
            echo($this->a);


            // echo 'day la noi dung nam trong ham log duwoc goi qua construc';
            return '';
        }
    }

    new test();
    
?>