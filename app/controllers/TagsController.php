<?php

namespace controllers;
use Database\Database as DB;

class TagsController extends BaseController
{
    public function get(): void
    {
        $result = $this->DB->query("SELECT *  FROM Tags");
        echo json_encode(['success'=>true,'tags'=>$result]);
    }


}