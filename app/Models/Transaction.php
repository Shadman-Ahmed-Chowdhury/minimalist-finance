<?php

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'loan_type',
        'loan_party_id',
        'category_id',
        'amount',
        'from_account_id',
        'to_account_id',
        'note',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function scopeIncome(Builder $query)
    {
        return $query->whereIn('type', ['income', 'initial']);
    }

    public function scopeExpense(Builder $query)
    {
        return $query->whereIn('type', ['expense']);
    }

    public function scopeTransfer(Builder $query)
    {
        return $query->whereIn('type', ['transfer']);
    }

    public function scopeLoan(Builder $query)
    {
        return $query->whereIn('type', ['loan']);
    }

    public function scopeMonthly(Builder $query)
    {
        return $query->whereBetween('date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
    }




    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loanParty()
    {
        return $this->belongsTo(LoanParty::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function fromAccount()
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    public function toAccount()
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }
}
