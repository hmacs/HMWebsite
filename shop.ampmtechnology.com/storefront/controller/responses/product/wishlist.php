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
if (!defined('DIR_CORE')) {
    header('Location: static_pages/');
}

class ControllerResponsesProductWishlist extends AController
{
    public $error = array();
    public $data = array();

    public function add()
    {

        //init controller data
        $this->extensions->hk_InitData($this, __FUNCTION__);

        $json = array();
        if ($this->customer->isLogged() || $this->customer->isUnauthCustomer()) {
            if ($this->request->get['product_id']) {
                $this->customer->addToWishList($this->request->get['product_id']);
                $json['success'] = $this->language->get('text_wishlist_add_success');
            } else {
                $json['error'] = 'Missing required data';
            }
        } else {
            $json['error'] = 'No permission';
        }
        //init controller data
        $this->extensions->hk_UpdateData($this, __FUNCTION__);

        $this->load->library('json');
        $this->response->setOutput(AJson::encode($json));
    }

    public function remove()
    {

        //init controller data
        $this->extensions->hk_InitData($this, __FUNCTION__);

        $json = array();
        if ($this->customer->isLogged() || $this->customer->isUnauthCustomer()) {
            if ($this->request->get['product_id']) {
                $this->customer->removeFromWishList($this->request->get['product_id']);
                $json['success'] = $this->language->get('text_wishlist_remove_success');
            } else {
                $json['error'] = 'Missing required data';
            }
        } else {
            $json['error'] = 'No permission';
        }
        //init controller data
        $this->extensions->hk_UpdateData($this, __FUNCTION__);

        $this->load->library('json');
        $this->response->setOutput(AJson::encode($json));
    }

}
