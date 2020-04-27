<?php

namespace App\Services;

use App\Models\ParserLog;
use App\Models\Skill;
use App\Models\Employer;
use App\Models\Project;
use Core\Config;
use GuzzleHttp\Client;

class FreelanceHuntService
{
    const TYPE_EMPLOYER = 1;
    const TYPE_SKILL = 2;
    const TYPE_PROJECT = 3;

    private $client;

    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;

        $this->client = new Client([
            'headers' => [
                'Authorization' => 'Bearer ' . $this->config['token']
            ]
        ]);
    }

    public function projects(int $page = 1)
    {
        $response = $this->client->request('GET', $this->config['url'] . 'projects', [
            'query' => [
                'page[number]' => $page
            ]
        ]);

        $body = json_decode($response->getBody()->getContents(), true);

        return $body;
    }

    public function projectDetail(int $project_id)
    {
        $response = $this->client->request('GET', $this->config['url'] . 'projects/' . $project_id);

        $body = json_decode($response->getBody()->getContents(), true);

        return $body;
    }

    public function skills(int $page = 1)
    {
        $response = $this->client->request('GET', $this->config['url'] . 'skills', [
            'query' => [
                'page[number]' => $page
            ]
        ]);

        $body = json_decode($response->getBody()->getContents(), true);

        return $body;
    }

    public function employers(int $page = 1)
    {
        $response = $this->client->request('GET', $this->config['url'] . 'employers', [
            'query' => [
                'page[number]' => $page
            ]
        ]);

        $body = json_decode($response->getBody()->getContents(), true);

        return $body;
    }

    public function employerDetail(int $employer_id)
    {
        $response = $this->client->request('GET', $this->config['url'] . 'employers/' . $employer_id);

        $body = json_decode($response->getBody()->getContents(), true);

        return $body;
    }
}
