<?php

namespace App\Controller;

use App\Entity\Band;
use App\Form\BandType;
use App\Repository\BandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BandController extends AbstractController
{
    /**
     * @Route("/bands", name="bands")
     */
    public function indexAction(): Response
    {
        $repo = $this->getDoctrine()->getRepository(Band::class);
        return $this->render('band/index.twig', [
            'bands' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/admin/bands", name="band_admin")
     */
    public function adminAction(): Response
    {
        $repo = $this->getDoctrine()->getRepository(Band::class);
        return $this->render('band/admin.twig', [
            'bands' => $repo->findAll(),
        ]);
    }

    /**
     * @param $band
     * @param Request $request
     * @return RedirectResponse|Response
     */
    private function setUpForm($band, Request $request, bool $create = false)
    {
        $form = $this->createForm(BandType::class, $band);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $band = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($band);
            $manager->flush();

            return $this->redirectToRoute("band_admin");
        } else if ($form->isSubmitted()) {
            return $this->render('error.twig', ['errors' => (array)$form->getErrors()]);
        }

        return $this->render("band/form.twig", ["form" => $form->createView(), "create" => $create]);
    }

    /**
     * @Route("/admin/band/new", name="band_new")
     */
    public function createAction(Request $request): Response
    {
        $band = new Band();
        return $this->setUpForm($band,$request,true);
    }

    /**
     * @Route("/band/{id}", name="band_detail")
     */
    public function detailAction(Band $band): Response
    {
        return $this->render('band/detail.twig', [
            'band' => $band,
        ]);
    }

    /**
     * @Route("/admin/band/{id}/update", name="band_update")
     */
    public function edit(Request $request, Band $band): Response
    {
        return $this->setUpForm($band,$request);
    }

    /**
     * @Route("/admin/band/{id}/delete", name="band_delete")
     */
    public function deleteAction(Band $band): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($band);
        $manager->flush();

        return $this->redirectToRoute('band_admin');
    }
}
