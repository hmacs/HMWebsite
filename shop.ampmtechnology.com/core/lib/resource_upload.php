<?php
/*
 * jQuery File Upload Plugin PHP Example 5.2.7
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://creativecommons.org/licenses/MIT/
 */

// array_replace_recursive exist in php >= 5.3.0
if (!function_exists('array_replace_recursive')) {
    function array_replace_recursive($array, $array1)
    {
        function recurse($array, $array1)
        {
            foreach ($array1 as $key => $value) {
                // create new key in $array, if it is empty or not an array
                if (!isset($array[$key]) || (isset($array[$key]) && !is_array($array[$key]))) {
                    $array[$key] = array();
                }

                // overwrite the value in the base array
                if (is_array($value)) {
                    $value = recurse($array[$key], $value);
                }
                $array[$key] = $value;
            }

            return $array;
        }

        // handle the arguments, merge one by one
        $args = func_get_args();
        $array = $args[0];
        if (!is_array($array)) {
            return $array;
        }
        for ($i = 1; $i < count($args); $i++) {
            if (is_array($args[$i])) {
                $array = recurse($array, $args[$i]);
            }
        }

        return $array;
    }
}

class ResourceUploadHandler
{
    private $options;

    function __construct($options = null)
    {
        $this->options = array(
            'script_url'              => $_SERVER['PHP_SELF'],
            'upload_dir'              => dirname(__FILE__).'/files/',
            'upload_url'              => dirname($_SERVER['PHP_SELF']).'/files/',
            'param_name'              => 'files',
            // The php.ini settings upload_max_filesize and post_max_size
            // take precedence over the following max_file_size setting:
            'max_file_size'           => null,
            'min_file_size'           => 1,
            'accept_file_types'       => '/.+$/i',
            'max_number_of_files'     => null,
            'discard_aborted_uploads' => true,
            'image_versions'          => array(
                // Uncomment the following version to restrict the size of
                // uploaded images. You can also add additional versions with
                // their own upload directories:
                /*
                                'large' => array(
                                    'upload_dir' => dirname(__FILE__).'/files/',
                                    'upload_url' => dirname($_SERVER['PHP_SELF']).'/files/',
                                    'max_width' => 1920,
                                    'max_height' => 1200
                                ),
                                */
                'thumbnail' => array(
                    'upload_dir' => dirname(__FILE__).'/thumbnails/',
                    'upload_url' => dirname($_SERVER['PHP_SELF']).'/thumbnails/',
                    'max_width'  => 80,
                    'max_height' => 80,
                ),
            ),
        );
        if ($options) {
            $this->options = array_replace_recursive($this->options, $options);
        }
    }

    public function get()
    {
        $file_name = isset($_REQUEST['file']) ?
            basename(stripslashes($_REQUEST['file'])) : null;
        if ($file_name) {
            $info = $this->get_file_object($file_name);
        } else {
            $info = $this->get_file_objects();
        }

        return $info;
    }

    /**
     * @param string $file_name
     *
     * @return null|stdClass
     */
    private function get_file_object($file_name)
    {
        $file_path = $this->options['upload_dir'].$file_name;
        if (is_file($file_path) && $file_name[0] !== '.') {
            $file = new stdClass();
            $file->name = $file_name;
            $file->size = filesize($file_path);
            $file->url = $this->options['upload_url'].rawurlencode($file->name);
            foreach ($this->options['image_versions'] as $version => $options) {
                if (is_file($options['upload_dir'].$file_name)) {
                    $file->{$version.'_url'} = $options['upload_url']
                        .rawurlencode($file->name);
                }
            }
            $file->delete_url = $this->options['script_url']
                .'?file='.rawurlencode($file->name);
            $file->delete_type = 'DELETE';

            return $file;
        }

        return null;
    }

    private function get_file_objects()
    {
        return array_values(array_filter(array_map(
            array($this, 'get_file_object'),
            scandir($this->options['upload_dir'])
        )));
    }

    /**
     * @return array
     */
    public function post()
    {
        Registry::getInstance()->get('language')->load('common/resource_library');
        if (isset($_FILES[$this->options['param_name']])) {
            $upload = $_FILES[$this->options['param_name']];
        } else {
            $upload = array(
                'tmp_name' => null,
                'name'     => null,
                'size'     => null,
                'type'     => null,
                'error'    => 'emptyResult',
            );
        }

        $info = array();
        if (!is_array($upload['tmp_name'])) {
            $upload['tmp_name'] = array(0 => $upload['tmp_name']);
            $upload['name'] = array(0 => $upload['name']);
            $upload['size'] = array(0 => $upload['size']);
            $upload['type'] = array(0 => $upload['type']);
        }

        foreach ($upload['tmp_name'] as $index => $value) {
            $info[] = $this->handle_file_upload(
                $upload['tmp_name'][$index],
                isset($_SERVER['HTTP_X_FILE_NAME']) ? $_SERVER['HTTP_X_FILE_NAME'] : $upload['name'][$index],
                isset($_SERVER['HTTP_X_FILE_SIZE']) ? $_SERVER['HTTP_X_FILE_SIZE'] : $upload['size'][$index],
                isset($_SERVER['HTTP_X_FILE_TYPE']) ? $_SERVER['HTTP_X_FILE_TYPE'] : $upload['type'][$index],
                $upload['error'][$index]
            );
        }

        return $info;
    }

    /**
     * @param string $uploaded_file
     * @param string $name
     * @param int    $size
     * @param string $type
     * @param int    $error
     *
     * @return stdClass
     */
    private function handle_file_upload($uploaded_file, $name, $size, $type, $error)
    {

        $file = new stdClass();
        // Remove path information and dots around the filename, to prevent uploading
        // into different directories or replacing hidden system files.
        // Also remove control characters and spaces (\x00..\x20) around the filename:
        $name = $name == '' ? 'UnknownFile' : $name;
        $name = str_replace(" ", "_", stripslashes($name));

        // basename removes first part of filename like ????????_??????????.zip (with non-latin characters).
        // Basename of that name will be _??????????.zip
        if ($this->strpos_array($name, array('/', '\'')) !== false) {
            $name = basename($name);

        } else {
            $res = strrpos($name, '/');
            if ($res !== false) {
                $name = substr($name, $res + 1);

            }
            $res = strrpos($name, '\'');
            if ($res !== false) {
                $name = substr($name, $res + 1);

            }
        }

        $file->name = trim($name, ".\x00..\x20");
        $file->size = intval($size);
        $file->type = $type;

        // error check
        if ($error) {
            $error_text = getTextUploadError($error);
        }
        $error_text = $this->has_error($uploaded_file, $file, $error_text);
        if (!$error_text && $file->name) {
            if (!is_dir(DIR_RESOURCE.$this->options['upload_dir'])) {
                $path = '';
                $directories = explode('/', str_replace('../', '', $this->options['upload_dir']));
                foreach ($directories as $directory) {
                    $path = $path.'/'.$directory;
                    if (!is_dir(DIR_RESOURCE.$path)) {
                        @mkdir(DIR_RESOURCE.$path, 0777);
                        chmod(DIR_RESOURCE.$path, 0777);
                    }
                }
            }
            $rs_dir = DIR_RESOURCE.$this->options['upload_dir'];
            if (!is_dir($rs_dir) || !is_writeable($rs_dir)) {
                $error_text = "Please check 'resources' folder permissions. (".$rs_dir.")";
            }
        }
        if (!$error_text && $file->name) {
            $file_path = DIR_RESOURCE.$this->options['upload_dir'].$file->name;
            $append_file = !$this->options['discard_aborted_uploads']
                && is_file($file_path)
                && $file->size > filesize($file_path);
            clearstatcache();
            if ($uploaded_file && is_uploaded_file($uploaded_file)) {
                // multipart/formdata uploads (POST method uploads)
                if ($append_file) {
                    file_put_contents(
                        $file_path,
                        fopen($uploaded_file, 'r'),
                        FILE_APPEND
                    );
                } else {
                    $result = move_uploaded_file($uploaded_file, $file_path);
                    if ($result === false) {
                        $file->error = 'Failed! Check error log for details.';
                        $error_text = 'Error! Unable to move uploaded file from '.$uploaded_file.' to '.$file_path;
                    }
                }
            } else {
                // Non-multipart uploads (PUT method support)
                file_put_contents(
                    $file_path,
                    fopen('php://input', 'r'),
                    $append_file ? FILE_APPEND : 0
                );
            }
            $file_size = filesize($file_path);
            if ($file_size === $file->size) {
                $file->url = $this->options['upload_url'].rawurlencode($file->name);
                chmod($file_path, 0777);
            } else {
                if ($this->options['discard_aborted_uploads']) {
                    unlink($file_path);
                    $file->error = 'Failed! Check error log for details.';
                    if (!$file_size) {
                        $error_text = 'Unable to save file on disk! Please check "resources" folder permissions!';
                    } else {
                        $error_text = 'Unable to save file '.basename($file_path).' on disk! Integrity check error!';
                    }
                }
            }
            $file->size = $file_size;
            $file->delete_url = $this->options['script_url'].'&resource_id=%ID%';
            $file->delete_type = 'DELETE';
        } else {
            $file->error = $error_text;
        }

        if ($error_text) {
            //todo: add this into abc sys-log in the future
            $error = new AError($error_text);
            $error->toDebug();
        }

        return $file;
    }

    /**
     * @param $haystack
     * @param $needle
     *
     * @return bool|int
     */
    public function strpos_array($haystack, $needle)
    {
        if (!is_array($needle)) {
            $needle = array($needle);
        }
        foreach ($needle as $what) {
            if (($pos = strpos($haystack, $what)) !== false) {
                return $pos;
            }
        }

        return false;
    }

    /**
     * @param string   $uploaded_file
     * @param stdClass $file
     * @param string   $error
     *
     * @return string
     */
    private function has_error($uploaded_file, $file, $error)
    {
        if ($error) {
            return $error;
        }
        if (!preg_match($this->options['accept_file_types'], $file->name)) {
            return Registry::getInstance()->get('language')->get('error_acceptFileTypes');
        }
        if ($uploaded_file && is_uploaded_file($uploaded_file)) {
            $file_size = filesize($uploaded_file);
        } else {
            $file_size = $_SERVER['CONTENT_LENGTH'];
        }

        if ($this->options['max_file_size']
            && (
                $file_size > $this->options['max_file_size']
                || $file->size > $this->options['max_file_size'])
        ) {
            return Registry::getInstance()->get('language')->get('error_maxFileSize');
        }
        if ($this->options['min_file_size']
            && $file_size < $this->options['min_file_size']
        ) {
            return Registry::getInstance()->get('language')->get('error_minFileSize');
        }
        if (is_int($this->options['max_number_of_files'])
            && (
                count($this->get_file_objects()) >= $this->options['max_number_of_files'])
        ) {
            return Registry::getInstance()->get('language')->get('error_maxNumberOfFiles');
        }

        return $error;
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $file_name = isset($_REQUEST['file']) ?
            basename(stripslashes($_REQUEST['file'])) : null;
        $file_path = $this->options['upload_dir'].$file_name;
        $success = is_file($file_path) && $file_name[0] !== '.' && unlink($file_path);

        return $success;
    }

}
