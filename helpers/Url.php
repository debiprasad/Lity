<?php

/**
 * Url
 *
 * @author  Wibeset <support@wibeset.com>
 * @package helpers
 *
 */

class Lity_Helper_Url
{
	/**
	 * Get value from querystring
	 *
	 * @param  string $url
	 * @param  string $key
	 * @return string
	 *
	 */
  public function get_value_from_querystring($url, $key)
	{
		$value = false;

		if (is_array($url)) {
			$url = implode('/', $url);
		}

		$docs = explode('?', $url);
		$docs = explode('&', $docs[1]);

		foreach ($docs as $doc) {
			list($current_key, $current_value) = explode('=', $doc);

			if ($current_key == $key) {
				$value = $current_value;
				break;
			}
		}

		return $value;

  } // get_value_from_querystring()

	/**
	 * Get domain from url
	 *
	 * @param  string $url
	 * @return string
	 *
	 */
	public function get_domain_from_url($url)
	{
		return parse_url($url, PHP_URL_HOST);
		
	} // get_domain_from_url()

	/**
	 * Add file version from filemtime to url
	 *
	 * @deprecated
	 * @param  string $url
	 * @return string
	 *
	 */
	public function add_version_to_file_url($url)
	{
		// get file extension
		$ext = substr($url, strrpos($url, '.')+1);

		// images don't have the same name as their extensions
		$dir = (in_array($ext, array('gif', 'png', 'jpg')) ? 'img' : $ext);

		// check path if we're in the thumbs directory
		$dir = ($dir == 'img' && strpos($url, '/thumbs/') ? 'thumbs' : $dir);

		// get the correct urlbase for filetype
		$urlbase = app()->config['url'.$dir];

		// remove the urlbase from the included url
		$file = str_replace($urlbase, '', $url);

		// thumbs dir don't have the public prefix
		$path = ($dir == 'thumbs' ? $dir : 'public/'.$dir).'/'.$file;

		// get the "version" of the file, which is its last modified timestamp
		$version = (file_exists($path) ? filemtime($path).'.' : '');

		// build the new url by inserting the version before the extension
		$newurl = $urlbase.str_replace($ext, $version.$ext, $file);

		return $newurl;

	}	// add_version_to_file_url()
	
	/**
	 * Versionify filename
	 * 
	 * @param string $filename
	 * @param string $urlbase
	 * @return string File's url versionified
	 * 
	 */
	public function versionify($filename, $urlbase)
	{
		// get file extension
		$ext = substr($filename, strrpos($filename, '.')+1);
		
		// get the "version" of the file, which is its last modified timestamp
		$version = (file_exists(ABSPATH.$filename) ? filemtime(ABSPATH.$filename).'.' : '');
		
		// build the new url by inserting the version before the extension
		$newurl = $urlbase.str_replace('.'.$ext, '.'.$version.$ext, $filename);
		
		return $newurl;
		
	} // versionify()

} // Lity_Helper_Url
