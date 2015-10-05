<!DOCTYPE html>
<html ang="en">
     <head>
        <meta charset=UTF-8">
        <title>Duplicate Line Item</title>
        <base href="<?php echo base_url(); ?>" />
        
        <script type="text/javascript">
             var orderItemID = <?php echo $orderItemID; ?>
             //alert("hi");
            
        </script>
        <script type="text/javascript">
             var typeOfRequestCalled = <?php echo "\"".$typeOfRequest."\""; ?>
            
             //alert("request Called "+typeOfRequestCalled);
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
        
        <script src="js/orderItemModule.js"></script>
        
        <style type="text/css">
             body {
/*                    width: 800px;*/
                    padding-top: 40px;
                    padding-bottom: 40px;
                    background-color: #f5f5f5;
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
                    <div id="mainDuplicate" class="span12">
                        <form class="form-horizontal" name="orderItemDuplicateForm" id="orderItemDuplicateForm">
                            <fieldset>
                                <legend>Duplicate Line Item : </legend>
                                    <div class="control-group">
                                        <label class="control-label" for="orderItemDescription">Description: </label>
                                        <div class="controls">
                                            <input type="text" class="input-xlarge" placeholder="Description"  name="orderItemDescription" id="orderItemDescription">
                                        </div>
                                    </div> 

                                    <div id="orderItemStructureID" class="control-group">
                                        <label class="control-label" for="orderItemStructure">ID: </label>
                                        <div class="controls">
                                            <input type="text" class="input-large" placeholder="ID"  name="orderItemStructure" id="orderItemStructure">
                                        </div>
                                    </div>
                                    <div id="customerSpecific" class="hide">
                                        <div class="control-group">
                                            <label class="control-label" for="orderItemSportJobNumber">Sport Job Number: </label>
                                            <div class="controls">
                                                <input type="text" class="input-medium" placeholder="Sport Job Number"  name="orderItemSportJobNumber" id="orderItemSportJobNumber">
                                            </div>
                                        </div> 
                                        <div class="control-group">
                                            <label class="control-label" for="orderItemSportItemNumber">Sport Item Number: </label>
                                            <div class="controls">
                                                <input type="text" class="input-medium" placeholder="Sport Item Number"  name="orderItemSportItemNumber" id="orderItemSportItemNumber">
                                            </div>
                                        </div> 
                                        <div class="control-group">
                                            <label class="control-label" for="orderItemSportLocationNumber">Sport Location Number: </label>
                                            <div class="controls">
                                                <input type="text" class="input-medium" placeholder="Sport Location Number"  name="orderItemSportLocationNumber" id="orderItemSportLocationNumber">
                                            </div>
                                        </div> 
                                    
                                    </div>
                                    
                                    <div class="control-group">
                                        <label class="control-label" for="orderItemQuantity">Quantity: </label>
                                        <div class="controls">
                                            <input type="text" class="input-medium" placeholder="Quantity"  name="orderItemQuantity" id="orderItemQuantity">
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="orderItemHeight">Height: </label>
                                        <div class="controls">
                                            <input type="text" class="input-medium" placeholder="Height"  name="orderItemHeight" id="orderItemHeight">
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="orderItemWidth">Width: </label>
                                        <div class="controls">
                                            <input type="text" class="input-medium" placeholder="Width"  name="orderItemWidth" id="orderItemWidth">
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="orderItemStructure">Price: </label>
                                        <div class="controls">
                                            <input type="text" class="input-medium" placeholder="Price"  name="orderItemPrice" id="orderItemPrice">
                                        </div>
                                    </div> 
                                
                            </fieldset>
<!--                            <fieldset id="customerSpecific" class="hide">
                                 <legend>Sport Graphic Specific Info:- </legend>
                                  <div class="control-group">
                                        <label class="control-label" for="orderItemSportJobNumber">Sport Job Number: </label>
                                        <div class="controls">
                                            <input type="text" class="input-medium" placeholder="Sport Job Number"  name="orderItemSportJobNumber" id="orderItemSportJobNumber">
                                        </div>
                                  </div> 
                                  <div class="control-group">
                                        <label class="control-label" for="orderItemSportItemNumber">Sport Item Number: </label>
                                        <div class="controls">
                                            <input type="text" class="input-medium" placeholder="Sport Item Number"  name="orderItemSportItemNumber" id="orderItemSportItemNumber">
                                        </div>
                                  </div> 
                                  <div class="control-group">
                                        <label class="control-label" for="orderItemSportLocationNumber">Sport Location Number: </label>
                                        <div class="controls">
                                            <input type="text" class="input-medium" placeholder="Sport Location Number"  name="orderItemSportLocationNumber" id="orderItemSportLocationNumber">
                                        </div>
                                  </div> 
                            </fieldset>-->
                            <br/>
                            <input type="hidden" name="orderItemID"                   id="orderItemID" >
                            <input type="hidden" name="orderItemTotalDashNum"         id="orderItemTotalDashNum"  >
                            <input type="hidden" name="orderItemCustomerID"           id="orderItemCustomerID"  >
                            <input type="hidden" name="typeOfRequestCalledHidden"     id="typeOfRequestCalledHidden"  >
                            
                            <div class="control-group">
                                <div class="controls">
                                     <button type="submit" id="submitOrderItemDuplicateForm" class="btn  btn-primary">Duplicate</button>
                                </div>
                                
                            </div>
                            
                            
                            
                           
<!--                            <div class="control-group">
                                 
                                 <div class="controls">
                                   
                                 </div>
                                 
                            </div>-->
                            
                            
                        </form>
                        
                        
                    </div>
                    
                </section>
               
                <section  class="row-fluid">
                    <div id="sucess"  class=" span6 hide hero-unit">
                       
                        
                        <div  id="ajaxLoadAni">
                            <img src="images/ajaxLoad/ajax-loader.gif" alt="Ajax Loading Animation" />
                                <span>Duplicating...</span>
                        </div>
                        <div id="message">
                            <p><strong>Completed</strong></p>
                        </div> 
<!--                        
                        <div class="progress progress-striped active">
                            <div class="bar" style="width: 0%;"></div>
                        </div>-->
                        
                        
                    </div>
                     
                
                </section>
            
        </div>
  
    </body>
    

    

</html>

