<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class App9_content_writer extends Authenticatable
{
use Notifiable;
public $table = 'app9_content_writer';
protected $fillable = ['full_name', 'tel', 'skype', 'profession', 'email', 'password'];
protected $hidden = ['password', 'remember_token',];
}
