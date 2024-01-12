<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Tag;

/**
 * @mixin Tag
 */
class TagResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name
        ];
    }
}
