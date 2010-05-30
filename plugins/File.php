<?php

/**
 * Convenience API for reading, writing and appending to files
 *
 * @author  Wibeset <support@wibeset.com>
 * @package plugins
 *
 */

class Lity_Plugin_File {

	/**
	 * @var $_path
	 */
	protected $_path;

	/**
	 * @var $_folder
	 */
	protected $_folder;

	/**
	 * @var $_name
	 */
	protected $_name;

	/**
	 * Set file.
	 *
	 * @param  string $path
	 * @param  bool   $create create file if true
	 *
	 */
	public function set($path, $create = false) {
		$this->_path = $path;
		$this->_folder = dirname($path);
		$this->_name = basename($path);
		if ($create) $this->create();
	} // set()

	/**
	 * Read file's content
	 *
	 * @return string
	 *
	 */
	public function read() {
		return file_get_contents($this->get_full_path());
	} // read()

	/**
	 * Append $data to file
	 *
	 * @param  string  $data data to append to file
	 * @return boolean
	 *
	 */
	public function append($data) {
		return $this->write($data, 'a');
	} // append()

	/**
	 * Write $data to file
	 *
	 * @param  string $data
	 * @param  string $mode 'w' = write
	 *                      'a' = append
	 * @return bool
	 *
	 */
	public function write($data, $mode = 'w') {

		// create handle
		if (!($handle = fopen($this->get_full_path(), $mode)))
			return false;

		// write to file
		if (!fwrite($handle, $data))
			return false;

		// close file
		if (!fclose($handle))
			return false;

		return true;

	} // write()

	/**
	 * Get md5 checksum (see http://www.php.net/md5_file)
	 *
	 * @return string
	 *
	 */
	public function get_md5() {
		return md5_file($this->get_full_path());
	} // get_md5()

	/**
	 * Get file's size
	 *
	 * @return int
	 *
	 */
	public function get_size() {
		return filesize($this->get_full_path());
	} // get_size()

	/**
	 * Get file's extension
	 *
	 * @todo
	 * @return string
	 *
	 */
	public function get_extension() {
		$info = pathinfo($this->get_full_path());
		return $info['extension'];
	} // get_extension()

	/**
	 * Get file's name
	 *
	 * @return string
	 *
	 */
	public function get_name() {
		return $this->_name;
	} // get_name()

	/**
	 * Get file's owner
	 *
	 * @return int
	 *
	 */
	public function get_owner() {
		return fileowner($this->get_full_path());
	} // get_owner()

	/**
	 * Get file's group
	 *
	 * @return int
	 *
	 */
	public function get_group() {
		return filegroup($this->get_full_path());
	} // get_group()

	/**
	 * Create the file
	 *
	 * @return boolean
	 *
	 */
	public function create() {

		$dirs = explode('/', $this->get_full_path());	 

		if (count($dirs) > 1) {
			array_pop($dirs);
			@mkdir(implode('/', $dirs), 0777, true);
		}

		$handle = fopen($this->get_full_path(), 'w+');
		fclose($handle);

	} // create()

	/**
	 * Is file exists?
	 *
	 * @return boolean
	 *
	 */
	public function exists() {
		return file_exists($this->get_full_path());
	} // exists()

	/**
	 * Delete the file
	 *
	 * @return boolean
	 *
	 */
	public function destroy() {
		return unlink($this->get_full_path());
	} // destroy()

	/**
	 * Is writable?
	 *
	 * @return boolean
	 *
	 */
	public function writable() {
		return is_writable($this->get_full_path());
	} // writable()

	/**
	 * Is executable?
	 *
	 * @return boolean
	 *
	 */
	public function executable() {
		return is_executable($this->get_full_path());
	} // executable()

	/**
	 * Is readable?
	 *
	 * @return boolean
	 *
	 */
	public function readable() {
		return is_readable($this->get_full_path());
	} // readable()

	/**
	 * Get last access timestamp
	 *
	 * @return int timestamp
	 *
	 */
	public function get_last_access() {
		return fileatime($this->get_full_path());
	} // get_last_access()

	/**
	 * Get last modified timestamp
	 *
	 * @return int timestamp
	 *
	 */
	public function get_last_change() {
		return filemtime($this->get_full_path());
	} // get_last_change()

	/**
	 * Get folder
	 *
	 * @return string
	 *
	 */
	public function get_folder() {
		return $this->_folder;
	} // get_folder()

	public function get_chmod() {
	} // get_chmod()

	/**
	 * Get full path
	 *
	 * @return string
	 *
	 */
	public function get_full_path() {
		return $this->_path;
	} // get_full_path()

} // Lity_Plugin_File
