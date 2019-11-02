<?php

$fp = fopen("/www/jhdotcom/var/post.out", "w");

$payload = json_decode($_POST['payload']);

$jdtg = strftime("%j%H%M");
$ndtg = strtoupper(strftime("%d%H%M %b %Y"));

$prec = "R";
$lmf = "AA";
$cai = "ZHSW";
$osri = "HXWEBB";
$sec = 'U';
$ssn = 0;
$ri = "HX3837";
$pla = "GITHUB PROJECT PUSH";

$repo = $payload->repository->full_name;

$msg  = sprintf("%s%s%c%s %s %04d %s-%s--%s.\n", $prec, $lmf, $sec, $cai, $osri, $ssn, $jdtg, str_repeat($sec, 4), $ri);
$msg .= sprintf("%s %s\n", $prec, $ndtg);
$msg .= "FM GITHUB WEBHOOK RECEIVER\n";
$msg .= "TO $ri/$pla\n\n";

$msg .= "UNCLAS\nSUBJ: PUSH TO GITHUB REPO $repo\n\n";

function zooper($far, $op) {
	if (count($far)) {
		$r = ucfirst($op) . " " . count($far) . " files:\n";
		foreach ($far as $f) {
			$r .= "     $f\n";
		}
		$r .= "\n";
		return($r);
	}
	return("No files $op.\n\n");
}
		
		

foreach ($payload->commits as $commit) {
	$author = $commit->author->name;
	$email = $commit->author->email;

	$tstamp = $commit->timestamp;
	$cmsg = $commit->message;

	$msg .= "On $tstamp\nby $author <$email>\n\n";
	$msg .= trim($cmsg);
	$msg .= "\n\n";

	$msg .= zooper($commit->added, 'added');
	$msg .= zooper($commit->removed, 'removed');
	$msg .= zooper($commit->modified, 'modified');

	$msg .= "\$\$\n\n";
}

$msg .= "NNNN\n\n";



fwrite($fp, $msg);

fclose($fp);

$tname = uniqid("pusher.", true) . ".txt";

system("uucp /www/jhdotcom/var/post.out NTXG!HX3837!/nims/HX3837/$tname");

print "\n\n$msg\n\n";
?>
