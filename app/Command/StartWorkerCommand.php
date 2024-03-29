<?php
declare(strict_types=1);

namespace App\Command;

use Fyre\Command\Command;
use Fyre\Console\Console;
use Fyre\Queue\Worker;
use RuntimeException;

use function pcntl_fork;

/**
 * StartWorkerCommand
 */
class StartWorkerCommand extends Command
{

    protected string|null $name = 'Start Worker Command';

    protected string $description = 'This command will start a new queue worker.';

    /**
     * Run the command.
     * @param array $arguments The command arguments.
     * @return int|null The exit code.
     */
    public function run(array $arguments = []): int|null
    {
        $pid = pcntl_fork();

        if ($pid === -1) {
            throw new RuntimeException('Unable to fork process');
        }

        if ($pid) {
            Console::write('Worker started on PID: '.$pid, [
                'foreground' => 'cyan'
            ]);
        } else {
            $worker = new Worker($arguments);
            $worker->run();
        }

        return static::CODE_SUCCESS;
    }

}
