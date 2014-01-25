<?php

// The caching time for the curl results in minutes.
define("OVGU_CACHE_TIMEOUT", 20);

define("OVGU_URL", "http://www.ovgu.de");
define("HISQIS_URL", "https://hisqis.uni-magdeburg.de");
define("MOODLE_URL", "http://moodle.ovgu.de");
define("WEBMAIL_URL", "https://webmail.uni-magdeburg.de");
define("LSF_URL", "http://lsf.ovgu.de");

function isOnline($url) {
	$lastTime = apc_fetch("LAST_CACHE_TIME_" . $url);
	if (!$lastTime || $lastTime + (60*OVGU_CACHE_TIMEOUT) < time()) {
		if ($lastTime != false) {
			apc_delete("LAST_CACHE_TIME_" . $url);
			apc_delete("LAST_CACHE_RESULT_" . $url);
		}

		apc_add("LAST_CACHE_TIME_" . $url, time());
		$resURL = curl_init($url);
		curl_setopt($resURL, CURLOPT_NOBODY, true);
		curl_setopt($resUrl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($resURL, CURLOPT_TIMEOUT, 1);
		curl_exec($resURL);
		$intReturnCode = curl_getinfo($resURL, CURLINFO_HTTP_CODE);
		curl_close ($resURL);
		apc_add("LAST_CACHE_RESULT_" . $url, $intReturnCode);
		return $intReturnCode == 200 || $intReturnCode == 302 || $intReturnCode == 304;
	} else {
		return apc_fetch("LAST_CACHE_RESULT_" . $url);
	}
}

?>

<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<link href='http://fonts.googleapis.com/css?family=Rokkitt:400' rel='stylesheet' type='text/css'>
		<link href='style.css' rel='stylesheet' type='text/css'>
		
		<title>Ist die OVGU online?</title>
	</head>
	<body>
		<h1>Ist die OVGU online?</h1>
		<img src="src/otto_nein.png">
		
		<hr>

		<table>
			<tr>
				<td>Homepage*</td>
				<td>
					<?php if (isOnline(OVGU_URL)) { ?>
						<span class="on">Online</span>
					<?php } else { ?>
						<span class="off">Offline</span>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td>HisQis</td>
				<td>
					<?php if (isOnline(HISQIS_URL)) { ?>
						<span class="on">Online</span>
					<?php } else { ?>
						<span class="off">Offline</span>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td>LSF</td>
				<td>
					<?php if (isOnline(LSF_URL)) { ?>
						<span class="on">Online</span>
					<?php } else { ?>
						<span class="off">Offline</span>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td>Moodle</td>
				<td>
					<?php if (isOnline(MOODLE_URL)) { ?>
						<span class="on">Online</span>
					<?php } else { ?>
						<span class="off">Offline</span>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td>Webmail</td>
				<td>
					<?php if (isOnline(WEBMAIL_URL)) { ?>
						<span class="on">Online</span>
					<?php } else { ?>
						<span class="off">Offline</span>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td>WLAN</td>
				<td>
					<span class="off">Offline</span>
				</td>
			</tr>
			<tr>
				<td>LAN</td>
				<td>
					<span class="on">Online</span>
				</td>
			</tr>
			<tr>
				<td>Telefon</td>
				<td>
					<span class="off">Offline</span>
				</td>
			</tr>
			<tr>
				<td>Drucker</td>
				<td>
					<span class="on">Online</span>
				</td>
			</tr>
			<tr>
				<td>Rückmeldeautomaten</td>
				<td>
					<span class="off">Offline</span>
				</td>
			</tr>			
		</table>

		
		<small>* außer medizinische Fakultät</small>

		<hr>

		<div>
			Stand: 21.1.2014 9:45 Uhr
		</div>


		<a href="mailto:hi@jfilter.de?&subject=OVGU Down">Kontakt</a>
		|
		<a href="impressum.html">Impressum</a>

		<p><small>Nach einer Idee von <a href="https://twitter.com/rosario_raulin">Rosario Raulin</a>, umgesetzt durch <a href="https://twitter.com/fil_ter">Johannes Filter</a>.</small></p>




	</body>
</html>