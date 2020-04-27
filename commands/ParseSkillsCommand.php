<?php
namespace Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use App\Models\Skill;
use App\Services\FreelanceHuntService;

class ParseSkillsCommand extends Command
{
    public function configure()
    {
        $this->setName('skills:parse');
    }

    public function execute(InputInterface $input, OutputInterface $output)
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

                $format = "Save skill id: %d, name: %s";
                $output->writeln(sprintf($format, $skill['id'], $skill['name']));
            }

            $result = $service->employers(parsePage($result['links']['next']));
        };
    }
}