<?php

/**
 * Duration
 * 
 * @author  Wibeset <support@wibeset.com>
 * @package helpers
 * 
 */

class Lity_Helper_Duration
{
	/**
	 * Return an array of date segments.
	 *
	 * @param  int   $seconds Number of seconds to be parsed
	 * @return mixed An array containing named segments
	 * 
	 */
	public function duration($seconds, $periods = null)
	{
		// Define time periods
		if (!is_array($periods)) {
			$periods = array (
												'years'     => 31556926,
												'months'    => 2629743,
												'weeks'     => 604800,
												'days'      => 86400,
												'hours'     => 3600,
												'minutes'   => 60,
												'seconds'   => 1
												);
		}

		// Loop
		$seconds = (float) $seconds;
		foreach ($periods as $period => $value) {
			
			$count = floor($seconds / $value);

			if ($count == 0) {
				continue;
			}

			$values[$period] = $count;
			$seconds = $seconds % $value;
		}

		// Return
		if (empty($values)) {
			$values = null;
		}

		return $values;
		
	} // duration()

	/**
   * Return a string of time periods.
   *
   * @param  int    $duration 
	 * @return string
	 * 
	 */
	public function in_words($duration)
	{
		$duration = $this->duration($duration);

		foreach ($duration as $key => $value) {
			
			$segment_name = substr($key, 0, -1);
			$segment = $value . ' ' . $segment_name;

			// Plural
			if ($value != 1) {
				$segment .= 's';
			}

			$words[] = $segment;
		}

		return implode(' ', $words);
		
	} // in_words()

} // Lity_Helper_Duration
