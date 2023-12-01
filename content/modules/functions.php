<?php
function email_V($email)
{
	$atom   = '[-a-z0-9_]';   // caract&egrave;res autoris&eacute;s avant l'arobase
	$domain = '([a-z0-9]([-a-z0-9]*[a-z0-9]+)?)'; // caract&egrave;res autoris&eacute;s apr&egrave;s l'arobase (nom de domaine)

	$regex = '/^' . $atom . '+' .   // Une ou plusieurs fois les caract&egrave;res autoris&eacute;s avant l'arobase
	'(\.' . $atom . '+)*' .         // Suivis par z&eacute;ro point ou plus
									// s&eacute;par&eacute;s par des caract&egrave;res autoris&eacute;s avant l'arobase
	'@' .                           // Suivis d'un arobase
	'(' . $domain . '{1,63}\.)+' .  // Suivis par 1 &agrave; 63 caract&egrave;res autoris&eacute;s pour le nom de domaine
									// s&eacute;par&eacute;s par des points
	$domain . '{1,63}$/i';          // Suivi de 2 &agrave; 63 caract&egrave;res autoris&eacute;s pour le nom de domaine

	// test de l'adresse e-mail
	if (preg_match($regex, $email)):
		return true;
	else:
		return false;
	endif;
}
function encrypt_newsletter($data) {
    $key = "All2017";  // Clé de 8 caractères max
    $data = serialize($data);
    $td = mcrypt_module_open(MCRYPT_DES,"",MCRYPT_MODE_ECB,"");
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND); 
    mcrypt_generic_init($td,$key,$iv);
    $data = base64_encode(mcrypt_generic($td, '!'.$data));
    mcrypt_generic_deinit($td);
    return $data; 
}
 
function decrypt_newsletter($data) {
    $key = "All2017"; 
    $td = mcrypt_module_open(MCRYPT_DES,"",MCRYPT_MODE_ECB,""); 
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND); 
    mcrypt_generic_init($td,$key,$iv); 
    $data = mdecrypt_generic($td, base64_decode($data));
    mcrypt_generic_deinit($td); 
 
    if (substr($data,0,1) != '!') 
        return false; 
 
    $data = substr($data,1,strlen($data)-1); 
    return unserialize($data); 
}

function pass_V($ps)
{
	if(strlen($ps)<6):
		return false;
		else: return true;
	endif;
}

function validate_alpha($str)
{
	return preg_match('/^[a-zA-Z]+$/',$str);
}

function validate_alpha_spce($str)
{
	return preg_match('/^[a-zA-Z ]+$/',$str);
}

function validate_alpha_($str)
{
	return preg_match('/^[a-zA-Z0-9-_ �����.]+$/',$str);
}

function validate_numeric($str)
{
	return preg_match('/^[0-9]+$/',$str);
}

function validate_numeric_spce($str)
{
	return preg_match('/^[0-9 ]+$/',$str);
}
function validate_alphanumeric_underscore($str)
{
	return preg_match('/^[a-zA-Z0-9_]+$/',$str);
}

function validate_alphanumeric_score($str)
{
	return preg_match('/^[a-zA-Z0-9-]+$/',$str);
}

function validate_alphanumeric($str)
{
	return preg_match('/^[a-zA-Z0-9]+$/',$str);
}

/*function getoption($n){
	$r=mysql_fetch_array(mysql_query("select valeur from options where id=".$n));
	return $r['valeur'];
}*/

function add_date($givendate,$day=0,$mth=0,$yr=0) {
    $cd = strtotime($givendate);
    $newdate = date('Y-m-d h:i:s', mktime(date('h',$cd),
    date('i',$cd), date('s',$cd), date('m',$cd)+$mth,
    date('d',$cd)+$day, date('Y',$cd)+$yr));
    return $newdate;
}




function encrypt($user_password) {
    $options = [
		'cost' => 12,
	];
    return password_hash($user_password, PASSWORD_BCRYPT, $options);
}
 
function decrypt($user_password,$hashed_password) {
    if(password_verify($user_password,$hashed_password)==1){
		return true;
	}else{
		return false;
	}
   
}

function generer_mot_de_passe($nb_caractere = 12)
{
        $mot_de_passe = "";
       
        $chaine = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $longeur_chaine = strlen($chaine);
       
        for($i = 1; $i <= $nb_caractere; $i++)
        {
            $place_aleatoire = mt_rand(0,($longeur_chaine-1));
            $mot_de_passe .= $chaine[$place_aleatoire];
        }

        return $mot_de_passe;   
}


function changeDate($date){
        $day    = array("dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi");
        $month  = array("janvier","fevrier","mars","avril","mai","juin","juillet","aout","septembre","octobre","novembre","decembre");
        $timestamp = mktime(0,0,0,substr($date, 5, 2),substr($date, 8, 2),substr($date, 0, 4));
        return $day[date("w",$timestamp)].date(" j ",$timestamp).$month[date("n",$timestamp)-1].date(" Y",$timestamp);
    }
    
function eco_check_hash($password, $hash)
{
	$itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	if (strlen($hash) == 34)
	{
		return (_hash_crypt_private($password, $hash, $itoa64) === $hash) ? true : false;
	}

	return (md5($password) === $hash) ? true : false;
}

/**
* Generate salt for hash generation
*/
function _hash_gensalt_private($input, &$itoa64, $iteration_count_log2 = 6)
{
	if ($iteration_count_log2 < 4 || $iteration_count_log2 > 31)
	{
		$iteration_count_log2 = 8;
	}

	$output = '$H$';
	$output .= $itoa64[min($iteration_count_log2 + ((PHP_VERSION >= 5) ? 5 : 3), 30)];
	$output .= _hash_encode64($input, 6, $itoa64);

	return $output;
}

/**
* Encode hash
*/
function _hash_encode64($input, $count, &$itoa64)
{
	$output = '';
	$i = 0;

	do
	{
		$value = ord($input[$i++]);
		$output .= $itoa64[$value & 0x3f];

		if ($i < $count)
		{
			$value |= ord($input[$i]) << 8;
		}

		$output .= $itoa64[($value >> 6) & 0x3f];

		if ($i++ >= $count)
		{
			break;
		}

		if ($i < $count)
		{
			$value |= ord($input[$i]) << 16;
		}

		$output .= $itoa64[($value >> 12) & 0x3f];

		if ($i++ >= $count)
		{
			break;
		}

		$output .= $itoa64[($value >> 18) & 0x3f];
	}
	while ($i < $count);

	return $output;
}

/**
* The crypt function/replacement
*/
function _hash_crypt_private($password, $setting, &$itoa64)
{
	$output = '*';

	// Check for correct hash
	if (substr($setting, 0, 3) != '$H$' && substr($setting, 0, 3) != '$P$')
	{
		return $output;
	}

	$count_log2 = strpos($itoa64, $setting[3]);

	if ($count_log2 < 7 || $count_log2 > 30)
	{
		return $output;
	}

	$count = 1 << $count_log2;
	$salt = substr($setting, 4, 8);

	if (strlen($salt) != 8)
	{
		return $output;
	}

	/**
	* We're kind of forced to use MD5 here since it's the only
	* cryptographic primitive available in all versions of PHP
	* currently in use.  To implement our own low-level crypto
	* in PHP would result in much worse performance and
	* consequently in lower iteration counts and hashes that are
	* quicker to crack (by non-PHP code).
	*/
	if (PHP_VERSION >= 5)
	{
		$hash = md5($salt . $password, true);
		do
		{
			$hash = md5($hash . $password, true);
		}
		while (--$count);
	}
	else
	{
		$hash = pack('H*', md5($salt . $password));
		do
		{
			$hash = pack('H*', md5($hash . $password));
		}
		while (--$count);
	}

	$output = substr($setting, 0, 12);
	$output .= _hash_encode64($hash, 16, $itoa64);

	return $output;
}

?>