<?php
namespace App\Models;
use CodeIgniter\Model;
class OtpTokenModel extends Model
{
    protected $table = 'otp_token';
    protected $primaryKey = 'id';
    protected $allowedFields = ['userID', 'token', 'expires_at', 'created_at'];
    public $timestamps = false;
}
