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
        
        <script src="js/orderShipTrackingModule.js"></script>
       
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
             
      
        </style>
       
         
    </head>
    <body>
        <span id="table-wrapper">
            <div class="container" style="margin-top: 10px">
                <section class="row-fluid">
                    <div class="offset10">
                        <nav>
                            <ul id="dynamicLinks" class="nav nav-pills">
                               <li> <a id="shippingCharge" href="odrTracking/ordershiptrackingcontroller/orderShipSelect/">Add Shipping Charge</a></li>
                            </ul>
                        </nav>
                    </div>
                     
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
                            <tr id="${orderShipID}">
                                <td>${orderShipID}</td>
                                <td>${shippingCompany}</td>
                                <td>${shippingService}</td>
                                <td>${trackingID}</td>
                                <td>${shippingCharge}</td>
                            </tr>
                        </script>
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
                                    <th>kp_OrderShipID</th>
                                    <th>Shipper Company</th>
                                    <th>Shipper Service</th>
                                   
<!--                                    <th>kp_ShipperID</th>
                                    <th>kp_ShipperServiceID</th>-->
                                  

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                   
                                </tr>
                            </tbody>
                        </table>
                     </div>
                </section>
                
            </div>
        </span>
        <span id="table-wrapper-form" >
              <div class="container" style="margin-top: 10px">
                  <br><br>
                  <form class="form-inline" id="shippingChareTrackingFrm" name="shippingChareTrackingFrm"    method="POST" action="odrTracking/ordershiptrackingcontroller/orderShipTrackingSubmit" >
                      <section id="chargeTracking"  class="row-fluid">
                          <div class="span9">
                              <fieldset>
                                  <legend>
                                      Please enter an amount to charge and a Tracking number (if applicable)
                                  </legend>
                                        <div class="controls">
                                            <input type="text" class="input-medium" placeholder="Charge"  name="charge" id="charge">
                                            <label class="error">*</label>
                                         </div>

                                         <div class="controls">
                                             <input type="text" class="input-medium"  placeholder="Tracking Number" name="trackingNumber" id="trackingNumber">
                                             <label></label>
                                         </div>
                                         <br>
                                             <button type="button" id="CancelBtn" class="btn">Cancel</button>
                                             <button type="submit" id="submit" class="btn  btn-primary">Done</button>
                                          
                                         
                                             
                                        
                                         


                              </fieldset> 
                          
                          </div>
                      </section>
                      <input type="hidden" id="orderShipIDHidden" name="orderShipIDHidden">
                      <input type="hidden" id="orderIDHidden" name="orderIDHidden" value="<?php echo $orderID;  ?>">
                     
                      
                      
                      
                  </form>
              </div>
        </span>
        
          
        
        
        
       
            


    </body>
   
</html>

