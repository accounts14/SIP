<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $cat = ['Uncategories', 'Information', 'Announcement', 'News', 'Invitation'];
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'slug'        => $this->slug,
            'content'     => $this->content,
            'for_member'  => $this->for_member,
            'category'    => $cat[$this->category],
            'tags'        => explode(',', $this->tags),
            'publisher'   => $this->publishUser->name,
            'published_at'=> $this->published_at,
            'school_id'   => $this->school_id,
            'school'      => $this->whenLoaded('school', function() {
                return [
                    'name'          => $this->school->name,
                    'type'          => $this->school->type,
                    'accreditation' => $this->school->accreditation,
                    'level'         => $this->school->level,
                    'npsn'          => $this->school->npsn,
                ];
            }),
        ];
    }
}
