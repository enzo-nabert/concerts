<?php

namespace App\Controller;

use App\Entity\Concert;
use App\Form\ConcertType;
use App\Repository\ConcertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Config\Doctrine\Orm\EntityManagerConfig;

class ConcertController extends AbstractController
{
    /**
     * @Route("/concert/{id}", name="concert")
     */
    public function detailAction(int $id): Response
    {
        $concertRepo = $this->getDoctrine()->getRepository(Concert::class);
        $concert = $concertRepo->find($id);
        return $this->render('concert/detail.twig', ['concert' => $concert]);
    }

    /**
     * @Route("/", name="home")
     */
    public function indexAction(ConcertRepository $repository): Response {
        return $this->render("concert/index.twig",["concerts" => $repository->findAll()]);
    }

    /**
     * @Route("/admin/concerts", name="concerts_admin")
     */
    public function adminAction(ConcertRepository $repository): Response {
        return $this->render("concert/admin.twig",["concerts" => $repository->findAll()]);
    }

    /**
     * @Route("/admin/concert/new", name="concert_create")
     */
    public function createAction(Request $request): Response
    {
        $concert = new Concert();

        return $this->setUpForm($concert, $request, true);
    }

    /**
     * @Route("/admin/concert/delete/{id}", name="concert_delete")
     */
    public function deleteAction(Concert $concert, EntityManagerInterface $manager): Response {
        $manager->remove($concert);
        $manager->flush();



        return $this->redirectToRoute("concerts_admin");
    }

    /**
     * @Route("admin/concert/update/{id}", name="concert_update")
     */
    public function updateAction(Request $request, Concert $concert): Response
    {
        return $this->setUpForm($concert, $request);
    }

    /**
     * @param $concert
     * @param Request $request
     * @return RedirectResponse|Response
     */
    private function setUpForm($concert, Request $request, bool $create = false)
    {
        $form = $this->createForm(ConcertType::class, $concert);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $concert = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($concert);
            $manager->flush();

            return $this->redirectToRoute("concerts_admin");
        } else if ($form->isSubmitted()) {
            return $this->render('error.twig', ['errors' => (array)$form->getErrors()]);
        }

        return $this->render("concert/form.twig", ["form" => $form->createView(), "create" => $create]);
    }
}
