<?php
namespace Commands;

use App\Models\ParserLog;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use App\Models\Employer;
use App\Services\FreelanceHuntService;

class ParseEmployersCommand extends Command
{
    public function configure()
    {
        $this->setName('employers:parse');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $service = new FreelanceHuntService();

        $employerModel = new Employer();

        $logModel = new ParserLog();

        $firstPage = $logModel->getFirstPage(FreelanceHuntService::TYPE_EMPLOYER);
        $lastPage = $logModel->getLastPage(FreelanceHuntService::TYPE_EMPLOYER);

        if($firstPage && $lastPage){
            $total = $logModel->countRequests(
                FreelanceHuntService::TYPE_EMPLOYER,
                $firstPage['first'],
                $lastPage['last']
            );

            $from = new \DateTime($firstPage['first']);
            $to = new \DateTime($firstPage['to']);

            $diff = $to->diff($from);

            $format = "Total requests: %d in %d hours %d minutes";
            $output->writeln(sprintf($format, $total['total'], $diff->format('%h'), $diff->format('%i')));

            if($total > $diff->format('%i') / 60 * 1000){
                $format = "Limit exhausted: %d request in %d hours %d minutes";
                $output->writeln(sprintf($format, $total['total'], $diff->format('%h'), $diff->format('%i')));
                exit;
            }
        }

        $page = $lastPage['page'] ? ($lastPage['page'] + 1) : 1;

        $result = $service->employers($page);

        while(isset($result['links']['next'])){

            if($this->checkLimit(1000)){
                $format = "Limit exhausted: %d in %d hours %d minutes";
                $output->writeln(sprintf($format, $total['total'], $diff->format('%h'), $diff->format('%i')));
            }

            $format = "Current Page: %d";
            $output->writeln(sprintf($format, $page));

            foreach($result['data'] as $employer){
                if($employerModel->exists('id', $employer['id'])) continue;

                $employerModel->insert([
                    'id' => $employer['id'],
                    'name' => $employer['attributes']['first_name'] . ' ' . $employer['attributes']['last_name'],
                    'login' => $employer['attributes']['login']
                ]);
                $format = "Save employer id: %d, login: %s";
                $output->writeln(sprintf($format, $employer['id'], $employer['attributes']['login']));
            }

            $this->writeLog([
                'type' => FreelanceHuntService::TYPE_EMPLOYER,
                'page' => $page
            ]);

            $page = $this->parsePage($result['links']['next']);

            $result = $service->employers($page);
        };
    }

    private function checkLimit($limit)
    {
        $logModel = new ParserLog();

        $firstPage = $logModel->getFirstPage(FreelanceHuntService::TYPE_EMPLOYER);
        $lastPage = $logModel->getLastPage(FreelanceHuntService::TYPE_EMPLOYER);

        if($firstPage && $lastPage){
            $total = $logModel->countRequests(
                FreelanceHuntService::TYPE_EMPLOYER,
                $firstPage['first'],
                $lastPage['last']
            );

            $from = new \DateTime($firstPage['first']);
            $to = new \DateTime($firstPage['to']);

            $diff = $to->diff($from);

            return $total < $diff->format('%i') / 60 * $limit;
        }

        return true;
    }

    private function writeLog($data)
    {
        $logModel = new ParserLog();

        $logModel->insert([
            'type' => $data['type'],
            'page' => $data['page'],
            'created_at' => (new \DateTime())->format('Y-m-d H:i:s')
        ]);
    }

    private function parsePage($link){
        $url = parse_url($link);

        $query = $url['query'];

        return (int) explode('=', $query)[1];
    }
}