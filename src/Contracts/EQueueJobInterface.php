<?php

namespace EQueue\Contracts;

interface EQueueJobInterface
{
    public function getUuid(): string;

    public function getEntityId(): int;

    public function handle(): void;
}
