<?php

/**
 * Youtube
 * 
 * You must add the following config to app/config/application.php:
 * 
 * $config['youtube'] = array('devid' => 'your dev id');
 * 
 * @author Wibeset <support@wibeset.com>
 * @package services
 *
 */

class Lity_Service_Youtube
{
    /**
	 * Get video's thumbnail from video's url
	 *
	 * @param string $url video's url
	 * @return string thumbnail's url
	 *
	 */
	public function get_thumbnail_from_url($url)
	{
		$id = helper('url')->get_value_from_querystring($url, 'v');
		
		return $this->get_thumbnail_from_id($id);
		
	} // get_thumbnail_from_url()

	/**
	 * Get video's thumbnail from video's id
	 *
	 * @param string $id video's id
	 * @return thumbnail's url
	 *
	 */
	public function get_thumbnail_from_id($id)
	{
		$url = 'http://www.youtube.com/api2_rest?method=youtube.videos.get_details&dev_id='.
			app()->config['youtube']['devid'].'&video_id='.$id;

		$feed = $this->_request($url);

		if ($feed != "") {
			preg_match('/<thumbnail_url>(.*?)<\/thumbnail_url>/', $feed, $thumb_matches);
			$thumbnail = $thumb_matches[1];
			$thumbnail = str_replace('&amp;', '&', $thumbnail);
		}

		return $thumbnail;

	} // get_thumbnail_from_id()

	/**
	 * Search videos
	 * 
	 * @param string $query      search query
	 * @param string $orderby    relevance, viewCount, published or rating
	 * @param int    $startindex 
	 * @param int    $limit
	 * @return array videos
	 *
	 */
	public function search($query, $orderby, $startindex = 1, $limit = 50)
	{
		$url = 'http://gdata.youtube.com/feeds/api/videos?alt=json&v=2&q='.urlencode($query).
			'&strict=false&orderby='.$orderby.'&start-index='.$startindex.'&max-results='.$limit;
		
		return $this->_request($url);		
		
	} // search()

	/**
	 * Get user uploaded videos
	 * 
	 * @param string $username   
	 * @param string $orderby    relevance, viewCount, published or rating
	 * @param int    $startindex 
	 * @param int    $limit
	 * @return array videos
	 *
	 */
	public function get_user_uploads($username, $orderby, $startindex = 1, $limit = 50)
	{
		$url = 'http://gdata.youtube.com/feeds/api/users/'.$username.'/uploads?alt=json&v=2'.
			'&strict=false&orderby='.$orderby.'&start-index='.$startindex.'&max-results='.$limit;
		
		return $this->_request($url);
		
	} // get_user_uploads()
	
	/**
	 * Get playlist uploaded videos
	 * 
	 * @param string $playlist
	 * @param string $orderby    relevance, viewCount, published or rating
	 * @param int    $startindex 
	 * @param int    $limit
	 * @return array videos
	 *
	 */
	public function get_playlist_uploads($playlist, $orderby, $startindex = 1, $limit = 50)
	{
		$url = 'http://gdata.youtube.com/feeds/api/playlists/'.$playlist.'?alt=json&v=2'.
			'&strict=false&orderby='.$orderby.'&start-index='.$startindex.'&max-results='.$limit;
				
		return $this->_request($url);
		
	} // get_playlist_uploads()
	
	/**
	 * Sent a request..
	 * 
	 * @param string $url 
	 * @return array
	 * 
	 */
	private function _request($url)
	{
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 10);
		$result = curl_exec($ch);
		curl_close($ch);

		return json_decode($result, true);
		
	} // _request()
	
} // Lity_Service_youtube