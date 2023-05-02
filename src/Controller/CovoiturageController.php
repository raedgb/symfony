<?php

namespace App\Controller;

use App\Entity\Covoiturage;
use App\Form\CovoiturageType;
use App\Repository\CovoiturageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Twilio\Rest\Client;
use Symfony\Component\OptionsResolver\Options;
use Dompdf\Dompdf;

#[Route('/covoiturage')]
class CovoiturageController extends AbstractController
{
    #[Route('/', name: 'app_covoiturage_index', methods: ['GET'])]
    public function index(CovoiturageRepository $covoiturageRepository,EntityManagerInterface $entityManager): Response
    {
        $query = $entityManager->createQuery(
            'SELECT a
            FROM App\Entity\Covoiturage a
            ORDER BY a.date_depart DESC'
        );
    
        $covoiturages = $query->getResult();
        
        return $this->render('covoiturage/index.html.twig', [
            'covoiturages' => $covoiturages,
        ]);
    }


  #[Route('/pdf/{id}', name: 'app_covoiturage_pdf', methods: ['GET'])]
public function pdf($id, EntityManagerInterface $entityManager): Response
{
    $covoiturage = $entityManager
        ->getRepository(Covoiturage::class)
        ->find($id);

 
    $pdfOptions=['defaultFont', 'Arial'];
    $dompdf = new Dompdf($pdfOptions);

    $html = $this->renderView('covoiturage/pdf.html.twig', [
        'covoiturage' => $covoiturage,
    ]);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $pdfOutput = $dompdf->output();

    return new Response($pdfOutput, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="covoiturage.pdf"'
    ]);
}

    
    #[Route('/back', name: 'app_covoiturage_index_back', methods: ['GET'])]
    public function indexback(CovoiturageRepository $covoiturageRepository): Response
    {
        return $this->render('covoiturage/indexback.html.twig', [
            'covoiturages' => $covoiturageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_covoiturage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CovoiturageRepository $covoiturageRepository): Response
    {
        $covoiturage = new Covoiturage();
        $form = $this->createForm(CovoiturageType::class, $covoiturage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $covoiturageRepository->save($covoiturage, true);


           $sid = 'ACab2c2616352a0def3c4ce0b5120f5476';
        $token = '235239a14f23241eda0dd6db24c00f80';
         $twilio = new Client($sid, $token);
         $twilio->messages
                          ->create('+21628254738', 
                                   array(
                                       'from' => '+16315199780', 
                                       'body' => 'Votre coviturage a été ajoutée avec succées ' 
                                   )
                          );


            return $this->redirectToRoute('app_covoiturage_index', [], Response::HTTP_SEE_OTHER);
        }
        
        
        return $this->renderForm('covoiturage/new.html.twig', [
            'covoiturage' => $covoiturage,
            'form' => $form,
        ]);
    }

   

    #[Route('/{id}', name: 'app_covoiturage_show', methods: ['GET'])]
    public function show(Covoiturage $covoiturage): Response
    {
        return $this->render('covoiturage/show.html.twig', [
            'covoiturage' => $covoiturage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_covoiturage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Covoiturage $covoiturage, CovoiturageRepository $covoiturageRepository): Response
    {
        $form = $this->createForm(CovoiturageType::class, $covoiturage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $covoiturageRepository->save($covoiturage, true);
            $this->addFlash('success', 'Covoiturage modifiée avec succès!');

            return $this->redirectToRoute('app_covoiturage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('covoiturage/edit.html.twig', [
            'covoiturage' => $covoiturage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_covoiturage_delete', methods: ['POST'])]
    public function delete(Request $request, Covoiturage $covoiturage, CovoiturageRepository $covoiturageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$covoiturage->getId(), $request->request->get('_token'))) {
            $covoiturageRepository->remove($covoiturage, true);
        }
        $this->addFlash('success', 'Covoiturage supprimée avec succès!');

        return $this->redirectToRoute('app_covoiturage_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/back/{id}', name: 'app_covoiturage_delete_back', methods: ['POST'])]
    public function deleteback(Request $request, Covoiturage $covoiturage, CovoiturageRepository $covoiturageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$covoiturage->getId(), $request->request->get('_token'))) {
            $covoiturageRepository->remove($covoiturage, true);
        }

        return $this->redirectToRoute('app_covoiturage_index_back', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/covoiturage/{covid}/like', name: 'app_covoiturage_like', methods: ['POST'])]
    public function like(Request $request, EntityManagerInterface $entityManager,$covid): Response
    {
        $covoiturage = $entityManager
        ->getRepository(Covoiturage::class)
        ->find($covid);
    if (! $covoiturage) {
        throw $this->createNotFoundException(
            'No covoiturage found for id '.$covid
        );
    }

    $covoiturage->setLikes($covoiturage->getLikes() + 1);
    $entityManager->flush();

    // Redirect to the index page after liking the annonce
    return new RedirectResponse($this->generateUrl('app_covoiturage_index'));
    }
  
    #[Route('/covoiturage/{covid}/dislike', name: 'app_covoiturage_dislike', methods: ['POST'])]
    public function dislike(Request $request, EntityManagerInterface $entityManager,$covid): Response
    {
        $covoiturage = $entityManager
        ->getRepository(Covoiturage::class)
        ->find($covid);
    if (!$covoiturage) {
        throw $this->createNotFoundException(
            'No annonce found for id '.$covid
        );
    }

    $covoiturage->setDislikes($covoiturage->getDislikes() + 1);
    $entityManager->flush();

    // Redirect to the index page after disliking the annonce
    return new RedirectResponse($this->generateUrl('app_covoiturage_index'));
    }
}
