<?php

namespace App\Imports;

use Carbon\Carbon;
use ZanySoft\Zip\Zip;
use App\Models\ArcTeam;
use setasign\Fpdi\Fpdi;
use App\Models\Signature;
use App\Models\ArcRegistration;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Maatwebsite\Excel\Concerns\ToCollection;

class ArcRegistrationsImport implements ToCollection
{

    public function __construct(public string $templatePath)
    {
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $folder = 'arc-invitations-' . Carbon::now()->format('Y-m-d H:i:s');
        if (!is_dir(storage_path() . '/app/invitations-papers/')) {
            mkdir(storage_path() . '/app/invitations-papers/');
        }
        if (!is_dir(storage_path() . '/app/invitations-papers/' . $folder)) {
            mkdir(storage_path() . '/app/invitations-papers/' . $folder);
        }
        foreach ($rows as $row) {
            $team = ArcTeam::where('code', $row[1])->first();
            $user = ArcRegistration::find($row[0]);
            if ($team !== null and $user !== null) {
                $fpdi = new Fpdi;
                $count = $fpdi->setSourceFile($this->templatePath);
                $fpdi->SetFont('Times', 'IB', 24);
                for ($i = 1; $i <= $count; $i++) {
                    $fpdi->SetTextColor(68, 69, 68);
                    $template = $fpdi->importPage($i);
                    $size = $fpdi->getTemplateSize($template);
                    $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $fpdi->useTemplate($template);
                    $paperCode = $this->generateID() . $user->id;
                    $checkincode = $this->generateID();
                    $checkoutcode = $this->generateID();
                    $this
                        ->writeInscriptionDate($fpdi, $user->created_at)
                        ->writeEventName($fpdi, $user->getTitle())
                        ->writeEventDate($fpdi, $user->getStartDate())
                        ->writeEventLocation($fpdi, $user->getLocation())
                        ->writeTeamName($fpdi, $team->title)
                        ->writeFullname($fpdi, $user->fullname)
                        ->writeFonction($fpdi, ($user->is_student) ? "Student" : $user->job)
                        ->addPaperBarCode($fpdi, $paperCode)
                        ->addCheckInBarCode($fpdi, $checkincode)
                        ->addCheckOutBarCode($fpdi, $checkoutcode);
                    $filepath = 'invitations-papers/' . $folder . '/' . $team->title . '-' . $user->fullname . '.pdf';

                    $invitation = $user->invitations()->create(['path' => $filepath, 'member_id' => $user->id]);
                    Signature::create([
                        'member_id' => $user->id,
                        'invitation_id' => $invitation->id,
                        'paper_code' => $paperCode,
                        'checkin_code' => $checkincode,
                        'checkout_code' => $checkoutcode,
                    ]);

                    $fpdi->Output(storage_path('app/' . $filepath), 'F');
                }
            }
        }
        if (!is_dir(storage_path() . '/app/public/archive-invitations-papers/')) {
            mkdir(storage_path() . '/app/public/archive-invitations-papers/');
        }
        $zip = new Zip;
        $zip->create(storage_path() . '/app/public/archive-invitations-papers/' . $folder . '.zip');
        if (! $zip->has(storage_path() . '/app/invitations-papers/' . $folder . '/')) {
            $zip->add(storage_path() . '/app/invitations-papers/' . $folder . '/');
        }
        
        $zip->close();
        Cache::put('exported-arc-invitations-path', 'archive-invitations-papers/' . $folder . '.zip');
    }

    public function generateID(): string
    {
        return 'CELEC-' . substr(uniqid(), -10);
    }

    public function writeInscriptionDate(Fpdi $fpdi, string $text)
    {
        $left = 125;
        $top = 32;
        $fpdi->Text($left, $top, Carbon::parse($text)->format('Y-m-d'));

        return $this;
    }

    public function writeEventName(Fpdi $fpdi, string $text)
    {
        $left = 62;
        $top = 90;
        $fpdi->Text($left, $top, $text);

        return $this;
    }

    public function writeEventDate(Fpdi $fpdi, string $text)
    {
        $left = 62;
        $top = 108;
        $fpdi->Text($left, $top, Carbon::parse($text)->format('Y-m-d'));

        return $this;
    }

    public function writeEventLocation(Fpdi $fpdi, string $text)
    {
        $left = 70;
        $top = 127;
        $fpdi->Text($left, $top, $text);

        return $this;
    }

    public function writeTeamName(Fpdi $fpdi, string $text)
    {
        $left = 75;
        $top = 177;
        $fpdi->Text($left, $top, mb_convert_encoding($text, 'UTF-8', mb_detect_encoding($text)));

        return $this;
    }

    public function writeFullname(Fpdi $fpdi, string $text)
    {
        $left = 80;
        $top = 195;
        $fpdi->Text($left, $top, mb_convert_encoding($text, 'UTF-8', mb_detect_encoding($text)));

        return $this;
    }

    public function writeFonction(Fpdi $fpdi, string $text)
    {
        $left = 92;
        $top = 210;
        $fpdi->Text($left, $top, $text);

        return $this;
    }

    public function addPaperBarCode(Fpdi $fpdi, string $uuid)
    {
        // $generator = new BarcodeGeneratorJPG();
        // $barcode = $generator->getBarcode($uuid, $generator::TYPE_CODE_128, 3, 50, [0, 0, 0]);
        $barcode = QrCode::format('png')->size(100)->backgroundColor(243, 246, 249, 50)->style('round')->color(2, 79, 156)->generate($uuid);
        Storage::put('papersBarCodes/' . $uuid . '.png', $barcode);
        $fpdi->Image(storage_path('app/papersBarCodes/' . $uuid . '.png'), 10, 5, 50, 50, 'PNG');

        return $this;
    }

    public function addCheckInBarCode(Fpdi $fpdi, string $uuid)
    {
        // $generator = new BarcodeGeneratorJPG();
        // $barcode = $generator->getBarcode($uuid, $generator::TYPE_CODE_128, 3, 50, [0, 0, 0]);
        $barcode = QrCode::format('png')->size(100)->backgroundColor(243, 246, 249)->style('round')->color(2, 79, 156)->generate($uuid);
        Storage::put('checkInsBarCodes/' . $uuid . '.png', $barcode);
        $fpdi->Image(storage_path('app/checkInsBarCodes/' . $uuid . '.png'), 22, 230, 50, 50, 'PNG');

        return $this;
    }

    public function addCheckOutBarCode(Fpdi $fpdi, string $uuid)
    {
        // $generator = new BarcodeGeneratorJPG();
        // $barcode = $generator->getBarcode($uuid, $generator::TYPE_CODE_128, 3, 50, [0, 0, 0]);
        $barcode = QrCode::format('png')->size(100)->backgroundColor(243, 246, 249)->style('round')->color(2, 79, 156)->generate($uuid);
        Storage::put('checkOutsBarCodes/' . $uuid . '.png', $barcode);
        $fpdi->Image(storage_path('app/checkOutsBarCodes/' . $uuid . '.png'), 140, 230, 50, 50, 'PNG');

        return $this;
    }
}
