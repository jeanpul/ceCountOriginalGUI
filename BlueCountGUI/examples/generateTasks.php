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

function getTask($ts, $ft, $fq)
{
  $str = "";
  $str .= "BCTask_listAutomaticComputationTask MINUTE \"" . strftime("%Y-%m-%d %H:%M:00",$ts) . "\" $ft $fq";
  return $str;
}

/**
 * Pour �viter de stocker trop de compteurs inutiles	
 */
$opening = "09:00:00";
$closing = "18:00:00";

$openingTime = strtotime($opening);
$closingTime = strtotime($closing);

/**
 * P�riode � traiter
 */
$dateStart = "2007-08-01 $opening";
$dateEnd = "2007-08-01 $closing";

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


/**
 * Informations sur les parametres
 */
echo "# date de d�but = $dateStart\n";
echo "# date de fin = $dateEnd\n";
echo "# heure d'ouverture = $opening\n";
echo "# heure de fermeture = $closing\n";

/**
 * C'est parti !
 */
while($timeStart < $timeEnd)
{
  if(checkTimeStamp($timeStart, $openingTime, $closingTime))
    {
      $str = getTask($timeStart, $argv[1], $argv[2]);
      echo $str . "\n";
      echo "echo $str\n";
    }
  else
    {
      $timeStart = setOpeningNextDay($timeStart, $openingTime);
    }
  $timeStart = addTime($timeStart, $timeStep);
}

?>