<?php

namespace App\Imports;

use App\Models\Signature;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Picqer\Barcode\BarcodeGeneratorJPG;
use setasign\Fpdi\Fpdi;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use ZanySoft\Zip\Zip;

class UsersImport implements ToCollection
{
    public function __construct(public Model $model, public ?string $templatePath)
    {
    }

    /**
     * @param  array  $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        if ($this->templatePath === null) {
            $invitationTemplate = storage_path('app/A4 - 1INVITATION.pdf');
        }else {
            $invitationTemplate = $this->templatePath;
        }
        

        $folder = $this->model->getTitle().'-invitations-'.Carbon::now()->format('Y-m-d H:i:s');
        if (! is_dir(storage_path().'/app/invitations-papers/')) {
            mkdir(storage_path().'/app/invitations-papers/');
        }
        mkdir(storage_path().'/app/invitations-papers/'.$folder);
        foreach ($rows as $key => $row) {
            if ($key > 0) {
                $fpdi = new Fpdi;
                $count = $fpdi->setSourceFile($invitationTemplate);
                $fpdi->SetFont('Times', 'IB', 24);
                for ($i = 1; $i <= $count; $i++) {
                    $fpdi->SetTextColor(68, 69, 68);
                    $template = $fpdi->importPage($i);
                    $size = $fpdi->getTemplateSize($template);
                    $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $fpdi->useTemplate($template);
                    $paperCode = $this->generateID().$this->model->id;
                    $checkincode = $this->generateID();
                    $checkoutcode = $this->generateID();
                    $this
                        ->writeInscriptionDate($fpdi, $row[10])
                        ->writeEventName($fpdi, $this->model->getTitle())
                        ->writeEventDate($fpdi, $this->model->getStartDate())
                        ->writeEventLocation($fpdi, $this->model->getLocation())
                        ->writeFullname($fpdi, $row[2])
                        ->writeEmail($fpdi, $row[3])
                        ->writePhone($fpdi, $row[4])
                        ->addPaperBarCode($fpdi, $paperCode)
                        ->addCheckInBarCode($fpdi, $checkincode)
                        ->addCheckOutBarCode($fpdi, $checkoutcode);
                }
                $filepath = 'invitations-papers/'.$folder.'/'.$row[2].'-'.$this->model->getTitle().'-'.Carbon::now()->format('Y-m-d H:i:s').'.pdf';

                $invitation = $this->model->invitations()->create(['path' => $filepath, 'member_id' => $row[0]]);
                Signature::create([
                    'member_id' => $row[0],
                    'invitation_id' => $invitation->id,
                    'paper_code' => $paperCode,
                    'checkin_code' => $checkincode,
                    'checkout_code' => $checkoutcode,
                ]);

                $fpdi->Output(storage_path('app/'.$filepath), 'F');
            }
        }
        if (! is_dir(storage_path().'/app/public/archive-invitations-papers/')) {
            mkdir(storage_path().'/app/public/archive-invitations-papers/');
        }
        $zip = new Zip;
        $zip->create(storage_path().'/app/public/archive-invitations-papers/'.$folder.'.zip');
        $zip->add(storage_path().'/app/invitations-papers/'.$folder.'/');
        $zip->close();
        $this->model->archive()->create(['path' => 'archive-invitations-papers/'.$folder.'.zip']);
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
        $fpdi->Text($left, $top, mb_convert_encoding($text, 'UTF-8', mb_detect_encoding($text)));

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
        // $generator = new BarcodeGeneratorJPG();
        // $barcode = $generator->getBarcode($uuid, $generator::TYPE_CODE_128, 3, 50, [0, 0, 0]);
        $barcode = QrCode::format('png')->size(100)->backgroundColor(243, 246, 249, 50)->style('round')->color(2, 79, 156)->generate($uuid);
        Storage::put('papersBarCodes/'.$uuid.'.png', $barcode);
        $fpdi->Image(storage_path('app/papersBarCodes/'.$uuid.'.png'), 10, 5, 50, 50, 'PNG');

        return $this;
    }

    public function addCheckInBarCode(Fpdi $fpdi, string $uuid): UsersImport
    {
        // $generator = new BarcodeGeneratorJPG();
        // $barcode = $generator->getBarcode($uuid, $generator::TYPE_CODE_128, 3, 50, [0, 0, 0]);
        $barcode = QrCode::format('png')->size(100)->backgroundColor(243, 246, 249)->style('round')->color(2, 79, 156)->generate($uuid);
        Storage::put('checkInsBarCodes/'.$uuid.'.png', $barcode);
        $fpdi->Image(storage_path('app/checkInsBarCodes/'.$uuid.'.png'), 22, 230, 50, 50, 'PNG');

        return $this;
    }

    public function addCheckOutBarCode(Fpdi $fpdi, string $uuid): UsersImport
    {
        // $generator = new BarcodeGeneratorJPG();
        // $barcode = $generator->getBarcode($uuid, $generator::TYPE_CODE_128, 3, 50, [0, 0, 0]);
        $barcode = QrCode::format('png')->size(100)->backgroundColor(243, 246, 249)->style('round')->color(2, 79, 156)->generate($uuid);
        Storage::put('checkOutsBarCodes/'.$uuid.'.png', $barcode);
        $fpdi->Image(storage_path('app/checkOutsBarCodes/'.$uuid.'.png'), 140, 230, 50, 50, 'PNG');

        return $this;
    }

    public function generateID(): string
    {
        return 'CELEC-'.substr(uniqid(), -10);
    }
}
