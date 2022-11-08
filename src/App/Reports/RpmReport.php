<?php

namespace App\Reports;

use DateTime;
use Exception;
use TestTask\CallDtoSpl;
use TestTask\MaxCallPerMinutesDto;
use TestTask\MaxCallPerMinutesDtoSpl;

class RpmReport
{
    /**
     * Посчитаем поминутную нагрузку
     * @param array $rps
     * @return array
     */
    public function getRpm(array $rps): array
    {
        $rpm = [];
        foreach ($rps as $time => $requests) {
            $datetime = (new DateTime)->setTimestamp($time);
            $time = $datetime
                ->setTime($datetime->format('H'), $datetime->format('i'))
                ->getTimestamp();

            if (!isset($rpm[$time])) {
                $rpm[$time] = $requests;
            } else {
                if ($requests > $rpm[$time]) $rpm[$time] = $requests;
            }
        }

        return $rpm;
    }

    /**
     * Получим поминутное время в течение суток
     * @param CallDtoSpl $dto
     * @return array
     * @throws Exception
     */
    public function fillDayEveryMinute(CallDtoSpl $dto): array
    {
        $items = $dto->getItem();
        $start = array_shift($items)->getStartDateTime();
        $start_min = (new DateTime($start))
            ->setTime(0, 0)
            ->format('Y-m-d H:i:s');
        $end_min = (new DateTime($start))
            ->setTime(23, 59, 59, 59)
            ->format('Y-m-d H:i:s');

        $start_time = strtotime($start_min);
        $end_time = strtotime($end_min);

        $range = [];
        for ($i = $start_time; $i <= $end_time; $i+=60) {
            $range[$i] = 0;
        }

        return $range;
    }

    /**
     * Посчитаем максимальную нагрузку в минуту
     * @param array $range
     * @param array $rpm
     * @return MaxCallPerMinutesDtoSpl
     */
    public function fillMaxCallPerMinutes(array $range, array $rpm): MaxCallPerMinutesDtoSpl
    {
        $requests = new MaxCallPerMinutesDtoSpl();
        foreach ($range as $time => $calls) {
            if (isset($rpm[$time])) $calls = $rpm[$time];

            $datetime =  (new DateTime)->setTimestamp($time);

            $requests->setItem(
                (new MaxCallPerMinutesDto())
                    ->setDateTime($datetime)
                    ->setCallsCount($calls)
            );
        }

        return $requests;
    }
}
