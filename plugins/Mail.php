<?php

/**
 * API for sending mail
 *
 * @todo replace by something like phpmailer
 * @author  Wibeset <support@wibeset.com>
 * @package plugins
 *
 */

class Lity_Plugin_Mail
{
    /**
	 * Send an email. (use mail() function)
	 *
	 * @param string $to
	 * @param string $subject
	 * @param string $body
	 * @param array  $headers
	 * @return bool success
	 * 
	 */
	public function send($to, $subject, $body, $headers = array())
	{
	    if (!isset($headers['Content-Type']))
			$headers['Content-Type'] = 'text/plain; charset='.(isset(q()->config['encoding']) ? q()->config['encoding'] : 'UTF-8');

		$h = '';

		foreach ($headers as $headerk => $headerv) {
			$h .= $headerk.": ".$headerv."\r\n";
		}

		return mail($to, $subject, $body, ($h != '' ? $h : null));

	} // send()

} // Lity_Plugin_Mail