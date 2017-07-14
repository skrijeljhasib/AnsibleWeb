<?php
namespace Project\PluginTestService;

use Fei\Entity\Exception;
use Fei\Service\Audit\Client\Audit;
use Fei\Service\Logger\Client\Logger;
use Fei\Service\Logger\Entity\Notification;
use Fei\ApiClient\Transport\BasicTransport;
use Fei\Service\Audit\Entity\AuditEvent;
use Fei\Service\Mailer\Client\Mailer;
use Fei\Service\Mailer\Entity\Mail;
use Fei\Service\Filer\Client\Filer;
use Fei\Service\Filer\Entity\File;
use SplFileObject;

class PluginTester implements PluginTesterInterface
{
    protected $errorLog = false;
    protected $errorAudit = false;
    protected $errorMailer = false;
    protected $errorFiler = false;
    protected $errorLogMessage;
    protected $errorAuditMessage;
    protected $errorMailerMessage;
    protected $errorFilerMessage;
    protected $loggerServer = 'http://logger.test.flash-global.net';
    protected $auditServer = 'http://audit.test.flash-global.net';
    protected $mailServer = 'http://mailer.test.flash-global.net';
    protected $filerServer = 'http://filer.test.flash-global.net';
    protected $mailRecipient = "k.danthine@flash-global.net";
    public function loggerTester()
    {
        try {
            $logger = new Logger(array(
                Logger::OPTION_BASEURL => $this->loggerServer,
                Logger::OPTION_FILTER => Notification::LVL_DEBUG,
            ));
            $logger->setTransport(new BasicTransport()); //Standard Transport method
            if(($logger->notify('Logger notification from tester')) == false){
                $this->errorLog = true;
                $this->errorLogMessage .= "<br>notify (1) returned an error";
            } // default level is Notification::LVL_INFO
            if(($logger->notify('Logger debug message from tester', array('level' => Notification::LVL_DEBUG))) == false){
                $this->errorLog = true;
                $this->errorLogMessage .= "<br>notify (2) returned an error";
            }
        }catch (Exception $e){
            $this->errorLog = true;
            $this->errorLogMessage = $e;
        }
    }
    public function auditTester()
    {
        try {
            $audit = new Audit(array(
                    Audit::OPTION_BASEURL => $this->auditServer,
                    Audit::OPTION_FILTER => AuditEvent::LVL_DEBUG,
                )
            );
            $audit->setTransport(new BasicTransport());
            if(($audit->notify('AuditEvent message from tester')) == false){
                $this->errorAudit = true;
                $this->errorAuditMessage .= "<br>notify (1) returned an error";
            } // default level is AuditEvent::LVL_INFO
            if(($audit->notify('Audit debug message from tester', array('level' => AuditEvent::LVL_DEBUG))) == false){
                $this->errorAudit = true;
                $this->errorAuditMessage .= "<br>notify (2) returned an error";
            }
        }catch(Exception $e){
            $this->errorAudit = true;
            $this->errorAuditMessage .= "<br>" . $e;
        }
    }
    public function mailerTester()
    {
        try {
            $mailer = new Mailer(array(Mailer::OPTION_BASEURL => $this->mailServer));
            $mailer->setTransport(new BasicTransport());
            $message = new Mail();
            $message->setSubject('Tester is working');
            $message->setTextBody('Tester is checking mailer');
            $message->addRecipient($this->mailRecipient);
            $message->setSender(array('no-reply@flash-global.net'));
            if (!($mailer->transmit($message))) {
                $this->errorMailer = true;
                $this->errorMailerMessage .= "transmit returned an error";
            }
        }catch(\Exception $e){
            $this->errorMailer = true;
            $this->errorMailerMessage .= $e;
        }
    }
    public function filerTester()
    {
        try {
            $filer = new Filer([Filer::OPTION_BASEURL => $this->filerServer]);
            $filer->setTransport(new BasicTransport());
            $file = $filer::embed(__DIR__ . '/../PluginTestService/TesterFiles/testUpload');
            $file->setCategory(File::CATEGORY_MISCELLANEOUS)
                ->setContexts([
                    'name'       => 'testUpload'
                ]);
            $uuid = $filer->upload($file, null);
            if ($uuid == false) {
                $this->errorFiler = true;
                $this->errorFilerMessage .= "upload returned an error";
            }
        }catch(\Exception $e){
            $this->errorFiler = true;
            $this->errorFilerMessage .= $e;
        }
    }
    public function getErrorLog(){
        return $this->errorLog;
    }
    public function getErrorAudit(){
        return $this->errorAudit;
    }
    public function getErrorMailer(){
        return $this->errorMailer;
    }
    public function getErrorFiler(){
        return $this->errorFiler;
    }
    public function getErrorLogMessage(){
        return $this->errorLogMessage;
    }
    public function getErrorAuditMessage(){
        return $this->errorAuditMessage;
    }
    public function getErrorMailerMessage(){
        return $this->errorMailerMessage;
    }
    public function getErrorFilerMessage(){
        return $this->errorFilerMessage;
    }
}
