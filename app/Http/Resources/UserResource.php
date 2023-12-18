<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => [
                'name' => $this->name,
                'email' => $this->email
            ],
            'ability' => [
                [
                    "action" => "read",
                    "subject" => "dashboard"
                ],
                [
                    "action" => "read",
                    "subject" => "second"
                ]
                //Systems
//                $this->can('READ ACCESS') || $this->can('MANAGE ALL') ? [
//                    "action" => "read",
//                    "subject" => "access"
//                ] : [],
//                $this->can('READ FINANCE') || $this->can('MANAGE ALL') ? [
//                    "action" => "read",
//                    "subject" => "finance"
//                ] : [],
            ]
        ];
    }
}
