<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ArtistController extends AbstractController
{
    /**
     * @Route("/admin/artists", name="artist_admin")
     * @isGranted("ROLE_ADMIN")
     */
    public function adminAction(ArtistRepository $repository): Response {
        return $this->render("artist/admin.twig", ["artists" => $repository->findAll()]);
    }

    /**
    * @Route("/admin/artist/new",name="artist_new")
     * @isGranted("ROLE_ADMIN")
    */
    public function createAction(Request $request): Response {
        return $this->setUpForm(new Artist(),$request,true);
    }

    /**
    * @Route("/admin/artist/{id}/update",name="artist_update")
     * @isGranted("ROLE_ADMIN")
    */
    public function updateAction(Artist $artist, Request $request): Response {
        return $this->setUpForm($artist,$request,true);
    }

    /**
     * @Route("/admin/artist/{id}/delete",name="artist_delete")
     * @isGranted("ROLE_ADMIN")
     */
    public function deleteAction(Artist $artist, EntityManagerInterface $manager): Response {
        $manager->remove($artist);
        $manager->flush();
        return $this->redirectToRoute("artist_admin");
    }


    private function setUpForm(Artist $artist, Request $request, bool $create = false)
    {
        $form = $this->createForm(ArtistType::class, $artist);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $artist = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($artist);
            $manager->flush();

            return $this->redirectToRoute("artist_admin");
        }

        return $this->render("form/update_create_form.twig", ["form" => $form->createView(), "create" => $create, "entity" => "Artist"]);
    }


}
