<?php 
/**
 *
 * Install Mongo DB PHP Library
 * https://docs.mongodb.com/php-library/v1.2/tutorial/install-php-library
 */

require_once __DIR__ . '/vendor/autoload.php';

//Initialise Variables
$database_name = 'apls_callleadgen_new';
$total_collections =0;
$total_documents = 0;



//Created connection
$mongodb = new MongoDB\Client();

//Select Database 

$database = $mongodb->{$database_name};


//Read JSON file

$file_name = 'apls_callleadgen_backup_1608101694.json';

//Read JSON file contents
$json = file_get_contents($file_name);

$json_to_array = json_decode($json,true);//true for array

/* 
 Array
        (
            [collection] => campaign
            [data] => Array
                (
                    [0] => Array
                        (
                            

                        )
*/  


foreach ($json_to_array as $record) 
{
    $collection_name = $record['collection'];
    $collection_data = $record['data'];


     //Drop Already exist collection incase you import into already exist database and with same collection

    $database->dropCollection($collection_name);

    //Mongo DB collection object
    $collection = $database->{$collection_name};

    //
    foreach ($collection_data as $row) {
    	
    	//Convert Row (Document) to json
    	$json_row = json_encode($row);

    	//Convert JSON to BSON
        $bson = \MongoDB\BSON\fromJSON($json_row);

    	//Convert BSON to PHP Std Class object
    	$row = \MongoDB\BSON\toPHP($bson);


    	// Insert Record to Document
    	$collection->insertOne($row);



	    $total_documents++;  
	    
    }

    $total_collections++;
}                      



echo "<h3> Total <i style='color:green'>$total_collections collections </i> and <i style='color:green'> $total_documents documents </i> imported into database <i style='color:green'> $database_name </i>";