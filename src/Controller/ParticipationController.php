<?php

namespace App\Controller;

use App\Entity\Participation;
use App\Entity\Covoiturage;

use App\Form\ParticipationType;
use App\Repository\ParticipationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
#[Route('/participation')]
class ParticipationController extends AbstractController
{
    #[Route('/', name: 'app_participation_index', methods: ['GET'])]
    public function index(ParticipationRepository $participationRepository): Response
    {
        return $this->render('participation/index.html.twig', [
            'participations' => $participationRepository->findAll(),
        ]);
    }

    #[Route('/back', name: 'app_participation_index_back', methods: ['GET'])]
    public function indexback(ParticipationRepository $participationRepository): Response
    {
        return $this->render('participation/indexback.html.twig', [
            'participations' => $participationRepository->findAll(),
        ]);
    }


    #[Route('/new', name: 'app_participation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ParticipationRepository $participationRepository,MailerInterface $mailer): Response
    {
        $participation = new Participation();
        $form = $this->createForm(ParticipationType::class, $participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = $participation->getCovoiturage();
            $event->setNbPlace($event->getNbPlace() - 1);
            $participationRepository->save($participation, true);


            $email = (new Email())
            ->from('touskieart.reclamations@gmail.com')
            ->to($participation->getMail())
            ->subject('email de Participation')
            ->text('Participation  effecté  avec succées' );
             $mailer->send($email);
             
        $this->addFlash('success', 'participation ajoutée avec succès!');

            return $this->redirectToRoute('app_participation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participation/new.html.twig', [
            'participation' => $participation,
            'form' => $form,
        ]);
    }

    #[Route('/newback', name: 'app_participation_new_back', methods: ['GET', 'POST'])]
    public function newback(Request $request, ParticipationRepository $participationRepository): Response
    {
        $participation = new Participation();
        $form = $this->createForm(ParticipationType::class, $participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $participationRepository->save($participation, true);

            return $this->redirectToRoute('app_participation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participation/newback.html.twig', [
            'participation' => $participation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_participation_show', methods: ['GET'])]
    public function show(Participation $participation): Response
    {
        return $this->render('participation/show.html.twig', [
            'participation' => $participation,
        ]);
    }
    #[Route('/back/{id}', name: 'app_participation_show_back', methods: ['GET'])]
    public function showback(Participation $participation): Response
    {
        return $this->render('participation/showback.html.twig', [
            'participation' => $participation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_participation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Participation $participation, ParticipationRepository $participationRepository): Response
    {
        $form = $this->createForm(ParticipationType::class, $participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $participationRepository->save($participation, true);
            $this->addFlash('success', 'participation modifiée avec succès!');

            return $this->redirectToRoute('app_participation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participation/edit.html.twig', [
            'participation' => $participation,
            'form' => $form,
        ]);
    }

    #[Route('/back/{id}/edit', name: 'app_participation_edit_back', methods: ['GET', 'POST'])]
    public function editback(Request $request, Participation $participation, ParticipationRepository $participationRepository): Response
    {
        $form = $this->createForm(ParticipationType::class, $participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $participationRepository->save($participation, true);

            return $this->redirectToRoute('app_participation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participation/editback.html.twig', [
            'participation' => $participation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_participation_delete', methods: ['POST'])]
    public function delete(Request $request, Participation $participation, ParticipationRepository $participationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participation->getId(), $request->request->get('_token'))) {
            $covoiturage = $participation->getCovoiturage();
            $currentNbPlaces = $covoiturage->getNbPlace();
            $covoiturage->setNbPlace($currentNbPlaces + 1);
            $participationRepository->remove($participation, true);

        }
        $this->addFlash('success', 'participation supprimée avec succès!');


        return $this->redirectToRoute('app_participation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/back/{id}', name: 'app_participation_delete_back', methods: ['POST'])]
    public function deleteback(Request $request, Participation $participation, ParticipationRepository $participationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participation->getId(), $request->request->get('_token'))) {
            $participationRepository->remove($participation, true);
        }

        return $this->redirectToRoute('app_participation_index_back', [], Response::HTTP_SEE_OTHER);
    }
   

    #[Route('/covoiturage/{id}/participation', name: 'app_covoiturage_participations', methods: ['GET'])]
    public function showParticipationsByCovoiturageId(Covoiturage $covoiturage, ParticipationRepository $participationRepository)
{
    $participations = $participationRepository->findParticipationsByCovoiturageId($covoiturage->getId());

    return $this->render('participation/participationidcov.html.twig', [
        'covoiturage' => $covoiturage,
        'participations' => $participations,
    ]);
}


}



