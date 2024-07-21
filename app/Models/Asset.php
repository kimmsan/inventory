<?php

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use Sluggable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'cat_id', 'slug', 'asset_cost', 'depreciation', 'depreciation_type', 'salvage_value', 'useful_life', 'daily_depreciation', 'note', 'image_path', 'date', 'expire_date', 'created_by', 'status',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    protected $appends = ['calculated_value'];

    /**
     * Get the current value
     *
     * @return string
     */
    public function getCalculatedValueAttribute()
    {
        return $this->currentValue();
    }

    // return depreciation expense
    public function depreciationExpense()
    {
        $depreciationExpense = null;
        if ($this->depreciation == 1) {
            $usefulLife = $this->depreciation_type == 1 ? $this->useful_life * 12 : $this->useful_life;
            $depreciationExpense = round(($this->asset_cost - $this->salvage_value) / $usefulLife, 2);
        }

        return $depreciationExpense;
    }

    // return depreciation expense in text format
    public function depreciationExpenseTxt()
    {
        $depreciationExpenseText = 'No Depreciation';
        if ($this->depreciation == 1) {
            $txt = $this->depreciation_type == 1 ? ' Pre Year' : ' Per Month';
            $depreciationExpenseText = $this->depreciationExpense().$txt;
        }

        return $depreciationExpenseText;
    }

    // return current value
    public function currentValue()
    {
        $today = date('Y-m-d');
        $createdAt = $this->date;
        $currentValue = $this->asset_cost;
        if (($this->depreciation == 1) && ($today > $createdAt)) {
            $currentValue = $this->asset_cost - $this->deprecatedAmount();
        }
        $currentValue = $currentValue > 0 ? $currentValue : 0;

        return round($currentValue, 2);
    }

    // calculated deprecated amount
    public function deprecatedAmount()
    {
        $deprecatedAmount = 0;
        if ($this->depreciation == 1) {
            $deprecatedAmount = $this->usedLife() * $this->depreciationExpense();
        }

        return round($deprecatedAmount, 2);
    }

    // calculated used life
    public function usedLife()
    {
        $usedLife = 0;
        if ($this->depreciation == 1) {
            $currentMonth = Carbon::parse(date('Y-m-d'))->floorMonth();
            $endMonth = Carbon::parse($this->date)->floorMonth();
            $usedLife = $endMonth->diffInMonths($currentMonth);
        }

        return $usedLife;
    }

    // calculated remaining life
    public function remainingLife()
    {
        $str = null;
        if ($this->depreciation == 1) {
            $currentMonth = Carbon::parse(date('Y-m-d'))->floorMonth();
            $usefulLife = $this->depreciation_type == 1 ? $this->useful_life * 12 : $this->useful_life;
            $endMonth = Carbon::parse($this->date)->addMonths($usefulLife);

            if ($endMonth > $currentMonth) {
                $remainingLife = $endMonth->diffInMonths($currentMonth);
                if ($remainingLife >= 12) {
                    $year = floor($remainingLife / 12);
                    $month = $remainingLife % 12;
                    $yearText = $year > 1 ? ' Years' : ' Year';
                    $monthText = $month > 1 ? ' Months' : ' Month';
                    if ($month > 0) {
                        $str = $year.$yearText.' & '.$month.$monthText;
                    } else {
                        $str = $year.$yearText;
                    }
                } else {
                    $str = $remainingLife > 1 ? $remainingLife.' Months' : $remainingLife.' Month';
                }
            } else {
                $str = 'Expired';
            }
        }

        return $str;
    }

    /**
     * Get the asset type that owns the asset.
     */
    public function assetType()
    {
        return $this->belongsTo(AssetType::class, 'cat_id');
    }

    /**
     * Get the user who had created this expense.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
