<?php

namespace Payment\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class PayzaButton extends AbstractHelper 
{
    protected $result;

    public function __invoke()
    {
        $sm = $this->getServiceLocator();
        $config = $sm->get('Config');
        $renderer = $sm->get('ViewRenderer');

        $baseurl = $renderer->plugin('ServerUrl')->__invoke();
        $alerturl = $baseurl . $renderer->plugin('Url')->__invoke('payza-payment', array('action' => 'listener'));
        $cancelurl = $baseurl . $renderer->plugin('Url')->__invoke('payza-payment', array('action' => 'cancel'));
        $successurl = $baseurl . $renderer->plugin('Url')->__invoke('payza-payment', array('action' => 'success'));
        
        $model = new ViewModel();
        $model->setTemplate('payza-payment/partial/button.phtml');
        $model->setVariable('username', $config['payza']['Username']);
        $model->setVariable('posturl', $config['payza']['URLPost']);
        $model->setVariable('alerturl', $alerturl);
        $model->setVariable('cancelurl', $cancelurl);
        $model->setVariable('successurl', $successurl);

        return $renderer->render($model);
    }

    protected function getServiceLocator()
    {
        return $this->getView()->getHelperPluginManager()->getServiceLocator();
    }
}