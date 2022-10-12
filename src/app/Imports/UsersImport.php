<?php

namespace App\Imports;

use App\Models\Invitation;
use App\Models\Signature;
use App\Training;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Picqer\Barcode\BarcodeGeneratorJPG;
use setasign\Fpdi\Fpdi;
use ZanySoft\Zip\Zip;

class UsersImport implements ToCollection
{
    public function __construct(public Training $training)
    {
    }

    /**
     * @param  array  $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        $invitationTemplate = storage_path('app/A4 - 1INVITATION.pdf');

        $folder = $this->training->title.'-invitations-'.Carbon::now()->format('Y-m-d H:i:s');
        mkdir(storage_path().'/app/invitations-papers/'.$folder);
        foreach ($rows as $key => $row) {
            if ($key > 0) {
                $fpdi = new Fpdi;
                $count = $fpdi->setSourceFile($invitationTemplate);
                $fpdi->SetFont('Times', 'IUB', 24);
                for ($i = 1; $i <= $count; $i++) {
                    $fpdi->SetTextColor(68, 69, 68);
                    $template = $fpdi->importPage($i);
                    $size = $fpdi->getTemplateSize($template);
                    $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $fpdi->useTemplate($template);
                    $paperCode = $this->generateID();
                    $checkincode = $this->generateID();
                    $checkoutcode = $this->generateID();
                    $this
                        ->writeInscriptionDate($fpdi, $row[10])
                        ->writeEventName($fpdi, $this->training->title)
                        ->writeEventDate($fpdi, $this->training->starting_at)
                        ->writeEventLocation($fpdi, $this->training->location)
                        ->writeFullname($fpdi, $row[2])
                        ->writeEmail($fpdi, $row[3])
                        ->writePhone($fpdi, $row[4])
                        ->addPaperBarCode($fpdi, $paperCode)
                        ->addCheckInBarCode($fpdi, $checkincode)
                        ->addCheckOutBarCode($fpdi, $checkoutcode);
                }
                $filepath = 'invitations-papers/'.$folder.'/'.$row[2].'-'.$this->training->title.'-'.Carbon::now()->format('Y-m-d H:i:s').'.pdf';
                $invitation = Invitation::create(['training_id' => $this->training->id, 'path' => $filepath, 'user_id' => $row[0]]);

                Signature::create([
                    'user_id' => $row[0],
                    'invitation_id' => $invitation->id,
                    'paper_code' => $paperCode,
                    'checkin_code' => $checkincode,
                    'checkout_code' => $checkoutcode,
                ]);

                $fpdi->Output(storage_path('app/'.$filepath), 'F');
            }
        }
        $zip = Zip::create(storage_path().'/app/public/archive-invitations-papers/'.$folder.'.zip');
        $zip->add(storage_path().'/app/invitations-papers/'.$folder.'/');
        $zip->close();
        $this->training->archive()->create(['path' => 'archive-invitations-papers/'.$folder.'.zip']);
    }

    public function writeInscriptionDate(Fpdi $fpdi, string $text): UsersImport
    {
        $left = 125;
        $top = 32;
        $fpdi->Text($left, $top, Carbon::parse($text)->format('Y-m-d'));

        return $this;
    }

    public function writeEventName(Fpdi $fpdi, string $text): UsersImport
    {
        $left = 62;
        $top = 90;
        $fpdi->Text($left, $top, $text);

        return $this;
    }

    public function writeEventDate(Fpdi $fpdi, string $text): UsersImport
    {
        $left = 62;
        $top = 108;
        $fpdi->Text($left, $top, Carbon::parse($text)->format('Y-m-d'));

        return $this;
    }

    public function writeEventLocation(Fpdi $fpdi, string $text): UsersImport
    {
        $left = 70;
        $top = 127;
        $fpdi->Text($left, $top, $text);

        return $this;
    }

    public function writeFullname(Fpdi $fpdi, string $text): UsersImport
    {
        $left = 75;
        $top = 177;
        $fpdi->Text($left, $top, $text);

        return $this;
    }

    public function writeEmail(Fpdi $fpdi, string $text): UsersImport
    {
        $left = 52;
        $top = 195;
        $fpdi->Text($left, $top, $text);

        return $this;
    }

    public function writePhone(Fpdi $fpdi, string $text): UsersImport
    {
        $left = 92;
        $top = 210;
        $fpdi->Text($left, $top, $text);

        return $this;
    }

    public function addPaperBarCode(Fpdi $fpdi, string $uuid): UsersImport
    {
        $generator = new BarcodeGeneratorJPG();
        $barcode = $generator->getBarcode($uuid, $generator::TYPE_CODE_128, 3, 50, [0, 0, 0]);
        Storage::put('papersBarCodes/'.$uuid.'.jpg', $barcode);
        $fpdi->Image(storage_path('app/papersBarCodes/'.$uuid.'.jpg'), 10, 5, 80, 20, 'JPG');

        return $this;
    }

    public function addCheckInBarCode(Fpdi $fpdi, string $uuid): UsersImport
    {
        $generator = new BarcodeGeneratorJPG();
        $barcode = $generator->getBarcode($uuid, $generator::TYPE_CODE_128, 3, 50, [0, 0, 0]);
        Storage::put('checkInsBarCodes/'.$uuid.'.jpg', $barcode);
        $fpdi->Image(storage_path('app/checkInsBarCodes/'.$uuid.'.jpg'), 22, 260, 80, 20, 'JPG');

        return $this;
    }

    public function addCheckOutBarCode(Fpdi $fpdi, string $uuid): UsersImport
    {
        $generator = new BarcodeGeneratorJPG();
        $barcode = $generator->getBarcode($uuid, $generator::TYPE_CODE_128, 3, 50, [0, 0, 0]);
        Storage::put('checkOutsBarCodes/'.$uuid.'.jpg', $barcode);
        $fpdi->Image(storage_path('app/checkOutsBarCodes/'.$uuid.'.jpg'), 120, 260, 80, 20, 'JPG');

        return $this;
    }

    public function generateID(): string
    {
        return 'CELEC-'.substr(uniqid(), -10);
    }
}
