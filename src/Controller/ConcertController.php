<?php

namespace App\Controller;

use App\Entity\Concert;
use App\Form\ConcertType;
use App\Repository\ConcertRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ConcertController extends AbstractController
{
    /**
     * @Route("/concert/{id}", name="concert")
     */
    public function detailAction(Concert $concert): Response
    {
        return $this->render('concert/detail.twig', [
            'concert' => $concert,
            "address" => $concert->getRoom()->getOrganization()->getAddress()
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function indexAction(ConcertRepository $repository): Response {
        $date = new DateTime();
        $mySQLdate = $date->format("Y-m-d");
        return $this->render("concert/index.twig",["concerts" => $repository->findByDate($mySQLdate,"after"), "past" => false]);
    }

    /**
     * @Route("/past", name="past_concerts")
     */
    public function pastAction(ConcertRepository $repository): Response {
        $date = new DateTime();
        $mySQLdate = $date->format("Y-m-d");
        return $this->render("concert/index.twig",["concerts" => $repository->findByDate($mySQLdate,"before"), "past" => true]);
    }

    /**
     * @Route("/admin/concerts", name="concerts_admin")
     * @isGranted("ROLE_ADMIN")
     */
    public function adminAction(ConcertRepository $repository): Response {
        return $this->render("concert/admin.twig",["concerts" => $repository->findAll()]);
    }

    /**
     * @Route("/admin/concert/new", name="concert_create")
     * @isGranted("ROLE_ADMIN")
     */
    public function createAction(Request $request): Response
    {
        return $this->setUpForm(new Concert(), $request, true);
    }

    /**
     * @Route("/admin/concert/delete/{id}", name="concert_delete")
     * @isGranted("ROLE_ADMIN")
     */
    public function deleteAction(Concert $concert, EntityManagerInterface $manager): Response {
        $manager->remove($concert);
        $manager->flush();



        return $this->redirectToRoute("concerts_admin");
    }

    /**
     * @Route("admin/concert/update/{id}", name="concert_update")
     * @isGranted("ROLE_ADMIN")
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
        }

        return $this->render("form/update_create_form.twig", ["form" => $form->createView(), "create" => $create, "entity" => "Concert"]);
    }
}
