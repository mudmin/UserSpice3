<?php

// Get user IP
	function getIP() {
	/*
	This function will try to find out if user is coming behind proxy server. Why is this important?
	If you have high traffic web site, it might happen that you receive lot of traffic
	from the same proxy server (like AOL). In that case, the script would count them all as 1 user.
	This function tryes to get real IP address.
	Note that getenv() function doesn't work when PHP is running as ISAPI module
	*/
		if (getenv('HTTP_CLIENT_IP')) {
			$ip = getenv('HTTP_CLIENT_IP');
		}
		elseif (getenv('HTTP_X_FORWARDED_FOR')) {
			$ip = getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_X_FORWARDED')) {
			$ip = getenv('HTTP_X_FORWARDED');
		}
		elseif (getenv('HTTP_FORWARDED_FOR')) {
			$ip = getenv('HTTP_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_FORWARDED')) {
			$ip = getenv('HTTP_FORWARDED');
		}
		else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

// Pie Chart for Page Permission Counts
function fetchUserjsonPIE() 
	{
	// Example query
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT COUNT(*) AS sum1,name
	FROM ".$db_table_prefix."permission_page_matches LEFT JOIN ".$db_table_prefix."permissions ON permission_id = ".$db_table_prefix."permissions.id  GROUP BY permission_id");
	$stmt->execute();
	$stmt->bind_result($sum1, $name);
	while ($stmt->fetch())
		{
		$row[] = array('sum1' => $sum1, 'name' => $name);
		}
	$stmt->close();
	return ($row);
	}

// Bar Chart for Login Counts
function fetchUserjsonLG2() 
	{
	// Example query
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT COUNT(*) AS sum1,audit_timestamp
	FROM ".$db_table_prefix."audit WHERE audit_eventcode = 1 GROUP BY MINUTE(FROM_UNIXTIME(audit_timestamp)) ORDER BY audit_timestamp DESC LIMIT 100");
	$stmt->execute();
	$stmt->bind_result($sum1, $name);
	while ($stmt->fetch())
		{
		$row[] = array('sum1' => $sum1, 'audit_timestamp' => $name);
		}
	$stmt->close();
	return ($row);
	}

// Bar Chart for Signup Counts
function fetchUserjsonLG()
	{
	// Example query
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT COUNT(*) AS sum1,sign_up_stamp	FROM ".$db_table_prefix."users GROUP BY DAY(FROM_UNIXTIME(sign_up_stamp))ORDER BY sign_up_stamp DESC");
	$stmt->execute();
	$stmt->bind_result($sum1, $name);
	while ($stmt->fetch())
		{
		$row[] = array('sum1' => $sum1, 'sign_up_stamp' => $name);
		}
	$stmt->close();
	return ($row);
	}


// complex query gets audit 
	function fetchAllLatest($userid,$start,$end,$eventcode)
		{
		global $mysqli,$db_table_prefix;
		$stmt = $mysqli->prepare("SELECT
									id,
									display_name,
									audit_id,
									audit_userid,
									audit_userip,
									audit_othus,
									audit_eventcode,
									audit_action,
									audit_itemid,
									audit_timestamp
			FROM ".$db_table_prefix."audit LEFT JOIN ".$db_table_prefix."users ON audit_userid = id
									WHERE 
									audit_userid != ? 
									AND audit_timestamp >= ? 
									AND audit_timestamp <= ? 
									AND audit_eventcode = ?
									ORDER BY audit_id DESC 
			");
			$stmt->bind_param("iiii",$userid,$start,$end,$eventcode);
			$stmt->execute();
			$stmt->store_result();
			$num_returns = $stmt->num_rows;
			if ($num_returns > 0)
					{
					$stmt->bind_result($userid, $displayname,$auditid, $audituserid, $ip, $othus, $event, $action, $itemid, $timestamp);
					while ($stmt->fetch())
						{
						$row[] = array('id' => $userid,'display_name' => $displayname,'audit_id' => $auditid, 'audit_userid' => $audituserid,  'audit_userip' => $ip, 'audit_othus' => $othus, 'audit_eventcode' => $event, 'audit_action' => $action, 'audit_itemid' => $itemid, 'audit_timestamp' => $timestamp);
						}
					}
				else
					{
					$stmt->close();
					return false;
					}
			$stmt->close();
			return ($row);

		}

// simplest count function
function countStuff($what)
	{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT COUNT(*) AS sum1 FROM ".$db_table_prefix.$what);
	$stmt->execute();
	$stmt->store_result();
	$num_returns = $stmt->num_rows;
	if ($num_returns > 0)
			{
			$stmt->bind_result($sum1);
			while ($stmt->fetch())
				{
				$row = array('sum1' => $sum1);
				}
			return $row;
			}
		else
			{
			return false;
			}
	}

	
// greedy count function with a modifier
function countLoginsSince($eventcode,$since)
	{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT COUNT(*) AS sum1 FROM ".$db_table_prefix."audit WHERE audit_eventcode = ? AND audit_timestamp > ?");
	$stmt->bind_param("ii", $eventcode,$since);
	$stmt->execute();
	$stmt->store_result();
	$num_returns = $stmt->num_rows;
	if ($num_returns > 0)
			{
			$stmt->bind_result($sum1);
			while ($stmt->fetch())
				{
				$row = array('sum1' => $sum1);
				}
			return $row;
			}
		else
			{
			return false;
			}
	}
	
// handy ago() function for UserSpice timestamps
function ago($time) { 
    $timediff=time()-$time; 
    $days=intval($timediff/86400);
    $remain=$timediff%86400;
    $hours=intval($remain/3600);
    $remain=$remain%3600;
    $mins=intval($remain/60);
    $secs=$remain%60;

    if ($secs>=0) $timestring = $secs."s";//"0m".
    if ($mins>0) $timestring = $mins."m";//.$secs."s";
    if ($hours>0) $timestring = $hours."h";//.$mins."m";
    if ($days>0) $timestring = $days."d";//.$hours."h";

    return $timestring; 
}

//Retrieve information for admin audit
	function fetchAllAudit()
		{
		global $mysqli,$db_table_prefix;
		$stmt = $mysqli->prepare("SELECT 
				id,
				display_name,
				audit_id,
				audit_userid,
				audit_userip,
				audit_othus,
				audit_eventcode,
				audit_action,
				audit_itemid,
				audit_timestamp
				
			FROM ".$db_table_prefix."audit LEFT JOIN ".$db_table_prefix."users ON audit_userid = id ORDER BY audit_id DESC") ; 
			$stmt->execute();
			$stmt->bind_result($userid, $displayname,$auditid, $audituserid, $ip, $othus, $event, $action, $itemid, $timestamp);

			while ($stmt->fetch()){
				$row[] = array('id' => $userid,'display_name' => $displayname,'audit_id' => $auditid, 'audit_userid' => $audituserid,  'audit_userip' => $ip, 'audit_othus' => $othus, 'audit_eventcode' => $event, 'audit_action' => $action, 'audit_itemid' => $itemid, 'audit_timestamp' => $timestamp);
			}
			$stmt->close();
			return ($row);
		}

		//Retrieve information for user audit
	function fetchUserAudit($userid)
		{
		global $mysqli,$db_table_prefix;
		$stmt = $mysqli->prepare("SELECT 
				id,
				display_name,
				audit_id,
				audit_userid,
				audit_userip,
				audit_othus,
				audit_eventcode,
				audit_action,
				audit_itemid,
				audit_timestamp
				
			FROM ".$db_table_prefix."audit LEFT JOIN ".$db_table_prefix."users ON audit_userid = id WHERE audit_userid = ? ORDER BY audit_id DESC") ; 
			$stmt->bind_param("i",$userid);
			$stmt->execute();
			$stmt->store_result();
			$num_returns = $stmt->num_rows;
			if ($num_returns > 0)
					{
					$stmt->bind_result($userid, $displayname,$auditid, $audituserid, $ip, $othus, $event, $action, $itemid, $timestamp);
					while ($stmt->fetch()){
						$row[] = array('id' => $userid,'display_name' => $displayname,'audit_id' => $auditid, 'audit_userid' => $audituserid,  'audit_userip' => $ip, 'audit_othus' => $othus, 'audit_eventcode' => $event, 'audit_action' => $action, 'audit_itemid' => $itemid, 'audit_timestamp' => $timestamp);
						}
					}
				else
					{
					$stmt->close();
					return false;
					}
			$stmt->close();
			return ($row);

		}

 function writeAudit($userid,$userip,$othus,$event,$action,$itemid=0)
	{
	global $mysqli,$db_table_prefix;
	$time = time();
	$stmt = $mysqli->prepare("INSERT INTO ".$db_table_prefix."audit (
	audit_userid,audit_userip,audit_othus,audit_eventcode,audit_action,audit_itemid,audit_timestamp
	)
	VALUES (
	?,
	?,
	?,
	?,
	?,
	?,
	?
	)");
	$stmt->bind_param("isiisii",$userid,$userip,$othus,$event,$action,$itemid,$time);
	$result = $stmt->execute();
	$stmt->close();
	return $result;
	}



	?>