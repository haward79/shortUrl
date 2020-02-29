<?php

    function parseId(string $id)
    {
        $parsed = array(
            'year' => substr($id, 0, 4),
            'month' => substr($id, 4, 2),
            'date' => substr($id, 6, 2),
            'hour' => substr($id, 8, 2),
            'minute' => substr($id, 10, 2),
            'second' => substr($id, 12, 2),
            'no' => substr($id, 14, 3)
        );

        return $parsed;
    }

    function generateId(string $url)
    {
        // Encode url string.
        $url = urlencode($url);

        require_once 'database.php';

        // Check if the url has been shortened.
        $dbRetrieve = mysqlQuery("SELECT id FROM urlMapping WHERE url = '$url';");

        if($dbRetrieve !== false && mysqli_num_rows($dbRetrieve) > 0)
            return mysqli_fetch_row($dbRetrieve)[0];

        // Get max stamp from database.
        $dbRetrieve = mysqlQuery("SELECT MAX(id) FROM urlMapping;");

        if($dbRetrieve !== false && mysqli_num_rows($dbRetrieve) > 0)
        {
            $maxId = mysqli_fetch_row($dbRetrieve)[0];

            // Not the first request at this moment.
            if(substr($maxId, 0, 14) == date('YmdHis'))
            {
                $nextNo = (int)parseId($maxId)['no'] + 1;

                // Too many request at this moment.
                if($nextNo > 999)
                    return '';
                else
                {
                    $id = date('YmdHis') . ((int)$parsed['no'] + 1);
                    mysqlQuery("INSERT INTO urlMapping (id, url) VALUES ('$id', '$url');");
                    return $id;
                }
            }
            // The first request at this moment.
            else if(substr($maxId, 0, 14) < date('YmdHis'))
            {
                $id = date('YmdHis') . '000';
                mysqlQuery("INSERT INTO urlMapping (id, url) VALUES ('$id', '$url');");
                return $id;
            }
            // Can NOT use past time to generate id.
            else
                return '';
        }
        // Query failed.
        else
            return '';
    }

    function getMappedUrl(string $id)
    {
        require_once 'database.php';

        $dbRetrieve = mysqlQuery("SELECT url FROM urlMapping WHERE id = '$id';");

        if($dbRetrieve !== false && mysqli_num_rows($dbRetrieve) > 0)
            return mysqli_fetch_row($dbRetrieve)[0];
        // Query failed.
        else
            return '';
    }

