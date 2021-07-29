# php-process-handler
A small library to avoid process overlapping. Working on MongoDB with the Jenssegers library. But you may replace the infrastructure to use it in any other DBs.

Use example:

    /**
     * @throws Exception
     */
    public function __invoke(): void
    {
        $process = $this->processService->find(get_class($this));
        $process->setTimeoutSeconds(666);

        if ($process->hasTimeout()) {
            $process->reset();
        }

        if ($process->isRunning()) {
            throw new Exception('Process is already running.');
        }

        $process->notifyStart();
        $this->goToMyStartingMethod();
        $process->notifyEnd();
    }
