<?php
  require_once('core.php');
  $aEvents = array();
  $iNow = time();
  
  $iStartOfThisMonth = $iTableStart = mktime(0, 0, 0, (int) date("n"), 1, (int) date("Y"));
  
  $iTableStart = addMonths($iStartOfThisMonth, 1);
  $iTableEnd = addMonths($iStartOfThisMonth, 2)+(60*60*24*7);
  $iEventsEnd = addMonths($iStartOfThisMonth, 4) - 1;
  
  $aFeeds = array( 
      'LECTIONARY'    => 'http://www.google.com/calendar/feeds/sdr6vocc24tsebm6l6dbrbn0do%40group.calendar.google.com/private-2eeb147af35a65bcd5ced9227187f318/full',
      'REGULAR_EVENT' => 'http://www.google.com/calendar/feeds/ebrbnedrrlti3900mjuo7gq7fo%40group.calendar.google.com/public/full',
      'ROTAS'         => 'http://www.google.com/calendar/feeds/9nlsntlte1u1dtpbie0h5i297g%40group.calendar.google.com/public/full',
      );
  
  if(isNetworkAvailable())
  {
    foreach($aFeeds as $sName => $sURL)
    {
      addEvents($sName, $sURL, $iTableStart, $iTableEnd, $aEvents);
    }
  }
  else
  {
    header('HTTP/1.1 404 Not Found', true, 404);
    exit;
  }
  
  ksort($aEvents);
  
  
  $aTest = $aEvents['01/03/2009'];
  //echo "\n<br><pre>\naTest  =" .var_export($aTest , TRUE)."</pre>";
  
  //echo "\n<br><pre>\naEvents =" .var_export($aEvents, TRUE)."</pre>";
  ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
  
  <?php
  include ('tableoutput.php');
  
  
  $aEvents = array();
  addEvents('EVENTS', CONST_GOOGLE_EVENT_FEED, $iTableStart, $iEventsEnd, $aEvents);
  ksort($aEvents);
  include ('eventoutput.php');
  
  
  
  exit;
  
  function addEvents($sName, $sURL, $iMin, $iMax, &$aEvents)
  {
    
    $sQ  = "?max-results=200&singleevents=true&orderby=starttime&";
    $sQ .= "start-min=".urlencode(date3339($iMin))."&";//2007-05-22T09%3A58%3A47-04%3A00
    $sQ .= "start-max=".urlencode(date3339($iMax));//2007-11-06T09%3A58%3A47-04%3A00
    //echo "\n<br><pre>\nsQ  =" .$sQ ."</pre>";

    
    $xmlDoc = new DOMDocument();
    $bTest = $xmlDoc->load($sURL.$sQ);
    if(!$bTest)
    {
      header('HTTP/1.1 404 Not Found', true, 404);
      exit; //not sure it should fail if one fails
      return;
    }
    
    $sXML = $xmlDoc->saveXML();
    $xpath = new DOMXpath($xmlDoc);
    
    $xpath->registerNamespace('xmlns','http://www.w3.org/2005/Atom' );
    $xpath->registerNamespace('openSearch', "http://a9.com/-/spec/opensearchrss/1.0/");
    $xpath->registerNamespace('batch', "http://schemas.google.com/gdata/batch");
    $xpath->registerNamespace('gCal', "http://schemas.google.com/gCal/2005");
    $xpath->registerNamespace('gd', "http://schemas.google.com/g/2005");
    
    $oEvents = $xpath->query("//xmlns:entry");
    
    if (!is_null($oEvents))
    {
      for($i = 0; $i < $oEvents->length; $i++)
      {
        $oEvent = $oEvents->item($i);
        $oTitle = $oEvent->getElementsByTagName ('title');
        $sTitle = $oTitle->item(0)->textContent;
        
        $oContent = $oEvent->getElementsByTagName('content');
        $sContent = $oContent->item(0)->textContent;
        
        $sWhere = $xpath->query("gd:where", $oEvent)->item(0)->getAttribute('valueString');
        
        $oWhen = $xpath->query("gd:when", $oEvent)->item(0);
        $sStart = $oWhen->getAttribute('startTime');
        //echo "\n<br><pre>\nsStart  =" .$sStart ."</pre>";
        $oStart = new DateTime($sStart);
        $oNow =  new DateTime($sStart);
        
        $sStartConved = $oStart->format('l jS \of F Y g:i A');
        
        $sEnd = $oWhen->getAttribute('endTime');
        $oEnd = new DateTime($sEnd);
        $sEndConved = $oEnd->format('l jS \of F Y g:i A');
        
        $iStart = $iStartKey = (int) $oStart->format('U');
        $iEnd = (int) $oEnd->format('U');
        
        if($iEnd >= $iMin && $iEnd <= $iMax)
        {
          $sDate = $oStart->format("Y/m/d");
          $aEvents[$sDate]['iDayTime'] = $oStart->format("U");
          //echo "\n<br><pre>\nsDate  =" .$sDate ."</pre>";
          
          if(!isset($aEvents[$sDate]['iTypeCount'][$sName]))
          {
            $aEvents[$sDate]['iTypeCount'][$sName] = 0;
          }
          $aEvents[$sDate]['iTypeCount'][$sName] ++;
          
          if($sName == 'LECTIONARY')// && $oStart->format("D") == 'Sun')
          {
            $aEvents[$sDate]['sDayName'] = $sWhere;
          }
          else
          {
            $aEvents[$sDate]['aData'][$iStartKey][$sName][] = array(
              'sTitle'    => $sTitle,
              'sWhere'    => $sWhere,
              'sContent'  => $sContent,
              //'sStart'    => $sStartConved,
              //'sEnd'      => $sEndConved,
              'iStart'    => $iStart,
              'iEnd'      => $iEnd,
            );
          }
        }
      }
    }
  }
  
  function renderEvents($aEvents)
  {
    ksort($aEvents);
    $aSlides = array();
    foreach($aEvents as $aEvent)
    {
      $sSlide = $aEvent['sTitle'].($aEvent['sStart']?("\n".$aEvent['sStart']):"")."\n\n".($aEvent['sWhere']?($aEvent['sWhere']."\n"):'').$aEvent['sContent'];
      $aSlides[] = $sSlide;
    }
    
    $sSlides = join("\n---\n", $aSlides);
    echo $sSlides;
  }
