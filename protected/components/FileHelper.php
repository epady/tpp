<?php

class FileHelper extends CFileHelper
{

    /**
     * 生成图片
     * 
     * @param  [type] $dir      [description]
     * @param  [type] $contents [description]
     * @return [type]           [description]
     */
    static function file_force_contents($dir, $contents){
        $parts = explode('/', $dir);
        $file = array_pop($parts);
        $dir = '';
        foreach($parts as $part)
            if(!is_dir($dir .= "/$part")) mkdir($dir);
        file_put_contents("$dir/$file", $contents);
    }

    /**
     * 创建一个目录树，失败抛出异常
     *
     * 用法：
     * @code php
     * Helper_Filesys::mkdirs('/top/second/3rd');
     * @endcode
     *
     * @param string $dir 要创建的目录
     * @param int $mode 新建目录的权限
     *
     * @throw Q_CreateDirFailedException
     */
    static function mkdirs($dir, $mode = 0777)
    {
        if (!is_dir($dir))
        {
            $ret = @mkdir($dir, $mode, true);
            if (!$ret)
            {
                throw new CException($dir);
            }
        }
        return true;
    }

    /**
     * 删除指定目录及其下的所有文件和子目录，失败抛出异常
     *
     * 用法：
     * @code php
     * // 删除 my_dir 目录及其下的所有文件和子目录
     * Helper_Filesys::rmdirs('/path/to/my_dir');
     * @endcode
     *
     * 注意：使用该函数要非常非常小心，避免意外删除重要文件。
     *
     * @param string $dir 要删除的目录
     * @param Boolean $delete_self 是否删除本身目录
     * @throw Q_RemoveDirFailedException
     */
    static function rmdirs($dir, $delete_self = true)
    {
        $dir = realpath($dir);
        if ($dir == '' || $dir == '/' || (strlen($dir) == 3 && substr($dir, 1) == ':\\'))
        {
            // 禁止删除根目录
            throw new Exception('禁止删除：' . $dir);
        }

        // 遍历目录，删除所有文件和子目录
        if (false !== ($dh = opendir($dir)))
        {
            while (false !== ($file = readdir($dh)))
            {
                if ($file == '.' || $file == '..')
                {
                    continue;
                }

                $path = $dir . DIRECTORY_SEPARATOR . $file;
                if (is_dir($path))
                {
                    self::rmdirs($path);
                } else
                {
                    unlink($path);
                }
            }
            closedir($dh);
            if ($delete_self)
            {
                if (@rmdir($dir) == false)
                {
                    throw new Exception('删除失败：' . $dir);
                }
            }
        } else
        {
            throw new Exception('删除失败：' . $dir);
        }
    }

    static function log_result($msg, $file = 'return')
    {
        // 创建当前日志文件  以日期命名
        $fileName = $file . '_' . date('Y-m-d', time()) . '.log';
        // 存放目录
        $logpath = Yii::app()->runtimePath . '/alipay';

        if (!file_exists($logpath))
        {
            $ret = @mkdir($logpath, 0777, true);
            if (!$ret)
            {
                throw new Exception($logpath);
            }
        }
        $logfile = $logpath . '/' . $fileName;

        // 添加日志
        file_put_contents($logfile, date('Y-m-d H:i:s') . ' ' . $msg . "\n", FILE_APPEND);
    }

}