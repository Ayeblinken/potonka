<!DOCTYPE html>
<html lang="en">
    <head>
         <meta charset="utf-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <meta name="description" content="">
         <meta name="author" content="">
         <title>Credit Release</title>
         <base href="<?php echo base_url(); ?>" />
         
         <script type="text/javascript">
            var orderID     = "<?php echo $orderID;?>";
           //alert(orderID);
         </script>
     
         <!-- Bootstrap core CSS -->
         <link href="bootstrap3/dist/css/bootstrap.css" rel="stylesheet">
     
         <!-- Bootstrap theme -->
         <link href="bootstrap3/dist/css/bootstrap-theme.min.css" rel="stylesheet">
     
         <!-- Bootstrap core JavaScript
         ================================================== -->
         <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
         
         <script src="js/jquery.validate.min.js"></script>
         <script src="js/additional-methods.min.js"></script>
         
         <script src="bootstrap3/dist/js/bootstrap.min.js"></script>
         
         <script src="js/clearForm.js"></script>
          
         <script src="js/orderModuleCreditRelease.js"></script>
          <style type="text/css">
           .form-horizontal .form-group {
                margin-bottom: 5px;
    /*if help block is in use 8 Tightens up the space inbetween fields*/
            }
           .error {
                color:red;
           }
/*           .modal-footer {
                 background-color: #f5f5f5;
           }*/
/*            .modal-footer {
  padding: 14px 15px 15px;
  margin-bottom: 0;
  text-align: right;
  background-color: #f5f5f5;
  border-top: 1px solid #ddd;
  -webkit-border-radius: 0 0 6px 6px;
     -moz-border-radius: 0 0 6px 6px;
          border-radius: 0 0 6px 6px;
  *zoom: 1;
  -webkit-box-shadow: inset 0 1px 0 #ffffff;
     -moz-box-shadow: inset 0 1px 0 #ffffff;
          box-shadow: inset 0 1px 0 #ffffff;
}*/
/*            .valid {
                color:green;
            }*/
           </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                     <!--This is the Customer Name Header-->
                     <div class="well well-sm">
                         <div class="row">
                             <div class="col-md-9 col-sm-12">
                                 <h4 id="companyNameContactName"></h4>
<!--                                 <h3><?php //echo $orderID; ?></h3>-->
<!--                                 <h3 id="companyNameContactName"></h3>-->
                             </div>
                         </div>
                     </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <form name="creditReleaseFrm" id="creditReleaseFrm" class="form-horizontal" role="form">
                        <div class="form-group">
                            <div class="col-md-12 col-sm-12">
                                <label class="col-md-2 col-sm-3 control-label">Credit Hold @ Order</label>
                                <div class="col-md-9 col-sm-9">
                                     <div class="checkbox">
                                        <label>
                                            <!--Orders.nb_CreditHoldTimeOrder-->
                                            <input onclick="return false" name="creditHoldTimeOrder" id="creditHoldTimeOrder" type="checkbox">  
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 col-sm-12">
                                <label for="typeOfHold" class="col-md-2 col-sm-3 control-label">Type Of Hold</label>
                                <div class="col-md-4 col-sm-4">
                                    <input type="text" class="form-control" name="typeOfHold" id="typeOfHold" readonly>
                                </div>
                            </div>   
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 col-sm-12">
                                <label class="col-md-2 col-sm-3 control-label">Override Credit Hold</label>
                                <div class="col-md-9 col-sm-9">
                                     <div class="checkbox">
                                        <label>
                                            <!--Orders.nb_CreditHoldOveride REQUIRED-->
                                            <input name="overRideCreditHoldCheckBox" id="overRideCreditHoldCheckBox" type="checkbox" value="1">  
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 col-sm-12">
                                <label for="creditHoldTypeOverRideSelect" class="col-md-2 col-sm-3 control-label">Type Override</label>
                                <div class="col-md-4 col-sm-4">
                                    <select class="form-control" name="creditHoldTypeOverRideSelect" id="creditHoldTypeOverRideSelect">
                                        <option value="">--Please Select--</option>
                                        <option value="Released">Released</option>
                                        <option value="Process Do Not Ship">Process Do Not Ship</option>
                                    </select>
                                </div>
                            </div>   
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 col-sm-12">
                                <label for="releasedBySelect" class="col-md-2 col-sm-3 control-label">Released By</label>
                                <div class="col-md-4 col-sm-4">
                                    <select class="form-control" name="releasedBySelect" id="releasedBySelect">
                                        <option value="">--Please Select--</option>
                                        <option value="Kit">Kit</option>
                                        <option value="Dan">Dan</option>
                                        <option value="Nikki">Nikki</option>
                                        <option value="Ebony">Ebony</option>
                                        <option value="Dave">Dave</option>
                                        <option value="Robbie">Robbie</option>
                                    </select>
                                </div>
                            </div>   
                        </div>
                        <div class="form-group">
                             <div class="col-md-12 col-sm-12">
                                 <label for="overRideNotes" class="col-md-2 col-sm-3 control-label">Override Note</label>
                                 <div class="col-md-4 col-sm-4">
                                     <textarea name="overRideNotes" id="overRideNotes" class="form-control" rows="6"></textarea>
                                 </div>
                             </div>
                        </div>
                        <div class="form-group">
                             <div class="col-md-12 col-sm-12">
                                 <div class="col-md-10 col-md-offset-2 col-sm-10 col-sm-offset-3">
                                     <button type="submit" class="btn btn-default">Save</button>
                                 </div>    
                                 
                             </div>
                        </div>
                        <input type="hidden" name="orderIDHidden" id="orderIDHidden">
                    </form>
                </div>    
            </div>
            <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                             <button id="closeMyModalTopBtn" type="button" class="close" aria-hidden="true">&times;</button>
                             <h3 class="modal-title" id="myModalLabel">Please wait</h3>
                        </div>
                        <div class="modal-body">
                            <p id="message">
                                <img src="images/ajaxLoad/ajax-loader.gif" alt="Ajax Loading Animation" />&nbsp;&nbsp;&nbsp;&nbsp;<span><strong>Submitting Data...</strong></span>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button id="closeMyModalBtn" type="button" class="btn btn-default hide">Close</button>
<!--                            <button id="closeMyModalBtn" type="button" class="btn btn-default hide" data-dismiss="modal">Close</button>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
