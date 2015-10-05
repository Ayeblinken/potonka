<!DOCTYPE html>
<html ang="en">
    <head>
        <meta charset=UTF-8">
        
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="no-cache">
        <meta http-equiv="Expires" content="-1">
        <meta http-equiv="Cache-Control" content="no-cache">
        
        
        <title><?php if($requestCalled == "read") echo $orderID."-".$dashNum;
                     if($requestCalled == "create") echo $orderID;
                     if($requestCalled == "template") echo "template-".$companyName;
        ?></title>
        <base href="<?php echo base_url(); ?>" />
        
        <script>
             var requestCalled       = "<?php echo $requestCalled;?>";
             //alert("hyi1: "+requestCalled);
             var changeID            = "<?php echo $changeID?>";
             var orderIDView         = "<?php echo $orderID?>";
             var templateName        = '<?php if($requestCalled == "template") echo addslashes($templateName);?>';
             //alert("hyi2: "+templateName);
            
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

        <link rel="shortcut icon" href="images/ii_logo_fav.png">
         
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
            #orderItemStatusChangeModalForm {
                    width: 640px; /* SET THE WIDTH OF THE MODAL */
                    
                    margin: -229px 0 0 -490px; /* margin: -199px 0 0 -230px; CHANGE MARGINS TO ACCOMODATE THE NEW WIDTH (original = margin: -250px 0 0 -280px;) */
            }
            #orderItemStatusChangeForm .test78 {
                margin-bottom: 5px;
                /*if help block is in use 8 Tightens up the space inbetween fields */
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
            #previewInfo.hover
            {
/*                -khtml-user-drag: element;
                border: 2px solid #000;*/
                border: 2px dashed #000;
                border-radius: 5%;
                
            }
            #preview.hover
            {
/*                -khtml-user-drag: element;*/
/*                border: 2px solid #000;*/
                border: 2px dashed #000;
                border-radius: 5%;
                
            }
            #sportUploadPreviewInfo.hover
            {
/*                border: 2px solid #000;*/
                border: 2px dashed #000;
                border-radius: 5%;
                
            }
            #sportUploadPreview.hover
            {
/*                border: 2px solid #000;*/
                border: 2px dashed #000;
                border-radius: 5%;
                
            }
            #proofImageUploadPreview.hover
            {
/*                border: 2px solid #000;*/
                border: 2px dashed #000;
                border-radius: 5%;
                
            }
            #proofImageUploadPreviewInfo.hover
            {
/*                border: 2px solid #000;*/
                border: 2px dashed #000;
                border-radius: 5%;
                
            }
/*            .navbar-inverse .navbar-inner 
            {
                background-color: DarkGreen;
                 remove the gradient 
                background-image: none;
                 set font color to white 
                color: white;
            } */

        </style>
        
    </head>
    
    <body>
         
            <div id="templateChange" class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a id="templateChangeName" class="brand" style="cursor: pointer;"><?php if($requestCalled != "template") echo "Job # "; ?><?php echo $orderID;?> 
                        <?php if($requestCalled == "read")
                         {
                            echo "-".$dashNum;
                            ?>
                          <?php  
                         }
                         echo "&nbsp;".$companyName; if($requestCalled == "template") echo " - ".$templateName;
                        ?>
                    </a>
                    <?php if($requestCalled == "read" || $requestCalled == "template")
                    {?>
                    <button id="dupOrderItem"  type="submit" onclick="location.href=<?php echo "'orderItems/orderitemcontroller/dup/".$changeID."/".$requestCalled.'\'' ?>" class="btn"><i class="icon-plus"></i>&nbsp;Duplicate</button>  
                        <?php if($requestCalled == "read")
                        {?>
                            &nbsp;<a id="orderItemProofAnchor" href="" class="btn"><i id="orderItemProofIcon" class="icon-ok"></i> Proof</a>
                            &nbsp;<div class="btn-group">
                                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-print"></i>
                                    Print
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a id="orderItemPrintTicketAnchor"    tabindex="-1" href="#">Ticket</a></li>
                                     <li><a id="orderItemPrintTicketAllAnchor" tabindex="-1" href="#">Ticket All</a></li>
                                    <li><a id="orderItemPrintImageAnchor"     tabindex="-1" href="#">Image</a></li>
                                    <li><a id="orderItemPrintLabelAnchor"     tabindex="-1" href="#">Label</a></li>
                                    <li><a id="orderItemReprintTicketLabelAnchor"     tabindex="-1" href="#">Reprint Ticket</a></li>
                                   
                                </ul>
                              </div>
                              <div id="templateBtnGroup" class="btn-group">
                                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-plus"></i>
                                    Template
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a id="orderItemTemplateCustomerAnchor" tabindex="-1" href="#">This Customer</a></li>
                                    <li><a id="orderItemTemplateRefGuideAnchor" tabindex="-1" href="#">Reference Guide</a></li>
                                </ul>
                              </div>
<!--                            &nbsp;<a id="orderItemTemplateAnchor" href="" class="btn"><i id="orderItemTemplateIcon" class="icon-plus"></i>Template</a>-->
<!--                        &nbsp;<a id="orderItemPrintTicketAnchor" href="" class="btn">Print Ticket</a>-->
                        <div class="pull-right">
                            <a id="orderItemStatusChangeLink" href="#" class="hide">Status: Picked Up</a>
                        <?php
                        }?>
                        
                            <?php
                            if(!is_null($getDashNum))
                            {?>
                                 <a id="minusOrderItemDashNum" class="btn btn-small displayPagi"><i class="icon-chevron-left"></i></a>
                                 <?php //echo $dashNum." of ".$getDashNum ?>
                            
                                 <a id="displayPaginationNum" class="btn btn-small">
                                    <?php echo $dashNum." of ".$getDashNum ?>
                                 </a>
                                 <a id="addOrderItemDashNum" class="btn btn-small displayPagi"><i class="icon-chevron-right"></i></a>
                                <?php
                            }
                            ?>
                           
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
                                        </div>&nbsp;<a id="reverseHWData"><img src="images/Switch.png" alt="Reverse data in H and W"></a>
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
                                    <?php if($requestCalled == "template") 
                                    {?>
                                        <span class="control-label">Description</span>
                                    <?php
                                    }
                                    else
                                    {?>
                                         <span class="control-label"><a href="" id="descriptionOrderLink">Description</a></span>
                                    <?php
                                    }?>
                                   
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
                         <div id="userAccessRow" class="row-fluid">
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
                            <img id="previewInfo" src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" />
                        </div>
                        <p id="prepressUploadBar" class="hide">
                                    <img src="images/ajaxLoad/ajax-loader.gif" alt="Ajax Loading Animation" />&nbsp;&nbsp;&nbsp;&nbsp;<span><strong>Uploading...</strong></span>
                        </p>
                        <!--<progress id="prepressUploadBar" class="hide" min="0" max="115" value="0">0% complete</progress>-->
                        <div id="uploadFrm">
                            <form id="upload" method="post" action="orderItems/orderitemcontroller/uploadPrepressImage" enctype="multipart/form-data">
                                <a href="" id="selectBtnFrm"  class="btn">Add&nbsp;<i class="icon-plus"></i></a> 
<!--                                <button name="uploadSubmitFrm" id="uploadSubmitFrm" type="submit"  class="btn hide">Upload</button>-->
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
                                        <img id="sportUploadPreviewInfo" src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" />
                                </div>
                                <!--<progress id=p></progress>-->
                                 <p id="deckSheetUploadBar" class="hide">
                                    <img src="images/ajaxLoad/ajax-loader.gif" alt="Ajax Loading Animation" />&nbsp;&nbsp;&nbsp;&nbsp;<span><strong>Uploading...</strong></span>
                                </p>
                                <!--<progress id="deckSheetUploadBar" class="hide" min="0" max="115" value="0">0% complete</progress>-->
                                <div id="sport_uploadDv">
                                    <form id="upload" method="post" action="orderItems/orderitemcontroller/uploadPrepressImage" enctype="multipart/form-data">
                                        
                                        <a href="" id="selectSportBtnFrm"  class="btn">Add Decksheet&nbsp;<i class="icon-plus"></i></a>
                                        <!--<button name="uploadSportSubmitFrm" id="uploadSportSubmitFrm" type="submit"  class="btn hide">Upload</button><hr id="horizontalTagCustomSport">-->
                                    
                                        <div id="uploadInput" class="hide">
                                             <input type="file" id="sportUploadFile" name="sportUploadFile" accept="image/jpg, image/jpeg,application/pdf" />
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
                                <div id="proofInfoFrmDiv" class="row-fluid">
<!--                                     <form id="proofInfoFrm" name="proofInfoFrm" class="form-horizontal test78" method="post" action="orderItems/orderitemcontroller/submitProofModalData" enctype="multipart/form-data">-->
<!--                                     <form id="proofInfoFrm" name="proofInfoFrm" class="form-horizontal test78" method="post">-->
                                        <div class="span12">
                                            <form id="proofInfoFrm" name="proofInfoFrm" class="form-horizontal test78" method="post">
                                                
                                           
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
                                                <input type="hidden" name="proofImageNotFound"      id="proofImageNotFound" />  
                                                <input type="hidden" name="proofOrderItemIDHidden"  id="proofOrderItemIDHidden"/>
                                            </div>
                                            </form>
<!--                                            <form id="proofModalUploadInfoFrm" name="proofModalUploadInfoFrm" class="form-horizontal test78">-->
                                            <div class="span4">
                                                 <div id="proofImageUploadPreview">
                                                        <img id="proofImageUploadPreviewInfo" style ="max-width: 280px; max-height: 150px; line-height: 150px;" src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" />
                                                 </div>
                                               
                                                 <div id="proofUploadFrm">
                                                      <br/>
                                                      <a href="" id="addProofBtnImage"  class="btn">Add&nbsp;<i class="icon-plus"></i></a><span id="imageNotFoundError"></span>
                                                      <p id="proofUploadBar" class="hide">
                                                            <img src="images/ajaxLoad/ajax-loader.gif" alt="Ajax Loading Animation" />&nbsp;&nbsp;&nbsp;&nbsp;<span><strong>Uploading...</strong></span>
                                                      </p>
                                                    
<!--                                                      <label class="error hide" id="imageNotFoundError">Please upload an image</label>-->
                                                      <div id="uploadInput" class="hide">
                                                         <input type="file" id="proofUploadImage" name="proofUploadImage" accept="image/jpg, image/jpeg" />
                                                         <!-- MAX_FILE_SIZE must precede the file input field -->
                                                         <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                                                         
                                                         <input type="hidden" name="proofOrderItemIDHidden"  id="proofModalUploadOrderItemIDHidden" value="<?php echo $changeID?>"/>
                                                         <input type="hidden" name="proofOrderIDHidden"      id="proofOrderIDHidden"/>
                                                         
                                                         <button name="uploadProofFrm" id="uploadProofFrm" type="submit"  class="btn"></button>
                                                      </div>
                                                 </div>
                                            </div>
<!--                                            </form>    -->
                                        </div>
<!--                                    </form>-->
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
<!--                                          <button class="btn"   id="cancelModalProofInfoFrm" data-dismiss="modal" aria-hidden="true">Close</button>-->
                                          <a class="btn" name="cancelModalProofInfoFrm" id="cancelModalProofInfoFrm" href="#">Close</a>
                                          <a id="validateModalProofInfoFrm" name="validateModalProofInfoFrm" href="#" class="btn btn-primary btnlarge">Save</a>
<!--                                          <button type="submit" id="validateModalProofInfoFrm" class="btn btn-primary btnlarge" >Save</button>-->
                                     </div>    
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div id="orderItemStatusChangeModalForm" class="modal hide fade">
                    <div class="modal-header">
                        <h3><?php echo $orderID."-".$dashNum; ?></h3>
<!--                         <div id="statuschangeIDHeading" class="well well-small"><?php //echo $orderID."-".$dashNum; ?></div>-->
                    </div>
                    <div class="modal-body">
                        <div class="row-fluid">
                            <form id="orderItemStatusChangeForm" name="orderItemStatusChangeForm" class="form-horizontal test78">
                                <div id="orderItemStatusChangeDiv" class="span12">
                                    <div class="control-group test78">
                                        <label class="control-label" for="currentStatus">Current Status: </label>
                                        <div class="controls">
                                            <input type="text" class="input-xlarge"   name="currentStatus" id="currentStatus" readonly>
                                        </div>
                                    </div>
                                    <div class="control-group test78">
                                        <label class="control-label" for="newStatus">New Status </label>
                                        <div class="controls">
                                            <select name="newStatus" id="newStatus" name="newStatus" style="width:12em;">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group test78">
                                          <label class="control-label" for="userName">User Name </label>
                                          <div class="controls">
                                              <input type="text" class="typeahead" id="userName" name="userName" autocomplete="off" data-provide="typeahead">
                                          </div>
                                    </div>
                                    <div class="control-group test78">
                                           <label class="control-label" for="notes">Notes </label>
                                           <div class="controls">
                                               <textarea name="notes" id="notes" rows="9"></textarea>
                                           </div>
                                    </div>
                                    <div id="applyStatusToAll" class="control-group test78">
                                          <label class="control-label" for="applyToAll">Apply to All</label>
                                          <div class="controls">
                                              <input type="checkbox" id="applyToAll" name="applyToAll" value="1"> <span id="customWarningMsg" class="label label-info hide">Are You Sure ?</span>
                                          </div>
                                         
                                    </div>
                                    
                                </div>
                                <input type="hidden" name="orderItemIDHidden"         id="orderItemIDHidden"         value="<?php echo $changeID;?>">
                                <input type="hidden" name="orderIDHidden"             id="orderIDHidden"             value="<?php echo $orderID; ?>" >
                                <input type="hidden" name="statusChangeRequestHidden" id="statusChangeRequestHidden" value="orderItemChange"  >
                             </form>
                         </div>
                     </div>
                     <div class="modal-footer">
                        <div class="row-fluid">
                             <button class="btn"   id="cancelModalOrderItemStatusChangeFrm" data-dismiss="modal" aria-hidden="true">Cancel</button>
                             <button type="submit" id="validateModalOrderItemStatusChangeFrm" class="btn btn-primary btnlarge" >Save</button>
                        </div>
                     </div>
                </div>
                <div id="templateChangeModalForm" class="modal hide fade">
                    <div class="modal-header">
                        <h3>Change Template Name:</h3>
                    </div>
                    <div class="modal-body">
                        <div class="row-fluid">
                              <form id="templateChangeForm" name="templateChangeForm" class="form-horizontal test78">
                                <div  class="span12">
                                    <div class="control-group test78">
                                        <label class="control-label" for="templateNameInput">Name: </label>
                                        <div class="controls">
                                            <input type="text" class="input-xlarge"  name="templateNameInput" id="templateNameInput">
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="templateOrderItemIDHidden"         id="templateOrderItemIDHidden"         value="<?php echo $changeID;?>">
                             </form>
                         </div>
                     </div>
                     <div class="modal-footer">
                        <div class="row-fluid">
                               <button class="btn"   id="cancelModalTemplateChangeFrm" data-dismiss="modal" aria-hidden="true">Cancel</button>
                               <button type="submit" id="validateModalTemplateChangeFrm" class="btn btn-primary btnlarge" >Save</button>
                        </div>
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
          else if($requestCalled == "template")
          {
              $orderID = "template";
              //$orderID = "null";
              //echo "ChangeID: ".$changeID."<br/>ProductBuildID: ".$productBuildID."<br/>OrderID: ".$orderID."<br/>";
              echo  Modules::run('orderItemComponents/orderitemcomponentcontroller/setup',$orderID,$changeID,$productBuildID);
              
          }  
?>