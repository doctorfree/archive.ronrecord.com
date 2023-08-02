<?php
$nouns = array("viewer", "2", "silks", "1", "skybox", "2", "RL", "2", "SL", "2", "sandbox", "2", "lag", "1", "hovertext", "3", "hud", "1", "bling", "1", "neko", "2", "AO", "2", "attachment", "3", "pixel", "2", "pixels", "2", "freebie", "2", "SL Marketplace", "5", "tier", "1", "landmark", "2", "SL blogger", "4", "gesture", "2", "DJ", "2", "hostess", "2", "chat bot", "2", "tiny", "2", "invisiprim", "4", "furry", "2", "vampire", "2", "mouselook", "2", "Gor", "1", "money tree", "3", "virtual world", "4", "alt", "1", "griefer", "2", "flying car", "3", "camping chair", "3", "sculpted prim", "3", "flexible prim", "4", "flexi prim", "3", "prim", "1", "welcome center", "4", "orient island", "5", "Linden Lab", "3", "Linden", "2", "inner core", "3", "pose ball", "2", "avatar", "3", "avatars", "3", "sim", "1", "script", "1", "hardware lighting", "4", "Resident", "3", "Linden dollar", "4", "Mainland", "2", "Private Island", "4", "Homestead", "2", "grid", "1", "region", "2", "parcel", "2", "CopyBot", "3", "Firestorm", "2", "Zindra", "2");

$verbs = array("TP", "2", "teleport", "3", "stream", "1", "partner", "2", "IM", "2", "age verify", "4", "emote", "2", "accessorize", "4", "rez", "1", "Being Ruthed", "3", "Ruthed", "1", "Building", "2", "Scripting", "2", "Editing", "3", "Chatting", "2", "Lagging", "2", "Flying", "2", "Building together", "5", "Cybering", "3", "Teleporting", "4", "Pose balling", "3", "Editing appearance", "6", "Exploring", "3", "Griefing", "2", "Rebaking", "2", "Uploading", "2", "Editing profile", "5"); 

$adv = array("inworld", "2", "earlier", "3", "everywhere", "3", "frequently", "3", "in the Metaverse", "5", "slowly", "2", "graphicly", "3", "dazzlingly", "3", "dreamily", "3", "well", "1", "once", "1", "less", "1", "twice", "1", "in the air", "3", "quickly", "2", "very quickly", "4", "", "0", "with great speed", "3", "virtually", "4", "vivaciously", "4", "visually", "4");

$adj = array("anonymous", "4", "adorable", "4", "adventurous", "4", "fullperm", "2", "afk", "3", "dancing", "2", "gorean", "3", "virtuous", "3", "ecstatic", "3", "fabulous", "3", "so gorgeous", "3", "sexy cool", "3", "lonely", "2", "cool", "1", "baked", "1", "brave", "1", "nude", "1", "worn", "1", "electric", "3", "stunning", "2", "creepy", "2", "dashing", "2", "glowing", "2", "beautiful", "3", "colorful", "3", "", "0"); 

$one = array("how", "once", "quite", "so");
$two = array("aptly", "always", "boldly", "fiercely", "they are", "it is");
$three = array("vibrantly", "totally", "serenely", "seemingly", "it is so"); 
$four = array("absolutely", "virtually", "incredibly");
$five = array("Second Life Haiku!");

function makehaiku1() { 
    while(1) { 
	global $nouns, $adj; 
	$x = mt_rand(0, count($nouns) - 1); 
	if ($x % 2 == 1) { 
	    $x--; 
        }
	$y = mt_rand(0, count($adj) - 1);
	if ($y % 2 == 1) { 
	    $y--; 
        }
	if ($nouns[$x+1] + $adj[$y+1] == 5) { 
	    $haiku1 = $adj[$y]." ".$nouns[$x]; 
	    break; 
        }
	else if (1 + $nouns[$x+1] + $adj[$y+1] == 5) { 
	    $haiku1 = "The ".$adj[$y]." ".$nouns[$x]; 
	    break; 
        }
    }
    return ucfirst($haiku1); 
}

function makehaiku2() { 
    while(1) { 
	global $verbs, $adv; 
	$a = mt_rand(0, count($verbs) - 1); 
	if ($a % 2 == 1) { 
	    $a--; 
        }
	$z = mt_rand(0, count($adv) - 1); 
	if ($z % 2 == 1) { 
	    $z--; 
        }
	$haiku2 = $verbs[$a]." ".$adv[$z]; 
	if ($verbs[$a+1] + $adv[$z+1] == 7) { 
	    break; 
        }
    }
    return ucfirst($haiku2); 
}

function makehaiku3() { 
	global $adj, $one, $two, $three, $four, $five;
	$y = mt_rand(0, count($adj) - 1); 
	if ($y % 2 == 1) { 
	    $y--; 
        }
	if ($adj[$y+1] == 0) { 
	    $x = mt_rand(0, count($five) - 1); 
            $foobar = $five[$x];
        }
	else if ($adj[$y+1] == 1) { 
	    $x = mt_rand(0, count($four) - 1); 
            $foobar = $four[$x]; 
        }
	else if ($adj[$y+1] == 2) { 
	    $x = mt_rand(0, count($three) - 1); 
            $foobar = $three[$x]; 
        }
	else if ($adj[$y+1] == 3) { 
	    $x = mt_rand(0, count($two) - 1); 
            $foobar = $two[$x]; 
        }
	else if ($adj[$y+1] == 4) { 
	    $x = mt_rand(0, count($one) - 1); 
            $foobar = $one[$x]; 
        }
        $haiku3 = $foobar . " " . $adj[$y]; 
        return ucfirst($haiku3); 
}

$haiku = "A Second Life Haiku:\n\n".makehaiku1()."\n".makehaiku2()."\n".makehaiku3(); 
echo($haiku);
?> 

