<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Check;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CheckExport implements FromCollection, WithHeadings, ShouldAutoSize
{

    public function __construct(public int $training_id)
    {
    }

    public function headings(): array
    {
        return ["ID #", "Fullname", "Email", "Logs", "Total time", "Number of checks"];
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Check::where('training_id', $this->training_id)->get()->groupBy('member_id')->map(function ($memberChecks) {
            $checksText = "";
            $firstCheckin = null;
            $lastCheckout = null;
            foreach($memberChecks as $key => $check) {

                if ($key == 0 AND $firstCheckin === null) {
                    $firstCheckin = $check->checkedIn_at;
                }

                if ($key+1 === $memberChecks->count() AND $lastCheckout === null) {
                    $lastCheckout = $check->checkedOut_at;
                }

                $checksText .= "Check in ".($key+1)." at :".$check->checkedIn_at." ";
                $checksText .= "/ Check out ".($key+1)." at :".$check->checkedOut_at;
                if ($check->checkedOut_at !== null) {
                    $stayed =  Carbon::parse($check->checkedOut_at)->diffForHumans($check->checkedIn_at);
                    $checksText .= " , stayed ($stayed checked in) ";
                }else {
                    $checksText .= " , stayed (Could not calculate duration ,no checkout found) ";
                }
                if ($key+1 < $memberChecks->count()) {
                    $checksText .= "\n";
                }
            }
            if ($firstCheckin !== null AND $lastCheckout !== null) {
                $totalTime = Carbon::parse($check->checkedOut_at)->longAbsoluteDiffForHumans($check->checkedIn_at);
            }else {
                $totalTime = "Could not calculate total time (no checkout found)";
            }
            return [
                $memberChecks->first()->id,
                $memberChecks->first()->member->fullname,
                $memberChecks->first()->member->email,
                $checksText,
                $totalTime,
                $memberChecks->count()
            ];
        });
    }

    // public function map($checksOfMember): array
    // {
    //     $data = [];
    //     dd($checksOfMember);
    //     foreach($checksOfMember as $check) {

    //         array_push($data,  [
    //             $check->id,
    //             $check->member->fullname,
    //             $check->member->email,

    //         ]);
    //     }
    //     return $data;   
    // }

}
