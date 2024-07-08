<?php

namespace App\Http\Controllers;

use App\Helper\HelperFunction;
use App\Helper\Page;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Paper;
use App\Models\PaperTimeLine;
use App\Models\RemoteSourceHistory;
use App\Models\ViewSource;
use App\Models\Writer;
use App\Services\CartService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Thanhnt\Nan\Helper\LogTha;

class PaperController extends Controller
{
    use Page;

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
    ) {
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

    public function insertPaper()
    {
        $request = $this->request;
        $category_option = $request->__get("category_option");
        try {
            $paper = $this->paper;
            $paper->fill([
                "title" => $request->__get("page_title"),
                "url_alias" => $request->__get("alias") ? str_replace(" ", "-", $request->__get("alias")) : str_replace(" ", "-", $request->get("page_title")),
                "short_conten" => $request->__get("short_conten"),
                "conten" => $request->__get("conten"),
                "active" => $request->__get("active") ? true : false,
                "show" => $request->get("show", false) === 'on',
                "auto_hide" => $request->__get("auto_hide") === 'on',
                "show_writer" => $request->__get("show_writer") === 'on',
                "show_time" => $request->__get("show_time"),
                "image_path" => $request->__get("image_path") ?: "",
                "writer" => $request->get("writer", null),
                "type" => $this->pageType()
            ]);
            $paper->save();
            if ($new_id = $paper->id) {

                /**
                 * insert paper list image of carousel.
                 */
                if ($request->get('slider_data') && $listImages = json_decode($request->get('slider_data'))) {
                    DB::table('paper_carousel')->insert(array_map(function ($item) use ($new_id) {
                        return [
                            "title" => $item->title,
                            "description" => $item->label,
                            "value" => $item->image_path,
                            "paper_id" => $new_id
                        ];
                    }, $listImages));
                }
                /**
                 * save in DB page category
                 */
                $this->insert_page_category($new_id, $category_option);

                /**
                 * insert for paper to timeline
                 */
                if ($time_line_type = $this->request->get('time_line_type') && $time_line_value = $this->request->get('time_line_value')) {
                    $this->insert_paper_timeline($new_id, $time_line_type, $time_line_value);
                }
                /**
                 * save in DB page tags
                 */
                $this->insert_page_tag($request->__get("paper_tag"), $new_id, Paper::PAGE_TAG);

                /**
                 * save price for paper
                 */
                $this->insert_paper_price($request->__get("price"), $new_id);

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
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 1);
        }
        return redirect()->back()->with("error", "add error");
    }

    function insert_paper_timeline($paper_id, $timeline_id, $value)
    {
        $paper_timeline = $this->paper_timeline;
        $paper_timeline->fill([
            'paper_id' => $paper_id,
            'timeline_id' => $timeline_id,
            'timeline_value' => Carbon::createFromFormat('Y-m-d H:i:s', $value)
        ])->save();
    }

    public function editPaper($paper_id)
    {
        $paper = $this->paper->find($paper_id);
        $writers = Writer::all();
        $filemanager_url = url("adminhtml/file/manager") . "?editor=tinymce5";
        $filemanager_url_base = url("adminhtml/file/manager");
        $paper_category = array_column($paper->to_category()->get(["category_id"])->toArray(), "category_id");
        $category_option = $this->category->setSelected($paper_category)->category_tree_option();

        return view("adminhtml.templates.papers.edit", compact("paper", "writers", "category_option", "filemanager_url", "filemanager_url_base"));
    }

    public function updatePaper($paper_id)
    {
        $paper = $this->paper->find($paper_id);
        $request = $this->request;
        if ($paper) {
            try {
                $paper->fill([
                    "title" => $request->__get("page_title"),
                    "url_alias" => $request->__get("alias") ?: str_replace(" ", "_", $request->get("page_title")),
                    "short_conten" => $request->__get("short_conten"),
                    "conten" => $request->__get("conten"),
                    "active" => $request->__get("active") ? true : false,
                    "show" => $request->__get("show") ? true : false,
                    "auto_hide" => $request->__get("auto_hide") ? true : false,
                    "show_writer" => $request->__get("show_writer") ? true : false,
                    "show_time" => $request->__get("show_time"),
                    "image_path" => $request->__get("image_path") ?: "",
                    "writer" => $request->get("writer", null)
                ]);
                $paper->save();
                if ($new_id = $paper->id) {
                    $this->delete_page_category($paper);
                    $this->insert_page_category($new_id, $request->get("category_option"));
                    $this->delete_page_tag($paper);
                    $this->insert_page_tag($request->__get("paper_tag"), $new_id, Paper::PAGE_TAG);
                }
                return redirect()->back()->with("success", "updated success");
            } catch (\Throwable $th) {
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
                $paper = $this->paper::find($paper_id);
                if ($paper && $paper->id) {
                    $tags = $paper->to_tag()->getResults();
                    $categories = $paper->to_category()->getResults();
                    if ($tags->count()) {
                        foreach ($tags as $tag) {
                            $tag->forceDelete();
                        }
                    }
                    if ($categories->count()) {
                        foreach ($categories as $category) {
                            $category->forceDelete();
                        }
                    }
                    /**
                     * delete paper slider carousel.
                     */
                    $this->deleteImageCarousel($paper->id);
                    $paper->delete();
                    return response(json_encode([
                        "code" => "200",
                        "value" => "deleted: success!"
                    ]), 200);
                }
            }
        } catch (\Throwable $th) {
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
        } catch (\Throwable $th) {
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
        // save for history
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
        } catch (\Throwable $th) {
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
        } catch (\Throwable $th) {
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
        if ($params['type'] === 'like') {
            $paperSource->like = $params['action'] === 'add' ? $paperSource->like + 1 : $paperSource->like - 1;
        } elseif ($params['type'] === 'heart') {
            $paperSource->heart = $params['action'] === 'add' ? $paperSource->heart + 1 : $paperSource->heart - 1;
        }
        $paperSource->save();
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

    function cart(): View
    {
        return view('frontend.templates.cart.index', ['cart' => $this->cartService->getCart()]);
    }

    function clearCart()
    {
        $this->cartService->clearCart();
        return redirect()->back()->with("success", "clear cart success");
    }

    function checkout(): View
    {
        return view("frontend.templates.cart.checkout", ['cart' => $this->cartService->getCart()]);
    }

    function checkoutPro() {
        $order_data = $this->cartService->submitOrder();
        return redirect()->back()->with($order_data['status'] ? "success" : 'error', $order_data['message']);
    }

    function xoaItem($id)
    {
        $this->cartService->xoaItem($id);
        return redirect()->back()->with('success', "removed the item");
    }

    function byType($type, Request $request): View
    {
        $limit = $request->get('limit', 4);
        $papers = Paper::all()->where("type", "=", $type)->slice($request->get('p', 0) * $limit, $limit);
        return view('frontend.templates.paper.product', ['papers' => $papers, "type" => $type]);
    }

    function moreByType($type, Request $request): Response
    {
        $limit = $request->get('limit', 4);
        $papers = Paper::all()->where("type", "=", $type)->slice($request->get('p', 0) * $limit, $limit);
        $data = view("frontend/templates/paper/component/list_category_paper", ['papers' => $papers])->render();

        return response(json_encode([
            "code" => 200,
            "data" => $data
        ]));
    }
}
