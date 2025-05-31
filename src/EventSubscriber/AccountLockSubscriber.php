<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Repository\LoginHistoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;

class AccountLockSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $em;
    private LoginHistoryRepository $loginHistoryRepository;

    public function __construct(EntityManagerInterface $em, LoginHistoryRepository $loginHistoryRepository)
    {
        $this->em = $em;
        $this->loginHistoryRepository = $loginHistoryRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginFailureEvent::class => 'onLoginFailure',
        ];
    }

    public function onLoginFailure(LoginFailureEvent $event): void
    {
        $passport = $event->getPassport();
        if ($passport && $passport->getUser()) {
            $user = $passport->getUser();
            if ($user instanceof User && !$user->isLocked()) {
                $failedAttempts = $this->loginHistoryRepository->countFailedLoginsInLastHour($user);
                if ($failedAttempts >= 5) {
                    $user->setLocked(true);
                    $this->em->persist($user);
                    $this->em->flush();
                }
            }
        }
    }
}
