<?php

namespace Modules\Supplier\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Contact\Transformers\ContactResource;
use Modules\Location\Transformers\LocationResource;
use Modules\Upload\Transformers\UploadResource;

class SupplierResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'fullname' => $this->fullname,
            'company_name' => $this->company_name,
            'email' => $this->email,
            'remarks' => $this->remarks ?? "",
            'is_active' => $this->is_active,
            'type' => $this->type,
            'locations' => LocationResource::collection($this->whenLoaded('locations')),
            'contacts' => ContactResource::collection($this->whenLoaded('contacts')),
            'avatar' => $this->relationLoaded('media') && $this->media_count ? (new UploadResource($this->getFirstMedia('suppliers')))->additional(['conversion' => 'avatar'])
                : config('upload.default_image'),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
