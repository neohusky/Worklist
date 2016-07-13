<?php
/**
 * Created by PhpStorm.
 * User: theokitsos
 * Date: 23/11/2015
 * Time: 8:16 PM
 */
require_once('config.php');

function RemainingActivity($A0, $StartDate, $EndDate, $HalfLife) {
    $Start = strtotime($StartDate);
    $End= strtotime($EndDate);
    $interval  = abs($End - $Start);
    $minutes   = round($interval / 60);

    //echo $Time.'minutes';
    echo "Time elapsed " . $minutes . " minutes. ";
    $At= $A0 * exp((-log(2)/$HalfLife * $minutes));

    return $At;

}

function UpdateElutionActivity($conn,$id,$Activity,$Time){

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql=  "UPDATE eluates
		SET eluates.`Remaining activity` = $Activity, eluates.`Remaining activity calibration time` = '$Time'
		WHERE eluates.EluateID = $id";

    $retval2 = mysql_query( $sql, $conn );
    if(! $retval2 )
    {
        die('Could not update data: ' . mysql_error());
    }
    echo "Updated data successfully\n";
    mysql_close($conn);
}