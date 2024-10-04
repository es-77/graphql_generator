<?php

namespace Emmanuelsaleem\Graphqlgenerator\services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use PDO;
use PDOException;


trait TableColumnsInfo
{
    public function tableInfo($tables,$pdo)
    {
        $data = [];
        foreach ($tables as $key => $table_name) {
            
            if (in_array($table_name,$this->selectedSkipTables) == true) {
                continue;
            }
        
            $table_name_columns_names = [];
            $stmt = $pdo->query("SHOW COLUMNS FROM $table_name");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $table_name_columns_names['table_name'] = trim($table_name);
        
            $table_columns = [];
        
            foreach ($columns as $column) {
                $column_name = $column['Field'];
                $column_type = $column['Type'];
                $column_null = $column['Null'];
        
                $foreign_key_query = "
                    SELECT
                    REFERENCED_TABLE_NAME,
                    REFERENCED_COLUMN_NAME
                    FROM
                    information_schema.KEY_COLUMN_USAGE
                    WHERE
                    TABLE_NAME = '$table_name' AND
                    COLUMN_NAME = '$column_name' AND
                    REFERENCED_TABLE_NAME IS NOT NULL
                    ";
        
                $foreign_key_stmt = $pdo->query($foreign_key_query);
                $foreign_key = $foreign_key_stmt->fetch(PDO::FETCH_ASSOC);
        
                $unique_query = "
                    SELECT
                    COUNT(*) as count
                    FROM
                    information_schema.statistics
                    WHERE
                    table_name = '$table_name' AND
                    column_name = '$column_name' AND
                    non_unique = 0
                    ";
        
                $unique_stmt = $pdo->query($unique_query);
                $unique = $unique_stmt->fetch(PDO::FETCH_ASSOC);
        
                $table_column = [
                    'column_name' => $column_name,
                    'column_type' => $column_type,
                    'column_null' => $column_null,
                    'is_unique' => ($unique['count'] > 0)
                ];
        
                if (!empty($foreign_key)) {
                    $table_column['foreign_key'] = [
                        'table_name' => $foreign_key['REFERENCED_TABLE_NAME'],
                        'column_name' => $foreign_key['REFERENCED_COLUMN_NAME']
                    ];
                }
        
                $table_columns[] = $table_column;
            }
        
            $table_name_columns_names['columns'] = $table_columns;
            $data[$key] = $table_name_columns_names;
        }
        return $data;
    }
}
