<?php


namespace ColibriWP\PageBuilder\License;


use WP_Error;
use WP_Http;

class Endpoint {


	/**
	 * @param $url
	 * @param string $method
	 *
	 * @return array|WP_Error Array containing 'headers', 'body', 'response', 'cookies', 'filename'.
	 *
	 */
	private static function request( $url, $method = "GET" ) {
		$http = new WP_Http();
		$body = array(
			'project_url' => get_option( 'colibri_sync_data_source', '' ),
			'license'     => License::getInstance()->getLicenseKey()
		);

		return $http->request( $url, array(
			'method'     => $method,
			'timeout'    => 30,
			'user-agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . get_bloginfo( 'url' ),
			'sslverify'  => false,
			'body'       => $body
		) );
	}

	/**
	 * @return RequestResponse
	 */
	public static function activate() {
		$content = static::request( License::getInstance()->getActivateEndpoint(), "POST" );

		return new RequestResponse( $content );
	}

	/**
	 * @return RequestResponse
	 */
	public static function check() {
		$content = static::request( License::getInstance()->getCheckEndpoint(), "POST" );

		return new RequestResponse( $content );
	}
}
