<?php

namespace App\Jobs;

use Carbon\Carbon;
use ZanySoft\Zip\Zip;
use setasign\Fpdi\Fpdi;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Spatie\FlareClient\Http\Exceptions\BadResponse;

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
        ini_set("gd.jpeg_ignore_warning", 1);
        $templatePDF = storage_path('app/badge-template.pdf');
        $folder = 'badges-' . Carbon::now()->format('Y-m-d H:i:s');
        if (is_dir(storage_path() . '/app/badges/')) {
            system('rm -rf '.storage_path() . '/app/badges/');
        }
        mkdir(storage_path() . '/app/badges/');
        mkdir(storage_path() . '/app/badges/' . $folder);
        foreach ($this->members as $member) {
            if ($member->image()->exists()) {
                $fpdi = new Fpdi;
                $fpdi->setSourceFile($templatePDF);

                $template = $fpdi->importPage(1);
                $fpdi->SetTextColor(0, 0, 0);
                $size = $fpdi->getTemplateSize($template);
                $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $fpdi->useTemplate($template);

                $this
                    ->addResponsiveFullname($fpdi, $member->fullname)
                    ->addRegistrationNumber($fpdi, $member->registration_number)
                    ->addUserImage($fpdi, $member->image->path)
                    ->addStudyYear($fpdi)
                    ->importSecondPage($fpdi);
                
                $fpdi->Output(storage_path('app/badges') ."/$folder/". $member->fullname.'.pdf', 'F');
            }
        }

        if (! is_dir(storage_path().'/app/public/badges/')) {
            mkdir(storage_path().'/app/public/badges/');
        }
        $zip = Zip::create(storage_path().'/app/public/badges/'.$folder.'.zip');
        $zip->add(storage_path().'/app/badges/'.$folder.'/');
        $zip->close();

        Cache::put('download-badges-path', 'badges/'.$folder.'.zip');
    }

    public function addResponsiveFullname(Fpdi $fpdi, string $fullname): BadgeGeneratorJob
    {
        if (strlen($fullname) >= 18) {
            $fpdi->SetFont('Times', null, 12);
            $left = 30;
            $top = 20;
            $fpdi->Text($left, $top, $fullname);
        } else {
            $fpdi->SetFont('Times', null, 14);
            $left = 35;
            $top = 20;
            $fpdi->Text($left, $top, $fullname);
        }
        return $this;
    }

    public function addRegistrationNumber(Fpdi $fpdi, string $registration_number): BadgeGeneratorJob
    {
        $fpdi->SetFont('Times', null, 10);
        $left = 37;
        $top = 33;
        $fpdi->Text($left, $top, $registration_number);
        return $this;
    }

    public function addUserImage(Fpdi $fpdi, string $imagepath): BadgeGeneratorJob
    {
        $extension = explode(".", $imagepath);
        $extension = end($extension);
        if (in_array($extension, ['jpeg', 'jpg', 'png'])) {
            $fpdi->Image(storage_path('app/public/' . $imagepath), 6.5, 16.5, 25.3, 28, strtoupper($extension));
        }

        return $this;
    }

    public function addStudyYear(Fpdi $fpdi): BadgeGeneratorJob
    {
        $studyYear = Carbon::now()->year . "/" . Carbon::now()->addYear()->year;
        $fpdi->SetFont('Times', null, 10);
        $left = 37;
        $top = 45;
        $fpdi->Text($left, $top, $studyYear);

        return $this;
    }

    public function importSecondPage(Fpdi $fpdi): void
    {
        $template = $fpdi->importPage(2);
        $size = $fpdi->getTemplateSize($template);
        $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $fpdi->useTemplate($template);
    }
}
