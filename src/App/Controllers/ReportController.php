<?php
namespace App\Controllers;

use App\Reports\Report;
use Exception;

class ReportController {

    private Report $report;
    private string $file;

    public function __construct()
    {
        $this->report = new Report();
        $this->file = ROOT_PATH.'/data.json';
    }

    public function rpsReport(): string
    {
        try {
            $items = $this->report->fillCallDtoSpl($this->file);
            return $this->report->buildServerOverLoadTable($items);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function rpmReport(): string
    {
        try {
            $items = $this->report->fillCallDtoSpl($this->file);
            return $this->report->buildServerLoadTable($items);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
