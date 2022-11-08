<?php

require_once __DIR__.'/classLoader.php';
define("ROOT_PATH", dirname(__FILE__));

echo "<div><a href='?rps-report'>Отчет RPS</a></div>";
echo "<div><a href='?rpm-report'>Отчет RPM</a></div>";

if (isset($_GET['rps-report'])) {
    echo "<h1>Отчет 1</h1>";
    echo (new \App\Controllers\ReportController())->rpsReport();
}
if (isset($_GET['rpm-report'])) {
    echo "<h1>Отчет 2</h1>";
    echo (new \App\Controllers\ReportController())->rpmReport();
}
