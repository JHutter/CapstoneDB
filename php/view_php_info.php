<?php

phpinfo();

echo "<br<br>Memory peak usage: ".(memory_get_peak_usage(true)/1024/1024)." MiB\n\n<br>";
echo "<br<br>Memory usage: ".(memory_get_usage(true)/1024/1024)." MiB\n\n";

?>