<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\QrCodeService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail as MimeTemplatedEmail;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Mailer\MailerInterface;


#[Route('/client')]
class ClientController extends AbstractController
{

    #[Route('/', name: 'app_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository, PaginatorInterface  $paginator, Request $request): Response
    {
        $client = $clientRepository->findAll();
        $clients = $paginator->paginate($client, $request->query->getInt('page', 1), 2);
        return $this->render('client/index.html.twig', [
            'clients' => $clients
        ]);
    }
    public function SendMail($client, $qrcode, MailerInterface $mailer)
    {
        $email = (new Email())
            ->from(new Address('no-reply@wamidu.com', 'WAMIDU'))
            ->to($client->getEmail())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com') 
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Wamidu Qr Code !')
            ->html('<header><img src="public/assets/img/logo.png"  alt="wamidu"/></header>
            <div>
            <h4>Salut ' . $client->getSurname() . ' ,</h4>
            <div>Voici ton QrCode qui te permet d accéder aux services wamidu</br>
            <img src="' . $qrcode . '"  alt="wamidu" width="60" height="60"/>
            <p> Ton code personnel est : ' . $client->getCodeUser() . '</p>
            Clique sur ce bouton pour voir ton historique et le nombre de tickets restants
            <button><buton/>
             </div>
            </div>
            <footer><a><img src="assets/img/logo.png"  alt="wamidu"  width="60" height="60"/>WAMIDU</a>
            </br>
            <p> Copyright 2023 WAMIDU</p>
            
            <footer>');
        $mailer->send($email);
    }

    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(
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

        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
            'errors' => $errors,

        ]);
    }


    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]

    public function edit(Request $request, Client $client, ClientRepository $clientRepository, NotifierInterface $notifier): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientRepository->save($client, true);
            $notifier->send(new Notification($client->getName() . ' ' . $client->getSurname() . ' modifié(e) avec succès ! ', ['browser']));

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, ClientRepository $clientRepository, NotifierInterface $notifier): Response
    {
        if ($this->isCsrfTokenValid('delete' . $client->getId(), $request->request->get('_token'))) {
            $clientRepository->remove($client, true);
            $notifier->send(new Notification($client->getName() . ' ' . $client->getSurname() . ' supprimé(e) avec succès ! ', ['browser']));
        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }
}
