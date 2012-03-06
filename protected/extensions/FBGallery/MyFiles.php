<?php
	/**
	* MyFiles class file.
	*
	* @author Ovidiu Pop <matricks@webspider.ro>
	* @link http://www.webspider.ro/
	* @copyright Copyright &copy; 2010 Ovidiu Pop
	* Dual licensed under the MIT and GPL licenses:
	* http://www.opensource.org/licenses/mit-license.php
	* http://www.gnu.org/licenses/gpl.html
	*
	*/

abstract class MyFiles
{
	public function cleanItemTitle($str)
	{
		return preg_replace("/[^a-zA-Z0-9-_.,\s]/", " ", $str);
	}


	public function cleanFileName($str)
	{
		return preg_replace("/[^a-z0-9-_.]/", "_", strtolower($str));
	}


	/**
	*Create a new folder with given permissions
	* @param string $newdir - the name for new folder
	* @param string $rights - permission to be set on folder - default 0777
	* return void
	*/
	public function NewFolder($newdir, $rights=0777)
	{
		$old_mask = umask(0);
		if(!file_exists($newdir))
		{
			if(!mkdir($newdir, $rights, true))
				return false;
			else
				return true;
		}else
			return true;
		
		umask($old_mask);
	}

	/**
	*return an array with all files from a directory
	* @param string $dir - the directory name
	* @param string $ext - the file's extension type - default any file *
	* @param boolean $fullpath - false-return only filename, true-include fullpath
	* return array
	*/
	public function filesFromDir($dir, $ext="*", $fullpath=false)
	{
		$files = glob($dir . '*.'.$ext) ? glob($dir . '*.'.$ext) : array();
		if($fullpath)
			return $files;

		$arr = array();
		foreach($files as $file)
		{
			$arr[] = str_replace("$dir", "", $file);
		}
		return $arr;
	}


	/**
	* Remove all content from a folder, but not folder itself
	* @param string $dirContainer - the folder name
	* return void
	*/
	public function emptyFolder($dirContainer){
		$dir = opendir( $dirContainer );
		while (false !== ($fname = readdir( $dir ))) {
			if(is_file($dirContainer."/".$fname)){ 
				unlink($dirContainer."/".$fname);
			}
		}
		closedir( $dir );
	}

	/**
	*Remove a file from a directory
	* @param string $dir - the directory name
	* @param string $file - the file name
	*/
	public static function deleteFile($dir, $file)
	{
		$dfile = $dir.$file;
		if(file_exists($dfile))
			return unlink($dfile);
		return true;
	}

	/**
	* Remove the directory and its content (all files and subdirectories).
	* @param string $dir the directory name
	* return void
	*/
	public function rmrf($dir) 
	{
		foreach (glob($dir) as $file)
		{
			if (is_dir($file)) {
				rmrf("$file/*");
				rmdir($file);
			} else {
				unlink($file);
			}
		}
	}

	/**
	* return string
	* Remove extension of a given file.
	* @param string $filename - the file name
	*/
	function RemoveExtension($fileName) 
	{ 
		$ext = strrchr($fileName, '.'); 
		if($ext !== false)
			$fileName = substr($fileName, 0, -strlen($ext)); 
		return $fileName; 
	}

	/**
	 * Converts a class name into space-separated words.
	 * For example, 'PostTag' will be converted as 'Post Tag' or 'Post tag'.
	 * @param string name - the string to be converted
	 * @param boolean $ucwords - whether to capitalize the first letter in each word
	 * @param boolean $ucfirst - whether to capitalize only the first letter in first word
	 * if both $ucwords and $ucfirst are set to true, ucwords will be returned
	 * @return string - the resulting words
	*/
	public function class2name($name, $ucwords=true, $ucfirst=false)
	{
		$result=trim(strtolower(str_replace('_',' ',preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $name))));

		if($ucwords) 
			return ucwords($result);

		if($ucfirst) 
			return ucfirst($result);

		return $result;
	}


	public function moveFile($from, $to)
	{
		rename($from, $to);
	}

	public function moveAllFiles($from, $to)
	{
		$files = MyFiles::filesFromDir($from);
		foreach($files as $file)
		{
			if(!rename($from.$file, $to.$file))
				throw new Exception(Yii::t('app', 'Error: Couldn\'t move files! Please check permissions.'));
		}
	}



}