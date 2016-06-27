//
try {
	importPackage(Packages.org.dcm4che2.tool.dcmmwl);
} catch(e) {
	logger.error(e)
}
/////////

	//------------------Get DWL values from DB------------------------
var dbConn = DatabaseConnectionFactory.createDatabaseConnection ('com.mysql.jdbc.Driver','jdbc:mysql://localhost:3306/NMIS','nucmed','nucmed');
var query = "SELECT DWL_ServerAET, DWL_ServerIP, DWL_ServerPort, DWL_OwnAET, DWL_OwnIP, DWL_SearchModality, DWL_RefreshTime, DWL_Trigger, DWL_LastRun FROM settings WHERE id = '1'";
var result = dbConn.executeCachedQuery( query );

result.next();
var ServerAET = result.getString(1)
var ServerIP = result.getString(2)
var ServerPort = result.getString(3)
var OwnAET = result.getString(4)
var OwnIP = result.getString(5)
var SearchModality = result.getString(6)
var dwlRefreshTime = result.getString(7)
var dwlTrigger = result.getString(8)
var dwlLastRun = result.getString(9)

result.close();
//-------------------------------------------------------------
//------Get Today-----------------------------------
var todaydateString = DateUtil.getCurrentDate('yyyyMMdd');
//---------------


try {
	var arg=new Array();
	arg[0]=ServerAET+ "@" + ServerIP+ ":" + ServerPort; //IWMCHW_DWL@192.168.220.21:1024";
	arg[1]="-L";
	arg[2]=OwnAET;//"HOTLAB";
	arg[3]="-mod="+SearchModality;//"-mod=NM";
	arg[4]="-date="+todaydateString;//"-date=20160509";
	arg[5]="-r";
	arg[6]="00401002";
	arg[7]="-r";
	arg[8]="00101020";
	arg[9]="-storexml";
	arg[10]="C:\\test";

} catch(e) {
	logger.error(e)
}


// Get date data as strings with formatting 'yyyy-MM-dd HH:mm:ss'
var strdwlLastRun = dwlLastRun
var strCurrentDate = DateUtil.getCurrentDate('yyyy-MM-dd HH:mm:ss')
//--------------Convert to Date variables-----------------
var Date1 = org.joda.time.format.DateTimeFormat.forPattern('yyyy-MM-dd HH:mm:ss').parseDateTime(dwlLastRun.toString());
var Date2 = org.joda.time.format.DateTimeFormat.forPattern('yyyy-MM-dd HH:mm:ss').parseDateTime(strCurrentDate.toString());

//-----------------------Subtract the 2 dates------------------------
var seconds = org.joda.time.Seconds.secondsBetween(Date1,Date2);
var SecondsSinceLastRun = seconds.getSeconds();

//logger.info('Manual Trigger = ' + dwlTrigger + '. Time since last query = '  + SecondsSinceLastRun + 's. Next run at ' + dwlRefreshTime +'s.');

//-----------------------------Time based DWL query--------------------------------------------------------------------------------
if (parseInt(SecondsSinceLastRun) >= parseInt(dwlRefreshTime)){
	//--Empty The DWL table-------------------
	var query = "TRUNCATE TABLE dicomworklist";
	var result2 = dbConn.executeUpdate( query );

	//-----------------------------------------
	//---------------------Execute Batch-------------------

try {
	//var test = DcmMWL.main(arg);
	//logger.info(test);
	var result = DcmMWL.main(arg);


} catch(e) {
	logger.error(e)
}


	logger.info('Time run DWL query  :  Next run in ' + dwlRefreshTime + 's.');
	//-------------------------------------------------------------

	//---------------------Update DB with the last DWL last run time ----------------------------


	var query = "UPDATE settings SET DWL_LastRun = '" + strCurrentDate +"'";

	var result3 = dbConn.executeUpdate( query );
	dbConn.close();
	//-------------------------------------------------------------
	}
//-----------------------------User initiated DWL query--------------------------------------------------------------------------------
else if (dwlTrigger == 'true') {
	//--Empty The DWL table-------------------
	var query = "TRUNCATE TABLE dicomworklist";
	var result2 = dbConn.executeUpdate( query );

	//-----------------------------------------
	//Run Batch

try {
	//var test = DcmMWL.main(arg);
	//logger.info(test);
	var result = DcmMWL.main(arg);


} catch(e) {
	logger.error(e)
}
	//---------------------Update DB with the last DWL last run time ----------------------------
	var query = "UPDATE settings SET DWL_LastRun = '" + strCurrentDate + "'," + "DWL_Trigger = False";
	var result3 = dbConn.executeUpdate( query );
	dbConn.close();
	logger.info('User initiated DWL query :  Next run in ' + dwlRefreshTime + 's.');

}
//-----------------------Do nothing and wait for either user or time Trigger------------------------------------------------------------
else{
	//logger.info("NotRun");
	}


//try {
//	importPackage(Packages.org.dcm4che2.tool.dcmmwl);
//} catch(e) {
//	logger.error(e)
//}
//
//
//
//try {
//	var arg=new Array();
//	arg[0]="IWMCHW_DWL@192.168.220.21:1024";
//	arg[1]="-L";
//	arg[2]="HOTLAB";
//	arg[3]="-mod=NM";
//	arg[4]="-date=20160509";
//	arg[5]="-r";
//	arg[6]="00401002";
//	arg[7]="-r";
//	arg[8]="00101020";
//	arg[9]="-storexml";
//	arg[10]="C:\\test";
//
//} catch(e) {
//	logger.error(e)
//}
//
//try {
//	//var test = DcmMWL.main(arg);
//	//logger.info(test);
//	var result = DcmMWL.main(arg);
//

//} catch(e) {
//	logger.error(e)
//}