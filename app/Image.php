<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Class Image.
 *
 * @property string $name
 * @property string $image
 * @property int $order
 * @property string $status
 */
class Image extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait;

    /**
     * Fillable Form Fields.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'order',
        'status',
    ];

    /**
     * Appendable Fields.
     *
     * @var array
     */
    protected $appends = [
        'image',
        'image_link',
        'image_full_url',
    ];

    /**
     * Relation With Models.
     *
     * @var array
     */
    protected $with = [
        'media'
    ];

    /**
     * Validation Rules To Store New Image.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public static function storeValidation($request)
    {
        return [
            'name' => 'min:5|max:50|required',
            'image' => 'file|required',
            'order' => 'integer|min:1|max:11|required',
            'status' => 'in:1|max:191|nullable',
        ];
    }

    /**
     * Validation Rules To Update Existing Image.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public static function updateValidation($request)
    {
        return [
            'name' => 'min:5|max:50|required',
            'image' => 'nullable',
            'order' => 'integer|min:1|max:11|required',
            'status' => 'in:1|max:191|nullable',
        ];
    }

    /**
     * Image Attribute.
     *
     * @return mixed
     */
    public function getImageAttribute()
    {
        return $this->getFirstMedia('image');
    }

    /**
     * Image Full URL Attribute.
     *
     * @return mixed
     */
    public function getImageFullUrlAttribute()
    {
        $file = $this->getFirstMedia('image');

        if (!$file) {
            return null;
        }

        return '<imge src="' . $file->getFullUrl() . '" target="_blank" />';
        //return '<a href="' . $file->getFullUrl() . '" target="_blank">' . $file->file_name . '</a>';
    }

    /**
     * Image Link Attribute.
     *
     * @return string
     */
    public function getImageLinkAttribute()
    {
        $file = $this->getFirstMedia('image');
        if (!$file) {
            return null;
        }

        return '<img src="' . $file->getFullUrl('thumb') . '"/>';
        //return '<a href="' . $file->getUrl() . '" target="_blank">' . $file->file_name . '</a>';
    }

    /**
     * Register Media Conversion.
     *
     * @param Media $media
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
              ->width(150)
              ->height(150)
              ->sharpen(10);
    }
}
