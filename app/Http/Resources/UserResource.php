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
                //Permission
                $this->can('ADD PERMISSION') || $this->can('MANAGE ALL') ? [
                    "action" => "add",
                    "subject" => "permission"
                ] : [],
                $this->can('LIST PERMISSION') || $this->can('MANAGE ALL') ? [
                    "action" => "list",
                    "subject" => "permission"
                ] : [],
                $this->can('UPDATE PERMISSION') || $this->can('MANAGE ALL') ? [
                    "action" => "update",
                    "subject" => "permission"
                ] : [],
                $this->can('DELETE PERMISSION') || $this->can('MANAGE ALL') ? [
                    "action" => "delete",
                    "subject" => "permission"
                ] : [],

                //role
                $this->can('ADD ROLE') || $this->can('MANAGE ALL') ? [
                    "action" => "add",
                    "subject" => "role"
                ] : [],
                $this->can('LIST ROLE') || $this->can('MANAGE ALL') ? [
                    "action" => "list",
                    "subject" => "role"
                ] : [],
                $this->can('UPDATE ROLE') || $this->can('MANAGE ALL') ? [
                    "action" => "update",
                    "subject" => "role"
                ] : [],
                $this->can('DELETE ROLE') || $this->can('MANAGE ALL') ? [
                    "action" => "delete",
                    "subject" => "role"
                ] : [],

                //employee
                $this->can('ADD EMPLOYEE') || $this->can('MANAGE ALL') ? [
                    "action" => "add",
                    "subject" => "employee"
                ] : [],
                $this->can('LIST EMPLOYEE') || $this->can('MANAGE ALL') ? [
                    "action" => "list",
                    "subject" => "employee"
                ] : [],
                $this->can('UPDATE EMPLOYEE') || $this->can('MANAGE ALL') ? [
                    "action" => "update",
                    "subject" => "employee"
                ] : [],
                $this->can('DELETE EMPLOYEE') || $this->can('MANAGE ALL') ? [
                    "action" => "delete",
                    "subject" => "employee"
                ] : [],
            ]
        ];
    }
}
