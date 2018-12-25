<?php
require APPPATH .'/libraries/jwt.php';

/**
 * 
 */
class Implement 
{
	
	PRIVATE $key="hi";
	public function GeneratedTokens($data){
		$jwt= JWT::encode($data,$this->key);
		return $jwt;

	}
	public function DecodeTOkens($token)
	{
		$decode =JWT::decode($token,$this->key,array('HS256'));
		$DecodeData=(array) $decode;
		 return $DecodeData;
	}
}
  ?>