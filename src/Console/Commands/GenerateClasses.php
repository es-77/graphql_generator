<?php

namespace Emmanuelsaleem\Graphqlgenerator\Console\Commands;

use Illuminate\Console\Command;
use Emmanuelsaleem\Graphqlgenerator\Services\{
    HelperFunctions,
    DatabaseConnection,
    TableColumnsInfo,
    GraphQlGenerator
};

use function Laravel\Prompts\text;
use function Laravel\Prompts\table;
use function Laravel\Prompts\multiselect;

class GenerateClasses extends Command
{
    use HelperFunctions, DatabaseConnection, TableColumnsInfo, GraphQlGenerator;

    protected $signature = 'generate:graph-ql-query';
    protected $description = 'create graphql query ';
    protected $columnsName = [];
    protected $selectedSkipTables = [];
    protected $entityName = '';

    public $pdo = '';
    public $table = [];
    public $tableColumnsInfo = [];

    public function handle()
    {
        $path = text(
            label: 'Give the path where you want to add the generated code? (e.g., C:/laragon/www/my-laravel-app/app/graphql)',
            placeholder: 'C:/laragon/www/my-laravel-app/app/graphql',
            hint: '(e.g., C:/laragon/www/my-laravel-app/app/graphql)',
            required: 'Your Path is required.'
        );
        $folderName = text(
            label: 'Give the folder name? (graphql)',
            placeholder: 'example graphql',
            default: 'graphql',
            required: 'Your folder name is required.'
        );
 

        $arrayOfPDOAndTable = $this->databaseConnection();
        $this->pdo = $arrayOfPDOAndTable['pdo'];
        $this->table = $arrayOfPDOAndTable['tables'];

        $tableRows = array_map(fn($tableName) => [$tableName], $this->table);

        table(
            headers: ['Table Name'],
            rows: $tableRows
        );

        $this->selectedSkipTables = multiselect(
            label: 'Select tables to Skiip generate GraphQL queries for',
            options: $this->table,
            // required: true
        );
        
        
        $arrayOfPDOAndTable = $this->databaseConnection();
        $this->pdo = $arrayOfPDOAndTable['pdo'];
        $this->table = $arrayOfPDOAndTable['tables'];
        $this->tableColumnsInfo = $this->tableInfo($this->table,$this->pdo);
        $this->generateGraphQl($this->tableColumnsInfo,$path,$folderName);
        info('Surprise the world by using this package!');
        info('install this package composer require nuwave/lighthouse');
        exec('composer require nuwave/lighthouse', $output, $returnVar);

        if ($returnVar !== 0) {
            $this->error("An error occurred while installing the package. composer require nuwave/lighthouse");
        } else {
            $this->info("Package installed successfully.");
        }
    }
}
