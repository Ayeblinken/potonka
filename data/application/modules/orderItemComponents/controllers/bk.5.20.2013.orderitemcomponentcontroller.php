<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class orderItemComponentController extends MX_Controller 
{
    //put your code here
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('orderitemcomponentmodel');
        
    }
    public function setup($orderID,$orderItemID,$productBuildID)
    {
        $data['orderID']            = $orderID;
        $data['orderItemID']        = $orderItemID;
        $data['productBuildID']     = $productBuildID;
        
        $this->load->view('setup',$data); 
        
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
        echo json_encode($this->orderitemcomponentmodel->getProductBuildData($orderItemID));
        
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
    public function processSetupLeftTableInsert($orderItemID)
    {
        date_default_timezone_set('America/Indianapolis');
        
        $resultValues             = $this->input->post('result');
        $resultKeys               = $this->input->post('resultKeys');
        $resultValues[7]          = date("Y-m-d H:i:s", time());
        
        
            
        
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
            if($categoryExists)
            {
                //display a not added message
                echo "No";
                
            }
            else
            {
                    if($resultValues[8] == "Print Material")
                    {
                        $row               = Modules::run('orderItems/orderitemcontroller/getOrderItemQtyHeightWidth',$resultValues[2]);
                        
                        $qtyHeightWidth    = explode(',', $row);
                        $resultKeys[10]     =  "n_Quantity";
                        $resultKeys[11]    =  "n_HeightInInches";
                        $resultKeys[12]    =  "n_WidthInInches";
                        $resultValues[10]   = $qtyHeightWidth[0];
                        $resultValues[11]  = $qtyHeightWidth[1];
                        $resultValues[12]  = $qtyHeightWidth[2];
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

                            
                            
                            $resultValues[13]  = $row['kf_EquipmentID'];

                            $resultValues[14]  = $row['kf_EquipmentModeID'];

                            $resultKeys[13]    = "kf_LinkedEquipmentID";
                            $resultKeys[14]    = "kf_LinkedEquipmentModeID";
                            
                            
                            //print_r($resultKeys);
                            //print_r($resultValues);
                            $result           = array_combine($resultKeys,$resultValues);
                            //print_r($result);
                            unset($result['category']);


                            $resultArray      = array($result);
                            
                            //print_r($resultArray);
                            
                           
                            
                            $lastInsertedID  = $this->orderitemcomponentmodel->insertOrderItemComponentTable($resultArray);

                            echo $lastInsertedID;
                            
                            



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
                            
                            $resultArray      = array($result);
                            
                             //print_r($resultArray);

                           
                            
                            $lastInsertedID    = $this->orderitemcomponentmodel->insertOrderItemComponentTable($resultArray);

                            echo $lastInsertedID;
                            
                            
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
                            
                            
                            $this->updateOIC($oicPrintMaterialID,$data);
                          
                            
                            // Insert a new row for the Equipment Category
                            //unset($resultValues[8]);
                            //unset($resultKeys[8]);
                            //array_pop($resultValues);
                            //array_pop($resultKeys);

                            $result           = array_combine($resultKeys,$resultValues);
                            
                            unset($result['category']);
                            


                            $resultArray      = array($result);
                            
                            //print_r($resultArray);
                            
                           

                            $lastInsertedID  = $this->orderitemcomponentmodel->insertOrderItemComponentTable($resultArray);

                            echo $lastInsertedID;
                            
                          


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
                            
                            $resultArray       = array($result);

                            
                            
                            $lastInsertedID    = $this->orderitemcomponentmodel->insertOrderItemComponentTable($resultArray);

                            echo $lastInsertedID;
                            
                           
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
            echo $lastInsertedID;
            
            
        }
        
        
    }
    public function getOrderItemComponentArrayFromOrderID($orderID)
    {
        $orderItemComponentArray        =  $this->orderitemcomponentmodel->getOrderItemComponentByOrderID($orderID);
        
        return $orderItemComponentArray;
        
        //print_r($orderItemComponentArray);
        
    }
    
    
    public function getOrderItemComponentArray($selectedOrderItemID,$orderItemDescription,$lastInsertedOrderItemID)
    {
        //echo "oicOI".$selectedOrderItemID."<br>";
        // Get the OrderItemComponent Data/Array
        $orderItemComponentArray        =  $this->orderitemcomponentmodel->getOrderItemComponentByOrderItemID($selectedOrderItemID);
        
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
        $bWp['bleedFeet']                = $bleedHeightJustFeet.'\''.' x '.$bleedHeightDecimalFeetInInches
                                           ."\""." x ".$bleedWidthJustFeet.'\''.' x '.$bleedWidthDecimalFeetInInches."\"";
          
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
        $bWp['whiteFeet']                = $whiteHeightJustFeet.'\''.' x '.$whiteHeightDecimalFeetInInches."\""." x ".$whiteWidthJustFeet
                                           .'\''.' x '.$whiteWidthDecimalFeetInInches."\"";
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
        $bWp['pocketFeet']               = $pocketHeightJustFeet.'\''.' x '.$pocketHeightDecimalFeetInInches."\""." x ".$pocketWidthJustFeet
                                           .'\''.' x '.$pcoketWidthDecimalFeetInInches."\"";
        $bWp['pocketInches']             = $pocketHeightInches.$pocketWidthInches;
        
        return $bWp;
        //print_r($bWp);
        
        
    }
    public function populatePrintMaterialFrm($orderItemComponentID)
    {
        $printMaterialFrmData                     = array();
        $bWp                                      = array();
        
        
        $row                                      = $this->orderitemcomponentmodel->getOrderItemComponentByID($orderItemComponentID);
        $printMaterialFrmData['Equipment']        = Modules::run('equipment/equipmentcontroller/getEquipmentData', $row['kf_LinkedEquipmentID']);
        $printMaterialFrmData['Mode']             = Modules::run('equipmentModes/equipmentmodecontroller/getEquipmentModeData', $row['kf_LinkedEquipmentModeID']);
        $printMaterialFrmData['SubMode']          = Modules::run('equipmentSubModes/equipmentsubmodecontroller/getEquipmentSubModeData',$row['kf_LinkedEquipmentID'], $row['kf_LinkedEquipmentModeID']);
        $printMaterialFrmData['printDoubleSided'] = $row['nb_PrintDoubleSided'];
        
        $printMaterialFrmData['Description']      = $row['t_Description'];
        
        $printMaterialFrmData['Direction']      = $row['t_Directions'];
        
        
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
                        'nb_ShowOnInvoice' => $modalShowOnInvoice
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
                        'nb_ShowOnInvoice'   => $modalShowOnInvoice
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
                        'kf_LinkedEquipmentID'      => $modalEquipment,
                        'nb_PrintDoubleSided'       => $modalPrintDoubleSided,
                
                        'kf_LinkedEquipmentModeID'  => $modalMode,
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
                        'nb_ShowOnInvoice'          => $modalShowOnInvoice
                     );
            
            $this->updateOIC($orderItemComponentID, $data);
        } 
        
    }        
}

?>
