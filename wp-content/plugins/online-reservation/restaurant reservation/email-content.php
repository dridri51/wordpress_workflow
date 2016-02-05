<?php
/*
	TABLE OF CONTENTS ( SCHEDULE TAB )
	=======================================
	1.	HTML 
	2.	PLAIN TEXT
*/

global	$name;
global	$email; 			
global	$phone; 			
global	$message; 		
global	$table;
global 	$type_of_table;
global	$persons;		
global	$lunch; 			
global	$date;			
global 	$time;		
global 	$recipient;
global 	$from;
global  $subject;
global  $messages;
global $restaurant_name;
global $restaurant_address;
global  $restaurant_city;
global  $restaurant_state;
global  $restaurant_zipcode;
global $restaurant_phone;
global $restaurant_fax;
global $restaurant_email;
global $restaurant_website;
global $restaurant_logo;
global $restaurant_image;
global $restaurant_offer_link;
global $restaurant_reservation_link;
global $restaurant_message;
global $restaurant_information;
global $restaurant_policies;
global $restaurant_icon_exist;
global $restaurant_facebook;
global $restaurant_twitter;
global $restaurant_google;
global $restaurant_linkedin;
global $restaurant_pinterest;
global $restaurant_youtube;
global $restaurant_footer;
global $restaurant_footer;
global $confirmation_link;
global $email_template;
global $text_email;




/*========================================================
	1.	HTML 
========================================================*/
$email_template .='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title></title>


<!--[if gte mso 6]>
  <style>
      table.kmButtonBarContent {width:100% !important;}
      table.kmButtonCollectionContent {width:100% !important;}
  </style>
<![endif]-->
<style type="text/css">
  @media only screen and (max-width: 480px) {
    body, table, td, p, a, li, blockquote {
      -webkit-text-size-adjust: none !important;
    }
    body {
      width: 100% !important;
      min-width: 100% !important;
    }
    td[id=bodyCell] {
      padding: 10px !important;
    }
    table.kmMobileHide {
      display:none !important;
    }
    table[class=kmTextContentContainer] {
      width: 100% !important;
    }
    table[class=kmBoxedTextContentContainer] {
      width: 100% !important;
    }
    td[class=kmImageContent] {
      padding-left:0 !important;
      padding-right:0 !important;
    }
    img[class=kmImage] {
      width:100% !important;
    }
    table[class=kmSplitContentLeftContentContainer],
    table[class=kmSplitContentRightContentContainer],
    table[class=kmColumnContainer],
    td[class=kmVerticalButtonBarContentOuter] table[class=kmButtonBarContent],
    td[class=kmVerticalButtonCollectionContentOuter] table[class=kmButtonCollectionContent],
    table[class=kmVerticalButton],
    table[class=kmVerticalButtonContent] {
      width:100% !important;
    }
    td[class=kmButtonCollectionInner] {
      padding-left:9px !important;
      padding-right:9px !important;
      padding-top:9px !important;
      padding-bottom:0 !important;
      background-color:transparent !important;
    }
    td[class=kmVerticalButtonIconContent],
    td[class=kmVerticalButtonTextContent],
    td[class=kmVerticalButtonContentOuter] {
      padding-left:0 !important;
      padding-right:0 !important;
      padding-bottom:9px !important;
    }
    table[class=kmSplitContentLeftContentContainer] td[class=kmTextContent],
    table[class=kmSplitContentRightContentContainer] td[class=kmTextContent],
    table[class="kmColumnContainer"] td[class=kmTextContent],
    table[class=kmSplitContentLeftContentContainer] td[class=kmImageContent],
    table[class=kmSplitContentRightContentContainer] td[class=kmImageContent] {
      padding-top:9px !important;
    }
    td[class="rowContainer kmFloatLeft"],
    td[class="rowContainer kmFloatLeft firstColumn"],
    td[class="rowContainer kmFloatLeft lastColumn"] {
      float:left;
      clear: both;
      width: 100% !important;
    }
    table[id=templateContainer],
    table[class=templateRow] {
      max-width:600px !important;
      width:100% !important;
    }
    
      h1 {
        font-size:24px !important;
        line-height:130% !important;
      }
    
    
      h2 {
        font-size:20px !important;
        line-height:130% !important;
      }
    
    
      h3 {
        font-size:18px !important;
        line-height:130% !important;
      }
    
    
      h4 {
        font-size:16px !important;
        line-height:130% !important;
      }
    
    
      td[class=kmTextContent] {
        font-size:14px !important;
        line-height:130% !important;
      }
      td[class=kmTextBlockInner] td[class=kmTextContent] {
        padding-right:18px !important;
        padding-left:18px !important;
      }

      td[class="kmTableBlock kmTableMobile"] td[class=kmTableBlockInner] {
        padding-left:9px !important;
        padding-right:9px !important;
      }
      td[class="kmTableBlock kmTableMobile"] td[class=kmTableBlockInner] [class=kmTextContent] {
        font-size:14px !important;
        line-height:130% !important;
        padding-left:4px !important;
        padding-right:4px !important;
      }
    
  }
</style>

</head>
<body style="margin: 0; padding: 0; background-color: #C7C7C7">
<center>
<table align="center" border="0" cellpadding="0" cellspacing="0" id="bodyTable" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 0; background-color: #C7C7C7; height: 100%; margin: 0; width: 100%">
<tbody>
<tr>
<td align="center" id="bodyCell" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding-top: 50px; padding-left: 20px; padding-bottom: 20px; padding-right: 20px; border-top: 0; height: 100%; margin: 0; width: 100%">
<table border="0" cellpadding="0" cellspacing="0" id="templateContainer" width="600" style="mso-table-lspace: 0; mso-table-rspace: 0; border: 7px solid #F0670C; background-color: #F4F4F4">
<tbody>
<tr>
<td id="templateContainerInner" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 0">
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tr>
<td align="center" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<table border="0" cellpadding="0" cellspacing="0" class="templateRow" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody>
<tr>
<td class="rowContainer kmFloatLeft" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<table border="0" cellpadding="0" cellspacing="0" class="kmSplitBlock" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody class="kmSplitBlockOuter">
<tr>
<td class="kmSplitBlockInner" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding-top:20px;padding-bottom:9px;background-color:#FFFFFF;padding-left:18px;padding-right:18px;">
<table align="left" border="0" cellpadding="0" cellspacing="0" class="kmSplitContentOuter" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody>
<tr>
<td class="kmSplitContentInner" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<table align="left" border="0" cellpadding="0" cellspacing="0" class="kmSplitContentLeftContentContainer" width="264" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody>
<tr>
<td class="kmImageContent" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 0; padding-top:0px;padding-bottom:0;padding-left:9px;padding-right:9px;">';

if( $restaurant_logo != '' ){
$email_template .='<img align="left" alt="" class="kmImage" src="'.$restaurant_logo.'" width="228" style="border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; padding-bottom: 0; display: inline; vertical-align: bottom; margin-right: 0; max-width:274px;" />';
}

$email_template .='
</td>
</tr>
</tbody>
</table>
<table align="right" border="0" cellpadding="0" cellspacing="0" class="kmSplitContentRightContentContainer" width="264" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody>
<tr>
<td class="kmTextContent" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: #FFF; font-family: Helvetica, Arial; font-size: 14px; line-height: 150%; text-align: left">';
if( $restaurant_address != '' ){
$email_template .='<p style="margin: 0; text-align: right;"><span style="color:#000000;">'.$restaurant_address.'</span></p>';
}

if( $restaurant_address != '' ){
	$email_template .='<p style="margin: 0; text-align: right;">';
	
		if( $restaurant_city != '' ){
			$email_template .='<span style="color:#000000;">'.$restaurant_city.', </span>';
		}
		
		if( $restaurant_state != '' ){
			$email_template .='<span style="color:#000000;">'.$restaurant_state.'</span>';
		}
		
		if( $restaurant_zipcode != '' ){
			$email_template .='<span style="color:#000000;"> '.zipcode.'</span>';
		}
	$email_template .='</p>';
}

if( $restaurant_phone != '' ){
$email_template .='<p style="margin: 0; text-align: right;"><span style="color:#000000;">Tel: '.$restaurant_phone.'</span></p>';
}
if( $restaurant_fax != '' ){
$email_template .='<p style="margin: 0; text-align: right;"><span style="color:#000000;">Fax: '.$restaurant_fax.'</span></p>';
}
if( $restaurant_email != '' ){
$email_template .='<p style="margin: 0; text-align: right;"><a href="'.$restaurant_email.'">'.$restaurant_email.'</a></p>';
}
if( $restaurant_website != '' ){
$email_template .='<p style="margin: 0; text-align: right;"><a target="_blank" href="http://'.$restaurant_website.'" style="word-wrap: break-word; color: #0000CD; font-weight: normal; text-decoration: underline">'.$restaurant_website.'</a></p>';
}
if( $restaurant_offer_link != '' || $restaurant_reservation_link != '' ){
	$email_template .='<p style="margin: 0; padding-bottom: 0; text-align: right;"><span style="color:#000000;">'; 
		if( $restaurant_offer_link != '' ){
			$email_template .='<a target="_blank" href="http://'.$restaurant_offer_link.'">Special Offers</a></span>'; 
		}
	
		if( $restaurant_offer_link != '' && $restaurant_reservation_link != '' ){
			$email_template .='<span style="color:#000000;"> | </span>'; 
		}
		if( $restaurant_reservation_link != '' ){
			$email_template .='<a target="_blank" href="http://'.$restaurant_reservation_link.'">Reservations</a>';
		}					
	$email_template .='</p>';
} // if( $restaurant_offer_link != '' || $restaurant_reservation_link != '' ){
$email_template .='
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table border="0" cellpadding="0" cellspacing="0" class="kmTextBlock" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody class="kmTextBlockOuter">
<tr>
<td class="kmTextBlockInner" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; background-color:#FFFFFF;">
<table align="left" border="0" cellpadding="0" cellspacing="0" class="kmTextContentContainer" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody>
<tr>
<td class="kmTextContent" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: #FFF; font-family: Helvetica, Arial; font-size: 14px; line-height: 150%; text-align: left; font-size:12px;color:#727272;padding-bottom:20px;text-align:center;padding-right:18px;padding-left:18px;padding-top:20px;">
<p style="margin: 0; padding-bottom: 0"><span style="color:#FF8C00;"><span style="font-size: 36px; line-height: 46px;"><strong>Reservation Confirmation</strong></span></span></p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table border="0" cellpadding="0" cellspacing="0" class="kmImageBlock" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody class="kmImageBlockOuter">
<tr>
<td class="kmImageBlockInner" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0;" valign="top">
<table align="left" border="0" cellpadding="0" cellspacing="0" class="kmImageContentContainer" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody>
<tr>
<td class="kmImageContent" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 0; padding-top:0px;padding-bottom:0;text-align: center;">';

if( $restaurant_image != '' ){
$email_template .='<img align="center" alt="" class="kmImage" src="'.$restaurant_image.'" width="500" style="border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; padding-bottom: 0; display: inline; vertical-align: bottom; width:100%;" />';
}

$email_template .='</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table border="0" cellpadding="0" cellspacing="0" class="kmTextBlock" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody class="kmTextBlockOuter">
<tr>
<td class="kmTextBlockInner" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; background-color:#F0670C;">
<table align="left" border="0" cellpadding="0" cellspacing="0" class="kmTextContentContainer" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody>
<tr>
<td class="kmTextContent" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: #FFF; font-family: Helvetica, Arial; font-size: 14px; line-height: 150%; text-align: left; padding-top:20px;padding-bottom:20px;padding-left:18px;color:#FFFFFF;padding-right:18px;">
<p style="margin: 0; padding-bottom: 0"><span style="font-size:26px;"><strong>'.$subject.'</strong></span></p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td align="center" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<table border="0" cellpadding="0" cellspacing="0" class="templateRow" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody>
<tr>
<td class="rowContainer kmFloatLeft" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<table border="0" cellpadding="0" cellspacing="0" class="kmTextBlock" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody class="kmTextBlockOuter">
<tr>
<td class="kmTextBlockInner" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; background-color:#FFFFFF;">
<table align="left" border="0" cellpadding="0" cellspacing="0" class="kmTextContentContainer" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody>
<tr>
<td class="kmTextContent" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: #FFF; font-family: Helvetica, Arial; font-size: 14px; line-height: 150%; text-align: left; padding-top:20px;padding-bottom:20px;padding-left:18px;padding-right:18px;">

<p style="margin: 0; padding-bottom: 1em">
	<span style="color:#000000; min-width:150px; display:inline-block;"><strong>Name 			</strong></span>
	<span style="color:#000000;">'.$name.'</span>
</p>
<p style="margin: 0; padding-bottom: 1em">
	<span style="color:#000000; min-width:150px; display:inline-block;"><strong>Email			</strong></span>
	<span style="color:#000000;">'.$email.'</span>
</p>
<p style="margin: 0; padding-bottom: 1em">
	<span style="color:#000000; min-width:150px; display:inline-block;"><strong>Phone			</strong></span>
	<span style="color:#000000;">'.$phone.'</span>
</p>';
if( $type_of_table != '' ){
$email_template .='
<p style="margin: 0; padding-bottom: 1em">
	<span style="color:#000000; min-width:150px; display:inline-block;"><strong>Type of table 			</strong></span>
	<span style="color:#000000;">'.$type_of_table.'</span>
</p>';
}

$email_template .='
<p style="margin: 0; padding-bottom: 1em">
	<span style="color:#000000; min-width:150px; display:inline-block;"><strong>Table		</strong></span>
	<span style="color:#000000;">'.$table.'</span>
</p>
<p style="margin: 0; padding-bottom: 1em">
	<span style="color:#000000; min-width:150px; display:inline-block;"><strong>Persons			</strong></span>
	<span style="color:#000000;">'.$persons.'</span>
</p>
<p style="margin: 0; padding-bottom: 1em">
	<span style="color:#000000; min-width:150px; display:inline-block;"><strong>Booking Date 			</strong></span>
	<span style="color:#000000;">'.$date.'</span>
</p>
<p style="margin: 0; padding-bottom: 1em">
	<span style="color:#000000; min-width:150px; display:inline-block;"><strong>Booking Time			</strong></span>
	<span style="color:#000000;">'.$time.'</span>
</p>
<p style="margin: 0; padding-bottom: 1em">
	<span style="color:#000000; min-width:150px; display:inline-block;"><strong>Message			</strong></span>
	<span style="color:#000000;">'.$message.'</span>
</p>';

if( $restaurant_message != '' ){
$email_template .= 	$restaurant_message;
} // if( $restaurant_message != '' ){
	
$email_template .='
<br />
<p style="margin: 0; padding-bottom: 1em">
	<span style="color:#000000;display:inline-block;">Please <strong><a href="'.$confirmation_link.'" target="_blank">Click Here</a></strong> to confirm and complete your Reservation process</span>
</p>

</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>';


if( $restaurant_information != '' ){
$email_template .='
<table border="0" cellpadding="0" cellspacing="0" class="kmTextBlock" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody class="kmTextBlockOuter">
<tr>
<td class="kmTextBlockInner" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; background-color:#F0670C;">
<table align="left" border="0" cellpadding="0" cellspacing="0" class="kmTextContentContainer" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody>
<tr>
<td class="kmTextContent" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: #FFF; font-family: Helvetica, Arial; font-size: 14px; line-height: 150%; text-align: left; padding-top:20px;padding-bottom:20px;padding-left:18px;color:#FFFFFF;padding-right:18px;">
<p style="margin: 0; padding-bottom: 0"><span style="font-size:26px;"><strong>Restaurant Information</strong></span></p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table border="0" cellpadding="0" cellspacing="0" class="kmTextBlock" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody class="kmTextBlockOuter">
<tr>
<td class="kmTextBlockInner" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; ">
<table align="left" border="0" cellpadding="0" cellspacing="0" class="kmTextContentContainer" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody>
<tr>
<td class="kmTextContent" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: #000000; font-family: Helvetica, Arial; font-size: 14px; line-height: 150%; text-align: left; padding-top:9px;padding-bottom:9px;padding-left:18px;padding-right:18px;">'
.$restaurant_information.
'</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>';
} // $restaurant_information

$email_template .='
<table border="0" cellpadding="0" cellspacing="0" class="kmButtonBarBlock" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody class="kmButtonBarOuter">
<tr>
<td class="kmButtonBarInner" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding-top:9px;padding-bottom:9px;padding-left:9px;padding-right:9px;">
<table align="left" border="0" cellpadding="0" cellspacing="0" class="kmButtonBarContentContainer" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody>
<tr>
<td align="left" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding-left:9px;padding-right:9px;">
<table border="0" cellpadding="0" cellspacing="0" class="kmButtonBarContent" align="left" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; font-family: Helvetica, Arial">
<tbody>
<tr>
<td align="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<table align="left" border="0" cellpadding="0" cellspacing="0" class="" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody>
<tr>
<td align="center" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding-right:10px;">
<a href="http://facebook.com/" target="_blank" style="word-wrap: break-word; color: #0000CD; font-weight: normal; text-decoration: underline"><img src="https://d3k81ch9hvuctc.cloudfront.net/assets/email/buttons/default/facebook_48.png" alt="Button Text" class="kmButtonBlockIcon" width="48" style="border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; width:48px; max-width:48px; display:block;" /></a>
</td>
</tr>
</tbody>
</table>
<table align="left" border="0" cellpadding="0" cellspacing="0" class="" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody>
<tr>
<td align="center" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding-right:10px;">
<a href="http://google.com/" target="_blank" style="word-wrap: break-word; color: #0000CD; font-weight: normal; text-decoration: underline"><img src="https://d3k81ch9hvuctc.cloudfront.net/assets/email/buttons/default/google_plus_48.png" alt="Custom" class="kmButtonBlockIcon" width="48" style="border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; width:48px; max-width:48px; display:block;" /></a>
</td>
</tr>
</tbody>
</table>
<table align="left" border="0" cellpadding="0" cellspacing="0" class="" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody>
<tr>
<td align="center" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding-right:10px;">
<a href="http://linkedin.com/" target="_blank" style="word-wrap: break-word; color: #0000CD; font-weight: normal; text-decoration: underline"><img src="https://d3k81ch9hvuctc.cloudfront.net/assets/email/buttons/default/linkedin_48.png" alt="Custom" class="kmButtonBlockIcon" width="48" style="border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; width:48px; max-width:48px; display:block;" /></a>
</td>
</tr>
</tbody>
</table>
<table align="left" border="0" cellpadding="0" cellspacing="0" class="" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody>
<tr>
<td align="center" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding-right:10px;">
<a href="http://pinterest.com/" target="_blank" style="word-wrap: break-word; color: #0000CD; font-weight: normal; text-decoration: underline"><img src="https://d3k81ch9hvuctc.cloudfront.net/assets/email/buttons/default/pinterest_48.png" alt="Custom" class="kmButtonBlockIcon" width="48" style="border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; width:48px; max-width:48px; display:block;" /></a>
</td>
</tr>
</tbody>
</table>
<table align="left" border="0" cellpadding="0" cellspacing="0" class="" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody>
<tr>
<td align="center" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding-right:10px;">
<a href="http://twitter.com/" target="_blank" style="word-wrap: break-word; color: #0000CD; font-weight: normal; text-decoration: underline"><img src="https://d3k81ch9hvuctc.cloudfront.net/assets/email/buttons/default/twitter_48.png" alt="Custom" class="kmButtonBlockIcon" width="48" style="border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; width:48px; max-width:48px; display:block;" /></a>
</td>
</tr>
</tbody>
</table>
<table align="left" border="0" cellpadding="0" cellspacing="0" class="" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody>
<tr>
<td align="center" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; ">
<a href="http://youtube.com/" target="_blank" style="word-wrap: break-word; color: #0000CD; font-weight: normal; text-decoration: underline"><img src="https://d3k81ch9hvuctc.cloudfront.net/assets/email/buttons/default/youtube_48.png" alt="Custom" class="kmButtonBlockIcon" width="48" style="border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; width:48px; max-width:48px; display:block;" /></a>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>';

if( $restaurant_policies != '' ){
$email_template .='
<table border="0" cellpadding="0" cellspacing="0" class="kmTextBlock" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody class="kmTextBlockOuter">
<tr>
<td class="kmTextBlockInner" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; background-color:#F0670C;">
<table align="left" border="0" cellpadding="0" cellspacing="0" class="kmTextContentContainer" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody>
<tr>
<td class="kmTextContent" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: #FFF; font-family: Helvetica, Arial; font-size: 14px; line-height: 150%; text-align: left; padding-top:20px;padding-bottom:20px;padding-left:18px;color:#FFFFFF;padding-right:18px;">
<p style="margin: 0; padding-bottom: 0"><span style="font-size:26px;"><strong>Restaurant Policies</strong></span></p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table border="0" cellpadding="0" cellspacing="0" class="kmTextBlock" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody class="kmTextBlockOuter">
<tr>
<td class="kmTextBlockInner" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; ">
<table align="left" border="0" cellpadding="0" cellspacing="0" class="kmTextContentContainer" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody>
<tr>
<td class="kmTextContent" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: #000000; font-family: Helvetica, Arial; font-size: 14px; line-height: 150%; text-align: left; padding-top:9px;padding-bottom:9px;padding-left:18px;padding-right:18px;">'
.$restaurant_policies.
'</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>';
}

$email_template .='
<table border="0" cellpadding="0" cellspacing="0" class="kmTextBlock" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody class="kmTextBlockOuter">
<tr>
<td class="kmTextBlockInner" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; background-color:#F0670C;">
<table align="left" border="0" cellpadding="0" cellspacing="0" class="kmTextContentContainer" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
<tbody>
<tr>
<td class="kmTextContent" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: #FFF; font-family: Helvetica, Arial; font-size: 14px; line-height: 150%; text-align: left; padding-top:9px;padding-bottom:9px;padding-left:18px;color:#FFFFFF;padding-right:18px;">
'.$restaurant_footer.'       
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</center>
</body>
</html>
';


/*========================================================
	2.	PLAIN TEXT
========================================================*/

$text_email .=' Restaurant Information \n';
$text_email .= $restaurant_address .' \n';
$text_email .= $restaurant_city.', '.$restaurant_state.' '.zipcode.' \n';
$text_email .= 'Tel : '.$restaurant_phone.' , Fax : '.$restaurant_fax.' \n';
$text_email .= 'Email : '.$restaurant_email.' \n';
$text_email .= 'Web : '.$restaurant_website.' \n';
$text_email .= 'Special Offer : '.$restaurant_offer_link.' \n';
$text_email .= 'Reservation : '.$restaurant_reservation_link.' \n';
$text_email .= 'Facebook : '.$restaurant_facebook.' \n';
$text_email .= 'Twiiter : '.$restaurant_twitter.' \n';
$text_email .= 'Google : '.$restaurant_google.' \n';
$text_email .= 'LinkedIn : '.$restaurant_linkedin.' \n';
$text_email .= 'Pinterest : '.$restaurant_pinterest.' \n';
$text_email .= 'Youtube : '.$restaurant_youtube.' \n\n';


$text_email .=' Your Reservation \n';
$text_email .= 'Name : '.$name.' \n';
$text_email .= 'Email : '.$email.' \n';
$text_email .= 'Phone : '.$phone.' \n';
$text_email .= 'Message : '.$message.' \n';
$text_email .= 'Table : '.$table.' \n';
$text_email .= 'Type of table : '.$type_of_table.' \n';
$text_email .= 'Persons : '.$persons.' \n';
$text_email .= 'lunch : '.$lunch.' \n';
$text_email .= 'date : '.$date.' \n';
$text_email .= 'time : '.$time.' \n\n';

if( $restaurant_message != '' ){
	$text_email .= 	$restaurant_message;
} 

$text_email .= 'Please go to this link to confirm your reservation process : '.$confirmation_link.' \n\n';
// if( $restaurant_message != '' ){


?>