<?php


/*
Alphaland 2021
*/

//headers
header("Access-Control-Allow-Origin: https://www.alphaland.cc");
header("access-control-allow-credentials: true");
header('Content-Type: application/json');

$userid = $user->id;

//user info
$userquery = $pdo->prepare('SELECT * FROM `users` WHERE id = :uid');
$userquery->bindParam(':uid', $userid, PDO::PARAM_INT);
$userquery->execute();
$userquery = $userquery->fetch(PDO::FETCH_OBJ);

$username = getUsername($userquery->id);
$blurb = $userquery->blurb;
$email = obfuscate_email($userquery->email);
$verified = (bool)$userquery->verified;
$joinpref = $userquery->canJoin;
$tradepref = null;
$theme = $userquery->theme;

//feature tester locked
//initialize 2FA in the database if it hasnt been already
if (inFeatureTesterGroup($userid)) {
	initialize2FA($userid);
}

$userInfo = array (
	"userid" => $userid,
	"username" => $username,
	"email" => $email,
	"verified" => $verified,
	"blurb" => $blurb,
	"featuretester" => inFeatureTesterGroup($userid),
	"twofactorenabled" => is2FAInitialized($userid),
	"referralprogram" => inReferralProgram($userid),
	"joinpref" => $joinpref,
	"tradepref" => $tradepref,
	"theme" => $theme
);
// ...

die(json_encode($userInfo));