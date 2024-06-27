<?php

namespace MtrDesign\Krait\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;


#[AsCommand(name: 'krait:table')]
class CreateTableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'krait:table {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates all resources needed for a Krait table.';

    public function handle() {
        $this->callSilent('krait:table-vue-component', [
            'name' => $this->argument('name')
        ]);

        $this->callSilent('krait:table-class', [
            'name' => $this->argument('name')
        ]);
        $this->callSilent('krait:table-controller', [
            'name' => $this->argument('name')
        ]);

        $this->callSilent('krait:refresh');
    }
}
