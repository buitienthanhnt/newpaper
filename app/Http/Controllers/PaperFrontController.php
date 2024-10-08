<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Paper;
use App\Models\PaperContent;
use App\Models\ViewSource;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PaperFrontController extends Controller implements PaperFrontControllerInterface
{
    protected $request;
    protected $paper;
    protected $cartService;

    public function __construct(
        Request $request,
        Paper $paper,
        CartService $cartService
    )
    {
        $this->request = $request;
        $this->paper = $paper;
        $this->cartService = $cartService;
    }

    function addPaperLike($paper_id)
    {
        $params = $this->request->toArray();
        $paperSource = ViewSource::where('type', '=', ViewSource::TYPE_PAPER)->where('source_id', '=', $paper_id)->first();
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

    /**
     * @param int $paper_id
     * @param int $p
     * @return string
     */
    function getPaperComments($paper_id, $p): string
    {
        $_paper = $this->paper->find($paper_id);
        $comments = $_paper->getComments(null, $p);
        $commentsHtml = view('frontend.templates.paper.component.commentHistory', ['comments' => $comments])->render();
        return $commentsHtml;
    }

    /**
     * @param int $paper_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function addComment($paper_id)
    {
        $request = $this->request;
        try {
            $comment = new Comment([
                "paper_id" => $paper_id,
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

    /**
     * @param int $comment_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     */
    function replyComment($comment_id, )
    {
        $request = $this->request;
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

    /**
     * @param int $comment_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response|mixed
     */
    function commentLike($comment_id)
    {
        $type = $this->request->get("type");
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

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    function addCart()
    {
        $this->cartService->addCart($this->request->get('id'));
        return redirect()->back()->with("success", "add success");
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    function viewCart()
    {
        return view('frontend.templates.cart.index', ['cart' => $this->cartService->getCart()]);
    }

    /**
     * @param int $item_id
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    function deleteItem($item_id)
    {
        $this->cartService->xoaItem($item_id);
        return redirect()->back()->with('success', "removed the item");
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    function clearCart()
    {
        $this->cartService->clearCart();
        return redirect()->back()->with("success", "clear cart success");
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    function checkout()
    {
        return view("frontend.templates.cart.checkout", ['cart' => $this->cartService->getCart()]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    function checkoutPost()
    {
        $order_data = $this->cartService->submitOrder();
        return redirect()->back()->with($order_data['status'] ? "success" : 'error', $order_data['message']);
    }

    /**
     * @param string $type
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View|mixed
     */
    function getPaperByType($type)
    {
        $request = $this->request;
        $limit = $request->get('limit', 4);
        $paper_ids = array_column(PaperContent::all()->where(PaperContent::ATTR_TYPE, $type)->slice($request->get('p', 0) * $limit, $limit)->toArray(), 'paper_id');
        $papers = Paper::find($paper_ids);
        return view('frontend.templates.paper.product', ['papers' => $papers, "type" => $type]);
    }

    /**
     * @param string $type
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response|mixed
     */
    function morePaperByType($type)
    {
        $request = $this->request;
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
