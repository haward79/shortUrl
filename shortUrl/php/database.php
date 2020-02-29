<?php

    // Parse ini file.
    $iniParse = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/../database.ini', true);

    // Save parsed result.
    define('dbHostname', $iniParse['mysql']['host']);
    define('dbUsername', $iniParse['mysql']['username']);
    define('dbPassword', $iniParse['mysql']['password']);
    define('dbDatabase', $iniParse['mysql']['database']);

    function mysqlQuery(string $queryStr)
    {
        $dbCon = mysqli_connect(dbHostname, dbUsername, dbPassword, dbDatabase);

        if($dbCon !== false)
        {
            $dbRetrieve = mysqli_query($dbCon, $queryStr);
            mysqli_close($dbCon);
        }
        else
            $dbRetrieve = false;

        return $dbRetrieve;
    }

