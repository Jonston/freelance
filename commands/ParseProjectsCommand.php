<?php
namespace Commands;

use App\Models\Skill;
use Core\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use App\Models\Project;
use App\Models\Employer;
use App\Services\FreelanceHuntService;

class ParseProjectsCommand extends Command
{

    public function configure()
    {
        $this->setName('projects:parse');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        function parsePage($link){
            $url = parse_url($link);

            $query = $url['query'];

            return explode('=', $query)[1];
        }

        $service = new FreelanceHuntService(Config::get('freelancehunt', CONFIG_PATH));

        $projectsModel = new Project();

        $result = $service->projects();

        while($result['links']['next'] ?? null){
            foreach($result['data'] as $project){

                if($projectsModel->exists('id', $project['id'])) continue;

                $format = "Save project id: %d, name: %s";
                $output->writeln(sprintf($format, $project['id'], $project['attributes']['name']));

                $data = [
                    'id' => $project['id'],
                    'name' => $project['attributes']['name'],
                    'link' => $project['links']['self']['web']
                ];

                if($project['attributes']['budget']['amount'])
                    $data['budget'] = $project['attributes']['budget']['amount'];

                if($project['attributes']['budget']['amount'])
                    $data['currency'] = $project['attributes']['budget']['currency'];

                $employer = $project['attributes']['employer'] ?? null;

                if($employer){
                    $format = "Save employer id: %d, login: %s";
                    $output->writeln(sprintf($format, $employer['id'], $employer['login']));

                    $this->saveEmployer($project['attributes']['employer']);

                    $data['employer_id'] = $employer['id'];
                }

                $projectsModel->insert($data);

                $skills = $project['attributes']['skills'] ?? null;

                if($skills){
                    foreach($skills as $skill){
                        $format = "Save skill id: %d, name: %s";
                        $output->writeln(sprintf($format, $skill['id'], $skill['name']));

                        $this->saveSkill($skill);

                        $projectsModel->addSkill([
                            'skill_id' => $skill['id'],
                            'project_id' => $project['id']
                        ]);
                    }
                }
            }

            $nextPage = parsePage($result['links']['next']);

            $result = $service->projects($nextPage);
        };
    }

    public function saveSkill($skill)
    {
        $skillModel = new Skill();

        if( ! $skillModel->exists('id', $skill['id'])){
            $skillModel->insert([
                'id' => $skill['id'],
                'name' => $skill['name']
            ]);
        }
    }
    public function saveEmployer($employer)
    {
        $employerModel = new Employer();

        if( ! $employerModel->exists('id', $employer['id'])){
            $employerModel->insert([
                'id' => $employer['id'],
                'name' => $employer['first_name'] . ' ' . $employer['last_name'],
                'login' => $employer['login']
            ]);
        }
    }

}