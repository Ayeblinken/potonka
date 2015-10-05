<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        
       
        <script type="text/javascript">
            //localStorage.clear();
        </script>
        
        <title><?php if(!empty($orderID)) echo $orderID." - "; ?> Sales Order </title>
        <base href="<?php echo base_url(); ?>" />
      
        <!-- Bootstrap core CSS -->
        <!--<link href="bootstrap3/dist/css/bootstrap.css" rel="stylesheet">-->
        <link href="bootstrap3/dist/css/bootstrap.css" rel="stylesheet">
         
          <!-- Bootstrap theme -->
        <link href="bootstrap3/dist/css/bootstrap-theme.min.css" rel="stylesheet">
         
         <!-- Data Table Table tools CSS  -->
        <link rel="stylesheet" href="extras/TableTools/media/css/TableTools.css">
         
         <!-- Datables Bootstrap CSS -->
        <link rel="stylesheet" href="media/dtbs3/dataTables.bootstrap.css">
         
        <link rel="stylesheet" href="bootstrapNewDatePicker/css/datepicker.css">
         
<!--         <link href="magicsuggest/bin/magicsuggest-1.3.1-min.css" rel="stylesheet">-->
         
         <!-- magic sugges CSS -->
         <!-- typeahead Bootstrap CSS -->
<!--         <link href="typeahead/typeahead.js-bootstrap.css" rel="stylesheet">-->
         
<!--         <link href="media/orderRequest.css" rel="stylesheet">-->
         

     
         <!-- Bootstrap core JavaScript
         ================================================== -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<!--         <script type="text/javascript" src="magicsuggest/bin/jquery-1.8.3-min.js"></script>-->
<!--         <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>-->
         
         <!-- DataTables core JavaScript
         ================================================== -->
        <script src="media/js/orderOverview_jquery.dataTables.js"></script>
        
        <script src="extras/TableTools/media/js/TableTools.js"></script>
        <script src="extras/TableTools/media/js/ZeroClipboard.js"></script>
        
        <!--Bootstrap JavaScript
         ================================================== --> 
        <script src="bootstrap3/dist/js/bootstrap.min.js"></script>
        
        <script src="bootstrapNewDatePicker/js/bootstrap-datepicker.js"></script>
        
        <!-- DataTables Bootstrap JavaScript
        ================================================== --> 
        <script src="media/dtbs3/dataTables.bootstrap.js"></script>
       
        <script src="js/jquery.validate.min.js"></script>
        <script src="js/additional-methods.min.js"></script>
        
         
<!--         <script type="text/javascript" src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
         
         <script src="bootstrapNewDatePicker/js/bootstrap-datepicker.js"></script>-->
         
<!--         <script src="typeahead/typeahead.js"></script>-->
<!--         <script src="magicsuggest/bin/magicsuggest-1.3.1-min.js"></script>-->
         
        <script src="js/clearForm.js"></script>
          
        <script src="js/orderModuleInternalOrderFrm.js"></script>
        <style type="text/css">
            #resetSearch {
                text-indent: -1000em;
                width: 16px;
                height: 16px;
                display: inline-block;
                /*    background-image: url(http://p.yusukekamiyamane.com/icons/search/fugue/icons/cross.png);*/
                background-repeat: no-repeat;
                position: relative;
                left: -20px; 
                top: 2px;
            }
/*            body.modal-open {
                overflow: hidden;
            }*/
            body { padding-top: 10px; }
/*            .row {
                    margin-top: 30px;
                    margin-bottom: 10px;

                 }*/
           .error {
                color:red;
           }
/*           #previewInfo.hover {
               
           }*/
        </style>
    </head>
    <body>
        <div class="container">
            <div id="customerTableSelect" class="row">
                <div class="col-md-12 col-sm-12">
                   <h4><span class="label label-default">Choose a Customer</span></h4>
                </div>
                <div class="col-md-12 col-sm-12">
                     <div id="customerDiv" style="margin-top: 15px">
                            <table  class="table table-striped table-bordered" id="customerInfoDataTable">
                                <thead>
                                    <tr>
                                        <th>CustomerID</th>
                                        <th>Company Name</th>
                                        <th>City</th>
                                        <th>State</th>
                                        <th>EmployeeIDSales</th>
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
                     </div>
                </div>
            </div>
             <div id="customerContactTableSelect" class="row hide">
                <br/>
               <button  name="addContactBtn" id="addContactBtn" style="margin-bottom: 5px;" type="submit" class="btn btn-default col-md-2 col-sm-2">Add Contact</button>
               <button id="contactTblRefreshBtn" name="contactTblRefreshBtn" type="submit" class="btn btn-default col-md-1 col-md-offset-8 col-sm-1 col-sm-offset-8">Refresh</button>
<!--               <div class="col-md-12 col-sm-12"><a name ="addContactBtn" id="addContactBtn" class="btn btn-primary">Add Contact</a>-->
<!--                    <div class="col-md-8 col-sm-8">
                        <h4>Choose a Contact</h4>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <a name ="addContactBtn" id="addContactBtn" class="btn btn-primary">Add Contact</a>
                    </div>-->
                   
<!--                      <h4><span class="label label-default">Choose a Contact</span></h4>-->
<!--                </div>    --><br/>   <br/>
                <div class="col-md-12 col-sm-12">
                     <div id="customerContactDiv" style="margin-top: 15px"> <h4>Choose a Contact</h4>
                            <table  class="table table-striped table-bordered" id="customerContactInfoDataTable">
                                <thead>
                                    <tr>
                                        <th>AddressID</th>
                                        <th>Contact Name</th>
                                        <th>Contact Title</th>
                                        <th>Email</th>
                                        <th>Edit</th>
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
                     </div>
                </div>
            </div>
<!--            //action ="orders/ordercontroller/submitInternalOrderRequestFrm"-->
            <form  name="internalSalesOrderFrm" id="internalSalesOrderFrm" class="form-horizontal" role="form">
            <div id="orderRequestDiv" class="row hide">
                <div class="col-md-12 col-sm-12">
                  
                    <div class="col-md-6 col-sm-6">
                         
                         <div class="form-group">
<!--                              <div class="col-md-12 col-sm-12">
                                  <label for="internalOrderCompanyName" class="col-md-2 col-sm-3 control-label">Company</label>
                                  <div class="col-md-4 col-sm-4">
                                      <div id="ms" name="colors"></div>
                                  </div>
                              </div>-->
                              <div class="col-md-12 col-sm-12">
<!--                                  <span  id="changeCompany" class="label label-primary btn">Change Customer</span>-->
<!--                                  <button type="button" class="btn btn-primary">Company</button>-->
<!--                              <label id="customerNameLabel" for="internalOrderCompanyName" class="col-md-2 col-sm-3 control-label">Company</label>-->
                                  <a id="customerNameLabel" class="col-md-4 col-sm-4 control-label">Company</a>
                                  <div class="col-md-8 col-sm-8">
                                      <input type="text" id="companyName" name="companyName" class="form-control" />
                                  </div>
                              </div>
                         </div>
                         <div class="form-group">
                              <div class="col-md-12 col-sm-12">
<!--                                  <span id="changeContact" class="label label-primary btn">Change Contact</span>-->
<!--                                  <label id="contactNameLabel" for="contact" class="col-md-2 col-sm-3 control-label">Contact</label>-->
                                  <a id="contactNameLabel" class="col-md-4 col-sm-4 control-label">Contact</a>
                                  <div class="col-md-8 col-sm-8">
                                     <input type="text" class="form-control" name="contactName" id="contactName">
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                               <div class="col-md-12 col-sm-12">
                                   <label for="orderType" class="col-md-4 col-sm-4 control-label">Order Type</label>
                                   <div class="col-md-8 col-sm-8">
                                       <select id="orderType" name="orderType" class="form-control">
                                           <option value="Order">Order</option>
                                           <option value="Sample">Sample</option>
                                       </select>
                                   </div>     
                               </div>
                               
                          </div>
                          <div class="form-group">
                              <div class="col-md-12 col-sm-12">
                                  <label for="jobName" class="col-md-4 col-sm-4 control-label">Job Name</label>
                                  <div class="col-md-8 col-sm-8">
                                     <input type="text" class="form-control" name="jobName" id="jobName">
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="col-md-12 col-sm-12">
<!--                                  <label for="artLocation" class="col-md-2 col-sm-3 control-label">Art Location</label>-->
                                  <a id="artLocationLabel" class="col-md-4 col-sm-4 control-label">Art Location</a>
                                  <div class="col-md-8 col-sm-8">
                                     <input type="text" class="form-control" name="artLocation" id="artLocation">
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="col-md-12 col-sm-12">
                                  <a id="customerPurChaseOrderNumberLabel" class="col-md-4 col-sm-4 control-label">Customer P.O.#</a>
<!--                                  <label for="customerPurChaseOrderNumber" class="col-md-4 col-sm-4 control-label">Customer P.O.#</label>-->
                                  <div class="col-md-8 col-sm-8">
                                     <input type="text" class="form-control" name="customerPurChaseOrderNumber" id="customerPurChaseOrderNumber">
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="col-md-12 col-sm-12">
                                   <label class="col-md-4 col-sm-4 control-label">P.O. TBD</label>
                                   <div class="col-md-8 col-sm-8">
                                       <div class="checkbox">
                                           <label>
                                                <input id="customerPOToBeDetermined" name="customerPOToBeDetermined"  type="checkbox" value="1">
                                           </label>
                                       </div>
                                   </div>
                              </div>
                          </div>    
                          <div class="form-group">
                              <div class="col-md-12 col-sm-12">
                                  <label class="col-md-4 col-sm-4 control-label">Total Order Pricing</label>
                                  <div class="col-md-8 col-sm-8">
                                      <div class="checkbox">
                                          <label><!--Orders.nb_CreditHoldOveride REQUIRED-->
                                              <input id="totalOrderPricingCheckBox" name="totalOrderPricingCheckBox"  type="checkbox" value="1">
<!--                                              <label class="col-md-2 col-sm-3 control-label">Price</label>-->
                                                  <span id="orderPricingInputSpan" class="hide">
                                                      <input id="orderPricingInput" name="orderPricingInput" class="form-control col-md-2 col-sm-2">
                                                  </span>
                                          </label>
                                      </div> 
<!--                                      <div class="col-xs-2">
                                           
                                      </div>-->
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="col-md-12 col-sm-12">
                                  <label class="col-md-4 col-sm-4 control-label">Incomplete Pricing</label>
                                  <div class="col-md-8 col-sm-8">
                                      <div class="checkbox">
                                          <label>
                                              <!--Orders.nb_CreditHoldOveride REQUIRED-->
                                              <input id="incompletePricingCheckBox" name="incompletePricingCheckBox"  type="checkbox" value="1">  
                                          </label>
                                      </div>
                                  </div>
                              </div>
                          </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                          <div class="form-group">
                              <div class="col-md-12 col-sm-12">
                                  <label for="proofDueDate" class="col-md-4 col-sm-4 control-label">Proof Due</label>
                                  <div class="col-md-4 col-sm-4">
                                     <input type="text" class="form-control" name="proofDueDate" id="proofDueDate"  data-date-format="mm/dd/yyyy">
                                  </div>
                                  <div class="col-md-4 col-sm-4">
                                      <select id="proofDueTime" name="proofDueTime" class="form-control">
                                          <option value=""></option>
                                          <option value="12:00 PM">12:00 PM</option>
                                          <option value="01:00 PM">1:00 PM</option>
                                          <option value="02:00 PM">2:00 PM</option>
                                          <option value="03:00 PM">3:00 PM</option>
                                          <option value="04:00 PM">4:00 PM</option>
                                          <option value="05:00 PM">5:00 PM</option>
                                          <option value="06:00 PM">6:00 PM</option>
                                          <option value="07:00 PM">7:00 PM</option>
                                          <option value="08:00 PM">8:00 PM</option>
                                          <option value="09:00 PM">9:00 PM</option>
                                          <option value="10:00 PM">10:00 PM</option>
                                          <option value="11:00 PM">11:00 PM</option>
                                          <option value="12:00 PM">12:00 PM</option>
                                          <option value="01:00 AM">1:00 AM</option>
                                          <option value="02:00 AM">2:00 AM</option>
                                          <option value="03:00 AM">3:00 AM</option>
                                          <option value="04:00 AM">4:00 AM</option>
                                          <option value="05:00 AM">5:00 AM</option>
                                          <option value="06:00 AM">6:00 AM</option>
                                          <option value="07:00 AM">7:00 AM</option>
                                          <option value="08:00 AM">8:00 AM</option>
                                          <option value="09:00 AM">9:00 AM</option>
                                          <option value="10:00 AM">10:00 AM</option>
                                          <option value="11:00 AM">11:00 AM</option>
                                        </select>
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="col-md-12 col-sm-12">
                                  <label class="col-md-4 col-sm-4 control-label">Date Received</label>
                                  <div class="col-md-8 col-sm-8">
                                      <p id="showDateReceived" class="form-control-static">Date Received</p>
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="col-md-12 col-sm-12">
                                  <label for="serviceLevel" class="col-md-4 col-sm-4 control-label">Service Level</label>
                                  <div class="col-md-8 col-sm-8">
                                      <select id="serviceLevel" name="serviceLevel" class="form-control">
                                           <option value=""></option>
                                           <option value="Same Day">Same Day</option>
                                           <option value="24 Hour">24 Hour</option>
                                           <option value="48 Hour">48 Hour</option>
                                           <option value="72 Hour">72 Hour</option>
                                           <option value=">72 Hour">>72 Hour</option>
                                          
                                      </select>  
<!--                                     <input type="text" class="form-control" name="serviceLevel" id="serviceLevel">-->
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="col-md-12 col-sm-12">
                                  <label for="jobDueDate" class="col-md-4 col-sm-4 control-label">Job Due</label>
                                  <div class="col-md-4 col-sm-4">
                                     <input type="text" class="form-control" name="jobDueDate" id="jobDueDate" data-date-format="mm/dd/yyyy">
                                  </div>
                                  <div class="col-md-4 col-sm-4">
                                      <select id="jobDueTime" name="jobDueTime" class="form-control">
                                          <option value=""></option>
                                          <option value="12:00 PM">12:00 PM</option>
                                          <option value="01:00 PM">1:00 PM</option>
                                          <option value="02:00 PM">2:00 PM</option>
                                          <option value="03:00 PM">3:00 PM</option>
                                          <option value="04:00 PM">4:00 PM</option>
                                          <option value="05:00 PM">5:00 PM</option>
                                          <option value="06:00 PM">6:00 PM</option>
                                          <option value="07:00 PM">7:00 PM</option>
                                          <option value="08:00 PM">8:00 PM</option>
                                          <option value="09:00 PM">9:00 PM</option>
                                          <option value="10:00 PM">10:00 PM</option>
                                          <option value="11:00 PM">11:00 PM</option>
                                          <option value="12:00 PM">12:00 PM</option>
                                          <option value="01:00 AM">1:00 AM</option>
                                          <option value="02:00 AM">2:00 AM</option>
                                          <option value="03:00 AM">3:00 AM</option>
                                          <option value="04:00 AM">4:00 AM</option>
                                          <option value="05:00 AM">5:00 AM</option>
                                          <option value="06:00 AM">6:00 AM</option>
                                          <option value="07:00 AM">7:00 AM</option>
                                          <option value="08:00 AM">8:00 AM</option>
                                          <option value="09:00 AM">9:00 AM</option>
                                          <option value="10:00 AM">10:00 AM</option>
                                          <option value="11:00 AM">11:00 AM</option>
                                        </select>
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="col-md-12 col-sm-12">
                                  <label for="onPressDate" class="col-md-4 col-sm-4 control-label">On Press</label>
                                  <div class="col-md-4 col-sm-4">
                                     <input type="text" class="form-control" name="onPressDate" id="onPressDate"  data-date-format="mm/dd/yyyy">
                                  </div>
                                  <div class="col-md-4 col-sm-4">
                                      <select id="onPressTime" name="onPressTime" class="form-control">
                                          <option value=""></option>
                                          <option value="12:00 PM">12:00 PM</option>
                                          <option value="01:00 PM">1:00 PM</option>
                                          <option value="02:00 PM">2:00 PM</option>
                                          <option value="03:00 PM">3:00 PM</option>
                                          <option value="04:00 PM">4:00 PM</option>
                                          <option value="05:00 PM">5:00 PM</option>
                                          <option value="06:00 PM">6:00 PM</option>
                                          <option value="07:00 PM">7:00 PM</option>
                                          <option value="08:00 PM">8:00 PM</option>
                                          <option value="09:00 PM">9:00 PM</option>
                                          <option value="10:00 PM">10:00 PM</option>
                                          <option value="11:00 PM">11:00 PM</option>
                                          <option value="12:00 PM">12:00 PM</option>
                                          <option value="01:00 AM">1:00 AM</option>
                                          <option value="02:00 AM">2:00 AM</option>
                                          <option value="03:00 AM">3:00 AM</option>
                                          <option value="04:00 AM">4:00 AM</option>
                                          <option value="05:00 AM">5:00 AM</option>
                                          <option value="06:00 AM">6:00 AM</option>
                                          <option value="07:00 AM">7:00 AM</option>
                                          <option value="08:00 AM">8:00 AM</option>
                                          <option value="09:00 AM">9:00 AM</option>
                                          <option value="10:00 AM">10:00 AM</option>
                                          <option value="11:00 AM">11:00 AM</option>
                                        </select>
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                             <div class="col-md-12 col-sm-12">
                                 <div class="col-md-10 col-md-offset-2 col-sm-10 col-sm-offset-3">
<!--                                     <button id="createNewSalesOrder" type="submit" class="btn btn-primary">Create</button>-->
<!--                                     <button name ="createNewSalesOrder" id="createNewSalesOrder" class="btn btn-primary">Create</button>-->
                                 </div>
                             </div>
                          </div>
                    </div>
                        
                        <input type="hidden" name="customerIDHidden"               id="customerIDHidden">
                        <input type="hidden" name="custCompanyHidden"              id="custCompanyHidden">
                        <input type="hidden" name="qbCityHidden"                   id="qbCityHidden">
                        <input type="hidden" name="qbStateHidden"                  id="qbStateHidden">
                        
                        <input type="hidden" name="addressIDHidden"                id="addressIDHidden">
                        <input type="hidden" name="addressContactNameHidden"       id="addressContactNameHidden">
                        
                        <input type="hidden" name="newOrderIDHidden"               id="newOrderIDHidden" value="<?php if(!empty($orderID)) echo $orderID; ?>">
                        <input type="hidden" name="orderEmployeeIDSalesHidden"     id="orderEmployeeIDSalesHidden">
                </div>
            </div>
<!--            <div class="row hide">
                <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                         <div class="col-md-12 col-sm-12">
                             <div class="col-md-10 col-md-offset-2 col-sm-10 col-sm-offset-3">
                                 <button type="submit" class="btn btn-primary col-md-10 col-sm-10">Create</button>
                             </div>
                         </div>
                    </div>
                    
                </div>tabindex="-1"
            </div>-->
            </form>
            <button name ="createNewSalesOrder" id="createNewSalesOrder" class="btn btn-primary hide">Create</button>
            <div class="row">
                <div id="myModal" class="modal fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
    <!--                            <button id="closeMyModalTopBtn" type="button" class="close" aria-hidden="true">&times;</button>-->
                                    <h3 class="modal-title" id="myModalLabel">Set up Art Location</h3>
                            </div>
                            <div class="modal-body">
                                <form  name="salesOrderRequestDynamicModalFrm" id="salesOrderRequestDynamicModalFrm" class="form-horizontal" role="form">
                                <div class="col-md-12 col-sm-12">
                                     <div id="setUpArtLocationDiv" class="row">
                                        <div class="col-md-12 col-sm-12">
                                             <div class="form-group">
                                                  <div class="col-md-12 col-sm-12">
                                                      <label for="setUpartLocation" class="col-md-3 col-sm-3 control-label">Art Location</label>
                                                      <div class="col-md-8 col-sm-8">
                                                          <input type="text" class="form-control" name="setUpartLocation" id="setUpartLocation">
                                                      </div>
                                                  </div>
                                             </div>
                                        </div>
                                     </div>
                                </div>
                                     <input type="hidden"  name="dymanicModalTypeOfSubmitHidden"      id="dymanicModalTypeOfSubmitHidden">
                                     <input type="hidden" name="dymanicModalAddressIDHidden"          id="dymanicModalAddressIDHidden">
                                     <input type="hidden" name="dymanicModalAddressOtherIDHidden"     id="dymanicModalAddressOtherIDHidden">
                                </form>
                           </div>
<!--                            <p id="message">class="col-md-3 col-sm-3
                                    <img src="images/ajaxLoad/ajax-loader.gif" alt="Ajax Loading Animation" />&nbsp;&nbsp;&nbsp;&nbsp;<span><strong id="msgResult">Getting Data...</strong></span>
                            </p>-->
                           <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="button" id="saveArtLocationBtn" name="saveArtLocationBtn" class="btn btn-primary">Save changes</button>
                           </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </body>
</html>
