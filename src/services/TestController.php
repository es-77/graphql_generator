<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\services\{
    HelperFunctions,
    DatabaseConnection,
    TableColumnsInfo,
    GraphQlGenerator
};

class TestController extends Controller
{
    use HelperFunctions,DatabaseConnection,TableColumnsInfo,GraphQlGenerator;

    public $pdo = '';
    public $table = [];
    public $tableColumnsInfo = [];

    public function index()
    {
        $arrayOfPDOAndTable = $this->databaseConnection();
        $this->pdo = $arrayOfPDOAndTable['pdo'];
        $this->table = $arrayOfPDOAndTable['tables'];
        $this->tableColumnsInfo = $this->tableInfo($this->table,$this->pdo);
        $this->generateGraphQl($this->tableColumnsInfo,$appendPath='C:\laragon\www\my-laravel-app\app\graphql',$folderName='testql');
        dd($this->tableColumnsInfo);

    }

}


