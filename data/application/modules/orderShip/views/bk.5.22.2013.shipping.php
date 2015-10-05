<meta http-equiv="X-UA-Compatible" content="chrome=1">
<!DOCTYPE html>
<html ang="en">
    <head>
        <meta charset=UTF-8">
       
        <title>Shipping</title>
        
        <base href="  <?php echo base_url(); ?>" />
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
        <script src="js/orderShipModule.js"></script>
        
        <style type="text/css">
            #myModalEditBtn {
                    width: 610px; /* SET THE WIDTH OF THE MODAL */
                    margin: -199px 0 0 -230px; /* CHANGE MARGINS TO ACCOMODATE THE NEW WIDTH (original = margin: -250px 0 0 -280px;) */
                }
            label.error {
                        display: inline-block;
			font-weight: bold;
			color: red;
			font-size: 87.5%;
                        padding: 2px 8px;
			margin-top: 2px;
			
		}
        </style>
        
      
         
    </head>
    <body>
       
        <span id="table-wrapper">
         
            <div class="container" style="margin-top: 10px">
                <nav>
                    <ul class="nav nav-pills">
                        <li> <a id="createNew" href="#" >Add New Record</a></li>
                        <li> <a id="shippingReport" target="_blank" >Shipping Report</a></li>
                        <li> <a id="addReceipient" target="_blank" >Add Receipient</a></li>
                        <li> <a id="addBlind" target="_blank" >Add Blind 3rd Party</a></li>
                    </ul>
                </nav>
               
               
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="records" >
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Details</th>
                            <th>Receipeint</th>
                            <th>Blind - Third Party</th>
                            <th>Tracking #'s</th>
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
                            <td>${Details}</td>
                            <td>${Receipeint}</td>
                            <td>${Blind}</td>
                            <td><a class="trackBtn" href="${Tracking}">${Tracking}</a></td>
                            <td><a class="deleteBtn" href="${deleteLink}">Delete</a></td>
                            <td class="hidden">${shipperIDShipperServiceID}</td>
                        </tr>
                 </script>
      
            </div>
<!--            <div id="myModalConfirm" class="modal hide fade">
                 <div class="modal-header">
                     <h3 id="modalCustomHeading">Delete Action</h3>
                 </div>
                 <div class="modal-body">
                     <form id="deleteFrmModalOrderShipTable" name="deleteFrmModalOrderShipTable" method="POST">
                         <p id="deleteConfirm"></p> 
                         <input type="hidden"  id="deleteModalOrderShipID" name="deleteModalOrderShipID" >
                     </form>
                    
                 </div>
                 <div id="dynamciFooterMyModalConfirm" class="modal-footer">
                     <button class="btn btn-info" id="deleteCancelBtnOrderShipIDModal" data-dismiss="modal" aria-hidden="true">Cancel</button>
                     &nbsp;
                     <button type="submit" id="deleteSubmitBtnOrderShipIDModal" class="btn  btn-primary">OK</button>
                     <a id="deleteRecord" class="btn btn-primary" >OK</a> Previous Version of code 
                 </div>
            </div>-->

<!--         Below is the 'Modal update' commented  out- orderShipID can be modified even with a tracking number. 
             <div id="myModalUpdate" class="modal hide fade">
                 <div class="modal-header">
                     <h3 id="modalCustomHeadingUpdate">Update Action</h3>
                 </div>
                 <div class="modal-body">
                     <p id="updateAction">
                        
                     </p>
                 </div>
                 <div class="modal-footer">
                     <button class="btn, btn-primary" data-dismiss="modal">Ok</button>
                 </div>
             </div>-->
        </span>
        <div id="myModalConfirm" class="modal hide fade">
                 <div class="modal-header">
                     <h3 id="modalCustomHeading">Delete Action</h3>
                 </div>
                 <div class="modal-body">
                     <form id="deleteFrmModalOrderShipTable" name="deleteFrmModalOrderShipTable" method="POST">
                         <p id="deleteConfirm"></p> 
                         <input type="hidden"  id="deleteModalOrderShipID" name="deleteModalOrderShipID" >
                     </form>
                    
                 </div>
                 <div id="dynamciFooterMyModalConfirm" class="modal-footer">
                     <button class="btn btn-info" id="deleteCancelBtnOrderShipIDModal" data-dismiss="modal" aria-hidden="true">Cancel</button>
                     &nbsp;
                     <button type="submit" id="deleteSubmitBtnOrderShipIDModal" class="btn  btn-primary">OK</button>
<!--                     <a id="deleteRecord" class="btn btn-primary" >OK</a> Previous Version of code -->
                 </div>
        </div>
        <div id="myModalEditBtn" class="modal hide fade">
                <div class="modal-header">
                    <h3 id="modalCustomHeadingDataTable">Update Action</h3>
                </div>
                <div class="modal-body">
                     <section class="row-fluid">
                        <div class="span9" id="updateActionDataTable">
                            <form id="updateFrmModalAddress" name="updateFrmModalAddress">
                                <fieldset>
                                    <legend> Ship To Contact Info</legend>
                                        <div class="controls">
                                            <input type="text" class="input-xlarge" placeholder="Company Name"  name="companyNameModal" id="companyNameModal">
                                            <label class="error"></label><label></label>

                                        </div>
                                        <div class="controls">
                                            <input type="text" class="input-xlarge"  placeholder="Contact Name" name="contactNameModal" id="contactNameModal">
                                            <label class="error"></label><label></label>
<!--                                                <span id="contactNameModalError" style="color: red; font-size:67.5%">*</span>-->
                                        </div>
                                         <div class="controls">
                                            <input type="text" class="input-large"  placeholder="Title" name="titleModal" id="titleModal">
                                        </div>
                                         <div class="controls">
                                            <input type="text" class="input-large" name="emailModal" placeholder="email" id="emailModal">
<!--                                                 <span id="emailModalError"  style="color: red; font-size:67.5%">*</span>-->
                                         </div>

                                </fieldset>
                                <fieldset>
                                     <legend>Phone Info</legend>

                                        <div class="controls">
                                           <input type="text" class="input-large" name="phoneModal" placeholder="phone" id="phoneModal">
                                            <label class="error"></label><label></label>
                                        </div>

                                        <div class="controls">
                                           <input type="text" class="input-large" placeholder="fax" name="faxModal" id="faxModal">
                                        </div>


                                        <div class="controls">
                                           <input type="text" class="input-large" name="mobileModal" placeholder="mobile" id="mobileModal">
                                        </div>



                                </fieldset>
                                <fieldset>
                                       <legend>Address Info</legend>
                                           <div class="controls">
                                               <select name="countryModal" id="countryModal" style="width:12em;">

                                               </select>
                                               <label></label>
                                           </div>


                                           <div class="controls">
                                              <input type="text" class="input-xlarge" placeholder="Address1" name="Address1Modal" id="Address1Modal">
                                               <label class="error"></label><label></label>
                                           </div>


                                           <div class="controls">
                                              <input type="text" class="input-xlarge" placeholder="Address2" name="Address2Modal" id="Address2Modal">

                                           </div>


                                           <div class="controls">
                                              <input type="text" class="input-medium" placeholder="city" name="cityModal" id="cityModal">
                                              <label class="error"></label><label></label>
<!--                                                    <span id="cityModalError" style="color: red; font-size:67.5%">*</span>-->
                                           </div>

                                           <div class="controls">
                                              <select name="stateModal" id="stateModal" style="width:12em;">
                                                  <option value="">State</option>
<!--                                                      <option value="AL">AL</option>
                                                  <option value="AK">AK</option>
                                                  <option value="AZ">AZ</option>
                                                  <option value="AR">AR</option>
                                                  <option value="CA">CA</option>
                                                  <option value="CO">CO</option>
                                                  <option value="CT">CT</option>
                                                  <option value="DE">DE</option>
                                                  <option value="DC">DC</option>
                                                  <option value="FL">FL</option>
                                                  <option value="GA">GA</option>
                                                  <option value="HI">HI</option>
                                                  <option value="ID">ID</option>
                                                  <option value="IL">IL</option>
                                                  <option value="IN">IN</option>
                                                  <option value="IA">IA</option>
                                                  <option value="KS">KS</option>
                                                  <option value="KY">KY</option>
                                                  <option value="LA">LA</option>
                                                  <option value="ME">ME</option>
                                                  <option value="MD">MD</option>
                                                  <option value="MA">MA</option>
                                                  <option value="MI">MI</option>
                                                  <option value="MN">MN</option>
                                                  <option value="MS">MS</option>
                                                  <option value="MO">MO</option>
                                                  <option value="MT">MT</option>
                                                  <option value="NE">NE</option>
                                                  <option value="NV">NV</option>
                                                  <option value="NH">NH</option>
                                                  <option value="NJ">NJ</option>
                                                  <option value="NM">NM</option>
                                                  <option value="NY">NY</option>
                                                  <option value="NC">NC</option>
                                                  <option value="ND">ND</option>
                                                  <option value="OH">OH</option>
                                                  <option value="OK">OK</option>
                                                  <option value="OR">OR</option>
                                                  <option value="PA">PA</option>
                                                  <option value="RI">RI</option>
                                                  <option value="SC">SC</option>
                                                  <option value="SD">SD</option>
                                                  <option value="TN">TN</option>
                                                  <option value="TX">TX</option>
                                                  <option value="UT">UT</option>
                                                  <option value="VT">VT</option>
                                                  <option value="VA">VA</option>
                                                  <option value="WA">WA</option>
                                                  <option value="WV">WV</option>
                                                  <option value="WI">WI</option>
                                                  <option value="WY">WY</option>-->
                                             </select>   <label id="stateError" class="error"></label><label></label>
                                           </div>

                                           <div class="controls">
                                              <input type="text" class="input-medium" placeholder="zipCode" name="zipCodeModal" id="zipCodeModal">
                                               <label class="error"></label><label></label>
                                           </div>

<!--                                               <div class="controls">
                                              <input type="text" class="input-medium" placeholder="country" name="countryModal" id="countryModal">
                                           </div>-->

                               </fieldset>
                                <fieldset>
                                     <legend>Notes Info</legend>

                                            <div class="controls">
                                                <textarea name="notesModal" id="notesModal" rows="3"></textarea>
    <!--                                           <input type="text" class="input-medium" placeholder="notes" name="notes" id="notes">-->
                                            </div>

                                            <div class="control-group">
                                                <div class="controls">
                                                     <label class="checkbox">
                                                       <input type="checkbox" name="inActiveModal" value="1"> Inactive
                                                     </label>   

                                                </div>
                                            </div>
                                </fieldset>
                                <input type="hidden"    name="modalAddressID"      id="modalAddressID" />
                                <input type="hidden"    name="modalCustomerID"     id="modalCustomerID" />
                                <input  type="hidden"   name="modalTypeMain"       id="modalTypeMain"  />
                                <input  type="hidden"   name="modalTypeSub"        id="modalTypeSub" />
                            </form>
                        </div>
                   </section>
                </div>
                <div class="modal-footer">
                       <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <!--                           <button class="btn" type="submit" id="cancelModalAddress" aria-hidden="true">Close</button>-->
    <!--                          <a id="cancelModalAddress" class="btn btn-primary " >Cancel</a>-->
                      &nbsp;
                      <button type="submit" id="validateModalAddress" class="btn btn-primary btnlarge" >
                                Save
                       </button>

    <!--                          <a id="validateModalAddress" class="btn btn-primary" >Save</a>-->

                </div>
        </div>
        <span id="dataTable-wrapper">
            <div class="container" style="margin-top: 10px">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="mytable" >
                    <thead>
                        <tr>
                            <th>CompanyName</th>
                            <th>ContactName</th>
                            <th>Address1</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Zip Code</th>
                            <th>Phone</th>
                            <th>Address2</th>
                            <th>Country</th>
                            <th>Email</th>
                            <th>Fax</th>
                            <th>Mobile</th>
                            <th>OtherID</th>
                            <th>AddressID</th>
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
                            <td></td>
                            <td></td>
                            <td class="editBtn"></td>
                        </tr>
                    </tbody>
                </table><br>
                <h4 id="reminderRecepientHeading"></h4>
                <p id="reminderRecepientInfo"></p>
<!--                <div id="myModalEditBtn" class="modal hide fade">
                    <div class="modal-header">
                        <h3 id="modalCustomHeadingDataTable">Update Action</h3>
                    </div>
                    <div class="modal-body">
                         <section class="row-fluid">
                            <div class="span9" id="updateActionDataTable">
                                <form id="updateFrmModalAddress" name="updateFrmModalAddress">
                                    <fieldset>
                                        <legend> Ship To Contact Info</legend>
                                            <div class="controls">
                                                <input type="text" class="input-xlarge" placeholder="Company Name"  name="companyNameModal" id="companyNameModal">
                                                <label class="error"></label><label></label>
                                               
                                            </div>
                                            <div class="controls">
                                                <input type="text" class="input-xlarge"  placeholder="Contact Name" name="contactNameModal" id="contactNameModal">
                                                <label class="error"></label><label></label>
                                                <span id="contactNameModalError" style="color: red; font-size:67.5%">*</span>
                                            </div>
                                             <div class="controls">
                                                <input type="text" class="input-large"  placeholder="Title" name="titleModal" id="titleModal">
                                            </div>
                                             <div class="controls">
                                                <input type="text" class="input-large" name="emailModal" placeholder="email" id="emailModal">
                                                 <span id="emailModalError"  style="color: red; font-size:67.5%">*</span>
                                             </div>

                                    </fieldset>
                                    <fieldset>
                                         <legend>Phone Info</legend>

                                            <div class="controls">
                                               <input type="text" class="input-large" name="phoneModal" placeholder="phone" id="phoneModal">
                                                <label class="error"></label><label></label>
                                            </div>

                                            <div class="controls">
                                               <input type="text" class="input-large" placeholder="fax" name="faxModal" id="faxModal">
                                            </div>


                                            <div class="controls">
                                               <input type="text" class="input-large" name="mobileModal" placeholder="mobile" id="mobileModal">
                                            </div>



                                    </fieldset>
                                    <fieldset>
                                           <legend>Address Info</legend>
                                               <div class="controls">
                                                   <select name="countryModal" id="countryModal" style="width:12em;">
                                        
                                                   </select>
                                                   <label></label>
                                               </div>
                                               

                                               <div class="controls">
                                                  <input type="text" class="input-xlarge" placeholder="Address1" name="Address1Modal" id="Address1Modal">
                                                   <label class="error"></label><label></label>
                                               </div>


                                               <div class="controls">
                                                  <input type="text" class="input-xlarge" placeholder="Address2" name="Address2Modal" id="Address2Modal">

                                               </div>


                                               <div class="controls">
                                                  <input type="text" class="input-medium" placeholder="city" name="cityModal" id="cityModal">
                                                  <label class="error"></label><label></label>
                                                    <span id="cityModalError" style="color: red; font-size:67.5%">*</span>
                                               </div>

                                               <div class="controls">
                                                  <select name="stateModal" id="stateModal" style="width:12em;">
                                                      <option value="">State</option>
                                                      <option value="AL">AL</option>
                                                      <option value="AK">AK</option>
                                                      <option value="AZ">AZ</option>
                                                      <option value="AR">AR</option>
                                                      <option value="CA">CA</option>
                                                      <option value="CO">CO</option>
                                                      <option value="CT">CT</option>
                                                      <option value="DE">DE</option>
                                                      <option value="DC">DC</option>
                                                      <option value="FL">FL</option>
                                                      <option value="GA">GA</option>
                                                      <option value="HI">HI</option>
                                                      <option value="ID">ID</option>
                                                      <option value="IL">IL</option>
                                                      <option value="IN">IN</option>
                                                      <option value="IA">IA</option>
                                                      <option value="KS">KS</option>
                                                      <option value="KY">KY</option>
                                                      <option value="LA">LA</option>
                                                      <option value="ME">ME</option>
                                                      <option value="MD">MD</option>
                                                      <option value="MA">MA</option>
                                                      <option value="MI">MI</option>
                                                      <option value="MN">MN</option>
                                                      <option value="MS">MS</option>
                                                      <option value="MO">MO</option>
                                                      <option value="MT">MT</option>
                                                      <option value="NE">NE</option>
                                                      <option value="NV">NV</option>
                                                      <option value="NH">NH</option>
                                                      <option value="NJ">NJ</option>
                                                      <option value="NM">NM</option>
                                                      <option value="NY">NY</option>
                                                      <option value="NC">NC</option>
                                                      <option value="ND">ND</option>
                                                      <option value="OH">OH</option>
                                                      <option value="OK">OK</option>
                                                      <option value="OR">OR</option>
                                                      <option value="PA">PA</option>
                                                      <option value="RI">RI</option>
                                                      <option value="SC">SC</option>
                                                      <option value="SD">SD</option>
                                                      <option value="TN">TN</option>
                                                      <option value="TX">TX</option>
                                                      <option value="UT">UT</option>
                                                      <option value="VT">VT</option>
                                                      <option value="VA">VA</option>
                                                      <option value="WA">WA</option>
                                                      <option value="WV">WV</option>
                                                      <option value="WI">WI</option>
                                                      <option value="WY">WY</option>
                                                 </select>   <label id="stateError" class="error"></label><label></label>
                                               </div>

                                               <div class="controls">
                                                  <input type="text" class="input-medium" placeholder="zipCode" name="zipCodeModal" id="zipCodeModal">
                                                   <label class="error"></label><label></label>
                                               </div>

                                               <div class="controls">
                                                  <input type="text" class="input-medium" placeholder="country" name="countryModal" id="countryModal">
                                               </div>

                                   </fieldset>
                                    <fieldset>
                                         <legend>Notes Info</legend>

                                                <div class="controls">
                                                    <textarea name="notesModal" id="notesModal" rows="3"></textarea>
                                                   <input type="text" class="input-medium" placeholder="notes" name="notes" id="notes">
                                                </div>

                                                <div class="control-group">
                                                    <div class="controls">
                                                         <label class="checkbox">
                                                           <input type="checkbox" name="inActiveModal" value="1"> Inactive
                                                         </label>   

                                                    </div>
                                                </div>
                                    </fieldset>
                                    <input type="hidden"    name="modalAddressID"      id="modalAddressID" />
                                    <input type="hidden"    name="modalCustomerID"     id="modalCustomerID" />
                                    <input  type="hidden"   name="modalTypeMain"       id="modalTypeMain"  />
                                    <input  type="hidden"   name="modalTypeSub"        id="modalTypeSub" />
                                </form>
                            </div>
                       </section>
                    </div>
                    <div class="modal-footer">
                           <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                           <button class="btn" type="submit" id="cancelModalAddress" aria-hidden="true">Close</button>
                          <a id="cancelModalAddress" class="btn btn-primary " >Cancel</a>
                          &nbsp;
                          <button type="submit" id="validateModalAddress" class="btn btn-primary btnlarge" >
                                    Save
                           </button>
                          
                          <a id="validateModalAddress" class="btn btn-primary" >Save</a>
                          
                    </div>
                </div>-->
            </div>
        </span>
        
        <div id="shipBlind">
                <div class="container" style="margin-top: 10px">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="mytable_blind" >
                        <thead>
                            <tr>

                                <th>CompanyName</th>
                                <th>ContactName</th>
                                <th>Address1</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Zip Code</th>
                                <th>Phone</th>
                                <th>Address2</th>
                                <th>Country</th>
                                <th>Email</th>
                                <th>Fax</th>
                                <th>Mobile</th>
                                <th>OtherID</th>
                                <th>AddressID</th>
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
                                <td></td>
                                <td></td>


                            </tr>



                        </tbody>
                    </table>
<!--                    <div id="blindMyModalEditBtn" class="modal hide fade">
                    <div class="modal-header">
                        <h3 id="blindModalCustomHeadingDataTable">Update Action</h3>
                    </div>
                    <div class="modal-body">
                         <section class="row-fluid">
                            <div class="span9" id="blindUpdateActionDataTable">
                                <form id="blindUpdateFrmModalAddress" name="blindUpdateFrmModalAddress">
                                    <fieldset>
                                        <legend> Ship Blind From Contact Info</legend>
                                            <div class="controls">
                                                <input type="text" class="input-xlarge" placeholder="Company Name"  name="blindCompanyNameModal" id="blindCompanyNameModal">
                                                <span id="blindCompanyNameModalError" style="color: red; font-size:67.5%">*</span>
                                            </div>
                                            <div class="controls">
                                                <input type="text" class="input-xlarge"  placeholder="Contact Name" name="blindContactNameModal" id="blindContactNameModal">
                                                <span id="blindContactNameModalError" style="color: red; font-size:67.5%">*</span>
                                            </div>
                                             <div class="controls">
                                                <input type="text" class="input-large"  placeholder="Title" name="blindTitleModal" id="blindTitleModal">
                                            </div>
                                             <div class="controls">
                                                <input type="text" class="input-large" name="blindEmailModal" placeholder="email" id="blindEmailModal">
                                                 <span id="blindEmailModalError"  style="color: red; font-size:67.5%">*</span>
                                             </div>

                                    </fieldset>
                                    <fieldset>
                                         <legend>Phone Info</legend>

                                            <div class="controls">
                                               <input type="text" class="input-large" name="blindPhoneModal" placeholder="phone" id="blindPhoneModal">
                                                <span id="blindPhoneModalError" style="color: red; font-size:67.5%">*</span>
                                            </div>

                                            <div class="controls">
                                               <input type="text" class="input-large" placeholder="fax" name="blindFaxModal" id="blindFaxModal">
                                            </div>


                                            <div class="controls">
                                               <input type="text" class="input-large" name="blindMobileModal" placeholder="mobile" id="blindMobileModal">
                                            </div>



                                    </fieldset>
                                    <fieldset>
                                           <legend>Address Info</legend>

                                               <div class="controls">
                                                  <input type="text" class="input-xlarge" placeholder="Address1" name="blindAddress1Modal" id="blindAddress1Modal">
                                                   <span id="blindAddress1ModalError"  style="color: red; font-size:67.5%">*</span>
                                               </div>


                                               <div class="controls">
                                                  <input type="text" class="input-xlarge" placeholder="Address2" name="blindAddress2Modal" id="blindAddress2Modal">

                                               </div>


                                               <div class="controls">
                                                  <input type="text" class="input-medium" placeholder="city" name="blindCityModal" id="blindCityModal">
                                                    <span id="blindCityModalError" style="color: red; font-size:67.5%">*</span>
                                               </div>

                                               <div class="controls">
                                                  <select name="blindStateModal" id="blindStateModal" style="width:12em;">
                                                      <option value="">State</option>
                                                      <option value="AL">AL</option>
                                                      <option value="AK">AK</option>
                                                      <option value="AZ">AZ</option>
                                                      <option value="AR">AR</option>
                                                      <option value="CA">CA</option>
                                                      <option value="CO">CO</option>
                                                      <option value="CT">CT</option>
                                                      <option value="DE">DE</option>
                                                      <option value="DC">DC</option>
                                                      <option value="FL">FL</option>
                                                      <option value="GA">GA</option>
                                                      <option value="HI">HI</option>
                                                      <option value="ID">ID</option>
                                                      <option value="IL">IL</option>
                                                      <option value="IN">IN</option>
                                                      <option value="IA">IA</option>
                                                      <option value="KS">KS</option>
                                                      <option value="KY">KY</option>
                                                      <option value="LA">LA</option>
                                                      <option value="ME">ME</option>
                                                      <option value="MD">MD</option>
                                                      <option value="MA">MA</option>
                                                      <option value="MI">MI</option>
                                                      <option value="MN">MN</option>
                                                      <option value="MS">MS</option>
                                                      <option value="MO">MO</option>
                                                      <option value="MT">MT</option>
                                                      <option value="NE">NE</option>
                                                      <option value="NV">NV</option>
                                                      <option value="NH">NH</option>
                                                      <option value="NJ">NJ</option>
                                                      <option value="NM">NM</option>
                                                      <option value="NY">NY</option>
                                                      <option value="NC">NC</option>
                                                      <option value="ND">ND</option>
                                                      <option value="OH">OH</option>
                                                      <option value="OK">OK</option>
                                                      <option value="OR">OR</option>
                                                      <option value="PA">PA</option>
                                                      <option value="RI">RI</option>
                                                      <option value="SC">SC</option>
                                                      <option value="SD">SD</option>
                                                      <option value="TN">TN</option>
                                                      <option value="TX">TX</option>
                                                      <option value="UT">UT</option>
                                                      <option value="VT">VT</option>
                                                      <option value="VA">VA</option>
                                                      <option value="WA">WA</option>
                                                      <option value="WV">WV</option>
                                                      <option value="WI">WI</option>
                                                      <option value="WY">WY</option>
                                                 </select>   <span id="blindStateModalError" style="color: red; font-size:67.5%">*</span>
                                               </div>

                                               <div class="controls">
                                                  <input type="text" class="input-medium" placeholder="zipCode" name="blindZipCodeModal" id="blindZipCodeModal">
                                                   <span id="blindZipCodeModalError" style="color: red; font-size:67.5%">*</span>
                                               </div>

                                               <div class="controls">
                                                  <input type="text" class="input-medium" placeholder="country" name="blindCountryModal" id="blindCountryModal">
                                               </div>

                                   </fieldset>
                                    <fieldset>
                                         <legend>Notes Info</legend>

                                                <div class="controls">
                                                    <textarea name="blindNotesModal" id="blindNotesModal" rows="3"></textarea>
                                                   <input type="text" class="input-medium" placeholder="notes" name="notes" id="notes">
                                                </div>

                                                <div class="control-group">
                                                    <div class="controls">
                                                         <label class="checkbox">
                                                           <input type="checkbox" name="blindInActiveModal" id="blindInActiveModal" value="1"> Inactive
                                                         </label>   

                                                    </div>
                                                </div>
                                    </fieldset>
                                    <input type="hidden"    name="blindModalAddressID"      id="blindModalAddressID" />
                                    <input type="hidden"    name="blindModalCustomerID"     id="blindModalCustomerID" />
                                    <input  type="hidden"   name="blindModalTypeMain"       id="blindModalTypeMain"  />
                                    <input  type="hidden"   name="blindModalTypeSub"        id="blindModalTypeSub" />
                                </form>
                            </div>
                       </section>
                    </div>
                    <div class="modal-footer">
                          <a id="blindCancelModalAddress" class="btn btn-primary " >Cancel</a>
                          &nbsp;
                          <a id="blindValidateModalAddress" class="btn btn-primary" >Save</a>
                          
                    </div>
                </div>-->
      
                </div>
        </div>
        
            
        <div id="shipperformInfo">
           <br/>
           <form name="updateOrderShipTableMainFrm" id="updateOrderShipTableMainFrm">
<!--           <form method="POST" name="updateOrderShipTableMainFrm" id="updateOrderShipTableMainFrm" action="ship/homecontroller/completeUpdateAction">-->
               <section class="row-fluid">
                    <div class="well well-small">
                        <div class="span4 offset1">
                            <strong>Send Via</strong>
         <!--                   <button type="SendVia" id="SendVia" class="btn btn-mini" >
                                 <strong>Send Via</strong>
                             </button>-->
                        </div>
                        <div class="span3 offset1">
                             <button type="recipientTo" id="recipientTo" class="btn btn-primary btn-mini" >
                                 <strong>Recipient</strong>
                             </button>
                             <button type="closeRecipient" id="closeRecipient" class="btn btn-primary btn-mini" >
                                 <strong>TBD</strong>
                             </button>
                        </div>
                        <div class="span3">
                             <button type="blindFrom" id="blindFrom" class="btn btn-primary btn-mini" >
                                 <strong>Blind -Third Party</strong>
                             </button>
                             <button type="closeBlindFrom" id="closeBlindFrom" class="btn btn-primary btn-mini" >
                                 <strong>&times;</strong>
                             </button>
                        </div>

                    </div>
               </section>
               <section class="row-fluid">
                   <div class="span12">
                       <div class="row-fluid">
                           <div class="span6">
                                 <section class="row-fluid">
                                    <div class="span8">
                                        <div class="span4 offset1">
                                            <span class="help-inline">Shipper:</span>   
                                        </div>


                                        <div class="span3">
                                            <select name="shipperInfo" id="shipperInfo" class="shipperInfo">
                                            <option value="">--Select Shipper--</option> 
                                            </select>
                                        </div>

                                    </div>
                                 </section>

                                <section class="row-fluid">
                                    <div class="span8">
                                        <div class="span4 offset1">
                                            <p>Shipper Service:</p>  
                                        </div>
                                        <div class="span3">
                                            <div id="ShipperServ" >	
                                                <select name="ShipperServiceID" id="ShipperServiceID" class="shipperService">
                                                <option value="">--Please Select--</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </section>  

                                <section class="row-fluid"> 
                                    <div class="span8">
                                        <div class="span4 offset1">
                                            <p>Bill To:</p>   
                                        </div>
                                        <div class="span3">
                                            <div id="billToSelect" >	
                                                <select name="billTo" id="billTo" class="billTo">
                                                    <option selected=""></option>
                                                    <option value="Recipient">Recipient</option>
                                                    <option value="Third Party">Third Party</option>
                                                </select>
                                                <label></label>

                                            </div> 
                                        </div>
                                    </div>
                                </section>

                                <section class="row-fluid">
                                    <div class="span8">
                                        <div class="span4 offset1">
                                            <p>Bill To Account#:</p>  
                                        </div>

                                        <div class="span3">
                                            <div id="billToAccountNumber" >	
                                            <input type="text" name="billAcountNumber" id="billAcountNumber">
                                            </div>
                                        </div>  
                                    </div> 
                                </section>

                                <section class="row-fluid">
                                    <div class="span8">
                                        <div class="span4 offset1">
                                            <p>Ship With OrderID:</p>  
                                        </div>
                                        <div class="span3">
                                            <div id="sWithOrderID">	
                                                <input type="text" name="shipWithOrderID" id="shipWithOrderID">

                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <section class="row-fluid">
                                    <div class="span8">
                                        <div class="span4 offset1">
                                            <p>Hide Shipping on Work Order</p>
                                        </div>
                                        <div class="span3">
                                            <input type="checkbox" name="hideOnWorkOrder" value="1" id="hideOnWorkOrder">
                                        </div>
                                    </div>
                                </section>
                                <section class="row-fluid">
                                    <div class="span8">
                                        <div class="span4 offset1">
                                            <p>Notes</p>
                                        </div>
                                        <div class="span3">
                                            <textarea name="notes" id="notes" rows="3"></textarea>
                                        </div>
                                    </div>
                                </section>
                           </div>
                           <div class="span3">
                               <section class="row-fluid">
                                   <div id="recipientAddress">
                                       <address id ="recipientAddressInfo">

                                       </address>
                                      <input type="hidden"   name="receipeintContactNameHidden"          id="receipeintContactNameHidden" >
                                      <label></label>
                                      <input type="hidden"   name="receipeintAddressNameHidden"          id="receipeintAddressNameHidden" >
                                      <label></label>
                                      <input type="hidden"   name="receipeintCityNameHidden"             id="receipeintCityNameHidden" >
                                      <label></label>
                                      <input type="hidden"   name="receipeintStateNameHidden"            id="receipeintStateNameHidden" >
                                      <label></label>
                                      <input type="hidden"   name="receipeintZipCodeNameHidden"          id="receipeintZipCodeNameHidden" >
                                      <label></label>

                                   </div>
                               </section>
                           </div>
                            <div class="span3">
                               <section class="row-fluid">
                                   <div id="blindAddress">
                                       <address id="blindAddressInfo">

                                       </address>

                                   </div>
                               </section>
                           </div>
                       </div>
                   </div>
               </section>
               <section class="row-fluid">
                    <div class="span12">
                        <div class="row-fluid">
                            <div class="span2 offset10">
                                <button type="submit" id="updateBtnOrderShipTableMainFrm" class="btn btn-primary btnlarge" >
                                    Submit
                                </button>
                            </div> 
                        </div>
              </section>
       
        <input type="hidden"   name="typeOfSubmitHidden"                   id="typeOfSubmitHidden" >
      <!----------------------------------- OrderID,OrderShipID,ShippingTBD Hidden Fields ---------------------------------->
        <input type="hidden"   name="orderIDHidden"                        id="orderIDHidden" >    
        <input type="hidden"   name="orderShipIDHidden"                    id="orderShipIDHidden" >
        <input type="hidden"   name="nb_ShippingTBD"                       id="nb_ShippingTBD" >
        <!----------------------------------- Receipeint Hidden Fields ---------------------------------->
        <input type="hidden"   name="receipeintCustomerShipToIDHidden"     id="receipeintCustomerShipToIDHidden" > 
        <input type="hidden"   name="receipeintCustomerIDHidden"           id="receipeintCustomerIDHidden" >   
             
        <input type="hidden"   name="receipeintCompanyNameHidden"          id="receipeintCompanyNameHidden" >
        
        
       
       
       
        <input type="hidden"   name="receipeintAddressNameHidden2"         id="receipeintAddressNameHidden2" >
       
        <input type="hidden"   name="receipeintCountryNameHidden"          id="receipeintCountryNameHidden" >
        
        <input type="hidden"   name="receipeintEmailNameHidden"            id="receipeintEmailNameHidden" >
        <input type="hidden"   name="receipeintFaxNameHidden"              id="receipeintFaxNameHidden" >
        <input type="hidden"   name="receipeintMobileNameHidden"           id="receipeintMobileNameHidden" >
        <input type="hidden"   name="receipeintPhoneNameHidden"            id="receipeintPhoneNameHidden" >
       
       
       
       
        <!----------------------------------- Blind Hidden Fields ---------------------------------->
        <input type="hidden"   name="blindCustomerShipToIDHidden"          id="blindCustomerShipToIDHidden" > 
       
        <input type="hidden"   name="blindCompanyNameHidden"               id="blindCompanyNameHidden" >
        <input type="hidden"   name="blindContactNameHidden"               id="blindContactNameHidden" >
        <input type="hidden"   name="blindAddressNameHidden"               id="blindAddressNameHidden" >
        <input type="hidden"   name="blindCityNameHidden"                  id="blindCityNameHidden" >
        <input type="hidden"   name="blindStateNameHidden"                 id="blindStateNameHidden" >
       
       
       
       
        <input type="hidden"   name="blindAddressNameHidden2"              id="blindAddressNameHidden2" >
        <input type="hidden"   name="blindCountryNameHidden"               id="blindCountryNameHidden" >
        <input type="hidden"   name="blindZipCodeNameHidden"               id="blindZipCodeNameHidden" >
        <input type="hidden"   name="blindEmailNameHidden"                 id="blindEmailNameHidden" >
        <input type="hidden"   name="blindFaxNameHidden"                   id="blindFaxNameHidden" >
        <input type="hidden"   name="blindMobileNameHidden"                id="blindMobileNameHidden" >
        <input type="hidden"   name="blindPhoneNameHidden"                 id="blindPhoneNameHidden" >
        
        </form>
     </div>    
        
    </body>
</html>

