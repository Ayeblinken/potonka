<!DOCTYPE HTML>
<html>  
    <head>  
        <title><?php echo $result->kp_OrderID ?></title>
        <base href="<?php echo base_url(); ?>" />
        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>bootstrap3/dist/css/bootstrap.css" rel="stylesheet">  
        <link rel="stylesheet" href="<?php echo base_url(); ?>bootstrap3/dist/css/bootstrap-theme.min.css" rel="stylesheet"> 


        <!-- Data Table Bootstrap CSS  -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>extras/TableTools/media/css/TableTools.css">
    <!--    <link rel="stylesheet" href="<?php echo base_url(); ?>media/DT_bootstrap/DT_bootstrap.css">-->
        <link rel="stylesheet" href="<?php echo base_url(); ?>media/dtbs3/dataTables.bootstrap.css">

        <link rel="stylesheet" href="<?php echo base_url(); ?>css/datepicker.css">

        <link rel="stylesheet" href="<?php echo base_url(); ?>media/table.css">

        <script type="text/javascript">

            var orderID = "<?php echo $orderID; ?>";
            //alert(orderID);
        </script>
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
            body { padding-top: 5px; }
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


    </head>  
    <body>
    <div class="row">        
        <div class="col-sm-12 col-md-12">
            <div id="OrderItems">
                <table class="table table-striped table-bordered" id="orderDetailTable">
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
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane" id="shipping">
            <iframe src="https://apps.indyimaging.com/apps/orderNotes/<?php echo $result->kp_OrderID ?>/orderView" seamless width=100% height="1200"></iframe>
        </div>
    </div>
</div> <!-- Close for Container

<!--    <pre>
<//?php print_r($result1[0])?>
</pre> -->


<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>-->
<script src="<?php echo base_url(); ?>media/js/jquery_1.9.1.min.js"></script>
<script src="<?php echo base_url(); ?>media/js/orderOverview_jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>extras/TableTools/media/js/TableTools.js"></script>
<script src="<?php echo base_url(); ?>extras/TableTools/media/js/ZeroClipboard.js"></script>
<script src="<?php echo base_url(); ?>bootstrap3/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>js/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>media/dtbs3/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url(); ?>js/orderModuleOrderOverView.js"></script>

<script>
    //		$(function(){
    //                    // on load
    //                    var now = new Date();
    //		    
    //                    var today = (now.getMonth() + 1) + '-' + now.getDate() + '-' + now.getFullYear();
    //		    
    //                    $('#dp1').val(today);
    //		    // calls date picker when selected
    //                    
    //                    $('#dp1').datepicker({
    //                        format: 'mm-dd-yyyy'
    //                    });
    //                    
    //                    $('#go').click(function(){
    //                            var url = "https://localhost/ci/index.php/homescreen/index/";
    //                            var d = $("#dp1").val();
    //                            // var d = (getFullYear(d) + '-' + getMonth(d)  + '-' + getDate(d));
    //                            var d = (d.slice(6,10) + '-' + d.slice(0,2)  + '-' + d.slice(3,5));
    //                            //alert(d);
    //                            window.location = url + d;
    //                            });
    //                    });
</script>
</body>  
</html>  


