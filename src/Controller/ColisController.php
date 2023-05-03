<?php

namespace App\Controller;

use App\Entity\Colis;
use App\Form\ColisType;
use App\Repository\ColisRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;




#[Route('/colis')]
class ColisController extends AbstractController
{
    #[Route('/index', name: 'app_colis_index', methods: ['GET'])]
    public function index(Request $request,ColisRepository $colisRepository, PaginatorInterface $paginator,EntityManagerInterface $entityManager): Response
    {
        $pagination = $paginator->paginate(
            $colisRepository->findAll(),
            $request->query->getInt('page', 1),
            2
        );
       
    
        return $this->render('colis/index.html.twig', [ 'colis' => $pagination  ]);
        
    }




    

    #[Route('/new', name: 'app_colis_new', methods: ['GET', 'POST'])]
public function new(Request $request, ColisRepository $colisRepository, EntityManagerInterface $entityManager): Response
{
    $coli = new Colis();
    $form = $this->createForm(ColisType::class, $coli);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        /** @var UploadedFile $imageFile */
        $imageFile = $form->get('image')->getData();

        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $coli->setImage($newFilename);
        }

        $entityManager->persist($coli);
        $entityManager->flush();
        $this->addFlash('success', 'Limage a bien été ajoutée.');

        return $this->redirectToRoute('app_colis_index');
    }

    return $this->renderForm('colis/new.html.twig', [
        'form' => $form,
    ]);
}


#[Route('/pdf/{id}', name: 'app_colis_pdf', methods: ['GET'])]
public function pdf($id, EntityManagerInterface $entityManager): Response
{
    $colis = $entityManager
        ->getRepository(Colis::class)
        ->find($id);

    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');
    $dompdf = new Dompdf($pdfOptions);

    $html = $this->renderView('colis/pdf.html.twig', [
        'colis' => $colis,
    ]);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $pdfOutput = $dompdf->output();

    return new Response($pdfOutput, 200, [
        'Content-Type' => 'colis/pdf',
        'Content-Disposition' => 'attachment; filename="colis.pdf"'
    ]);
}

    #[Route('/show/{idColis}', name: 'app_colis_show', methods: ['GET'])]
    public function show(Colis $colis ): Response
    {
       
        return $this->render('colis/show.html.twig', [
            'coli' => $colis,
        ]);
    }

    #[Route('/{idColis}/edit', name: 'app_colis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Colis $coli, ColisRepository $colisRepository): Response
    {
        $form = $this->createForm(ColisType::class, $coli);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $colisRepository->save($coli, true);

            return $this->redirectToRoute('app_colis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('colis/edit.html.twig', [
            'coli' => $coli,
            'form' => $form,
        ]);
    }

    #[Route('/{idColis}', name: 'app_colis_delete', methods: ['POST'])]
    public function delete(Request $request, Colis $coli, ColisRepository $colisRepository, MailerInterface $mailer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coli->getIdColis(), $request->request->get('_token'))) {
            $colisRepository->remove($coli, true);
               
            $email = (new Email())
            ->from('iskander.guedri777@gmail.com')
            ->to('iskander.guedri777@gmail.com ')
            ->subject('Supressionde Colis')
            ->html('<p>Un Colis a été suprrimé:</p>' .
            '<ul>' .
            '<li>id du colis: ' . $coli->getIdColis() . '</li>' .
            '<li>categorie: ' . $coli->getCategorie() . '</li>' .
            '<li>de poids: ' . $coli->getPoids() . '</li>' .
            '</ul>');


        $mailer->send($email);
        }

        return $this->redirectToRoute('app_colis_index', [], Response::HTTP_SEE_OTHER);
    }
}
