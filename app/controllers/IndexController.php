<?php

namespace App\Controllers;

use App\Models\Project;
use App\Services\ExchangeService;
use JasonGrimes\Paginator;

class IndexController extends AbstractController
{
    function index($page = 1)
    {
        $projectsModel = new Project();

        $skills = [1, 99, 86];

        $projects = $projectsModel->getProjects(10, ($page - 1) * 10, $skills);
        $totalProjects = $projectsModel->getTotalProjectsInSkills($skills);

        $pagination = new Paginator($totalProjects, 10, $page, '/projects/(:num)');

        return $this->render('pages/projects.html.twig', [
            'projects' => $projects,
            'pagination' => [
                'prev' => $pagination->getPrevUrl(),
                'pages' => $pagination->getPages(),
                'next' => $pagination->getNextUrl()
            ]
        ]);
    }

    function info()
    {
        $exchangeService = new ExchangeService();

        $projectsModel = new Project();

        $rate = $exchangeService->UAH2RUB(1, ExchangeService::TYPE_BUY);

        $groups = $projectsModel->getProjectsGroups($rate);

        return $this->render('pages/projects-info.html.twig', [
            'groups' => $groups
        ]);
    }
}