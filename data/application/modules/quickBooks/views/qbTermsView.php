<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <!-- the head section -->
    <head>
        <title>Customer Collection Report</title>
        <style type="text/css">    
		body 
		{
	font: 14px/1.3 verdana, arial, helvetica, sans-serif;
	clear: both;
	font-family: verdana, arial, helvetica, sans-serif;
		}
		#custCollectionInfo {
	background-color: #C0C0C0;
	display: inline-block;
	width: 400px;
}
        #firstTbl {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	display: inline;
	float: left;
	margin-left: 0px;
	padding-left: 0px;
	margin-right: 0px;
	padding-right: 0px;
}
        #secondTbl {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	font-weight: normal;
	margin-right: 450px;
	padding-left: 5px;
	width: 360px;
	float: right;
	display: inline-block;
}
        
        tr #labl {
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 14px;
	font-weight: bold;
	text-align: justify;
	vertical-align: middle;
	border-top-style: inset;
	border-right-style: inset;
	border-bottom-style: inset;
	border-left-style: inset;
	padding-top: 2px;
	padding-bottom: 2px;
	margin-top: 5px;
	margin-bottom: 5px;
	width: 200px;
}
       tr #inputT {
	font-family: "Courier New", Courier, monospace;
	font-size: 14px;
	color: #FFF;
	height: 23px;
	width: 150px;
        text-align: center;
	padding-top: 1px;
	border-top-style: inset;
	border-right-style: inset;
	border-bottom-style: inset;
	border-left-style: inset;
	border: 1px solid #000;
	padding-top: 2px;
	padding-bottom: 2px;
	margin-top: 5px;
	margin-bottom: 5px;
}

#customTable1
{
	border-collapse:collapse;
}

#customTable1, td
{
	border:1px solid black;
}

#customTable2
{
	border-collapse:collapse;
}

#customTable2, td
{
	border:1px solid black;
}
        </style>
        <!-- <link rel="stylesheet" type="text/css" href="main_indy.css" /> -->
    </head>

    <!-- the body section -->
<body>
    <div id="page">
        <div id="main">
        	<div id="content_design">
            <div id="firstTbl">
            
            <table id="customTable1" width="300" border="1">
                    <tr>
                    <td width="200">Current :</td> 
                    <td align="right"><?php echo "$".number_format($current,2); ?></td>
                    </tr>
                    
                    <tr> 
                    <td width="200"> 
                     1-30 :</td>
                     <?php
					 if($balance_1_30 >0)
					 {?>
						 <td align="right"><font color="#FF0000"><?php echo "$".number_format($balance_1_30,2); ?> </font></td>
						 
					<?php
					 }
					 else
					 {?>
						<td align="right"><?php echo "$".number_format($balance_1_30,2); ?></td>
					 <?php
                     }
					 ?> 
                    </tr>
                    
                    <tr> 
                    <td width="200"> 
                     31-60 :</td>
                     <?php
					 if($balance_31_60 >0)
					 {?>
						  <td align="right"><font color="#FF0000"><?php echo "$".number_format($balance_31_60,2);?></font></td>
					 <?php
                     }
					 else
					 {?>
						 <td align="right"><?php echo "$".number_format($balance_31_60,2);?></td>
					<?php
					 }
					 ?> 
                   
                    </tr>
                    
                    <tr> 
                    <td width="200"> 
                     61-90 :</td>
                     <?php
					 if($balance_61_90 >0)
					 { ?>
                     	 <td align="right"><font color="#FF0000"><?php echo "$".number_format($balance_61_90,2); ?></font></td>
					 <?php
                     }
					 else
					 {?>
						 <td align="right"><?php echo "$".number_format($balance_61_90,2); ?></td>
					 <?php
                     }?>
                   
                    </tr>
                    
                    <tr> 
                    <td width="200"> 
                    Greater 90 :</td>
                    <?php
					if($balance_greater_90 >0)
					{?>
						<td align="right"><font color="#FF0000"><?php echo "$".number_format($balance_greater_90,2) ;?></font></td>
					<?php
                    }
					else
					{?>
						<td align="right"><?php echo "$".number_format($balance_greater_90,2) ;?></td>
					<?php
                    }
					?> 
                    
                    </tr>
                    
                    <tr> 
                    <td width="200"> 
                    Total Invoices Past Due:</td> 
                    <?php
					if($totalInvoicesPastDue >0)
					{?>
						<td align="right"><font color="#FF0000"><?php echo "$".number_format($totalInvoicesPastDue ,2) ;?></font></td>
					<?php
                    }
					else
					{
					?>
                    	<td align="right"><?php echo "$".number_format($totalInvoicesPastDue ,2) ;?></td>
                    <?php
					}
					?>
                    </tr>
                    
                    <tr> 
                    <td width="200"> 
                    Grand Total Invoiced:</td> 
                    <td align="right" ><?php echo "$".number_format($grandTotalInvoiced ,2) ;?></td>
                    </tr>
                 </table>
                
                <br/>
                 <table id="customTable1" width="300" border="1">
                    <tr> 
                    <td width="200"> 
                    Unused Payment :</td> 
                    <td align="right"><?php echo "$".number_format($TotalUnusedPayment,2); ?></td>
                    </tr>
                    
                    <tr> 
                    <td width="200"> 
                    Credit Memo Remaining :</td> 
                    <td align="right"> <?php echo "$".number_format($CreditRemaining,2); ?></td>
                    </tr>
                
                
                    <tr> 
                    <td width="200"> 
                    Work In House :</td> 
                    <td align="right"><?php echo "$".number_format($WorkInHouseTotalAmount,2); ?></td>
                    </tr>
                
                    <tr> 
                    <td width="200"> 
                    Total On Account :</td> 
                    <td align="right"><?php echo "$".number_format($totalOnAccount,2); ?></td>
                    </tr>
                
                    <tr> 
                    <td width="200"> 
                    Credit Available :</td> 
                    <?php 
					if($creditAvailable <= 0)
					{?>
						<td align="right"><font color="#FF0000"><?php echo "$".number_format($creditAvailable,2) ?></font></td>
						
						
					<?php
                    }
					else
					{?>
						<td align="right"><font color="#009100"><?php echo "$".number_format($creditAvailable,2) ?></font></td>
					<?php
                    }
					 ?>
                    </tr>
            </table>
           
            </div>
           
            
          </div>
        </div>
        </div>
        <div id="footer">
    </div><!-- end page -->
</body>
</html>
