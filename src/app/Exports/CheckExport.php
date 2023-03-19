<?php

namespace App\Exports;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CheckExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(public Model $model)
    {
    }

    public function headings(): array
    {
        return ['ID #', 'Fullname', 'Email', 'Logs', 'Total time', 'Number of checks'];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $class = get_class($this->model);

        return $this->model->checks()->get()->groupBy('member_id')->map(function ($memberChecks) use ($class) {
            if ($class === 'App\\Models\\Event') {
                $member = $memberChecks->first()->eventMember;
            } elseif ($class === 'App\\Training') {
                $member = $memberChecks->first()->trainingMember;
            } else {
                return [];
            }
            if ($member === null) {
                dump($memberChecks);

                return [];
            }
            $checksText = '';
            $firstCheckin = null;
            $lastCheckout = null;
            if ($firstCheckin === null) {
                $firstCheckin = $memberChecks->first()->checkedIn_at;
            }

            if ($lastCheckout === null) {
                $lastCheckout = $memberChecks->last()->checkedOut_at;
            }

            foreach ($memberChecks as $key => $check) {
                $checksText .= 'Check in '.($key + 1).' at :'.$check->checkedIn_at.' ';
                $checksText .= '/ Check out '.($key + 1).' at :'.$check->checkedOut_at;
                if ($check->checkedOut_at !== null) {
                    $stayed = Carbon::parse($check->checkedOut_at)->diffForHumans($check->checkedIn_at);
                    $checksText .= " , stayed ($stayed checked in) ";
                } else {
                    $checksText .= ' , stayed (Could not calculate duration ,no checkout found) ';
                }
                if ($key + 1 < $memberChecks->count()) {
                    $checksText .= "\n";
                }
            }
            if ($firstCheckin !== null and $lastCheckout !== null) {
                $totalTime = $lastCheckout->diffForHumans($firstCheckin, ['parts' => 3, 'syntax' => CarbonInterface::DIFF_ABSOLUTE]);
            } else {
                $totalTime = 'Could not calculate total time (no checkout found)';
            }

            return [
                $member->id,
                $member->fullname(),
                $member->email,
                $checksText,
                $totalTime,
                $memberChecks->count(),
            ];
        });
    }
}
