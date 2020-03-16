<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Product extends Model
{
    protected $perPage = 50;

    protected $fillable = [
        'stock_no',
        'lab',
        'report_no',
        'report_link',
        'shape',
        'carats',
        'color',
        'clarity',
        'cut',
        'polish',
        'symmetry',
        'fluorescence',
        'measurements',
        'depth_percentage',
        'table_percentage',
        'ratio',
        'girdle',
        'culet',
        'video_link',
        'image_link',
        'origin',
        'total_amount',
        'final_price',
        'csv_data_id',
        'product_id',
        'user_id',
        'api_id',
    ];

    protected $short_names = [
        'good'      => 'GD',
        'very good' => 'VG',
        'excellent' => 'EX',
        'ideal'     => 'ID',
    ];

    protected static function boot()
    {
        parent::boot();


    }

    /**
    * Get the product_title.
    *
    * @return string
    */
    public function getProductTitleAttribute()
    {
    //    return "{$this->carats} {$this->color} {$this->clarity} {$this->shape}";

        $shape = $this->shape != 'ROUND BRILLIANT' ? ucfirst(strtolower($this->shape)) : 'Round';
        return "{$this->carats} Carat {$this->color}-Color {$this->clarity}-Clarity {$shape} Diamond";
    }

    /**
     * Set the shape.
     *
     * @param  string  $value
     * @return void
     */
    public function setShapeAttribute($value)
    {
        $shapes_abbr = Config::get('diamonds.abbreviation');

        $value = $shapes_abbr[strtoupper($value)] ?? $value;

        $this->attributes['shape'] = strtoupper($value);
    }

    /**
     * Set the cut.
     *
     * @param  string  $value
     * @return void
     */
    public function setCutAttribute($value)
    {
        $this->attributes['cut'] = empty($value) ? 'N/A' : ($this->short_names[strtolower($value)] ?? $value);
    }

    /**
     * Set the polish.
     *
     * @param  string  $value
     * @return void
     */
    public function setPolishAttribute($value)
    {
        $this->attributes['polish'] = $this->short_names[strtolower($value)] ?? $value;
    }

    /**
     * Set the symmetry.
     *
     * @param  string  $value
     * @return void
     */
    public function setSymmAttribute($value)
    {
        $this->attributes['symmetry'] = $this->short_names[strtolower($value)] ?? $value;
    }

    /**
     * Set the fluorescence.
     *
     * @param  string  $value
     * @return void
     */
    public function setFluorescenceAttribute($value)
    {
        $this->attributes['fluorescence'] = strtoupper($value);
    }

    /**
    * Set the measurements.
    *
    * @return string
    */
    public function setMeasurementsAttribute($value)
    {
        if($value) {
            $value = str_replace([' x ', ' X ', 'X', 'x', ' * ', '*'], ' x ', $value);
            $value = preg_replace('/\s\s+/', ' ', $value);
        }
       $this->attributes['measurements'] = $value;
    }

    /**
     * Set the table_percentage.
     *
     * @param  string  $value
     * @return void
     */
    public function setTablePercentageAttribute($value)
    {
        if($value) {
            $value = str_replace('%', '', $value);
        }
        $this->attributes['table_percentage'] = $value && is_numeric($value) ? $value : 0;
    }

    /**
     * Set the depth_percentage.
     *
     * @param  string  $value
     * @return void
     */
    public function setDepthPercentageAttribute($value)
    {
        if($value) {
            $value = str_replace('%', '', $value);
        }
        $this->attributes['depth_percentage'] = $value && is_numeric($value) ? $value : 0;
    }

    /**
     * Set the rap_price.
     *
     * @param  string  $value
     * @return void
     */
    public function setRapPriceAttribute($value)
    {
        $this->attributes['rap_price'] = $value && is_numeric($value) ? str_replace(',', '', $value) : 0;
    }

    /**
     * Set the per_carat_amount.
     *
     * @param  string  $value
     * @return void
     */
    public function setPerCaratAmountAttribute($value)
    {
        $this->attributes['per_carat_amount'] = $value && is_numeric($value) ? str_replace(',', '', $value) : 0;
    }

    /**
     * Set the total_amount.
     *
     * @param  string  $value
     * @return void
     */
    public function setTotalAmountAttribute($value)
    {
        $this->attributes['total_amount'] = $value && is_numeric($value) ? str_replace(',', '', $value) : 0;
    }
}
