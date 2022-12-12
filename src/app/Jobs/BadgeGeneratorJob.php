<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use setasign\Fpdi\Fpdi;
use Throwable;
use ZanySoft\Zip\Zip;

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
        $folder = 'badges-'.Carbon::now()->format('Y-m-d H:i:s');
        if (is_dir(storage_path().'/app/badges/')) {
            system('rm -rf '.storage_path().'/app/badges/');
        }
        mkdir(storage_path().'/app/badges/');
        mkdir(storage_path().'/app/badges/'.$folder);

        foreach ($this->members as $member) {
            $imageCorrupted = false;
            try {
                getimagesize(storage_path('app/public/'.$member->image->path));
            } catch (Throwable $e) {
                $imageCorrupted = true;
            }
            if (
                $member->image()->exists()
                and
                ! $imageCorrupted
            ) {
                $extension = explode('.', storage_path('app/public/'.$member->image->path));
                $extension = end($extension);

                if (strtolower($extension) === 'png') {
                    if ($this->checkIfPngImageDepthIsGraterThan8bits(storage_path('app/public/'.$member->image->path))) {
                        $this->convert16bitsImageDepthTo8bits(storage_path('app/public/'.$member->image->path));
                    }
                }

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

                $fpdi->Output(storage_path('app/badges')."/$folder/".$member->fullname.'.pdf', 'F');
            }
        }

        if (! is_dir(storage_path().'/app/public/badges/')) {
            mkdir(storage_path().'/app/public/badges/');
        }
        $zip = new Zip;
        $zip = $zip->create(storage_path().'/app/public/badges/'.$folder.'.zip');
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
        $extension = explode('.', $imagepath);
        $extension = end($extension);
        if (in_array($extension, ['jpeg', 'jpg', 'png'])) {
            $fpdi->Image(storage_path('app/public/'.$imagepath), 6.5, 16.5, 25.3, 28, strtoupper($extension));
        }

        return $this;
    }

    public function addStudyYear(Fpdi $fpdi): BadgeGeneratorJob
    {
        $studyYear = Carbon::now()->year.'/'.Carbon::now()->addYear()->year;
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

    public function checkIfPngImageDepthIsGraterThan8bits(string $path): bool
    {
        $info = unpack('A8sig/Nchunksize/A4chunktype/Nwidth/Nheight/Cbit-depth/Ccolor/Ccompression/Cfilter/Cinterface', file_get_contents($path, 0, null, 0, 29));

        return $info['bit-depth'] > 8;
    }

    public function convert16bitsImageDepthTo8bits(string $path): void
    {
        $image = new \Imagick($path);
        $image->setImageDepth(8);
        $image->writeImage($path);
    }
}
