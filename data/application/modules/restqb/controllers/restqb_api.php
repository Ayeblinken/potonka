<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * # Get all rows from the "employees" table
 * GET http://api.example.com/shopbot/data/employees/employee_api/
 *
 * # Get a single row from the "customers" table (where "123" is the ID)
 * GET http://api.example.com/customers/123/
 *
 * # Get all rows from the "customers" table where the "country" field matches "Australia" (`LIKE`)
 * GET http://api.example.com/customers/country/Australia/
 *
 * # Get 50 rows from the "customers" table
 * GET http://api.example.com/customers/?limit=50
 *
 * # Get 50 rows from the "customers" table ordered by the "date" field
 * GET http://api.example.com/customers/?limit=50&by=date&order=desc
 *
 * # Create a new row in the "customers" table where the POST data corresponds to the database fields
 * POST http://api.example.com/customers/
 *
 * # Update customer "123" in the "customers" table where the PUT data corresponds to the database fields
 * PUT http://api.example.com/customers/123/
 *
 * # Delete customer "123" from the "customers" table
 * DELETE http://api.example.com/customers/123/
 *
 * $_SERVER['REQUEST_METHOD']
 *
 * http://code.tutsplus.com/tutorials/a-beginners-guide-to-http-and-rest--net-16340
 * When assigning resources rest api will assign to an array from the url rest/kp_OrderID/33333/ to an array
 * 'rest' would call the function
 * print_r($this->get()); // will display the rest array
 * print_r($this->get('kp_OrderID')); // will display value from array
 * we can also call print_r($this->uri->segment(4)); // this will ignore the rest array
 *
 */

// This can be removed if you use __autoload() in config.php OR use Modular Extensions

require APPPATH.'/libraries/REST_Controller.php';

class Restqb_Api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        $this->load->model('restqbmodel');
    }

    /**
     * REST - Get All Rows from the $table
     * @return [json] [Get]
     */

    public function restQB_get()
    {
        $data = null;
        $getArry = $this->get();
        //print_r($getArry);
        //echo "<br/>";
        //var_dump($getArry);
        //echo "<br/> SizeOf: ".sizeof($getArry)."<br/>";
        // if size is equal to 1 add no where condition and just get the table Data
        if(sizeof($getArry) === 1)
        {

            $table =  key($getArry);
            //echo $table;
            // get all table data
            if(empty($getArry[$table]))
            {
                //echo "<br/>".empty($getArry[$table])."<br/>";
                //https://localhost/shopbot/data/rest/rest_api/rest/Vendors
                $data  = $this->restqbmodel->getAll($table);
            }
            // get table data by ID
            else
            {
                // sample url: https://localhost/shopbot/data/rest/rest_api/rest/Vendors/10052

                // get ID
                $id             = $getArry[$table];

                // get the primary key field name
                $result         = $this->restqbmodel->getPrimaryKeyFieldName($table);
                $primaryKeyName = $this->getPrimaryKeyName($result);
                //echo "<br/> primary key name: ".$primaryKeyName."<br/>";
                // get the data
                $data           = $this->restqbmodel->getRow($table,$primaryKeyName,$id);

                //var_dump($data);
            }
        }
        // if size is greater than 1 add where condition
        else
        {
            // generate dynamic where condition depending on the GET array
            $getArry              = $this->nullCheckFunction($getArry);
            $keyNames             = array_keys($getArry);
            //echo "<br/>";
            //var_dump($keyNames);
            //echo "<br/>";
            $keyValues            = array_values($getArry);
            //var_dump($keyValues);

            $table                = $keyNames[0];
            //echo "<br/> table:".$table;
            $id                   = $keyValues[0];

            //echo "<br/> array shift:";
            array_shift($getArry);
            //remove the table index key
            //$getArry = $this->tblCheckFunction($getArry,$table);
            //var_dump($getArry);

            //get the search Index and search Index value
            $searchIndexValue = $this->getSearchTerm($getArry);

            //get compound term value
            $compoundIndexValue = $this->getCompoundTerm($getArry);

            //var_dump($compoundIndexValue);

            //remove the search index key
            $getArry = $this->searchCheckFunction($getArry);

            //remove compoundcondition value
            $getArry = $this->compoundCheckFunction($getArry);
            //echo "<br/> After:";
            //var_dump($getArry);
//            echo "<br/> id:".$id;
//            echo "<br/> isset: ".isset($id);
//            echo "<br/> empty: ".empty($id);
//            echo "<br/>Search condition :".isset($searchIndexValue['searchIndex'])."<br/>";
//            echo "<br/>Search value :".isset($searchIndexValue['searchValue'])."<br/>";

            $searchIndex = $searchIndexValue['searchIndex'];
            $searchValue = $searchIndexValue['searchValue'];

            $compoundIndex = $compoundIndexValue['compoundIndex'];
            $compoundValue = $compoundIndexValue['compoundValue'];

            // if id is there then include the id in the where condition
            if(!empty($id))
            {
                //echo "id found";
                $result            = $this->restqbmodel->getPrimaryKeyFieldName($table);
                $primaryKeyName    = $this->getPrimaryKeyName($result);
                $primaryKeyNameID  = array($primaryKeyName=>$id);
                $whereArry         = array_merge($primaryKeyNameID,$getArry);
            }
            else
            {
                //echo "no id found";
                $whereArry         = $getArry;
            }
            //echo "<br/>Final where Array :<br/>";
            //var_dump($whereArry);

            if(!empty($searchIndex) && !empty($searchValue) && strtolower($searchValue) == "and")
            {
                // has a dynamic where condition
                //echo "search index and search value found";
                //echo "<br/>where condition is and";
                $data = $this->restqbmodel->getDynamicRow($table,$whereArry);

                //echo "<br/>".sizeof($data)."<br/>";

                //var_dump($data);
            }
            else if(!empty($searchIndex) && !empty($searchValue) && strtolower($searchValue) == "or")
            {
                //echo "<br/>where condition is or";
                //check each and every key name

                $whereKeyNames  = array_keys($whereArry);

                $whereKeyValues = array_values($whereArry);

                //find and remove the extra string 1 in the $whereKeyNames array
                foreach($whereKeyValues as $key=>$value)
                {
                    if($whereKeyValues[$key] == null)
                    {
                        $whereKeyValues[$key] = 'null';
                    }

                }
                foreach($whereKeyNames as $key=>$value)
                {
                    //echo "<br/>keyName: ".$key."<br/>";

                    //echo "<br/>name: ".$value."<br/>";

                    //strpos($findme, $key);

                    //echo "<br/>".strlen($value);

                    $valueLength = strlen($value);

                    //echo "<br/>string Length: ".$valueLength."<br/>";

                    //echo "<br/> substring: ".substr($value,($valueLength-1))."<br/>";

                    $lastCharStr = substr($value,($valueLength-1));

                    //echo "<br/> last Char String: ".$lastCharStr."<br/>";

                    if(substr($value,($valueLength-1)) == "1")
                    {
                        //echo "unique character found as 1";
                        //echo "<br/> before value: ".$whereKeyNames[$key]."<br/>";
                        $whereKeyNames[$key]=substr_replace($value ,"",-1);
                        //echo "<br/> after value: ".$whereKeyNames[$key]."<br/>";
                    }
                    //echo strrpos($value,"1");

                }
                //echo "final where after replace of same key names";
                ///var_dump($whereKeyNames);
                //var_dump($whereKeyValues);
                // do this in for loop

                if(sizeof($whereKeyNames == $whereKeyValues))
                {
                    //echo "<br/> equal size <br/>";
                    //check for compound condition

                    //if($compoundIndex = $compoundIndexValue['compoundIndex'];

                    if(!empty($compoundIndex) && !empty($compoundIndexValue))
                    {
                        $whereQueryCondition1 = "(";
                        //echo "compound query build<br/>";

                        $internalKeyName = "";
                        for($x=0;$x<sizeof($whereKeyNames);$x++)
                        {
                            //echo "<br/>".$internalKeyName."<br/>";
                            if($internalKeyName != $whereKeyNames[$x])
                            {
                                $internalKeyName = $whereKeyNames[$x];
                                if($whereKeyValues[$x] == 'null')
                                {
                                    $whereQueryCondition1 .=  $whereKeyNames[$x]. " is ".$whereKeyValues[$x];
                                    $whereQueryCondition1 .=  " or ";

                                }
                                else
                                {
                                    $whereQueryCondition1 .=  $whereKeyNames[$x]. " = '".$whereKeyValues[$x]."'";
                                    $whereQueryCondition1 .=  " or ";
                                }

                                //echo "<br/>".$whereQueryCondition1."<br/>";
                            }
                            else
                            {
                                $whereQueryCondition1 .=  $whereKeyNames[$x]. " = '".$whereKeyValues[$x]."') and (";
                            }
                            //$internalKeyName       = $whereKeyNames[$x];

                            //$whereQueryCondition1 .=  "(".$whereKeyNames[$x]. " is ".$whereKeyValues[$x];
                            //$whereQueryCondition1 .=  " or ";


                        }
                        $whereQueryCondition1 = rtrim($whereQueryCondition1, " and (");
                        //echo "<br/>where with compound query<br/>";
                        //echo $whereQueryCondition1."<br/>";
                        $data = $this->restqbmodel->getDynamicWhereCondition($table,$whereQueryCondition1);
                        //echo sizeof($data)."<br/>";
                        //var_dump($data);

                    }
                    else
                    {
                        $whereQueryCondition = "";
                        for($x=0;$x<sizeof($whereKeyNames);$x++)
                        {
                            if($whereKeyValues[$x] == 'null')
                            {
                                //echo "null value found ";
                                $whereQueryCondition .=  $whereKeyNames[$x]. " is ".$whereKeyValues[$x];
                                $whereQueryCondition .=  " or ";
                            }
                            else
                            {
                                $whereQueryCondition .=  $whereKeyNames[$x]. " = '".$whereKeyValues[$x]."'";
                                $whereQueryCondition .=  " or ";
                            }

                        }

                        //echo "<br/>Query without compound query<br/>";
                        $whereQueryCondition = rtrim($whereQueryCondition, " or ");
                        //echo $whereQueryCondition."<br/>";

                        // call the rest model and pass the dynamic where condition

                        $data = $this->restqbmodel->getDynamicWhereCondition($table,$whereQueryCondition);
                        //echo sizeof($data)."<br/>";
                        //var_dump($data);


                    }


                }
            }
            else
            {
                // no where condition or where query

                echo "search index and search value not found";
            }


        }
        if($data)
        {
            $this->response($data, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('msg'=>'data variable is empty'), 404);
        }
    }

    protected function getCompoundTerm($getArry)
    {
        $compoundIndexValue = array('compoundIndex'=>'','compoundValue'=>'');

        foreach($getArry as $key=>$value)
        {
            if(strtolower($key) == "compoundcondition")
            {
                $compoundIndexValue['compoundIndex'] = strtolower($key);

                $compoundIndexValue['compoundValue'] = strtolower($getArry[$key]);
            }
        }

        return $compoundIndexValue;
    }
    protected function compoundCheckFunction($getArry)
    {
        foreach($getArry as $key=>$value)
        {
            if(strtolower($key) == "compoundcondition")
            {
                unset($getArry[$key]);
            }
        }

        return $getArry;
    }
    protected function getSearchTerm($getArry)
    {
        $searchIndexValue = array('searchIndex'=>'','searchValue'=>'');

        foreach($getArry as $key=>$value)
        {
            if(strtolower($key) == "search")
            {
                $searchIndexValue['searchIndex'] = strtolower($key);

                $searchIndexValue['searchValue'] = strtolower($getArry[$key]);

                //unset($getArry[$key]);

            }
        }

        return $searchIndexValue;
    }
    protected function searchCheckFunction($getArry)
    {
        foreach($getArry as $key=>$value)
        {
            if(strtolower($key) == "search")
            {
                unset($getArry[$key]);

            }
        }

        return $getArry;
    }
    protected function nullCheckFunction($getArry)
    {
        foreach($getArry as $key=>$value)
        {
            if($getArry[$key] == 'null')
            {
                $getArry[$key] = null;
            }
        }

        return $getArry;
    }
    protected function getPrimaryKeyName($resultArray)
    {

        $primaryKeyName = $resultArray['COLUMN_NAME'];

        if($primaryKeyName)
        {
            return $primaryKeyName;
        }
        else
        {
            $this->response(array('mssg'=>'no primary key field name detected'), 404); // 200 being the HTTP response code
        }

    }


    public function restQbTables_get()
    {
        $result = $this->getQBTableList();
        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(NULL, 404);
        }
    }
    protected function getQBTableList()
    {
        $allowed = array(
                        'account'=>'ListID',
                        'bill'=>'TxnID',
                        'billpaymentcheck'=>'TxnID',
                        'billpaymentcreditcard'=>'TxnID',
                        'creditcardcharge'=>'TxnID',
                        'creditcardcredit' =>'TxnID',
                        'creditmemo'=>'TxnID',
                        'creditmemolinedetail'=>'TxnLineID',
                        'customer' =>'ListID',
                        'customermsg'=>'ListID',
                        'customertype'=>'ListID',
                        'deposity'=>'TxnID',
                        'depositlinedetail'=>'TxnType',
                        'employee'=>'ListID',
                        'entity'=>'ListID',
                        'invoice'=>'TxnID',
                        'invoicelinedetail'=>'TxnLineID',
                        'itemnoninventory' =>'ListID',
                        'itemreceipt'      =>'TxnID',
                        'items'=>'ListID',
                        'itemsalestax'=>'ListID',
                        'itemservice'=>'ListID',
                        'paymentmethod'=>'ListID',
                        'purchaseorder'=>'TxnID',
                        'purchaseorderlinedetail'=>'TxnLineID',
                        'receivepayment'=>'TxnID',
                        'receivepaymenttodeposit'=>'TxnID',
                        'refundappliedtotxndetail'=>'TxnID',
                        'salesorpurchasedetail' => 'IDKEY',
                        'salesrep'=>'ListID',
                        'salestaxcode'=>'ListID',
                        'shipmethod'=>'ListID',
                        'shiptoaddressdetail'=>'IDKEY',
                        'standardterms'=>'ListID',
                        'vendor'=>'ListID',
                        'vendorcredit'=>'TxnID',
                        'vendortype'=>'ListID'
                    );

        return $allowed;

    }
    public function restqb_post()
    {
        //create a new row in the database
        $data                       = $this->post('formData',false);

        $tblName                    = $this->post('tblName',false);

        $insertedID                 = $this->restqbmodel->insertTblData($data,$tblName);

        $result                     = array('id'=>$insertedID);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'somethign went wrong with rest insert'), 404);
        }

    }
    public function restqb_put()
    {
        //update an existing row in table
        $data                       = $this->put('formData',false);

        $tblName                    = $this->put('tblName',false);

        $id                         = $this->put('id',false);

        $allowed                    = $this->getQBTableList();

        if (array_key_exists(strtolower($tblName), $allowed))
        {
            $primaryKeyName         = $allowed[strtolower($tblName)];
        }
        else
        {
            $this->response(array('mssg'=>'no QB Column key found for '.$tblName. ' .Please check the table'), 404); // 200 being the HTTP response code
        }

        $result                     = $this->restqbmodel->updateTblData($tblName,$data,$primaryKeyName,$id);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('msg'=>'update operation failed'), 200);
        }

    }
    public function restqb_delete($tableName,$id)
    {
        if(!empty($tableName))
        {
            $allowed  = $this->getQBTableList();
            if (array_key_exists(strtolower($tableName), $allowed))
            {
                $primaryKeyName = $allowed[strtolower($tableName)];
            }
            else
            {
                $this->response(array('mssg'=>'no QB Column key found for '.$tableName. ' .Please check the table'), 404); // 200 being the HTTP response code
            }
        }

        $this->restqbmodel->deleteTblData($tableName,$primaryKeyName,$id);

        $this->response(array(
            'returned from delete:' => $id,
        ));
    }

}

?>
