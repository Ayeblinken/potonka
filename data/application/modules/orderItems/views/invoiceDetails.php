<!DOCTYPE html>
<html ang="en">
    <meta charset=UTF-8">
        <title>Invoice Details</title>
        <base href="<?php echo base_url(); ?>" />
        
        <script type="text/javascript">
             var orderID = <?php echo $orderID ?>
            
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
        
        <script src="js/orderItemModuleInvoiceDetails.js"></script>
        
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
/*                #orderItemTotalText {
                   clear:both;float:right;
                }*/

      
        </style>
    </head>
    <body>
          <span id="table-wrapper">
            <div class="container-fluid" style="margin-top: 10px">
                <section id="incompletePricingWarning" class="row-fluid hide">
                    <div class="row-fluid">
                         <div class="alert">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Incomplete Pricing-</strong>You will be unable to invoice the order.
                         </div>
                        
                    </div>
                   
                </section>
                <section class="row-fluid">
                    <div class="form-inline">
                        <div class="row-fluid">
                            <div class="span2">
                                <label class="checkbox">
                                <input id="incompletePricing" type="checkbox" disabled> Incomplete Pricing
                                </label>
                            </div>
                            <div class="span2">
                                 <label class="checkbox">
                                    <input id="useTotalOrderPricing"  type="checkbox" disabled> Total Order Price
                                 </label>
                            </div>
                            <div class="span2">
                                    <input type="text" id="totalOrderPrice" class="input-medium" placeholder="Total Order Price" readonly>
                            </div>
                        </div>
                       
                        
                    </div><br/>
                   
                </section>
                <section class="row-fluid">
                    <div class="span12">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="records" >
                            <thead>
                                <tr>
                                    <th>Do Not Invoice</th>
                                    <th>ID#</th>
                                    <th>Qty</th>
                                    <th>Product</th>
                                    <th>Description</th>
                                    <th>Pricing Type</th>
                                    <th>Price</th>
                                    <th>Total</th>
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
                                    <td></td>
                                </tr>
                                
                            </tbody>
                        </table>
                         <script type="text/template" id="readTemplate">
                            <tr id="${ID}">
                                <td> <input type="checkbox" disabled ${doNotInvoiceChecked}></td>
                                <td>${orderIDDashNum}</td>
                                <td>${Qty}</td>
                                <td>${product}</td>
                                <td>${description}</td>
                                <td>${pricingType}</td>
                                <td>${price}</td>
                                <td>${total}</td>
                            </tr>
                         
                        </script>
                        
                    </div>
                </section>
                <section class="row-fluid">
                    <div class="row-fluid">
                        <div class="span3">
                              <label class="checkbox">
                                <input id="postedToQuickbooks" type="checkbox" disabled> Posted to Quickbooks
                              </label>
                            
                        </div>
                        <div  class="offset6 span2">
                            Order Items Total:&nbsp;&nbsp;&nbsp;
<!--                            <i class="icon-hand-right"></i>-->
                        </div>
                        <div id="orderItemTotalText" class="span1">&nbsp;&nbsp;&nbsp;
                            
                        </div>
                       
                      
                    </div>
<!--                    <hr/>-->
                </section>
                <section class="row-fluid">
                    <div class="row-fluid">
<!--                           <div class="span3">
                            
                           </div>-->
                           <div  class="offset9 span2">
                            Other Charges Total:&nbsp;&nbsp;&nbsp;
<!--                            <i class="icon-hand-right"></i>-->
                           </div>
                           <div id="otherChargeTotalText" class="span1" >&nbsp;&nbsp;&nbsp;
                            
                          </div>
                    </div>
                   
<!--                     <hr/>-->
                </section>
                <section class="row-fluid">
                    <div class="row-fluid">
                          <div  class="offset9 span2">
                              Shipping Total:&nbsp;
<!--                              <i class="icon-hand-right"></i>-->
                          </div>
                          <div id="shippingTotalText" class="span1" >&nbsp;&nbsp;&nbsp;
                                   
                          </div>
                    </div>
<!--                     <hr/>-->
                </section>
               <section class="row-fluid">
                    <div class="row-fluid">
                          <div  class="offset9 span2">
                              Estimated Tax:&nbsp;
<!--                              <i class="icon-hand-right"></i>-->
                          </div>
                          <div id="EstimatedTotalText" class="span1">&nbsp;&nbsp;&nbsp;
                                   NA
                          </div>
                    </div>
<!--                     <hr/>-->
                </section>
                <section class="row-fluid">
                    <div class="row-fluid">
                         <div class="offset9 span2">
                            <strong>Grand Total:&nbsp;</strong> 
<!--                            <i class="icon-hand-right"></i>-->
                        </div>
                        <div id="grandTotalText" class="span1">&nbsp;&nbsp;&nbsp;
                           
                        </div>
                        
                    </div>
<!--                    <hr/>-->
                    
                </section>
                <input type="hidden" id="grandTotal">
                    
                
              
                
            </div>
        </span>
       
    </body>
</html>
