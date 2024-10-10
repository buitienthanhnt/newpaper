<?php

namespace App\Http\Controllers;

use App\Helper\HelperFunction;
use App\Helper\Page;
use App\Models\Category;
use App\Models\CategoryInterface;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Paper;
use App\Models\PaperContent;
use App\Models\PaperInterface;
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
use Thanhnt\Nan\Helper\RemoteSourceManager;
use Thanhnt\Nan\Helper\StringHelper;
use Throwable;

class PaperController extends Controller implements PaperControllerInterface
{
    use Page;
    use StringHelper;

    const PAGE_TYPE = 1;

    /**
     * @var CartService $cartService
     */
    protected $cartService;
    protected $request;
    protected $paper;
    protected $category;
    protected $notification;
    protected $helperFunction;
    protected $logTha;
    protected $remoteSourceManager;

    public function __construct(
        Request $request,
        Paper $paper,
        Category $category,
        Notification $notification,
        HelperFunction $helperFunction,
        LogTha $logTha,
        CartService $cartService,
        RemoteSourceManager $remoteSourceManager
    )
    {
        $this->request = $request;
        $this->paper = $paper;
        $this->logTha = $logTha;
        $this->category = $category;
        $this->notification = $notification;
        $this->helperFunction = $helperFunction;
        $this->cartService = $cartService;
        $this->remoteSourceManager = $remoteSourceManager;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function listPaper()
    {
        $papers = $this->paper->orderBy("updated_at", "DESC")->paginate(8);
        return view("adminhtml.templates.papers.list", compact("papers"));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function createPaper()
    {
        return view("adminhtml.templates.papers.create", [
            "category_option" => $this->category->category_tree_option(),
            "filemanager_url" => url("adminhtml/file/manager") . "?editor=tinymce5",
            "time_line_option" => $this->category->time_line_option(),
            "writers" => Writer::all()
        ]);
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
            PaperInterface::ATTR_TITLE => $this->request->get("page_title"),
            PaperInterface::ATTR_URL_ALIAS => $this->formatPath($this->request->get("alias") ?: $this->request->get("page_title")),
            PaperInterface::ATTR_SHORT_CONTENT => $this->request->get("short_conten"),
            PaperInterface::ATTR_IMAGE_PATH => $this->request->get("image_path") ?: "",
            PaperInterface::ATTR_ACTIVE => $this->request->get("active") ? true : false,
            PaperInterface::ATTR_SHOW => $this->request->get("show", false) === 'on',
            PaperInterface::ATTR_AUTO_HIDE => $this->request->get("auto_hide") === 'on',
            PaperInterface::ATTR_SHOW_TIME => $this->request->get("show_time"),
            PaperInterface::ATTR_SHOW_WRITER => $this->request->get("show_writer") === 'on',
            PaperInterface::ATTR_WRITER => $this->request->get("writer")[0] ?? null,
        ]);
        $paper->save();
        return $paper;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
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
                $this->insert_page_tag($request->__get("paper_tag"), $new_id, Paper::PAPER_TAG);

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

    /**
     * @param int $paper_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View|mixed
     */
    public function editPaper($paper_id)
    {
        /**
         * @var Paper $paper
         */
        $paper = $this->paper->find($paper_id);
        $writers = Writer::all();
        $filemanager_url = url("adminhtml/file/manager") . "?editor=tinymce5";
        $filemanager_url_base = url("adminhtml/file/manager");
        $category_option = $this->category->setSelected($paper->listIdCategories())->category_tree_option();
        $time_line_option = $this->category->time_line_option(array_filter($paper->getContents()->toArray(), function ($i) {
                return $i['type'] === 'timeline';
            })[0]['depend_value'] ?? null);

        return view("adminhtml.templates.papers.edit",
            compact("paper", "writers", "category_option", "filemanager_url", "filemanager_url_base", "time_line_option"));
    }

    /**
     * @param int $paper_id
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
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
                $this->insert_page_tag($this->request->__get("paper_tag"), $paper->id, Paper::PAPER_TAG);
                return redirect()->back()->with("success", "updated success");
            } catch (Throwable $th) {
                $th->getMessage();
            }
        }
        return redirect()->back()->with("error", "update error, please try again!");
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
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
                    $tags = $paper->get_tags();
                    if ($tags->count()) {
                        foreach ($tags as $tag) {
                            $tag->forceDelete();
                        }
                    }

                    /**
                     * delete paper categories.
                     */
                    $categories = $paper->getPaperCategories();
                    if ($categories->count()) {
                        foreach ($categories as $category) {
                            $category->forceDelete();
                        }
                    }

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

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function newByUrl()
    {
        return view("adminhtml.templates.papers.new_by_url");
    }

    public function sourcePaper()
    {
        $request = $this->request;
        $value = $this->remoteSourceManager->source($request);
        if (!$value) {
            return redirect()->back()->with("error", "can not parse source!");
        } else {
            /**
             * get write for
             */
            $writers = Writer::all();
            $values = [
                "value" => $value,
                "category_option" => $this->category->category_tree_option(),
                "filemanager_url" => url("adminhtml/file/manager") . "?editor=tinymce5",
                "filemanager_url_base" => url("adminhtml/file/manager"),
                "time_line_option" => $this->category->time_line_option(),
                "writers" => $writers,
            ];
            return view("adminhtml.templates.papers.create", $values);
        }
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
}
