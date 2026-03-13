<?php
declare(strict_types=1);

namespace Nitsan\NitsanProduct\Authentication\EventListener;

use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Mail\Mailer;
use TYPO3\CMS\Core\Mail\FluidEmail;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Mail\MailerInterface;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Authentication\Event\AfterUserLoggedInEvent;
#[AsEventListener(
    identifier: 'nitsan_product/backend-user-loggin'
)]
final class LoginEventListener{
    private LoggerInterface $logger;
    
    public function __construct(LoggerInterface $logger){
        $this->logger = $logger;
    }

    public function __invoke(AfterUserLoggedInEvent $logginEvent){
        $userDetails = $logginEvent->getUser();

        if($userDetails instanceof BackendUserAuthentication && $userDetails->isAdmin()){
            $adminEmail = $userDetails->user['email'];
            $adminName = $userDetails->user['username'];
            
            if(!empty($adminEmail)){
                $Sendmail = GeneralUtility::makeInstance(MailMessage::class);

                // Simple mail from here 

                $Sendmail->from('no-reply@test.com')
                        ->to($adminEmail)
                        ->subject('Admin Login Successfully')
                        ->text("Hello $adminName, \n\n You have logged into typo3 Backed")
                        ->send();
                $this->logger->info('Admin login email sent', [
                    'username' => $adminName,
                    'email' => $adminEmail
                ]);


                $this->showBackendMessage();

            }
        }

    }

    public function showBackendMessage(){
        $flashMessage = GeneralUtility::makeInstance(
                                                        FlashMessage::class,
                                                        'Please check your email. A login notification was sent.',
                                                        'Admin Login',
                                                        ContextualFeedbackSeverity::OK,
                                                        true
                                                    );
        $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();

        $messageQueue->addMessage($flashMessage);
    }

}