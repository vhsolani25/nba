<?php

namespace App\Http\Controllers\Api\V1;

use App\Image;
use App\Http\Controllers\Controller;
use App\Http\Resources\Image as ImageResource;
use App\Http\Requests\Admin\StoreImagesRequest;
use App\Http\Requests\Admin\UpdateImagesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Http\Controllers\Traits\FileUploadTrait;


class ImagesController extends Controller
{
    public function index()
    {
        

        return new ImageResource(Image::with([])->get());
    }

    public function show($id)
    {
        if (Gate::denies('image_view')) {
            return abort(401);
        }

        $image = Image::with([])->findOrFail($id);

        return new ImageResource($image);
    }

    public function store(StoreImagesRequest $request)
    {
        if (Gate::denies('image_create')) {
            return abort(401);
        }

        $image = Image::create($request->all());
        
        if ($request->hasFile('image')) {
            $image->addMedia($request->file('image'))->toMediaCollection('image');
        }

        return (new ImageResource($image))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateImagesRequest $request, $id)
    {
        if (Gate::denies('image_edit')) {
            return abort(401);
        }

        $image = Image::findOrFail($id);
        $image->update($request->all());
        
        if (! $request->input('image') && $image->getFirstMedia('image')) {
            $image->getFirstMedia('image')->delete();
        }
        if ($request->hasFile('image')) {
            $image->addMedia($request->file('image'))->toMediaCollection('image');
        }

        return (new ImageResource($image))
            ->response()
            ->setStatusCode(202);
    }

    public function destroy($id)
    {
        if (Gate::denies('image_delete')) {
            return abort(401);
        }

        $image = Image::findOrFail($id);
        $image->delete();

        return response(null, 204);
    }
}
