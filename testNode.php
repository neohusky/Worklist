<?php
/**
 * Created by PhpStorm.
 * User: nucmed
 * Date: 27/06/2016
 * Time: 1:05 PM
 */
require_once('config.php');
require_once('functions.php');


$DWLXMLimported_dir = dirname(__FILE__)."/DWLxml/imported";
$DWLXML_dir = dirname(__FILE__)."/DWLxml";




queryDWL();




ClearDWLtable($conn);

//Step through files in a folder


$files = glob("$DWLXML_dir/*.{xml}", GLOB_BRACE);//separate file extensions with commas
foreach($files as $file) {
    ImportXML($file,$conn);


    //unlink($file);//Delete imported file
    rename($file, $DWLXMLimported_dir);
};






function ImportXML ($file,$conn){
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
    $ReasonForTheRequestedProcedure = clean($ReasonForTheRequestedProcedure);

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

    $sql = "INSERT INTO dicomworklist (StudyInstanceUID, AccessionNumber, ReferringPhysiciansName, PatientID, PatientName, PatientDOB, PatientSex, PatientSize, PatientWeight, MedicalAlert, RequestedProcedureDescription, CurrentPatientLocation, Modality, ScheduledProcedureStepDescription, ScheduledProcedureStartDate,ReasonForTheRequestedProcedure)
VALUES ('$StudyInstanceUID','$AccessionNumber','$ReferringPhysiciansName','$PatientID','$PatientName','$PatientDOB','$PatientSex','$PatientSize','$PatientWeight','$MedicalAlert','$RequestedProcedureDescription','$CurrentPatientLocation','$Modality','$ScheduledProcedureStepDescription','$ScheduledProcedureStartDate','$ReasonForTheRequestedProcedure')";

// Check connection
    if (mysqli_connect_errno($conn))
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    mysqli_query($conn, $sql) or die(mysqli_error());

    echo "xml imported\n";

}

function ClearDWLtable($conn){
// Check connection
    if (mysqli_connect_errno($conn))
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    $sql = "TRUNCATE TABLE dicomworklist";
    mysqli_query($conn, $sql) or die(mysqli_error());

}


/*INSERT INTO dicomworklist (StudyInstanceUID, AccessionNumber, ReferringPhysiciansName, PatientID, PatientName, PatientDOB, PatientSex, PatientSize, PatientWeight, MedicalAlert, RequestedProcedureDescription, CurrentPatientLocation, Modality, ScheduledProcedureStepDescription, ScheduledProcedureStartDate,ReasonForTheRequestedProcedure)
VALUES (${StudyInstanceUID},${AccessionNumber},${ReferringPhysiciansName},${PatientID},REPLACE(${PatientName},"^",", "),${PatientDOB},${PatientSex},${PatientSize},${PatientWeight},${MedicalAlert},${RequestedProcedureDescription},${CurrentPatientLocation},${Modality},${ScheduledProcedureStepDescription},${ScheduledProcedureStartDate},${ReasonForTheRequestedProcedure})*/

function queryDWL(){
    global $DWLXML_dir;
    $dcmexec = "dcm4che-2.0/dcm4che-2.0.18-bin/dcm4che-2.0.18/bin/dcmmwl";

//Original query string
//$dcmquery ="IWMCHW_DWL@192.168.0.193:1024 -L HOTLAB -mod NM -date 20160712 -r 00401002 -storexml /Users/theokitsos/PhpstormProjects/Worklist/xml";

//Query variables
    $ServerAET = "IWMCHW_DWL";
    $ServerIP = "192.168.220.21";
    $ServerPort = "1024";
    $OwnAET = "HOTLAB";
    $OwnIP = "192.168.0.100";
    $OwnPort = "104";
    $SearchModality = "NM";
    $StudyDate = "20160710-20160713";
    //$XML_dir = "/Users/theokitsos/PhpstormProjects/Worklist/xml";


    $dcmquery ="$ServerAET@$ServerIP:$ServerPort -L $OwnAET@$OwnIP:$OwnPort -mod $SearchModality -date $StudyDate -r 00401002 -r 00101020 -storexml $DWLXML_dir";


//$output = shell_exec('dcm4che-2.0/dcm4che-2.0.18-bin/dcm4che-2.0.18/bin/dcmmwl IWMCHW_DWL@192.168.0.193:1024 -L HOTLAB -mod NM -date 20160712 -r 00401002 -storexml /Users/theokitsos/PhpstormProjects/Worklist/xml');
    $output = shell_exec($dcmexec." ".$dcmquery);

    echo "<pre>$output</pre>";
}

function clean($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}