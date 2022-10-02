<?php
/**
 * Created by PhpStorm.
 * User: Alexandr Statut
 * Date: 23.09.2019
 * Time: 12:40
 */


namespace admin_panel\controllers;

use system\core\Controller;
use modules\storage\Storage;


class Visitlog extends Controller {

    public function __construct()
    {
        parent::__construct();

        if (!$this->isAdminAuth() && !$this->isRootAuth()) {
            $this->url::redirect(SITE . 'login');
        }
    }

    public function index()
    {
        $files
            = $logs
            = [];

        for ($i = 0; $i < 7; $i++) {
            $files[] = date('Y-m-d', time() - (3600 * 24 * $i));
        }

        foreach ($files as $file) {
            $logs[$file] = $this->getDayLog($file);
        }

        foreach ($files as $k) {

            $_fname = explode(DIRECTORY_SEPARATOR, $k);
            $fname  = substr(end($_fname), 0, strpos(end($_fname), '.'));

            $logs[$fname] = $this->getDayLog($k);
        }

        $this->buffer->logs = $logs;

        $this->loadTemplate();
    }

    private function getDayLog($log_file)
    {
        $log = [];

        if (Storage::disk('visits')->exists($log_file)) {
            $log = Storage::disk('visits')->get($log_file)->fromJson();
        }

        return $log;
    }
}