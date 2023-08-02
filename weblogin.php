<?php
/**
*	@author SignpostMarv Martin
*	@license http://www.creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
*	@link  https://wiki.secondlife.com/wiki/Web_login_code/python Based on python code
*	@link  http://wiki.secondlife.com/wiki/Web_login_code/php5 Originally posted on the Second Life wiki, 2008-01-08
*	@link http://code.google.com/p/uhu/source/browse/trunk/secondlife/web-login.php SVN browse
*	@link http://uhu.googlecode.com/svn/trunk/secondlife/web-login.php SVN checkout
*	@package SecondLife
*	@subpackage WebLogin
*/
 
/**
*	Gives {@link sl_web_login} it's own Exception class, assisting in Exception handling.
*	@package WebLogin
*/
class sl_web_login_Exception extends Exception{}
 
/**
*	Declares the public interface to {@link sl_web_login}
*	@package WebLogin
*/
interface sl_web_login_funcs
{
/**
*	Returns the web login key to pass back to the viewer/bot
*	@return string a {@link http://wiki.secondlife.com/wiki/UUID UUID}
*/
	public function web_login_key();
/**
*	Returns the {@link http://wiki.secondlife.com/wiki/UUID UUID} for the agent logging in.
*	@return string a {@link http://wiki.secondlife.com/wiki/UUID UUID}
*/
	public function uuid();
/**
*	Returns the fist name provided in {@link sl_web_login::__construct()}
*	@return string The agent's first name
*/
	public function first();
/**
*	Returns the last name provided in {@link sl_web_login::__construct()}
*	@return string The agent's last name
*/
	public function last();
/**
*	Returns the location provided in {@link sl_web_login::__construct()}
*	@return string The desired location for the agent to log into
*/
	public function location();
/**
*	Returns the grid provided in {@link sl_web_login::__construct()}
*	@return string The desired grid to log into
*/
	public function grid();
/**
*	Returns the continuation parameter provided in {@link sl_web_login::__construct()}
*	@return string
*/
	public function continuation();
/**
*	Returns the cURL info for the web login operation
*	@return array
*/
	public function info();
/**
*	Returns the error associated with the web login cURL operation
*	@return string|null
*/
	public function error();
/**
*	Configures the cURL operation for the web login operation
*	@param string $first see {@see sl_web_login_funcs::first()}
*	@param string $last see  {@see sl_web_login_funcs::last()}
*	@param string $password the agent's password
*	@param string $location see  {@see sl_web_login_funcs::location()}
*	@param string $grid see  {@see sl_web_login_funcs::grid()}
*	@param string $continuation see  {@see sl_web_login_funcs::continuation()}
*/
	public function __construct($first,$last,$password,$location='home',$grid='agni',$continuation='');
/**
*	Returns the URL to use with cURL for the web login operation
*	@return string
*/
	public static function login_url();
/**
*	Returns the regex to use in order to extract the web login key
*	@return string a regex string compatible with {@link http://uk.php.net/preg_match preg_match}
*/
	public static function web_login_key_regex();
/**
*	Returns the regex to use in order to extract the agent key
*	@return string a regex string compatible with {@link http://uk.php.net/preg_match preg_match}
*/
	public static function uuid_regex();
}
/**
*	The root class for web login operations
*	@package WebLogin
*/
abstract class sl_web_login implements sl_web_login_funcs
{
/**
*	@var string The UUID to use for {@link https://wiki.secondlife.com/wiki/NULL_KEY NULL_KEY}
*/
	const NULL_KEY            = '00000000-0000-0000-0000-000000000000';
/**
*	@var array see {@link sl_web_login_funcs::error()}
*/
	protected $error;
/**
*	@var string see {@link sl_web_login_funcs::web_login_key()}
*/
	protected $web_login_key;
/**
*	@var string see {@link sl_web_login_funcs::uuid()}
*/
	protected $uuid;
/**
*	@var string see {@link sl_web_login_funcs::first()}
*/
	protected $first;
/**
*	@var string see {@link sl_web_login_funcs::last()}
*/
	protected $last;
/**
*	@var string see {@link sl_web_login_funcs::location()}
*/
	protected $location;
/**
*	@var string see {@link sl_web_login_funcs::grid()}
*/
	protected $grid;
/**
*	@var string see {@link sl_web_login_funcs::continuation()}
*/
	protected $continuation;
/**
*	@var string The name of the class instance
*/
	protected $class_name;
/**
*	@var string The body of the cURL operation
*/
	protected $data;
/**
*	@var array see {@link sl_web_login_funcs::info()}
*/
	protected $info;
/**
*	@uses sl_web_login_funcs::__construct()
*	@uses sl_web_login::$first
*	@uses sl_web_login::$last
*	@uses sl_web_login::$location
*	@uses sl_web_login::$grid
*	@uses sl_web_login::$continuation
*	@uses sl_web_login::get_class_name()
*	@uses sl_web_login_funcs::login_url()
*	@uses sl_web_login_Exception
*/
	public function __construct($first,$last,$password,$location='home',$grid='agni',$continuation='')
	{
		$this->first = $first;
		$this->last = $last;
		$this->location = $location;
		$this->grid = $grid;
		$this->continuation = $continuation;
		$ch = curl_init(call_user_func($this->get_class_name() . '::login_url'));
		if($ch === false)
		{
			throw new sl_web_login_Exception('Couldn\'t initialise a cURL handle',100);
		}
		curl_setopt_array($ch,
			array(
				CURLOPT_POST           => 1,
				CURLOPT_HEADER         => 1,
				CURLOPT_FOLLOWLOCATION => 0,
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_HTTPHEADER     =>
					array(
						'Accept:text/plain',
					),
				CURLOPT_POSTFIELDS     => 
					array(
						'username'     => $first,
						'lastname'     => $last,
						'password'     => $password,
						'continuation' => $continuation,
						'grid'         => $grid,
						'location'     => $location,
					),
			)
		);
		$this->data = curl_exec($ch);
		$this->info = curl_getinfo($ch);
		if($this->data === false)
		{
			$this->error         = curl_error($ch);
		}
		curl_close($ch);
	}
/**
*	Returns the class name for the current instance
*	@uses sl_web_login::$class_name
*	@return string
*/
	protected function get_class_name()
	{
		if(isset($this->class_name) === false)
		{
			$this->class_name = get_class($this);
		}
		return $this->class_name;
	}
/**
*	Returns the body of the cURL opreation
*	@uses sl_web_login::$data
*	@return string
*/
	protected function data()
	{
		return $this->data;
	}
/**
*	@uses sl_web_login_funcs::info()
*	@return array
*/
	public function info()
	{
		return $this->info;
	}
/**
*	@uses sl_web_login_funcs::error()
*	@return string|null
*/
	public function error()
	{
		return $this->error;
	}
/**
*	@uses sl_web_login::get_class_name()
*	@uses sl_web_login_funcs::get_web_login_key()
*	@uses sl_web_login::$web_login_key
*	@uses sl_web_login::NULL_KEY
*	@return string
*/
	public function web_login_key()
	{
		if(isset($this->web_login_key) === false)
		{
			$this->web_login_key = call_user_func_array($this->get_class_name() . '::get_web_login_key',array($this));
			if(isset($this->web_login_key) === false)
			{
				$this->web_login_key = self::NULL_KEY;
			}
		}
		return $this->web_login_key;
	}
/**
*	@uses sl_web_login::get_class_name()
*	@uses sl_web_login_funcs::get_uuid()
*	@uses sl_web_login::$uuid
*	@uses sl_web_login::NULL_KEY
*	@return string
*/
	public function uuid()
	{
		if(isset($this->uuid) === false)
		{
			$this->uuid = call_user_func_array($this->get_class_name() . '::get_uuid',array($this));
			if(isset($this->uuid) === false)
			{
				$this->uuid = self::NULL_KEY;
			}
		}
		return $this->uuid;
	}
/**
*	@uses sl_web_login_funcs::first()
*	@uses sl_web_login::$first
*	@return string
*/
	public function first()
	{
		return $this->first;
	}
/**
*	@uses sl_web_login_funcs::last()
*	@uses sl_web_login::$last
*	@return string
*/
	public function last()
	{
		return $this->last;
	}
/**
*	@uses sl_web_login_funcs::location()
*	@uses sl_web_login::$location
*	@return string
*/
	public function location()
	{
		return $this->location;
	}
/**
*	@uses sl_web_login_funcs::grid()
*	@uses sl_web_login::$grid
*	@return string
*/
	public function grid()
	{
		return $this->grid;
	}
/**
*	@uses sl_web_login_funcs::continuation()
*	@uses sl_web_login::$continuation
*	@return string
*/
	public function continuation()
	{
		return $this->continuation;
	}
/**
*	Extracts the web login key from the cURL body
*	@uses sl_web_login_funcs::web_login_key_regex()
*	@uses sl_web_login::data()
*	@uses sl_web_login_Exception
*	@return string
*/
	protected static function get_web_login_key(sl_web_login $sl_web_login)
	{
		if(preg_match(call_user_func(get_class($sl_web_login) . '::web_login_key_regex'),$sl_web_login->data(),$matches))
		{
			return $matches[1];
		}
		else
		{
			throw new sl_web_login_Exception('Could not locate web login key',101);
		}
	}
/**
*	Extracts the agent UUID from the cURL body
*	@uses sl_web_login_funcs::uuid_regex()
*	@uses sl_web_login::data()
*	@uses sl_web_login_Exception
*	@return string
*/
	protected static function get_uuid(sl_web_login $sl_web_login)
	{
		if(preg_match(call_user_func(get_class($sl_web_login) . '::uuid_regex'),$sl_web_login->data(),$matches))
		{
			return $matches[1];
		}
		else
		{
			throw new sl_web_login_Exception('Could not locate Resident UUID',102);
		}
	}
}
/**
*	Sub-class of {@link sl_web_login} that provides configuration for logging into Linden Lab's Second Life grids
*	@package WebLogin
*	@subpackage LindenLabWebLogin
*/
class LL_sl_web_login extends sl_web_login
{
/**
*	@var string The URL to use with the cURL operation in {@link sl_web_login::__construct()}
*/
	const login_url = 'https://secure-web14.secondlife.com/app/login/go.php';
/**
*	@var string The regex to use with {@link sl_web_login_funcs::web_login_key_regex}
*/
	const regex_web_login_key = '/web_login_key=([0-9a-f]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12})/';
/**
*	@var string The regex to use with {@link sl_web_login_funcs::uuid_regex}
*/
	const regex_Resident_uuid = '/second-life-member=([0-9a-f]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12})/';
/**
*	Returns the URL to use with the cURL operation in {@link sl_web_login::__construct()}
*	@uses sl_web_login_funcs::login_url()
*	@uses LL_sl_web_login::login_url
*/
	public static function login_url()
	{
		return self::login_url;
	}
/**
*	Returns the URL to use with the cURL operation in {@link sl_web_login::web_login_key_regex()}
*	@uses sl_web_login_funcs::web_login_key_regex()
*	@uses LL_sl_web_login::regex_web_login_key
*/
	public static function web_login_key_regex()
	{
		return self::regex_web_login_key;
	}
/**
*	Returns the URL to use with the cURL operation in {@link sl_web_login::uuid_regex()}
*	@uses sl_web_login_funcs::uuid_regex()
*	@uses LL_sl_web_login::regex_Resident_uuid
*/
	public static function uuid_regex()
	{
		return self::regex_Resident_uuid;
	}
 
}
?>

