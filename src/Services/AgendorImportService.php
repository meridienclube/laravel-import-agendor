<?php

namespace ConfrariaWeb\ImportAgendor\Services;

use ConfrariaWeb\ImportAgendor\Imports\AgendorImport;
use ConfrariaWeb\Import\Traits\ImportService;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportAgendorService
{
    use ImportService;

    public function execute()
    {
        try {
            if (!Storage::disk('public')->exists('imports/' . $this->import->file)) {
                $this->setReturn('status', false);
                $this->setReturn('message', 'A integração não foi executada pois o arquivo não foi encontrado.');
                return $this;
            }
            $importExcel = new AgendorImport($this->import);
            Excel::import($importExcel, storage_path('app/public/imports/' . $this->import->file));
            //$importExcel->getRowCount()
            $this->setReturn('message', 'A integração foi processada com sucesso.');
            return $this;
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }
}
