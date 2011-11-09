<!DOCTYPE html 
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>Adding calendar events</title>
    <style>
    body {
      font-family: Verdana;      
    }
    li {
      border-bottom: solid black 1px;      
      margin: 10px; 
      padding: 2px; 
      width: auto;
      padding-bottom: 20px;
    }
    h2 {
      color: red; 
      text-decoration: none;  
    }
    span.attr {
      font-weight: bolder;  
    }
    </style>    
  </head>
  <body>
    <h1>Add Event</h1>
<?php
      // load classes
      require_once 'Zend/Loader.php';
      Zend_Loader::loadClass('Zend_Gdata');
      Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
      Zend_Loader::loadClass('Zend_Gdata_Calendar');
      Zend_Loader::loadClass('Zend_Http_Client');
      
      // connect to service
      require_once 'config.php';
      $gcal = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
      $client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, $gcal);
      $gcal = new Zend_Gdata_Calendar($client);
      

      // construct event object
      // save to server      
	  $arr=array(
	  array("nothing", "miehet", "miehet", "naiset", "naiset"),
	  array("nothing", "Peltola A4", "Eira B9", "Myllymä C13", "Linna E21"),
	  array("nothing", "Peltola E25", "Paukkonen E24", "Nilakin B7", "Toivonen D20"),
	  array("Turpeinen A2", "nothing", "Mettinen C14", "Saarainen A1", "nothing"),
	  array("Meunier B10", "Lehtinen A3", "Nysten B8", "Eira B9", "Nyroos B6"),
	  array("nothing", "Paaso B5", "Söströ29", "Itkonen C15", "Peippo C16"),
	  array("nothing", "Peltola A4", "Eira B9", "Myllymäki C13", "Linna E21"),
	  array("nothing", "Peltola E25", "Paukkonen E24", "Nilakin B7", "Toivonen D20"),
	  array("Turpeinen A2", "nothing", "Mettinen C14", "Saarainen A1", "nothing"),
	  array("Meunier B10", "Lehtinen A3", "Nysten B8", "Eira B9", "Nyroos B6"),
	  array("nothing", "Paaso B5", "Söderström S29", "Itkonen C15", "Peippo C16"),
	  array("nothing", "nothing", "nothing", "nothing", "nothing")
	  );
	 if (date("w") == 0) { 
     $adjuster = 6; 
     } 
     else { 
     $adjuster = date("w") - 1; 
     }
	 $isoWeekStartDate = date('Y-m-d', strtotime(date('o-\\WW', $time)));
     echo $isoWeekStartDate;
     $startDate = date('Y-m-d', strtotime('-' .$adjuster. ' days')); 
     $startDate = strtotime('-' .$adjuster. ' days'); 
     $endDate = strtotime ( '+7 days' , strtotime ( $startDate ) ) ;
     $endDate = date ( 'Y-m-d' , $endDate ); 
      try {
      	for ($i=0; $i<7; $i++) {
      		for ($j=0; $j<4;$j++) {
		        $event = $gcal->newEventEntry();        
		        $event->title = $gcal->newTitle($arr[$i][$j]);        
		        $when = $gcal->newWhen();
		        $when->startTime = date($startDate, strtotime('+' .$i. ' days'));
			print(date($startDate, strtotime('+' .$i. ' days'))."<br/>");
			echo "\$i=".$i." and \$j=".$j."<br/>";
		        $when->endTime = date(date($startDate, strtotime('+' .$i. ' days')), strtotime('+1 hours'));
			print(date(date($startDate, strtotime('+' .$i. ' days')), strtotime('+1 hours'))."<br/>");
		        $event->when = array($when);        
		        #$gcal->insertEvent($event);
			}
		}   
      } catch (Zend_Gdata_App_Exception $e) {
        echo "Error: " . $e->getResponse();
      }
      echo 'Event successfully added!'; 
    ?>
  </body>
</html>     