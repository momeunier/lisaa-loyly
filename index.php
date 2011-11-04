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
    $client2 = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, $gcal);
    $gcal = new Zend_Gdata_Calendar($client);
    if (date("w") == 0) { 
     $adjuster = 6; 
    } 
    else { 
     $adjuster = date("w") - 1; 
    } 
    $startDate = date("Y-m-d", strtotime("-" .$adjuster. " days")); 
    $endDate = strtotime ( '+7 days' , strtotime ( $startDate ) ) ;
    $endDate = date ( 'Y-m-j' , $endDate );
    
    $query = $gcal->newEventQuery();
    $query->setUser('default');
    $query->setVisibility('private');
    $query->setProjection('basic');
    $query->setOrderby('starttime');
    $query->setSortOrder('ascending');
    $query->setMaxResults(50);
    echo $startDate . "<br/>" . $endDate . "<br/>" ;
    $query->setStartMin($startDate);
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
      $id = substr($event->id, strrpos($event->id, '/')+1);
#      echo $id ; 
      try {
      $gcal_detail = new Zend_Gdata_Calendar($client2);
      } catch (Zend_Gdata_App_Exception $e) {
       echo "Error: " . $e->getResponse();
      }
      $event_detail = $gcal_detail->getCalendarEventEntry('http://www.google.com/calendar/feeds/default/private/full/' . $id);
      $when = $event_detail->getWhen();
      $startTime = strtotime($when[0]->getStartTime());
      $sdate_dd = date('d', $startTime);
      $sdate_mm = date('m', $startTime);
      $sdate_yy = date('Y', $startTime);
      $sdate_hh = date('H', $startTime);
      $sdate_ii = date('i', $startTime);
      $endTime = strtotime($when[0]->getEndTime());
      $edate_hh = date('H', $endTime);
      $edate_ii = date('i', $endTime);      
      echo "<li>\n";
      echo "<strong>" . stripslashes($event->title) . "</strong>\n";
 #     echo stripslashes($event->summary) . " <br/>\n";
      echo " - " . $sdate_dd . "." . $sdate_mm . "." . $sdate_yy . " from " . $sdate_hh . "." . $sdate_ii . " to " . $edate_hh . "." . $edate_ii; 
     # echo "<a href=\"edit.php?id=$id\">edit</a> | ";
     # echo "<a href=\"delete.php?id=$id\">delete</a> <br/>\n";
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
<table>
<tr><td>
<td>
<table>
<tr><td>&nbsp;</td></tr>
<tr><td>17-18</td></tr>
<tr><td>18-19</td></tr>
<tr><td>19-20</td></tr>
<tr><td>20-21</td></tr>
<tr><td>21-22</td></tr>
</table>
</td>
<td>
<table>
<tr><td>Maanantai</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>Miehet</td></tr>
<tr><td>Miehet</td></tr>
<tr><td>Naiset</td></tr>
<tr><td>Naiset</td></tr>
</table>
</td>
<td>
<table>
<tr><td>Tiistai</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
</table>
</td>
<td>
<table>
<tr><td>Keskiviikko</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
</table>
</td>
<td>
<table>
<tr><td>Torstai</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
</table>
</td>
<td>
<table>
<tr><td>Perjantai</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
</table>
</td>
<td>
<table>
<tr><td>Lauantai</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
</table>
</td>
<td>
<table>
<tr><td>Suununtai</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
<tr><td>16-17</td></tr>
</table>
</td>
</tr>
</table>
<iframe src="https://www.google.com/calendar/embed?src=kartanonkaari22%40gmail.com&ctz=Europe/Helsinki" style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>

  </body>
</html>    
