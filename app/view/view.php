<?php

namespace view;

class view
{
    public static function view($file)
    {
        header('content-type: text/html; charset=utf-8');
//        $file = realpath(__DIR__ . "../view/{$file}");
        $file = realpath(__DIR__ . "/../../resources/views/{$file}.view.php");

        if (file_exists($file)){

            echo file_get_contents($file);
            return;
        }

    }
}