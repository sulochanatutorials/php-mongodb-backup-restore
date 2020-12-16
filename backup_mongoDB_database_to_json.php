<?php 
/**
 *
 * Install Mongo DB PHP Library
 * https://docs.mongodb.com/php-library/v1.2/tutorial/install-php-library
 */

require_once __DIR__ . '/vendor/autoload.php';

//Initialise Variables
$database_backup_array = [];
$database_name = 'apls_callleadgen';

/* 
    [
        'collection'=>'collection name',
        'data'=>'collection data to array'
    ]

*/

//Created connection
$mongodb = new MongoDB\Client();

//Select Database 

$database = $mongodb->{$database_name};

//List All collections

foreach ($database->listCollections() as $collectionInfo) {
    
    /**
     *
     * Traverse collection
     *
     */
        $whole_collection_array = [];
        $collection_data = $database->{$collectionInfo['name']}->find();

	    $result = Array();
		foreach ($collection_data as $row) {

			 $row_to_array = iterator_to_array($row);

		    array_push($whole_collection_array,$row_to_array );
		}
		
		array_push($database_backup_array, 
            [
            	'collection'=>$collectionInfo['name'],
            	'data'=> $whole_collection_array
            ]
		); 
    
}


// Save Data to JSON File
file_put_contents($database_name.'_backup_'.time().'.json', json_encode($database_backup_array));

