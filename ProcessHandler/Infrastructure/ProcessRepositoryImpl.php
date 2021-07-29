<?php

namespace Core\Shared\Libs\ProcessHandler\Infrastructure;

use Core\Shared\Libs\ProcessHandler\Domain\Process;
use Core\Shared\Libs\ProcessHandler\Domain\ProcessRepository;
use DateTime;

class ProcessRepositoryImpl implements ProcessRepository
{
    private function getDateTime(string $dateTime): DateTime
    {
        return DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);
    }

    private function buildProcessModel(Process $process): ProcessModel
    {
        $processModel = new ProcessModel();
        $processModel->{ProcessModel::FIELD_CLASS} = $process->getClass();
        $processModel->{ProcessModel::FIELD_IS_RUNNING} = $process->isRunning();
        return $processModel;
    }

    private function findRaw(string $class): ?ProcessModel
    {
        return ProcessModel::where(ProcessModel::FIELD_CLASS, $class)->first();
    }

    public function insert(Process $process): ?Process
    {
        $processModel = new ProcessModel();
        $processModel->{ProcessModel::FIELD_CLASS} = $process->getClass();
        $processModel->{ProcessModel::FIELD_IS_RUNNING} = $process->isRunning();

        if ($processModel->save()) {
            return $process;
        }

        return null;
    }

    public function update(Process $process): ?Process
    {
        $processModel = $this->findRaw($process->getClass());

        if ($processModel !== null) {
            $processModel->delete();
        }

        $newProcessModel = $this->buildProcessModel($process);

        if ($newProcessModel->save()) {
            return $process;
        }

        return null;
    }

    public function find(string $class): ?Process
    {
        $processModel = $this->findRaw($class);

        if ($processModel !== null) {
            return Process::create(
                $processModel->{ProcessModel::FIELD_CLASS},
                $processModel->{ProcessModel::FIELD_IS_RUNNING},
                $this->getDateTime($processModel->{ProcessModel::UPDATED_AT}),
                $this->getDateTime($processModel->{ProcessModel::CREATED_AT})
            );
        }

        return null;
    }
}
