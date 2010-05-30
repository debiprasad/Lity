<?php

/**
 * Image
 * 
 * @todo rotate, crop, etc
 * @author  Wibeset <support@wibeset.com>
 * @package plugins
 *
 */

class Lity_Plugin_Image
{
    /**
	 * Convert (imagemagick)
	 *
	 */
	public function convert()
	{
	} // convert()

	/**
	 * Create a thumbnail from an image
	 *
	 * Note: Imagemagick must be installed on your server.
	 *
	 * @example ->create_thumbnail('path/to/source.jpg', 'path/to/thumbnail.jpg', '60x60');
	 *
	 * @param string $source     source file including path
	 * @param string $thumb_name thumbnail's filename including path
	 * @param string $size       thubmnail's width & height
	 * @param int    $quality    0 to 100
	 * @return bool success
	 *
	 */
	public function create_thumbnail($source, $thumb_name, $size, $quality = 100)
	{
		exec('convert '.$source.' -thumbnail '.$size.' -quality '.$quality.' '.$thumb_name, $output, $success);   
		# 0 on success, 1 on error: convert to boolean.
		return !$success;                 
		         
	} // create_thumbnail()

	/**
	 * Resize an image
	 * 
	 * @todo $ratio 
	 * @param string $source      source file including path
	 * @param string $resize_name filename after resize
	 * @param string $size        new size (ex: 120x90)
	 * @param bool   $ratio       keep ratio
	 * @return bool success
	 *
	 */
	public function resize($source, $resize_name, $size, $ratio = true)
	{
		exec('convert '.$source.' -resize '.$size.' '.$resize_name, $output, $success);
		return $success;
		
	} // resize()

	/**
	 * Download
	 * 
	 * @param string $image    image's url to download
	 * @param string $savepath path to save image
	 * @param string $filename image filename after download 
	 * @return string filename
	 *
	 */
	public function download($image, $savepath, $filename)
	{
		exec('wget -w 5 -O '.$savepath.$filename.' "'.$image.'"', $output, $success);
		return $filename;
		
	} // download()

} // Lity_Plugin_Image