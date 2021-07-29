<?php

namespace Core\Shared\Libs\ProcessHandler\Domain;

use DateTime;

class Process
{
    private string $class;
    private bool $isRunning;
    private ?ProcessService $service;
    private DateTime $updatedAt;
    private DateTime $createdAt;
    private int $startedAtTime;
    private int $timeoutSeconds;

    private function save(self $instance): void
    {
        $this->service->update($instance);
    }

    public static function create(
        string $class,
        bool $isRunning,
        DateTime $updatedAt,
        DateTime $createdAt,
        ?ProcessService $service = null
    ): self
    {
        return new self($class, $isRunning, $updatedAt, $createdAt, $service);
    }

    public function __construct(string $class, $isRunning, $updatedAt, $createdAt, ?ProcessService $service)
    {
        $this->class = $class;
        $this->isRunning = $isRunning;
        $this->service = $service;
        $this->updatedAt = $updatedAt;
        $this->createdAt = $createdAt;
        $this->startedAtTime = time();
    }

    public function reset(): void
    {
        $default = $this->service->getDefault($this->class);
        $this->save($default);
    }

    public function isRunning(): bool
    {
        return $this->isRunning;
    }

    public function hasTimeout(): bool
    {
        return $this->getDeltaTime() >= $this->timeoutSeconds;
    }

    public function notifyStart(): void
    {
        $this->isRunning = true;
        $this->save($this);
    }

    public function notifyEnd(): void
    {
        $this->reset();
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function setService(ProcessService $service): void
    {
        $this->service = $service;
    }

    public function getService(): ProcessService
    {
        return $this->service;
    }

    public function getDeltaTime(): int
    {
        $lastUpdateTime = $this->updatedAt->getTimestamp();
        $currentTime = time();
        return $currentTime - $lastUpdateTime;
    }

    public function getTimeoutSeconds(): int
    {
        return $this->timeoutSeconds;
    }

    public function setTimeoutSeconds(int $timeoutSeconds): void
    {
        $this->timeoutSeconds = $timeoutSeconds;
    }
}
