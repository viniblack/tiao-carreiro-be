<?php

namespace Tests\Unit\Resources;

use Tests\TestCase;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;

class UserResourceTest extends TestCase
{
  #[Test]
  public function it_returns_the_expected_user_resource_structure()
  {
    $now = Carbon::now();

    $user = User::factory()->make([
      'id' => 1,
      'name' => 'Vini',
      'email' => 'vini@email.com',
      'role' => 'member',
      'email_verified_at' => $now->toDateTimeString(),
      'created_at' => $now,
      'updated_at' => $now,
    ]);

    $resource = (new UserResource($user))->toArray(request());

    $this->assertEquals([
      'id' => 1,
      'name' => 'Vini',
      'email' => 'vini@email.com',
      'role' => 'member',
      'email_verified_at' => $now->toDateTimeString(),
      'created_at' => $now->toDateTimeString(),
      'updated_at' => $now->toDateTimeString(),
    ], $resource);
  }
}
