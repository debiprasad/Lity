<?php

/**
 * Pagination
 * 
 */

class Lity_Helper_Pagination
{
	/**
	 * Return pagination as links in html format
	 * 
	 * @param  array  $pagination   pagination from Activerecord
	 * @param  string $link         page's url containing {{page}}
	 * @param  int    $range        range before and after current page
	 * @param  bool   $nextprevious show next/previous button
	 * @return string pagination in html format
	 * 
	 */
	public function to_links($pagination, $url, $range = 5, $nextprevious = true)
	{
		if (substr($url, 0, 4) != 'http')
			$url = app()->config['urlbase'].$url;

		$urls = '';

		if ($pagination['last'] <= 1)
			return '';

		// previous
		if ($pagination['current'] > 1 && $nextprevious)
			$urls .= '<a href="'.str_replace('{{page}}', $pagination['previous'], $url).'" title="Go to previous page">&laquo;</a>';

		// first
		if ($pagination['current'] > 1)
			$urls .= '<a href="'.str_replace('{{page}}', 1, $url).'" title="Go to first page">1</a>';

		// ...
		if ($pagination['current'] - $range > 1)
			$urls .= '<span>...</span>';

		// 5 pages before
		for ($page = 1; $page <= $range; $page++) {
	    $p = $pagination['current'] - ($range+1-$page);
	    if ($p > 1) $urls .= '<a href="'.str_replace('{{page}}', $p, $url).'" title="Go to page '.$p.'">'.$p.'</a>';
		}

		// current
		$urls .= '<a class="current" href="'.str_replace('{{page}}', $pagination['current'], $url).'" title="Current page">'.$pagination['current'].'</a>';

		// 5 pages after
		for ($page = 1; $page <= $range; $page++) {
	    $p = $pagination['current'] + $page;
	    if ($p < $pagination['last']) $urls .= '<a href="'.str_replace('{{page}}', $p, $url).'" title="Go to page '.$p.'">'.$p.'</a>';
		}

		// ...
		if ($pagination['last'] - 1 > $pagination['current'] + $range)
			$urls .= '<span>...</span>';

		// last
		if ($pagination['current'] < $pagination['last'])
			$urls .= '<a href="'.str_replace('{{page}}', $pagination['last'], $url).'" title="Go to last page">'.$pagination['last'].'</a>';

		// next
		if ($pagination['current'] < $pagination['last'] && $nextprevious)
			$urls .= '<a href="'.str_replace('{{page}}', $pagination['next'], $url).'" title="Go to next page">&raquo;</a>';

		return $urls;

	} // to_links()

} // Lity_Helper_Pagination
