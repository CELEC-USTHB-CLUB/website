<?php

namespace App\Imports;

use App\Training;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;
use setasign\Fpdi\Fpdi;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use ZanySoft\Zip\Zip;

class TrainingUsersImport implements ToCollection
{
    public function __construct(public Training $training, public string $certificationTemplatePath)
    {
    }

    /**
     * @param  Collection  $collection
     */
    public function collection(Collection $rows)
    {
        $folder = $this->training->title.'-certifications-'.Carbon::now()->format('Y-m-d H:i:s');
        if (! is_dir(storage_path().'/app/certifications-papers/')) {
            mkdir(storage_path().'/app/certifications-papers/');
        }
        mkdir(storage_path().'/app/certifications-papers/'.$folder);

        foreach ($rows as $key => $row) {
            if ($row[0] !== null and $row[0] !== '') {
                $fpdi = new Fpdi;
                $count = $fpdi->setSourceFile($this->certificationTemplatePath);
                for ($i = 1; $i <= $count; $i++) {
                    $fpdi->SetTextColor(34, 59, 129);
                    $template = $fpdi->importPage($i);
                    $size = $fpdi->getTemplateSize($template);
                    $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $fpdi->useTemplate($template);
                    $certificationSignature = $this->generateSignature($row[0]);
                    $this
                        ->writeFullname($fpdi, $row[0])
                        ->writeSignature($fpdi, $certificationSignature);
                }
                $filepath = 'certifications-papers/'.$folder.'/'.$row[0].'-'.$key.'-'.Carbon::now()->format('Y-m-d H:i:s').'.pdf';
                $fpdi->Output(storage_path('app/'.$filepath), 'F');
            }
        }
        if (! is_dir(storage_path().'/app/public/archive-certifications-papers/')) {
            mkdir(storage_path().'/app/public/archive-certifications-papers/');
        }
        $zip = new Zip;
        $zip->create(storage_path().'/app/public/archive-certifications-papers/'.$folder.'.zip');
        $zip->add(storage_path().'/app/certifications-papers/'.$folder.'/');
        $zip->close();
        $this->training->certificationZip()->create(['path' => 'archive-certifications-papers/'.$folder.'.zip']);
    }

    public function generateSignature(string $fullname): string
    {
        $signature = 'CELEC-'.uniqid();
        $this->training->certifications()->create([
            'fullname' => $fullname,
            'signature' => $signature,
        ]);

        return $signature;
    }

    public function writeFullname(Fpdi $fpdi, string $text): TrainingUsersImport
    {
        $top = 105;
        $fontSize = 250 / (mb_strlen($text) * 0.2645833333);

        $left = 47;
        $fpdi->SetFont('Courier', '', $fontSize);

        $fpdi->Text($left, $top, $text);

        return $this;
    }

    public function writeSignature(Fpdi $fpdi, string $uuid): TrainingUsersImport
    {
        $text = url('/certification?token='.$uuid);
        $barcode = QrCode::format('png')->size(100)->backgroundColor(247, 248, 251)->color(13, 61, 142)->generate($text);
        Storage::put('certificationsSignatures/'.$uuid.'.png', $barcode);
        $fpdi->Image(storage_path('app/certificationsSignatures/'.$uuid.'.png'), 10, 10, 25, 25, 'PNG');

        return $this;
    }
}
