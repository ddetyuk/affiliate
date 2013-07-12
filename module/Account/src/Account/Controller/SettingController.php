<?php

namespace Account\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use \Account\Service\Account;

class SettingController extends AbstractActionController
{

    public function indexAction()
    {
        if(!$this->IsGranted('account.setting.update')){
            $this->redirect()->toRoute('user', array('action'=>'login'));
        }
        $model   = new ViewModel();
        $form    = $this->getServiceLocator()->get('Account\Form\Setting');
        $setting = $this->getServiceLocator()->get('Setting\Service\Setting');
        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());
            
            if ($form->isValid()) {
                $data = $form->getData();
                $setting->set(Account::LEVEL1_PERSENT, $data['level1']);
                $setting->set(Account::LEVEL2_PERSENT, $data['level2']);
                $setting->set(Account::LEVEL3_PERSENT, $data['level3']);
                $setting->set(Account::USERS_MIN_VAL, $data['minval']);
                $this->flashMessenger()->addSuccessMessage('Settings successfully updated');
            }
        } else {
            $result = $setting->get(Account::LEVEL1_PERSENT);
            if ($result->isSuccess()) {
                $form->get('level1')->setValue($result->getEntity()->getValue());
            }
            $result = $setting->get(Account::LEVEL2_PERSENT);
            if ($result->isSuccess()) {
                $form->get('level2')->setValue($result->getEntity()->getValue());
            }
            $result = $setting->get(Account::LEVEL3_PERSENT);
            if ($result->isSuccess()) {
                $form->get('level3')->setValue($result->getEntity()->getValue());
            }
            $result = $setting->get(Account::USERS_MIN_VAL);
            if ($result->isSuccess()) {
                $form->get('minval')->setValue($result->getEntity()->getValue());
            }
        }
        $model->setVariable('form', $form);
        $result = $setting->get(Account::SYSTEM_BALANCE);
        if ($result->isSuccess()) {
            $model->setVariable('sbalance', $result->getEntity()->getValue());
        }
        $result = $setting->get(Account::USERS_BALANCE);
        if ($result->isSuccess()) {
            $model->setVariable('ubalance', $result->getEntity()->getValue());
        }
        $result = $setting->get(Account::PAYMENT_BALANCE);
        if ($result->isSuccess()) {
            $model->setVariable('pbalance', $result->getEntity()->getValue());
        }
        return $model;
    }

}
