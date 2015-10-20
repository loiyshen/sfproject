<?php

namespace Bundles\FrontendBundle\Util;

/**
 * @EmailSendHelper
 * Sending Email Helper Class
 */
class EmailSendHelper
{

    /**
     * Send Email via Qmail (SMTP)
     * @param unknown $container Symfony Service Container
     * @param string $subject 
     * @param string $content 
     * @param array|string $fromAddress 
     * @param array|string $toAddress 
     * @param array|string $ccAddress 
     * @param array|string $bccAddress 
     * @return boolean|string
     */
    public static function sendQmail($container, $subject, $content, $fromAddress, $toAddress, $ccAddress = NULL, $bccAddress = NULL, $charset = 'UTF-8')
    {
        $mailer = $container->get('mailer');
        $message = new \Swift_Message($subject, $content, 'text/plain', $charset);
        $message->setFrom($fromAddress);
        $message->setTo($toAddress);
        $message->setCc($ccAddress);
        $message->setBcc($bccAddress);
        try {
            $mailer->send($message);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return true;
    }

}