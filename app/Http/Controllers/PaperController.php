<?php

namespace App\Http\Controllers;

use App\Helper\HelperFunction;
use App\Helper\Page;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Paper;
use App\Models\PaperContent;
use App\Models\PaperTimeLine;
use App\Models\RemoteSourceHistory;
use App\Models\ViewSource;
use App\Models\Writer;
use App\Services\CartService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Thanhnt\Nan\Helper\LogTha;
use Thanhnt\Nan\Helper\StringHelper;
use Throwable;

class PaperController extends Controller
{
    use Page;
    use StringHelper;

    protected $request;
    protected $paper;
    protected $category;
    protected $notification;
    protected $helperFunction;
    protected $logTha;
    protected $paper_timeline;
    /**
     * @var CartService $cartService
     */
    protected $cartService;

    const PAGE_TYPE = 1;

    public function __construct(
        Request $request,
        Paper $paper,
        Category $category,
        PaperTimeLine $paper_timeline,
        Notification $notification,
        HelperFunction $helperFunction,
        LogTha $logTha,
        CartService $cartService
    )
    {
        $this->request = $request;
        $this->paper = $paper;
        $this->logTha = $logTha;
        $this->category = $category;
        $this->notification = $notification;
        $this->helperFunction = $helperFunction;
        $this->paper_timeline = $paper_timeline;
        $this->cartService = $cartService;
    }

    public function listPaper()
    {
        $page_lists = $this->paper->orderBy("updated_at", "DESC")->paginate(8);
        foreach ($page_lists as &$page) {
            $resuls = [];
            $categories = $page->to_category()->get("category_id")->toArray();
            if ($categories) {
                $id_of_categories = array_map(function ($item) {
                    return $item["category_id"];
                }, $categories);

                $resuls = Category::all(['id', "name"])->whereIn("id", $id_of_categories);
            }
            $page->categories = $resuls;
        }
        return view("adminhtml.templates.papers.list", compact("page_lists"));
    }

    public function createPaper()
    {
        $writers = Writer::all();
        return view("adminhtml.templates.papers.create", [
            "category_option" => $this->category->category_tree_option(),
            "filemanager_url" => url("adminhtml/file/manager") . "?editor=tinymce5",
            "filemanager_url_base" => url("adminhtml/file/manager"),
            "time_line_option" => $this->category->time_line_option(),
            "writers" => $writers
        ]);
    }

    function pageType(): string
    {
        $request = $this->request;
        if ($request->get('slider_data')) {
            return 'carousel';
        }
        if (!empty($request->get('price'))) {
            return 'product';
        }
        return 'content';
    }

    protected function convertRequestData(int $paper_id): array
    {
        $datas = $this->request->toArray();
        $returnValues = [];
        foreach ($datas as $key => $value) {
            if (empty($value)) {
                continue;
            }
            if (strpos($key, 'images_imagex') !== false) {
                $img_desc = $datas[str_replace('images_imagex_', 'imagex_desc_', $key)] ?: null;
                $returnValues[] = [
                    "type" => "image",
                    "key" => $key,
                    "value" => $value,
                    "paper_id" => $paper_id,
                    "depend_value" => $img_desc,
                ];
                continue;
            }

            switch ($key) {
                case 'price':
                    $returnValues[] = [
                        "type" => "price",
                        "key" => $key,
                        "value" => $value,
                        "paper_id" => $paper_id,
                        "depend_value" => null,
                    ];
                    break;
                case 'slider_data':
                    $returnValues[] = [
                        "type" => "slider_data",
                        "key" => $key,
                        "value" => $value,
                        "paper_id" => $paper_id,
                        "depend_value" => null,
                    ];
                    break;
                case 'conten':
                    $returnValues[] = [
                        "type" => "conten",
                        "key" => $key,
                        "value" => $value,
                        "paper_id" => $paper_id,
                        "depend_value" => null,
                    ];
                    break;
                case 'time_line_type':
                    break;
                case 'time_line_value':
                    $returnValues[] = [
                        "type" => "timeline",
                        "key" => $key,
                        "depend_value" => $datas['time_line_type'],
                        "value" => $value,
                        "paper_id" => $paper_id
                    ];
                default:
            }
        }
        return $returnValues;
    }

    protected function insertPaperContent($new_id)
    {
        $contents = $this->convertRequestData($new_id);
        PaperContent::insert($contents);
    }

    /**
     * @return Paper
     */
    protected function saveMainPaper()
    {
        $paper = $this->paper;
        $paper->fill([
            "title" => $this->request->get("page_title"),
            "url_alias" => $this->formatPath($this->request->get("alias") ?: $this->request->get("page_title")),
            "short_conten" => $this->request->get("short_conten"),
            "conten" => null,
            "active" => $this->request->get("active") ? true : false,
            "show" => $this->request->get("show", false) === 'on',
            "auto_hide" => $this->request->get("auto_hide") === 'on',
            "show_writer" => $this->request->get("show_writer") === 'on',
            "show_time" => $this->request->get("show_time"),
            "image_path" => $this->request->get("image_path") ?: "",
            "writer" => $this->request->get("writer")[0] ?? null,
            "type" => $this->pageType()
        ]);
        $paper->save();
        return $paper;
    }

    public function insertPaper()
    {
        $request = $this->request;
        try {
            $paper = $this->saveMainPaper();
            if ($new_id = $paper->id) {
                /**
                 * insert list content of paper.
                 */
                $this->insertPaperContent((int)$new_id);

                /**
                 * save in DB page category
                 */
                $this->insert_page_category($new_id, $request->__get("category_option"));

                /**
                 * save in DB page tags
                 */
                $this->insert_page_tag($request->__get("paper_tag"), $new_id, Paper::PAGE_TAG);

                /**
                 * save for history
                 */
                if ($request_url = $request->get("request_url")) {
                    /**
                     * save source into database.
                     */
                    $this->save_remote_source_history($request_url, self::PAGE_TYPE, $new_id, true);
                    /**
                     * log remote source of paper.
                     */
                    $this->logTha->logRemoteSource("info", urldecode($request_url));
                }
                /**
                 * push notification to mobile
                 */
                $all_fcm = $this->notification->where("active", true)->get()->toArray();
                $this->helperFunction->push_notification_json($all_fcm, $paper);
            }
            return redirect()->back()->with("success", "add success");
        } catch (Exception $e) {
            // return redirect()->back()->with("error", $e->getMessage());
            throw new Exception($e->getMessage(), 1);
        }
    }

    public function editPaper($paper_id)
    {
        /**
         * @var Paper $paper
         */
        $paper = $this->paper->find($paper_id);
        $writers = Writer::all();
        $paper_category = array_column($paper->to_category()->get(["category_id"])->toArray(), "category_id");
        $category_option = $this->category->setSelected($paper_category)->category_tree_option();
        $time_line_option = $this->category->time_line_option(array_filter($paper->to_contents()->toArray(), function ($i) {
                return $i['type'] === 'timeline';
            })[0]['depend_value'] ?? null);

        return view("adminhtml.templates.papers.edit",
            compact("paper", "writers", "category_option", "time_line_option"));
    }

    public function updatePaper($paper_id)
    {
        $paper = $this->paper->find($paper_id);
        $this->paper = $paper;
        if ($paper) {
            try {
                /**
                 * update main content.
                 */
                $this->saveMainPaper();
                /**
                 * update paper content.
                 */
                $this->delete_paper_content($paper);
                $this->insertPaperContent($paper->id);
                /**
                 * update paper category.
                 */
                $this->delete_page_category($paper);
                $this->insert_page_category($paper->id, $this->request->get("category_option"));
                /**
                 * update paper tags.
                 */
                $this->delete_page_tag($paper);
                $this->insert_page_tag($this->request->__get("paper_tag"), $paper->id, Paper::PAGE_TAG);
                return redirect()->back()->with("success", "updated success");
            } catch (Throwable $th) {
                $th->getMessage();
            }
        }
        return redirect()->back()->with("error", "update error, please try again!");
    }

    public function deletePaper()
    {
        $request = $this->request;
        try {
            if ($paper_id = $request->get("paper_id")) {
                /**
                 * @var Paper $paper
                 */
                $paper = $this->paper::find($paper_id);
                if ($paper && $paper->id) {
                    /**
                     * delete paper contents.
                     */
                    $this->delete_paper_content($paper);

                    /**
                     * remove paper tags
                     */
                    $tags = $paper->to_tag()->getResults();
                    if ($tags->count()) {
                        foreach ($tags as $tag) {
                            $tag->forceDelete();
                        }
                    }

                    /**
                     * delete paper categories.
                     */
                    $categories = $paper->to_category()->getResults();
                    if ($categories->count()) {
                        foreach ($categories as $category) {
                            $category->forceDelete();
                        }
                    }

                    /**
                     * delete paper slider carousel.
                     */
                    $this->deleteImageCarousel($paper->id);

                    /**
                     * delete main paper.
                     */
                    $paper->delete();

                    return response(json_encode([
                        "code" => "200",
                        "value" => "deleted: success!"
                    ]), 200);
                }
            }
        } catch (Throwable $th) {
            //throw $th;
        }
        return response(json_encode([
            "code" => 401,
            "value" => "delete error. Please try again!"
        ]), 401);
    }

    function deleteImageCarousel(int $paper_id): bool
    {
        try {
            DB::table('paper_carousel')->where('paper_id', $paper_id)->delete();
        } catch (Throwable $th) {
            //throw $th;
        }
        return false;
    }

    public function newByUrl()
    {
        return view("adminhtml.templates.papers.new_by_url");
    }

    /**
     * @return bool
     */
    protected function save_remote_source_history($request_url = "", $type = null, $paper_id = null, $active = true)
    {
        if (!$request_url) {
            return false;
        }
        $history = new RemoteSourceHistory(["url_value" => $request_url, "type" => $type, "paper_id" => $paper_id, "active" => $active]);
        return $history->save();
    }

    function getCommentContent($paper_id, $p, Request $request): string
    {
        $_paper = $this->paper->find($paper_id);
        $comments = $_paper->getComments(null, $p);
        $commentsHtml = view('frontend.templates.paper.component.commentHistory', ['comments' => $comments])->render();
        return $commentsHtml;
    }

    public function addComment($page_id, Request $request)
    {
        //throw new \Exception("Error Processing Request", 500);
        try {
            $comment = new Comment([
                "paper_id" => $page_id,
                "email" => $request->get("email", Auth::user()->email),
                "name" => $request->get("name", Auth::user()->name),
                "subject" => $request->get("subject"),
                "content" => $request->get("message", $request->get("content")),
                "parent_id" => $request->get("parent_id", null)
            ]);

            $comment->save();
            return response(json_encode([
                "code" => 200,
                "data" => 123
            ], 200));
        } catch (Throwable $th) {
            return response(json_encode([
                "code" => 400,
                "data" => null
            ], 500));
        }
    }

    function replyComment($comment_id, Request $request)
    {

        try {
            $comment = new Comment([
                "paper_id" => $request->get("paper_value"),
                "parent_id" => $comment_id,
                "email" => $request->get("email"),
                "name" => $request->get("name"),
                "content" => $request->get("conten")
            ]);

            $comment->save();
            return response(json_encode([
                "code" => 200,
                "data" => 123
            ], 200));
        } catch (Throwable $th) {
            //throw $th;
        }
        return response(json_encode([
            "code" => 200,
            "data" => 123
        ], 500));
    }

    function addLike($paper_id, Request $request)
    {
        $params = $request->toArray();
        $paperSource = ViewSource::where('type', '=', ViewSource::PAPER_TYPE)->where('source_id', '=', $paper_id)->first();
        if (empty($paperSource)) {
            ViewSource::firstOrCreate([
                "type" => $params['paper'],
                "source_id" => $paper_id,
                "value" => 1,
                'heart' => $params['type'] === 'heart' ? 1 : 0,
                'like' => $params['type'] === 'like' ? 1 : 0
            ]);
        } else {
            if ($params['type'] === 'like') {
                $paperSource->like = $params['action'] === 'add' ? $paperSource->like + 1 : $paperSource->like - 1;
            } elseif ($params['type'] === 'heart') {
                $paperSource->heart = $params['action'] === 'add' ? $paperSource->heart + 1 : $paperSource->heart - 1;
            }
            $paperSource->save();
        }
        return response('success');
    }

    function like($comment_id, Request $request)
    {
        $type = $request->get("type");
        if ($type == "like") {
            $comment = Comment::find($comment_id);
            $comment->like = $comment->like + 1;
            $comment->save();
            return response(json_encode([
                "code" => 200,
                "count" => $comment->like,
                "message" => ""
            ], 200));
        } elseif ($type == "dislike") {
            $comment = Comment::find($comment_id);
            $comment->like = $comment->like - 1 <= 0 ? 0 : $comment->like - 1;
            $comment->save();
            return response(json_encode([
                "code" => 200,
                "count" => $comment->like,
                "message" => ""
            ], 200));
        }
        return response(json_encode([
            "code" => 500,
            "count" => 123,
            "message" => ""
        ], 500));
    }

    function addCart(Request $request)
    {
        $this->cartService->addCart($request->get('id'));
        return redirect()->back()->with("success", "add success");
    }

    function addCartApi(Request $request)
    {
        $cartData = $this->cartService->addCart($request->get('id'));
        return $cartData;
    }

    function cart(): View
    {
        return view('frontend.templates.cart.index', ['cart' => $this->cartService->getCart()]);
    }

    function getCartApi()
    {
        return $this->cartService->getCart();
    }

    function clearCart()
    {
        $this->cartService->clearCart();
        return redirect()->back()->with("success", "clear cart success");
    }

    function clearCartApi()
    {
        $this->cartService->clearCart();
        return $this->getCartApi();
    }

    function checkout(): View
    {
        return view("frontend.templates.cart.checkout", ['cart' => $this->cartService->getCart()]);
    }

    function checkoutPro()
    {
        $order_data = $this->cartService->submitOrder();
        return redirect()->back()->with($order_data['status'] ? "success" : 'error', $order_data['message']);
    }

    function xoaItem($id)
    {
        $this->cartService->xoaItem($id);
        return redirect()->back()->with('success', "removed the item");
    }

    function removeItemApi($id)
    {
        $this->cartService->xoaItem($id);
        return $this->cartService->getCart();
    }

    function byType($type, Request $request): View
    {
        $limit = $request->get('limit', 4);
        $paper_ids = array_column(PaperContent::all()->where(PaperContent::ATTR_TYPE, $type)->slice($request->get('p', 0) * $limit, $limit)->toArray(), 'paper_id');
        $papers = Paper::find($paper_ids);
        return view('frontend.templates.paper.product', ['papers' => $papers, "type" => $type]);
    }

    function moreByType($type, Request $request): Response
    {
        $limit = $request->get('limit', 4);
        $paper_ids = array_column(PaperContent::all()->where(PaperContent::ATTR_TYPE, $type)->slice($request->get('p', 0) * $limit, $limit)->toArray(), 'paper_id');
        $papers = Paper::find($paper_ids);
        $data = view("frontend/templates/paper/component/list_category_paper", ['papers' => $papers])->render();

        return response(json_encode([
            "code" => 200,
            "data" => $data
        ]));
    }
}
