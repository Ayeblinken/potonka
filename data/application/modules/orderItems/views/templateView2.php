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
           var jobNumber            = "<?php echo $orderID;?>";
            
           var templateCustomerID   = "<?php echo $customerID;?>";
           //alert(orderID);
        </script>
        
        <!-- Bootstrap core CSS -->
        <link href="bootstrap3/dist/css/bootstrap.css" rel="stylesheet"> 
        <link href="bootstrap3/dist/css/bootstrap-theme.min.css" rel="stylesheet">

        <!-- Data Table Bootstrap CSS  -->
<!--        <link rel="stylesheet" href="extras/TableTools/media/css/TableTools.css">-->
        <link rel="stylesheet" href="extras/TableTools/media/css/TableTools.css">
         
<!--        <link rel="stylesheet" href="media/dtbs3/dataTables.bootstrap.css">-->
        <link rel="stylesheet"  href="media/dtbs3/dataTables.bootstrap.css">
       

        <link rel="stylesheet" href="<?php echo base_url(); ?>css/datepicker.css">

        <link rel="stylesheet" href="<?php echo base_url(); ?>media/table.css">

       

<!--        <link rel="stylesheet" href="<?php //echo base_url(); ?>media/table.css">-->

       
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
            body { padding-top: 60px; }
            #checkrow { top: 30px; }


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
        </style>
         
         
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>media/js/orderOverview_jquery.dataTables.js"></script>
        <script src="<?php echo base_url(); ?>extras/TableTools/media/js/TableTools.js"></script>
        <script src="<?php echo base_url(); ?>extras/TableTools/media/js/ZeroClipboard.js"></script>
        <script src="<?php echo base_url(); ?>bootstrap3/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap-datepicker.js"></script>
        <script src="<?php echo base_url(); ?>media/dtbs3/dataTables.bootstrap.js"></script>
        
         
         <script src="js/orderItemTemplateModule.js"></script>
         <style>
            .row {
                    margin-top: 20px;
                    margin-bottom: 20px;

                 }
         </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="col-md-3 col-md-offset-9 col-sm-3 col-sm-offset-9">
                        <button type="submit" onclick="window.open(<?php echo "'orderItemUpSideFrm/create/".$orderID.'\'' ?>)"class="btn btn-default">Add Custom Order Line</button>
                    </div>
                    
                    
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div id="content">
                        <ul class="nav nav-tabs nav-justified" id="customTemplateTabs">
                             <li class="active"><a href="#customerTemplate" data-toggle="tab">Customer Templates</a></li>
                             <li><a href="#referenceGuideTemplate" data-toggle="tab">Reference Guide Templates</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="customerTemplate">
                                <div id="customerTemplateDiv" style="margin-top: 15px">
                                    <table class="table table-striped table-bordered" id="customerTemplateDataTable">
                                        <thead>
                                            <tr>
                                                <th>Template Name</th>
                                                <th>Product Cat/ Name</th>
                                                <th>Qty</th>
                                                <th>Size H" X W"</th>
                                                <th>Price Type</th>
                                                <th>Price</th>
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
                                    </table>
                                    
                                </div>
                                
                            </div>
                            <div class="tab-pane active" id="referenceGuideTemplate">
                                
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
       
    </body>
</html>
