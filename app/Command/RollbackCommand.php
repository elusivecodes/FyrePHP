<?php
declare(strict_types=1);

namespace App\Command;

use Fyre\Command\Command;
use Fyre\Console\Console;
use Fyre\Migration\MigrationRunner;

/**
 * RollbackCommand
 */
class RollbackCommand extends Command
{

    protected string|null $name = 'Rollback Command';

    protected string $description = 'This command will perform rollbacks.';

    /**
     * Run the command.
     * @param array $arguments The command arguments.
     * @return int|null The exit code.
     */
    public function run(array $arguments = []): int|null
    {
        MigrationRunner::rollback($arguments['version'] ?? null);

        return static::CODE_SUCCESS;
    }

}
