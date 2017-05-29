<?php

/**
*	@copyright (c) Telemetry Gateway 2017 <www.telemetry-gateway.com>
*/

namespace Bonita;

/**
*	Http
*
*	@package Bonita
*	@version 1.0
*/

final class Http{

	/**
	*	settings
	*
	*	@var private static
	*/

	private static $settings = [

		'_method'	=>	'_method'
	];

	/**
	*	scheme
	*
	*	@return ?string
	*/

	public static function scheme() : ?string{

		return $_SERVER['REQUEST_SCHEME'] ?? null;
	}

	/**
	*	method
	*
	*	@return string
	*/

	public static function method() : string{

		if( 'GET' == $_SERVER['REQUEST_METHOD'] ){

			return $_GET[self::$settings['_method']] ?? 'GET';
		}

		return $_POST[self::$settings['_method']] ?? 'POST';
	}

	/**
	*	host
	*
	*	@return string
	*/

	public static function host() : string{

		return $_SERVER['HTTP_HOST'];
	}

	/**
	*	path
	*
	*	@return string
	*/

	public static function path() : ?string{

		$path = substr(

			$_SERVER['SCRIPT_NAME'], 1,

			strrpos(

				str_replace(

					'\\', '/', $_SERVER['SCRIPT_NAME']

				), '/'
			)
		);

		return isset( $path{0} ) ? rtrim( $path, '/' ) . '/' : null;
	}

	/**
	*	base
	*
	*	@param bool $port
	*	@return string
	*/

	public static function base( $port = false ){

		return (

			isset( self::scheme(){0} ) ? self::scheme() . '://' : null

		) .

		self::host() .

		(
			$port ? ':' . $_SERVER['SERVER_PORT'] . '/' : '/'
		) .

		(
			isset( self::path(){0} )

			? self::path() : null
		);
	}

	/**
	*	uri
	*
	*	@param int $index
	*	@return mixed
	*/

	public static function uri( int $index = null ){

		$uri = trim(

			substr(

				$_SERVER['REQUEST_URI'],

				strrpos( str_replace( '\\', '/', $_SERVER['SCRIPT_NAME'] ), '/' )

			), '/'
		);

		if( isset( $uri{0} ) ){

			if( !isset( $index ) ) return $uri;

			else{

				$uri = explode( '/', $uri );

				if( $index < 0 ){

					$index = $index + count( $uri );
				}

				if( !isset( $uri[$index] ) ) return null;

				else return self::type_safe( $uri[$index] );
				
			}
		}

		else return null;
	}

	/**
	*	go
	*
	*	@param string $url
	*	@return int
	*/

	public static function go( string $url = null ){

		return isset( $url ) ? header( "Location: $url" ) : null;
	}

	/**
	*	type_safe
	*
	*	@param mixed $input
	*	@return mixed
	*/

	private static function type_safe( $input ){

		if( is_numeric( $input ) ){

			if( false === strpos( $input, '.' ) ) return ( int ) $input;

			else return ( double ) $input;
		}

		return $input;
	}
}
