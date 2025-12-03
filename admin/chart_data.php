<?php
header("Content-Type: application/json");

$command = escapeshellcmd("python python/chart_data.py");
$output = shell_exec($command);

echo $output;
