<?php

namespace App\Traits;

use App\Services\ExportService;

trait Export {
    private function renderFormat($data)
    {
        $newFormat = NULL;
        switch ($this->format) {
            case 'xml':
                $newFormat = ExportService::toXml($data);
                break;
            case 'json':
                $newFormat = ExportService::toJson($data);
                break;
            case 'csv':
                $newFormat = ExportService::toCsv($data);
                break;
            default:
                if (!$data->count()) {
                    view('export', [
                        'message' => 'Sorry, no matching data was found',
                    ]);
                }
                view('export', [
                    'export' => $data,
                ]);
        }

        return $newFormat;
    }
}