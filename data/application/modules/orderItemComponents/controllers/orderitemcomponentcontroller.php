<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class orderItemComponentController extends MX_Controller
{
    //put your code here
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        $this->load->model('orderitemcomponentmodel');

    }
    public function setup($orderID=null,$orderItemID=null,$productBuildID=null)
    {
        //echo "hi";
//        if(!is_null($orderID))
//        {
//            $data['orderID']            = $orderID;
//
//        }
//        else
//        {
//            $data['orderID']            = null;
//
//        }
        $data['orderID']            = $orderID;
        $data['orderItemID']        = $orderItemID;
        $data['productBuildID']     = $productBuildID;
        //print_r($data);
        $this->load->view('setup',$data);

    }
    public function dupLineItemExceptPrintEquipOIC($orderItemID,$orderItemComponentID,$orderID)
    {
        // get the productBuild ID of selected OrderItemID
        $orderItemArry    = Modules::run('orderItems/orderitemcontroller/getOrderItemFieldsFromOrderItemID',$orderItemID);

        //var_dump($orderItemArry);

        //get a list of orderitems for a given orderID
        $orderItemCompArry    = Modules::run('orderItems/orderitemcontroller/getOrderItemsFromOrderID',$orderID);



        //var_dump($orderItemCompArry);
        //echo "<br/>";
        //define an array to insert into oic table
        $oicSameProductBuildArry = array();

        $y=0;

        for($i=0; $i<sizeof($orderItemCompArry); $i++)
        {
            if(!in_array($orderItemArry[0]['kp_OrderItemID'], $orderItemCompArry[$i]))
            {
                 if($orderItemArry[0]['kf_ProductBuildID'] == $orderItemCompArry[$i]['kf_ProductBuildID'])
                 {
                     //get the OrderItemComponent ID row data
                     $oicSameProductBuildArry[$y] = $this->orderitemcomponentmodel->getOrderItemComponentByID($orderItemComponentID);

                     // set the kp_OrderItemComponentID val to empty bec of insert operation
                     $oicSameProductBuildArry[$y]['kp_OrderItemComponentID'] = "";

                     // update the orderItemID
                     $oicSameProductBuildArry[$y]['kf_OrderItemID'] = $orderItemCompArry[$i]['kp_OrderItemID'];

                     $y++;

                 }

            }
//            if($orderItemArry[0]['kf_ProductBuildID'] == $orderItemCompArry[$i]['kf_ProductBuildID'])
//            {
//
//                if(!in_array($orderItemArry[0]['kp_OrderItemID'], $orderItemCompArry[$i]))
//                {
//                    //get the OrderItemComponent ID row data
//                    $oicSameProductBuildArry[$y] = $this->orderitemcomponentmodel->getOrderItemComponentByID($orderItemComponentID);
//
//                    // set the kp_OrderItemComponentID val to empty bec of insert operation
//                    $oicSameProductBuildArry[$y]['kp_OrderItemComponentID'] = "";
//
//                    // update the orderItemID
//                    $oicSameProductBuildArry[$y]['kf_OrderItemID'] = $orderItemCompArry[$i]['kp_OrderItemID'];
//
//                    $y++;
//                }
//            }
        }
        //var_dump($oicSameProductBuildArry);
        //echo sizeof($oicSameProductBuildArry);

        $this->orderitemcomponentmodel->insertOrderItemComponentTable($oicSameProductBuildArry);


    }
    public function getSetUptHeading($productBuildID)
    {

        $row          = Modules::run('productBuilds/productbuildcontroller/getProductBuildItemData',$productBuildID);
        //print_r($row);
        $setUpHeading = "Product Build: ".$row['t_Category']."-".$row['t_Name'];
        echo json_encode($setUpHeading);
    }
    public function displayProductBuild($orderItemID)
    {
        // get the productBuild ID of selected OrderItemID
        $orderItemArry            = Modules::run('orderItems/orderitemcontroller/getOrderItemFieldsFromOrderItemID',$orderItemID);

        $orderItemProductBuildID  = $orderItemArry[0]['kf_ProductBuildID'];
        $checkOICproductBuildArry = $this->orderitemcomponentmodel->checkOrderItemComponentsAreValid($orderItemID,$orderItemProductBuildID);
        //var_dump($checkOICproductBuildArry);
        //echo json_encode($this->orderitemcomponentmodel->getProductBuildData($orderItemID));


        //var_dump($displayProductBuildArry);

        $oicProductBuildUpdate = array();

        for($i=0; $i<sizeof($checkOICproductBuildArry);$i++)
        {
            if($checkOICproductBuildArry[$i]['Valid'] == "inValid")
            {
               unset($oicProductBuildUpdate);

               $oicProductBuildUpdate['nb_InvalidProductBuild'] = "1";

               $this->updateOIC($checkOICproductBuildArry[$i]['kp_OrderItemComponentID'],$oicProductBuildUpdate);

            }
            if($checkOICproductBuildArry[$i]['Valid'] == "Valid")
            {
                unset($oicProductBuildUpdate);

                $oicProductBuildUpdate['kf_ProductBuildItemID'] = $checkOICproductBuildArry[$i]['NewProductBuild'];
                $oicProductBuildUpdate['nb_InvalidProductBuild'] = null;
                $this->updateOIC($checkOICproductBuildArry[$i]['kp_OrderItemComponentID'],$oicProductBuildUpdate);
            }
            //var_dump($oicProductBuildUpdate);
            //break;
        }

        //var_dump($displayProductBuildArry);
        $displayProductBuildArry  = $this->orderitemcomponentmodel->getProductBuildData($orderItemID);

        for($i=0;$i<sizeof($displayProductBuildArry);$i++)
        {
            if($displayProductBuildArry[$i]->t_Category == "Print Material")
            {
                $addPrintMaterialDataDisplayArry = $this->additionalDataDisplayPrintMaterial($displayProductBuildArry[$i]->OrderItemComponentID);

                //var_dump($addPrintMaterialDataDisplayArry);
                if(isset($addPrintMaterialDataDisplayArry['printAttrSubMode']))
                {
                    $displayProductBuildArry[$i]->printAttrSubMode      = $addPrintMaterialDataDisplayArry['printAttrSubMode'];
                }

                $displayProductBuildArry[$i]->printAttrPrintDoubleSided =  $addPrintMaterialDataDisplayArry['printAttrPrintDoubleSided'];

                $displayProductBuildArry[$i]->printAttrCustomPrintSpecs = $addPrintMaterialDataDisplayArry['printAttrCustomPrintSpecs'];

                $displayProductBuildArry[$i]->printAttrTiled            = $addPrintMaterialDataDisplayArry['printAttrTiled'];
                $displayProductBuildArry[$i]->printAttrOverlapTile      = (float)$addPrintMaterialDataDisplayArry['printAttrOverlapTile'];

                $displayProductBuildArry[$i]->printAttrqtyHtW           = $addPrintMaterialDataDisplayArry['printAttrqtyHtW'];

                $displayProductBuildArry[$i]->printAttrbWpData          = $addPrintMaterialDataDisplayArry['printAttrbWpData'];
            }
        }
        //var_dump($displayProductBuildArry);

        echo json_encode($displayProductBuildArry);
    }
    public function designChangeForPrintMaterial($orderItemComponentID)
    {
        echo json_encode($this->additionalDataDisplayPrintMaterial($orderItemComponentID));
    }
    public function additionalDataDisplayPrintMaterial($orderItemComponentID)
    {
        $dataDisplayPrintMaterial = array();

        $oicArray                 = $this->orderitemcomponentmodel->getOrderItemComponentByID($orderItemComponentID);

        // ----- Bleed ------
        $bWp['bleedTop']                          = $oicArray['n_BleedTop'];
        $bWp['bleedBottom']                       = $oicArray['n_BleedBottom'];
        $bWp['bleedLeft']                         = $oicArray['n_BleedLeft'];
        $bWp['bleedRight']                        = $oicArray['n_BleedRight'];

        // ----- White ------
        $bWp['whiteTop']                          = $oicArray['n_WhiteTop'];
        $bWp['whiteBottom']                       = $oicArray['n_WhiteBottom'];
        $bWp['whiteLeft']                         = $oicArray['n_WhiteLeft'];
        $bWp['whiteRight']                        = $oicArray['n_WhiteRight'];

        // ----- Pocket ------
        $bWp['pocketTop']                         = $oicArray['n_PocketTop'];
        $bWp['pocketBottom']                      = $oicArray['n_PocketBottom'];
        $bWp['pocketLeft']                        = $oicArray['n_PocketLeft'];
        $bWp['pocketRight']                       = $oicArray['n_PocketRight'];

        $bWp['heightInInches']                    = $oicArray['n_HeightInInches'];
        $bWp['widthInInches']                     = $oicArray['n_WidthInInches'];

        $bwpArry                                  = $this->calculateBleedWhitePocketFeetInches($bWp);

        //var_dump($bwpArry);

        $equipmentSubModeArry                     = Modules::run('equipmentSubModes/equipmentsubmodecontroller/getEquipmentSubModeFromEquipmentSubModeID',$oicArray['kf_EquipmentSubModeID']);

        if(isset($equipmentSubModeArry['t_Name']))
        {
            $dataDisplayPrintMaterial['printAttrSubMode']      = $equipmentSubModeArry['t_Name'];
        }

        $dataDisplayPrintMaterial['printAttrPrintDoubleSided'] =  $oicArray['nb_PrintDoubleSided'];
        $dataDisplayPrintMaterial['printAttrCustomPrintSpecs'] = $oicArray['nb_CustomPrintSpecs'];

        $dataDisplayPrintMaterial['printAttrTiled']            = $oicArray['t_Tiled'];
        $dataDisplayPrintMaterial['printAttrOverlapTile']      = (float)$oicArray['n_OverlapTile'];
        $dataDisplayPrintMaterial['printAttrqtyHtW']           = " <strong>Qty ".$oicArray['n_Quantity']
                                                                    ." ".(float)($oicArray['n_HeightInInches'])
                                                                    ."'' H x ".(float)($oicArray['n_WidthInInches'])
                                                                    ."'' W </strong>";
        $dataDisplayPrintMaterial['printAttrbWpData']         = '<br/>&nbsp;<table  class="table table-striped table-bordered table-condensed" id="tableBleedWhitePocked">
        <thead><th></th><th>T</th><th>B</th><th>L</th><th>R</th><th>Inches</th><th>Feet</th></thead>
        <tbody><tr><td>B</td><td id="bleedTopDisplayOnly">'.(float)$bwpArry['bleedTop'].'</td>
        <td id="bleedBottomDisplayOnly">'.(float)$bwpArry['bleedBottom'].'</td>
        <td id="bleedLeftDisplayOnly">'.(float)$bwpArry['bleedLeft'].'</td><td id="bleedRightDisplayOnly">'.(float)$bwpArry['bleedRight'].'</td>
        </td><td id="bleedInchesDisplayOnly">'.$bwpArry['bleedInches'].'</td><td id="bleedFeetDisplayOnly">'.$bwpArry['bleedFeet'].'</tr>
        <tr><td>W</td><td id="whiteTopDisplayOnly">'.(float)$bwpArry['whiteTop'].'</td><td id="whiteBottomDisplayOnly">'.(float)$bwpArry['whiteBottom'].'</td>
        <td id="whiteLeftDisplayOnly">'.(float)$bwpArry['whiteLeft'].'</td><td id="whiteRightDisplayOnly">'.(float)$bwpArry['whiteRight'].'</td>
        <td id="whiteInchesDisplayOnly">'.$bwpArry['whiteInches'].'</td><td id="whiteFeetDisplayOnly">'.$bwpArry['whiteFeet'].'</td></tr>
        <tr><td>P</td><td id="pocketTopDisplayOnly">'.(float)$bwpArry['pocketTop'].'</td><td id="pocketBottomDisplayOnly">'.(float)$bwpArry['pocketBottom'].'</td>
        <td id="pocketLeftDisplayOnly">'.(float)$bwpArry['pocketLeft'].'</td><td id="pocketRightDisplayOnly">'.(float)$bwpArry['pocketRight'].'</td>
        <td id="pocketInchesDisplayOnly">'.$bwpArry['pocketInches'].'</td><td id="pocketFeetDisplayOnly">'.$bwpArry['pocketFeet'].'</td></tr></tbody></table>';

        return $dataDisplayPrintMaterial;
        //var_dump($dataDisplayPrintMaterial);


    }
    public function getDisplayCategories($productBuildID)
    {
        $displayCategory= array();

        $processRelatedProductBuild  = $this->orderitemcomponentmodel->getRelatedProductBuildData($productBuildID);
        //array_shift($processRelatedProductBuild);
        //print_r($processRelatedProductBuild);
        for($i=0; $i<sizeof($processRelatedProductBuild); $i++)
        {
            $displayCategory[$i] = $processRelatedProductBuild[$i]['DisplayCategory'];

        }
        //print_r($displayCategory);


        $category = array_unique($displayCategory);
        //print_r($category);
        $comma_separated = implode(",", $category);
        $category = explode(",", $comma_separated);
        //echo sizeof($category)."<br/>";
        //print_r($category);
        return $category;

    }
    public function getDisplayNames($productBuildID)
    {
        $result = "";
        $multi = "<option value=\"\"></option>";
        $occurences                  = array();
        //$occurencesArray             = array();
        $categoryGroup               = $this->getDisplayCategories($productBuildID);
        //print_r($categoryGroup);
        $processRelatedProductBuild  = $this->orderitemcomponentmodel->getRelatedProductBuildData($productBuildID);
        //array_shift($processRelatedProductBuild);
        //print_r($processRelatedProductBuild);
        foreach($categoryGroup as $category)
        {

             $multi  .= "<optgroup label=\"$category\">";
             for($i=0; $i<sizeof($processRelatedProductBuild); $i++)
             {
                 $categoryFound = in_array($category,$processRelatedProductBuild[$i]);
                 if($categoryFound)
                 {
//                     array_push($occurences,  $processRelatedProductBuild[$i]['productBuildItemID'],$processRelatedProductBuild[$i]['buildItemID'],
//                                              $processRelatedProductBuild[$i]['equipmentID'],$processRelatedProductBuild[$i]['equipmentModeID'],
//                                              $processRelatedProductBuild[$i]['DisplayCategory'].",".$processRelatedProductBuild[$i]['DisplayName'])  ;
//                     $result .= implode(",",$occurences);
                     //echo $result;
                     //$multi  .=  "<option value=".$result.">".$processRelatedProductBuild[$i]['DisplayName']."</option>";

//                     $multi  .= "<option value=" .$processRelatedProductBuild[$i]['productBuildItemID'].",".$processRelatedProductBuild[$i]['buildItemID']
//                                                 .",".$processRelatedProductBuild[$i]['equipmentID'].",".$processRelatedProductBuild[$i]['equipmentModeID']
//                                                 .",".$processRelatedProductBuild[$i]['DisplayCategory'].",".$processRelatedProductBuild[$i]['DisplayName']
//                                                 .">".$processRelatedProductBuild[$i]['DisplayName']."</option>";

                      $multi  .= "<option value=" .$processRelatedProductBuild[$i]['productBuildItemID'].",".$processRelatedProductBuild[$i]['buildItemID']
                                                 .",".$processRelatedProductBuild[$i]['equipmentID'].",".$processRelatedProductBuild[$i]['equipmentModeID']
                                                 .",".$processRelatedProductBuild[$i]['t_FormView'].",".$processRelatedProductBuild[$i]['nb_NotConnectedToInventory']
                                                 .",".$processRelatedProductBuild[$i]['nb_ShowOnInvoice']
                                                 .">".$processRelatedProductBuild[$i]['DisplayName']."</option>";
                 }
             }
             $multi .= "</optgroup>";
        }
        $occurences['setupLeftTable'] =  $multi;
        //print_r($occurences);

        //echo $multi;

//            for($i=0; $i<sizeof($processRelatedProductBuild); $i++)
//            {
//              foreach($categoryGroup as $category)
//              {
//                  $categoryFound = in_array($category,$processRelatedProductBuild[$i]);
//                  if($categoryFound)
//                  {
//                      $occurences[$category][$i] = array_slice($processRelatedProductBuild[$i],0);
//
//                  }
//
//              }
//
//
//            }

        //array('displayNames'=>$occurences);array('displayNames'=>$occurences);
        //$occurencesArray = array($occurences);
        //print_r($occurences);
        //echo json_encode($occurencesArray);


        echo json_encode($occurences);

    }
    public function deleteOrderItemComponentTableRow($orderItemComponentID)
    {
        if(is_null($orderItemComponentID))
        {
            echo 'Error: Id not provided';
            return;
        }
        else
        {
            $this->orderitemcomponentmodel->deleteOrderItemComponentRow($orderItemComponentID);
            echo 'Records deleted successfully';
        }

    }
    /*
     * Below method 'relatedProductBuilds' is not in use.
     * We are using the 'getDisplayNames' method instead.
     *  */
    public function relatedProductBuilds($productBuildID)
    {
        //var_dump($this->orderitemcomponentmodel->getRelatedProductBuildData($productBuildID));
        echo json_encode($this->orderitemcomponentmodel->getRelatedProductBuildData($productBuildID));
    }
    public function getOnHandMaxMinData($inventoryItemID)
    {
        echo Modules::run('inventoryItems/inventoryitemcontroller/getInventoryOnHandMinMaxData',$inventoryItemID);

    }
    public function dropEmptyValues($result)
    {
        foreach($result as $key=>$value)
        {
            if(empty($value))
            {
                unset($result[$key]);

            }
        }
        return $result;

    }
    public function processSetupLeftTableInsert($orderItemID,$replace = "false")
    {
        date_default_timezone_set('America/Indianapolis');

        $resultValues             = $this->input->post('result');
        $resultKeys               = $this->input->post('resultKeys');
        $resultValues[7]          = date("Y-m-d H:i:s", time());



        $response                 = array();

        $rightMainTableCategories = array();

        $oicEquipmentID           ="";
        $oicPrintMaterialID       ="";


        // Get the Right Side Main Table and place all the categories in an array.
        $getProductBuildData      = $this->orderitemcomponentmodel->getProductBuildData($orderItemID);
        //print_r($getProductBuildData);
        for($i=0; $i<sizeof($getProductBuildData); $i++)
        {
            $rightMainTableCategories[$i] = $getProductBuildData[$i]->t_Category;
            if($getProductBuildData[$i]->t_Category == "Equipment")
            {
                $oicEquipmentID         = $getProductBuildData[$i]->OrderItemComponentID;
            }
            if($getProductBuildData[$i]->t_Category == "Print Material")
            {
                $oicPrintMaterialID     = $getProductBuildData[$i]->OrderItemComponentID;
            }


        }
        //print_r($rightMainTableCategories);

        // if the posted value has equipment or print material as category
        // if posted value has no equipment or print material as category
        if($resultValues[8] == "Print Material" || $resultValues[8] =="Equipment")
        {
            // Check to see if the categories array already has a equipment or print material
            $categoryExists = in_array($resultValues[8],$rightMainTableCategories);
            // if categories array already have a equipment or print Material
            // no insert
            if($categoryExists && $replace == "false")
            {
                //display a not added message
                $response['existingMaterial'] = "yes";
                //echo "No";

            }
            else
            {
                    if($resultValues[8] == "Print Material")
                    {
                        $row               = Modules::run('orderItems/orderitemcontroller/getOrderItemQtyHeightWidth',$resultValues[2]);

                        $qtyHeightWidth    = explode(',', $row);
                        $resultKeys[10]    =  "n_Quantity";
                        $resultKeys[11]    =  "n_HeightInInches";
                        $resultKeys[12]    =  "n_WidthInInches";
                        // added description Key
                        $resultKeys[13]    =  "t_Description";
                        $resultValues[10]  = $qtyHeightWidth[0];
                        $resultValues[11]  = $qtyHeightWidth[1];
                        $resultValues[12]  = $qtyHeightWidth[2];
                         // added description Value
                        $resultValues[13]  = $qtyHeightWidth[3];
                        // check if there is an equipment from the right Main Table Categories
                        $equipmentCategoryExists = in_array("Equipment",$rightMainTableCategories);
                        // if equipment exists
                        if($equipmentCategoryExists)
                        {
                            //get the OrderItemComponents row for that Equipment Category .
                            $row = $this->orderitemcomponentmodel->getOrderItemComponentByID($oicEquipmentID);

                            //print_r($row);

                            // get the equipmentID and Equipment Mode ID from the OrderItemComponents row for that Equipment Category
                            //print_r($resultKeys);
                            //print_r($resultValues);

                            // remove the last element in the array (category)
                            //unset($resultValues[8]);
                            //unset($resultKeys[8]);
                            //array_pop($resultValues);
                            //array_pop($resultKeys);

                            //print_r($resultKeys);
                            //print_r($resultValues);



                            $resultValues[14]  = $row['kf_EquipmentID'];

                            $resultValues[15]  = $row['kf_EquipmentModeID'];

                            $resultKeys[14]    = "kf_LinkedEquipmentID";
                            $resultKeys[15]    = "kf_LinkedEquipmentModeID";


                            //print_r($resultKeys);
                            //print_r($resultValues);
                            $result            = array_combine($resultKeys,$resultValues);
                            //print_r($result);
                            unset($result['category']);

                            //strip any empty values from the result
                            $result = $this->dropEmptyValues($result);

                            if($replace == "true")
                            {
                                unset($result['kp_OrderItemComponentID']);
                                $result['kf_InventoryItemID'] = null;
                                $this->updateOIC($oicPrintMaterialID,$result);
                                $response['orderItemComponentID']=  $oicPrintMaterialID;
                                $response['replaceOIC'] = "PrintMaterialReplaced";
                                //echo "replaced";
                            }
                            else
                            {
                                $resultArray              = array($result);

                                $lastInsertedID           = $this->orderitemcomponentmodel->insertOrderItemComponentTable($resultArray);
                                $response['replaceOIC']   = "PrintMaterialInserted";
                                $response['orderItemComponentID']  =  $lastInsertedID;
                                //echo $lastInsertedID;

                            }






                        }
                        //if equipment doesn't exist
                        else
                        {
                            //unset($resultValues[8]);
                            //unset($resultKeys[8]);
                            //array_pop($resultValues);
                            //array_pop($resultKeys);

                            $result           = array_combine($resultKeys,$resultValues);

                            unset($result['category']);

                            //strip any empty values from the result
                            $result = $this->dropEmptyValues($result);

                            if($replace == "true")
                            {
                                unset($result['kp_OrderItemComponentID']);
                                $result['kf_InventoryItemID'] = null;
                                $this->updateOIC($oicPrintMaterialID,$result);
                                $response['orderItemComponentID']=  $oicPrintMaterialID;
                                $response['replaceOIC'] = "PrintMaterialReplaced";
                                //echo "replaced";
                            }
                            else
                            {
                                $resultArray      = array($result);

                                //print_r($resultArray);

                                $lastInsertedID   = $this->orderitemcomponentmodel->insertOrderItemComponentTable($resultArray);
                                $response['replaceOIC']   = "PrintMaterialInserted";
                                $response['orderItemComponentID']  =  $lastInsertedID;

                                //echo $lastInsertedID;
                            }
                        }
                    }
                    if($resultValues[8] == "Equipment")
                    {
                        // check if there is a Print material from the right Main Table Categories
                        $equipmentCategoryExists = in_array("Print Material",$rightMainTableCategories);
                        // if Print material exists
                        if($equipmentCategoryExists)
                        {
                            // update the Linked EquipmentID and Linked Equipment ModeID of the Print Material Category.
                            $linkedEquipmentID     = $resultValues[5];
                            //echo $oicPrintMaterialID."<br/>";
                            //echo $linkedEquipmentID."<br/>";
                            $linkedEquipmentModeID = $resultValues[6];
                            //echo $linkedEquipmentModeID."<br/>";

                             $data = array(
                                            'kf_LinkedEquipmentID'=>$linkedEquipmentID,
                                            'kf_LinkedEquipmentModeID'=>$linkedEquipmentModeID

                                           );


                            //$this->updateOIC($oicPrintMaterialID,$data);


                            // Insert a new row for the Equipment Category
                            //unset($resultValues[8]);
                            //unset($resultKeys[8]);
                            //array_pop($resultValues);
                            //array_pop($resultKeys);

                            $result           = array_combine($resultKeys,$resultValues);

                            unset($result['category']);

                            //strip any empty values from the result
                            $result = $this->dropEmptyValues($result);



                            if($replace == "true")
                            {
                                unset($result['kp_OrderItemComponentID']);

                                $data['kf_EquipmentSubModeID'] = null;
                                $this->updateOIC($oicPrintMaterialID,$data);
                                $this->updateOIC($oicEquipmentID,$result);
                                $response['orderItemComponentID'] =  $oicEquipmentID;
                                $response['replaceOIC'] = "EquipmentMaterialReplaced";
                                //echo "replaced";

                            }
                            else
                            {
                                $this->updateOIC($oicPrintMaterialID,$data);
                                $resultArray      = array($result);

                                //print_r($resultArray);



                                $lastInsertedID  = $this->orderitemcomponentmodel->insertOrderItemComponentTable($resultArray);
                                $response['orderItemComponentID'] =  $lastInsertedID;
                                $response['replaceOIC'] = "EquipmentMaterialInserted";

                                //echo $lastInsertedID;

                            }

                        }
                        //if Print Material doesn't exist
                        else
                        {
                            //unset($resultValues[8]);
                            //unset($resultKeys[8]);

                            //array_pop($resultValues);
                            //array_pop($resultKeys);

                            $result            = array_combine($resultKeys,$resultValues);

                            unset($result['category']);

                            //strip any empty values from the result
                            $result = $this->dropEmptyValues($result);

                            if($replace == "true")
                            {
                                unset($result['kp_OrderItemComponentID']);



                                $this->updateOIC($oicEquipmentID,$result);

                                $response['orderItemComponentID'] =  $oicEquipmentID;
                                $response['replaceOIC']           = "EquipmentMaterialReplaced";

                                //echo "replaced";

                            }
                            else
                            {
                                $resultArray       = array($result);

                                $lastInsertedID    = $this->orderitemcomponentmodel->insertOrderItemComponentTable($resultArray);

                                $response['orderItemComponentID'] =  $lastInsertedID;
                                $response['replaceOIC'] = "EquipmentMaterialInserted";

                            }
                        }
                    }
            }


        }
        else
        {
            //array_pop($resultValues);
            //array_pop($resultKeys);

            //unset($resultValues[8]);
            //unset($resultKeys[8]);

            $result           = array_combine($resultKeys,$resultValues);

            unset($result['category']);

            $resultArray      = array($result);
            //print_r($resultArray);



            $lastInsertedID   = $this->orderitemcomponentmodel->insertOrderItemComponentTable($resultArray);
            $response['orderItemComponentID'] =  $lastInsertedID;
            $response['replaceOIC'] = "NonPrintEquiptmentMaterial";
            //echo $lastInsertedID;


        }
        echo json_encode($response);


    }
    public function getOrderItemComponentArrayFromOrderID($orderID)
    {
        $orderItemComponentArray        =  $this->orderitemcomponentmodel->getOrderItemComponentByOrderID($orderID);

        return $orderItemComponentArray;

        //print_r($orderItemComponentArray);

    }

    public function getOrderItemComponentArrayFromOrderItemID($orderItemID)
    {
        $orderItemComponentArray = $this->orderitemcomponentmodel->getOrderItemComponentByOrderItemID($orderItemID);
        //print_r($orderItemComponentArray);

        return $orderItemComponentArray;

    }
    public function checkOrderItemComponentLinkedKeysFromOrderItemID($orderItemID)
    {
        $getOrderItemComponentID = "";
        $orderItemComponentArray        =  $this->getOrderItemComponentArrayFromOrderItemID($orderItemID);

        // get the OrderItemID only when linked equipmentID and Linked Equipment Mode ID are not empty.
        for($i=0; $i<sizeof($orderItemComponentArray); $i++)
        {
            if(($orderItemComponentArray[$i]['kf_LinkedEquipmentID'] != null && $orderItemComponentArray[$i]['kf_LinkedEquipmentID'] != 0) &&
               ($orderItemComponentArray[$i]['kf_LinkedEquipmentModeID'] != null && $orderItemComponentArray[$i]['kf_LinkedEquipmentModeID'] != 0) &&
               ($orderItemComponentArray[$i]['nb_CustomPrintSpecs'] != 1)
              )
            {
                $getOrderItemComponentID = $orderItemComponentArray[$i]['kp_OrderItemComponentID'];
            }
        }
        return $getOrderItemComponentID;

    }

    public function getOrderItemComponentArray($selectedOrderItemID,$orderItemDescription,$lastInsertedOrderItemID)
    {
        //echo "oicOI".$selectedOrderItemID."<br>";
        // Get the OrderItemComponent Data/Array

        $orderItemComponentArray        =  $this->getOrderItemComponentArrayFromOrderItemID($selectedOrderItemID);
        //$orderItemComponentArray        =  $this->orderitemcomponentmodel->getOrderItemComponentByOrderItemID($selectedOrderItemID);

        // update the selected row with the submitted data
        $orderItemComponentUpdatedArray =  $this->updateOICArrayOnSelect($orderItemComponentArray,$orderItemDescription,$lastInsertedOrderItemID);

        // Insert the updated $orderItemComponentArray into OrderItemComponent Table
        $this->submitOrderItemComponentTable($orderItemComponentUpdatedArray);

    }
    public function updateOICArrayOnSelect($orderItemComponentArray,$orderItemDescription,$lastInsertedOrderItemID)
    {
        $lenArr                  = sizeof($orderItemComponentArray);
        //echo "<br>".$lenArr."<br>";
        for($i = 0; $i<$lenArr; $i++)
        {
            $orderItemComponentArray[$i]['kf_OrderItemID']          = $lastInsertedOrderItemID;

            $orderItemComponentArray[$i]['kp_OrderItemComponentID'] = '';

            $orderItemComponentArray[$i]['t_Description']           = $orderItemDescription;
        }

        return $orderItemComponentArray;

    }
    public function submitOrderItemComponentTable($orderItemComponentUpdatedArray)
    {
        // Insert the updated $orderItemComponentArray into OrderItemComponent Table
        $this->orderitemcomponentmodel->insertOrderItemComponentTable($orderItemComponentUpdatedArray);

    }
    public function getPrintMaterialInfo($orderItemInsertedID,$quantity,$height,$width)
    {

        //echo "printM".$orderItemInsertedID."<br>";
        $printMaterialData = $this->orderitemcomponentmodel->printMaterial($orderItemInsertedID);

        //echo "printMData". $printMaterialData['kp_OrderItemComponentID'];
        if($printMaterialData == "Completed")
        {
            echo "<p><strong>Completed</strong></p>";

        }
//        if($printMaterialData['kp_OrderItemComponentID'] == "" ||
//           $printMaterialData['n_Quantity']              == "" ||
//           $printMaterialData['n_HeightInInches'] =="")
//        {
//            echo "<p><strong>Completed</strong></p>";
//
//        }
        else
        {
            $data = array(
                        'n_Quantity' => $quantity,
                        'n_HeightInInches' => $height,
                        'n_WidthInInches' => $width
                        );
            //$this->updateOIC($printMaterialData['kp_OrderItemComponentID'],$quantity,$height,$width,null,null);
            $this->updateOIC($printMaterialData['kp_OrderItemComponentID'],$data);
            echo "<p><strong>Completed!</strong></p>";
        }




    }

//    public function updateOIC($orderItemComponentID,$quantity="",$height="",$width="",$linkedEquipmentID="",$linkedEquipmentModeID="")
//    {
//        //echo "printMupdate".$orderItemComponentID."<br>";
//        $this->orderitemcomponentmodel->updateOrderItemComponent($orderItemComponentID,$quantity,$height,$width,$linkedEquipmentID,$linkedEquipmentModeID);
//    }
    public function updateOIC($orderItemComponentID,$data)
    {
        //echo "printMupdate".$orderItemComponentID."<br>";
        $this->orderitemcomponentmodel->updateOrderItemComponent($orderItemComponentID,$data);
    }
    public function getDupFinAllLineItems($orderID)
    {
        $bleedWhitePocketDupValues             = $this->input->post('resultValues');
        $bleedWhitePocketDupKeys               = $this->input->post('resultKeys');

        $bleedWhitePocketDup                   = array_combine($bleedWhitePocketDupKeys, $bleedWhitePocketDupValues);



        //echo $orderID."<br/>";
        //print_r($bleedWhitePocketDup);

       $this->orderitemcomponentmodel->dupFinAllLineItems($orderID,$bleedWhitePocketDup);
        //print_r($row);

    }
    public function calculateBleedWhitePocketFeetInches($bWp)
    {
        //------------------------ Bleed ----------------------------------------
        //-- bleed Inches
        $bleedHeightInches               = $bWp['heightInInches']+$bWp['bleedTop']+$bWp['bleedBottom']."\""." x ";
        $bleedWidthInches                = $bWp['widthInInches']+$bWp['bleedLeft']+$bWp['bleedRight']."\"";

        //--bleed Height Feet
        $bleedHeightFullFeet             = ($bWp['heightInInches']+$bWp['bleedTop']+$bWp['bleedBottom'])/12;
        $bleedHeightJustFeet             = floor($bleedHeightFullFeet);
        $bleedHeightDecimalFeetInInches  = round(($bleedHeightFullFeet - $bleedHeightJustFeet) * 12);


        //--bleed Width Feet
        $bleedWidthFullFeet              = ($bWp['widthInInches']+$bWp['bleedLeft']+$bWp['bleedRight'])/12;
        $bleedWidthJustFeet              = floor($bleedWidthFullFeet);
        $bleedWidthDecimalFeetInInches   = round(($bleedWidthFullFeet - $bleedWidthJustFeet) * 12);


        //--- final bleed values
        $bWp['bleedFeet']                = $bleedHeightJustFeet.'\''.'   '.$bleedHeightDecimalFeetInInches
                                           ."\""." x ".$bleedWidthJustFeet.'\''.'   '.$bleedWidthDecimalFeetInInches."\"";

        $bWp['bleedInches']              = $bleedHeightInches.$bleedWidthInches;

        //------------------------ White --------------------------------------------
        //-- White Inches
        $whiteHeightInches               = $bWp['heightInInches']+$bWp['bleedTop']+$bWp['bleedBottom']+$bWp['whiteTop']+$bWp['whiteBottom']."\""." x ";
        $whiteWidthInches                = $bWp['widthInInches']+$bWp['bleedLeft']+$bWp['bleedRight']+$bWp['whiteLeft']+$bWp['whiteRight']."\"";

        //--White Height Feet
        $whiteHeightFullFeet             = ($bWp['heightInInches']+$bWp['bleedTop']+$bWp['bleedBottom']+$bWp['whiteTop']+$bWp['whiteBottom'])/12;
        $whiteHeightJustFeet             = floor($whiteHeightFullFeet);
        $whiteHeightDecimalFeetInInches  = round(($whiteHeightFullFeet - $whiteHeightJustFeet) * 12);


        //--White Width Feet
        $whiteWidthFullFeet              = ($bWp['widthInInches']+$bWp['bleedLeft']+$bWp['bleedRight']+$bWp['whiteLeft']+$bWp['whiteRight'])/12;
        $whiteWidthJustFeet              = floor($whiteWidthFullFeet);
        $whiteWidthDecimalFeetInInches   = round(($whiteWidthFullFeet - $whiteWidthJustFeet) * 12);

        //--- final White values
        $bWp['whiteFeet']                = $whiteHeightJustFeet.'\''.'   '.$whiteHeightDecimalFeetInInches."\""." x ".$whiteWidthJustFeet
                                           .'\''.'   '.$whiteWidthDecimalFeetInInches."\"";
        $bWp['whiteInches']              = $whiteHeightInches.$whiteWidthInches;

        //------------------------ Pocket --------------------------------------------
         //-- Pocket Inches
        $pocketHeightInches              = $bWp['heightInInches']+$bWp['bleedTop']+$bWp['bleedBottom']+$bWp['whiteTop']+$bWp['whiteBottom']
                                            +$bWp['pocketTop']+$bWp['pocketBottom']."\""." x ";
        $pocketWidthInches               = $bWp['widthInInches']+$bWp['bleedLeft']+$bWp['bleedRight']+$bWp['whiteLeft']+$bWp['whiteRight']+
                                            $bWp['pocketLeft']+$bWp['pocketRight']."\"";

         //--Pocket Height Feet
        $pocketHeightFullFeet            = ($bWp['heightInInches']+$bWp['bleedTop']+$bWp['bleedBottom']+$bWp['whiteTop']+$bWp['whiteBottom']
                                            +$bWp['pocketTop']+$bWp['pocketBottom'])/12;
        $pocketHeightJustFeet            = floor($pocketHeightFullFeet);
        $pocketHeightDecimalFeetInInches = round(($pocketHeightFullFeet - $pocketHeightJustFeet) * 12);

        //--Pocket Width Feet
        $pocketWidthFullFeet             = ($bWp['widthInInches']+$bWp['bleedLeft']+$bWp['bleedRight']+$bWp['whiteLeft']+$bWp['whiteRight']
                                            +$bWp['pocketLeft']+$bWp['pocketRight'])/12;
        $pocketWidthJustFeet             = floor($pocketWidthFullFeet);
        $pcoketWidthDecimalFeetInInches  = round(($pocketWidthFullFeet - $pocketWidthJustFeet) * 12);

        //--- final Pocket values
        $bWp['pocketFeet']               = $pocketHeightJustFeet.'\''.'  '.$pocketHeightDecimalFeetInInches."\""." x ".$pocketWidthJustFeet
                                           .'\''.'   '.$pcoketWidthDecimalFeetInInches."\"";
        $bWp['pocketInches']             = $pocketHeightInches.$pocketWidthInches;

        return $bWp;
        //print_r($bWp);


    }
    public function updateLinkedPrintMaterialData($oicEquipmentID,$oicPrintMaterialID)
    {
        $resultValues                             = array();
        $row                                      = $this->orderitemcomponentmodel->getOrderItemComponentByID($oicEquipmentID);

        $linkedEquipmentID                        = $row['kf_EquipmentID'];
        $linkedEquipmentModeID                    = $row['kf_EquipmentModeID'];
        $data = array(
                        'kf_LinkedEquipmentID'=>$linkedEquipmentID,
                        'kf_LinkedEquipmentModeID'=>$linkedEquipmentModeID
                       );
        $this->updateOIC($oicPrintMaterialID,$data);

        $row                                     = $this->orderitemcomponentmodel->getOrderItemComponentByID($oicPrintMaterialID);
        $resultValues[0]                         = $row['kf_LinkedEquipmentID'];
        $resultValues[1]                         = $row['kf_LinkedEquipmentModeID'];

        //print_r($resultValues);
        return $resultValues;


    }
    public function populatePrintMaterialFrm($orderItemComponentID)
    {
        $printMaterialFrmData                     = array();
        $bWp                                      = array();


        $row                                      = $this->orderitemcomponentmodel->getOrderItemComponentByID($orderItemComponentID);

        $printMaterialFrmData['Equipment']        = Modules::run('equipment/equipmentcontroller/getEquipmentData', $row['kf_LinkedEquipmentID']);
        $printMaterialFrmData['Mode']             = Modules::run('equipmentModes/equipmentmodecontroller/getEquipmentModeData', $row['kf_LinkedEquipmentModeID']);
        $printMaterialFrmData['SubMode']          = Modules::run('equipmentSubModes/equipmentsubmodecontroller/getEquipmentSubModeData',$row['kf_LinkedEquipmentID'], $row['kf_LinkedEquipmentModeID'],$row['kf_EquipmentSubModeID']);


        //------START-------secondary check to update print material from the equipment category -------------------------START--------------------//
        $oicEquipmentID                           = "";
        $displayCategory                          = $this->input->get('displayCategory');

        if($displayCategory == "Print Material" && (($row['kf_LinkedEquipmentID']=="0" || is_null($row['kf_LinkedEquipmentID'])) || ($row['kf_LinkedEquipmentModeID']=="0" || is_null($row['kf_LinkedEquipmentModeID']))))
        {

            //check if there is a Equipment in the right side table.
             $getProductBuildData      = $this->orderitemcomponentmodel->getProductBuildData($row['kf_OrderItemID']);
              for($i=0; $i<sizeof($getProductBuildData); $i++)
              {
                   if($getProductBuildData[$i]->t_Category == "Equipment")
                   {
                       //if there is one call printMaterial Update method
                       $oicEquipmentID                    = $getProductBuildData[$i]->OrderItemComponentID;
                       $updatedLinkedEquip                = $this->updateLinkedPrintMaterialData($oicEquipmentID,$orderItemComponentID);
                       $printMaterialFrmData['Equipment'] = Modules::run('equipment/equipmentcontroller/getEquipmentData', $updatedLinkedEquip[0]);
                       $printMaterialFrmData['Mode']      = Modules::run('equipmentModes/equipmentmodecontroller/getEquipmentModeData', $updatedLinkedEquip[1]);
                       $printMaterialFrmData['SubMode']   = Modules::run('equipmentSubModes/equipmentsubmodecontroller/getEquipmentSubModeData',$updatedLinkedEquip[0], $updatedLinkedEquip[1],$row['kf_EquipmentSubModeID']);
                   }
              }
        }
        //--------END----secondary check to update print material from the equipment category ------------------END------------------------//


        $printMaterialFrmData['printDoubleSided'] = $row['nb_PrintDoubleSided'];

        $printMaterialFrmData['Description']      = $row['t_Description'];

        $printMaterialFrmData['Direction']        = $row['t_Directions'];


        $printMaterialFrmData['Quantity']         = $row['n_Quantity'];
        $printMaterialFrmData['Height']           = $row['n_HeightInInches'];
        $printMaterialFrmData['Width']            = $row['n_WidthInInches'];
        $printMaterialFrmData['CustomPrintSpecs'] = $row['nb_CustomPrintSpecs'];
        $printMaterialFrmData['orderID']          = $row['kf_OrderID'];
        $printMaterialFrmData['inventoryItemID']  = $row['kf_InventoryItemID'];
        //$printMaterialFrmData['inventoryItemDes'] = Modules::run('inventoryItems/inventoryitemcontroller/getInventoryItemData', $row['kf_InventoryItemID']);
        $printMaterialFrmData['inventoryItemDes'] = Modules::run('inventoryItemsToBuildItemsLink/inventoryitemstobuilditemslinkcontroller/getInventoryItemValueList', $row['kf_BuildItemID']);

        //$printMaterialFrmData['onHandMinMax']     = $this->getOnHandMaxMinData($row['kf_InventoryItemID']);
        $printMaterialFrmData['onHandMinMax']     = Modules::run('inventoryItems/inventoryitemcontroller/getInventoryOnHandMinMaxData',$row['kf_InventoryItemID']);

        $printMaterialFrmData['bleedWhitePocket'] = Modules::run('orderItemQuickSize/orderitemquicksizecontroller/getOrderItemQuickSizeInfo');


        // ----- Bleed ------
        $bWp['bleedTop']                          = $row['n_BleedTop'];
        $bWp['bleedBottom']                       = $row['n_BleedBottom'];
        $bWp['bleedLeft']                         = $row['n_BleedLeft'];
        $bWp['bleedRight']                        = $row['n_BleedRight'];

         // ----- White ------
        $bWp['whiteTop']                          = $row['n_WhiteTop'];
        $bWp['whiteBottom']                       = $row['n_WhiteBottom'];
        $bWp['whiteLeft']                         = $row['n_WhiteLeft'];
        $bWp['whiteRight']                        = $row['n_WhiteRight'];

        // ----- Pocket ------
        $bWp['pocketTop']                         = $row['n_PocketTop'];
        $bWp['pocketBottom']                      = $row['n_PocketBottom'];
        $bWp['pocketLeft']                        = $row['n_PocketLeft'];
        $bWp['pocketRight']                       = $row['n_PocketRight'];

        $bWp['heightInInches']                    = $row['n_HeightInInches'];
        $bWp['widthInInches']                     = $row['n_WidthInInches'];

        //$printMaterialFrmData['bWpData']          = $bWp;

        $printMaterialFrmData['bWpData']          = $this->calculateBleedWhitePocketFeetInches($bWp);

        $printMaterialFrmData['showOnInvoice']    = $row['nb_ShowOnInvoice'];
        //print_r($printMaterialFrmData);

        $printMaterialFrmData['getDirection']     = Modules::run('buildItems/builditemcontroller/getAddDirection',$row['kf_BuildItemID']);

        $printMaterialFrmData['title']            = $row['t_Tiled'];
        $printMaterialFrmData['overlapTile']      = $row['n_OverlapTile'];

        $printMaterialFrmData['equipSubModeID']   = $row['kf_EquipmentSubModeID'];

        echo json_encode($printMaterialFrmData);
    }
    public function updateSetupModalFrm()
    {
        $typeOfForm                   = $this->input->post('typeOfForm');

        $typeOfFormNotConnected       = $this->input->post('typeOfFormNotConnected');

        $orderItemComponentID         = $this->input->post('orderItemComponentIDHidden');

        $modalShowOnInvoice           = $this->input->post('modalShowOnInvoice');

        // for Description
        if($typeOfForm == "Description" || ($typeOfForm == "Inventory" && $typeOfFormNotConnected == "1"))
        {
            //collect only description
            $modalDescription         = $this->input->post('modalDescription');

            //update the value in the OIC table.
            $data = array(
                        't_Directions' => $modalDescription,
                        'nb_ShowOnInvoice' => $modalShowOnInvoice,

                        'nb_InvalidProductBuild'   => null
                     );


            $this->updateOIC($orderItemComponentID, $data);

        }
        if($typeOfForm == "Inventory" && $typeOfFormNotConnected != "1")
        {
            //collect  description and Inventory Item Description
            $modalDescription         = $this->input->post('modalDescription');
            $inventoryItemDesc        = $this->input->post('inventoryItemDesc');

            //update the value in the OIC table.
            $data = array(
                        't_Directions'       => $modalDescription,
                        'kf_InventoryItemID' => $inventoryItemDesc,
                        'nb_ShowOnInvoice'   => $modalShowOnInvoice,

                        'nb_InvalidProductBuild'   => null
                     );

            $this->updateOIC($orderItemComponentID, $data);
        }
        if($typeOfForm == "All")
        {
            //collect  Everything
            $modalEquipment           = $this->input->post('modalEquipment');
            $modalPrintDoubleSided    = $this->input->post('modalPrintDoubleSided');

            $modalMode                = $this->input->post('modalMode');
            $modalTitle               = $this->input->post('modalTitle');

            $modalSubMode             = $this->input->post('modalSubMode');
            $modalOverlap             = $this->input->post('modalOverlap');

            if($modalOverlap == "")
            {
                $modalOverlap = null;

            }

            $modalDescription         = $this->input->post('modalDescription');

            $modalQty                 = $this->input->post('modalQty');
            $modalHeight              = $this->input->post('modalHeight');
            $modalWidth               = $this->input->post('modalWidth');

            $modalCustomPrintSpecs    = $this->input->post('modalCustomPrintSpecs');

            $bleedWhitePocket         = $this->input->post('bleedWhitePocket');

            $bleedTop                 = $this->input->post('bleedTop');
            $bleedBottom              = $this->input->post('bleedBottom');
            $bleedLeft                = $this->input->post('bleedLeft');
            $bleedRight               = $this->input->post('bleedRight');

            $whiteTop                 = $this->input->post('whiteTop');
            $whiteBottom              = $this->input->post('whiteBottom');
            $whiteLeft                = $this->input->post('whiteLeft');
            $whiteRight               = $this->input->post('whiteRight');

            $pocketTop                = $this->input->post('pocketTop');
            $pocketBottom             = $this->input->post('pocketBottom');
            $pocketLeft               = $this->input->post('pocketLeft');
            $pocketRight              = $this->input->post('pocketRight');

            $inventoryItemDesc        = $this->input->post('inventoryItemDesc');


            //update the value in the OIC table.
            $data = array(
                        //'kf_LinkedEquipmentID'      => $modalEquipment,
                        'nb_PrintDoubleSided'       => $modalPrintDoubleSided,

                        //'kf_LinkedEquipmentModeID'  => $modalMode,
                        't_Tiled'                   => $modalTitle,

                        'kf_EquipmentSubModeID'     => $modalSubMode,
                        'n_OverlapTile'             => $modalOverlap,

                        't_Description'             => $modalDescription,

                        'n_Quantity'                => $modalQty,
                        'n_HeightInInches'          => $modalHeight,
                        'n_WidthInInches'           => $modalWidth,

                        'nb_CustomPrintSpecs'       => $modalCustomPrintSpecs,

                        'n_BleedTop'                => $bleedTop,
                        'n_BleedBottom'             => $bleedBottom,
                        'n_BleedLeft'               => $bleedLeft,
                        'n_BleedRight'              => $bleedRight,

                        'n_WhiteTop'                => $whiteTop,
                        'n_WhiteBottom'             => $whiteBottom,
                        'n_WhiteLeft'               => $whiteLeft,
                        'n_WhiteRight'              => $whiteRight,

                        'n_PocketTop'               => $pocketTop,
                        'n_PocketBottom'            => $pocketBottom,
                        'n_PocketLeft'              => $pocketLeft,
                        'n_PocketRight'             => $pocketRight,

                        'kf_InventoryItemID'        => $inventoryItemDesc,
                        'nb_ShowOnInvoice'          => $modalShowOnInvoice,

                        'nb_InvalidProductBuild'   => null
                     );

            //print_r($data);
            $this->updateOIC($orderItemComponentID, $data);
        }
        //do the remaining update
        if($this->input->post('duplicateLineItemNotEP') == "true")
        {
            $this->dupLineItemExceptPrintEquipOIC($this->input->post('orderItemIDUniqueHidden'), $orderItemComponentID, $this->input->post('orderIDUniqueHidden'));

        }
    }
}

?>
