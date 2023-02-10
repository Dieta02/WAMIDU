<?php

namespace App\Controller;

use App\Entity\Vendeur;
use App\Form\VendeurType;
use App\Repository\VendeurRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;

#[Route('/vendeur')]
class VendeurController extends AbstractController
{
    #[Route('/', name: 'app_vendeur_index', methods: ['GET'])]
    public function index(VendeurRepository $vendeurRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $vendeur = $vendeurRepository->findAll();

        $vendeurs = $paginator->paginate($vendeur, $request->query->getInt('page', 1), 1);

        return $this->render('vendeur/index.html.twig', [
            'vendeurs' => $vendeurs,
        ]);
    }

    #[Route('/new', name: 'app_vendeur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, VendeurRepository $vendeurRepository, NotifierInterface $notifier): Response
    {
        $vendeur = new Vendeur();
        $form = $this->createForm(VendeurType::class, $vendeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vendeurRepository->save($vendeur, true);
            $notifier->send(new Notification($vendeur->getName() . ' ' . $vendeur->getSurname() . ' enregistré(e) avec succès ! ', ['browser']));

            return $this->redirectToRoute('app_vendeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vendeur/new.html.twig', [
            'vendeur' => $vendeur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vendeur_show', methods: ['GET'])]
    public function show(Vendeur $vendeur): Response
    {
        return $this->render('vendeur/show.html.twig', [
            'vendeur' => $vendeur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vendeur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vendeur $vendeur, VendeurRepository $vendeurRepository, NotifierInterface $notifier): Response
    {
        $form = $this->createForm(VendeurType::class, $vendeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vendeurRepository->save($vendeur, true);
            $notifier->send(new Notification($vendeur->getName() . ' ' . $vendeur->getSurname() . ' modifié(e) avec succès ! ', ['browser']));

            return $this->redirectToRoute('app_vendeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vendeur/edit.html.twig', [
            'vendeur' => $vendeur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vendeur_delete', methods: ['POST'])]
    public function delete(Request $request, Vendeur $vendeur, VendeurRepository $vendeurRepository, NotifierInterface $notifier): Response
    {
        if ($this->isCsrfTokenValid('delete' . $vendeur->getId(), $request->request->get('_token'))) {
            $vendeurRepository->remove($vendeur, true);
            $notifier->send(new Notification($vendeur->getName() . ' ' . $vendeur->getSurname() . ' supprimé(e) avec succès ! ', ['browser']));
        }

        return $this->redirectToRoute('app_vendeur_index', [], Response::HTTP_SEE_OTHER);
    }
}
