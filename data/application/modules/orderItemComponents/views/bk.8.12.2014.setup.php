<!DOCTYPE html>
<html ang="en">
    <meta charset=UTF-8">
    
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="no-cache">
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Cache-Control" content="no-cache">
    
        <title>Set Up</title>
        <base href="<?php echo base_url(); ?>" />
        
        <script type="text/javascript">
             var orderID         = '<?php echo $orderID;?>';
             //alert("first: ");
             var orderItemID     = <?php echo $orderItemID?>;
             var productBuildID  = <?php echo $productBuildID?>;
             
             
            
        </script>
        
        <link href="media/css_bootstrap/bootstrap.css" rel="stylesheet" type="text/css">
        
        <link href="media/css_bootstrap/bootstrap-responsive.css" rel="stylesheet" type="text/css">
        
        <!---------- Bootstrap Modal CSS ----------------->
        <link href="media/css_bootstrap/bootstrap-modal.css" rel="stylesheet" />
        
       
        
        <script type="text/javascript" charset="utf-8" src="media/js/jquery.js"></script>
        <script src="chosen/chosen.jquery.js" type="text/javascript"></script>
        <script src="js/jquery.maskedinput.js"></script>
        <script src="js/jquery.validate.min.js"></script>
        <script src="js/additional-methods.min.js"></script>
       
<!--        <script type="text/javascript" charset="utf-8" src="media/js_bootstrap/bootstrap.min.js"></script>-->
        <script type="text/javascript" charset="utf-8" src="media/js_bootstrap/bootstrap.js"></script>
        
        
        
        <script type="text/javascript" src="js/jquery-templ.js"></script>
        
        <!------------ Bootstrap Modal JS ------------->
        <script src="media/js_bootstrap/bootstrap-modalmanager.js"></script>
        <script src="media/js_bootstrap/bootstrap-modal.js"></script>
        
        <script src="js/clearForm.js"></script>
        
        <script src="js/oicModuleSetup.js"></script>
        
        <style type="text/css">
/*                #setUpPrintMaterialForm {
                    width: 990px;  SET THE WIDTH OF THE MODAL 
                    
                    margin: -429px 0 0 -390px;  margin: -199px 0 0 -230px; CHANGE MARGINS TO ACCOMODATE THE NEW WIDTH (original = margin: -250px 0 0 -280px;) 
                    margin: -309px 0 0 -490px;
                }
                #setUpPrintMaterialForm .modal-body {
                    max-height: 590px;
                    max-height: 490px;
                } */
                #printMaterialFrm .test78 {
                    margin-bottom: 10px;
                /*if help block is in use 8 Tightens up the space inbetween fields */
                }   
                body {
/*                    width: 800px;*/
                    padding-top: 40px;
                    padding-bottom: 40px;
/*                    background-color: #f5f5f5;*/
/*                    margin: 0 auto;
                    padding: 10px 20px;*/
                }
         
            
                legend     {
                        font-size: 107.5%;
                }
                label.error {
                        display: inline-block;
			font-weight: bold;
			color: red;
			font-size: 87.5%;
                        padding: 2px 8px;
			margin-top: 2px;
			
		}
                #ajaxLoadAni {
/*                    background: #3A3A3A;
                    color: #fff;*/

                    /* we hide it because we only need to display it when ajax call is made */
/*                    display: none;*/

                    font-weight: bold;
                    position: absolute;
/*                    position: fixed;*/
/*                    top: 0;*/
/*                    left: 40%;
                    padding: 8px;*/
                    width: 126px;
                    z-index: 9999;
/*                    z-index: 9999;*/
                }
 
                #ajaxLoadAni span {
                    float: right;
                    margin: 1px 0 0 0;
                }
                #qtyHwAdjustWidth{
                    margin-left: 180px !important;
                    width:105px;
                }
                #dupExceptEquipPrintMat{
                    cursor:pointer;
                }
                
                input#modalQty{
                    width:46px;
                }
                input#modalHeight{
                    width:46px;
                }
                input#modalWidth{
                    width:46px;
                }
                
/*                #orderItemTotalText {
                   clear:both;float:right;
                }*/

      
        </style>
        <link rel="stylesheet" href="chosen/chosen.css" />
    </head>
    <body>
        <div class="container-fluid">
            <section class="row-fluid">
                <div class="span12">
                    <div class="row-fluid">
                        <div class="span12">
                            <h4 id="setUpHeading"></h4>
                        </div>
                        
                    </div>
                    
                </div>
            </section>
            <section class="row-fluid">
                <div id="oicSetup" class="span12">
                    <div class="row-fluid">
                        <div class="span3">
                            <select  id="oicSetupLeft" name="oicSetupLeft" data-placeholder="Related BuiltItems" style="width:350px;" class="chzn-select" >
          
                            </select>
                        </div>
                        <div class="span1">
                            
                        </div>
<!--                         <div class="span8">
                               <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="records" >
                            <thead>
                                <tr>
                                    <th>Manufacturing Category</th>
                                    <th>Sub-Category/Name</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    
                                </tr>
                            </tbody>
                        </table>
                        
                        <script type="text/template" id="readTemplate">
                            <tr id="${OrderItemComponentID}">
                                <td>${t_Category}</td>
                                <td><a id="displayForm"    class="formBtn"   href="${formLink}">${DisplayName}</a></td>
                                <td><a id="removeTableRow" class="deleteBtn" href="${deleteLink}"><i class="icon-remove"></i></a></td>
                            </tr>
                        </script>     
                            
                        </div>-->
                        
                    </div>
                    
                </div>
            </section><br/>
            <section class="row-fluid">
                <div  class="span12">
                    <div class="row-fluid">
                        <div class="span12">
                            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="records" >
                                <thead>
                                    <tr>
                                        <th>BuildItem</th>
                                        <th>Category</th>
                                        <th>Direction</th>
                                        <th>Inventory</th>
                                        <th></th>
                                        <th class="hide"></th> 
                                        <th class="hide"></th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        
                                    </tr>
                                    
                                </tbody>
                            </table>
                            <script type="text/template" id="readTemplate">
                                    <tr class="${indicateError}" id="${OrderItemComponentID}">
                                        <td><a id="displayForm"    class="formBtn"   href="${formLink}">${DisplayName}</a>${forPrint}</td>
                                        <td>${t_Category}</td>
                                        <td>${Direction}${forPrintDirection}</td>
                                        <td>${InvID}&nbsp;${INVOH}&nbsp;${InvDesc}</td>
                                        <td><a id="removeTableRow" class="deleteBtn" href="${deleteLink}"><i class="icon-remove"></i></a></td>
                                        <td class="hide">${t_FormView}</td>
                                        <td class="hide">${nb_NotConnectedToInventory}</td>
                                    </tr>
                            </script>   
                        </div>
                    </div>
                </div>
            </section>
                
            <section class="row-fluid">
                <div id="setUpPrintMaterialForm" class="modal hide" data-width="780">
                    <div class="modal-header">
                         <h3 id="modalCustomHeadingPrintMaterialForm">Print Material -</h3>
                    </div>
                    <div class="modal-body">
                        <section class="row-fluid">
                             <form class="form-horizontal test78" name="printMaterialFrm" id="printMaterialFrm">
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="span3">
                                            <div class="control-group test78">
                                                <label class="control-label" for="modalEquipment">Equipment: </label>
                                                <div class="controls">
                                                      <input type="text" class="input-medium" placeholder="Equipment Name"  name="modalEquipment" id="modalEquipment"> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="offset2 span5">
                                            <div class="control-group test78">
                                                <div class="controls">
                                                    <label class="checkbox">
                                                         <input type="checkbox" value="1" name="modalPrintDoubleSided" id="modalPrintDoubleSided"> Print Double Sided
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="span3">
                                            <div class="control-group test78">
                                                 <label class="control-label" for="modalMode">Mode: </label>
                                                 <div class="controls">
                                                      <input type="text" class="input-medium" placeholder="Mode"  name="modalMode" id="modalMode"> 
                                                 </div>
                                            </div>
                                        </div>
                                        <div class="offset2 span5">
                                            <div class="control-group test78">
                                                <label class="control-label" for="modalTitle">Tile: </label>
                                                <div class="controls">
                                                     <select name="modalTitle" id="modalTitle" style="width:12em;">
                                                         <option value=""></option>
<!--                                                         <option value="Empty">Empty</option>-->
                                                         <option value="DO NOT TILE">DO NOT TILE</option>
                                                         <option value="Vertically">Vertically</option>
                                                         <option value="Horizontally">Horizontally</option>
                                                     </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="span3">
                                            <div class="control-group test78">
                                                 <label class="control-label" for="modalSubMode">Sub Mode: </label>
                                                 <div class="controls">
                                                     <select name="modalSubMode" id="modalSubMode" style="width:12em;">
                                        
                                                     </select>
                                                     
                                                 </div>
                                            </div>
                                        </div>
                                        <div class="offset2 span5">
                                            <div class="control-group test78">
                                                <label class="control-label" for="modalOverlap">Overlap: </label>
                                                <div class="controls">
                                                     <select name="modalOverlap" id="modalOverlap" style="width:12em;">
                                                         <option value=""></option>
                                                         <option value="0">0</option>
                                                         <option value="0.25">.25</option>
                                                         <option value="0.5">.50</option>
                                                         <option value="0.75">.75</option>
                                                         <option value="1">1</option>
                                                         <option value="1.5">1.5</option>
                                                         <option value="2">2</option>
                                                         <option value="2.5">2.5</option>
                                                         <option value="3">3</option>
                                                     </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="descriptionShow" class="row-fluid">
                                    <div class="span12">
                                        <div class="control-group test78">
                                            <label id="DescriptionNameLabel" class="control-label" for="modalDescription">Description: </label>
                                            <div class="controls">
                                                <div class="input-prepend">
                                                     <div class="btn-group">
                                                        <button class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                                            <ul id="dynamicDirection" class="dropdown-menu">
                                                             
                                                            </ul>
                                                    </div><!-- /btn-group -->
                                                    <input type="text" class="input-xxlarge"  name="modalDescription" id="modalDescription">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div id="qtyHwAdjustWidth" class="span3">
                                            <div class="test78">
                                                <div class="input-prepend">
                                                    <span class="add-on">Qty</span>
                                                    <input class="span4 changeMini"  type="text" name="modalQty"  id="modalQty">
                                                </div> 
                                            </div>
                                        </div>
                                        <div class="span2">
                                            <div class="test78">
                                                <div class="input-prepend">
                                                     <span class="add-on">H</span>
                                                     <input class="span4 changeMini"  type="text" name="modalHeight"  id="modalHeight">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span2">
                                            <div class="test78">
                                                <div class="input-prepend">
                                                    <span class="add-on">W</span>
                                                    <input class="span4 changeMini"  type="text" name="modalWidth"  id="modalWidth">
                                                </div> 
                                            </div>
                                        </div>
                                        <div class="span3">
                                            <div class="test78">
                                                  <label class="checkbox">
                                                     <input type="checkbox" value="1" name="modalCustomPrintSpecs" id="modalCustomPrintSpecs"> Custom Print Specs
                                                  </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="span5">
                                            <div class="control-group test78">
                                                 <label class="control-label" for="bleedWhitePocket">Bleed/White/Pocket: </label>
                                                 <div class="controls">
                                                     <select name="bleedWhitePocket" id="bleedWhitePocket" style="width:12em;">
                                                         
                                                     </select>
                                                 </div>
                                            </div>
                                        </div>
                                         <div class="offset1 span5">
                                             <a id="dupFinLineItems" href="#" >Duplicate Finishing Across All Line Items</a>
                                         </div>
                                      
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="offset1 span10">
                                            <table  class="table table-striped table-bordered table-condensed" id="tableBleedWhitePocked">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>T</th>
                                                        <th>B</th>
                                                        <th>L</th>
                                                        <th>R</th>
                                                        <th>Inches</th>
                                                        <th>Feet</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>B</td>
                                                        <td>
                                                             <input class="input-mini dynamicBleed" name="bleedTop" type="text" id="bleedTop" />
                                                        </td>
                                                        <td>
                                                            <input class="input-mini dynamicBleed" name="bleedBottom" type="text" id="bleedBottom" />
                                                        </td>
                                                        <td>
                                                             <input class="input-mini dynamicBleed" name="bleedLeft" type="text" id="bleedLeft" />
                                                        </td>
                                                        <td>
                                                             <input class="input-mini dynamicBleed" name="bleedRight" type="text" id="bleedRight" />
                                                        </td>
                                                        <td id="bleedInches" >
                                                          
                                                            
                                                        </td>
                                                        <td id="bleedFeet">
                                                             
                                                            
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>W</td>
                                                        <td>
                                                            <input class="input-mini dynamicWhite" name="whiteTop" type="text" id="whiteTop" />
                                                        </td>
                                                        <td>
                                                             <input class="input-mini dynamicWhite" name="whiteBottom" type="text" id="whiteBottom" />
                                                        </td>
                                                        <td>
                                                             <input class="input-mini dynamicWhite" name="whiteLeft" type="text" id="whiteLeft" />
                                                        </td>
                                                        <td>
                                                            <input class="input-mini dynamicWhite" name="whiteRight" type="text" id="whiteRight" />
                                                        </td>
                                                        <td id="whiteInches">
                                                            
                                                        </td>
                                                        <td id="whiteFeet">
                                                            
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>P</td>
                                                        <td>
                                                            <input class="input-mini dynamicPocket" name="pocketTop" type="text" id="pocketTop" />
                                                        </td>
                                                        <td>
                                                             <input class="input-mini dynamicPocket" name="pocketBottom" type="text" id="pocketBottom" />
                                                        </td>
                                                        <td>
                                                             <input class="input-mini dynamicPocket" name="pocketLeft" type="text" id="pocketLeft" />
                                                        </td>
                                                        <td>
                                                             <input class="input-mini dynamicPocket" name="pocketRight" type="text" id="pocketRight" />
                                                        </td>
                                                        <td id="pocketInches">
                                                            
                                                        </td>
                                                        <td id="pocketFeet">
                                                            
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div id="inventoryShow" class="row-fluid">
                                    <div class="span12">
                                        <div class="span11">
                                            <div  class="control-group test78">
                                                <label class="control-label" for="inventoryItemDesc">Inventory Item: </label>
                                                <div class="controls">
                                                    <select name="inventoryItemDesc" id="inventoryItemDesc"   style="width:38em;">
                                                        
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="inventoryOnHandMinMaxShow" class="row-fluid">
                                    <div class="span12">
                                        <div class="offset1 span8">
                                            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered hide" id="onHandMinMaxSqFt">
                                                <thead>
                                                    <tr>
                                                        <th>On Hand</th>
                                                        <th>Min</th>
                                                        <th>Max</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td id="onHand"></td>
                                                        <td id="invMin"></td>
                                                        <td id="invMax"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="span3">
                                            <a id="emailPurchasing" class="hide" href="#" >Email Purchasing</a>
                                        </div>
                                    </div>
                                </div>
                                <div id="showBillNameInvoice" class="row-fluid">
                                    <div class="span12">
                                        <div class="offset2 span10">
                                            <label class="checkbox">
                                                 <input type="checkbox" value="1" name="modalShowOnInvoice" id="modalShowOnInvoice"> Show Build Name on Invoice
                                            </label>
                                        </div>
                                       
                                    </div>
                                </div>
                                <input type="hidden" name="orderItemComponentIDHidden"       id="orderItemComponentIDHidden" />
                                <input type="hidden" name="orderIDHidden"                    id="orderIDHidden" />
                                <input type="hidden" name="orderItemIDHidden"                id="orderItemIDHidden" />
                                
                                <input type="hidden" name="orderIDUniqueHidden"              id="orderIDUniqueHidden" />
                                <input type="hidden" name="orderItemIDUniqueHidden"          id="orderItemIDUniqueHidden" />
                               
                                
                                <input type="hidden" name="bleedTopHidden"                   id="bleedTopHidden" />
                                <input type="hidden" name="bleedBottomHidden"                id="bleedBottomHidden" />
                                <input type="hidden" name="bleedLeftHidden"                  id="bleedLeftHidden" />
                                <input type="hidden" name="bleedRightHidden"                 id="bleedRightHidden" />
                                
                                <input type="hidden" name="whiteTopHidden"                   id="whiteTopHidden" />
                                <input type="hidden" name="whiteBottomHidden"                id="whiteBottomHidden" />
                                <input type="hidden" name="whiteLeftHidden"                  id="whiteLeftHidden" />
                                <input type="hidden" name="whiteRightHidden"                 id="whiteRightHidden" />
                                
                                <input type="hidden" name="pocketTopHidden"                  id="pocketTopHidden" />
                                <input type="hidden" name="pocketBottomHidden"               id="pocketBottomHidden" />
                                <input type="hidden" name="pocketLeftHidden"                 id="pocketLeftHidden" />
                                <input type="hidden" name="pocketRightHidden"                id="pocketRightHidden" />
                                
                                <input type="hidden" name="typeOfForm"                       id="typeOfForm" />
                                <input type="hidden" name="typeOfFormNotConnected"           id="typeOfFormNotConnected" />
                                <input type="hidden" name="duplicateLineItemNotEP"           id="duplicateLineItemNotEP" />
                                
                             </form>
                        </section>
                    </div>
                    <div class="modal-footer">
                        <section class="row-fluid">
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="span4">
<!--                                          <a type="submit" id="dupExceptEquipPrintMat" class="hide"><small>Duplicate Across Line Items</small></a>-->
                                          <a id="dupExceptEquipPrintMat" class="hide" >Duplicate Across Line Items</a>
<!--                                          <button type="submit" id="dupExceptEquipPrintMat" class="btn hide" >Duplicate Across Line Items</button>-->
                                    </div>
                                    <div class="span3 offset5">
                                         <button class="btn" id="cancelModalPrintMaterialFrm" data-dismiss="modal" aria-hidden="true">Close</button>
                                         <button type="submit" id="validateModalPrintMaterialFrm" class="btn btn-primary" >Save</button>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </section>
            <section>
                 <div id="myModalConfirm" class="modal hide fade">
                    <div class="modal-header">
                        <h3 id="modalCustomHeading"></h3>
                    </div>
                    <div class="modal-body">
                        <p id="noInsertMessage"></p> 
                    </div>
                    <div id="dynamciFooterMyModalConfirm" class="modal-footer">
                        <a href="" class="btn btnlarge" id="cancelReplaceEPModalFrm" data-dismiss="modal" aria-hidden="true">No</a>
                        <a href="" id="submitReplaceEPModalFrm" class="btn btn-primary btnlarge" >Yes</a>&nbsp;&nbsp;
<!--                        <button class="btn btn-info" id="deleteCancelBtnOrderShipIDModal" data-dismiss="modal" aria-hidden="true">OK</button>-->
                    </div>
                </div>
            </section>
        </div>
    </body>
    </html>
