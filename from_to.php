<?php
$inputFilePath = 'C:\\Users\\user\\Documents\\Firs Github\\to-pivot-api\\tbl.xlsx';
$command = "python script.py \"$inputFilePath\" 2>&1";
$output = shell_exec($command);

echo "<pre>starting up..........</pre>";
echo "<pre>$output</pre>";
echo "<pre>ending process.................</pre>";
