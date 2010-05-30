<?php

/**
 * Text
 *
 * @author Wibeset <support@wibeset.com>
 * @package helpers
 *
 */

class Lity_Helper_Text
{
	/**
	 * Turns all urls into clickable links.
	 *
	 * @param string $text
	 * @param string $options
	 * @return string Text with links replaced
	 *
	 */
	public function auto_link($text)
	{
	  $text = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\">\\2</a>", $text);
	  $text = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\">\\2</a>", $text);

		return $text;

	} // auto_link()

	/**
	 * Turns all urls, @username and hashtags into clickable links.
	 *
	 * @param string $text
	 * @return string Text with links replaced
	 *
	 */
	public function twitterify($text)
	{
	  $text = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\">\\2</a>", $text);
	  $text = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\">\\2</a>", $text);
	  $text = preg_replace("/@(\w+)/", "<a href=\"http://twitter.com/\\1\">@\\1</a>", $text);
	  $text = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\">#\\1</a>", $text);

		return $text;

	} // twitterify()

	/**
	 * Highlights the phrase where it is found in the text by surrounding it like
	 * <strong class="highlight">I.m a highlight phrase</strong>.
	 *
	 * @param string $text
	 * @param string $words
	 * @param string $highlighter
	 * @return string Text with words highlighted
	 *
	 */
	public function highlight($text, $words, $highlighter = '<strong class="highlight">%s</strong>')
	{
		$highlight = str_replace('%s', $words, $highlighter);
		return str_replace($words, $highlight, $text);
	} // highlight()

	/**
	 * Sanitizes the given HTML by making form and script tags into regular text, and removing
	 * all "onxxx" attributes (so that arbitrary Javascript cannot be executed). Also removes href
	 * attributes that start with "javascript:".
	 *
	 * @param string $html
	 * @return string html sanitized
	 *
	 */
	public function sanitize($html)
	{
	} // sanitize()

	/**
	 * Sanitizes a string so we can be sure that it can be used safely in a SQL query.
	 *
	 * @param string $string
	 * @return string String sanitized
	 *
	 */
	public function sanitize_sql($string)
	{
		return trim(addslashes(strip_tags($string)));
		
	} // sanitize_sql()

	/**
	 * Turns all links into words, like "<a href="something">else</a>" to "else".
	 *
	 * @param string $text
	 * @return string Text with links stripped
	 *
	 */
	public function strip_links($text)
	{
	} // strip_links()

	/**
	 * Truncates text to the length of length and replaces the last three characters
	 * with the truncate_string if the text is longer than length.
	 *
	 * @param string $text
	 * @param int    $length
	 * @param string $truncate_string
	 * @param string $breakpoint 
	 * @return string
	 *
	 */
	public function truncate($text, $length = 30, $truncate_string = '...', $breakpoint = false)
	{
		//
		if ($breakpoint != false && mb_strlen($text) > $length) {
			$text = mb_substr($text, 0, $length);			
			return mb_substr($text, 0, mb_strrpos($text, $breakpoint)).$truncate_string;
		}
		else if (mb_strlen($text) > $length) {
			return mb_substr($text, 0, $length).$truncate_string;
		}

		return $text;

	} // truncate()

	/**
	 * Word wrap long lines to line_length.
	 *
	 * @param string $text
	 * @param int    $line_length
	 * @return string
	 *
	 */
	public function word_wrap($text, $line_length = 80)
	{
	} // word_wrap()

	/**
	 * Take a string and return only the alpha chars.
	 *
	 * @param string $text
	 * @param string $more specify more chars than alphas.
	 * @return string
	 */
	public function alphaize($text, $more = '')
	{
		return preg_replace('/[^a-zàâçéèêëïîôùûüÿ'.$more.']/i', '', $text);
		
	} // alphaize()

	/**
	 * Take a string and return only the alphanum chars.
	 *
	 * @param string $text
	 * @param string $more specify more chars than alphanums.
	 * @return string
	 *
	 */
	public function alphanumize($text, $more = '')
	{
		return preg_replace('/[^a-zàâçéèêëïîôùûüÿ0-9'.$more.']/i', '', $text);
		
	} // alphanumize()

	/**
	 * HTMLentities a string
	 *
	 * @param string $text
	 * @return string
	 *
	 */
	public function entitize($text)
	{
		return htmlentities($text, ENT_QUOTES, 'UTF-8');
		
	} // entitize()

	/**
	 * Return a permalink
	 *
	 * @param string $text
	 * @return string
	 *
	 */
	public function to_permalink($text)
	{
		$text = $this->swap_accents($text);

		$text = preg_replace('/[^a-z0-9_]/i', '_', $text);
		$text = trim(preg_replace('/_+/', '_', $text), '_');

		return $text;

	} // to_permalink()

	/**
	 * Return a JSON string
	 *
	 * @param mixed $data
	 * @return Json string
	 *
	 */
	public function to_json($data)
	{
		return json_encode($data);
		
	} // to_json()

	/**
	 * Return data from json string
	 *
	 * @param string $json
	 * @return Data
	 *
	 */
	public function from_json($json, $assoc = false)
	{
		return json_decode($json, $assoc);
		
	} // from_json()

	/**
	 * Get a random alphanumeric string of $length.
	 *
	 * @param int $length
	 * @return string
	 *
	 */
	public function random_alphanum($length)
	{
		$string = "";
		$chars = "abcdefghijklmnopqrstuvwxyz1234567890";
		
		for ($i = 0; $i < $length; $i++) {
	    $string .= $chars{rand(0, 35)};
		}
		
		return $string;
		
	} // random_alphanum()

	/**
	 * Replace accents with letters.
	 *
	 * @param string $string
	 * @return string
	 *
	 */
	public function swap_accents($string)
	{
		$from = explode(' ', 'à â ç é è ê ë ï î ô ù û ü ÿ');
		$to =   explode(' ', 'a a c e e e e i i o u u u y');

		$string = mb_strtolower($string, 'UTF-8');
		$string = str_replace($from, $to, $string);

		return $string;

	} // swap_accents()

	/**
	 * nl2br()
	 *
	 * @param string $string
	 * @return string
	 *
	 */
	public function nl2br($string)
	{
		$string = str_replace("\r\n", "<br />", $string);
		$string = str_replace(array("<ul></br />", "<ol><br />", "</ul></br />", "</ol><br />", "</li><br />"), array("<ul>", "<ol>", "</ul>", "</ol>","</li>"), $string);
		
		return $string;

	} // nl2br()

} // Lity_Helper_Text
