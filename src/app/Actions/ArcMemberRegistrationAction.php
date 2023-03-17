<?php 

namespace App\Actions;

use App\Models\ArcRegistration;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class ArcMemberRegistrationAction {

    public function handle(
        string $teamCode ,
        ?string $teamTitle,
        string $wilaya,
        string $fullname,
        string $email,
        string $phone,
        string $isStudent,
        ?string $job,
        string $tshirt,
        string $linkedInGithub,
        UploadedFile $idCardFile,
        string $needHosting,
        string $skills,
        string $projects,
        string $motivation,
        string $password,
        bool $isActivated
    ): array
    {
        $idCardPath = $idCardFile->store('id_cards', 'public');

        ArcRegistration::create([
            'wilaya' => $wilaya,
            'fullname' => $fullname,
            'email' => $email,
            'phone' => $phone,
            'is_student' => $isStudent,
            'job' => $job,
            'tshirt' => $tshirt,
            'linkedIn_github' => $linkedInGithub,
            'id_card_path' => $idCardPath,
            'need_hosting' => $needHosting,
            'skills' => $skills,
            'projects' => $projects,
            'motivation' => $motivation,
            'team_id' => $teamCode,
            'password' => Hash::make($password),
            'is_accepted' => $isActivated
        ]);

        return ['team_code' => $teamCode];
    }

}