<?php

/**
 * Date
 *
 * @see <a href="http://php.net/manual/en/function.strftime.php">PHP strftime</a>
 * @author  Wibeset <support@wibeset.com>
 * @package helpers
 * 
 */

class Lity_Helper_Date
{
	/**
	 * Return timestamp into words
	 *
	 * @see <a href="http://php.net/manual/en/function.strftime.php">PHP strftime</a>
	 * @param  int    $timestamp 
	 * @param  string $format    default is 'Month day, Year' (ex: Nov. 29, 2006)
	 * @return string 
	 * 
	 */
	public function time_in_words($timestamp, $format = '%b. %d, %G')
	{
		return strftime($format, (int)$timestamp);
	} // time_in_words()

	/**
	 * Get number of time in words from $from_time to now.
	 * 
	 * @param  int    $from_time timestamp
	 * @return string 
	 * 
	 */
	public function time_ago_in_words($from_time)
	{
		$difference = time() - $from_time;

		if ($difference < 60)
			return $difference." seconds ago";
		else {
			$difference = round($difference / 60);
			if ($difference < 60)
				return $difference." minutes ago";
			else {
				$difference = round($difference / 60);
				if ($difference < 24)
					return $difference." hours ago";
				else {
					$difference = round($difference / 24);
					if ($difference < 7)
						return $difference." days ago";
					else {
						$difference = round($difference / 7);
						return $difference." weeks ago";
					}
				}
			}
		}

	} // time_ago_in_words()

	/**
	 * Get number of time from $from_time to now.
	 * 
	 * @param  int $from_time timestamp
	 * @return array elapse difference
	 *               type   second(s), minute(s), hour(s), day(s) or week(s)
	 * 
	 */
	public function time_ago($from_time)
	{
		$difference = time() - $from_time;

		if ($difference < 60)
			return array('elapse' => $difference, 'type' => 'second'.($difference > 1 ? 's' : ''));
		else {
			$difference = round($difference / 60);
			if ($difference < 60)
				return array('elapse' => $difference, 'type' => 'minute'.($difference > 1 ? 's' : ''));
			else {
				$difference = round($difference / 60);
				if ($difference < 24)
					return array('elapse' => $difference, 'type' => 'hour'.($difference > 1 ? 's' : ''));
				else {
					$difference = round($difference / 24);
					if ($difference < 7)
						return array('elapse' => $difference, 'type' => 'day'.($difference > 1 ? 's' : ''));
					else {
						$difference = round($difference / 7);
						return array('elapse' => $difference, 'type' => 'week'.($difference > 1 ? 's' : ''));
					}
				}
			}
		}

	} // time_ago()

	/**
	 * Extract day from a timestamp
	 *
	 * @param  int $timestamp
	 * @return int 
	 * 
	 */
	public function select_day($timestamp)
	{
		return (int)date($timestamp, 'j');
	} // select_day()

	/**
	 * Extract month from a timestamp
	 *
	 * @param  int $timestamp
	 * @return int
	 * 
	 */
	public function select_month($timestamp)
	{
		return (int)date($timestamp, 'n');
	} // select_month()

	/**
	 * Extract year from a timestamp
	 *
	 * @param  int $timestamp
	 * @return int
	 * 
	 */
	public function select_year($timestamp)
	{
		return (int)date($timestamp, 'Y');
	} // select_year()

	/**
	 * Extract hour from a timestamp
	 *
	 * @param  int $timestamp
	 * @return int
	 * 
	 */
	public function select_hour($timestamp)
	{
		return (int)date($timestamp, 'G');
	} // select_hour()

	/**
	 * Extract minute from a timestamp
	 *
	 * @param  int $timestamp
	 * @return int
	 * 
	 */
	public function select_minute($timestamp)
	{
		return (int)date($timestamp, 'i');
	} // select_minute()

	/**
	 * Extract second from a timestamp
	 *
	 * @param  int $timestamp
	 * @return int
	 * 
	 */
	public function select_second($timestamp)
	{
		return (int)date($timestamp, 's');
	} // select_second()

	/**
	 * Get previous month in word from a timestamp
	 *
	 * @param  int    $timestamp
	 * @param  string $format    default is %B
	 * @return string for example, 'december'
	 * 
	 */
	public function previous_month_in_word($timestamp, $format = '%B')
	{
		return strftime($format, strtotime('-'.date('j', $timestamp).' days', $timestamp));
	} // previous_month_in_word()

	/**
	 * Get next month in word from a timestamp
	 *
	 * @param  int    $timestamp
	 * @param  string $format    default is %B
	 * @return string for example, 'january'
	 * 
	 */
	public function next_month_in_word($timestamp, $format = '%B')
	{
		if ((int)date('d', $timestamp) > 15)
			return strftime($format, strtotime('+16 days', $timestamp));
		return strftime($format, strtotime('next month', $timestamp));
	} // next_month_in_word()
	
	/**
	 * Get number of days from seconds
	 * 
	 * @param int $seconds number of seconds
	 * @return int Days
	 * 
	 */
	public function number_of_days($seconds)
	{
		return (int)($seconds / 60 / 60 / 24);
		
	} // number_of_days()

} // Lity_Helper_Date
