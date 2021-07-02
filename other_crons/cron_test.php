<?php
$date = date('y.m.d H:i:s');
$data = "Hello World $date\n";
$test_handle = fopen('test.txt', 'a+');
fwrite($test_handle, $data);
fclose($test_handle);

/**
 * for VBS - (window script)
 * https://ru.stackoverflow.com/questions/438543/%D0%92%D1%8B%D0%BF%D0%BE%D0%BB%D0%BD%D0%B5%D0%BD%D0%B8%D0%B5-php-%D1%81%D0%BA%D1%80%D0%B8%D0%BF%D1%82%D0%B0-%D1%81-%D0%BF%D0%BB%D0%B0%D0%BD%D0%B8%D1%80%D0%BE%D0%B2%D1%89%D0%B8%D0%BA-%D0%B7%D0%B0%D0%B4%D0%B0%D1%87-%D0%BD%D0%B0-windows
 * 
 * for openServer
 * %progdir%\modules\php\%phpdriver%\php-cgi.exe -c %progdir%\modules\php\%phpdriver%\PHP_7.1-x64_php.ini -q -f %sitedir%\zvartnoc\cron_test.php
 * 
 * %progdir%\modules\php\%phpdriver%\php-cgi.exe -c %progdir%\modules\php\%phpdriver%\PHP_7.1-x64_php.ini -q -f %sitedir%\zvartnoc\index.php
 * 
 * %progdir%\modules\wget\bin\wget.exe -q --no-cache http://zvartnox/cron_test_hello.php -O %progdir%\userdata\temp\temp.txt
 *
 * for batfile
 * "C:\ospanel\OSPanel\modules\php\PHP_7.1-x64\php-win.exe" -f C:\ospanel\OSPanel\domains\zvartnoc\cron_test_hello.php
 * pause
 * 
 * 
 */