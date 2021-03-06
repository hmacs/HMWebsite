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
if (!defined('DIR_CORE') || !IS_ADMIN) {
    header('Location: static_pages/');
}

class ControllerPagesTotalLowOrderFee extends AController
{
    public $data = array();
    public $error = array();
    private $fields = array('low_order_fee_total', 'low_order_fee_fee', 'low_order_fee_tax_class_id', 'low_order_fee_status', 'low_order_fee_sort_order', 'low_order_fee_calculation_order', 'low_order_fee_total_type');

    public function main()
    {

        //init controller data
        $this->extensions->hk_InitData($this, __FUNCTION__);

        $this->document->setTitle($this->language->get('heading_title'));
        $this->loadModel('setting/setting');

        if ($this->request->is_POST() && ($this->_validate())) {
            $this->model_setting_setting->editSetting('low_order_fee', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->extensions->hk_ProcessData($this);
            redirect($this->html->getSecureURL('total/low_order_fee'));
        }

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        $this->data['success'] = $this->session->data['success'];
        if (isset($this->session->data['success'])) {
            unset($this->session->data['success']);
        }

        $this->document->initBreadcrumb(array(
            'href'      => $this->html->getSecureURL('index/home'),
            'text'      => $this->language->get('text_home'),
            'separator' => false,
        ));
        $this->document->addBreadcrumb(array(
            'href'      => $this->html->getSecureURL('extension/total'),
            'text'      => $this->language->get('text_total'),
            'separator' => ' :: ',
        ));
        $this->document->addBreadcrumb(array(
            'href'      => $this->html->getSecureURL('total/low_order_fee'),
            'text'      => $this->language->get('heading_title'),
            'separator' => ' :: ',
            'current'   => true,
        ));

        $this->loadModel('localisation/tax_class');
        $_tax_classes = $this->model_localisation_tax_class->getTaxClasses();
        $tax_classes = array(0 => $this->language->get('text_none'));
        foreach ($_tax_classes as $k => $v) {
            $tax_classes[$v['tax_class_id']] = $v['title'];
        }

        foreach ($this->fields as $f) {
            if (isset ($this->request->post [$f])) {
                $this->data [$f] = $this->request->post [$f];
            } else {
                $this->data [$f] = $this->config->get($f);
            }
        }

        $this->data ['action'] = $this->html->getSecureURL('total/low_order_fee');
        $this->data['cancel'] = $this->html->getSecureURL('extension/total');
        $this->data ['heading_title'] = $this->language->get('text_edit').$this->language->get('text_total');
        $this->data ['form_title'] = $this->language->get('heading_title');
        $this->data ['update'] = $this->html->getSecureURL('listing_grid/total/update_field', '&id=low_order_fee');

        $form = new AForm ('HS');
        $form->setForm(array(
            'form_name' => 'editFrm',
            'update'    => $this->data ['update'],
        ));

        $this->data['form']['form_open'] = $form->getFieldHtml(array(
            'type'   => 'form',
            'name'   => 'editFrm',
            'action' => $this->data ['action'],
            'attr'   => 'data-confirm-exit="true" class="aform form-horizontal"',
        ));
        $this->data['form']['submit'] = $form->getFieldHtml(array(
            'type' => 'button',
            'name' => 'submit',
            'text' => $this->language->get('button_save'),
        ));
        $this->data['form']['cancel'] = $form->getFieldHtml(array(
            'type' => 'button',
            'name' => 'cancel',
            'text' => $this->language->get('button_cancel'),
        ));

        $this->data['form']['fields']['status'] = $form->getFieldHtml(array(
            'type'  => 'checkbox',
            'name'  => 'low_order_fee_status',
            'value' => $this->data['low_order_fee_status'],
            'style' => 'btn_switch status_switch',
        ));
        $this->data['form']['fields']['total'] = $form->getFieldHtml(array(
            'type'  => 'input',
            'name'  => 'low_order_fee_total',
            'value' => $this->data['low_order_fee_total'],
        ));
        $this->data['form']['fields']['fee'] = $form->getFieldHtml(array(
            'type'  => 'input',
            'name'  => 'low_order_fee_fee',
            'value' => $this->data['low_order_fee_fee'],
        ));
        $this->data['form']['fields']['tax'] = $form->getFieldHtml(array(
            'type'    => 'selectbox',
            'name'    => 'low_order_fee_tax_class_id',
            'options' => $tax_classes,
            'value'   => $this->data['low_order_fee_tax_class_id'],
        ));

        $this->loadLanguage('extension/extensions');
        $options = array(
            'fee'      => $this->language->get('text_fee'),
            'discount' => $this->language->get('text_discount'),
            'total'    => $this->language->get('text_total'),
            'subtotal' => $this->language->get('text_subtotal'),
            'tax'      => $this->language->get('text_tax'),
            'shipping' => $this->language->get('text_shipping'),
        );
        $this->data['form']['fields']['total_type'] = $form->getFieldHtml(array(
            'type'    => 'selectbox',
            'name'    => 'low_order_fee_total_type',
            'options' => $options,
            'value'   => $this->data['low_order_fee_total_type'],
        ));
        $this->data['form']['fields']['sort_order'] = $form->getFieldHtml(array(
            'type'  => 'input',
            'name'  => 'low_order_fee_sort_order',
            'value' => $this->data['low_order_fee_sort_order'],
        ));
        $this->data['form']['fields']['calculation_order'] = $form->getFieldHtml(array(
            'type'  => 'input',
            'name'  => 'low_order_fee_calculation_order',
            'value' => $this->data['low_order_fee_calculation_order'],
        ));
        $this->view->assign('help_url', $this->gen_help_url('edit_low_order_fee'));
        $this->view->batchAssign($this->data);
        $this->processTemplate('pages/total/form.tpl');

        //update controller data
        $this->extensions->hk_UpdateData($this, __FUNCTION__);
    }

    private function _validate()
    {
        if (!$this->user->canModify('total/low_order_fee')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        $this->extensions->hk_ValidateData($this);
        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}
