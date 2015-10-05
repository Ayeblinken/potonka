<!DOCTYPE html>
<html ang="en">
    <head>
        <meta charset=UTF-8">
        <title>Invoice Details</title>
        <base href="<?php echo base_url(); ?>" />
        <script type="text/javascript">
            
             var requestCalled ="<?php echo $requestCalled?>";
             
             //alert("hyi1: "+requestCalled);
             var changeID            = "<?php echo $changeID?>";
             var orderIDView         = "<?php echo $orderID?>";
             //alert("hyi2: "+changeID);
            
        </script>
        
        <link href="media/css_bootstrap/bootstrap.css" rel="stylesheet" type="text/css">
        
        <link href="media/css_bootstrap/bootstrap-responsive.css" rel="stylesheet" type="text/css">
        
        <!-- fileupload CSS  -->
<!--        <link href="media/css_fileUpload/fileupload.css" rel="stylesheet" type="text/css">-->
        
        <!-- Bootstrap Date Picker CSS  -->
        <link href="datepicker/css/datepicker.css" rel="stylesheet" type="text/css">
        
        <script type="text/javascript" charset="utf-8" src="media/js/jquery.js"></script>
        
        <!------------jquery.knob------ -->
<!--        <script src="js/jquery.knob.js"></script>-->
        
        <!--------------------------File upload scripts (JS) files START------ -->
        <!-- jQuery File Upload Dependencies -->
<!--        <script src="js/jquery.ui.widget.js"></script>
        <script src="js/jquery.iframe-transport.js"></script> 
        <script src="js/jquery.fileupload.js"></script>-->

       
         
        <script src="js/jquery.maskedinput.js"></script>
        <script src="js/jquery.validate.min.js"></script>
        <script src="js/additional-methods.min.js"></script>
        <?php
         if($requestCalled == "create")
         {?>
<!--              <script type="text/javascript" charset="utf-8" src="media/js_bootstrap/bootstrap.min.js"></script>-->
              <script type="text/javascript" charset="utf-8" src="media/js_bootstrap/bootstrap.js"></script>
         <?php
         
         }   
        ?>
<!--         <script type="text/javascript" charset="utf-8" src="media/js_bootstrap/bootstrap.min.js"></script>     -->
<!--        <script type="text/javascript" charset="utf-8" src="media/js_bootstrap/bootstrap.min.js"></script>-->
        
        
<!--        <script type="text/javascript" charset="utf-8" src="media/js_bootstrap/bootstrap_v2.3.2.js"></script>-->
        
        
        
        <script type="text/javascript" src="js/jquery-templ.js"></script>
        
        <!-- Bootstrap Date Picker JS  -->
        <script type="text/javascript" charset="utf-8" src="datepicker/js/bootstrap-datepicker.js"></script>
        
        <script src="js/clearForm.js"></script>
        
        <script src="js/orderItemModuleUpSideFrm.js"></script>
        
         <!-- fileupload JS file -->
<!--       <script src="js/script.js"></script>-->
        <!--------------------------File upload scripts (JS) files END-------->
        <style type="text/css">
            #proofModalForm {
                    width: 840px; /* SET THE WIDTH OF THE MODAL */
                    
                    margin: -229px 0 0 -490px; /* margin: -199px 0 0 -230px; CHANGE MARGINS TO ACCOMODATE THE NEW WIDTH (original = margin: -250px 0 0 -280px;) */
            }
            #proofModalForm .modal-body {
                    max-height: 590px;
            } 
            #orderItemUpSideFrm .test78 {
                margin-bottom: 10px;
                /*if help block is in use 8 Tightens up the space inbetween fields */
            }
/*            .form-horizontal .control-group {
                margin-bottom: 10px;
                if help block is in use 8 Tightens up the space inbetween fields
            }*/
            .wellwhite {
                background-color: #ffffff;
                /*just for testing not used anywhere*/
            }
            .visible-sport {
                display: ;
                /* ehrn customer is sport this field is hidden */
            }

            .hidden-sport {
                display: None;
                /*when customer is sport these fields are shown */
            }
             body {
/*                    width: 800px;*/
                    padding-top: 40px;
                    padding-bottom: 40px;
/*                    background-color: #f5f5f5;*/
/*                    margin: 0 auto;
                    padding: 10px 20px;*/
                }
        </style>
        
    </head>
    
    <body>
         
            <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="brand">Job # <?php echo $orderID;?> 
                        <?php if($requestCalled == "read")
                         {
                            echo "-".$dashNum;
                            ?>
                          <?php  
                         }
                         echo "&nbsp;".$companyName;
                        ?>
                    </a>
                    <?php if($requestCalled == "read")
                    {?>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li class="">
                                <button id="dupOrderItem"  type="submit" onclick="location.href=<?php echo "'orderItems/orderitemcontroller/dup/".$changeID."/"."orderItemUpSideFrm".'\'' ?>" class="btn">Duplicate</button>  
                            </li>
                            <li>
                                &nbsp;<a id="orderItemProofAnchor" href="" class="btn"><i id="orderItemProofIcon" class="icon-ok"></i>Proof</a>
                                
                            </li>
                            <li class="dropdown">
                                 <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                      <li><a href="#">Action</a></li>
                                      <li><a href="#">Another action</a></li>
                                      <li><a href="#">Something else here</a></li>
                                      <li class="divider"></li>
                                      <li class="nav-header">Nav header</li>
                                      <li><a href="#">Separated link</a></li>
                                      <li><a href="#">One more separated link</a></li>
                                    </ul>
                            </li>
                            
                        </ul>
                    </div>    
<!--                        <button id="dupOrderItem"  type="submit" onclick="location.href=<?php echo "'orderItems/orderitemcontroller/dup/".$changeID."/"."orderItemUpSideFrm".'\'' ?>" class="btn">Duplicate</button>  
                        &nbsp;<a id="orderItemProofAnchor" href="" class="btn"><i id="orderItemProofIcon" class="icon-ok"></i> Proof</a>
                        &nbsp;<a id="orderItemPrintTicketAnchor" href="" class="btn">Print Ticket</a>-->
                        <div class="pull-right">
                            <a id="minusOrderItemDashNum" class="btn btn-small displayPagi"><i class="icon-chevron-left"></i></a>
                            <?php //echo $dashNum." of ".$getDashNum ?>
                            <a id="displayPaginationNum" class="btn btn-small">
                                <?php echo $dashNum." of ".$getDashNum ?>
                            </a>
                            <a id="addOrderItemDashNum" class="btn btn-small displayPagi"><i class="icon-chevron-right"></i></a>
                        </div> 
                    <?php
                    } ?>
                </div>
            </div>
            </div>
<!--        <br/>-->
           
     
        
        <div class="container-fluid">
            <div class="row-fluid">
                <div  id="mainOrderItemUpSideFrm" class="span9">
                    <form name="orderItemUpSideFrm" id="orderItemUpSideFrm" class="form-horizontal test78">
<!--                    <form name="orderItemUpSideFrm" id="orderItemUpSideFrm" class="form-horizontal test78" method="post" action="orderItems/orderitemcontroller/submitOrderItemUpSideFrmData">-->
                         <!-- inline form setup-->
                         <div class="row-fluid">
                             <div class="span12">
                                 <div  class="control-group test78">
                                    <span class="control-label">Qty Height Width</span>
                                    <div class="controls form-inline">
                                        <div class="input-prepend">
                                            <span class="add-on">Q</span>
                                            <!-- Orderitems.n_Qty input-->
                                            <input class="input-small totalOrderPriceEffect"  name="n_Qty" id="n_Qty" autocomplete="off"  type="text" placeholder="Qty"> 
                                        </div>
                                        <div class="input-prepend"> <span class="add-on">H</span> 
                                            <!-- Orderitems.n_Height input-->
                                            <input class="input-small totalOrderPriceEffect feetInchFormat"  name="n_Height" id="n_Height" autocomplete="off"   type="text" placeholder="Height">
                                        </div>&nbsp;<a id="reverseHWData" href=""><img src="images/Switch.png" alt="Reverse data in H and W"></a>
                                        <div class="input-prepend"> <span class="add-on">W</span> 
                                            <!-- Orderitems.n_Width input-->
                                            <input class="input-small totalOrderPriceEffect feetInchFormat" name="n_Width" id="n_Width"  autocomplete="off"  type="text" placeholder="Width">
                                        </div>&nbsp;&nbsp;
                                        <a href="" id="showInchesFeetLink">Show Inches</a>
                                        
                                              
                                        
                                    </div>
                                 </div>
                             </div>
                         </div>
                         <div class="row-fluid">
                             <div class="span12">
                                 <div class="control-group test78">
<!--                                <label class="visible-sport" for="t_Structure">Description:</label>-->
                                    <span class="control-label"><a href="" id="descriptionOrderLink">Description</a></span>
                                    <div class="controls form-inline">
                                        <!-- Orderitems.t_Description input-->
                                        <input type="text" name="t_Description" autocomplete="off" class="input-xlarge" placeholder="Description of job or artwork" id="t_Description">
                                
                                 
                                        <label  class="visible-sport"  for="t_Structure">ID:</label>
                                        <!-- Orderitems.t_Structure input-->
                                        <input class="visible-sport"  name="t_Structure" autocomplete="off" id="t_Structure" type="text" placeholder="ID info">
                                        <?php if($requestCalled == "read")
                                        {?>
                                           &nbsp;<label for="dashNum">Adjust-#</label>
                                           <input name="dashNum" id="dashNum" class="input-mini" type="text">&nbsp;
                                       <?php     
                                        }?>
                                    </div>
                                 </div>
                             </div>
                         </div>
                         <div class="row-fluid">
                             <div class="span12">
                                 <!-- inline form setup-->
                                  <div class="control-group test78">
                                        <span id="spanPricing"  class="control-label test78">Pricing</span>
                                        <div class="controls form-inline">
                                             <!-- If Order.nb_UseTotalOrderPricing=1 then hide all fields except Do not Invoice-->
                                             <!-- Orderitems.t_Pricing-->
<!--                                             <select class="span2 btn-mini" id="pricingtype" name="pricingtype">-->
                                             <select class="span3" id="pricingtype" name="pricingtype">    
                                                <option value="Line Item Pricing">Line Item Pricing</option>
                                                <option value="SQ.FT. Pricing">SQ.FT. Pricing</option>
                                             </select>
                                             <label id="labelEach" class="input">Each </label>
                                                     <!-- Orderitems.n_Price input-->
                                                     <input class="input-small totalOrderPriceEffect" autocomplete="off" name="priceEach" id="priceEach" type="text">
                                             
                                             <label id="labelSqft" class="input">SqFt</label>
                                                     <input class="input-small" name="sqFt" id="sqFt"  type="text" placeholder="Sq Ft" readonly="true">
                                             
                                             <label id="labelTotal" class="input">Total
                                                 <!-- Calc of...-->
                                                 <input class="input-small" name="totalPrice" id="totalPrice" type="text" placeholder="Total Price" readonly="true">
                                             </label>
                                               
                                             <label class="checkbox">
                                                   <!-- Orderitems.nb_DoNotInvoice-->
                                                   <input type="checkbox" name="doNotInvoice" id="doNotInvoice" value="1">Do Not Invoice
                                             </label>
                                        </div>
                                  </div>    
                             </div>
                         </div>
                         <!-- inline form setup-->
                         <div class="row-fluid">
                             <div class="span12">
                                 <div class="control-group test78">
                                     <span id="makeProductBuildClick" class="control-label">Product Build</span>
                                     <div class="controls form-inline">
                                          <!-- populated w/ a query for all product build categories-->
                                          <!-- Orderitems.t_ProductType-->
                                          <select id="productBuildCategory" name="productBuildCategory">

                                          </select>
                                           <!-- hidden and then populated after a selection is made on buildcategory-->
                                           <!-- Orderitems.kf_ProductBuildID-->
                                           <select id="productBuildName" name="productBuildName" class="hide">
                                                <option value="">...Product Build</option>
                                           </select>
                                           &nbsp;&nbsp;&nbsp;<button name="submitTypeOIUpSideFrm" id="submitTypeOIUpSideFrm" type="submit"  class="btn btn-primary">Update</button>
                                     </div>
                                 </div>
<!--                                 <div  class="control-group">
                                        <label class="control-label" for="userName">User Name </label>
                                        <div class="controls">
                                            <input type="text" class="typeahead" id="userName" name="userName" autocomplete="off" data-provide="typeahead">
                                        </div>
                                 </div>-->
                             </div>
                         </div>
                         <div  class="hidden-sport">
                             <hr>
                              <div class="row-fluid">
                                  <div class="span6">
                                      <div class="control-group test78">
                                           <label class="control-label" for="t_SportJobNumber">S Job#</label>
                                           <div class="controls">
                                               <input type="text" name="t_SportJobNumber" id="t_SportJobNumber" placeholder="Sport Job Number">
                                           </div>
                                      </div>
                                      <div class="control-group test78">
                                           <label class="control-label" for="t_SportItemNumber">S Item#</label>
                                           <div class="controls">
                                               <!-- Orderitems.t_SportItemNumber-->
                                               <input id="t_SportItemNumber" name="t_SportItemNumber"  type="text" placeholder="Sport Item Number">
                                           </div>
                                      </div>
                                      <div class="control-group test78">
                                           <label class="control-label" for="t_SportLocationNumber">S Location#</label>
                                           <div class="controls">
                                               <!-- Orderitems.t_SportLocationNumber-->
                                               <input id="t_SportLocationNumber" type="text" name="t_SportLocationNumber" placeholder="Sport Location Number">
                                           </div>
                                      </div>
                                  </div>
                                  <div class="span6">
                                      <!-- This is for prepress-->
                                      <div class="control-group test78">
                                           <label class="control-label" for="nb_ArtReceivedProduction">Art Received in</label>
                                           <div class="controls">
                                               <label class="checkbox">
                                                    <!-- Orderitems.nb_ArtReceivedProduction-->
                                                    <input type="checkbox" id="nb_ArtReceivedProduction" name="nb_ArtReceivedProduction" value="1">
                                               </label>
                                               <!-- If you don't label with no value your checkbox gets funky when right aligning-->
                                           </div>
                                      </div>
                                      <div class="control-group test78">
                                               <label class="control-label" for="d_ArtReceived">Date Received</label>
                                               <div class="controls">
                                                    <!-- Orderitems.d_ArtReceived-->
                                                    <input data-format="MM/dd/yyyy" id="d_ArtReceived" name="d_ArtReceived" type="text" placeholder="Date Received">
                                               </div>
                                      </div>
                                      <div class="control-group test78">
                                               <label class="control-label" for="t_ArtReceivedBy">Received By</label>
                                               <div class="controls">
                                                     <!-- Orderitems.t_ArtReceivedBy-->
                                                     <input id="t_ArtReceivedBy" name="t_ArtReceivedBy" type="text" placeholder="Name">
                                               </div>
                                      </div>
                                      <div class="control-group test78">
                                               <label class="control-label" for="t_ArtContact">Sport Art Contact</label>
                                               <div class="controls">
                                                     <!-- Orderitems.t_ArtContact-->
                                                     <input id="t_ArtContact" name="t_ArtContact" type="text" placeholder="Art Contact">
                                               </div>
                                      </div>
                                      <div class="control-group test78">
                                               <label class="control-label" for="ti_UploadComplete">Upload Completed</label>
                                               <div class="controls">
                                                     <!-- Orderitems.ti_UploadComplete-->
                                                     <input id="ti_UploadComplete" name="ti_UploadComplete" type="text" placeholder="Completed">
                                               </div>
                                      </div>
                                  </div>
                              </div><hr>
                         </div>
                         
                         
                         
<!--                         <div class="control-group">
                             <span class="control-label">Product Build</span>
                             <div class="controls form-inline">
                                   populated w/ a query for all product build categories
                                   Orderitems.t_ProductType
                                  <select id="productBuildCategory" name="productBuildCategory">
                                  </select>
                                    hidden and then populated after a selection is made on buildcategory
                                    Orderitems.kf_ProductBuildID
                                  <select id="productBuildName" name="productBuildName" class="hide">
                                      <option value="">...Product Build</option>
                                  </select>
                                   &nbsp;&nbsp;&nbsp;<button id="submitType" type="submit" class="btn btn-primary">Update</button>
                             </div>
                         </div>-->
                         
                         <input type="hidden" name="requestCalledHidden"            id="requestCalledHidden"/>
                         
                         <input type="hidden" name="totalDasNum"                    id="totalDasNum" value="<?php if($requestCalled == "read")echo $getDashNum;?>"/>
                         
                         <input type="hidden" name="currentDasNum"                  id="currentDasNum" value="<?php if($requestCalled == "read")echo $dashNum;?>"/>
                         
                         <input type="hidden" name="orderItemIDHidden"              id="orderItemIDHidden"/>
                         <input type="hidden" name="orderIDHidden"                  id="orderIDHidden"/>
                         <input type="hidden" name="customerIDHidden"               id="customerIDHidden"/>
                         <input type="hidden" name="nb_UseTotalOrderPricingHidden"  id="nb_UseTotalOrderPricingHidden" />
                         <input type="hidden" name="heightHidden"                   id="heightHidden" />
                         <input type="hidden" name="widthHidden"                    id="widthHidden" />
                         
                         <input type="hidden" name="proofNotesHidden"               id="proofNotesHidden" />
                         <input type="hidden" name="proofCreatedByHidden"           id="proofCreatedByHidden" />
                         
                         <input type="hidden" name="currentProductBuildID"          id="currentProductBuildID" value="none" />
                         
                    </form>
                </div>
                <?php if($requestCalled == "read")
                {?>
                    <div id="riot" class="span3">
                        <div id="preview">
                            <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" />
                        </div>
                        <div id="uploadFrm">
                            <form id="upload" method="post" action="orderItems/orderitemcontroller/uploadPrepressImage" enctype="multipart/form-data">
                                <a href="" id="selectBtnFrm"  class="btn">Add&nbsp;<i class="icon-plus"></i></a> <button name="uploadSubmitFrm" id="uploadSubmitFrm" type="submit"  class="btn hide">Upload</button>
                                <hr class="hide" id="horizontalTagCustom">
                               
                                
<!--                                <button name="uploadSubmitFrm" id="uploadSubmitFrm" type="submit"  class="btn hide">Upload</button-->
                                 <input type="file" id="upl" name="upl" accept="image/jpg, image/jpeg" class="hide" />
                                 <input type="hidden" name="uploadOrderItemIDHidden"              id="uploadOrderItemIDHidden"/>
                                 <input type="hidden" name="uploadOrderIDHidden"                  id="uploadOrderIDHidden"/>
                            </form>
                        </div>
                        <div  class="hidden-sport differentSportUploadRow"> 
                            <div class="row-fluid">
                                <div id="sportUploadPreview">
                                        <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" />
                                </div>
                                <div id="sport_uploadDv">
                                    <form id="upload" method="post" action="orderItems/orderitemcontroller/uploadPrepressImage" enctype="multipart/form-data">
                                        
                                        <a href="" id="selectSportBtnFrm"  class="btn">Add Decksheet&nbsp;<i class="icon-plus"></i></a><button name="uploadSportSubmitFrm" id="uploadSportSubmitFrm" type="submit"  class="btn hide">Upload</button><hr id="horizontalTagCustomSport">
                                    
                                        <div id="uploadInput" class="hide">
                                             <input type="file" id="sportUploadFile" name="sportUploadFile" accept="image/jpg, image/jpeg" />
                                             <input type="hidden" name="sportUploadOrderItemIDHidden"              id="sportUploadOrderItemIDHidden"/>
                                             <input type="hidden" name="sportUploadOrderIDHidden"                  id="sportUploadOrderIDHidden"/>
                                             <input type="hidden" name="sportUploadImage"                          id="sportUploadImage"  value="1" />
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
               <?php
               }?>
            </div>
            <div  class="row-fluid">
                    <div id="sucess"  class=" span6 hide hero-unit">
                        <div  id="ajaxLoadAni">
                            <img src="images/ajaxLoad/ajax-loader.gif" alt="Ajax Loading Animation" />
                                <span id="typeOfAction"></span>
                        </div>
                        <div id="message">
                           
                        </div> 
                    </div>
            </div>
            <div class="row-fluid">
                <div id="proofModalForm" class="modal hide fade">
                    <div class="modal-header">
                         <h3 id="modalCustomHeadingProof">Proof Info:</h3>
                    </div>
                    <div class="modal-body">
                        <section class="row-fluid">
                                <div class="row-fluid">
                                     <form id="proofInfoFrm" name="proofInfoFrm" class="form-horizontal test78" method="post" action="orderItems/orderitemcontroller/submitProofModalData" enctype="multipart/form-data">
                                        <div class="span12">
                                            <div class="span8">
                                                <div class="control-group test78">
                                                    <label class="control-label">Proof Created By</label>
                                                    <div class="controls">
                                                       <input type="text" class="typeahead" id="proofCreatedBy" name="proofCreatedBy" autocomplete="off" data-provide="typeahead">
                                                        
                                                    </div>
                                                </div>
                                                <div class="control-group test78">
                                                    <label class="control-label" >Proof Note</label>
                                                    <div class="controls">
                                                        <!-- OrderItems.t_ProofNote -->
                                                        <textarea id="proofNotes" name="proofNotes" rows="5"></textarea> 
                                                    </div>
                                                </div>
                                                <div class="control-group test78">
                                                    <div class="controls">
                                                        <button id="customerProofBtn"  type="submit" class="btn hide">Customer Proof</button>  
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span4">
                                                 <div id="proofImageUploadPreview">
                                                        <img style ="max-width: 280px; max-height: 150px; line-height: 150px;" src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" />
                                                 </div>
                                               
                                                 <div id="proofUploadFrm">
                                                      <br/>
                                                      <a href="" id="addProofBtnImage"  class="btn">Add&nbsp;<i class="icon-plus"></i></a>
                                                      <label class="error hide" id="imageNotFoundError">Please upload an image</label>
                                                      <div id="uploadInput" class="hide">
                                                         <input type="file" id="proofUploadImage" name="proofUploadImage" accept="image/jpg, image/jpeg" />
                                                         <!-- MAX_FILE_SIZE must precede the file input field -->
                                                         <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                                                         
                                                         <input type="hidden" name="proofOrderItemIDHidden"  id="proofOrderItemIDHidden"/>
                                                         <input type="hidden" name="proofOrderIDHidden"      id="proofOrderIDHidden"/>
                                                         <input type="hidden" name="proofImageNotFound"      id="proofImageNotFound" />
                                                         <button name="uploadProofFrm" id="uploadProofFrm" type="submit"  class="btn"></button>
                                                      </div>
                                                 </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                        </section>
                    </div>
                    <div class="modal-footer">
                        <section class="row-fluid">
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="span1">
                                        <button type="submit" id="deleteModalProofInfoFrm" class="btn btnlarge hide" >Delete</button>
                                    </div>
                                     <div class="span3 offset8">
                                          <button class="btn"   id="cancelModalProofInfoFrm" data-dismiss="modal" aria-hidden="true">Close</button>
                                          <button type="submit" id="validateModalProofInfoFrm" class="btn btn-primary btnlarge" >Save</button>
                                     </div>    
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
         
    </body>
</html>
<?php
          if($requestCalled == "read")
          {
              //echo $productBuildID."<br/>".$orderID."<br/>";
              echo  Modules::run('orderItemComponents/orderitemcomponentcontroller/setup',$orderID,$changeID,$productBuildID);
              
          }    
?>