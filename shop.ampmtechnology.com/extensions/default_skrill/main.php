<?php
/*------------------------------------------------------------------------------
  $Id$

  AbanteCart, Ideal OpenSource Ecommerce Solution
  http://www.AbanteCart.com

  Copyright © 2011-2018 Belavier Commerce LLC

  This source file is subject to Open Software License (OSL 3.0)
  Lincence details is bundled with this package in the file LICENSE.txt.
  It is also available at this URL:
  <http://www.opensource.org/licenses/OSL-3.0>

 UPGRADE NOTE:
   Do not edit or add to this file if you wish to upgrade AbanteCart to newer
   versions in the future. If you wish to customize AbanteCart for your
   needs please refer to http://www.AbanteCart.com for more information.
------------------------------------------------------------------------------*/
if (!defined('DIR_CORE')) {
    header('Location: static_pages/');
}

if (!class_exists('ExtensionSkrill')) {
    include('core/skrill.php');
}

$controllers = array(
    'storefront' => array('responses/extension/default_skrill'),
    'admin'      => array(),
);

$models = array(
    'storefront' => array('extension/default_skrill'),
    'admin'      => array(),
);

$languages = array(
    'storefront' => array(
        'default_skrill/default_skrill',
    ),
    'admin'      => array(
        'default_skrill/default_skrill',
    ),
);

$templates = array(
    'storefront' => array(
        'responses/default_skrill.tpl',
    ),
    'admin'      => array(),
);