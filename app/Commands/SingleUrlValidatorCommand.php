<?php

namespace App\Commands;

use Zttp\Zttp;
use App\Command;
use App\Validator;
use Illuminate\Console\Scheduling\Schedule;
use Symfony\Component\Console\Helper\TableSeparator;

class SingleUrlValidatorCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'validate {url}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Test a single AMP url.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->task('Validating AMP.', function () {
            $validator = Validator::make()->validate($this->argument('url'));
            $this->line('');
            if ($validator->hasErrors()) {
                foreach ($validator->errors()->toArray() as $error) {
                    $rows[] = array_merge(['link' => $this->argument('url')], $error);
                }

                $rows = collect($rows)->map(function ($row) {
                    return collect($row)->map(function ($value, $key) {
                        return [$key, $value];
                    })->push(new TableSeparator())->values();
                })->flatten(1);

                $rows = $rows->push(['preview', 'https://search.google.com/test/amp?url='.$this->argument('url')]);
                $this->table(['Key', 'Value'], $rows->toArray());
                exit;
            } else {
                $this->line('<fg=black;bg=green>No errors Found!</>');
            }
            return ! $validator->hasErrors();
        });
    }
}
