<?php

namespace App\Http\Resources\Activity;

use App\Helpers\DateHelper;
use App\Http\Resources\Activity\ActivityType as ActivityTypeResource;
use App\Http\Resources\Emotion\Emotion as EmotionResource;
use Illuminate\Http\Resources\Json\Resource;

class Activity extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'object' => 'activity',
            'summary' => $this->summary,
            'description' => $this->description,
            'happened_at' => DateHelper::getDate($this->happened_at),
            'activity_type' => new ActivityTypeResource($this->type),
            'attendees' => [
                'total' => $this->contacts()->count(),
                'contacts' => $this->getContactsForAPI(),
            ],
            'emotions' => EmotionResource::collection($this->emotions),
            'url' => route('api.activity', $this->id),
            'account' => [
                'id' => $this->account_id,
            ],
            'created_at' => DateHelper::getTimestamp($this->created_at),
            'updated_at' => DateHelper::getTimestamp($this->updated_at),
        ];
    }
}
