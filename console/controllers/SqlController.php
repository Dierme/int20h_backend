<?php

namespace console\controllers;
use Yii;
use yii\console\Controller;

class SqlController extends Controller
{
    public $message;

    public function options($actionID)
    {
    return ['message'];
    }

    public function optionAliases()
    {
    return ['m' => 'message'];
    }

    public function actionIndex()
    {
//        $query = "DROP DATABASE int20h";
//        $query = "create database int20h";
//        $query = "alter table user
//          add column api_token varchar(255)";

        $connection = \Yii::$app->getDb();
        $command = $connection->createCommand($query);
        $result = $command->queryAll();
        //echo $this->message . "\n";
        return true;
    }
}