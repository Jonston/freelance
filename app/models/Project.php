<?php

namespace App\Models;

class Project extends AbstractModel
{
    protected $table = 'projects';

    /**
     * @param $data
     * @return mixed
     */
    public function addSkill($data)
    {
        $sql = $this->builder->insert()
            ->setTable('projects_skills')
            ->setValues($data);

        $this->execute($sql);

        return $this->db->lastInsertId();
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @param array $skills
     * @return mixed
     */
    public function getProjects(int $limit = null, int $offset = null, array $skills = null)
    {
        if($skills){
            $skills = implode(',', $skills);
            $skills = " WHERE s.id IN ({$skills}) ";
        }else{
            $skills = null;
        }

        $sql = "SELECT 
          p.id, p.name AS p_name, p.link, p.budget, p.currency, e.name AS e_name, e.login
          FROM projects p
          LEFT JOIN projects_skills ps ON p.id = ps.project_id
          LEFT JOIN skills s ON ps.skill_id = s.id
          LEFT JOIN employers e ON p.employer_id = e.id
          {$skills}
          GROUP BY p.id ORDER BY p.id DESC";

        if($limit){
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->db->prepare($sql);

        if($limit){
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset ? $offset : 0, \PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param array|null $skills
     * @return mixed
     */
    public function getTotalProjectsInSkills(array $skills)
    {
        $masks = [];

        foreach($skills as $key => $skill){
            $masks[':s' . $key] = $skill;
        }

        $in = implode(',', array_keys($masks));

        $sql = "SELECT COUNT(*) AS total 
          FROM projects p
          WHERE p.id IN (SELECT ps.project_id FROM projects_skills ps WHERE ps.skill_id IN ({$in}))";

        $stmt = $this->db->prepare($sql);

        foreach($masks as $key => $mask){
            $stmt->bindValue($key, $mask);
        }

        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_COLUMN);
    }

    /**
     * @param float $rate
     * @return mixed
     */
    public function getProjectsGroups(float $rate)
    {
        //Я понимаю, что есть лучшее решение, но ничего изящнее не придумал.
        $budget = "IF (currency = 'RUB', budget / {$rate}, budget)";

        $sql = "SELECT *,
            SUM({$budget} > 500) as g1,
            SUM({$budget} >= 500 AND {$budget} < 1000) as g2,
            SUM({$budget} >= 1000 AND {$budget} < 5000) as g3,
            SUM({$budget} >= 5000) as g4
          FROM projects WHERE budget IS NOT NULL";

        $stmt = $this->db->prepare($sql);

        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }


}