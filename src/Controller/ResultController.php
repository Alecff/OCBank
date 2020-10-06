<?php

namespace App\Controller;

use App\Entity\CPU;
use App\Entity\Node;
use App\Entity\Result;
use App\Form\ResultFormType;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\LineChart;
use ContainerIdcwq1H\getValidator_EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

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
     * @param SluggerInterface $slugger
     * @return Response
     */
    public function addResult(RouterInterface $router, EntityManagerInterface $em,  Request $request, SluggerInterface $slugger)
    {
        $autoCompleteUrl = $router->generate('get_cpus_json');
        $error = '';
        $this->denyAccessUnlessGranted('ROLE_USER');
        $form = $this->createForm(ResultFormType::class, ['url' => $autoCompleteUrl]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $cpu = $em->getRepository(CPU::class)->findOneBy(['Name' => $data['CPU']]);

            //if a valid cpu is given, add the result to the DB
            if ($cpu) {
                $result = new Result();
                $result
                    ->setCPU($cpu)
                    ->setMaxSpeed($data['MaxSpeed'])
                    ->setMaxSpeedVoltage($data['MaxSpeedVoltage'])
                    ->setUser($this->getUser());
                $em->persist($result);

                //add the nodes to the DB
                foreach ($data['nodes'] as $node) {
                    $file = $node['Proof'];
                    $filename = pathinfo($file, PATHINFO_FILENAME);
                    $safeFileName = $slugger->slug($filename);
                    $newFileName = $safeFileName.'-'.uniqid().'.'.$file->guessExtension();
                    try{
                        $file->move(
                            $this->getParameter('proof_directory'),
                            $newFileName
                        );
                    } catch (\Throwable $e) {
                        dd($e);
                    }
                    $nodeEntity = new Node();

                    $nodeEntity
                        ->setClock($node['Clock'])
                        ->setVoltage($node['Voltage'])
                        ->setResult($result)
                        ->setProof($newFileName)
                        ->setVerified(false);
                    $em->persist($nodeEntity);

                }
                $em->flush();

                return $this->redirectToRoute('home');
            } else {
                $error = 'CPU does not exist in our database yet.';
            }
        }

        return $this->render('result/new.html.twig', [
            'error' => $error,
            'form' => $form->createView(),
            'url' => $autoCompleteUrl,
        ]);
    }

    /**
     * @Route("results/validate")
     *
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function unverifiedResults(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $unverifiedNodes = $em->getRepository(Node::class)->findBy([
            'verified' => false
        ]);
        return $this->render('unverified.html.twig', [
            'nodes' => $unverifiedNodes
        ]);
    }

    /**
     * @Route("results/show")
     * @param EntityManagerInterface $em
     * @return Response
     */

    public function showResult(EntityManagerInterface $em): Response
    {

        $lineChart = new LineChart();

        $cpus = [
            ['Freq', 'Your CPU'],
            [4.5, 1.04],
            [4.6, 1.15],
            [4.7, 1.24],
            [4.8, 1.31],
            [4.9, 1.35],
            [5.0, 1.38],
            [5.1, null],

        ];

        $dbCpus = $em->getRepository(Result::class)->findAll();

        for ($i = 2; $i < 5; $i++) {
            $cpus[0][$i] = 'Database result '.$dbCpus[$i-2]->getId();
            foreach ($cpus as $lineKey => $line) {
                foreach ($dbCpus[$i-2]->getNodes() as $key => $node) {
                    if ($line[0] == $node->getClock()) {
                        $line[$i] = $node->getVoltage();
                    }
                }
                if (!array_key_exists($i, $line)) {
                    $line[$i] = null;
                }
                $cpus[$lineKey] = $line;
            }
        }



        $lineChart->getData()->setArrayToDataTable($cpus);
        $lineChart->getOptions()
            ->setTitle('i5-8600k Frequency Curve with Voltage (lower is better)')
            ->setWidth(1200)
            ->setHeight(700)
            ;


        return $this->render('show-result.html.twig', [
            'chart' => $lineChart,
            'cpuName' => 'i5-8600k'
        ]);
    }
}
