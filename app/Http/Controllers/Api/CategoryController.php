<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Model\Category;
use App\Traits\CustomApiResponseTrait;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

/**
 * @OA\Tag(name="Categories API", description="Categories API")
 */
/**
 * @OA\Schema(
 *     schema="CategoryRequest",
 *     required={"name", "is_publish"},
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="is_publish", type="boolean"),
 * )
 */
class CategoryController extends Controller
{
    use CustomApiResponseTrait;
    private $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
 * @OA\Get(
 *     path="/category",
 *     tags={"Categories"},
 *     summary="Get list of categories",
 *     @OA\Parameter(
 *         name="search",
 *         in="query",
 *         required=false,
 *         description="Search term to filter categories",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="limit",
 *         in="query",
 *         required=false,
 *         description="Number of categories to return per page",
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         required=false,
 *         description="Page number for pagination",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(response="200", description="A list of categories"),
 *     @OA\Response(response="500", description="Internal Server Error")
 * )
 */

    public function index(Request $request)
    {
        $categories = $this->categoryRepository->getAllCategories($request);
        return $this->apiResponse($categories->items(),'success',200,$categories->total(),$categories->perPage(),$categories->currentPage());
    }

    /**
     * @OA\Post(
     *     path="/category",
     *     tags={"Categories"},
     *     summary="Create new category",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CategoryRequest")
     *     ),
     *     @OA\Response(response="201", description="Success"),
     *     @OA\Response(response="500", description="Internal Server Error")
     * )
     */
    public function store(CategoryRequest $request)
    {
        try{
            $data = $request->validated();

            $category = $this->categoryRepository->store($data);
            return $this->apiResponse($category, 'success', 201);
        }catch(\Exception $e){
            return $this->apiResponse(null, $e->getMessage(), 500);
        }
    }

     /**
     * @OA\Get(
     *     path="/category/{id}",
     *     tags={"Categories"},
     *     summary="Get category by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category to fetch",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Category found"),
     *     @OA\Response(response="404", description="Category not found")
     * )
     */
    public function detail($id)
    {
        try {
            $category = $this->categoryRepository->getCategoryById($id);
            return $this->apiResponse($category, 200);
        } catch (\Exception $e) {
            return $this->apiResponse(null, $e->getMessage(), 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/category/{id}",
     *     tags={"Categories"},
     *     summary="Update category by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CategoryRequest")
     *     ),
     *     @OA\Response(response="200", description="Category updated successfully"),
     *     @OA\Response(response="500", description="Internal Server Error")
     * )
     */
    public function update(CategoryRequest $request, $id)
    {
        try{
            $data = $request->validated();
            $category = $this->categoryRepository->update($data, $id);
            return $this->apiResponse($category, 'success', 200);
        }catch(\Exception $e){
            return $this->apiResponse(null, $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/category/{id}",
     *     tags={"Categories"},
     *     summary="Delete category by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Category deleted successfully"),
     *     @OA\Response(response="404", description="Category not found"),
     *     @OA\Response(response="500", description="Internal Server Error")
     * )
     */
    public function destroy($id)
    {
        try{
            $category = $this->categoryRepository->getCategoryById($id);
            $this->categoryRepository->delete($id);
            return $this->apiResponse(null,'success', 200);
        }catch(\Exception $e){
            return $this->apiResponse(null, $e->getMessage(), 500);
        }
    }
}
