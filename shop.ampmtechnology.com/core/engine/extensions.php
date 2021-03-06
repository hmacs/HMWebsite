<?php
/*------------------------------------------------------------------------------
  $Id$

  AbanteCart, Ideal OpenSource Ecommerce Solution
  http://www.AbanteCart.com

  Copyright © 2011-2018 Belavier Commerce LLC

  This source file is subject to Open Software License (OSL 3.0)
  License details is bundled with this package in the file LICENSE.txt.
  It is also available at this URL:
  <http://www.opensource.org/licenses/OSL-3.0>

 UPGRADE NOTE:
   Do not edit or add to this file if you wish to upgrade AbanteCart to newer
   versions in the future. If you wish to customize AbanteCart for your
   needs please refer to http://www.AbanteCart.com for more information.
------------------------------------------------------------------------------*/

/**
 * ExtensionsApi
 * in a coherent structure.
 *
 * @package ExtensionsApi
 */

/**
 * short description.
 */
abstract class Extension
{

    /**
     * @var boolean Allow this extension to overload "hook" calls?
     */
    public $overloadHooks = false;

    /**
     * @var ExtensionsApi The current {@link ExtensionsApi} that has loaded this extension.
     */
    public $ExtensionsApi = null;

    /**
     * @var $baseObject AController The current object being plugged into.
     */
    protected $baseObject = null;

    /**
     * @var string  - name of method of controller that call hook
     */
    protected $baseObject_method = '';
    const REPLACED_METHOD = 'Indicates that a method with void return has been replaced';

    /**
     * Load the current object being plugged into.
     *
     * @param object $object The current object being plugged into.
     * @param string $method
     */
    public function loadBaseObject($object, $method)
    {
        //Can add wrapper class with set of mirror methods and properties to connect to base objects
        $this->baseObject = $object;
        $this->baseObject_method = $method;
    }

    /**
     * Load the current {@link ExtensionsApi} that has loaded this extension.
     *
     * @param ExtensionsApi $ExtensionsApi
     *
     * @internal param \ExtensionsApi $object The current
     *  {@link ExtensionsApi} that has loaded this extension. that has loaded this extension.
     */
    public function loadExtensionsApi(ExtensionsApi $ExtensionsApi)
    {
        $this->ExtensionsApi = $ExtensionsApi;
    }

    public function __call($method, $args)
    {
        if ((strpos($method, 'hk') === 0) && ($this->ExtensionsApi !== null)) {
            array_unshift($args, $this);
            $return = call_user_func_array(array($this->ExtensionsApi, $method), $args);
            return $return;
        }
        return null;
    }
}

/**
 * short description.
 *
 * long description.
 *
 * @package ExtensionCollection
 */
class ExtensionCollection
{

    protected $extensions = array();

    /**
     * @param array $extensions
     *
     * @throws Exception {when encounters extension not of class extension.}
     */
    public function __construct(array $extensions)
    {
        foreach ($extensions as $extension) {
            // another extension collection passed in
            if (($extension instanceof ExtensionCollection) === true) {
                $this->extensions = array_merge($this->extensions, $extension->extensions);
                continue;
            }

            if (is_object($extension) === false) {
                $extension = new $extension();
            }

            if (($extension instanceof Extension) === false) {
                $class = get_class($extension);
                if (!($parent = get_parent_class($extension))) {
                    $parent = $class;
                }
                throw new Exception(
                    'Expected "'.$class.'" to be of class Extension; was "'.$parent.'" instead.'
                );
            }

            $this->extensions[get_class($extension)] = $extension;
        }
    }

    protected function dispatchMethod($method, $args)
    {
        $return = null;

        $baseObject = array_shift($args);
        /**
         * @var Extension $extension
         */
        foreach ($this->extensions as $extension) {
            if (!method_exists($extension, $method) && ($extension->overloadHooks === false)) {
                continue;
            }

            // If a extension is dispatching don't change the baseObject.
            // If another extension needs to access the dispatching extension,
            //   it can use $this->ExtensionsApi->extensionName.
            if (($baseObject instanceof Extension) === false) {
                $extension->loadBaseObject($baseObject, $args[0]);
                $extension->loadExtensionsApi($baseObject->ExtensionsApi);
            }

            $tmp_return = call_user_func_array(array($extension, $method), $args);
            //when around method hook - returns ONLY first result
            if (strpos($method, 'around') === 0 && method_exists($extension, $method)) {
                $return = $tmp_return;
                //for avoid functions
                if ($return === null) {
                    $return = Extension::REPLACED_METHOD;
                }
                return $return;
            }
            if ($tmp_return !== null) {
                $return = $tmp_return;
            }
        }

        return $return;
    }

    public function __get($property)
    {
        if (isset($this->extensions[$property])) {
            return $this->extensions[$property];
        }
        return false;
    }

    public function __call($method, $args)
    {
        $return = $this->dispatchMethod($method, $args);
        if (strpos($method, 'around') === 0) {
            //when no result from around-hook - set result to true to continue call-chain and run base method
            if ($return === null) {
                //set canrun to true
                $return = true;
            }
            //when hook has been called - return null to prevent run base method later
            //case for avoid methods and
            elseif ($return === Extension::REPLACED_METHOD || $return === true) {
                $return = null;
            }
        }
        return $return;
    }

}

/**
 * ExtensionsApi . An intricate or interwoven combination of elements or parts
 * in a coherent structure.
 *
 * long description.
 *
 * @property ADb    $db
 * @property ACache $cache
 * @method hk_InitData(object $baseObject, string $baseObjectMethod)
 * @method hk_UpdateData(object $baseObject, string $baseObjectMethod)
 * @method hk_ProcessData(object $baseObject, string $point_name = '', mixed $array = null)
 * @method hk_ValidateData(object $baseObject, array $args = array())
 * @method hk_confirm(object $baseObject, int $order_id, int $order_status_id, string $comment)
 * @method hk_create(object $baseObject, array $data, int $order_status_id)
 * @method hk_query(object $baseObject, string $sql, bool $noexcept)
 * @method hk_load(object $baseObject, string $block, string $mode)
 * @method hk_apply_promotions(object $baseObject, array $total_data, array $total)
 * @package MyExtensionsApi
 */
class ExtensionsApi
{
    /**
     * @var Registry
     */
    protected $registry;
    /**
     * @var array $extensions - array of extensions objects
     */
    protected $extensions;
    /**
     * @var array $extensions_dir - list of all extensions in extension dir
     */
    protected $extensions_dir;
    /**
     * @var array $enabled_extensions - array of enabled extensions
     */
    protected $enabled_extensions;
    /**
     * @var array $db_extensions - array of extensions stored in db
     */
    protected $db_extensions;
    /**
     * @var array $missing_extensions - array of extensions stored in db but missing folder in extensions dir
     */
    protected $missing_extensions;

    /**
     * @var array $extension_controllers - array of extensions controllers
     */
    protected $extension_controllers;

    /**
     * @var array $extension_models - array of extensions models
     */
    protected $extension_models;

    /**
     * @var array $extension_languages - array of extensions languages
     */
    protected $extension_languages;
    /**
     * @var $ExtensionsApi ExtensionsApi
     */
    protected $ExtensionsApi;

    /**
     * @var array $extension_templates - array of extensions templates
     */
    protected $extension_templates;
    /**
     * @var array
     */
    protected $extension_types = array(
        'extensions',
        'payment',
        'shipping',
        'template',
        'language',
        'tax',
    );

    public function __construct()
    {

        $this->registry = Registry::getInstance();
        $this->cache = $this->registry->get('cache');
        $this->extensions_dir = array();
        $this->db_extensions = array();
        $this->missing_extensions = array();

        $extensions = glob(DIR_EXT.'*', GLOB_ONLYDIR);
        if ($extensions) {
            foreach ($extensions as $ext) {
                //skip other directory not containing extensions
                if (is_file($ext.'/config.xml')) {
                    $ext_text_id = basename($ext);
                    $xml = @simplexml_load_file($ext.'/config.xml');
                    //be sure that extension dirname equal extension-text-id in config.xml
                    if ($xml !== false && (string)$xml->id == $ext_text_id) {
                        $this->extensions_dir[] = $ext_text_id;
                    }
                }
            }
        }

        if ($this->registry->has('db')) {

            $this->db = $this->registry->get('db');

            //get extensions from db
            $query = $this->getExtensionsList();
            foreach ($query->rows as $result) {
                if (trim($result['key'])) {
                    $this->db_extensions[] = $result['key'];
                }
            }

            //check if we have extensions that has record in db, but missing files
            // if so, disable them
            $this->missing_extensions = array_diff($this->db_extensions, $this->extensions_dir);
            if (!empty($this->missing_extensions)) {
                foreach ($this->missing_extensions as $ext) {
                    $warning = new AWarning($ext.' directory is missing');
                    $warning->toLog();
                }
            }

            //check if we have extensions in dir that has no record in db
            $diff = array_diff($this->extensions_dir, $this->db_extensions);
            if (!empty($diff)) {
                foreach ($diff as $ext) {
                    $data['key'] = $ext;
                    $data['status'] = 0;
                    $misext = new ExtensionUtils($ext);
                    $data['type'] = $misext->getConfig('type');
                    $data['version'] = $misext->getConfig('version');
                    $data['priority'] = $misext->getConfig('priority');
                    $data['category'] = $misext->getConfig('category');
                    $data['license_key'] = $this->registry->get('session')->data['package_info']['extension_key'];

                    if ($this->registry->has('extension_manager')) {
                        $this->registry->get('extension_manager')->add($data);
                    }
                }
            }
        }
    }

    /**
     * @param string $type
     *
     * @return array
     */
    public function getInstalled($type = '')
    {
        $cache_key = '';
        if ($this->cache && $this->cache->isCacheEnabled()) {
            $cache_key = 'extensions.installed';
            if ($type) {
                $cache_key .= ".type=".$type;
            }
            $load_data = $this->cache->pull($cache_key);
            if ($load_data !== false) {
                //if we have cache, return
                return $load_data;
            }
        }

        $type = (string)$type;
        $extension_data = array();
        if (in_array($type, $this->extension_types)) {
            $sql = "SELECT DISTINCT e.key
                    FROM ".$this->db->table("extensions")." e
                    RIGHT JOIN ".$this->db->table("settings")." s 
                        ON s.group = e.key
                    WHERE e.type = '".$this->db->escape($type)."'";
        } elseif ($type == 'exts') {
            $sql = "SELECT DISTINCT e.key
                    FROM ".$this->db->table("extensions")." e
                    RIGHT JOIN ".$this->db->table("settings")." s 
                        ON s.group = e.key
                    WHERE e.type IN ('".implode("', '", $this->extension_types)."')";
        } elseif ($type == '') {
            $sql = "SELECT DISTINCT e.key
                    FROM ".$this->db->table("extensions")." e
                    RIGHT JOIN ".$this->db->table("settings")." s ON s.group = e.key";
        } else {
            $sql = "SELECT DISTINCT e.key
                    FROM ".$this->db->table("extensions")." e";
        }

        $query = $this->db->query($sql);
        foreach ($query->rows as $result) {
            if ($result['key']) {
                $extension_data[] = $result['key'];
            }
        }

        if ($this->cache && $this->cache->isCacheEnabled()) {
            $this->cache->push($cache_key, $extension_data);
        }

        return $extension_data;
    }

    /**
     * @param string $key
     *
     * @return array
     */
    public function getExtensionInfo($key = '')
    {
        $cache_key = '';
        if ($this->cache && $this->cache->isCacheEnabled()) {
            $cache_key = 'extensions.details';
            if ($key) {
                $cache_key .= ".key=".$key;
            }
            $load_data = $this->cache->pull($cache_key);
            if ($load_data !== false) {
                //if we have cache, return
                return $load_data;
            }
        }

        $sql = "SELECT * 
                FROM ".$this->db->table("extensions")."
                ".($key ? "WHERE `key` = '".$this->db->escape($key)."'" : '');
        $query = $this->db->query($sql);
        $extension_data = array();
        if ($query->num_rows == 1) {
            $extension_data = $query->row;
        } else {
            if ($query->num_rows) {
                foreach ($query->rows as $result) {
                    $extension_data[$result['key']] = $result;
                }
            }
        }

        if ($this->cache && $this->cache->isCacheEnabled()) {
            $this->cache->push($cache_key, $extension_data);
        }
        return $extension_data;
    }

    /**
     * Load extensions list from database
     *
     * @param array  $data
     *                     key - search extensions by key and name
     *                     category - search extensions by category
     *                     page - page number ( limit should be defined also )
     *                     limit - number of rows in page ( page should be defined also )
     * @param string $mode - can be "force" to prevent cache load
     *
     * @return bool|stdClass object array of extensions
     */
    public function getExtensionsList($data = array(), $mode = '')
    {
        $cache_key = '';
        if ($mode == '' && $this->cache && $this->cache->isCacheEnabled()) {
            $cache_key = 'extensions.list';
            if (!empty($data)) {
                $cache_key .= $this->cache->paramsToString($data);
            }

            $load_data = $this->cache->pull($cache_key);
            if ($load_data !== false) {
                //if we have cache, return
                return $load_data;
            }
        }

        $sql = "SELECT DISTINCT
                      e.extension_id,
                      e.type,
                      e.key,
                      e.category,
                      e.priority,
                      e.version,
                      e.license_key,
                      e.date_installed,
                      e.date_modified,
                      e.date_added,
                      s.store_id,
                      st.alias as store_name,
                      s.value as status
                FROM ".$this->db->table("extensions")." e
                LEFT JOIN ".$this->db->table("settings")." s
                    ON ( s.`group` = e.`key` AND s.`key` = CONCAT(e.`key`,'_status') )
                LEFT JOIN ".$this->db->table("stores")." st ON st.store_id = s.store_id
                WHERE e.key<>'' AND  e.`type` ";

        if (has_value($data['filter']) && $data['filter'] != 'extensions') {
            $sql .= " = '".$this->db->escape($data['filter'])."'";
        } else {
            $sql .= " IN ('".implode("', '", $this->extension_types)."') ";
        }

        if (has_value($data['search'])) {
            $keys = array();
            $ext_list = $this->getExtensionsList(array('filter' => $data['filter']));
            if ($ext_list->total) {
                foreach ($ext_list->rows as $extension) {
                    // searching ext by name
                    $name = $this->getExtensionName($extension['key']);
                    if (stripos($name, $data['search']) !== false) {
                        $keys[] = $extension['key'];
                    }
                }
            }
            if ($keys) {
                $sql .= " AND (e.`key` LIKE '%".$this->db->escape($data['search'], true)."%' ";
                $sql .= " OR  e.`key` IN ('".implode("','", $keys)."')) ";
            } else {
                $sql .= " AND e.`key` LIKE '%".$this->db->escape($data['search'], true)."%' ";
            }
        }
        if (has_value($data['category'])) {
            $sql .= " AND e.`category` = '".$this->db->escape($data['category'])."' ";
        }
        if (has_value($data['status'])) {
            $sql .= " AND s.value = '".(int)$data['status']."' ";
        }

        if (has_value($data['store_id'])) {
            $sql .= " AND COALESCE(s.`store_id`,0) = '".(int)$data['store_id']."' ";
        } else {
            $sql .= " AND COALESCE(s.`store_id`,0) = '".(int)$this->registry->get('config')->get('config_store_id')
                ."' ";
        }

        if (has_value($data['sort_order']) && $data['sort_order'][0] != 'name') {
            if ($data['sort_order'][0] == 'key') {
                $data['sort_order'][0] = '`key`';
            }
            $sql .= "\n ORDER BY ".implode(' ', $data['sort_order']);
        } else {
            //default extension sorting based on priority provided. High number is higher priority
            $sql .= "\n ORDER BY e.priority desc";
        }
        $total = null;
        if (has_value($data['page']) && has_value($data['limit'])) {
            $total = $this->db->query($sql);
            $sql .= " LIMIT ".(int)(($data['page'] - 1) * $data['limit']).", ".(int)($data['limit'])." ";
        }

        $result = $this->db->query($sql);

        if (has_value($data['sort_order']) && $data['sort_order'][0] == 'name') {
            if ($result->rows) {
                foreach ($result->rows as &$row) {
                    if (trim($row['key']) == '') {
                        unset($row);
                    }
                    $names[] = mb_strtolower(trim($this->getExtensionName($row['key'])));
                    $row['name'] = trim($this->getExtensionName($row['key']));
                }

                array_multisort(
                    $names,
                    ($data['sort_order'][1] == 'asc' ? SORT_ASC : SORT_DESC),
                    SORT_STRING,
                    $result->rows
                );
            }
        }

        $result->total = $total ? $total->num_rows : $result->num_rows;
        if ($this->cache && $this->cache->isCacheEnabled()) {
            $this->cache->push($cache_key, $result);
        }
        return $result;
    }

    /**
     * @param string $extension
     *
     * @return bool|string
     */
    public function getExtensionName($extension = '')
    {
        if (!$extension) {
            return false;
        }
        $name = '';
        $filename = DIR_EXT
            .$extension
            .'/admin/language/'
            .$this->registry->get('language')->language_details['directory']
            .'/'.$extension
            .'/'.$extension.'.xml';
        if (!file_exists($filename)) {
            $filename = DIR_EXT.$extension.'/admin/language/english/'.$extension.'/'.$extension.'.xml';
        }

        if (file_exists($filename)) {
            /**
             * @var SimpleXMLElement $xml
             */
            $xml = simplexml_load_file($filename);
            if ($xml && $xml->definition) {
                foreach ($xml->definition as $def) {
                    if ((string)$def->key == $extension.'_name') {
                        $name = (string)$def->value;
                        break;
                    }
                }
            }
        }
        return $name;
    }

    /**
     * @param ExtensionCollection $extensions
     */
    protected function setExtensionCollection(ExtensionCollection $extensions)
    {
        $this->extensions = $extensions;
    }

    /**
     * @return array
     */
    public function getExtensionCollection()
    {
        return $this->extensions;
    }

    /**
     * @return array
     */
    public function getMissingExtensions()
    {
        return $this->missing_extensions;
    }

    /**
     * Get an array of all enabled extensions.
     * NOTE: In admin all installed extensions are considered to be enabled
     *
     * @return array
     */
    public function getEnabledExtensions()
    {
        return $this->enabled_extensions;
    }

    /**
     * @return array
     */
    public function getExtensionsDir()
    {
        return $this->extensions_dir;
    }

    /**
     * @return array
     */
    public function getDbExtensions()
    {
        return $this->db_extensions;
    }

    /**
     * @return array
     */
    public function getExtensionControllers()
    {
        return $this->extension_controllers;
    }

    /**
     * @param array $value
     */
    public function setExtensionControllers($value)
    {
        $this->extension_controllers = $value;
    }

    /**
     * @return array
     */
    public function getExtensionTemplates()
    {
        return $this->extension_templates;
    }

    /**
     * @param array $value
     */
    public function setExtensionTemplates($value)
    {
        $this->extension_templates = $value;
    }

    /**
     * @return array
     */
    public function getExtensionLanguages()
    {
        return $this->extension_languages;
    }

    /**
     * @param array $value
     */
    public function setExtensionLanguages($value)
    {
        $this->extension_languages = $value;
    }

    /**
     * @return array
     */
    public function getExtensionModels()
    {
        return $this->extension_models;
    }

    /**
     * @avoid
     *
     * @param array $value
     */
    public function setExtensionModels($value)
    {
        $this->extension_models = $value;
    }

    /**
     * @param $extension
     *
     * @return bool|null
     */
    public function isExtensionAvailable($extension)
    {
        foreach ($this->extensions_dir as $ext) {
            if ($ext == $extension) {
                return true;
            }
        }
        return null;
    }

    /**
     * load all available (installed) extensions (for admin)
     *
     * @void
     */
    public function loadAvailableExtensions()
    {
        $this->loadEnabledExtensions(true);
    }

    /**
     * load all enabled extensions.
     * If force parameter provided,load all installed (for admin)
     *
     * @param bool $force_enabled_off
     *
     * @void
     * @throws Exception
     */
    public function loadEnabledExtensions($force_enabled_off = false)
    {
        /**
         * @var Registry
         */
        $ext_controllers = $ext_models = $ext_languages = $ext_templates = array();
        $enabled_extensions = $hook_extensions = array();

        foreach ($this->db_extensions as $ext) {
            //check if extension is enabled and not already in the picked list
            if (
                //check if we need only available extensions with status 0
                (($force_enabled_off && has_value($this->registry->get('config')->get($ext.'_status')))
                    || $this->registry->get('config')->get($ext.'_status')
                )
                && !in_array($ext, $enabled_extensions)
                && has_value($ext)
            ) {

                //priority for extension execution is set in the <priority> tag of extension configuration
                //order for priority is already set here
                $enabled_extensions[] = $ext;

                $controllers = $languages = $models = $templates = array(
                    'storefront' => array(),
                    'admin'      => array(),
                );
                if (is_file(DIR_EXT.$ext.'/main.php')) {
                    /** @noinspection PhpIncludeInspection */
                    include(DIR_EXT.$ext.'/main.php');
                }
                $ext_controllers[$ext] = $controllers;
                $ext_models[$ext] = $models;
                $ext_languages[$ext] = $languages;
                $ext_templates[$ext] = $templates;

                $class = 'Extension'.preg_replace('/[^a-zA-Z0-9]/', '', $ext);
                if (class_exists($class)) {
                    $hook_extensions[] = $class;
                }
            }
        }
        $this->enabled_extensions = $enabled_extensions;
        $this->setExtensionCollection(new ExtensionCollection($hook_extensions));

        ADebug::variable('List of loaded extensions', $enabled_extensions);

        $this->setExtensionControllers($ext_controllers);
        ADebug::variable('List of controllers used by extensions', $ext_controllers);

        $this->setExtensionModels($ext_models);
        ADebug::variable('List of models used by extensions', $ext_models);

        $this->setExtensionLanguages($ext_languages);
        ADebug::variable('List of languages used by extensions', $ext_languages);

        $this->setExtensionTemplates($ext_templates);
        ADebug::variable('List of templates used by extensions', $ext_templates);
    }

    /**
     * check if language file exists in extension resource
     *
     * @param string   $route
     * @param string   $language_name
     * @param int|bool $section
     *
     * @return array|bool
     */
    public function isExtensionLanguageFile($route, $language_name, $section)
    {
        if (!$this->registry->has('config')) {
            return false;
        }

        $file = ($section ? DIR_EXT_ADMIN : DIR_EXT_STORE)
            .'language/'
            .$language_name
            .'/'.$route.'.xml';

        //include language file from first matching extension
        foreach ($this->extensions_dir as $ext) {
            $f = DIR_EXT.$ext.$file;
            if (is_file($f)) {
                return array(
                    'file'      => $f,
                    'extension' => $ext,
                );
            }
        }
        return false;
    }

    /**
     * check if resource ( model, language, template ) is an extension resource
     *
     * @param  $resource_type - resource type - M, L, T  ( model, language, template )
     * @param  $route         - resource route to check
     * @param  $ext_status    - extension mode for resource route to check (enabled and all)
     * @param  $mode          - mode to force storefront
     *
     * @return array|bool - false if not found, array with extension name and file name if found
     */
    public function isExtensionResource($resource_type, $route, $ext_status = '', $mode = '')
    {
        if (empty($ext_status)) {
            $ext_status = 'enabled';
        }

        if (!$this->registry->has('config')) {
            return false;
        }

        $ext_section = (IS_ADMIN ? DIR_EXT_ADMIN : DIR_EXT_STORE);
        //mode to force load storefront model is loaded from admin
        if ($mode == 'storefront') {
            $ext_section = DIR_EXT_STORE;
        }

        switch ($resource_type) {
            case 'M' :
                $file = $ext_section.'model/'.$route.'.php';
                $source = $this->extension_models;
                break;
            case 'L' :
                $query = $this->registry->get('db')->query(
                    "SELECT directory FROM ".$this->db->table("languages")." 
                    WHERE code='".$this->registry->get('session')->data['language']."'"
                );
                $file = $ext_section
                    .'language/'
                    .$query->row['directory']
                    .'/'.$route.'.xml';
                $source = $this->extension_languages;
                break;
            case 'T' :
                $tmpl_id = IS_ADMIN
                    ? $this->registry->get('config')->get('admin_template')
                    : $this->registry->get('config')->get('config_storefront_template');
                $file = $ext_section.DIR_EXT_TEMPLATE.$tmpl_id.'/template/'.$route;
                $source = $this->extension_templates;
                break;
            default:
                return false;
        }

        $section = trim($ext_section, '/');

        //list only enabled extensions or all depending on status flag
        $extensions_lookup_list = array();
        if ($ext_status == 'enabled') {
            $extensions_lookup_list = $this->enabled_extensions;
        } else {
            if ($ext_status == 'all') {
                $extensions_lookup_list = $this->extensions_dir;
            }
        }

        //reverse $extensions_lookup_list when looking for tpl
        // need to take last from the list
        if ($resource_type == 'T') {
            $extensions_lookup_list = array_reverse($extensions_lookup_list);
        }

        foreach ($extensions_lookup_list as $ext) {
            $f = DIR_EXT.$ext.$file;
            if ($ext_status == 'all'
                || (is_array($source[$ext][$section])
                    && in_array($route, $source[$ext][$section]))
            ) {
                if (is_file($f)) {
                    return array(
                        'file'      => $f,
                        'extension' => $ext,
                        'base_path' => $file,
                    );
                }
                if ($resource_type == 'T') {
                    //check default template
                    $f = DIR_EXT.$ext.$ext_section.DIR_EXT_TEMPLATE.'default/template/'.$route;
                    if (is_file($f)) {
                        return array(
                            'file'      => $f,
                            'extension' => $ext,
                            'base_path' => $ext_section.DIR_EXT_TEMPLATE.'default/template/'.$route,
                        );
                    }
                }
            }
        }

        //we can include language file from all extensions too
        if ($resource_type == 'L') {
            foreach ($this->extensions_dir as $ext) {
                $f = DIR_EXT.$ext.$file;
                if (is_file($f)) {
                    return array(
                        'file'      => $f,
                        'extension' => $ext,
                    );
                }
            }
        }
        return false;
    }

    /**
     * Function returns all tpl with pre or post prefixes for all enabled extensions
     *
     * @param string $route - relative path of file.
     *
     * @return array|bool
     */
    public function getAllPrePostTemplates($route)
    {

        if (!$this->registry->has('config')) {
            return false;
        }

        $ext_section = (IS_ADMIN ? DIR_EXT_ADMIN : DIR_EXT_STORE);

        $tmpl_id = IS_ADMIN
            ? $this->registry->get('config')->get('admin_template')
            : $this->registry->get('config')->get('config_storefront_template');
        $file = $ext_section.DIR_EXT_TEMPLATE.$tmpl_id.'/template/'.$route;
        $source = $this->extension_templates;

        $section = trim($ext_section, '/');

        //list only enabled extensions
        $extensions_lookup_list = $this->enabled_extensions;
        $output = array();
        foreach ($extensions_lookup_list as $ext) {
            //looking for active template tpl
            $f = DIR_EXT.$ext.$file;
            $ext_tpls = is_array($source[$ext][$section]) ? $source[$ext][$section] : array();
            if (in_array($route, $ext_tpls)) {
                if (is_file($f)) {
                    $output[$ext] = array(
                        'file'      => $f,
                        'extension' => $ext,
                        'base_path' => $file,
                    );
                }
                //if active template tpl not found - looking for default
                if (!isset($output[$ext])) {
                    //check default template
                    $f = DIR_EXT.$ext.$ext_section.DIR_EXT_TEMPLATE.'default/template/'.$route;
                    if (is_file($f)) {
                        $output[] = array(
                            'file'      => $f,
                            'extension' => $ext,
                            'base_path' => $ext_section.DIR_EXT_TEMPLATE.'default/template/'.$route,
                        );
                    }
                }
            }
        }

        return $output;
    }

    /**
     * check if route is an extension controller (only enabled extensions can be checked)
     *
     * @param  $route - controller route to check
     *
     * @return array|bool - extension name, file, class name and method
     */
    public function isExtensionController($route)
    {

        $section = trim((IS_ADMIN ? DIR_EXT_ADMIN : DIR_EXT_STORE), '/');
        $path_build = '';
        $path_nodes = explode('/', $route);

        foreach ($path_nodes as $path_node) {
            $path_build .= $path_node;

            foreach ($this->enabled_extensions as $ext) {
                $file = DIR_EXT.$ext.(IS_ADMIN ? DIR_EXT_ADMIN : DIR_EXT_STORE).'controller/'.$path_build.'.php';
                $ext_controllers = is_array($this->extension_controllers[$ext][$section])
                    ? $this->extension_controllers[$ext][$section]
                    : array();
                if (in_array($path_build, $ext_controllers) && is_file($file)) {
                    //remove current node
                    array_shift($path_nodes);
                    //check for method
                    $method_to_call = array_shift($path_nodes);
                    if ($method_to_call) {
                        $method = $method_to_call;
                    } else {
                        $method = 'main';
                    }

                    return array(
                        'route'     => $path_build,
                        'extension' => $ext,
                        'file'      => $file,
                        'class'     => 'Controller'.preg_replace('/[^a-zA-Z0-9]/', '', $path_build),
                        'method'    => $method,
                    );
                }
            }

            $path_build .= '/';
            array_shift($path_nodes);
        }

        return false;
    }

    /**
     * Check if a {@link Extension} from the {@link ExtensionCollection} for this ExtensionsApi exists.
     * {@source}
     * Use like <code>isset($ExtensionsApi->extensionName)</code>
     *
     * @param string $property Name of the {@link extension} to check.
     *
     * @return boolean
     */
    public function __isset($property)
    {
        if ($this->extensions->$property !== false) {
            return true;
        }
        return false;
    }

    /**
     * Get a {@link Extension} from the {@link ExtensionCollection} for this ExtensionsApi.
     * {@source}
     * Use like <code>$ExtensionsApi->extensionName</code>
     *
     * @param string $property Name of the {@link extension} to get.
     *
     * @throws AException
     * @return extension
     */
    public function __get($property)
    {
        if ($this->extensions->$property !== false) {
            return $this->extensions->$property;
        }
        throw new AException(AC_ERR_LOAD,
            'Extensions of name "'.$property.'" not found in ExtensionsApi '
        );
    }

    /**
     * @param string $method (hk_[function] calls)
     * @param array  $args
     *
     * @return mixed|null
     */
    public function __call($method, array $args)
    {
        if (substr($method, 0, 2) == 'hk') {
            return $this->__ExtensionsApiCall(substr($method, 2), $args);
        }
        return null;
    }

    /**
     * @param string $method
     * @param array  $args
     *
     * @return mixed|null
     */
    protected function __ExtensionsApiCall($method, array $args)
    {
        $return = null;

        if ((sizeof($args) > 0) && is_object($args[0])) {
            /**
             * @var object Extension
             */
            $baseObject = $args[0];
            $baseObject->ExtensionsApi = $this;
        } else {
            $baseObject = $this;
            $baseObject->ExtensionsApi = $this;
            array_unshift($args, $baseObject);
        }

        $method = strtolower($method[0]).substr($method, 1);

        $extension_method = ucfirst(get_class($baseObject)).ucfirst($method);

        // before hook - runs before method; allows parameters to be changed
        $before_args = $args;
        array_shift($before_args);
        $args[] =& $before_args;
        call_user_func_array(array($this->extensions, 'before'.$extension_method), $args);
        $args = $before_args;
        array_unshift($args, $baseObject);

        $can_run = true;
        if (method_exists($baseObject, $method) || method_exists($baseObject, '__call')) {

            // callback surrounds the method execution
            $can_run = call_user_func_array(array($this->extensions, 'around'.$extension_method), $args);

            // method is allowed to run
            if ($can_run === true) {
                $object_args = $args;
                array_shift($object_args);
                $return = call_user_func_array(array($baseObject, $method), $object_args);

                // have replaced the method
            } elseif ($can_run !== false) {
                $return = $can_run;
            }
        }

        if ($can_run !== false) {
            $on_args = $args;
            $on_args[] =& $return;
            call_user_func_array(array($this->extensions, 'on'.$extension_method), $on_args);
        }

        call_user_func_array(array($this->extensions, 'after'.$extension_method), $args);

        return $return;
    }

}

/**
 * Class ExtensionUtils
 */
class ExtensionUtils
{
    /**
     * @var Registry
     */
    protected $registry;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var SimpleXmlElement|DOMNode
     */
    protected $config;
    /**
     * @var int
     */
    protected $store_id;
    /**
     * @var array
     */
    protected $error = array();
    /**
     * @var array
     */
    protected $tags = array();

    /**
     * @param string $ext
     * @param int    $store_id
     */
    public function __construct($ext, $store_id = 0)
    {
        $this->registry = Registry::getInstance();
        $this->name = (string)$ext;
        $this->store_id = (int)$store_id;
        $this->config = getExtensionConfigXml($ext);

        if (!$this->config) {
            $filename = DIR_EXT.str_replace('../', '', $this->name).'/config.xml';
            $err = sprintf('Error: Could not load config for <b>%s</b> ( '.$filename.')!', $this->name);
            foreach (libxml_get_errors() as $error) {
                $err .= "  ".$error->message;
            }
            $error = new AError($err);
            $error->toLog()->toDebug();
            $this->error[] = $err;
            return null;
        }
        return null;
    }

    /**
     * @param null $val
     *
     * @return bool|DOMElement|null|SimpleXMLElement|string
     */
    public function getConfig($val = null)
    {
        return !empty($val) ? isset($this->config->$val) ? (string)$this->config->$val : null : $this->config;
    }

    /**
     * validate extension resources. return warning in case conflict
     */
    public function validateResources()
    {
        $filename = DIR_EXT.str_replace('../', '', $this->name).'/main.php';
        if (!is_file($filename)) {
            return null;
        }

        //load extensions resources
        $controllers = $languages = $models = $templates = array(
            'storefront' => array(),
            'admin'      => array(),
        );
        /** @noinspection PhpIncludeInspection */
        include($filename);
        $validate_resources = array(
            'controllers' => $controllers,
            'languages'   => $languages,
            'models'      => $models,
            'templates'   => $templates,
        );

        //extensions resources
        $extensions = $this->registry->get('extensions');
        $ext_resources = array(
            'controllers' => $extensions->getExtensionControllers(),
            'languages'   => $extensions->getExtensionLanguages(),
            'models'      => $extensions->getExtensionModels(),
            'templates'   => $extensions->getExtensionTemplates(),
        );

        $conflict_resources = array();

        foreach ($validate_resources as $resource_type => $resources) {
            if (empty($resources)) {
                continue;
            }
            foreach ($ext_resources[$resource_type] as $checked_name => $checked_resources) {
                if ($checked_name == $this->name) {
                    continue;
                }
                foreach ($checked_resources as $section => $section_resources) {
                    $conflict = array_intersect((array)$resources[$section], (array)$section_resources);
                    if (!empty($conflict)) {
                        $conflict_resources[$checked_name][$resource_type][$section] = $conflict;
                    }
                }
            }
        }

        return $conflict_resources;
    }

    /**
     * @param string $err_msg
     */
    protected function error($err_msg)
    {
        $this->error[] = $err_msg;
    }

    /**
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Function returns array of form fields formatted for AHtml-class
     *
     * @return array
     * @throws AException
     */
    public function getSettings()
    {
        $this->registry->get('load')->model('setting/setting');
        $settings = $this->registry->get('model_setting_setting')->getSetting($this->name, $this->store_id);
        $result = array();
        $this->registry->get('session')->data['extension_required_fields'] = array();
        //add other settings items
        if (isset($this->config->settings->item)) {
            $i = 0;
            foreach ($this->config->settings->item as $item) {
                //detect if setting is serialized

                $true_item_id = (string)$item['id'];
                $value_key = substr($item['id'], -2);
                $item['id'] = $value_key == '[]'
                    ? substr($true_item_id, 0, strlen($true_item_id) - 2)
                    : $true_item_id;

                $value = $settings[(string)$item['id']];
                if (is_serialized($value)) {
                    $value = unserialize($value);
                }
                $result[$i] = array(
                    'name'          => (string)$true_item_id,
                    'value'         => $value,
                    'type'          => (string)$item->type,
                    'resource_type' => (string)$item->resource_type,
                    //to use few datasources inside the same form-element such as html_template
                    'data_source'   => (array)$item->variants->data_source,
                    'model_rt'      => (string)$item->variants->data_source->model_rt,
                    'method'        => (string)$item->variants->data_source->method,
                    //end of remove
                    'field1'        => (string)$item->variants->fields->field[0],
                    'field2'        => (string)$item->variants->fields->field[1],
                    'template'      => (string)$item->template,
                );
                // if just static option values are used
                if ($item->variants->item) {
                    foreach ($item->variants->item as $k) {
                        $k = (string)$k;
                        $result[$i]['options'][$k] = $this->registry->get('language')->get((string)$item['id'].'_'.$k);
                    }
                }

                if ((string)$item['id'] == $this->name.'_status') {
                    $result[$i]['style'] = 'btn_switch';
                    $result[$i]['attr'] = 'reload_on_save="true"';
                }

                $type_attr = $item->type->attributes();
                if ((string)$type_attr['required'] == 'true') {
                    $result[$i]['required'] = true;
                    $this->registry->get('session')->data['extension_required_fields'][] = $result[$i]['name'];
                }
                if ((string)$type_attr['readonly'] == 'true') {
                    $result[$i]['attr'] .= ' readonly';
                }

                $i++;
            }
        }

        return $result;
    }

    /**
     * Validation of settings of extension
     *
     * @param array $data - array of values for check. If it is empty - will check data from config
     *
     * @return array - array of 2 elements: result and array - item_ids list that not valid
     * @throws AException
     */
    public function validateSettings($data = array())
    {
        // if values not set or we change only status of extension
        if (!$data
            || (isset($data['one_field']) && isset($data[$this->name.'_status'])
                && $data[$this->name.'_status'] == 1)
        ) {
            $this->registry->get('load')->model('setting/setting');
            $data = $this->registry->get('model_setting_setting')->getSetting($this->name, $this->store_id);
        }

        //1. check is all required fields are set
        $result = $this->checkRequiredSettings($data);
        if (!$result) {
            return array('result' => false);
        }

        //2. is data valid?
        //2.1 - check by regex pattern from entity of config.xml
        if (isset($this->config->settings->item)) {
            foreach ($this->config->settings->item as $item) {
                if (!isset($data[(string)$item['id']])) {
                    continue;//if data for check not given - do nothing
                }
                $value = $data[(string)$item['id']];
                if (!is_multi($value)) {
                    if (is_array($value)) {
                        $value = array_map('trim', $value);
                    } else {
                        $value = trim($value);
                    }
                }
                if ((string)$item->pattern_validate) {
                    $matches = array();
                    $pattern = trim(trim((string)$item->pattern_validate), '/');
                    $pattern = '/'.$pattern.'/';
                    //is pattern valid?
                    if (preg_match($pattern, $value, $matches) === false) {
                        return array(
                            'result' => false,
                            'errors' => array(
                                'pattern' => 'Regex pattern for field "'.(string)$item['id'].'" is not valid.',
                            ),
                        );
                    } else {
                        if (!$matches) {
                            return array('result' => false, 'errors' => array((string)$item['id'] => ''));
                        }
                    }
                }
            }
        }
        //2.2 check data by given function from file validate.php
        $validate_file = DIR_EXT.$this->name.'/validate.php';

        if (file_exists($validate_file)) {
            /** @noinspection PhpIncludeInspection */
            include_once($validate_file);
            //function settingsValidation in validate.php must to return
            // formatted array as in caller (see phpdoc-comment: @return)
            if (function_exists('settingsValidation')) {
                $result = call_user_func('settingsValidation', $data);
                if (!isset($result['result']) || !isset($result['errors']) || !is_array($result['errors'])) {
                    return array(
                        'result' => false,
                        'errors' => array(
                            'pattern' => 'Error: Cannot to validate data by validate.php file. '
                                .'Function returns incorrect formatted data.',
                        ),
                    );
                }
                return $result;
            }
        }

        return array('result' => true);
    }

    /**
     * @param array $data - array of values for check. If it is empty - will check data from config
     *
     * @return bool
     */
    public function checkRequiredSettings($data = array())
    {

        if (isset($this->config->settings->item)) {
            /**
             * @var $items SimpleXmlElement
             */
            $items = $this->config->settings->item;
            foreach ($items as $item) {
                if (!isset($data[(string)$item['id']])) {
                    //if data for check not given - do nothing
                    continue;
                }
                $value = $data[(string)$item['id']];
                if (!is_multi($value)) {
                    if (is_array($value)) {
                        $value = array_map('trim', $value);
                    } else {
                        $value = trim($value);
                    }
                }

                /** @noinspection PhpUndefinedMethodInspection */
                $type_attr = $item->type->attributes();
                if ((string)$type_attr['required'] == 'true' && !$value) {
                    return false;
                }
            }
        }

        // at last we need to validate data
        return true;
    }

    /**
     * @return array
     */
    public function getDefaultSettings()
    {

        $result = array();
        if (isset($this->config->settings->item)) {
            foreach ($this->config->settings->item as $item) {
                if ((string)$item['id'] == $this->name.'_status') {
                    continue;
                }

                if (in_array((string)$item->type, array('checkboxgroup', 'multiselectbox'))) {
                    $value = (string)$item->default_value;
                } else {
                    $value = $this->registry->get('html')->convertLinks(
                        htmlentities((string)$item->default_value, ENT_QUOTES, 'UTF-8')
                    );
                }

                if ((string)$item->type == 'resource' && $value) {
                    $resource = new AResource((string)$item->resource_type);
                    $resource_id = $resource->getIdFromHexPath(str_replace((string)$item->resource_type, '', $value));
                    $resource_info = $resource->getResource($resource_id);
                    $value = (string)$item->resource_type.'/'.$resource_info['resource_path'];
                }
                $result[(string)$item['id']] = $value;
                if ((string)$item['id'] == 'priority') {
                    $result['sort_order'] = $value;
                }
            }
        }

        return $result;
    }
}