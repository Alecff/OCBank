<?php

namespace App\Controller;

use App\Entity\CPU;
use App\Entity\Result;
use App\Form\ResultFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ResultController extends AbstractController
{
    /**
     * @Route("/result", name="result")
     */
    public function index()
    {
        return $this->render('result/index.html.twig', [
            'controller_name' => 'ResultController',
        ]);
    }

    /**
     * @Route("results/new", name="new_result")
     *
     * @param RouterInterface $router
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param UserInterface|null $user
     *
     * @return Response
     */
    public function addResult(RouterInterface $router, EntityManagerInterface $em,  Request $request, UserInterface $user = null)
    {
        $autoCompleteUrl = $router->generate('get_cpus_json');
        $error = '';
        $this->denyAccessUnlessGranted('ROLE_USER');
        $form = $this->createForm(ResultFormType::class, null, ['attr' => ['url' => $autoCompleteUrl]]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $result = new Result();
            $cpu = $em->getRepository(CPU::class)->findOneBy(['Name' => $data['CPU']]);
            $result
                ->setCPU($cpu)
                ->setMaxSpeed($data['MaxSpeed'])
                ->setMaxSpeedVoltage($data['MaxSpeedVoltage'])
                ->setUser($this->getUser());
            $em->persist($result);
            $em->flush();
        } else {
            $error = 'Could not be submitted. Better message TODO.';
        }

        return $this->render('result/new.html.twig', [
            'error' => $error,
            'form' => $form->createView(),
            'url' => $autoCompleteUrl,
        ]);
    }
}
