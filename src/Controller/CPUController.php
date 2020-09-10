<?php

namespace App\Controller;

use App\Entity\CPU;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
     *
     * @throws ExceptionInterface
     */
    public function showCPUsJson(EntityManagerInterface $em, Request $request): Response
    {
        $serializer = new Serializer([new ObjectNormalizer()]);
        $cpus = $em->getRepository(CPU::class)->findAllContaining($request->query->get('query'));
        $cpuJSON = $serializer->normalize($cpus, null, [AbstractNormalizer::ATTRIBUTES => ['Name']]);

        return $this->json($cpuJSON);
    }
}
