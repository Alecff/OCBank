<?php

namespace App\Controller;

use App\Entity\CPU;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CPUController extends AbstractController
{
    /**
     * @Route("/cpus", name="show_cpus")
     */
    public function index(): Response
    {
        return $this->render('cpu/index.html.twig', [
            'controller_name' => 'CPUController',
        ]);
    }

    /**
     * @Route("/cpus/json", name="get_cpus_json")
     *
     * @param EntityManagerInterface $em
     * @param Request $request
     *
     * @return Response
     */
    public function showCPUsJson(EntityManagerInterface $em, Request $request): Response
    {
        $cpus = $em->getRepository(CPU::class)->findAllContaining($request->query->get('query'));
        return $this->json([
            'cpus' => $cpus
        ]);

    }
}
