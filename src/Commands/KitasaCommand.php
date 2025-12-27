<?php

namespace Emanate\Kitasa\Commands;

use Illuminate\Console\Command;

class KitasaCommand extends Command
{
    public $signature = 'kitasa';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
