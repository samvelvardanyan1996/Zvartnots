var request = new ActiveXObject("Msxml2.XMLHTTP.3.0"); 
var url = "http://zvartnoc/cron_test_hello.php";
request.open("GET", url);
request.send(null);
WScript.Sleep(500); // чтобы скрипт не завершился, прежде чем запрос уйдет в сеть
// WScript.echo("Done!!!");