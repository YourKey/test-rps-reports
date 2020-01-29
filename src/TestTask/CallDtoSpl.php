<?php

namespace TestTask;

final class  CallDtoSpl extends \SplObjectStorage
{
    private $items;

    public function setItem(CallDto $dto): void
    {
        $this->items[] = $dto;
    }

    public function getItem() {
        return $this->items;
    }
}
