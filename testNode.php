<?php
/**
 * Created by PhpStorm.
 * User: nucmed
 * Date: 27/06/2016
 * Time: 1:05 PM
 */

$file = 'dwl.xml';


$xml=simplexml_load_file($file) or die("Error: Cannot create object");


$AccessionNumber = $xml->attr[1];
$ReferringPhysiciansName =  $xml->attr[2];
$PatientName =  $xml->attr[3];
$PatientID = $xml->attr[4];
$PatientDOB = $xml->attr[5];
$PatientSex = $xml->attr[6];
$PatientSize = $xml->attr[7];
$PatientWeight =  $xml->attr[8];
$MedicalAlert = $xml->attr[9];

$StudyInstanceUID = $xml->attr[12];
$RequestedProcedureDescription = $xml->attr[15];
$CurrentPatientLocation = $xml->attr[19];

$Modality = $xml->attr[21]->item->attr[0];
$ScheduledProcedureStepDescription = $xml->attr[21]->item->attr[6] ;
$ScheduledProcedureStartDate =  $xml->attr[21]->item->attr[3];

$ReasonForTheRequestedProcedure = $xml->attr[23];

echo "AccessionNumber: " . $AccessionNumber . "\n";
echo "ReferringPhysiciansName: ".$ReferringPhysiciansName . "\n";
echo "PatientName: " .$PatientName . "\n";
echo "PatientID: " .$PatientID . "\n";
echo "PatientDOB: ".$PatientDOB . "\n";
echo "PatientSex: ".$PatientSex . "\n";
echo "PatientSize: " .$PatientSize . "\n";
echo "PatientWeight: " .$PatientWeight . "\n";
echo "MedicalAlert: " .$MedicalAlert . "\n";
echo "StudyInstanceUID: " .$StudyInstanceUID . "\n";
echo "RequestedProcedureDescription: " .$RequestedProcedureDescription . "\n";
echo "CurrentPatientLocation: " . $CurrentPatientLocation . "\n";
echo "Modality: ". $Modality . "\n";
echo "ScheduledProcedureStepDescription: " .$ScheduledProcedureStepDescription . "\n";
echo "ScheduledProcedureStartDate: " . $ScheduledProcedureStartDate . "\n";
echo "ReasonForTheRequestedProcedure: " . $ReasonForTheRequestedProcedure . "\n";

/*INSERT INTO dicomworklist (StudyInstanceUID, AccessionNumber, ReferringPhysiciansName, PatientID, PatientName, PatientDOB, PatientSex, PatientSize, PatientWeight, MedicalAlert, RequestedProcedureDescription, CurrentPatientLocation, Modality, ScheduledProcedureStepDescription, ScheduledProcedureStartDate,ReasonForTheRequestedProcedure)
VALUES (${StudyInstanceUID},${AccessionNumber},${ReferringPhysiciansName},${PatientID},REPLACE(${PatientName},"^",", "),${PatientDOB},${PatientSex},${PatientSize},${PatientWeight},${MedicalAlert},${RequestedProcedureDescription},${CurrentPatientLocation},${Modality},${ScheduledProcedureStepDescription},${ScheduledProcedureStartDate},${ReasonForTheRequestedProcedure})*/
