<!DOCTYPE html>
<html>
    <head>
        <title>Redo</title>
        <base href="<?php echo base_url(); ?>" />
        <script type="text/javascript">
            
            var orderRedoID = "<?php echo $orderRedoID;?>";
            var orderID     = "<?php echo $orderID;?>";
           //alert(orderID);
       </script>
        <link rel="stylesheet" href="media/css_bootstrap/bootstrap.css" type="text/css"/>
        <link rel="stylesheet" href="media/css_bootstrap/bootstrap-responsive.css" type="text/css">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.min.css" type="text/css">
        <link rel="stylesheet" href="media/redo.css" type="text/css">
        
        <!-- Photoswipe Gallery css) -->
        <link href="photoswipe/photoswipe-gallery.css" type="text/css" rel="stylesheet" />
        
        <link href="photoswipe/photoswipe.css" type="text/css" rel="stylesheet" />
        <!--photoswipe Image Gallery files -->
        <script type="text/javascript" src="photoswipe/lib/klass.min.js"></script>
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="media/js_bootstrap/bootstrap.min.js"></script>
        
        <!--photoswipe Image Gallery files -->
        <script type="text/javascript" src="photoswipe/code.photoswipe.jquery-3.0.5.min.js"></script>
        
        <script src="js/redoReadOnly.js"></script>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
                 <!--Form start  -->
                 <form class="form-horizontal" id="redoStatusFrm" name="redoStatusFrm">
                     <div class="row-fluid">
                         <div class="span8">
                             <h3>Redo Info for Order <?php echo $orderID;?></h3>
                         </div>
                     </div>
                     <div class="row-fluid">
                         <div class="well well-small span6">
                             <fieldset>
                                 <div class="span12">
                                     <div class="control-group">
                                         <label class="control-label">Requested By</label>
                                          <div class="controls">
                                            <input readonly="true" id="requestedBy" name="requestedBy" type="text">
                                          </div>
                                     </div>
                                     <div class="control-group">
                                        <label class="control-label">Date Time Requested</label>
                                        <div class="controls">
                                            <input readonly="true" name="dateTimeWhenRequested" id="dateTimeWhenRequested" type="text" class="">
                                        </div>
                                     </div>
                                     <div class="control-group">
                                        <label class="control-label">Redo Status</label>
                                        <div class="controls">
                                            <select readonly="true" name="redoStatus" id="redoStatus" class="">
                                                <option value="Pending">Pending</option>
                                                <option value="Need More Info">Need More Info</option>
                                                <option value="Approved">Approved</option>
                                                <option value="Resolved (No Redo)">Resolved (No Redo)</option>
                                            </select>
                                        </div>
                                         <div class="control-group">
                                        <label class="control-label">Approved By</label>
                                        <div class="controls">
                                            <input type="text" class="typeahead" id="approvedBy" name="approvedBy" autocomplete="off" data-provide="typeahead">
                                        </div>
                                    </div>
                                     </div>
                                 </div>
                             </fieldset>
                         </div>    
                         
                     </div>
                          
                          
                 </form>
            </div>
        </div>
    </body>
</html>