<?php

namespace App\Imports;

use App\Training;
use Carbon\Carbon;
use App\Models\User;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Picqer\Barcode\BarcodeGeneratorJPG;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Maatwebsite\Excel\Concerns\ToCollection;

class UsersImport implements ToCollection
{

    public function __construct(public Training $training)
    {
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        $invitationTemplate = storage_path('app/A4 - 1INVITATION.pdf');
        $fpdi       = new Fpdi;
        $count      = $fpdi->setSourceFile($invitationTemplate);
        $fpdi->SetFont("Times", "IUB", 24);

        $fpdi->SetTextColor(68, 69, 68);
        foreach ($rows as $key => $row) {
            if ($key > 0) {
                for ($i = 1; $i <= $count; $i++) {
                    $template   = $fpdi->importPage($i);
                    $size       = $fpdi->getTemplateSize($template);
                    $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
                    $fpdi->useTemplate($template);
                    $this
                        ->writeInscriptionDate($fpdi, $row[10])
                        ->writeEventName($fpdi, $this->training->title)
                        ->writeEventDate($fpdi, $this->training->starting_at)
                        ->writeEventLocation($fpdi, $this->training->location)
                        ->writeFullname($fpdi, $row[2])
                        ->writeEmail($fpdi, $row[3])
                        ->writePhone($fpdi, $row[4])
                        ->addPaperBarCode($fpdi)
                        ->addCheckInBarCode($fpdi)
                        ->addCheckOutBarCode($fpdi);
                }
                return $fpdi->Output(storage_path('app/out.pdf'), 'F');
            }
        }
    }

    public function writeInscriptionDate(Fpdi $fpdi, string $text): UsersImport
    {
        $left   =   125;
        $top    =   32;
        $fpdi->Text($left, $top, Carbon::parse($text)->format("Y-m-d"));
        return $this;
    }

    public function writeEventName(Fpdi $fpdi, string $text): UsersImport
    {
        $left   =   62;
        $top    =   90;
        $fpdi->Text($left, $top, $text);
        return $this;
    }


    public function writeEventDate(Fpdi $fpdi, string $text): UsersImport
    {
        $left   =   62;
        $top    =   108;
        $fpdi->Text($left, $top, Carbon::parse($text)->format('Y-m-d'));
        return $this;
    }

    public function writeEventLocation(Fpdi $fpdi, string $text): UsersImport
    {
        $left   =   70;
        $top    =   127;
        $fpdi->Text($left, $top, $text);
        return $this;
    }

    public function writeFullname(Fpdi $fpdi, string $text): UsersImport
    {
        $left   =   75;
        $top    =   177;
        $fpdi->Text($left, $top, $text);
        return $this;
    }

    public function writeEmail(Fpdi $fpdi, string $text): UsersImport
    {
        $left   =   52;
        $top    =   195;
        $fpdi->Text($left, $top, $text);
        return $this;
    }

    public function writePhone(Fpdi $fpdi, string $text): UsersImport
    {
        $left   =   92;
        $top    =   210;
        $fpdi->Text($left, $top, $text);
        return $this;
    }

    public function addPaperBarCode(Fpdi $fpdi): UsersImport
    {
        $generator = new BarcodeGeneratorJPG();
        $uuid = Str::random(10);
        $barcode = $generator->getBarcode($uuid, $generator::TYPE_CODE_128, 3, 50, [0, 0, 0]);
        $barcodelink = Storage::put('papersBarCodes/'.$uuid.'.jpg', $barcode);
        $fpdi->Image(storage_path('app/papersBarCodes/'.$uuid.'.jpg'), 10, 5, 50, 20, 'JPG');
        return $this;
    }

    public function addCheckInBarCode(Fpdi $fpdi): UsersImport
    {
        $generator = new BarcodeGeneratorJPG();
        $uuid = Str::random(10);
        $barcode = $generator->getBarcode($uuid, $generator::TYPE_CODE_128, 3, 50, [0, 0, 0]);
        $barcodelink = Storage::put('checkInsBarCodes/'.$uuid.'.jpg', $barcode);
        $fpdi->Image(storage_path('app/checkInsBarCodes/'.$uuid.'.jpg'), 22, 260, 50, 20, 'JPG');
        return $this;
    }

    public function addCheckOutBarCode(Fpdi $fpdi): UsersImport
    {
        $generator = new BarcodeGeneratorJPG();
        $uuid = Str::random(10);
        $barcode = $generator->getBarcode($uuid, $generator::TYPE_CODE_128, 3, 50, [0, 0, 0]);
        $barcodelink = Storage::put('checkOutsBarCodes/'.$uuid.'.jpg', $barcode);
        $fpdi->Image(storage_path('app/checkOutsBarCodes/'.$uuid.'.jpg'), 140, 260, 50, 20, 'JPG');
        return $this;
    }
}
