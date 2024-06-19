<?php
$cart = [
    ['price' => 123, "qty" => 1],
    ['price' => 22, "qty" => 1],
];
$a = array_sum(
    array_map(fn($i) => $i['price'] * $i['qty'], $cart),
);

echo($a);

exit();
    $a = ['id' => 123];
    // echo("asd/{$a['id']}");

    // echo(date("Y-m-d H:i:s",1716965369));

    // test run js in file php
    $js_log = shell_exec('node demo.js');
    echo($js_log."\n");
    exit;

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