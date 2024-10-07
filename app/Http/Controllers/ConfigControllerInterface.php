<?php
namespace App\Http\Controllers;

interface ConfigControllerInterface{
    const CONTROLLER_NAME = 'ConfigController';

    const LIST_CONFIG = 'listConfig';
    const CREATE_CONFIG = 'createConfig';
    const INSERT_CONFIG = 'insertConfig';
    const UPDATE_CONFIG = 'updateConfig';
    const DELETE_CONFIG = 'deleteConfig';

    public function listConfig();

    public function createConfig();

    public function insertConfig();

    public function updateConfig();

    public function deleteConfig();
}
