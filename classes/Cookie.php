<?php
/*
UserSpice 3
by Dan Hoover at http://UserSpice.com
Major code contributions by Astropos

a modern version of
UserCake Version: 2.0.2
UserCake created by: Adam Davis
UserCake V2.0 designed by: Jonathan Cassels
*/
class Cookie {

	public static function exists($name){
		return (isset($_COOKIE[$name])) ? true : false;
	}

	public static function get($name){
		return $_COOKIE[$name];
	}

	public static function put($name, $value, $expiry){
		if (setcookie($name, $value, time() + $expiry, "/")) {
			return true;
		}
		return false;
	}

	public static function delete($name){
		self::put($name, '', time() - 1);
	}
}
