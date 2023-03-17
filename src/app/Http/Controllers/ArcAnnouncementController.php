<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArcAnnouncement;
use Illuminate\Support\Collection;
use App\Http\Resources\ArcAnnouncementResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ArcAnnouncementController extends Controller
{
    public function all(): AnonymousResourceCollection
    {
        return ArcAnnouncementResource::collection(ArcAnnouncement::orderByDesc('created_at')->get());
    }
}
