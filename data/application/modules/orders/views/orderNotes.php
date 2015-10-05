<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Notes</title>
        <base href="<?php echo base_url(); ?>" />
        <script type="text/javascript">
            var notesOrderID     = "<?php echo $notesOrderID;?>";
            var typeOfView       = "<?php echo $typeOfView;?>";
           //alert(orderID);
       </script>
        
        
<!--        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.no-icons.min.css" />-->
        <link rel="stylesheet" href="media/css_bootstrap/bootstrap.css" type="text/css"/>
        <link rel="stylesheet" href="media/css_bootstrap/bootstrap-responsive.css" type="text/css">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.min.css" type="text/css">
        
        <link rel="stylesheet" href="media/summernote.css" type="text/css"/>
         <link rel="shortcut icon" href="images/ii_logo_fav.png">
        <!-- summernote compatibility css for bootstrap 2.x. -->
        <link rel="stylesheet/less" type="text/css" href="media/summernote.less" /> 
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="js/jquery.validate.min.js"></script>
        <script src="js/additional-methods.min.js"></script>
 
        
        <script src="media/js_bootstrap/bootstrap.min.js"></script>
        <script src="js/clearForm.js"></script>
          
       
        
       
        <script src="media/js_summernote/summernote.min_1.js"></script>
        <script src="js/orderModuleNotes.js"></script>
<!--        <script src="media/js_summernote/summernote.js"></script>-->
        <style type="text/css">
           .error {
                color:red;
            }
/*            .valid {
                color:green;
            }*/
        </style>
    </head>
    <body>
         <div class="container-fluid">
            <div class="row-fluid">
                <!--Form start  -->
                <form class="form-horizontal" id="orderNotesFrm" name="orderNotesFrm">
                
                <div id="customerNotesDiv" class="row-fluid hide">
                    <div class="span12">
                        <h5>CUSTOMER NOTES</h5>
                        <div id="customerNotesSummerNote">

		        </div>
<!--                        <div id="" class="summernote">
                            
                        </div>-->
                    </div>
                     <input type="hidden" name="customerNotesHiddenVal" id="customerNotesHiddenVal" />
                    <input type="hidden" name="notesCustomerIDHidden" id="notesCustomerIDHidden" >
                </div>
                <div id="orderNotesDiv" class="row-fluid hide">
                    <div class="span12">
                        <h5>ORDER NOTES</h5>
                        <div id="orderNotesSummerNote">

		        </div>
<!--                        <div id="" class="summernote">
                            
                        </div>-->
                    </div>
                    <input type="hidden" name="orderNotesHiddenVal" id="orderNotesHiddenVal" />
                </div>    
                &nbsp;
                   <input type="hidden" name="notesOrderIDHidden"   id="notesOrderIDHidden" value="<?php echo $notesOrderID;?>"/>
                   <input type="hidden" name="notesTypeOfView"      id="notesTypeOfView"    value="<?php echo $typeOfView;?>"/>
                </form>
                <div class="row-fluid">
                    <a id="submitOrderNotesFrm" name="submitOrderNotesFrm" href="#" class="btn btn-primary pull-right hide">Update</a>
                </div>
            </div>
        </div>
         <div id="myModal" class="modal hide fade">
            <div class="modal-header">
                <h3>Please wait</h3>
            </div>
            <div class="modal-body">
                <p id="uploadedFiles">
                    <img src="images/ajaxLoad/ajax-loader.gif" alt="Ajax Loading Animation" />&nbsp;&nbsp;&nbsp;&nbsp;<span><strong>Submitting Data...</strong></span>
                </p>
            </div>
            <div class="modal-footer">
                <button id="closeMyModalBtn" class="btn hide">Close</button>
            </div>
        </div>
        <script src="//cdnjs.cloudflare.com/ajax/libs/less.js/1.3.3/less.min.js" type="text/javascript"></script>
    </body>
   
</html>

