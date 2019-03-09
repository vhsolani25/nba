<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
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
    public function store(StoreImagesRequest $request)
    {
        if (Gate::denies('image_create')) {
            return abort(401);
        }
        //dd($request->all());
        $image = Image::create($request->all());

        if ($request->hasFile('file')) {
            $image->addMedia($request->file('file'))->toMediaCollection('image');
        }

        return (new ImageResource($image))
            ->response()
            ->setStatusCode(201);
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
