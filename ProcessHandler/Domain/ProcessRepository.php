<?php

namespace Core\Shared\Libs\ProcessHandler\Domain;

interface ProcessRepository
{
    public function insert(Process $process): ?Process;

    public function update(Process $process): ?Process;

    public function find(string $class): ?Process;
}
