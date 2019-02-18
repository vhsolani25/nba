<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Class Image
 *
 * @package App
 * @property string $name
 * @property string $image
 * @property integer $order
 * @property string $status
*/
class Image extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait;

    
    protected $fillable = ['name', 'order', 'status'];
    protected $appends = ['image', 'image_link'];
    protected $with = ['media'];
    

    public static function storeValidation($request)
    {
        return [
            'name' => 'min:5|max:50|required',
            'image' => 'file|required',
            'order' => 'integer|min:1|max:11|required',
            'status' => 'in:1|max:191|nullable'
        ];
    }

    public static function updateValidation($request)
    {
        return [
            'name' => 'min:5|max:50|required',
            'image' => 'nullable',
            'order' => 'integer|min:1|max:11|required',
            'status' => 'in:1|max:191|nullable'
        ];
    }

    

    public function getImageAttribute()
    {
        return $this->getFirstMedia('image');
    }

    /**
     * @return string
     */
    public function getImageLinkAttribute()
    {
        $file = $this->getFirstMedia('image');
        if (! $file) {
            return null;
        }

        return '<a href="' . $file->getUrl() . '" target="_blank">' . $file->file_name . '</a>';
    }
    
    
}
