<?php
declare(strict_types=1);

namespace App\Command;

use Fyre\Command\Command;
use Fyre\Console\Console;

/**
 * TestCommand
 */
class TestCommand extends Command
{

    protected string|null $name = 'Test Command';

    protected string $description = 'This is a test command.';

    /**
     * Run the command.
     * @param array $arguments The command arguments.
     * @return int|null The exit code.
     */
    public function run(array $arguments = []): int|null
    {
        Console::write('This is a test command');

        return static::CODE_SUCCESS;
    }

}
