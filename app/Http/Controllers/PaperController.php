<?php
namespace App\Http\Controllers;

use App\Helper\HelperFunction;
use App\Helper\Page;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Paper;
use App\Models\PaperContent;
use App\Models\PaperContentInterface;
use App\Models\PaperInterface;
use App\Models\RemoteSourceHistory;
use App\Models\RemoteSourceHistoryInterface;
use App\Models\Writer;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Thanhnt\Nan\Helper\LogTha;
use Thanhnt\Nan\Helper\RemoteSourceManager;
use Thanhnt\Nan\Helper\StringHelper;
use Exception;
use Throwable;

class PaperController extends Controller implements PaperControllerInterface
{
    use Page;
    use StringHelper;

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
        return view("adminhtml.templates.papers.create");
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
                case PaperContentInterface::TYPE_PRICE:
                    $returnValues[] = [
                        "type" => "price",
                        "key" => $key,
                        "value" => $value,
                        "paper_id" => $paper_id,
                        "depend_value" => null,
                    ];
                    break;
                case PaperContentInterface::TYPE_SLIDER:
                    $returnValues[] = [
                        "type" => "slider_data",
                        "key" => $key,
                        "value" => $value,
                        "paper_id" => $paper_id,
                        "depend_value" => null,
                    ];
                    break;
                case PaperContentInterface::TYPE_CONTENT:
                    $returnValues[] = [
                        "type" => "conten",
                        "key" => $key,
                        "value" => $value,
                        "paper_id" => $paper_id,
                        "depend_value" => null,
                    ];
                    break;
                case PaperContentInterface::TYPE_TIMELINE_DEPEND:
                    break;
                case PaperContentInterface::TYPE_TIMELINE:
                    $returnValues[] = [
                        "type" => "timeline",
                        "key" => $key,
                        "depend_value" => $datas[PaperContentInterface::TYPE_TIMELINE_DEPEND],
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
            PaperInterface::ATTR_TITLE => $this->request->get(PaperInterface::ATTR_TITLE),
            PaperInterface::ATTR_URL_ALIAS => $this->formatPath($this->request->get(PaperInterface::ATTR_URL_ALIAS) ?: $this->request->get(PaperInterface::ATTR_TITLE)),
            PaperInterface::ATTR_SHORT_CONTENT => $this->request->get(PaperInterface::ATTR_SHORT_CONTENT),
            PaperInterface::ATTR_IMAGE_PATH => $this->request->get(PaperInterface::ATTR_IMAGE_PATH) ?: "",
            PaperInterface::ATTR_ACTIVE => $this->request->get(PaperInterface::ATTR_ACTIVE) ? true : false,
            PaperInterface::ATTR_SHOW => $this->request->get(PaperInterface::ATTR_SHOW, false) === 'on',
            PaperInterface::ATTR_AUTO_HIDE => $this->request->get(PaperInterface::ATTR_AUTO_HIDE) === 'on',
            PaperInterface::ATTR_SHOW_TIME => $this->request->get(PaperInterface::ATTR_SHOW_TIME),
            PaperInterface::ATTR_SHOW_WRITER => $this->request->get(PaperInterface::ATTR_SHOW_WRITER) === 'on',
            PaperInterface::ATTR_WRITER => $this->request->get(PaperInterface::ATTR_WRITER)[0] ?? null,
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
                $this->insertPaperCategory($new_id, $request->__get(PaperInterface::EX_ATTR_CATEGORY));

                /**
                 * save in DB page tags
                 */
                $this->insertTags($request->__get(PaperInterface::EX_ATTR_TAGS), $new_id, Paper::EX_ATTR_TAGS);

                /**
                 * save for history
                 */
                if ($request_url = $request->get("source_request")) {
                    /**
                     * save source into database.
                     */
                    $this->saveRemoteSourceHistory($request_url, RemoteSourceHistoryInterface::TYPE_PAPER, $new_id, true);
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
        return view("adminhtml.templates.papers.edit", compact("paper"));
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
                $this->insertPaperCategory($paper->id, $this->request->get(PaperInterface::EX_ATTR_CATEGORY));
                /**
                 * update paper tags.
                 */
                $this->delete_page_tag($paper);
                $this->insertTags($this->request->__get(Paper::EX_ATTR_TAGS), $paper->id, Paper::EX_ATTR_TAGS);
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
                    $tags = $paper->getTags();
                    if ($tags->count()) {
                        foreach ($tags as $tag) {
                            $tag->forceDelete();
                        }
                    }

                    /**
                     * delete paper categories.
                     */
                    foreach ($paper->getPaperCategories() as $category) {
                        $category->forceDelete();
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
            "code" => 405,
            "value" => "delete error. Please try again!"
        ]), 405);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function newByUrl()
    {
        return view("adminhtml.templates.papers.new_by_url");
    }

    /**
     * create paper by remote source
     */
    public function sourcePaper()
    {
        $value = $this->remoteSourceManager->source($this->request);
        if (!$value) {
            return redirect()->back()->with("error", "can not parse source!");
        } else {
            $value['source_request'] = $this->request->get('source_request');
            return view("adminhtml.templates.papers.create", ["value" => $value]);
        }
    }

    /**
     * @param string $request_url
     * @param string|int $type
     * @param int $paper_id
     * @param bool $active
     * @return bool
     */
    protected function saveRemoteSourceHistory(string $request_url, $type = RemoteSourceHistoryInterface::TYPE_PAPER, $paper_id = null, $active = true)
    {
        $history = new RemoteSourceHistory([
            RemoteSourceHistoryInterface::ATTR_URL_VALUE => $request_url, 
            RemoteSourceHistoryInterface::ATTR_TYPE => $type, 
            RemoteSourceHistoryInterface::ATTR_PAPER_ID => $paper_id, 
            RemoteSourceHistoryInterface::ATTR_ACTIVE => $active
        ]);
        return $history->save();
    }
}
