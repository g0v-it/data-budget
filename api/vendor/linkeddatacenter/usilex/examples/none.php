<?php
echo "Hello world";
echo "\n<pre>";
echo "\nmemory_get_usage: ".memory_get_usage(false);
echo "\nscript execution time:".(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]);
echo "<pre>";
