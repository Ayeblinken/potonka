<!DOCTYPE html>
<html ang="en">
     <head>
        <meta charset=UTF-8">
        <title>Shipping</title>
        <base href="<?php echo base_url(); ?>" />
        <script type="text/javascript">
            
            var orderID = "<?php echo $orderID;?>";
           //alert(orderID);
       </script>
        <link href="media/css_bootstrap/bootstrap.css" rel="stylesheet" type="text/css">
        
        <link href="media/css_bootstrap/bootstrap-responsive.css" rel="stylesheet" type="text/css">
        
         <link href="extras/TableTools/media/css/TableTools.css" rel="stylesheet" type="text/css">
        
        <link href="media/DT_bootstrap/DT_bootstrap.css" rel="stylesheet" type="text/css"> 
         
        
        
        <script type="text/javascript" charset="utf-8" src="media/js/jquery.js"></script>
        <script src="js/jquery.maskedinput.js"></script>
        <script src="js/jquery.validate.min.js"></script>
        <script src="js/additional-methods.min.js"></script>
        
        
        
        <script type="text/javascript" charset="utf-8" src="media/js/jquery.dataTables.js"></script>
        
        <script type="text/javascript" charset="utf-8" src="extras/TableTools/media/js/ZeroClipboard.js"></script>
        <script type="text/javascript" charset="utf-8" src="extras/TableTools/media/js/TableTools.js"></script>
        <script type="text/javascript" charset="utf-8" src="media/js_bootstrap/bootstrap.min.js"></script>
        
        <script type="text/javascript" charset="utf-8" src="media/DT_bootstrap/DT_bootstrap.js"></script>
        
        <script type="text/javascript" src="js/jquery-templ.js"></script>
        
        <script src="js/clearForm.js"></script>
        <script src="js/orderShipTrackingModule.js"></script>
       
<!--       <script src="http://ajax.microsoft.com/ajax/jquery.templates/beta1/jquery.tmpl.js"></script>-->
      
<!--       <style type="text/css">
           #record_ordShip tr:hover {cursor: pointer;}
       </style>-->

        <style type="text/css">
            #OrderShipTrackingModalEditBtn {
                    width: 610px; /* SET THE WIDTH OF THE MODAL */
                    margin: -199px 0 0 -230px; /* CHANGE MARGINS TO ACCOMODATE THE NEW WIDTH (original = margin: -250px 0 0 -280px;) */
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
/*           label.naming {
                        display: inline-block;
			font-weight: bold;
			color: blue;
			font-size: 87.5%;
                        padding: 2px 8px;
			margin-top: 2px;
			
		}
             */
      
        </style>
       
         
    </head>
    <body>
        <span id="table-wrapper">
            <div class="container-fluid" style="margin-top: 10px">
                <section class="row-fluid">
                    <a id="addShippingCharge" href="#">Add Shipping Charge</a>
<!--                    <nav>
                            <ul id="dynamicLinks" class="nav nav-pills">
                               <li> <a id="shippingCharge" href="odrTracking/ordershiptrackingcontroller/orderShipSelect/">Add Shipping Charge</a></li>
                                    <li> <a id="addShippingCharge" href="#">Add Shipping Charge</a></li>
                            </ul>
                    </nav>-->
                </section>
                <section class="row-fluid">
                     <div class="span12">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="records" >
                            <thead>
                                <tr>
                                    <th>ID #</th>
                                    <th>Shipping Company</th>
                                    <th>Shipping Service</th>
                                    <th>Tracking #</th>
                                    <th>Charge</th>
                                    <th>Delete</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                
                        <script type="text/template" id="readTemplate">
                            <tr id="${ID}">
                                <td><a class="updateBtn" href="${updateLink}">${ID}</a></td>
                                <td>${shippingCompany}</td>
                                <td>${shippingService}</td>
                                <td>${trackingID}</td>
                                <td>${shippingCharge}</td>
                                <td><a class="deleteBtn" href="${deleteLink}">Delete</a></td>
                            </tr>
                        </script>
                     </div>
                     <div id="OrderShipTrackingModalEditBtn" class="modal hide fade">
                          <section class="row-fluid">
                          
                              <div class="modal-header">
                                <h3 id="modalCustomHeadingTemplateRecords">Update Action</h3>
                            </div>
                            <div class="modal-body">
                                
                                    <div class="span9" id="updateActionTemplateRecords">
                                       <form class="form-horizontal" id="updateFrmModalOrderShipTrackingTable" name="updateFrmModalOrderShipTrackingTable" method="POST">
                                           <div class="control-group">
                                                <label class="control-label" for="modalPackageCost">Package Cost: </label>
                                                <div class="controls">
                                               
                                                    <input type="text" class="input-medium" placeholder="Package Cost"  name="modalPackageCost" id="modalPackageCost">
                                                    
                                                </div>
                                           </div>
                                           <div class="control-group">
                                                <label class="control-label" for="modalCharge">Customer Charge: </label>
                                                <div class="controls">
                                                    <input type="text"  class="input-medium" placeholder="Charge"  name="modalCharge" id="modalCharge">
<!--                                                    <label class="error">*</label>-->
                                                    <label></label>
                                                </div>
                                           </div>
                                           <div class="control-group">
                                              <label class="control-label" for="modalTrackingNumber">Tracking#: </label>
                                              <div class="controls">
                                                    <input type="text" class="input-medium"  placeholder="Tracking Number" name="modalTrackingNumber" id="modalTrackingNumber">
                                              </div>
                                           </div>
                                           <div class="control-group">
                                               <div class="controls">
                                                   <label class="radio inline">
                                                       <input id="modalNoCharge"  name="modalNoChargeFlatStandardRate" value="No Charge" type="radio"> N/Charge
                                                   </label>&nbsp;   
                                                   <label class="radio inline">
                                                       <input id="modalFlatRate"  name="modalNoChargeFlatStandardRate" value="Flat Rate"  type="radio"> F/Rate
                                                   </label>&nbsp;
                                                   <label class="radio inline">
                                                       <input id="modalStandardRate"  name="modalNoChargeFlatStandardRate" value="Standard Rate"  type="radio"> S/Rate
                                                   </label>&nbsp;
                                               </div>
                                               
                                               
                                           </div>
                                           <input type="hidden"  id="modal_n_ShippingChargeHidden" name="modal_n_ShippingChargeHidden" >
                                           <input type="hidden"  id="modal_n_PackageChargeHidden" name="modal_n_PackageChargeHidden" > 
                                           <input type="hidden"  id="modalOrderShipTrackingID" name="modalOrderShipTrackingID" >
                                           <input type="hidden"  id="chargeTypeHidden" name="chargeTypeHidden">
                                           <input type="hidden"  id="modalOrderShipID" name="modalOrderShipID" >
                                      </form>  
                                    </div>
                              
                            </div>
                            <div class="modal-footer">
                                  
                                  <button class="btn" type="submit" id="closeOrderShipTrackingModal"   aria-hidden="true">Close</button>
                                  &nbsp;
                                  <button type="submit" id="validateOrderShipTrackingModal" class="btn  btn-primary">Done</button>
<!--                                  <a id="validateModalOrderShipTracking" class="btn btn-primary" >Save</a>-->
                         
                            </div>
                            
                            </section>               
                         
                     </div>
                      <div id="OrderShipTrackingModalDeleteBtn" class="modal hide fade">
                          <section class="row-fluid">
                                <div class="modal-header">
                                    <h3 id="modalCustomHeadingTemplateRecords">Delete Action</h3>
                                </div>
                                <div class="modal-body">
                                    <form id="deleteFrmModalOrderShipTrackingTable" name="deleteFrmModalOrderShipTrackingTable" method="POST">
                                        <p id="message"></p>
                                        <input type="hidden"  id="deleteModalOrderShipTrackingID" name="deleteModalOrderShipTrackingID" >
                                    </form>
                                    
                                </div>
                                <div class="modal-footer">
                                      <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                                      &nbsp;
                                      <button type="submit" id="deleteOrderShipTrackingModal" class="btn  btn-primary">OK</button>
    <!--                                  <a id="validateModalOrderShipTracking" class="btn btn-primary" >Save</a>-->
                                </div>
                             </form>
                        </section>                 
                         
                     </div>
                    
                </section> 
                  
            </div>
        </span>
        <span id="table-wrapper-select">
            <div class="container" style="margin-top: 10px">
                <section class="row-fluid">
                     <div class="span12">
                         <p>Select the address to add the tracking # to:</p>
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="ordShip_tbl" >
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Details</th>
                                    <th>Receipeint</th>
                                    <th>Blind - Third Party</th>
                                    <th>Tracking</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    
                                   
                                </tr>
                            </tbody>
                        </table>
                        <script type="text/template" id="readAddShipTrack">
                            <tr style="cursor: pointer;"  id="${ID}">
                                <td>${ID}</td>
                                <td>${Details}</td>
                                <td>${Receipeint}</td>
                                <td>${Blind}</td>
                                <td>${Tracking}</td>
                            </tr>
                        </script>
                     </div>
                </section>
                
            </div>
        </span>
        <span id="table-wrapper-form" >
              <div class="container">
<!--                  <form  class="form-horizontal" id="shippingChargeTrackingFrm" name="shippingChareTrackingFrm"    method="POST" action="odrTracking/ordershiptrackingcontroller/orderShipTrackingSubmit" >-->
                  <form  class="form-horizontal" id="shippingChargeTrackingFrm" name="shippingChargeTrackingFrm">
                      <section id="chargeTracking"  class="row-fluid">
                          <div class="span9">
                              <fieldset>
                                  <legend> Please enter an amount to Shipping Cost and a Tracking number (if applicable)</legend>
                                  <div class="control-group">
                                        <label class="control-label" for="packageCost">Package Cost: </label>
                                        <div class="controls">
                                            <input type="text" class="input-medium" placeholder="Package Cost"  name="packageCost" id="packageCost">
                                            <label id="packageCostLabel"></label>
<!--                                            <label id="packageCostLabel">*</label>-->
                                             
                                        </div>
                                  </div>
                                   <div class="control-group">
                                                <label class="control-label" for="charge">Customer Charge: </label>
                                                <div class="controls">
                                                    <input type="text"  class="input-medium" readonly ="true" value="$0.00" placeholder="Customer Charge"  name="customerCharge" id="customerCharge">
                                                    <label id="customerChargeLabel"></label>
<!--                                                    <label id="customerChargeLabel" >*</label>-->
                                               
                                                </div>
                                  </div>
                                  <div class="control-group">
                                        <label class="control-label" for="trackingNumber">Tracking#: </label>
                                        <div class="controls">
                                             <input  type="text" class="input-medium"  placeholder="Tracking Number" name="trackingNumber" id="trackingNumber">
                                             
                                        </div>
                                       
                                  </div>
                                  <div class="control-group">
                                               <div class="controls">
                                                   <label class="radio inline">
                                                       <input id="noCharge"  name="noChargeFlatStandardRate" value="No Charge" type="radio" > N/Charge
                                                   </label>&nbsp; &nbsp;  
                                                   <label class="radio inline">
                                                       <input id="flatRate"  name="noChargeFlatStandardRate" value="Flat Rate"  type="radio"> F/Rate
                                                   </label>&nbsp;&nbsp;
                                                   <label class="radio inline">
                                                       <input id="standardRate"  name="noChargeFlatStandardRate" value="Standard Rate"  type="radio" checked="checked"> S/Rate
                                                   </label>&nbsp;&nbsp;
                                                   <br><br><button type="button" id="CancelBtn" class="btn">Cancel</button>&nbsp;
                                                    <button type="submit" id="submit" class="btn  btn-primary">Done</button>
                                               </div>
                                  </div>
                                   
                             </fieldset>
                                <input type="hidden" id="orderShipIDHidden" name="orderShipIDHidden">
<!--                                <input type="hidden" id="orderShipTrackingIDHidden" name="orderShipTrackingIDHidden">-->
                                <input type="hidden" id="orderIDHidden" name="orderIDHidden" value="<?php echo $orderID;  ?>"> 
                          </div>
                      </section>  
                  </form>
              </div>
        </span>
        
          
        
        
        
       
            


    </body>
   
</html>

