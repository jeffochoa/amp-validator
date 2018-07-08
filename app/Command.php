<?php

namespace App;

use Symfony\Component\Console\Helper\Table;
use LaravelZero\Framework\Commands\Command as BaseCommand;

class Command extends BaseCommand
{
    /**
     * Write a string as standard output.
     *
     * @param  string  $string
     * @param  string  $style
     * @param  null|int|string  $verbosity
     * @return void
     */
    public function line($string, $style = null, $verbosity = null)
    {
        parent::line(PHP_EOL.$string.PHP_EOL, $style, $verbosity);
    }

    /**
     * Format input to textual table.
     *
     * @param  array   $headers
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $rows
     * @param  string  $tableStyle
     * @param  array   $columnStyles
     * @return void
     */
    public function table($headers, $rows, $tableStyle = 'default', array $columnStyles = [], array $columnWidths = [])
    {
        $this->line(PHP_EOL);
        $table = new Table($this->output);

        if ($rows instanceof Arrayable) {
            $rows = $rows->toArray();
        }

        $table->setHeaders((array) $headers)->setRows($rows)->setStyle($tableStyle);

        foreach ($columnStyles as $columnIndex => $columnStyle) {
            $table->setColumnStyle($columnIndex, $columnStyle);
        }

        if (!empty($columnWidths)) {
            $table->setColumnWidths($columnWidths)->render();
        } else {
            $table->render();
        }
    }
}
