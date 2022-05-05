<?php

namespace App\Services;

use LSS\Array2XML;

class ExportService
{
    public static function toXml($data)
    {
        header('Content-type: text/xml');
        $xmlData = [];

        foreach ($data->all() as $row) {
            $xmlRow = [];
            foreach ($row as $key => $value) {
                $key = preg_replace_callback('(\d)', function ($matches) {
                    return $this->numberText[$matches[0]] . '_';
                }, $key);
                $xmlRow[$key] = $value;
            }
            $xmlData[] = $xmlRow;
        }

        $xml = Array2XML::createXML('data', [
            'entry' => $xmlData
        ]);

        return $xml->saveXML();
    }

    public static function toJson($data)
    {
        header('Content-type: application/json');
        return json_encode($data->all());
    }

    public static function toCsv($data)
    {
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="export.csv";');
        if (!$data->count()) return [];

        $csv = [];

        $headings = collect($data->get(0))->keys();
        $headings = $headings->map(function ($item, $key) {
            return collect(explode('_', $item))
                ->map(function ($item, $key) {
                    return ucfirst($item);
                })
                ->join(' ');
        });
        $csv[] = $headings->join(',');

        foreach ($data as $row) {
            $csv[] = implode(',', (array)$row);
        }
        return implode("\n", $csv);
    }
}