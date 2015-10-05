<!DOCTYPE html>
<html ang="en">
     <head>
        <meta charset=UTF-8">
        <title>Status Change</title>
        <base href="<?php echo base_url(); ?>" />
        
        <script type="text/javascript">
            
             var statusChangeRequest ="<?php echo $statusChange?>";
             
             //alert("hyi: "+statusChangeRequest);
             var changeID            = "<?php echo $changeID?>";
            
        </script>
        
        <link href="media/css_bootstrap/bootstrap.css" rel="stylesheet" type="text/css">
        
        <link href="media/css_bootstrap/bootstrap-responsive.css" rel="stylesheet" type="text/css">
        
       
        
        <script type="text/javascript" charset="utf-8" src="media/js/jquery.js"></script>
        <script src="js/jquery.maskedinput.js"></script>
        <script src="js/jquery.validate.min.js"></script>
        <script src="js/additional-methods.min.js"></script>
       
        <script type="text/javascript" charset="utf-8" src="media/js_bootstrap/bootstrap.min.js"></script>
        
        
        
<!--        <script type="text/javascript" src="js/jquery-templ.js"></script>-->
        
        <script src="js/clearForm.js"></script>
        
        <script src="js/statusLogModuleStatusChange.js"></script>
        
        <style type="text/css">
             body {
/*                    width: 800px;*/
                    padding-top: 40px;
                    padding-bottom: 40px;
/*                    background-color: #f5f5f5;*/
/*                    margin: 0 auto;
                    padding: 10px 20px;*/
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
            #ajaxLoadAni {
/*                    background: #3A3A3A;
                    color: #fff;*/

                    /* we hide it because we only need to display it when ajax call is made */
/*                    display: none;*/

                    font-weight: bold;
                    position: absolute;
/*                    position: fixed;*/
/*                    top: 0;*/
/*                    left: 40%;
                    padding: 8px;*/
                    width: 126px;
                    z-index: 9999;
/*                    z-index: 9999;*/
                }
 
                #ajaxLoadAni span {
                    float: right;
                    margin: 1px 0 0 0;
                }    
                .wraptocenter {
                    
                   
                    display: table-cell;
                    text-align: center;
                    vertical-align: middle;
                    width: 100px;
                    height: 100px;
                    background-color:#999;
                }
                .wraptocenter * {
                    vertical-align: middle;
                }

      
        </style>
    </head>
    <!-- the body section -->
    <body>
         
        <div class="container-fluid">
            
                <section class="row-fluid">
                    <div id="statusChange" class="span12">
                        <form class="form-horizontal" name="statusChangeForm" id="statusChangeForm">
                            <fieldset>
                                <div id="statuschangeIDHeading" class="well well-small"> 
                                </div>
<!--                                <legend id="statuschangeIDHeading"> </legend>-->
                                    <div class="control-group">
                                        <label class="control-label" for="currentStatus">Current Status: </label>
                                        <div class="controls">
                                            <input type="text" class="input-xlarge"   name="currentStatus" id="currentStatus" readonly>
                                        </div>
                                    </div> 
                                    <div  class="control-group">
                                        <label class="control-label" for="newStatus">New Status </label>
                                        <div class="controls">
                                            <select name="newStatus" id="newStatus" name="newStatus" style="width:12em;">
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div  class="control-group">
                                        <label class="control-label" for="userName">User Name </label>
                                        <div class="controls">
                                            <input type="text" class="typeahead" id="userName" name="userName" autocomplete="off" data-provide="typeahead">
                                        </div>
                                    </div>
                                     <div  class="control-group">
                                        <label class="control-label" for="notes">Notes </label>
                                        <div class="controls">
                                            <textarea name="notes" id="notes" rows="9"></textarea>
                                        </div>
                                    </div>
                                     <div id="applyStatusToAll" class="control-group">
                                          <label class="control-label" for="applyToAll">Apply to All</label>
                                          <div class="controls">
                                                <input type="checkbox" name="applyToAll" value="1">
                                          </div>
                                    </div>
                            </fieldset>
                             <br/>
                            <input type="hidden" name="orderItemIDHidden"             id="orderItemIDHidden" >
                            <input type="hidden" name="orderIDHidden"                 id="orderIDHidden"  >
                            <input type="hidden" name="statusChangeRequestHidden"     id="statusChangeRequestHidden"  >
                            
                            <div class="control-group">
                                <div class="controls">
                                     <button type="button"  id="cancelStatusChangeBtn" class="btn">Cancel</button>&nbsp;&nbsp;
                                     <button type="submit" id="submitStatusChangeBtn" class="btn  btn-primary">Save</button>
                                </div>
                                
                            </div>
                        </form>
                        
                        
                    </div>
                    
                </section>
                <section  class="row-fluid">
                    <div id="sucess"  class=" span6 hide hero-unit">
                            <div  id="ajaxLoadAni">
                                 <img src="images/ajaxLoad/ajax-loader.gif" alt="Ajax Loading Animation" />
                                     <span>Processing...</span>
                             </div>
                             <div id="message">
                                 
                             </div> 
                    </div>
                    <div id="statusChangeConfirmModal" class="modal hide fade">
                          <section class="row-fluid">
                                <div class="modal-header">
                                    <h3 id="modalCustomHeadingTemplateRecords">Please Confirm:</h3>
                                </div>
                                <div class="modal-body">
                                    <form id="confirmFrmModal" name="confirmFrmModal">
                                        <h5 id="customMessageWarning"></h5>
                                        <!-- <p>This action cannot be undone.</p>-->
                                         <p>Do you want to continue?</p>
                                             <input type="hidden" name="orderIDHiddenModal"   id="orderIDHiddenModal" >
                                             <input type="hidden" name="newStatusHiddenModal" id="newStatusHiddenModal"  >
                                             <input type="hidden" name="userNameHiddenModal"  id="userNameHiddenModal"  >
                                             <input type="hidden" name="notesHiddenModal"     id="notesHiddenModal"  >
                                    </form>
                                </div>
                                <div class="modal-footer">
                                      <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                                      &nbsp;
                                      <button type="submit" id="confirmStatusModalBtn" class="btn  btn-primary">OK</button>
    <!--                                  <a id="validateModalOrderShipTracking" class="btn btn-primary" >Save</a>-->
                                </div>
                        </section>
                     </div>
                </section>
        </div>
    </body>
</html>

