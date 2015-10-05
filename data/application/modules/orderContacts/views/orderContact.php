<!DOCTYPE html>
<html ang="en">
     <head>
        <meta charset=UTF-8">
        <title>Order Contacts</title>
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
        
        <script src="js/orderContactModule.js"></script>
       
<!--       <script src="http://ajax.microsoft.com/ajax/jquery.templates/beta1/jquery.tmpl.js"></script>-->
      
<!--       <style type="text/css">
           #record_ordShip tr:hover {cursor: pointer;}
       </style>-->

        <style type="text/css">
             #orderContactModalEditBtn {
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
                    <a id="addOrderContact" href="#">Add Contact to Order</a>
                </section>
                <section class="row-fluid">
                    <div class="span12">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="records" >
                            <thead>
                                <tr>
                                    <th>Contact Name</th>
                                    <th>Phone</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Remove</th>
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
                        
                         <script type="text/template" id="readTemplate">
                            <tr id="${ID}">
                                <td>${contact}</td>
                                <td>${phone}</td>
                                <td>${mobile}</td>
                                <td>${email}</td>
                                <td><a class="deleteBtn" href="${deleteLink}"> <i class="icon-remove"></i></a></td>
                            </tr>
                        </script>
                        
                    </div>
                </section>
            </div>
        </span>
        <div id="myModalConfirm" class="modal hide fade">
                 <div class="modal-header">
                     <h3 id="modalCustomHeading">Remove Contact</h3>
                 </div>
                 <div class="modal-body">
                     <form id="deleteFrmModalOrderContactTable" name="deleteFrmModalOrderContactTable" method="POST">
                         <p id="deleteConfirm"></p> 
                         <input type="hidden"  id="deleteModalOrderContactID" name="deleteModalOrderContactID" >
                     </form>
                    
                 </div>
                 <div id="dynamciFooterMyModalConfirm" class="modal-footer">
                     <button class="btn" id="deleteCancelBtnOrderContactIDModal" data-dismiss="modal" aria-hidden="true">Cancel</button>
                     &nbsp;
                     <button type="submit" id="deleteSubmitBtnOrderContactIDModal" class="btn  btn-primary">OK</button>
                 </div>
        </div>
        <div id="orderContactModalEditBtn" class="modal hide fade">
            <section class="row-fluid">
                <div class="modal-header">
                    <h3 id="modalCustomHeadingTemplateRecords">Update Action</h3>
                </div>
                <div class="modal-body">
                        <div class="span12" id="updateActionTemplateRecords">
                            <form class="form-horizontal" id="createFrmModalOrderContactTable" name="createFrmModalOrderContactTable">
                                <div class="control-group">
                                     <label class="control-label" for="modalContactNameFull">Contact Name: </label>
                                     <div class="controls">
                                         <select name="modalContactNameFull" id="modalContactNameFull" style="width:12em;">
                                         </select>
                                         <label class="error"></label>
                                     </div>
                                </div>
                                
                                <input type="hidden" id="modalOrderIDHidden"      name="modalOrderIDHidden" >
                                
<!--                                <input type="hidden" id="typeOfSubmitHidden"      name="typeOfSubmitHidden" >-->
                                
                                <input type="hidden" id="modalOrderContactID"     name="modalOrderContactID" >
                            </form>
                        
                        </div>
                    
                </div>
                <div class="modal-footer">
                    <button class="btn" id="closeCreateOrderContactModal" data-dismiss="modal" aria-hidden="true">Cancel</button>
                    &nbsp;
                    <button type="submit" id="validateCreateOrderContactModal" class="btn  btn-primary">Done</button>
<!--                                  <a id="validateModalOrderShipTracking" class="btn btn-primary" >Save</a>-->
                         
                </div>
                
            </section>
            
        </div>
            
        
        
        
    </body>
       
