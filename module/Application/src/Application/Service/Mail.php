<?php

namespace Application\Service;

use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Application\Service\Result as ServiceResult;

class Mail
{

    protected $renderer;
    protected $transport;
    protected $options;

    public function __construct($options = null, $transport = null, $renderer = null)
    {
        if (null !== $transport) {
            $this->setTransport($transport);
        }
        if (null !== $renderer) {
            $this->setRenderer($renderer);
        }
    }

    public function createHtmlMessage($model, $values = array())
    {
        $renderer = $this->getRenderer();
        $content  = $renderer->render($model, $values);

        $text       = new MimePart(strip_tags($content));
        $text->type = "text/plain";

        $html       = new MimePart($content);
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->setParts(array($text, $html));

        $message = new Message();
        $message->setEncoding('utf-8');
        $message->setBody($body);
        if (array_key_exists('subject', $values)) {
            $message->setSubject($values['subject']);
        }

        $message->getHeaders()->get('content-type')->setType('multipart/alternative');

        return $message;
    }

    public function createTextMessage($model, $values = array())
    {
        $renderer = $this->getRenderer();
        $content  = $renderer->render($model, $values);

        $message = new Message();
        $message->setEncoding('utf-8');
        $message->setBody($content);
        if (array_key_exists('subject', $values)) {
            $message->setSubject($values['subject']);
        }

        return $message;
    }

    public function send(Message $message)
    {
        try {
            $this->getTransport()->send($message);
            return new ServiceResult(ServiceResult::SUCCESS, $message);
        } catch (\Exception $e) {
            return new ServiceResult(ServiceResult::FAILURE, null, array($e->getMessage()));
        }
    }

    protected function getRenderer()
    {
        return $this->renderer;
    }

    protected function setRenderer($renderer)
    {
        $this->renderer = $renderer;
    }

    protected function getTransport()
    {
        return $this->transport;
    }

    protected function setTransport($transport)
    {
        $this->transport = $transport;
    }

}

?>
