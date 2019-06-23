<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\BasicResource;
use Illuminate\Http\Request;
use App\Http\Resources\BasicCollectionResource;
use App;
use App\Category;
use App\Poll;

class CategoryController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return response(new BasicCollectionResource($categories),200);
    }

    /**
     * @param Category $category
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show(category $category)
    {
        if (!$category) {
            throw new ModelNotFoundException('Entry doesnt found');
        }
        return response(new BasicResource($category),200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $requestData = $request->all();

        $poll = Poll::where(['id' => $requestData['poll_id']])->first();

        if (empty($poll)) {
            throw new ModelNotFoundException('Entry doesnt found');
        }

        if (!empty($requestData['parent_id'])) {
            $parentCategory = Category::where([
                    'id' => $requestData['parent_id'],
                ]
            )
                ->first();
            if (!$parentCategory) {
                throw new ModelNotFoundException('Parent Entry doesnt found');
            }
        }

        Category::create($requestData);

        return response('Category Saved', 201);
    }

    /**
     * @param Request $request
     * @param Category $category
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        if (!$category) {
            throw new ModelNotFoundException('Entry didnt find');
        }

        $requestData = $request->all();

        $category->update($requestData);

        return response('Category Updated', 200);
    }

    /**
     * @param Category $category
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Category $category)
    {
        if (!$category) {
            throw new ModelNotFoundException('Entry doesnt found');
        }
        $category->delete();

        return response('deleted', 204);
    }
}
