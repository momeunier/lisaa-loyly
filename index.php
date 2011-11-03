<!DOCTYPE html 
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>Listing calendar contents</title>
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
    <?php
    require_once 'Zend/Loader.php';
    Zend_Loader::loadClass('Zend_Gdata');
    Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
    Zend_Loader::loadClass('Zend_Gdata_Calendar');
    Zend_Loader::loadClass('Zend_Http_Client');
    
    require_once 'config.php';
    $gcal = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
    $client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, $gcal);
    $gcal = new Zend_Gdata_Calendar($client);
    
    $query = $gcal->newEventQuery();
    $query->setUser('default');
    $query->setVisibility('private');
    $query->setProjection('basic');
    $query->setOrderby('starttime');
    if(isset($_GET['q'])) {
      $query->setQuery($_GET['q']);      
    }
    
    try {
      $feed = $gcal->getCalendarEventFeed($query);
    } catch (Zend_Gdata_App_Exception $e) {
      echo "Error: " . $e->getResponse();
    }
    ?>
    <h1><?php echo $feed->title; ?></h1>
    <?php echo $feed->totalResults; ?> event(s) found.
    <p/>
    <ol>

    <?php        
    foreach ($feed as $event) {
      echo "<li>\n";
      echo "<h2>" . stripslashes($event->title) . "</h2>\n";
 #     echo stripslashes($event->summary) . " <br/>\n";
      $id = substr($event->id, strrpos($event->id, '/')+1);
      echo "<a href=\"edit.php?id=$id\">edit</a> | ";
      echo "<a href=\"delete.php?id=$id\">delete</a> <br/>\n";
      echo "</li>\n";
    }
    echo "</ul>";
    ?>
    </ol>
    <p/>
    <a href="add.php">Add a new event</a><p/>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
      Search for events containing:<br/>
      <input type="text" name="q" size="10"/><p/>
      <input type="submit" name="submit" value="Search"/>
    </form>
<br/>
<iframe src="https://www.google.com/calendar/embed?src=kartanonkaari22%40gmail.com&ctz=Europe/Helsinki" style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>

  </body>
</html>    
