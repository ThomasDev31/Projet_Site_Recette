<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\CommentaireType;
use App\Entity\Recette;
use App\Entity\User;
use App\Entity\Note;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/recette')]
final class RecetteController extends AbstractController
{
    #[Route(name: 'recette.index', methods: ['GET'])]
    public function index(RecetteRepository $recetteRepository): Response
    {
        return $this->render('recette/index.html.twig', [
            'recettes' => $recetteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'recette.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('file')->getData();
            $sizeImage = $imageFile->getSize();

            if($sizeImage <= 2097152){
                if($imageFile){
                    
                    $originalFile = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFileName = $slugger->slug($originalFile);
                    $newFileName = $safeFileName . '-' . uniqid().'.'. $imageFile->guessExtension();

                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFileName
                    );
                    $recette->setFile($newFileName);
                }
                $userId = $this->getUser();
                $user = $entityManager->getRepository(User::class)->find($userId);

                $recette->setUser($user);

                $entityManager->persist($recette);
                $entityManager->flush();

                return $this->redirectToRoute('recette.index', [], Response::HTTP_SEE_OTHER);
            }else{
                $this->addFlash('error', "La taille de l'image est trop grande");
            }
        }
        return $this->render('recette/new.html.twig', [
            'recette' => $recette,
            'form' => $form,
        ]);
    }

    #[Route('/{userId}', name: 'recette.userId')]
    public function recetteByIdUser(EntityManagerInterface $entityManager, RecetteRepository  $recetteRepository, $userId): Response
    {

        $user = $entityManager->getRepository(User::class)->find($userId);

        $recettes = $recetteRepository->findByIdUser($userId);
  
        return $this->render('recette/show.html.twig', [
            'recettes' => $recettes,
        ]);
    }


    #[Route('/info/{id}', name: 'recette.info', methods: ['GET', 'POST'])]
    public function show(Recette $recette, EntityManagerInterface $entityManager,Request $request, $id): Response
    {  
        $message = new Message(); 
        $user = $this->getUser();

        $form = $this->createForm(CommentaireType::class, $message);
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            $avi = $form->get('commentaires')->getData();

            $message->setCommentaires($avi);
            $message->setCreateAt(new \DateTimeImmutable());
            $message->setRecette($recette); 

            if($user){
                $message->setUser($user);
            }
            
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('recette.info', ['id' => $recette->getId()]);
        }

        $commentaire = $recette->getMessage()->toArray();
        $note = $recette->getNotes()->toArray();
        $notemoyenne = [];

        foreach($note as $value){
            $notemoyenne [] = $value->getValue();
        }

        $moyenne = count($notemoyenne) > 0 ? array_sum($notemoyenne) / count($notemoyenne) : 0;
        $recette->setMoyenne($moyenne);
        $entityManager->persist($recette);
        $entityManager->flush();
        
        return $this->render('recette/info.html.twig', [
            'recette' => $recette,
            'form' => $form->createView(),
            'commentaires' => $commentaire,
            'note' => $moyenne
        ]);
    }

    #[Route('/{id}/edit', name: 'recette.editId', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recette $recette, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('recette.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recette/edit.html.twig', [
            'recette' => $recette,
            'form' => $form,
        ]);
    }

    #[Route('/recette/{id}', name: 'recette.deleteId', methods: ['POST'])]
    public function delete(Request $request, Recette $recette, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recette->getId(), $request->getPayload()->getString('_token'))) {
            foreach ($recette->getMessage() as $message) {
                $entityManager->remove($message);
            }

            foreach ($recette->getNotes() as $note) {
                $entityManager->remove($note);
            }

            $entityManager->remove($recette);
            $entityManager->flush();
        }

        return $this->redirectToRoute('recette.index', [], Response::HTTP_SEE_OTHER);
    }
}
