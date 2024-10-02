<?php

namespace Emmanuelsaleem\Graphqlgenerator\services;

trait GraphQlGenerator
{
    public function generateGraphQl($data,$appendPath,$folderName){

        $folderName = $this->replaceSpacesWithUnderscore($folderName);
        $final_files = [];

        foreach ($data as $table_info) {
            $column_name = '';
            $validation_column_name = '';
            $validation_column = '';
            $relaship_belongsTo = '';
            foreach ($table_info['columns'] as $column) {
                $validation_column = "";
                if ($column['is_unique'] == 1) {
                    $validation_column .= "
                    @rules(apply: [\"unique:" . $table_info['table_name'] . "," . $column['column_name'] . "\"])
                    ";
                }
                if ($column['column_null'] == 'NO') {
                    $validation_column .= "
                    @rules(apply: [\"required\"])
                    ";
                }
                $column_name .= "
                $column[column_name]: String";
                $validation_column_name .= "
                $column[column_name]: String " . $validation_column;
                if ($column !== end($table_info['columns'])) {
                    $column_name .= ",";
                    $validation_column_name .= ",";
                }
                if (isset($column['foreign_key'])) {
                    $relaship_belongsTo .= "
                    " . $this->camelToSingular($this->convertToUpperCamelCase($column['foreign_key']['table_name'])) . ": " . $this->camelToSingular($this->convertToUpperCamelCase($column['foreign_key']['table_name'])) . "! @belongsTo(relation: \"" . $this->convertFirstLetterToLower($this->camelToSingular($this->convertToUpperCamelCase($column['foreign_key']['table_name']))) . "\")";
                }
            }
            // mutation here
            $type_mutation = "   type Mutation {
            create" . $this->camelToSingular($this->convertToUpperCamelCase($table_info['table_name'])) . "(" . $validation_column_name . "): " . $this->camelToSingular($this->convertToUpperCamelCase($table_info['table_name'])) . "! @create
            update" . $this->camelToSingular($this->convertToUpperCamelCase($table_info['table_name'])) . "(" . $column_name . "): " . $this->camelToSingular($this->convertToUpperCamelCase($table_info['table_name'])) . " @update
            delete" . $this->camelToSingular($this->convertToUpperCamelCase($table_info['table_name'])) . "(id: ID! @whereKey): " . $this->camelToSingular($this->convertToUpperCamelCase($table_info['table_name'])) . " @delete
            }";
            // mutation here

            // type of here
            $type_of = "type " . $this->camelToSingular($this->convertToUpperCamelCase($table_info['table_name'])) . " {" . $column_name . $relaship_belongsTo . "}";
            // type of here

            // query for here 
            $type_query = "
            extend type Query {
            " . $this->convertToUpperCamelCase($table_info['table_name']) . ": [" . $this->camelToSingular($this->convertToUpperCamelCase($table_info['table_name'])) . " !]! @paginate
            " . $this->camelToSingular($this->convertToUpperCamelCase($table_info['table_name'])) . " (id: ID @eq): " . $this->camelToSingular($this->convertToUpperCamelCase($table_info['table_name'])) . "  @find
            }
            ";

            $final_files[$this->camelToSingular($this->convertToUpperCamelCase($table_info['table_name'])) . '.graphql'] = $type_query . "\n" . $type_mutation . "\n" . $type_of;
        }

        foreach ($final_files as $key => $value) {
            $HasMany = $this->getValueBeforeDot($key);
            $searchText = '@belongsTo';
            $line = $this->searchAndGetLine($value, $searchText);

            if ($line !== null) {
                $array_key = $this->getTheValueAfterColun($line);
                if (!$array_key == false) {
                    $array_key_path = $array_key . ".graphql";
                    $searchKeyword_string = 'type ' . $array_key . ' {';
                    $newText = $HasMany . ': [' . $HasMany . '!]! @hasMany(relation: "' . $this->convertFirstLetterToLower($this->convertToPlural($HasMany)) . '")';
                    $outputString = $this->searchAndAppend($final_files[$array_key_path], $searchKeyword_string, $newText);
                    $final_files[$array_key_path] = $outputString;
                } else {
                    // echo "no belog to";
                }
            } else {
                // echo "Line not found.\n";
            }
        }
        $this->createFolderAndAppendFiles($folderName, $final_files, $appendPath);
        echo "done";
        die;

    }
}
