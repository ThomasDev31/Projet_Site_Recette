<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\Recette;
use App\Form\NoteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class NoteController extends AbstractController
{
    #[Route('/note/{id}', name: 'note.index', requirements: ['id' => '\d+'])]
    public function index(Request $request, Recette $recette, EntityManagerInterface $entityManager): Response
    {   
        $note = new Note();
        $user = $this->getUser();

        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $avi = $form->get('value')->getData();

            $note->setValue($avi);
            $note->setRecette($recette); 

            if($user){
                $note->setUser($user);
            }
            
            $entityManager->persist($note);
            $entityManager->flush();

            return $this->redirectToRoute('recette.info', ['id' => $recette->getId()]);
        }
   

        return $this->render('note/index.html.twig', [
           'form' => $form
        ]);
        }
    }

