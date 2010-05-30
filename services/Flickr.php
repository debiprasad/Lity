<?php

/**
 * Flickr
 *
 * You must add the following config to app/config/application.php:
 *
 * $config['flickr'] = array('api_ley' => 'your api key');
 *
 * @see <a href="http://www.flickr.com/services/api/">Flickr API</a>
 * @author Wibeset <support@wibeset.com>
 * @package services
 *
 */

class Lity_Service_Flickr 
{
    /**
     * @var $_api_key
     */
    private $_api_key;

    /**
     * @var $_url
     */
    private $_url;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->_api_key = app()->config['flickr']['api_key'];
        $this->_url = "http://api.flickr.com/services/rest/?method=flickr.%%METHOD%%&api_key=".$this->_api_key."&format=json&nojsoncallback=1&";
        
    } // __construct()

    /**
     * Call a flickr method with params.
     *
     * @param string $method
     * @param array  $params
     * @return array result
     *
     */
    public function call($method, $params)
    {
        $call = str_replace('%%METHOD%%', $method, $this->_url);

        $args = array();
        foreach ($params as $paramk => $paramv) {
            $args[] = $paramk."=".urlencode($paramv);
        }

        $url = $call.implode('&', $args);

        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $results = curl_exec($ch);
        curl_close($ch);

        return json_decode($results, true);

    } // call()

    /**
     * Get recent pictures on flickr.
     *
     * @param array $params  per_page (int), page (int)
     * @return array photos
     *
     */
    public function get_recent($params = array())
    {
        $results = $this->call("photos.getRecent", $params);

        $photos = array();
        if (isset($results['photos']['photo'])) {
            foreach ($results['photos']['photo'] as $photo) {
                $photos[] = array_merge($photo, array('src' => "http://farm".$photo['farm'].".static.flickr.com/".$photo['server']."/".$photo['id']."_".$photo['secret']."_m.jpg",
                                                      'url' => "http://www.flickr.com/photos/".$photo['owner']."/".$photo['id'],
                                                      )
                                        );
            }
        }

        return $photos;

    } // get_recent()
    
    /**
     * Search pictures on flickr.
     *
     * @param array $params  query (string), per_page (int), page (int)
     * @return array photos
     *
     */
    public function search($params = array()) 
    {
        $results = $this->call("photos.search", $params);
        
        $photos = array();
        if (isset($results['photos']['photo'])) {
            foreach ($results['photos']['photo'] as $photo) {
                $photos[] = array_merge($photo, array('src' => "http://farm".$photo['farm'].".static.flickr.com/".$photo['server']."/".$photo['id']."_".$photo['secret']."_m.jpg",
                                                      'url' => "http://www.flickr.com/photos/".$photo['owner']."/".$photo['id'],
                                                      )
                                        );
            }
        }

        return $photos;

    } // search()

} // Lity_Service_Flickr