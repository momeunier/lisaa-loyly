<!DOCTYPE html 
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>Deleting calendar events</title>
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
    <h1>Delete Event</h1>
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
      
    // if event ID is present
    // get event object from feed
    // delete event  
    if (isset($_GET['id'])) {
      try {          
          $event = $gcal->getCalendarEventEntry('http://www.google.com/calendar/feeds/default/private/full/' . $_GET['id']);
          $event->delete();
      } catch (Zend_Gdata_App_Exception $e) {
          echo "Error: " . $e->getResponse();
      }        
      echo 'Event successfully deleted!';  
    } else {
      echo 'No event ID available';  
    }
    ?>
  </body>
</html>
