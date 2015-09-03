<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Directory Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/directory_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Create a Directory Map
 *
 * Reads the specified directory and builds an array
 * representation of it.  Sub-folders contained with the
 * directory will be mapped as well.
 *
 * @access	public
 * @param	string	path to source
 * @param	int		depth of directories to traverse (0 = fully recursive, 1 = current dir, etc)
 * @return	array
 */
if ( ! function_exists('directory_map'))
{
	function directory_map($source_dir, $directory_depth = 0, $hidden = FALSE)
	{
		if ($fp = @opendir($source_dir))
		{
			$filedata	= array();
			$new_depth	= $directory_depth - 1;
			$source_dir	= rtrim($source_dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

			while (FALSE !== ($file = readdir($fp)))
			{
				// Remove '.', '..', and hidden files [optional]
				if ( ! trim($file, '.') OR ($hidden == FALSE && $file[0] == '.'))
				{
					continue;
				}

				if (($directory_depth < 1 OR $new_depth > 0) && @is_dir($source_dir.$file))
				{
					$filedata[$file] = directory_map($source_dir.$file.DIRECTORY_SEPARATOR, $new_depth, $hidden);
				}
				else
				{
					$filedata[] = $file;
				}
			}

			closedir($fp);
			return $filedata;
		}

		return FALSE;
	}
}

/**
 * Copy a whole Directory
 *
 * Copy a directory recrusively ( all file and directories inside it )
 * https://github.com/EllisLab/CodeIgniter/wiki/copy-directory
 *
 * @access    public
 * @param    string    path to source dir
 * @param    string    path to destination dir
 * @return    array
 */
if(!function_exists('directory_copy'))
{
    function directory_copy($srcdir, $dstdir, $mode = 0777)
    {
        //preparing the paths
        $srcdir=rtrim($srcdir,'/');
        $dstdir=rtrim($dstdir,'/');

        //creating the destination directory
        if(!is_dir($dstdir)) {
          mkdir($dstdir, $mode);
          @chmod ($dstdir, $mode);
        }

        //Mapping the directory
        $dir_map=directory_map($srcdir);

        foreach($dir_map as $object_key=>$object_value)
        {
            if(is_numeric($object_key)) {
              copy($srcdir.'/'.$object_value,$dstdir.'/'.$object_value);//This is a File not a directory
              @chmod ($dstdir.'/'.$object_value, $mode);
            } else {
              directory_copy($srcdir.'/'.$object_key,$dstdir.'/'.$object_key, $mode);//this is a directory
            }
        }
    }
}

if(!function_exists('directory_delete'))
{
    function directory_delete($dir, $is_root = true)
    {
        $dir = rtrim($dir, DIRECTORY_SEPARATOR);

        if ( ! $current_dir = @opendir($dir))
        {
          return FALSE;
        }

        while (FALSE !== ($filename = @readdir($current_dir)))
        {
          if ($filename != "." and $filename != "..")
          {
            if (is_dir($dir.DIRECTORY_SEPARATOR.$filename))
            {
              // Ignore empty folders
              if (substr($filename, 0, 1) != '.')
              {
                directory_delete($dir.DIRECTORY_SEPARATOR.$filename);
              }
            }
            else
            {
              unlink($dir.DIRECTORY_SEPARATOR.$filename);
            }
          }
        }
        @closedir($current_dir);

        return $is_root ? @rmdir($dir) : true;
    }
}

if(!function_exists('directory_clean'))
{
    function directory_clean($dir)
    {
        directory_delete ($dir, false);
    }
}

/* End of file directory_helper.php */
/* Location: ./system/helpers/directory_helper.php */