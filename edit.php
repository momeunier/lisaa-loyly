<!DOCTYPE html 
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>Updating calendar events</title>
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
    <h1>Edit Event</h1>
    <?php
    // load classes
    require_once 'Zend/Loader.php';
    Zend_Loader::loadClass('Zend_Gdata');
    Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
    Zend_Loader::loadClass('Zend_Gdata_Calendar');
    Zend_Loader::loadClass('Zend_Http_Client');
    
    // connect to service
    $gcal = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
    $user = "kartanonkaari22@gmail.com";
    $pass = "Dominos17";
    $client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, $gcal);
    $gcal = new Zend_Gdata_Calendar($client);
     
    // get event details
    if (!isset($_POST['submit'])) {
      if (isset($_GET['id'])) {
        try {          
          $event = $gcal->getCalendarEventEntry('http://www.google.com/calendar/feeds/default/private/full/' . $_GET['id']);
        } catch (Zend_Gdata_App_Exception $e) {
          echo "Error: " . $e->getResponse();
        }
      } else {
          die('ERROR: No event ID available!');  
      }  
      
      // format data into human-readable form
      // populate a Web form with the record
      $title = $event->title;
      $when = $event->getWhen();
      $startTime = strtotime($when[0]->getStartTime());
      $sdate_dd = date('d', $startTime);
      $sdate_mm = date('m', $startTime);
      $sdate_yy = date('Y', $startTime);
      $sdate_hh = date('H', $startTime);
      $sdate_ii = date('i', $startTime);
      $endTime = strtotime($when[0]->getEndTime());
      $edate_dd = date('d', $endTime);
      $edate_mm = date('m', $endTime);
      $edate_yy = date('Y', $endTime);
      $edate_hh = date('H', $endTime);
      $edate_ii = date('i', $endTime);      
    ?>
    <form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
      <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
      Event title: <br/>
      <input name="title" type="text" size="15" value="<?php echo $title; ?>"/><p/>
      Start date (dd/mm/yyyy): <br/>
      <input name="sdate_dd" type="text" size="2" value="<?php echo $sdate_dd; ?>" />
      <input name="sdate_mm" type="text" size="2" value="<?php echo $sdate_mm; ?>"/>
      <input name="sdate_yy" type="text" size="4" value="<?php echo $sdate_yy; ?>"/><p/>
      Start time (hh:mm): <br/>
      <input name="sdate_hh" type="text" size="2" value="<?php echo $sdate_hh; ?>"/> 
      <input name="sdate_ii" type="text" size="2" value="<?php echo $sdate_ii; ?>"/><br/>
      End  date (dd/mm/yyyy): <br/>
      <input name="edate_dd" type="text" size="2" value="<?php echo $edate_dd; ?>" />
      <input name="edate_mm" type="text" size="2" value="<?php echo $edate_mm; ?>" />
      <input name="edate_yy" type="text" size="4" value="<?php echo $edate_yy; ?>" /><p/>
      End  time (hh:mm): <br/>
      <input name="edate_hh" type="text" size="2" value="<?php echo $edate_hh; ?>"  /> 
      <input name="edate_ii" type="text" size="2" value="<?php echo $edate_ii; ?>"  /><br/>
      <input name="submit" type="submit" value="Save" />      
    </form>    
    <?php              
    } else {
      // if form submitted
      // validate input
      if (empty($_POST['id'])) {
        die('ERROR: Missing event ID');
      } 
      
      if (empty($_POST['title'])) {
        die('ERROR: Missing title');
      } 
      
      if (!checkdate($_POST['sdate_mm'], $_POST['sdate_dd'], $_POST['sdate_yy'])) {
        die('ERROR: Invalid start date/time');        
      }
      
      if (!checkdate($_POST['edate_mm'], $_POST['edate_dd'], $_POST['edate_yy'])) {
        die('ERROR: Invalid end date/time');        
      }     
      
      $title = htmlentities($_POST['title']);
      $start = date(DATE_ATOM, mktime($_POST['sdate_hh'], $_POST['sdate_ii'], 0, $_POST['sdate_mm'], $_POST['sdate_dd'], $_POST['sdate_yy']));
      $end = date(DATE_ATOM, mktime($_POST['edate_hh'], $_POST['edate_ii'], 0, $_POST['edate_mm'], $_POST['edate_dd'], $_POST['edate_yy']));
      
      // get existing event record
      // update event attributes
      // save changes to server
      try {
        $event = $gcal->getCalendarEventEntry('http://www.google.com/calendar/feeds/default/private/full/' . $_POST['id']);
        $event->title = $gcal->newTitle($title); 
        $when = $gcal->newWhen();
        $when->startTime = $start;
        $when->endTime = $end;
        $event->when = array($when);        
        $event->save();   
      } catch (Zend_Gdata_App_Exception $e) {
        die("Error: " . $e->getResponse());
      }
      echo 'Event successfully modified!';    
    }    
    ?>
  </body>
</html>     
