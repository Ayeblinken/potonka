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


    </head>  
    <body> 
        <div class="container">
            <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">INDY</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="../apps/home/">Home</a></li>
                        <li><a href="#">Customers</a></li>
                        <li class="active"><a href="#">Orders</a></li> 
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#">Logout</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Setup <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li><a href="#">Separated link</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="row">
                <div class="col-md-12">
                    <div class="well well-small">
                        <h4>

                            <?php echo $result->kp_OrderID ?> &nbsp;&nbsp;
                            <?php echo $result->t_CustCompany ?> &nbsp;&nbsp;
                            <?php echo $result->t_JobName ?> &nbsp;&nbsp;
                            <div class="pull-right">
                                <?php echo $result->t_JobStatus; ?> &nbsp;&nbsp;
                                <?php date_default_timezone_set('America/Indianapolis'); ?>
                                <?php echo date('m-d-Y', strtotime($result->d_JobDue)); ?> &nbsp;&nbsp;
                                <?php
                                if ($result->ti_JobDue == '') {
                                    echo "";
                                } else {
                                    echo date('h:i a', strtotime($result->ti_JobDue));
                                }
                                ?>
                            </div>

                        </h4>
                    </div>
                </div>
                <div class="row">
                    <form class="form-horizontal" role="form">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Customer</label>
                                <div class="col-md-8">
                                    <select class="form-control">
                                        <option> <?php echo $result->t_CustCompany ?> </option>
                                        <option>Going to Use Chosen...</option>
                                        <option>For logging in the job</option>
                                        <option>Read Form</option>
                                        <option>Will be Input</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Order Contact</label>
                                <div class="col-md-8">
                                    <select class="form-control">
                                        <option><?php echo $result->t_ContactNameFull ?></option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">PM</label>
                                <div class="col-md-8">
                                    <select class="form-control">
                                        <option><?php echo $result->t_ProjectManager ?></option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Art Contact</label>
                                <div class="col-md-8">
                                    <select class="form-control">
                                        <option><?php echo $result->t_ArtContact ?></option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Salesperson</label>
                                <div class="col-md-8">
                                    <select class="form-control">
                                        <option><?php echo $result->t_SalesPerson ?></option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Logged In</label>
                                <div class="col-md-8">
                                    <select class="form-control">
                                        <option><?php echo $result->t_PersonWritingOrder ?></option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                    </select>
                                </div>
                            </div>


                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Master Job</label>
                                <div class="col-md-8">
                                    <select class="form-control">
                                        <option><?php echo $result->t_MasterJobName ?></option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Type of Order</label>
                                <div class="col-md-8">
                                    <select class="form-control">
                                        <option><?php echo $result->t_TypeOfOrder ?></option>
                                        <option>Sample</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">PO TBD</label>
                                <div class="col-md-8">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" id="inlineCheckbox" value="1" <?php echo ($result->nb_CustomerPOToBeDetermined == 1 ? 'checked' : ''); ?>>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">PO #</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" value="<?php echo $result->t_CustomerPO ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Job Name</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" value="<?php echo $result->t_JobName ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Art Location</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" value="<?php echo $result->t_ArtLocation ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <ul class="nav nav-tabs" id="ordertop">
                                <li class="active"><a href="#schedule" data-toggle="tab">Schedule</a></li>
                                <li><a href="#proof" data-toggle="tab">Proof</a></li>
                                <li><a href="#accounting" data-toggle="tab">Accounting</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="schedule">
                                    <div id="OrderItems" style="margin-top: 15px">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Status</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" value="<?php echo $result->t_JobStatus ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Service Level</label>
                                            <div class="col-md-8">
                                                <select class="form-control">
                                                    <option><?php echo $result->t_ServiceLevel ?></option>
                                                    <option>48 Hour</option>
                                                    <option>24 Hour</option>
                                                    <option>SameDay</option>
                                                    <option>>72 Hours</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">DateDue</label>
                                            <div class="col-md-4">
                                                <input  id="effect" type="text" value="<?php echo date('m-d-Y', strtotime($result->d_JobDue)) ?>" class="form-control datepicker">
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control">
                                                    <option>
                                                        <?php
                                                        if ($result->ti_JobDue == '') {
                                                            echo "";
                                                        } else {
                                                            echo date('h:i a', strtotime($result->ti_JobDue));
                                                        }
                                                        ?>
                                                    </option>
                                                    <option>12:00 PM</option>
                                                    <option>1:00 PM</option>
                                                    <option>2:00 PM</option>
                                                    <option>3:00 PM</option>
                                                    <option>4:00 PM</option>
                                                    <option>5:00 PM</option>
                                                    <option>6:00 PM</option>
                                                    <option>7:00 PM</option>
                                                    <option>8:00 PM</option>
                                                    <option>9:00 PM</option>
                                                    <option>10:00 PM</option>
                                                    <option>11:00 PM</option>
                                                    <option>12:00 AM</option>
                                                    <option>1:00 AM</option>
                                                    <option>2:00 AM</option>
                                                    <option>3:00 AM</option>
                                                    <option>4:00 AM</option>
                                                    <option>5:00 AM</option>
                                                    <option>6:00 AM</option>
                                                    <option>7:00 AM</option>
                                                    <option>8:00 AM</option>
                                                    <option>9:00 AM</option>
                                                    <option>10:00 AM</option>
                                                    <option>11:00 AM</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">On Press</label>
                                            <div class="col-md-4">
                                                <input type="text" value="<?php echo ($result->d_PrintJobDue == '' ? '' : date('m-d-Y', strtotime($result->d_PrintJobDue))); ?>" class="form-control datepicker">
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control">
                                                    <option>
                                                        <?php
                                                        if ($result->ti_PrintJobDue == '') {
                                                            echo "";
                                                        } else {
                                                            echo date('h:i a', strtotime($result->ti_PrintJobDue));
                                                        }
                                                        ?>
                                                    </option>
                                                    <option>12:00 PM</option>
                                                    <option>1:00 PM</option>
                                                    <option>2:00 PM</option>
                                                    <option>3:00 PM</option>
                                                    <option>4:00 PM</option>
                                                    <option>5:00 PM</option>
                                                    <option>6:00 PM</option>
                                                    <option>7:00 PM</option>
                                                    <option>8:00 PM</option>
                                                    <option>9:00 PM</option>
                                                    <option>10:00 PM</option>
                                                    <option>11:00 PM</option>
                                                    <option>12:00 AM</option>
                                                    <option>1:00 AM</option>
                                                    <option>2:00 AM</option>
                                                    <option>3:00 AM</option>
                                                    <option>4:00 AM</option>
                                                    <option>5:00 AM</option>
                                                    <option>6:00 AM</option>
                                                    <option>7:00 AM</option>
                                                    <option>8:00 AM</option>
                                                    <option>9:00 AM</option>
                                                    <option>10:00 AM</option>
                                                    <option>11:00 AM</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Proof Due</label>
                                            <div class="col-md-4">
                                                <input type="text" value="<?php echo ($result->d_ProofDue == '' ? '' : date('m-d-Y', strtotime($result->d_ProofDue))); ?>" class="form-control datepicker">
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control">
                                                    <option>
                                                        <?php
                                                        if ($result->ti_ProofDue == '') {
                                                            echo "";
                                                        } else {
                                                            echo date('h:i a', strtotime($result->ti_ProofDue));
                                                        }
                                                        ?>
                                                    </option>
                                                    <option>12:00 PM</option>
                                                    <option>1:00 PM</option>
                                                    <option>2:00 PM</option>
                                                    <option>3:00 PM</option>
                                                    <option>4:00 PM</option>
                                                    <option>5:00 PM</option>
                                                    <option>6:00 PM</option>
                                                    <option>7:00 PM</option>
                                                    <option>8:00 PM</option>
                                                    <option>9:00 PM</option>
                                                    <option>10:00 PM</option>
                                                    <option>11:00 PM</option>
                                                    <option>12:00 AM</option>
                                                    <option>1:00 AM</option>
                                                    <option>2:00 AM</option>
                                                    <option>3:00 AM</option>
                                                    <option>4:00 AM</option>
                                                    <option>5:00 AM</option>
                                                    <option>6:00 AM</option>
                                                    <option>7:00 AM</option>
                                                    <option>8:00 AM</option>
                                                    <option>9:00 AM</option>
                                                    <option>10:00 AM</option>
                                                    <option>11:00 AM</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Sure Date</label>
                                            <div class="col-md-8">
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" id="inlineCheckbox" value="1" <?php echo ($result->nb_SureDate == 1 ? 'checked' : ''); ?>>   
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="proof">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Proof Drawer</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" value="<?php echo $result->t_Drawer ?>">
                                        </div>
                                    </div>

                                </div>

                                <div class="tab-pane" id="accounting">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Credit Hold</label>

                                        <div class="col-md-8">
                                            <label class="checkbox-inline">
                                                <input type="checkbox" id="inlineCheckbox" value="1" <?php echo ($result->nb_CreditHoldTimeOrder == 1 ? 'checked' : ''); ?>> 
                                                When Order Placed
                                            </label>
                                        </div>

                                    </div>
                                    <div class="form-group check check4">
                                        <label class="col-md-4 control-label">Hold Type</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" value="<?php echo $result->t_CreditHoldType ?>">
                                        </div>
                                    </div>
                                    <div class="form-group check check4">
                                        <label class="col-md-4 control-label">Released By</label>
                                        <div class="col-md-8">
                                            <select class="form-control">
                                                <option><?php echo $result->t_CreditHoldReleasedBy ?></option>
                                                <option>Ebony</option>
                                                <option>Nicki</option>
                                                <option>Dave</option>
                                                <option>Robbie</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">CC Trans. #</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" value="<?php echo $result->t_CreditCardTransaction ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Overide Notes</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control" rows ="4"><?php echo $result->t_CreditHoldOverideNote ?></textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div> <!-- Close Content for Tabs -->
                </div>
                </form>
            </div>  <!-- Close Content for Row -->
            <hr>
           

            <div class="row">
                <div class="col-md-4">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>SqFt</th>
                                <th>Time</th>
                                <th>Press</th>
                                <th>Cx</th>
                                <th>Info</th>
                                <th>Ship</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo round($result->n_OrderItemCount) ?></td>
                                <td><?php echo round($result->n_OICSqFtSum) ?></td>
                                <td><?php echo round($result->n_DurationTime) ?></td>
                                <td><?php echo $result->t_MachineAb ?></td>
                                <td><?php echo round($result->n_Complexity) ?></td>
                                <td><?php echo $result->t_OrderItemAb ?></td>
                                <td><?php echo $result->t_OrdShip ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-8" id="checkrow">
                    <div class="form-inline">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"> Order Finished
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"> Tracking Sent
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"> Order Invoiced
                                </label>
                            </div> 
                        </div>
                    </div>
                </div>
            </div> <!-- Close Content for Row -->
            
            <hr>


 <!--<pre>
         <//?php print_r($result1)?>
 </pre>-->
            <div class="row">        
                <div class="col-md-12">
                    <div id="content">
                        <ul class="nav nav-tabs" id="myTabs">
                            <li class="active"><a href="#details" data-toggle="tab">Details</a></li>
                            <li><a href="#shipping" data-toggle="tab">Shipping</a></li>
                            <li><a href="#redo" data-toggle="tab">Redo</a></li>
                            <li><a href="#orderContacts" data-toggle="tab">Order Contacts</a></li>
                            <li><a href="#statusChanges" data-toggle="tab">Status Changes</a></li>
                            <li><a href="#invoiceDetails" data-toggle="tab">Invoice Details</a></li>
                            <li><a href="#invoiceShipping" data-toggle="tab">Invoice Shipping</a></li>
                            <li><a href="#otherCharges" data-toggle="tab">Other Charges</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="details">
                                <div id="OrderItems" style="margin-top: 15px">
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
                                <iframe src="https://apps.indyimaging.com/apps/orderShip/<?php echo $result->kp_OrderID ?>" seamless width=100% height="1200"></iframe>

                            </div>

                            <div class="tab-pane" id="redo">
                                <iframe src="https://apps.indyimaging.com/apps/redolist/<?php echo $result->kp_OrderID ?>" seamless width=100% height="900"></iframe>
                                <!--                    This will need updated with some logic like this: 
                                                        If(IsEmpty (  D4000_vJobs_bt::kf_OrderRedoID );
                                                        "https://apps.indyimaging.com/apps/redolist/" & D4000_vJobs_bt::kp_OrderID;
                                                        If( D4000_vJobs_bt::t_TypeOfOrder = "Redo";
                                                        "https://apps.indyimaging.com/apps/redoReadOnly/" & D4000_vJobs_bt::kp_OrderID & "/" & D4000_vJobs_bt::kf_OrderRedoID;
                                                        "https://apps.indyimaging.com/apps/redolist/" & D4000_vJobs_bt::kp_OrderID))-->
                            </div>

                            <div class="tab-pane" id="orderContacts">
                                <iframe src="https://apps.indyimaging.com/apps/orderContacts/<?php echo $result->kp_OrderID ?>" seamless width=100% height="900"></iframe>
                            </div>

                            <div class="tab-pane" id="statusChanges">
                                <iframe src="https://apps.indyimaging.com/apps/statusLog/<?php echo $result->kp_OrderID ?>" seamless width=100% height="900"></iframe>

                            </div>

                            <div class="tab-pane" id="invoiceDetails">
                                <iframe src="https://apps.indyimaging.com/apps/orderItems/<?php echo $result->kp_OrderID ?>" seamless width=100% height="900"></iframe>

                            </div>

                            <div class="tab-pane" id="invoiceShipping">
                                <iframe src="https://apps.indyimaging.com/apps/odrTracking/<?php echo $result->kp_OrderID ?>" seamless width=100% height="900"></iframe>
                            </div>

                            <div class="tab-pane" id="otherCharges">
                                <iframe src="https://apps.indyimaging.com/apps/otherCharges/<?php echo $result->kp_OrderID ?>" seamless width=100% height="900"></iframe>

                            </div>

                        </div>
                        <br>
                        <iframe src="https://apps.indyimaging.com/apps/orderNotes/<?php echo $result->kp_OrderID ?>" seamless width=100% height="900"></iframe>
                    </div> <!-- Close Content for Tabs -->
                </div>
            </div>
        </div> <!-- Close for Container
    
    <!--    <pre>
    <//?php print_r($result1[0])?>
    </pre> -->


        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
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


