<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;

class UserModelTest extends TestCase
{
  use RefreshDatabase;

  #[Test]
  public function user_table_has_expected_columns()
  {
    $this->assertTrue(Schema::hasColumns('users', [
      'name',
      'email',
      'role',
      'password'
    ]));
  }

  #[Test]
  public function can_create_user_with_fillable_attributes()
  {
    $data = [
      'name' => 'Vini',
      'email' => 'vini@email.com',
      'role' => 'member',
      'password' => bcrypt('senha123'),
    ];

    $user = User::create($data);

    $this->assertDatabaseHas('users', ['email' => 'vini@email.com']);
    $this->assertTrue(Hash::check('senha123', $user->password));
  }

  #[Test]
  public function factory_creates_valid_user()
  {
    $user = User::factory()->create();

    $this->assertInstanceOf(User::class, $user);
    $this->assertDatabaseHas('users', ['id' => $user->id]);
  }

  #[Test]
  public function factory_custom_data_works()
  {
    $user = User::factory()->customData()->create();

    $this->assertNotNull($user->name);
    $this->assertNotNull($user->email);
    $this->assertNotNull($user->role);
    $this->assertNotNull($user->password);
    $this->assertDatabaseHas('users', ['id' => $user->id]);
  }

  #[Test]
  public function seeder_creates_defined_users()
  {
    $this->seed();

    $this->assertDatabaseCount('users', 2);
    $this->assertDatabaseHas('users', [
      'name' => 'Admin',
      'email' => 'admin@email.com'
    ]);
  }
}
