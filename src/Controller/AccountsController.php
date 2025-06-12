<?php

namespace App\Controller;

use App\Entity\Bnet;
use App\Form\BnetAccountType;
use App\Service\PvPGNPasswordHasher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AccountsController extends AbstractController
{
    public function __construct(private PvPGNPasswordHasher $pvpgnPasswordHasher) {}

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/accounts', name: 'accounts')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $bnet = new Bnet();
        $form = $this->createForm(BnetAccountType::class, $bnet);
        $form->handleRequest($request);
        $error = null;
        $success = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $acctUsername = $bnet->getAcctUsername();
            $password = $form->get('password')->getData();
            $verifyPassword = $form->get('verifyPassword')->getData();

            $existing = $em->getRepository(Bnet::class)->findOneBy(['acctUsername' => $acctUsername]);
            if ($existing) {
                $error = 'Account username is already taken.';
            } elseif ($password !== $verifyPassword) {
                $error = 'Passwords do not match.';
            } else {
                $bnet->setAcctPasshash1($this->pvpgnPasswordHasher->hash($password));
                $bnet->setUid($bnet->getUid());
                $bnet->setAcctUsername($bnet->getAcctUsername());
                $bnet->setUser($this->getUser()); // Link to current user
                $bnet->setAcctCtime(); // Set creation time to now
                $em->persist($bnet);
                $em->flush();
                $success = true;
            }
        }

        // Only show accounts for the current user
        $accounts = $em->getRepository(Bnet::class)->findBy(['user' => $this->getUser()]);

        return $this->render('accounts.html.twig', [
            'form' => $form->createView(),
            'accounts' => $accounts,
            'error' => $error,
            'success' => $success,
        ]);
    }

    #[Route('/accounts/change-password/{id}', name: 'accounts_change_password', methods: ['POST'])]
    public function changePassword(Request $request, EntityManagerInterface $em, int $id): Response
    {
        $bnet = $em->getRepository(Bnet::class)->find($id);
        if (!$bnet) {
            return $this->json(['success' => false, 'error' => 'Account not found.'], 404);
        }
        // Ensure the Bnet account belongs to the current user
        if ($bnet->getUser() !== $this->getUser()) {
            return $this->json(['success' => false, 'error' => 'You do not have permission to change this account password.'], 403);
        }
        $data = json_decode($request->getContent(), true);
        $oldPassword = $data['oldPassword'] ?? '';
        $newPassword = $data['newPassword'] ?? '';
        $verifyPassword = $data['verifyPassword'] ?? '';

        // PvPGN hash is not a one-way hash, so we must hash the input and compare to the stored hash
        if ($this->pvpgnPasswordHasher->hash($oldPassword) !== $bnet->getAcctPasshash1()) {
            return $this->json(['success' => false, 'error' => 'Old password is incorrect.']);
        }
        if (strlen($newPassword) < 6 || strlen($newPassword) > 64) {
            return $this->json(['success' => false, 'error' => 'New password must be between 6 and 64 characters.']);
        }
        if ($newPassword !== $verifyPassword) {
            return $this->json(['success' => false, 'error' => 'Passwords do not match.']);
        }
        $bnet->setAcctPasshash1($this->pvpgnPasswordHasher->hash($newPassword));
        $em->flush();
        return $this->json(['success' => true, 'message' => 'Password changed successfully.']);
    }
}
