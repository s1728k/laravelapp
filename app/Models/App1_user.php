<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class App1_user extends Authenticatable
{
use Notifiable;
public $table = 'app1_users';
protected $connection = 'apps_db';
protected $fillable = ['chat_resource_id'];
protected $hidden = ['password', 'remember_token',];
}
