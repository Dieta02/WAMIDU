<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Historique;
use App\Repository\ClientRepository;
use App\Repository\HistoriqueRepository;
use App\Service\QrCodeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/wamidu', name: 'app_wamidu')]
class ClientViewController extends AbstractController
{

    #[Route('/index', name: 'app_wamidu_index')]
    public function index(Request $request): Response
    {
        if ($request->get('code')) {
        }
        return $this->render('client_view/index.html.twig', [
            'controller_name' => 'ClientViewController',
        ]);
    }

    #[Route('/historique/{client}', name: 'app_wamidu_historique', methods: ['GET'])]
    public function historique($client, HistoriqueRepository $historiqueRepository): Response
    {
        $historique = $historiqueRepository->findBy(array("client" => $client));
        return $this->render('client_view/historique.html.twig', [
            'historiques' => $historique,
        ]);
    }

    #[Route('profil/{id}', name: 'app_wamidu_profil', methods: ['GET'])]
    public function profil($id, ClientRepository $clientRepository): Response
    {
        $profil = $clientRepository->findBy(array("id" => $id));
        return $this->render('client_view/profil.html.twig', [
            'profil' => $profil,
        ]);
    }

    #[Route('/partage', name: 'app_wamidu_partage', methods: ['GET', 'POST'])]
    public function partage(
        Request $request,
        ClientRepository $clientRepository,
        QrCodeService $qrCodeService,
        NotifierInterface $notifier,
        ValidatorInterface $validator,
    ): Response {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        $errors = $validator->validate($client);
        if ($form->isSubmitted() && $form->isValid()) {
            $clientRepository->save($client, true);

            $qrcode = $qrCodeService->qrcode($client->getId());


            //$this->addFlash('success', $client->getSurname() . ' ' . $client->getName() . ' a été enregistré avec succès');
            $notifier->send(new Notification($client->getName() . ' ' . $client->getSurname() . ' enregistré(e) avec succès ! ', ['browser']));
            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('client_view/partage.html.twig', [
            'client' => $client,
            'form' => $form,
            'errors' => $errors,

        ]);
    }
}