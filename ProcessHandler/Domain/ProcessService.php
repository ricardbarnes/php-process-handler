<?php

namespace Core\Shared\Libs\ProcessHandler\Domain;

use Core\Shared\Libs\ProcessHandler\Domain\Process;
use DateTime;

class ProcessService implements ProcessRepository
{
    private ProcessRepository $repository;

    public function __construct(ProcessRepository $repository)
    {
        $this->repository = $repository;
    }

    public function insert(Process $process): ?Process
    {
        return $this->repository->insert($process);
    }

    public function update(Process $process): ?Process
    {
        return $this->repository->update($process);
    }

    public function find(string $class): ?Process
    {
        $process = $this->repository->find($class);

        if ($process === null) {
            $process = $this->getDefault($class);
            $this->insert($process);
        }

        $process->setService($this);
        return $process;
    }

    public function getDefault(string $class): Process
    {
        return Process::create($class, false, new DateTime(), new DateTime(), $this);
    }
}
