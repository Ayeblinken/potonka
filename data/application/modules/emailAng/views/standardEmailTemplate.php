<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:v="urn:schemas-microsoft-com:vml">

<head>

    <!-- Define Charset -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />


    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,800,700,900' rel='stylesheet' type='text/css'>

    <title>Indy Imaging - Customer Job Information</title>
    <!-- Responsive Styles and Valid Styles -->

    <style type="text/css">
        body {
            width: 100%;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            mso-margin-top-alt: 0px;
            mso-margin-bottom-alt: 0px;
            mso-padding-alt: 0px 0px 0px 0px;
        }
        p,
        h1,
        h2,
        h3,
        h4 {
            margin-top: 0;
            margin-bottom: 0;
            padding-top: 0;
            padding-bottom: 0;
        }
        span.preheader {
            display: none;
            font-size: 1px;
        }
        html {
            width: 100%;
        }
        table {
            font-size: 14px;
            border: 0;
        }
        /* ----------- responsivity ----------- */
        @media only screen and (max-width: 640px) {
            /*------ top header ------ */
            body[yahoo] .main-section-header {
                font-size: 34px !important;
                line-height: 30px !important;
            }
            body[yahoo] .main-header {
                font-size: 24px !important;
                line-height: 30px !important;
            }
            body[yahoo] .show {
                display: block !important;
            }
            body[yahoo] .hide {
                display: none !important;
            }
            body[yahoo] .align-center {
                text-align: center !important;
            }
            /*----- main image -------*/
            body[yahoo] .main-image img {
                width: 440px !important;
                height: auto !important;
            }
            /* ====== divider ====== */
            body[yahoo] .divider img {
                width: 440px !important;
            }
            /*--------- banner ----------*/
            body[yahoo] .banner img {
                width: 440px !important;
                height: auto !important;
            }
            /*-------- container --------*/
            body[yahoo] .container590 {
                width: 440px !important;
            }
            body[yahoo] .container580 {
                width: 400px !important;
            }
            body[yahoo] .half-container {
                width: 380px !important;
            }
            /*-------- secions ----------*/
            body[yahoo] .section-item {
                width: 440px !important;
            }
            body[yahoo] .section-img img {
                width: 440px !important;
                height: auto !important;
            }
        }
        @media only screen and (max-width: 479px) {
            /*------ top header ------ */
            body[yahoo] .main-section-header {
                font-size: 30px !important;
                line-height: 30px !important;
            }
            body[yahoo] .main-header {
                font-size: 20px !important;
                line-height: 30px !important;
            }
            /*----- main image -------*/
            body[yahoo] .main-image img {
                width: 280px !important;
                height: auto !important;
            }
            /* ====== divider ====== */
            body[yahoo] .divider img {
                width: 280px !important;
            }
            body[yahoo] .align-center {
                text-align: center !important;
            }
            /*--------- banner ----------*/
            body[yahoo] .banner img {
                width: 280px !important;
                height: auto !important;
            }
            /*-------- container --------*/
            body[yahoo] .container590 {
                width: 280px !important;
            }
            body[yahoo] .container580 {
                width: 260px !important;
            }
            body[yahoo] .wide-iphone {
                width: 210px !important;
            }
            body[yahoo] .half-container {
                width: 210px !important;
            }
            /*-------- secions ----------*/
            body[yahoo] .section-item {
                width: 280px !important;
            }
            body[yahoo] .section-item-iphone {
                width: 280px !important;
            }
            body[yahoo] .section-img img {
                width: 280px !important;
                height: auto !important;
            }
            body[yahoo] .section-iphone-img img {
                width: 280px !important;
                height: auto !important;
            }
            /*------- CTA -------------*/
            body[yahoo] .cta-btn img {
                width: 260px !important;
                height: auto !important;
            }
        }
    </style>
</head>

<body yahoo="fix" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

    <!-- ======= header ======= -->


    <!-- ======= main section ======= -->
    <p>Your Order: <a href="<?php echo 'https://apps.indyimaging.com/shopbot/#/home/view/'.$kp_OrderID.'/details' ?>" target ="_blank" title="Styling Links" style="color: red; text-decoration: none;"> <strong><?php if(isset($kp_OrderID)) echo $kp_OrderID; ?> </strong> </a>
    could not send a tracking email because the contact associated with the order does not have an email address.</p>
    <br/>
    <p>Company Name:<a href="<?php echo 'https://apps.indyimaging.com/shopbot/#/customer/edit//'.$kp_CustomerID.'/addr' ?>" target ="_blank" title="Styling Links" style="color: red; text-decoration: none;"> <strong><?php if(isset($t_CustCompany)) echo $t_CustCompany;?></strong> </a></p>
    <p>Please fix this.</p>




</body>

</html>
