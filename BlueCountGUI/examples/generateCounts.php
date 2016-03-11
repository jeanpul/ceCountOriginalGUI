<?php

ini_set('display_errors', true);

// DateOps de BluePHP
function addTime($timestamp, $timestep)
{
  return mktime((int) date('H', $timestamp) + $timestep['hour'], // hour
		(int) date('i', $timestamp) + $timestep['minute'], // minute
		(int) date('s', $timestamp) + $timestep['second'], // second
		(int) date('m', $timestamp) + $timestep['month'],  // month
		(int) date('d', $timestamp) + $timestep['day'], // day
		(int) date('Y', $timestamp) + $timestep['year']); // year
}

// tiens compte des horaires d'ouverture et de fermeture
function checkTimeStamp($timestamp, $openingTime, $closingTime)
{
  $openingTime = mktime((int) date('H', $openingTime),
			(int) date('i', $openingTime),
			(int) date('s', $openingTime),
			(int) date('m', $timestamp),
			(int) date('d', $timestamp),
			(int) date('Y', $timestamp));

  $closingTime = mktime((int) date('H', $closingTime),
			(int) date('i', $closingTime),
			(int) date('s', $closingTime),
			(int) date('m', $timestamp),
			(int) date('d', $timestamp),
			(int) date('Y', $timestamp));

  if($timestamp >= $openingTime and
     $timestamp <= $closingTime)
    {
      return true;
    }

  return false;
}

function setOpeningNextDay($timestamp, $openingTime)
{
  $timeStepDay = array ( "hour" => 0, "minute" => 0, "second" => 0, "month" => 0, "day" => 1, "year" => 0 );
  $timestamp = addTime($timestamp, $timeStepDay);
  return mktime((int) date('H', $openingTime),
		(int) date('i', $openingTime),
		(int) date('s', $openingTime),
		(int) date('m', $timestamp),
		(int) date('d', $timestamp),
		(int) date('Y', $timestamp));  
}

function getSQLInsert($id, $ts, $v)
{
  $str = "";
  $str .= "INSERT INTO counting VALUES(\"$id\",";
  $str .= $v . ",";
  $str .= "\"" . strftime("%Y-%m-%d %H:%M:%S", $ts) . "\",";
  $str .= "0);\n";
  return $str;
}

function getValue($variable)
{
  return mt_rand($variable["MIN"], $variable["MAX"]);
}

/**
 * Pour éviter de stocker trop de compteurs inutiles	
 */
$opening = "09:00:00";
$closing = "18:00:00";

$openingTime = strtotime($opening);
$closingTime = strtotime($closing);

/**
 * Période à traiter
 */
$dateStart = "2007-08-01 $opening";
$dateEnd = "2007-08-07 $closing";

/**
 * Pour faciliter les calculs
 * sur les dates, passage en secondes
 */
$timeStart = strtotime($dateStart);
$timeEnd = strtotime($dateEnd);

/**
 * Variable utilise pour l'increment
 */
$timeStep = array ( "hour" => 0, "minute" => 1, "second" => 0, "month" => 0, "day" => 0, "year" => 0 );

$Entrees = array ( "MIN" => 6, "MAX" => 50 );
$Sorties = array ( "MIN" => 6, "MAX" => 50 );

/**
 * Informations sur les parametres
 */
echo "-- date de début = $dateStart\n";
echo "-- date de fin = $dateEnd\n";
echo "-- heure d'ouverture = $opening\n";
echo "-- heure de fermeture = $closing\n";

$nbCounters = 19;

/**
 * C'est parti !
 */
while($timeStart < $timeEnd)
{
  if(checkTimeStamp($timeStart, $openingTime, $closingTime))
    {
      $j = 0;
      for($i = 0; $i < $nbCounters; ++$i)
	{
	  echo getSQLInsert($j, $timeStart, getValue($Entrees));
	  ++$j;
	  echo getSQLInsert($j, $timeStart, getValue($Sorties));
	  ++$j;
	}
    }
  else
    {
      $timeStart = setOpeningNextDay($timeStart, $openingTime);
    }
  $timeStart = addTime($timeStart, $timeStep);
}

?>
