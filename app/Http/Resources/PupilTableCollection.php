<?php

namespace App\Http\Resources;

use App\Models\Pupil;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PupilTableCollection extends ResourceCollection
{
    public $collects = Pupil::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return ['data' => $this->collection];
    }
}
