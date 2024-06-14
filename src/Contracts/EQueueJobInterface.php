<?php

namespace EQueue\Contracts;

interface EQueueJobInterface
{
    public function getUuid(): string;

    public function getEntityId(): string;

    public function handle(): void;
}
