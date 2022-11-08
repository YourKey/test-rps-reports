<?php

namespace App\Reports;

use Exception;
use TestTask\CallDto;
use TestTask\CallDtoSpl;
use TestTask\CallsReport;
use TestTask\MaxCallPerMinutesDtoSpl;

class Report extends CallsReport
{
    protected RpsReport $rpsReport;
    protected RpmReport $rpmReport;

    public function __construct()
    {
        $this->rpsReport = new RpsReport();
        $this->rpmReport = new RpmReport();
    }

    /**
     * @throws Exception
     */
    public function fillCallDtoSpl(string $json): CallDtoSpl
    {
        $json = file_get_contents($json);
        if (!$json) throw new Exception("Файл $json не найден");
        $items = json_decode($json);
        if (!$items) throw new Exception("Файл $json пустой");

        $list = new CallDtoSpl();

        foreach ($items as $item) {
            $duration = $item->duration_seconds;
            if ($duration < 0) throw new Exception("Некорректная длительность звонка");

            $list->setItem(
                (new CallDto())
                    ->setStartDateTime($item->start_date_time)
                    ->setDuration($duration)
            );
        }
        return $list;
    }

    /**
     * Выводит массив, в котором представлена информация по меткам времени,
     * в которых был превышен (>) лимит Calls::$maxCallPerOneSecond
     *
     * @param CallDtoSpl $dto
     * @return array - массив вида unixtimestamp => int количество звонков
     * @throws Exception
     */
    protected function getOverLoadCalls(CallDtoSpl $dto): array
    {
        $rps = $this->rpsReport->getRps($dto);
        return array_filter($rps, fn($requests) => $requests > static::$maxCallPerOneSecond);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function getMaxCallPerMinutes(CallDtoSpl $dto): MaxCallPerMinutesDtoSpl
    {
        $rps = $this->rpsReport->getRps($dto);
        $rpm = $this->rpmReport->getRpm($rps);
        $min_range = $this->rpmReport->fillDayEveryMinute($dto);
        return $this->rpmReport->fillMaxCallPerMinutes($min_range, $rpm);
    }
}
