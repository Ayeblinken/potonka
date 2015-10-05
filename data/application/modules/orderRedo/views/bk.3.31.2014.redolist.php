<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Redo List</title>
        <base href="<?php echo base_url(); ?>" />
        <script type="text/javascript">
            
            var orderID = "<?php echo $orderID;?>";
           //alert(orderID);
       </script>
        <link rel="stylesheet" href="media/css_bootstrap/bootstrap.css" type="text/css"/>
        <link rel="stylesheet" href="media/css_bootstrap/bootstrap-responsive.css" type="text/css">
        <link rel="stylesheet" href="media/redolist.css" type="text/css">
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<!--        <script type="text/javascript" charset="utf-8" src="media/js/jquery.js"></script>-->
        
        <script src="media/js_bootstrap/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/jquery-templ.js"></script>
        <script src="js/redolist.js"></script>
    </head>
    <body>
        <div class="container-fluid" style="margin-top: 10px">
            <section class="row-fluid">
                <a id="redoRequestPage" href="#" class="btn"><i class="icon-plus"></i> Redo Request</a>
                <br>
            </section>
            <section class="row-fluid">
                <div class="span12">
                     <table id="records" class="table table-condensed table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Items w/ Redo</th>
                                <th>Redo Order#</th>
                                <th>Department</th>
                                <th>Customer Issue</th>
                                <th>Problem</th>
                                <th>Solution</th>
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
                            <tr id="${OrderRedoID}">
                                <td><a class="statusBtn" href="#">${Status}</a></td>
                                <td>${ItemsRedo}</td>
                                <td><a target="_blank" class="orderIDBtn" href="orders/<?php echo $orderID; ?>">${kf_OrderIDRedo}</a></td>
                                <td>${Department}</td>
                                <td>${CustomerIssue}</td>
                                <td>${Problem}</td>
                                <td>${solution}</td>
                            </tr>
                      </script>
                      <input type="hidden" id="orderIDHidden" name="orderIDHidden" />
                </div>
            </section>
        </div>

    </body>
   
</html>
