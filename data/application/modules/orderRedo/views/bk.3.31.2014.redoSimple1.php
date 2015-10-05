<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Redo</title>
        <base href="<?php echo base_url(); ?>" />
        <script type="text/javascript">
            
            var orderRedoID         = "<?php echo $orderRedoID;?>";
            var orderID             = "<?php echo $orderID;?>";
            var originalOrderID     = "<?php echo $originalOrderID;?>";
           //alert(orderID);
       </script>
        
        
<!--        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.no-icons.min.css" />-->
        <link rel="stylesheet" href="media/css_bootstrap/bootstrap.css" type="text/css"/>
        <link rel="stylesheet" href="media/css_bootstrap/bootstrap-responsive.css" type="text/css">
<!--        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.min.css" type="text/css">-->
        <link rel="stylesheet" href="media/redo.css" type="text/css">
<!--        <link rel="stylesheet" href="media/summernote.css" type="text/css"/>-->
        
        <!-- Photoswipe Gallery css) -->
        <link href="photoswipe/photoswipe-gallery.css" type="text/css" rel="stylesheet" />
        
        <!-- Load Queue widget CSS and jQuery -->
<!--        <style type="text/css">@import url(js_plupload/jquery.plupload.queue/css/jquery.plupload.queue.css);</style>-->
        
        <link href="photoswipe/photoswipe.css" type="text/css" rel="stylesheet" />
        <!--photoswipe Image Gallery files -->
        <script type="text/javascript" src="photoswipe/lib/klass.min.js"></script>
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="js/jquery.validate.min.js"></script>
        <script src="js/additional-methods.min.js"></script>
<!--        <script type="text/javascript" charset="utf-8" src="media/js/jquery.js"></script>-->
        
        
        <!-- include libraries BS3 -->
        
<!--        <script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>-->
 
        
        <script src="media/js_bootstrap/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/jquery-templ.js"></script>
        <script src="js/clearForm.js"></script>
          
        
        <!-- Load plupload and all it's runtimes and finally the jQuery queue widget -->
        <script type="text/javascript" src="js_plupload/plupload.full.js"></script>
        <script type="text/javascript" src="js_plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>
        
        <!--photoswipe Image Gallery files -->
        <script type="text/javascript" src="photoswipe/code.photoswipe.jquery-3.0.5.min.js"></script>
        
<!--       
        <script src="media/js_summernote/summernote.min.js"></script>-->
        <script src="js/redoReadOnly.js"></script>

    </head>
    <body>
         <div class="container-fluid">
            <div class="row-fluid">
                <!--Form start  -->
                <form class="form-horizontal" id="redoStatusFrm" name="redoStatusFrm">
                <div class="row-fluid">
                    <div class="span8">
                    <h4>Redo for: <a target="_blank"  href="orders/<?php echo $originalOrderID; ?>"><?php echo $originalOrderID; ?></a></h4>
                    </div>
<!--                    <div class="span4 pull-right">
                        <a id="topSubmitPendingRedoFrm" name="topSubmitPendingRedoFrm" href="#" class="btn btn-primary">Submit</a>
                    </div>-->
                </div>
                <div class="row-fluid">
                    <div class="well well-small span6">
<!--                        <form class="form-horizontal">-->
                            <fieldset>
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Requested By</label>
                                        <div class="controls">
                                            <input readonly="true" id="requestedBy" name="requestedBy" type="text">
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Order Urgency</label>
                                        <div class="controls">
                                            <select name="orderUrgency" id="orderUrgency" disabled="true">
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
                                            <select name="shippingUrgency" id="shippingUrgency" disabled="true">
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
                                    <div class="control-group">
                                        <label class="control-label">Date Time Requested</label>
                                        <div class="controls">
                                            <input readonly="true" name="dateTimeWhenRequested" id="dateTimeWhenRequested" type="text" class="">
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Redo Status</label>
                                        <div class="controls">
                                            <select disabled="true" name="redoStatus" id="redoStatus" class="">
                                                <option value="Pending">Pending</option>
                                                <option value="Need More Info">Need More Info</option>
                                                <option value="Approved">Approved</option>
                                                <option value="Resolved (No Redo)">Resolved (No Redo)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Approved By</label>
                                        <div class="controls">
                                            <input readonly="true" type="text" class="typeahead" id="approvedBy" name="approvedBy" autocomplete="off" data-provide="typeahead">
                                        </div>
                                    </div>
                                    <div id="approvedControlGroup" class="control-group hide">
                                        <label class="control-label">Date and Time Approved</label>
                                        <div class="controls">
                                            <input readonly="true" name="dateTimeApproved" id="dateTimeApproved" type="text" class="">
                                        </div>
                                    </div>
                                    <div id="orderIDRedoControlGroup" class="control-group hide">
                                        <label class="control-label">Redo Order#</label>
                                        <div class="controls">
                                            <input readonly="true" name="orderIDRedo" id="orderIDRedo" type="text" class="">
                                        </div>
                                    </div>
<!--                                    <div id="createNewOrderControlGroup" class="control-group hide">
                                        <label class="control-label"></label>
                                        <div class="controls">
                                            <a href="#"  id="createNewRedoOrder" name="createNewRedoOrder" class="btn"><i class="icon-plus"></i> Create New Redo Order</a>
                                        </div>
                                    </div>-->
                                </div> 

                                &nbsp;
                                <hr>
                                <div class="control-group">
                                    <label class="control-label">Order Items With Redo's</label>
                                    <div class="controls">
                                        <!--Select sets OrderRedo.t_ItemsRedo-->
                                        <select disabled="true" name="itemsRedo" id="itemsRedo">   
                                            <option value="All Items">All Items</option>
                                            <option value="Partial">Partial</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="displayPartial" class="control-group hide">
                                    <label for="selectedPartial" class="control-label">You Selected</label>
                                    <div class="controls">
                                        <textarea rows="4" id="selectedPartial" name="selectedPartial" readonly="true"></textarea>
<!--                                            <button type="submit" name="changePartial" id="changePartial" class="btn">Change</button>-->
                                   </div>
                                </div><br/>
                                <span id="partialAllItemRedoError"></span>
<!--                                <p>Show a list of line items selected if set to Partial: -1, -2, -3, -4</p>
                                <p>If set to partial this should show the line items that are being redone</p>
                                <a href="#">Click Here to Adjust Line Items</a>-->
                            </fieldset>
                         
<!--                        </form>-->
                    </div>
<!--                   <form class="form-horizontal">-->
                    <div class="well span6">
                        <h5>Department(s) Responsible</h5>
                            <div class="row-fluid">
                                <div class="control-group">
                                    <div class="controls span4">
                                        <label class="checkbox">
                                            <input onclick="return false" type="checkbox" class="redoDepartmentChange" name="redoDepartResponsible[]" value="sales" >Sales</label>
                                        <label class="checkbox">
                                            <input onclick="return false" type="checkbox" class="redoDepartmentChange" name="redoDepartResponsible[]" value="customerservice">Customer Service</label>
                                        <label class="checkbox">
                                            <input onclick="return false" type="checkbox" class="redoDepartmentChange" name="redoDepartResponsible[]" value="prepress" >Prepress</label>
                                        <label class="checkbox">
                                            <input onclick="return false" type="checkbox" class="redoDepartmentChange" name="redoDepartResponsible[]" value="press" >Press</label>
                                        <label class="checkbox">
                                            <input onclick="return false" type="checkbox" class="redoDepartmentChange" name="redoDepartResponsible[]" value="finishing" >Finishing</label>
                                    </div>
                                    <div class="controls span4">
                                        <label class="checkbox">
                                            <input onclick="return false" type="checkbox" class="redoDepartmentChange" name="redoDepartResponsible[]" value="inspection" >QA Inspection</label>
                                        <label class="checkbox">
                                            <input onclick="return false" type="checkbox" class="redoDepartmentChange" name="redoDepartResponsible[]" value="shipping" >Shipping</label>
                                        <label class="checkbox">
                                            <input onclick="return false" type="checkbox" class="redoDepartmentChange" name="redoDepartResponsible[]" value="accounting" >Accouting</label>
                                        <label class="checkbox">
                                            <input onclick="return false" type="checkbox" class="redoDepartmentChange" name="redoDepartResponsible[]" value="material failure" >Material Failure</label>
                                        <label class="checkbox">
                                            <input onclick="return false" type="checkbox" class="redoDepartmentChange" name="redoDepartResponsible[]" value="customer concession" >Customer Concession</label>
                                    </div>
                                </div> <br/>
                                <span id="customDepartmentError"></span>
                            </div>
                            <div class="row-fluid">
                                &nbsp;
                                <hr>
                                <h5>Employees (if any) Responsible</h5>
                                <div class="control-group">
                                    <label class="control-label">Prepress</label>
                                    <div class="controls">
                                        <input readonly="true" id="namePrepress" name="namePrepress" type="text">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Press</label>
                                    <div class="controls">
                                        <input readonly="true" id="press" name="press" type="text">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Inspection</label>
                                    <div class="controls">
                                        <input readonly="true" id="inspection" name="inspection"  type="text">
                                    </div>
                                </div>
                            </div>
                    </div>
                     
<!--                    </form>-->
                </div>

                <div class="row-fluid">
                    <div class="span6">
                        <p><strong>CUSTOMERS CONCERNS?</strong></p>
                        <!--Textarea sets OrderRedo.t_CustomerIssue-->
                        <p readonly="true"  id="customerConcern" name="customerConcern" class="span12"></p>
                    </div>
                    <div class="span6">
                        <p><strong>WHAT SALES THINKS HAPPENED?</strong></p>
                        <!--Textarea sets OrderRedo.t_SalesViewIssue-->
                        <p readonly="true"  id="saleConcern" name="saleConcern" class="span12"></p>
                    </div>
                </div><hr>
                <div class="row-fluid">
                    <div class="span12">
                        <h5>RESEARCHED PROBLEM</h5>
                        <div id="researchedProblem">
                            
                        </div>
                    </div>
                    <input type="hidden" name="resProbHiddenVal" id="resProbHiddenVal" />
                </div><hr>
                &nbsp;
                <div class="row-fluid">
                    <div class="span12">
                        <h5>SOLUTION</h5>
                        <div id="solutionProvided"></div>
                    </div>
                    <input type="hidden" name="solutionHiddenVal" id="solutionHiddenVal" />
                </div>
                &nbsp;
                   <input type="hidden" name="redoStatusHidden"                id="redoStatusHidden" />
                   <input type="hidden" name="orderItemIDArryHidden"           id="orderItemIDArryHidden" />
                   <input type="hidden" name="partialOrderItemsWithRedoHidden" id="partialOrderItemsWithRedoHidden" />
                   <input type="hidden" name="orderRedoIDHidden"               id="orderRedoIDHidden" /> 
                   <input type="hidden" name="redoDeptRespHidden"              id="redoDeptRespHidden" />
                   <input type="hidden" name="orderIDHidden"                   id="orderIDHidden" />
                   
                   <input type="hidden" name="photosUploaded"                  id="photosUploaded" />
                </form>
<!--                <div class="row-fluid">
                    <a id="submitPendingRedoFrm" name="submitPendingRedoFrm" href="#" class="btn btn-primary pull-right">Submit</a>
                </div><br/>-->
                <form>  
<!--                <div class="row-fluid">
                    <div class="well well-small span12">
                        <a id="displayPluploadPlugin" href="#">Click Here to Add more Images</a>
                        <div class="hide" id="uploader">
                            <section class="row-fluid">
                                <div class="span9 offset3">
                                    <p>You browser doesn't have Flash or HTML5 support.</p>
                                </div>  
                            </section>
                        </div>
                    </div>
                </div>-->
                </form>     
                <div class="row-fluid">
                        <ul id="GalleryImages" class="gallery">
                        </ul>  
                </div><hr>
                <div class="row-fluid">
                     <a id="redoRequestPage" href="#" class="btn"><i class="icon-plus"></i> Redo Request</a>
                     <br> &nbsp;
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <table id="records" class="table table-condensed table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                     <th>Status</th>
                                     <th>Items w/ Redo</th>
                                     <th>Redo Order#</th>
                                     <th>Department</th>
                                     <th>Customer Issue</th>
                                     <th>Problem</th>
                                     <th>Solution</th>
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
                                     <td></td>
                                </tr>
                            </tbody>
                        </table>
                        <script type="text/template" id="readTemplate">
                            <tr id="${OrderRedoID}">
                                <td><a class="statusBtn" href="#">${Status}</a></td>
                                <td>${ItemsRedo}</td>
                                <td><a class="orderIDBtn" href="#">${kf_OrderIDRedo}</a></td>
                                <td>${Department}</td>
                                <td>${CustomerIssue}</td>
                                <td>${Problem}</td>
                                <td>${solution}</td>
                            </tr>
                        </script>
                    </div>
                </div>
                
            </div>
        </div>
       
       
    </body>
   
</html>
<?php   //echo  Modules::run('orderRedo/orderredocontroller/redoListView',$orderID); ?>
 

