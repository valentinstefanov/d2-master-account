<?php

namespace App\EventSubscriber;

use App\Entity\LoginHistory;
use App\Entity\User;
use App\Repository\LoginHistoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class LoginHistorySubscriber implements EventSubscriberInterface
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
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
            LoginFailureEvent::class => 'onLoginFailure',
        ];
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();
        if ($user instanceof User) {
            $loginHistory = new LoginHistory();
            $loginHistory->setUser($user);
            $loginHistory->setSuccess(true);
            $loginHistory->setCreatedAt(new \DateTime());
            $request = $event->getRequest();
            $ip = $request->getClientIp();
            $loginHistory->setIpAddress($ip);
            $this->em->persist($loginHistory);
            $this->em->flush();
        }
    }

    public function onLoginFailure(LoginFailureEvent $event): void
    {
        $passport = $event->getPassport();
        if ($passport && $passport->getUser()) {
            $user = $passport->getUser();
            if ($user instanceof User) {
                $managedUser = $this->em->getRepository(User::class)->find($user->getId());
                if ($managedUser) {
                    $loginHistory = new LoginHistory();
                    $loginHistory->setUser($managedUser);
                    $loginHistory->setSuccess(false);
                    $loginHistory->setCreatedAt(new \DateTime());
                    $request = $event->getRequest();
                    $ip = $request ? $request->getClientIp() : null;
                    $loginHistory->setIpAddress($ip);
                    $this->em->persist($loginHistory);
                    $this->em->flush();
                }
            }
        }
    }
}
