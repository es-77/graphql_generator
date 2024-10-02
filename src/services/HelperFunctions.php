<?php

namespace Emmanuelsaleem\Graphqlgenerator\services;

trait HelperFunctions
{

    // this function remove _ under socor
    function snakeToCamel($input)
    {
        $words = explode('_', $input);
        $firstWord = array_shift($words);
        $words = array_map('ucfirst', $words);
        $camelCaseString = $firstWord . implode('', $words);

        return $camelCaseString;
    }

    function camelToSingular($input)
    {
        // convert the camelCase string to words
        preg_match_all('/[A-Z][a-z]+/', $input, $words);

        // get the last word and convert it to its singular form
        $lastWord = end($words[0]);
        if (substr($lastWord, -3) == 'ies') {
            $singularWord = substr($lastWord, 0, -3) . 'y';
        } elseif (substr($lastWord, -1) == 's') {
            $singularWord = substr($lastWord, 0, -1);
        } else {
            $singularWord = $lastWord;
        }

        // replace the last word in the original string with its singular form
        $input = preg_replace('/([A-Z][a-z]+)$/', $singularWord, $input);

        return $input;
    }

    function convertFirstLetterToLower($string)
    {
        if (strlen($string) > 0) {
            $firstChar = substr($string, 0, 1);
            $lowercaseFirstChar = strtolower($firstChar);
            $restOfString = substr($string, 1);
            return $lowercaseFirstChar . $restOfString;
        }

        return $string;
    }

    function convertToPlural($word)
    {
        $irregulars = array(
            'child' => 'children',
            'person' => 'people',
            // Add more irregular word mappings here as needed
        );

        $exceptions = array(
            'sheep' => 'sheep',
            'mouse' => 'mice',
            // Add more exceptional word mappings here as needed
        );

        $lastChar = strtolower(substr($word, -1));
        $lastTwoChars = strtolower(substr($word, -2));
        $lastThreeChars = strtolower(substr($word, -3));

        if (isset($irregulars[$word])) {
            return $irregulars[$word];
        } elseif (isset($exceptions[$word])) {
            return $exceptions[$word];
        } elseif ($lastChar === 'y' && !in_array($lastTwoChars, ['ay', 'ey', 'iy', 'oy', 'uy'])) {
            return substr($word, 0, -1) . 'ies';
        } elseif ($lastTwoChars === 'ch' || $lastTwoChars === 'sh' || $lastChar === 'x' || $lastChar === 's') {
            return $word . 'es';
        } elseif ($lastThreeChars === 'man') {
            return substr($word, 0, -3) . 'men';
        }

        return $word . 's';
    }

    function convertToUpperCamelCase($str)
    {
        $str = str_replace('_', ' ', $str);  // Replace underscore with space
        $str = ucwords($str);               // Capitalize first letter of each word
        $str = str_replace(' ', '', $str);  // Remove spaces
        return $str;
    }

    function replaceSpacesWithUnderscore($string)
    {
        return str_replace(' ', '_', $string);
    }

    function getLastStringAfterSlash($string)
    {
        $lastSlashPosition = strrpos($string, '/');
        if ($lastSlashPosition !== false) {
            return substr($string, $lastSlashPosition + 1);
        } else {
            return $string; // No slash found, return the original string
        }
    }



    function createFileName($str)
    {
        $fileName = $str . '.graphql';
        return $fileName;
    }

    function deleteFileContend($filename)
    {
        // Open the file for writing and truncate its contents
        $fp = fopen($filename, 'w');
        ftruncate($fp, 0);
        fclose($fp);
    }
    function appendFileContend($filename, $newcontent)
    {
        file_put_contents($filename, $newcontent, FILE_APPEND);
    }

    // function createFolderAndAppendFiles($foldername, $files)
    // {
    //     // Check if the folder already exists
    //     if (is_dir($foldername)) {
    //         // If it does, add a suffix and increment the counter until you find a folder name that doesn't exist
    //         $suffix = '_new';
    //         $count = 1;
    //         while (is_dir($foldername . $suffix . $count)) {
    //             $count++;
    //         }
    //         $foldername = $foldername . $suffix . $count;
    //     }

    //     // Create the new folder
    //     mkdir($foldername);

    //     // Loop through each file and append it to the new folder
    //     foreach ($files as $filename => $content) {
    //         $filepath = $foldername . '/' . $filename;
    //         file_put_contents($filepath, $content);
    //     }
    // }

    function createFolderAndAppendFiles($foldername, $files, $appendPath = '')
    {
        $myFolder = $foldername;
        // Append the folder name to the append path, if provided
        if (!empty($appendPath)) {
            $foldername = rtrim($appendPath, '/') . '/' . $foldername;
        }

        // Check if the folder already exists
        if (is_dir($foldername)) {
            // If it does, add a suffix and increment the counter until you find a folder name that doesn't exist
            $suffix = '_new';
            $count = 1;
            while (is_dir($foldername . $suffix . $count)) {
                $count++;
            }
            $foldername = $foldername . $suffix . $count;
            $myFolder = $foldername . $suffix . $count;
        }

        // Create the new folder
        mkdir($foldername);

        // Loop through each file and append it to the new folder
        file_put_contents('./schema.graphql', '');
        foreach ($files as $filename => $content) {
            $filepath = $foldername . '/' . $filename;
            file_put_contents($filepath, $content, FILE_APPEND);
        }
        $import_file = "#import " . $this->getLastStringAfterSlash($foldername) . "/*.graphql";
        file_put_contents($appendPath . "/schema.graphql", $import_file, FILE_APPEND);
    }

    function searchAndAppendWord($folderPath, $searchWord, $newWord)
    {
        // Validate folder path
        if (!is_dir($folderPath)) {
            throw new Exception("Invalid folder path.");
        }

        // Open the folder and iterate through files
        $directory = new RecursiveDirectoryIterator($folderPath);
        $iterator = new RecursiveIteratorIterator($directory);
        $regexIterator = new RegexIterator($iterator, '/^.+\.txt$/i', RecursiveRegexIterator::GET_MATCH);

        foreach ($regexIterator as $file) {
            $filePath = $file[0];
            echo "helo";
            print_r($filePath);
            print_r($regexIterator);
            die;

            // Read the file content
            $content = file_get_contents($filePath);

            print_r($content);

            // Search for the word and append the new word if found
            $updatedContent = str_replace($searchWord, $searchWord . $newWord, $content);

            // Write the updated content back to the file
            file_put_contents($filePath, $updatedContent);
        }
    }

    function getValueBeforeDot($string)
    {
        $dotPosition = strpos($string, '.');
        if ($dotPosition !== false) {
            return substr($string, 0, $dotPosition);
        } else {
            return $string;
        }
    }


    function searchAndGetLine($inputString, $searchText)
    {
        $lines = explode("\n", $inputString);

        foreach ($lines as $line) {
            if (strpos($line, $searchText) !== false) {
                return $line;
            }
        }

        return null; // Line not found
    }
    function getTheValueAfterColun($inputString)
    {
        // Extract the value before the colon
        $colonPosition = strpos($inputString, ':');
        if ($colonPosition !== false) {
            $value = trim(substr($inputString, 0, $colonPosition));
            return $value;
        } else {
            return false;
        }
    }

    function searchAndAppend($inputString, $searchKeyword, $newText)
    {
        $position = strpos($inputString, $searchKeyword);

        if ($position !== false) {
            $position += strlen($searchKeyword);
            $outputString = substr_replace($inputString, ' 
            ' . $newText . ',', $position, 0);
            return $outputString;
        }

        return $inputString;
    }

}
