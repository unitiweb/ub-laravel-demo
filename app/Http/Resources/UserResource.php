<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * User resource class to display user details
 *
 * @package App\Http\Resources
 *
 * @property int id
 * @property string uid
 * @property int siteId
 * @property string email
 * @property string emailChange
 * @property string avatar
 * @property string firstName
 * @property string lastName
 * @property string role
 * @property bool verified
 * @property string status
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'uid' => $this->uid,
            'email' => $this->email,
            'emailChange' => $this->emailChange,
            'avatar' => $this->avatar,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'role' => $this->role,
            'verified' => $this->verified,
            'status' => $this->status,
            'settings' => new SettingsResource($this->whenLoaded('settings')),
            'site' => new SiteResource($this->whenLoaded('site')),
        ];
    }
}
