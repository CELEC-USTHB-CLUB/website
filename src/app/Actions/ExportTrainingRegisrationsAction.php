<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

class ExportTrainingRegisrationsAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Export registrattions';
    }

    public function getIcon()
    {
        return 'voyager-download';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-primary pull-right',
        ];
    }

    public function getDefaultRoute()
    {
        return url('admin/trainings/exportRegistrations/'.$this->data->id);
    }

    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug === 'trainings';
    }
}
