<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
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
        return response($categories);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        return response($category);
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
            return response()->json(['error' => 'نظرسنجی مورد نظر یافت نشد'], 204);
        }

        if (!empty($requestData['parent_id'])) {
            $parentCategory = Category::where([
                    'id' => $requestData['parent_id'],
                ]
            )
                ->first();
            if (!$parentCategory) {
                return response()->json(['error' => 'دسته بندی  والد وجود ندارد'], 204);
            }
        }

        $category = Category::create($requestData);


        return response()->json([
            'code' => 1,
            'result' => true,
        ], 201, $this->__headers);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $category = Category::where('id', $id)
            ->first();
        if (!$category) {
            return response()->json([
                'code' => 0,
                'error' => 'دسته بندی مورد نظر یافت نشد!',
            ], 204, $this->__headers);
        }

        $requestData = $request->all();

        $result = $category->update($requestData);

        if (!$result) {
            return response()->json([
                'code' => 0,
                'error' => 'خطا در عملیات مجددا سعی کنید!',
            ], 501, $this->__headers);
        }

        return response()->json([
            'code' => 1,
            'result' => true,
        ], 200, $this->__headers);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $category = Category::where('id', $id)
            ->first();
        $category->delete();

        return response()->json([
            'code' => 1,
            'result' => true,
        ], 200, $this->__headers);
    }
}
