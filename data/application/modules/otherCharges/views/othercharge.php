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
        
        
         
        
        
        <script type="text/javascript" charset="utf-8" src="media/js/jquery.js"></script>
        <script src="js/jquery.maskedinput.js"></script>
        <script src="js/jquery.validate.min.js"></script>
        <script src="js/additional-methods.min.js"></script>
        
        
        
       
        <script type="text/javascript" charset="utf-8" src="media/js_bootstrap/bootstrap.min.js"></script>
        
        
        
        <script type="text/javascript" src="js/jquery-templ.js"></script>
        
        <script src="js/clearForm.js"></script>
        
        <script src="js/otherChargeModule.js"></script>
       
<!--       <script src="http://ajax.microsoft.com/ajax/jquery.templates/beta1/jquery.tmpl.js"></script>-->
      
<!--       <style type="text/css">
           #record_ordShip tr:hover {cursor: pointer;}
       </style>-->

        <style type="text/css">
            
         
            
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
                    <a id="addOtherCharges" href="#">Add Other Charges</a>
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
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
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
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        
                         <script type="text/template" id="readTemplate">
                            <tr id="${ID}">
                                <td><a class="updateBtn" href="${updateLink}">${ID}</a></td>
                                <td>${Category}</td>
                                <td>${Description}</td>
                                <td>${Quantity}</td>
                                <td>${Price}</td>
                                <td>${Total}</td>
                                <td><a class="deleteBtn" href="${deleteLink}">Delete</a></td>
                            </tr>
                        </script>
                        
                    </div>
                </section>
            </div>
        </span>
        <div id="myModalConfirm" class="modal hide fade">
                 <div class="modal-header">
                     <h3 id="modalCustomHeading">Delete Action</h3>
                 </div>
                 <div class="modal-body">
                     <form id="deleteFrmModalOtherChargeTable" name="deleteFrmModalOtherChargeTable" method="POST">
                         <p id="deleteConfirm"></p> 
                         <input type="hidden"  id="deleteModalOtherChargeID" name="deleteModalOtherChargeID" >
                     </form>
                    
                 </div>
                 <div id="dynamciFooterMyModalConfirm" class="modal-footer">
                     <button class="btn" id="deleteCancelBtnOtherChargeIDModal" data-dismiss="modal" aria-hidden="true">Cancel</button>
                     &nbsp;
                     <button type="submit" id="deleteSubmitBtnOtherChargeIDModal" class="btn  btn-primary">OK</button>
<!--                     <a id="deleteRecord" class="btn btn-primary" >OK</a> Previous Version of code -->
                 </div>
        </div>
        <div id="otherChargeModalEditBtn" class="modal hide fade">
            <section class="row-fluid">
                <div class="modal-header">
                    <h3 id="modalCustomHeadingTemplateRecords">Update Action</h3>
                </div>
                <div class="modal-body">
                        <div class="span9" id="updateActionTemplateRecords">
                            <form class="form-horizontal" id="updateCreateFrmModalOtherChargeTable" name="updateCreateFrmModalOtherChargeTable">
                                <div class="control-group">
                                     <label class="control-label" for="modalCategory">Category: </label>
                                     <div class="controls">
                                         <select name="modalCategory" id="modalCategory" style="width:12em;">
                                             <option value="">Select Category</option>
                                             <option value="Prepress">Prepress</option>
                                             <option value="Finishing">Finishing</option>  
                                             <option value="Installation">Installation</option>
                                             <option value="Other Charges">Other Charges</option>  
                                         </select>
                                         <label class="error"></label>
                                     </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="modalDescription">Description: </label>
                                    <div class="controls">
                                         <input type="text"  class="input-medium" placeholder="Description"  name="modalDescription" id="modalDescription">
                                         <label class="error"></label>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="modalQty">Qty: </label>
                                    <div class="controls">
                                         <input type="text"  class="input-medium" placeholder="Qty"  name="modalQty" id="modalQty">
                                         <label class="error"></label>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="modalPrice">Price: </label>
                                    <div class="controls">
                                        <input type="text"  class="input-medium" placeholder="Price"  name="modalPrice" id="modalPrice">
                                        <label class="error"></label>
                                    </div>
                                </div>
                                <input type="hidden" id="modalOrderIDHidden"      name="modalOrderIDHidden" >
                                
                                <input type="hidden" id="typeOfSubmitHidden" name="typeOfSubmitHidden" >
                                
                                <input type="hidden" id="modalOtherChargeID" name="modalOtherChargeID" >
                            </form>
                        
                        </div>
                    
                </div>
                <div class="modal-footer">
                    <button class="btn" id="closeUpdateOtherChargeModal" data-dismiss="modal" aria-hidden="true">Cancel</button>
                    &nbsp;
                    <button type="submit" id="validateUpdateOtherChargeModal" class="btn  btn-primary">Done</button>
<!--                                  <a id="validateModalOrderShipTracking" class="btn btn-primary" >Save</a>-->
                         
                </div>
                
            </section>
            
        </div>
            
        
        
        
    </body>
       
