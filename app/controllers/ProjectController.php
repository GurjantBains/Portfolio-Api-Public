<?php
namespace controllers;
require_once "BaseController.php";

namespace controllers;
use Database\Database as DB;

class ProjectController extends BaseController
{
    public function project($id){
        $result = $this->DB->query("SELECT * FROM projects Where id= ?",[$id]);
        if($result){
        echo json_encode($result);
        }
        else{
            echo "Project not found";
        }

    }

    public function projects():void
    {
        $result = $this->DB->query("SELECT id,name,description,mainImage  FROM projects where visibility = 1");
        echo json_encode($result);
        exit();
    }
    public function filteredProjects($filter):void
    {
        $result = $this->DB->query("
    SELECT p.id,p.name,p.description,p.mainImage
    FROM projects as p
    JOIN ProjectTags as t ON p.id = t.projectId
    WHERE t.tagId = ?
    AND p.visibility = 1
  ",[$filter]);
        echo json_encode($result);
    }

}