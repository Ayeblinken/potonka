<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Redo Request</title>
        <base href="<?php echo base_url(); ?>" />
        <script type="text/javascript">
            
            var orderID = "<?php echo $orderID;?>";
           //alert(orderID);
       </script>
        <link rel="stylesheet" href="media/css_bootstrap/bootstrap.css" type="text/css"/>
        <link rel="stylesheet" href="media/css_bootstrap/bootstrap-responsive.css" type="text/css">
<!--        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.min.css" type="text/css">-->
        <link rel="stylesheet" href="media/redorequest.css" type="text/css">
        
        <!-- Load Queue widget CSS and jQuery -->
        <style type="text/css">@import url(js_plupload/jquery.plupload.queue/css/jquery.plupload.queue.css);</style>
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="js/jquery.validate.min.js"></script>
        <script src="js/additional-methods.min.js"></script>
        
        <script src="media/js_bootstrap/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/jquery-templ.js"></script>
        <script src="js/clearForm.js"></script>
        
         <!-- Load plupload and all it's runtimes and finally the jQuery queue widget -->
        <script type="text/javascript" src="js_plupload/plupload.full.js"></script>
        <script type="text/javascript" src="js_plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>
        
        <script src="js/redorequest.js"></script>
        <style type="text/css">
           .error {
                color:red;
            }
/*            .valid {
                color:green;
            }*/
        </style>
    </head>
    <body> 
        <div class="container">
            <div class="row-fluid">
                <div class="span12">
                        <h4>SALES REDO RESEARCH FORM</h4>
                        <hr>
                    <form class="form-horizontal" name="redoRequestFrm" id="redoRequestFrm">
<!--                    <div class="row-fluid">
                        <div class="well span6">
                              <div class="row-fluid">
                                    <br>&nbsp;
                                    <div class="control-group">
                                        <label for="requestBy" class="control-label">Request By</label>
                                            <div class="controls">
                                               <input type="text" class="typeahead" id="requestBy" name="requestBy" autocomplete="off" data-provide="typeahead">
                                            </div>
                                    </div>
                              </div>
                        </div>   
                    </div>    -->
                    <div class="row-fluid">
                        <div class="well span6">
                                <fieldset>
                                    <div class="row-fluid inline">
                                        <h5 id="redoRequestHeading">You are requesting a Redo on Order: 111234</h5>
                                        <!--<br>&nbsp;-->
                                        <div class="control-group">
                                            <label for="requestBy" class="control-label">Request By</label>
                                            <div class="controls">
                                               <input type="text" class="typeahead" id="requestBy" name="requestBy" autocomplete="off" data-provide="typeahead">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Order Items With Redo's</label>
                                            <div class="controls">
                                                <!--Select sets OrderRedo.t_ItemsRedo-->
                                                <select name="itemsRedo" id="itemsRedo">
                                                    <option value="">--Please select--</option>    
                                                    <option value="All Items">All Items</option>
                                                    <option value="Partial">Partial</option>
                                                </select>
                                            </div>
                                        </div>
                                         <div id="displayPartial" class="control-group hide">
                                            <label for="selectedPartial" class="control-label">You Selected</label>
                                            <div class="controls">
                                                <textarea rows="4" id="selectedPartial" name="selectedPartial" readonly="true"></textarea>
                                                <button type="submit" name="changePartial" id="changePartial" class="btn">Change</button>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Order Urgency</label>
                                            <div class="controls">
                                                <select name="orderUrgency" id="orderUrgency">
                                                    <option value="">--Please select--</option>    
                                                    <option value="Ship Today">Ship Today</option>
                                                    <option value="Ship Today">Ship Tomorrow</option>
                                                    <option value="Ship Today">Ship 48 Hours</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Shipping Urgency</label>
                                            <div class="controls">
                                                <select name="shippingUrgency" id="shippingUrgency">
                                                    <option value="">--Please select--</option>    
                                                    <option value="Ground">Ground</option>
                                                    <option value="Freight">Freight</option>
                                                    <option value="Overnight Standard">Overnight Standard</option>
                                                    <option value="Overnight Express">Overnight Express</option>
                                                    <option value="Overnight Early A.M. - Need Approval">Overnight Early A.M. - Need Approval</option>
                                                    <option value="Courier">Courier</option>
                                                    <option value="Yes Freight">Yes Freight</option>
                                                    <option value="Pickup">Pickup</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                        </div>
                        <div class="well well-small span6">
                            <div class="row-fluid">
                                <h5>Department(s) Responsible</h5>
                                    <fieldset>
                                        <!--Checkbox sets OrderRedo.t_Department-->
                                            <div class="controls span6">
                                                <label class="checkbox">
                                                    <input type="checkbox" class="departmentChange" name="departResponsible[]" value="sales">Sales
                                                </label>
                                                <label class="checkbox">
                                                    <input type="checkbox" class="departmentChange" name="departResponsible[]" value="customerservice">Customer Service
                                                </label>
                                                <label class="checkbox">
                                                    <input type="checkbox" class="departmentChange" name="departResponsible[]" value="prepress" id="prepress">Prepress
                                                </label>
                                                <label class="checkbox">
                                                    <input type="checkbox" class="departmentChange" name="departResponsible[]" value="press" id="press">Press
                                                </label>
                                            </div>
                                            <div class="controls span6">
                                                <label class="checkbox">
                                                    <input type="checkbox" class="departmentChange" name="departResponsible[]" value="finishing" id="finishing">Finishing
                                                </label>
                                                <label class="checkbox">
                                                    <input type="checkbox" class="departmentChange" name="departResponsible[]" value="inspection" id="inspection">Inspection
                                                </label>
                                                <label class="checkbox">
                                                    <input type="checkbox" class="departmentChange" name="departResponsible[]" value="shipping" id="shipping">Shipping
                                                </label>
                                                <label class="checkbox">
                                                     <input type="checkbox" class="departmentChange" name="departResponsible[]" value="material failure" >Material Failure
                                                </label>
                                                <label class="checkbox">
                                                  <input type="checkbox" class="departmentChange" name="departResponsible[]" value="customer concession" >Customer Concession
                                                </label>
                                            </div>
                                    </fieldset><br/>
                                    <span id="customDepartmentError"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="well span6">
                            <p><strong>WHAT ARE THE CUSTOMERS CONCERNS?</strong></p>
                            <!--Textarea sets OrderRedo.t_CustomerIssue-->
                                <textarea id="customerIssue" name="customerIssue" rows="8" class="span12" placeholder="Please, be as specific as possible..."></textarea>
                        </div>
                        <div class="well span6">
                            <p><strong>SALES WHAT DO YOU THINK HAPPENED?</strong></p>
                            <!--Textarea sets OrderRedo.t_SalesViewIssue-->
                            <textarea id="salesIssue" name="salesIssue" rows="8" class="span12" placeholder="Please, be as specific as possible..."></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="ordNU"                              id="ordNU" />
                    <input type="hidden" name="orRedoIDNU"                         id="orRedoIDNU" />
                    
                    <input type="hidden" name="deptRespNU"                         id="deptRespNU" />
                    <input type="hidden" name="partialOrderItemsWithRedoHidden"    id="partialOrderItemsWithRedoHidden" />
                    <input type="hidden" name="picYesNoHidden"                     id="picYesNoHidden" />
                    <input type="hidden" name="displayselectedPartialValuesHidden" id="displayselectedPartialValuesHidden" />
                    </form>    
                    <form>    
                    <div class="row-fluid">
                        <div class="well span12">
                            <p><strong>Upload Customers Photos of Jobs</strong></p>
                           
                                <div class="control-group">
                                    <label class="control-label">Do you have pictures?</label>
                                    <div class="controls">
                                        <!--Select sets OrderRedo.t_HasPhotos-->
                                        <select name="picturesYesNo" id="picturesYesNo" class="span1">
                                            <option></option>    
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                            
                            <div class="hide" id="uploader">
                                <section class="row-fluid">
                                    <div class="span9 offset3">
                                        <p>You browser doesn't have Flash or HTML5 support.</p>
                                    </div>  
                                </section>
		
                            </div>
                        </div>
                    </div>
                   
                    <a id="submitRedoRequestFrm" name="submitRedoRequestFrm" href="#" class="btn pull-left hide">Submit</a>
<!--                    <p>On submit we need to set the following fields:<br>
                        OrderRedo.ts_DateRequested = Current Timestamp<br>
                        OrderRedo.t_Status = Pending<br>     
                    </p>-->
                    <input type="hidden" name="orderIDHidden"     id="orderIDHidden" />
                    <input type="hidden" name="orderRedoIDHidden" id="orderRedoIDHidden" />
                  
                    <input type="hidden" name="departmentResponsibleHidden" id="departmentResponsibleHidden" />
                    </form>  
                </div>
                    
            </div> <!--row-fluid-->
        </div>  <!--Container-->
       
        <div id="partial" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="myModalLabel">Check Items We Need to Redo</h3>
            </div>
            <div class="modal-body">
                <table id="orderItemsWithRedoTbl" name="orderItemsWithRedoTbl" class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                             <th><input type="checkbox" id="selectAllCheck" />&nbsp;&nbsp;<small><i id="orderItemProofIcon" class="icon-ok"></i><i id="orderItemProofIcon" class="icon-remove"></i>&nbsp;all</small></th>
                            <th>#</th>
                            <th>Size</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <script type="text/template" id="orderItemsWithRedoTblTemplate">
                    <tr id="${orderItemID}">
                        <td><input name="orderItemWithRedoCheckBox"  class="checkOrderItemsWithRedo" type="checkbox" value="${orderItemID}"></td>
                        <td>${dashNum}</td>
                        <td>${qtyHtWPro}</td>
                        <td>${description}</td>
                    </tr>
                </script>
            </div>
            <div class="modal-footer">
<!--                <button id="cancelBtnOrderRedoItems" class="btn">Close</button>-->
                <button id="submitBtnOrderRedoItems" class="btn btn-primary">Save</button>
            </div>
        </div>
        <div id="myModal" class="modal hide fade">
            <div class="modal-header">
                <h3>Please wait</h3>
            </div>
            <div class="modal-body">
                <p id="uploadedFiles">
                    <img src="images/ajaxLoad/ajax-loader.gif" alt="Ajax Loading Animation" />&nbsp;&nbsp;&nbsp;&nbsp;<span><strong>Submitting Data...</strong></span>
                </p>
            </div>
            <div class="modal-footer">
                <button id="closeMyModalBtn" class="btn hide">Close</button>
            </div>
        </div>

    </body>
</html>
