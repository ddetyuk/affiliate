<?php

namespace Payment\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class PaymentButton extends AbstractHelper
{

    protected $result;

    public function __invoke($gateway = 'payza')
    {
        $sm       = $this->getServiceLocator();
        $config   = $sm->get('Config');
        $renderer = $sm->get('ViewRenderer');

        switch ($gateway) {
            case 'payza':
                $baseurl  = $renderer->plugin('ServerUrl')->__invoke();
                $alerturl = $baseurl . $renderer->plugin('Url')->__invoke('payment', array('action'   => 'listener'));
                $cancelurl = $baseurl . $renderer->plugin('Url')->__invoke('payment', array('action'    => 'cancel'));
                $successurl = $baseurl . $renderer->plugin('Url')->__invoke('payment', array('action' => 'success'));

                $model = new ViewModel();
                $model->setTemplate('payment/partial/payza-button.phtml');
                $model->setVariable('username', $config['gateway']['payza']['options']['Username']);
                $model->setVariable('posturl', $config['gateway']['payza']['options']['URLPost']);
                $model->setVariable('alerturl', $alerturl);
                $model->setVariable('cancelurl', $cancelurl);
                $model->setVariable('successurl', $successurl);

                return $renderer->render($model);
                break;
            default:
        }
    }

    protected function getServiceLocator()
    {
        return $this->getView()->getHelperPluginManager()->getServiceLocator();
    }

}