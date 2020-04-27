<?php

namespace App\Controllers;

use App\Models\Employer;
use App\Models\Project;
use App\Models\Skill;
use App\Services\FreelanceHuntService;
use Core\Config;
use GuzzleHttp\Client;

class ApiController extends AbstractController
{
    function employers()
    {
        function parsePage($link){
            $url = parse_url($link);

            $query = $url['query'];

            return explode('=', $query)[1];
        }

        $service = new FreelanceHuntService();

        $employerModel = new Employer();

        $result = $service->employers();

        echo '<pre>'; print_r($result); echo '</pre>';
    }

    function skills()
    {
        function parsePage($link){
            $url = parse_url($link);

            $query = $url['query'];

            return explode('=', $query)[1];
        }

        $service = new FreelanceHuntService();

        $skillModel = new Skill();

        $result = $service->skills();

        while(isset($result['links']['next'])){
            foreach($result['data'] as $skill){
                $skillModel->insert([
                    'id' => $skill['id'],
                    'name' => $skill['name']
                ]);
            }

            $result = $service->employers(parsePage($result['links']['next']));
        };
    }

    function projects()
    {
        function parsePage($link){
            $url = parse_url($link);

            $query = $url['query'];

            return explode('=', $query)[1];
        }

        $service = new FreelanceHuntService();

        $projectsModel = new Project();

        $result = $service->projects();

        while(isset($result['links']['next'])){
            foreach($result['data'] as $project){
                $projectsModel->insert([
                    'id' => $project['id'],
                    'name' => $project['attributes']['name'],
                    'link' => $project['attributes']['link'],
                    'amount' => $project['attributes']['budget']['amount'],
                    'employer' => $project['attributes']['employer']['id']
                ]);

                $skills = $project['attributes']['skills'];

                foreach($skills as $skill){
                    $projectsModel->addSkill([
                        'skill_id' => $skill['id'],
                        'project_id' => $project['attributes']['id']
                    ]);
                }
            }

            $result = $service->employers(parsePage($result['links']['next']));
        };

        //echo '<pre>'; print_r($result); echo '</pre>';
    }


}