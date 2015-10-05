<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Template View</title>
         <base href="<?php echo base_url(); ?>" />
         
         <script type="text/javascript">
            var templateOrderID      = "<?php echo $orderID;?>";
            
            //var templateCustomerID   = "<?php //echo $customerID;?>";
           //alert(orderID);
         </script>
         
         <!-- Bootstrap core CSS -->
         <link href="bootstrap3/dist/css/bootstrap.css" rel="stylesheet">
     
         <!-- Bootstrap theme -->
         <link href="bootstrap3/dist/css/bootstrap-theme.min.css" rel="stylesheet">
         
         <!-- Data Table Table tools CSS  -->
         <link rel="stylesheet" href="extras/TableTools/media/css/TableTools.css">
         
         <!-- Datables Bootstrap CSS -->
         <link rel="stylesheet" href="media/dtbs3/dataTables.bootstrap.css">
         
         
         <!-- Jquery core JavaScript
         ================================================== -->
<!--         <script type="text/javascript" charset="utf-8" src="media/js/jquery.js"></script>-->
         <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
         
         <!-- DataTables core JavaScript
         ================================================== -->
        <script src="media/js/orderOverview_jquery.dataTables.js"></script>
        
        <script src="extras/TableTools/media/js/TableTools.js"></script>
        <script src="extras/TableTools/media/js/ZeroClipboard.js"></script>
        
        <!--Bootstrap JavaScript
         ================================================== --> 
        <script src="bootstrap3/dist/js/bootstrap.min.js"></script>
         
         <!-- DataTables Bootstrap JavaScript
         ================================================== --> 
        <script src="media/dtbs3/dataTables.bootstrap.js"></script>
         
         <script src="js/jquery.validate.min.js"></script>
         <script src="js/additional-methods.min.js"></script>
         
         
         
         <script src="js/clearForm.js"></script>
         
         <script src="js/orderItemTemplateModule.js"></script>
        
         <style type="text/css">
              #resetSearch {
                text-indent: -1000em;
                width: 16px;
                height: 16px;
                display: inline-block;
                /*    background-image: url(http://p.yusukekamiyamane.com/icons/search/fugue/icons/cross.png);*/
                background-repeat: no-repeat;
                position: relative;
                left: -20px; 
                top: 2px;
            }
            body { padding-top: 10px; }
          


            /*span.icon_clear{
                position:absolute;
                right:10px;    
                display:none;
                
                 now go with the styling 
                cursor:pointer;
                font: bold 1em sans-serif;
                color:#38468F;  
                }
                span.icon_clear:hover{
                    color:#f52;
                }*/
            .row {
                    margin-top: 30px;
                    margin-bottom: 10px;

                 }
         </style>
    </head>
    <body>
        
        <div class="container">
            <!-- col-md-1  col-sm-1 -->
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="col-md-2 col-sm-2">
                         <button  type="button" style="margin-bottom: 5px;" type="submit" onclick="window.open(<?php echo "'orderItemUpSideFrm/create/".$orderID.'\'' ?>)"class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> Line Item</button>&nbsp;
                    </div>
                     <div class="col-md-1 col-md-offset-9 col-sm-1 col-sm-offset-9">
                        <button id="templateRefreshBtn" name="templateRefreshBtn" type="submit" class="btn btn-default ">Refresh</button>
                    </div>
                </div>
                
            </div>
<!--            <button  type="button" style="margin-bottom: 5px;" type="submit" onclick="window.open(<?php echo "'orderItemUpSideFrm/create/".$orderID.'\'' ?>)"class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> Line Item</button>&nbsp;
            <button id="templateRefreshBtn" name="templateRefreshBtn" type="submit" class="btn btn-default col-md-1 col-md-offset-7  col-sm-1 col-sm-offset-7">Refresh</button>
            -->
<!--            <span><label></label></span>-->
            <!--            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="col-md-3 col-md-offset-9 col-sm-3 col-sm-offset-9">
                        <button type="submit" onclick="window.open(<?php //echo "'orderItemUpSideFrm/create/".$orderID.'\'' ?>)"class="btn btn-default">Add Custom Order Line</button>
                    </div>
                </div>
            </div>-->
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div id="content">
                        <ul  class="nav nav-tabs" id="customTemplateTabs">
                            <li class="active"><a href="#readOrderLineItems" data-toggle="tab">View Line Items</a></li> 
                            <li><a href="#customerTemplate" data-toggle="tab">Customer Templates</a></li>
                            <li><a href="#referenceGuideTemplate" data-toggle="tab">Reference Guide Templates</a></li>
                             
                        </ul>
                        <div class="tab-content">
                               <!--Add tab or view line items -->
                            <div class="tab-pane active" id="readOrderLineItems">
                                <div id="orderLineItemViewDiv" style="margin-top: 15px">
                                    <table class="table table-striped table-bordered" id="orderLineItemViewDataTable">
                                          <thead>
                                            <tr>
                                                <th>ID#</th>
                                                <th>Qty</th>
                                                <th>Size</th>
                                                <th>Product</th>
                                                <th>Desc.</th>
                                                <th>ID</th>
                                                <th>Art Info</th>
                                                <th>OrderDashNum</th>
                                                <th>OrderItemID</th>
                                                <th>OrderID</th>
                                                <th>OrderItemImage</th>
                                                <th>Height</th>
                                                <th>Width</th>
                                                <th>Picture</th>
                                                <th></th>

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
                                    
                                </div>
                                <div class="hide" id="noOrderLineItemViewDiv" style="margin-top: 15px">
                                    <h4>No Line Items have been added</h4>
                                </div>
                                
                            </div>
                            <div class="tab-pane" id="customerTemplate">
                                <div id="customerTemplateDiv" style="margin-top: 15px">
                                    <table  class="table table-striped table-bordered" id="customerTemplateDataTable">
                                        <thead>
                                            <tr>
                                                <th>Template Name</th>
                                                <th>Product Cat/ Name</th>
                                                <th>Qty</th>
                                                <th>Size H" X W"</th>
                                                <th>Price Type</th>
                                                <th>Price</th>
                                                <th>ProductBuilds.t_Category</th>
                                                <th>ProductBuilds.t_Name</th>
                                                <th>orderItemID</th>
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
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                            </div>
                            <div class="tab-pane" id="referenceGuideTemplate">
                                <div id="referenceGuideTemplateDiv" style="margin-top: 15px">
                                    <table class="table table-striped table-bordered" id="referenceGuideTemplateDataTable">
                                         <thead>
                                             <tr>
                                                 <th>Template Name</th>
                                                 <th>Product Cat/ Name</th>
                                                 <th>Qty</th>
                                                 <th>Size H" X W"</th>
                                                 <th>Price Type</th>
                                                 <th>Price</th>
                                                 <th>ProductBuilds.t_Category</th>
                                                 <th>ProductBuilds.t_Name</th>
                                                 <th>orderItemID</th>
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
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                        
                                    </table>
                                    
                                    
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="orderItemPresent" id="orderItemPresent" >
                </div>
            </div>
        </div>
       
    </body>
</html>
