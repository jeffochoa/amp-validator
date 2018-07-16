<?php

namespace App\Commands;

use Zttp\Zttp;
use App\Command;
use App\Validator;
use League\Csv\Reader;
use League\Csv\Writer;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Scheduling\Schedule;
use Symfony\Component\Console\Helper\TableSeparator;

class BatchValidatorCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'validate-batch {path}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Validate a batch of AMP pages from a CSV file.';

    const SUMMARY_IDENTIFIER_WORD = 'AmpSummary';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() : void
    {
        $path = $this->argument('path');

        if ($this->isSummarizedFile($path)) {
            $this->warn('You are using a summarized CSV file');
            if ($this->confirm('Do you want to continue?')) {
                $this->respondWithGeneralReport($path);
            }
            $this->info('You can find more help here: https://support.google.com/webmasters/answer/6328309');
            $this->info('Please download the CSV report for a specific error from GWT and try again. (https://www.google.com/webmasters/tools/accelerated-mobile-pages)');
            exit;
        }

        $this->continueWithDetailedReport($path);
    }

    protected function continueWithDetailedReport(string $path) : void
    {
        $links = $this->getRecordsFromFile($path);

        $rows = $this->runLinksValidation($links);

        if (empty($rows)) {
            $this->line('<fg=black;bg=green>No errors Found!</>');
            exit();
        }

        $option = $this->askForOutputFormat();

        switch ($option) {
            case 0:
                $this->outputErrorsReportToCsv($rows);
                break;
            case 1:
                $this->outputSummarizedTable($rows);
                break;
            case 2:
            $this->outputExtendedReport($rows);
        }

        $this->info(PHP_EOL.'Total <fg=black;bg=green> ' . count($links) .' URLs processed </> - <fg=white;bg=red> (' . count($rows) . ' errors found) </>');
    }

    protected function askForOutputFormat()
    {
        return $this->menu('Please select an output format', [
                'Export to CSV file',
                'In console (summarized)',
                'In console (extended)'
            ])
            ->setBackgroundColour('green')
            ->setForegroundColour('black')
            ->setExitButtonText('Cancel')
            ->open();
    }

    protected function runLinksValidation(array $links) : array
    {
        $bar = $this->output->createProgressBar(count($links));

        $this->info('Running AMP validation');

        $rows = [];

        foreach ($links as $link) {
            $validator = Validator::make()->validate($link);
            foreach ($validator->errors()->toArray() as $error) {
                $row = array_merge(['link' => $link], $error);
                $row['preview'] = 'https://search.google.com/test/amp?url='.$row['link'];
                $rows[] = $row;
            }
            $bar->advance();
        }

        return $rows;
    }

    protected function outputExtendedReport(array $rows) : void
    {
        $columns = array_keys(Arr::first($rows));
        $columns = Arr::first(array_keys($rows));

        $rows = collect($rows)->map(function ($row) {
            return collect($row)->map(function ($value, $key) {
                return [$key, $value];
            })
            ->push(new TableSeparator())->values();
        })->flatten(1)->toArray();
        $this->table(['Key', 'Value'], $rows);
    }

    protected function outputSummarizedTable(array $rows) : void
    {
        $columns = ['link', 'code'];
        $rows = array_map(function ($row) use ($columns) {
            return Arr::only($row, $columns);
        }, $rows);
        $this->table($columns, $rows);
    }

    private function getRecordsFromFile(string $path): array
    {
        $reader = Reader::createFromPath($path, 'r');
        $reader->setHeaderOffset(0);
        $records = [];
        foreach ($reader->getRecords() as $row) {
            $records[] = $row['AMP URL'];
        }
        return $records;
    }

    protected function isSummarizedFile(string $path) : bool
    {
        return Str::contains($path, self::SUMMARY_IDENTIFIER_WORD);
    }

    protected function respondWithGeneralReport($path) : void
    {
        $reader = Reader::createFromPath($path, 'r')->setHeaderOffset(0);
        $rows = [];
        foreach ($reader->getRecords() as $error) {
            $rows[] = $error;
        }
        $this->table($reader->getHeader(), $rows);
    }

    protected function outputErrorsReportToCsv($rows) : void
    {
        $this->task('Creating report.', function () use ($rows) {
            try {
                $date = date('Y-m-d-Hi');
                $writer = Writer::createFromPath($path = __DIR__.'/../../storage/report_'.$date.'.csv', 'w+');
                $writer->insertOne(array_keys(Arr::first($rows)));
                $writer->insertAll($rows);
                $this->info(PHP_EOL.'<fg=black;bg=green> Done! </> You can find your report in: '.$path);
                return true;
            } catch (\Throwable $e) {
                return false;
            }
        });
    }
}
