<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreImagesRequest;
use App\Http\Requests\Admin\UpdateImagesRequest;
use App\Http\Resources\Image as ImageResource;
use App\Image;
use Illuminate\Support\Facades\Gate;

class ImagesController extends Controller
{
    /**
     * Image List.
     *
     * @return mixed
     */
    public function index()
    {
        return new ImageResource(Image::with([])->get());
    }

    /**
     * View Image.
     *
     * @param int|string $id
     *
     * @return mixed
     */
    public function show($id)
    {
        if (Gate::denies('image_view')) {
            return abort(401);
        }

        $image = Image::with([])->findOrFail($id);

        return new ImageResource($image);
    }

    /**
     * Store Image.
     *
     * @param StoreImagesRequest $request
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        if (Gate::denies('image_create')) {
            return abort(401);
        }

        $this->validate(
            $request,
            [
                'images' => 'required',
                'images.*' => 'required|mimes:jpg,jpeg,png|max:10000'
            ],
            [
                'images.*.required' => 'Please upload an image',
                'images.*.mimes' => 'Only jpeg,png images are allowed',
                'images.*.max' => 'Sorry! Maximum allowed size for an image is 10MB',
            ]
        );

        if ($request->hasfile('images')) {
            if (count($request->images)) {
                foreach ($request->images as $file) {
                    $image = Image::create(
                        [
                            'name' => 'one',
                            'status' => 1,
                            'order' => 1
                        ]
                    );
                    $image->addMedia($file)->toMediaCollection('image');
                }
            }
            return (new ImageResource($image))
            ->response()
            ->setStatusCode(201);
        }
    }

    /**
     * Update Image.
     *
     * @param UpdateImagesRequest $request
     * @param int|string          $id
     *
     * @return mixed
     */
    public function update(UpdateImagesRequest $request, $id)
    {
        if (Gate::denies('image_edit')) {
            return abort(401);
        }

        $image = Image::findOrFail($id);
        $image->update($request->all());

        if (!$request->input('image') && $image->getFirstMedia('image')) {
            $image->getFirstMedia('image')->delete();
        }
        if ($request->hasFile('image')) {
            $image->addMedia($request->file('image'))->toMediaCollection('image');
        }

        return (new ImageResource($image))
            ->response()
            ->setStatusCode(202);
    }

    /**
     * Destroy Image.
     *
     * @param int|string $id
     *
     * @return mixed
     */
    public function destroy($id)
    {
        if (Gate::denies('image_delete')) {
            return abort(401);
        }

        $image = Image::findOrFail($id);
        $image->forceDelete();

        return response(null, 204);
    }
}
