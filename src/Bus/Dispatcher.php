<?php

namespace MBM\Bus;

use Illuminate\Queue\Jobs\Job;
use MBM\Bus\Model\JobsRegister;

/**
 * Class Dispatcher
 *
 * TODO: Add support for plain jobs
 *
 * @package MBM\Bus
 */
class Dispatcher extends \Illuminate\Bus\Dispatcher
{
    public function dispatch($command)
    {
        if (is_object($command) and ! ($command instanceof AllowDuplicates)) {

            $checksum = $this->getChecksum($this->getCommandData($command));

            if (JobsRegister::find($checksum)) {
                return false;
            }

            JobsRegister::registerJob($this->getJobName($command), $checksum);
        }

        return parent::dispatch($command);
    }

    public function unregister(Job $job)
    {
        return JobsRegister::unregisterJob($this->getChecksum(array_get($job->payload(), 'data')));
    }

    /**
     * Generate dispatched command checksum
     *
     * @param $command
     * @return string
     */
    protected function getChecksum($payload)
    {
        return md5(serialize($payload));
    }

    protected function getCommandData($command) {
        return is_object($command) ? [
            'commandName' => $this->getJobName($command),
            'command'     => serialize(clone $command),
        ] : [
            'commandName' => $this->getJobName($command),
            'command'     => serialize($command),
        ];
    }

    protected function getJobName($command)
    {
        return is_object($command) ? get_class($command) : 'Plain';
    }
}