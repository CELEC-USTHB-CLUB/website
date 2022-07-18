<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Tu6ge\VoyagerExcel\Exports\AbstractExport;

class MemberExport extends AbstractExport implements FromCollection
{
    protected $dataType;

    protected $model;

    protected $ids;

    public function __construct($dataType, array $ids)
    {
        $this->dataType = $dataType;
        $this->model = new $dataType->model_name();
        $this->ids = array_filter($ids);
    }

    public function collection()
    {
        $fields = $this->dataType->browseRows->map(function ($res) {
            return $res['field'];
        });
        $fields->push('cv');

        $table = $this->dataType->browseRows->map(function ($res) {
            return $res['display_name'];
        });
        $table->push('Cv');
        $rs = $this->model->when(
            count($this->ids) > 0,
            function ($query) {
                $query->whereIn($this->model->getKeyName(), $this->ids);
            }
        )->get();
        $rs = $rs->map(function ($res) use ($fields) {
            $arr = [];
            foreach ($fields as $val) {
                if ($val === 'cv') {
                    $arr[$val] = url('storage').'/'.$res->cv->path;
                } else {
                    $arr[$val] = $res[$val];
                }
            }

            return $arr;
        });
        $table = collect([$table->toArray()])->merge($rs);

        return $table;
    }
}
