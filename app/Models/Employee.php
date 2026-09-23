<?php
// app/Models/Employee.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'admin_id','user_id','employee_id','name','email','phone',
        'role','department','designation','salary',
        'joining_date','status','profile_image','address',
    ];
    protected $casts = ['joining_date'=>'date','salary'=>'decimal:2'];

    public function admin()      { return $this->belongsTo(Admin::class); }
    public function user()       { return $this->belongsTo(User::class); }
    public function attendance() { return $this->hasMany(Attendance::class); }

    public function getPresentThisMonthAttribute(): int {
        return $this->attendance()->whereMonth('date',now()->month)->where('status','present')->count();
    }
    public function getTodayStatusAttribute(): string {
        $att = $this->attendance()->where('date',today())->first();
        return $att ? ucfirst($att->status) : 'Not marked';
    }
    protected static function boot() {
        parent::boot();
        static::creating(function($e) {
            if (empty($e->employee_id))
                $e->employee_id = 'EMP-'.str_pad(static::count()+1,3,'0',STR_PAD_LEFT);
        });
    }
}