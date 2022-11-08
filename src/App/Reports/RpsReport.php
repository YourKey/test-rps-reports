<?php

namespace App\Reports;

use DateTime;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use TestTask\CallDtoSpl;

class RpsReport
{
    /**
     * Посчитаем посекундную нагрузку
     * @param CallDtoSpl $dto
     * @return array
     * @throws Exception
     */
    public function getRps(CallDtoSpl $dto): array
    {
        $dates = $this->getRangeDate($dto);
        $rps = array_fill($dates['start'], $dates['end'] - $dates['start'] + 1, 0);

        foreach ($dto->getItem() as $item) {
            for ($i = 0; $i < $item->getDuration(); $i++) {
                $time = strtotime($item->getStartDateTime()) + $i;
                if (!isset($rps[$time])) break;
                $rps[$time]++;
            }
        }

        return $rps;
    }

    /**
    * Получим начало и конец времени для отчета
    * @param CallDtoSpl $dto
    * @return array
    * @throws Exception
    */
    #[ArrayShape(['start' => "int", 'end' => "int"])]
    public function getRangeDate(CallDtoSpl $dto): array
    {
        $start = $end = null;
        foreach ($dto->getItem() as $item) {
            $time = strtotime($item->getStartDateTime());
            if (!$time) throw new Exception("Некорректная дата: {$item->getStartDateTime()}");

            if ($time < $start || !$start) $start = $time;
            if ($time+$item->getDuration() > $end || !$end) $end = $time+$item->getDuration();
        }

        $end_day = (new DateTime)->setTimestamp($start)
            ->setTime(23, 59, 59, 59)
            ->getTimestamp();
        if ($end > $end_day) $end = $end_day;

        return ['start' => $start, 'end' => $end];
    }
}
