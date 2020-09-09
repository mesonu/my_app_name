<?php
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;



class HomesController extends AppController
{
    
    public function index()
    {
        $this->viewBuilder()->layout(false);
       

        // echo "<pre>";
        try {
            ini_set('max_execution_time', 0); // 0 = Unlimited
            set_time_limit(0);

            // $this->generateFakeData();
            $path = '../webroot/dummyData.csv';
            $handle = fopen($path, 'r');
            
            
            if(($handle) !== FALSE) {
                // necessary if a large csv file

                $row = 0;
                $arrayDatas = array();
                while(($data = fgetcsv($handle, 200000, ',')) !== FALSE) {
                    // number of fields in the csv
                    $col_count = count($data);

                    // get the values from the csv
                    $arrayDatas[$row]['first_name'] = $data[0];
                    $arrayDatas[$row]['last_name'] = $data[1];
                    $arrayDatas[$row]['email'] = $data[2];
                    $arrayDatas[$row]['dob'] = $data[3];
                    $arrayDatas[$row]['address'] = $data[4];
                    // inc the row
                    $row++;
                }
                array_shift($arrayDatas);
                
                fclose($handle);

                // Prior to 3.6 use TableRegistry::get('Articles')
                $articles = TableRegistry::getTableLocator()->get('Employees');
                $entities = $articles->newEntities($arrayDatas);
                $result = $articles->saveMany($entities);
            }
            
            // $data = $this->generateFakeData();
            
        } catch (Exception $e) {
            dd($e->getMessage());
        }
        $data = "uploded successfully!";
        $this->set(compact('data'));
    }

    public function generateRandomDob(){
        //Generate a timestamp using mt_rand.
        $timestamp = mt_rand(100, time());
        
        //date formate
        $randomDate = date("Y-m-d", $timestamp);
        
        //Print it out.
        return $randomDate;
    }

    public function exportData() {
        $this->viewBuilder()->layout(false);
       

        // echo "<pre>";
        try {
            ini_set('max_execution_time', 0); // 0 = Unlimited
            set_time_limit(0);

            // $this->generateFakeData();
            $path = '../webroot/dummyData.csv';
            $handle = fopen($path, 'r');
            
            
            if(($handle) !== FALSE) {
                // necessary if a large csv file

                $row = 0;
                $arrayDatas = array();
                while(($data = fgetcsv($handle, 200000, ',')) !== FALSE) {
                    // number of fields in the csv
                    $col_count = count($data);

                    // get the values from the csv
                    $arrayDatas[$row]['first_name'] = $data[0];
                    $arrayDatas[$row]['last_name'] = $data[1];
                    $arrayDatas[$row]['email'] = $data[2];
                    $arrayDatas[$row]['dob'] = $data[3];
                    $arrayDatas[$row]['address'] = $data[4];
                    // inc the row
                    $row++;
                }
                array_shift($arrayDatas);
                // echo "<pre>";
                // print_r($arrayDatas);
                $this->array_csv_download($arrayDatas);
                fclose($handle);

            }
            
            // $data = $this->generateFakeData();
            
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function genarteRandomEmail() {
        $emailProviders = array('gmail.com','yahoo.com','hotmail.com','zoho.com');
        // shuffle($emailproviders);
        return $emailProviders[1];
    }

    public function generateLength($min, $max) {
        return mt_rand($min, $max);
    }
    public function genarateAlphaNumeric($min, $max) {
        $n = $this->generateLength($min, $max);
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
        $randomString = ''; 
    
        for ($i = 0; $i < $n; $i++) { 
            $index = rand(0, strlen($characters) - 1); 
            $randomString .= $characters[$index]; 
        } 
        $emailproviders = $this->genarteRandomEmail();
        return strtolower($randomString).'@'.$emailproviders;
    }
    
    public function getUserName($min, $max) { 
        $n = $this->generateLength($min, $max);
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
        $randomString = ''; 
    
        for ($i = 0; $i < $n; $i++) { 
            $index = rand(0, strlen($characters) - 1); 
            $randomString .= $characters[$index]; 
        } 
    
        return ucfirst(strtolower($randomString)); 
    } 
  

    public function generateFakeData() {
        $fakeData = array();
        for ($i =0; $i < 200000; $i++) {
            $fakeData[$i]['first_name'] = $this->getUserName(4,10);
            $fakeData[$i]['last_name'] = $this->getUserName(3,10);
            $fakeData[$i]['email'] = $this->genarateAlphaNumeric(4,20);
            $fakeData[$i]['dob'] = $this->generateRandomDob();
            $fakeData[$i]['address'] = $this->getUserName(4,20);
        }
        $this->writeArrayToCsv($fakeData);
    }

    public function array_csv_download( $array, $filename = "export.csv", $delimiter=";" ) {
        header('Content-Type: text/csv; charset=utf-8');  
        header( 'Content-Disposition: attachment; filename="' . $filename . '";' );

        $handle = fopen( 'php://output', 'w' );
        // Open a file in write mode ('w') 
        
        // use keys as column titles
        fputcsv( $handle, array_keys( $array['0'] ) );

        foreach ( $array as $value ) {
            // fputcsv( $handle, $value , $delimiter );
            fputcsv( $handle, $value );
        }

        fclose( $handle );

        // use exit to get 
        exit();
    }

    function writeArrayToCsv($array) {
        $path = '../webroot/dummyData.csv';
        $handle = fopen($path, 'w'); 
        // use keys as column titles
        fputcsv( $handle, array_keys( $array['0'] ) );
        foreach ( $array as $value ) {
            // fputcsv( $handle, $value , $delimiter );
            fputcsv( $handle, $value );
        }
        fclose( $handle );
    }

    
}
