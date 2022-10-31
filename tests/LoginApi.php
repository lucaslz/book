<?php

namespace Tests;

use App\Models\User;

trait LoginApi
{
    /**
     * Realiza o Login da API
     * 
     * @return mixed
     */
    public function login()
    {
        $user = User::factory()->createOne();
        return $this->actingAs($user, 'api');
    }
}