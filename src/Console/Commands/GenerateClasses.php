<?php

namespace Emmanuelsaleem\Graphqlgenerator\Console\Commands;

use Illuminate\Console\Command;
use Emmanuelsaleem\Graphqlgenerator\Services\{
    HelperFunctions,
    DatabaseConnection,
    TableColumnsInfo,
    GraphQlGenerator
};

class GenerateClasses extends Command
{
    use HelperFunctions, DatabaseConnection, TableColumnsInfo, GraphQlGenerator;

    protected $signature = 'generate:graph-ql-query';
    protected $description = 'Generate Controller, Model, Request, Resource, Migration, Seeder, and Factory based on user input';
    protected $columnsName = [];
    protected $entityName = '';

    public $pdo = '';
    public $table = [];
    public $tableColumnsInfo = [];

    public function handle()
    {
        $path = $this->ask('Give the path where you want to add the generated code (e.g., C:/laragon/www/my-laravel-app/app/graphql)');
        $folderName = $this->ask('Give the folder name (default: graphql)', 'graphql');

        $arrayOfPDOAndTable = $this->databaseConnection();
        $this->pdo = $arrayOfPDOAndTable['pdo'];
        $this->table = $arrayOfPDOAndTable['tables'];
        $this->tableColumnsInfo = $this->tableInfo($this->table,$this->pdo);
        $this->generateGraphQl($this->tableColumnsInfo,$path,$folderName);
        dd($this->tableColumnsInfo);
    }
}
