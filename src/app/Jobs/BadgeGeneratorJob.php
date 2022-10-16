<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use setasign\Fpdi\Fpdi;

class BadgeGeneratorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public Collection $members)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $templatePDF = storage_path('app/badge-template.pdf');
        // $folder = 'badges-' . Carbon::now()->format('Y-m-d H:i:s');
        // if (!is_dir(storage_path() . '/app/badges/')) {
        //     mkdir(storage_path() . '/app/badges/');
        // }
        // mkdir(storage_path() . '/app/badges/' . $folder);
        foreach ($this->members as $member) {
            if ($member->image()->exists()) {
                $fpdi = new Fpdi;
                $fpdi->setSourceFile($templatePDF);

                $template = $fpdi->importPage(1);
                $fpdi->SetTextColor(0, 0, 0);
                $size = $fpdi->getTemplateSize($template);
                $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $fpdi->useTemplate($template);
                if (strlen($member->fullname) >= 18) {
                    $fpdi->SetFont('Times', null, 12);
                    $left = 30;
                    $top = 20;
                    $fpdi->Text($left, $top, $member->fullname);
                } else {
                    $fpdi->SetFont('Times', null, 14);
                    $left = 35;
                    $top = 20;
                    $fpdi->Text($left, $top, $member->fullname);
                }
                $fpdi->SetFont('Times', null, 10);
                $left = 37;
                $top = 33;
                $fpdi->Text($left, $top, $member->registration_number);

                $imagepath = $member->image->path;
                $extension = explode(".", $imagepath);
                $extension = end($extension);
                if (in_array($extension, ['jpeg', 'jpg', 'png'])) {
                    $fpdi->Image(storage_path('app/public/'.$member->image->path), 6.5, 16.5, 25.3, 28, 'JPG');
                }
                

                $template = $fpdi->importPage(2);
                $size = $fpdi->getTemplateSize($template);
                $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $fpdi->useTemplate($template);
                $fpdi->Output(storage_path('app/badges') . $member->fullname . 'out.pdf', 'F');
            }
        }
    }
}
