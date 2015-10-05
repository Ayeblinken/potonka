<!DOCTYPE HTML>
<html>  
    <head>  
        <title><?php echo $result->kp_OrderID ?></title>  
      <!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.min.css" media="screen">  
    <link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap-responsive.min.css"> 
    <link rel="stylesheet" href="<?php echo base_url();?>css/datepicker.css">
    </head>  
    <body> 
        
    <div class="navbar">
      <div class="navbar-inner">
        <a class="brand" href="#">Da SysTem</a>
        <ul class="nav">
          <li><a href="../home/">Home</a></li>
          <li><a href="#">Customers</a></li>
          <li><a href="#">Estimates</a></li>
          <li class="active"><a href="#">Orders</a></li>
          <li><a href="#">Setup</a></li>
        </ul>
      </div>
    </div>
        
<div class="row-fluid">
    <div class="span9">
        <h4>
        <div class="well well-small">
            OrderID: <?php echo $result->kp_OrderID ?> &nbsp;&nbsp;
            Customer: <?php echo $result->kf_CustomerID ?> &nbsp;&nbsp;
            Job: <?php echo $result->t_JobName ?> &nbsp;&nbsp;
            Date Due: <?php echo $result->d_JobDue ?> &nbsp;&nbsp;   
            Time Due: <?php echo $result->ti_JobDue ?> &nbsp;&nbsp;    
            Status: <?php echo $result->t_JobStatus ?> &nbsp;&nbsp;    
            
        </div>
        </h4>
        
            <div class="span3">
                <table class="table table-striped">
                  <tbody>
                    <tr>
                      <td>Customer</td>
                      <td> <?php echo $result->kf_CustomerID ?></td>
                    </tr>
                    <tr>
                      <td>Account#</td>
                      <td><?php echo $result->kf_CustomerID ?></td>
                    </tr>
                    <tr>
                      <td>Order Contact</td>
                      <td><?php echo $result->kf_ContactID?></td>
                    </tr>
                    <tr>
                      <td>Project Manager</td>
                      <td><?php echo $result->kf_ContactIDProjectManager ?></td>
                    </tr>
                    <tr>
                      <td>Art Contact</td>
                      <td><?php echo $result->kf_ContactIDArtContact ?></td>
                    </tr>
                    <tr>
                      <td>Sales Person</td>
                      <td><?php echo $result->t_PersonWritingOrder ?></td>
                    </tr>
                    <tr>
                      <td>CSR Loggin In</td>
                      <td><?php echo $result->t_PersonWritingOrder ?></td>
                    </tr>
                  </tbody>
                </table>
            </div>
            <div class="span3">
                <table class="table table-striped">
                  <tbody>
                    <tr>
                      <td>Master Job</td>
                      <td> <?php echo $result->kf_MasterJobID ?></td>
                    </tr>
                    <tr>
                      <td>Type of Order</td>
                      <td><?php echo $result->t_TypeOfOrder ?></td>
                    </tr>
                    <tr>
                      <td>P.O. #</td>
                      <td><?php echo $result->t_CustomerPO ?></td>
                    </tr>
                    <tr>
                      <td>Customer P.O. TBD</td>
                      <td><?php echo $result->nb_CustomerPOToBeDetermined ?></td>
                    </tr>
                    <tr>
                      <td>Job Name</td>
                      <td><?php echo $result->t_JobName ?></td>
                    </tr>
                    <tr>
                      <td>CC Trans #</td>
                      <td><?php echo $result->t_CreditCardTransaction ?></td>
                    </tr>
                    <tr>
                      <td>Credit Hold</td>
                      <td><?php echo $result->nb_CreditHoldTimeOrder ?></td>
                    </tr>
                    <tr>
                      <td>Hold Type</td>
                      <td><?php echo $result->t_CreditHoldType ?></td>
                    </tr>
                    <tr>
                      <td>Released By</td>
                      <td><?php echo $result->t_CreditHoldReleasedBy ?></td>
                    </tr>
                  </tbody>
                </table>
            </div>
            <div class="span3">
                 <table class="table table-striped">
                  <tbody>
                    <tr>
                      <td>Service Level</td>
                      <td> <?php echo $result->t_ServiceLevel ?></td>
                    </tr>
                    <tr>
                      <td>Status</td>
                      <td><?php echo $result->t_JobStatus ?></td>
                    </tr>
                    <tr>
                      <td>Sure Date</td>
                      <td><?php echo $result->nb_SureDate ?></td>
                    </tr>
                    <tr>
                      <td>Job Due</td>
                      <td><?php echo $result->d_JobDue ?></td>
                    </tr>
                    <tr>
                      <td>On Press</td>
                      <td><?php echo $result->d_PrintJobDue ?></td>
                    </tr>
                    <tr>
                      <td>Proof Due</td>
                      <td><?php echo $result->d_ProofDue ?></td>
                    </tr>
                    <tr>
                      <td>Proof Approved</td>
                      <td><?php echo $result->ts_ProofApproved ?></td>
                    </tr>
                    <tr>
                      <td>Proof Drawer</td>
                      <td><?php echo $result->t_Drawer ?></td>
                    </tr>
                    <tr>
                      <td>Order Finished</td>
                      <td><?php echo $result->nb_JobFinished ?></td>
                    </tr>
                    <tr>
                      <td>Tracking Sent</td>
                      <td><?php echo $result->ts_TrackNumberEmailSent ?></td>
                    </tr>
                    <tr>
                      <td>Order Invoiced</td>
                      <td><?php echo $result->nb_PostedToQuickBooks ?></td>
                    </tr>
                  </tbody>
                </table>
            </div>    
        </div>
          <table class="table table-striped span4">
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
</div>
<!--<pre>
        <//?php print_r($result1)?>
    </pre>-->
    <div class="span12">
            <div id="content">
                <ul class="nav nav-tabs" id="myTabs">
                    <li class="active"><a href="#details" data-toggle="tab">Details</a></li>
                    <li><a href="#shipping" data-toggle="tab">Shipping</a></li>
                    <li><a href="#notes" data-toggle="tab">Notes</a></li>
                    <li><a href="#redo" data-toggle="tab">Redo</a></li>
                    <li><a href="#orderContacts" data-toggle="tab">Order Contacts</a></li>
                    <li><a href="#statusChanges" data-toggle="tab">Status Changes</a></li>
                    <li><a href="#invoiceDetails" data-toggle="tab">Invoice Details</a></li>
                    <li><a href="#invoiceShipping" data-toggle="tab">Invoice Shipping</a></li>
                    <li><a href="#otherCharges" data-toggle="tab">Other Charges</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="details">
                         <div id="OrderItems">
                            <iframe src="https://apps.indyimaging.com/angularProject/orderItems/#/orderView/<?php echo $result->kp_OrderID ?>" seamless width=100% height="1200"></iframe>
                        </div>
                      
                    </div>
                    <div class="tab-pane" id="shipping">
                        <iframe src="https://apps.indyimaging.com/apps/orderShip/<?php echo $result->kp_OrderID ?>" seamless width=100% height="1200"></iframe>
                        
                    </div>
                    
                    <div class="tab-pane" id="notes">

                    </div>
                    
                    <div class="tab-pane" id="redo">

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
            </div> <!-- Close Content for Tabs -->
    </div>
                
<!--    <pre>
        <//?php print_r($result1[0])?>
    </pre> -->

        
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>js/bootstrap-datepicker.js"></script>
    <script>
		$(function(){
                    // on load
                    var now = new Date();
		    var today = (now.getMonth() + 1) + '-' + now.getDate() + '-' + now.getFullYear();
		    $('#dp1').val(today);
		    // calls date picker when selected
			$('#dp1').datepicker({
                            format: 'mm-dd-yyyy'
                        });
			$('#go').click(function(){
				var url = "https://localhost/ci/index.php/homescreen/index/";
				var d = $("#dp1").val();
                                // var d = (getFullYear(d) + '-' + getMonth(d)  + '-' + getDate(d));
                                var d = (d.slice(6,10) + '-' + d.slice(0,2)  + '-' + d.slice(3,5));
 				//alert(d);
 				window.location = url + d;
				});
		});
	</script>
    </body>  
</html>  


