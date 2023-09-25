<?php

namespace App\Http\Controllers;

use App\Helper\DomHtml;
use App\Models\Category;
use App\Models\ConfigCategory;
use App\Models\PageTag;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Session\Store;

class ManagerController extends Controller
{
    use DomHtml;

    protected $request;
    protected $paper;
    protected $category;
    protected $pageTag;
    const URI = "192.168.4.113";                  // jm-destop
    // const URI = "192.168.1.214";                    // m6800
    const URI2 = "192.168.1.150/laravel1/public";   // mochi-m4700

    public function __construct(
        Request $request,
        Paper $paper,
        Category $category,
        PageTag $pageTag
    ) {
        $this->request = $request;
        $this->paper = $paper;
        $this->category = $category;
        $this->pageTag = $pageTag;
    }

    public function homePage()
    {
        $list_center = [];
        $list_center_conten = [];
        $most_recent = null;
        $most_popular = null;
        $trendings = null;
        $weekly3_contens = null;
        $video_contens = null;
        $trending_left = $this->paper->orderBy("updated_at", "DESC")->take(3)->get();
        $trending_right = $this->paper->orderBy("updated_at", "DESC")->take(2)->get();
        $center_category = ConfigCategory::where("path", "center_category")->firstOr(function () {
            return null;
        });
        if ($center_category) {
            $list_center = Category::find(explode("&", $center_category->value));
            $list_papers = [];
            foreach ($list_center as $center) {
                $page_category = $center->to_page_category()->getResults()->toArray();
                $list_papers = array_unique([...array_column($page_category, "page_id"), ...$list_papers]);
            }
            if ($list_papers) {
                $list_center_conten = $this->paper->whereIn("id", $list_papers)->get();
            }
        }

        $most_recent = $this->paper->orderBy("updated_at", "ASC")->take(3)->get();
        $most_popular =  $this->paper->take(8)->get();
        $video_contens = $this->paper->orderBy("updated_at", "DESC")->take(3)->get();
        $weekly3_contens = $trendings = $this->paper->orderBy("updated_at", "DESC")->take(8)->get();

        return view("frontend/templates/homeconten", compact("trending_left", "trending_right", "list_center", "most_recent", "most_popular", "trendings", "weekly3_contens", "video_contens"));
    }

    public function pageDetail($alias, $page)
    {
        $paper = $this->paper->find($page);
        $category = Category::where("url_alias", "like", "today")->get()->first(); // lys
        $list_center = Category::where("url_alias", "like", 2)->take(4)->get();
        $papers = $category->get_papers(4, 0, $order_by = ["updated_at", "DESC"]);
        $top_paper = $papers->take(2);
        $papers = $papers->diff($top_paper);
        return view("frontend.templates.paper.paper_detail", compact("paper", "list_center", "top_paper", "papers"));
    }

    public function categoryView($category_id)
    {
        $category = Category::where("url_alias", "like", $category_id)->get()->first();

        $list_center = [];
        $list_center_conten = [];
        $most_recent = null;
        $most_popular = null;
        $trendings = null;
        $weekly3_contens = null;
        $video_contens = null;
        $trending_left = $this->paper->orderBy("updated_at", "DESC")->take(3)->get();
        $trending_right = $this->paper->orderBy("updated_at", "DESC")->take(2)->get();
        $center_category = ConfigCategory::where("path", "center_category")->firstOr(function () {
            return null;
        });
        if ($center_category) {
            $list_center = Category::find(explode("&", $center_category->value));
            $list_papers = [];
            foreach ($list_center as $center) {
                $page_category = $center->to_page_category()->getResults()->toArray();
                $list_papers = array_unique([...array_column($page_category, "page_id"), ...$list_papers]);
            }
            if ($list_papers) {
                $list_center_conten = $this->paper->whereIn("id", $list_papers)->get();
            }
        }

        $most_recent = $this->paper->orderBy("updated_at", "ASC")->take(3)->get();
        $most_popular =  $this->paper->take(6)->get();
        $weekly3_contens = $this->paper->take(8)->orderBy("updated_at", "DESC")->get();
        $list_center = Category::where("url_alias", "like", $category_id)->take(4)->get();
        $papers = $category->get_papers(4, 0, $order_by = ["updated_at", "DESC"]);
        $top_paper = $papers->take(2);
        $papers = $papers->diff($top_paper);
        return view("frontend/templates/categories", compact("category", "top_paper", "papers", "trending_left", "trending_right", "list_center", "most_recent", "most_popular", "weekly3_contens"));
    }

    public function tagView($value)
    {
        $papers = null;
        $paper_ids = $this->pageTag->to_paper($value);
        if ($paper_ids) {
            $papers = $this->paper::whereIn("id", $paper_ids)->get();
        }
        $trending_left = $papers->first();
        return view("frontend/templates/tags", ["tag" => $value, "papers" => $papers, "trending_left" => [$trending_left]]);
    }

    public function load_more()
    {
        $request = $this->request;
        $type = $request->get("type");
        $page = $request->get("page");
        if ($type) {
            $category = $this->category->where("url_alias", "like", $type)->first();
            $papers = $category->get_papers(4, $page);
        }
        $data = view("frontend/templates/paper/component/list_category_paper", ['papers' => $papers])->render();

        return response(json_encode([
            "code" => 200,
            "data" => $data
        ]));
    }

    function apiSourcePapers(Request $request)
    {
        // $papers = Paper::paginate($request->get("limit",  4))->orderBy("updated_at", "DESC");
        $papers = $this->paper->orderBy('updated_at', 'desc')->paginate(4);
        $data = $papers->toArray();
        if ($data["data"]) {
            foreach ($data["data"] as &$item) {
                $asset_path = "/newpaper/public/assets/";   // http:://192.168.100.210/newpaper/public/asset/pub_image/defaul.PNG
                $item["image_path"] = $item["image_path"] ? str_replace("localhost", self::URI, $item["image_path"]) : "http://" . self::URI . $asset_path . "pub_image/defaul.PNG";     // windown jmm-desk
                //                $item["image_path"] = $item["image_path"] ? str_replace("laravel1.com", self::URI2, $item["image_path"]) : "http://".self::URI2."/assets/pub_image/defaul.PNG"; // ubuntu m4700

                $item["short_conten"] = $this->cut_str($item["short_conten"], 90, "...");
                //                 $item["title"] = $this->cut_str($item["title"], 80, "../");
            }
        }
        return $data;
    }

    public function getPaperDetail($paper_id)
    {
        return $this->paper->find($paper_id);
    }

    public function getCategoryTop()
    {
        $top_category = ConfigCategory::where("path", "=", ConfigCategory::TOP_CATEGORY);
        $values = Category::whereIn("id", explode("&", $top_category->first()->value))->get()->toArray();
        $asset_path = "/newpaper/public/assets/";
        foreach ($values as &$value) {
            //            $value["image_path"] = $value["image_path"] ? str_replace("laravel1.com", self::URI2, $value["image_path"]) : "http://".self::URI2."/assets/pub_image/defaul.PNG"; // ubuntu m4700
            $value["image_path"] = $value["image_path"] ? str_replace("localhost", self::URI, $value["image_path"]) : "http://" . self::URI . $asset_path . "pub_image/defaul.PNG";     // windown jmm-desk
        }
        return $values;
    }

    public function getPaperCategory($category_id, Request $request)
    {
        $category = $this->category->find($category_id);
        $papers = $category->setSelectKey(["id", "title", "short_conten", "image_path"])->get_papers($request->get("limit", 4), $request->get("page", 1) - 1)->toArray();
        $asset_path = "/newpaper/public/assets/";
        foreach ($papers as &$value) {
            //            $value["image_path"] = $value["image_path"] ? str_replace("laravel1.com", self::URI2, $value["image_path"]) : "http://".self::URI2."/assets/pub_image/defaul.PNG";  // ubutnu m4700
            $value["image_path"] = $value["image_path"] ? str_replace("localhost", self::URI, $value["image_path"]) : "http://" . self::URI . $asset_path . "pub_image/defaul.PNG";      // windown jmm-desk
        }
        return $papers;
    }

    function getRelatedPaper()
    {
        $papers = Paper::all()->random(5)->toArray();
        $asset_path = "/newpaper/public/assets/";
        foreach ($papers as &$value) {
            //            $value["image_path"] = $value["image_path"] ? str_replace("laravel1.com", self::URI2, $value["image_path"]) : "http://".self::URI2."/assets/pub_image/defaul.PNG"; // ubuntu m4700
            $value["image_path"] = $value["image_path"] ? str_replace("localhost", self::URI, $value["image_path"]) : "http://" . self::URI . $asset_path . "pub_image/defaul.PNG";     // windown jmm-desk
        }
        return ['data' => $papers];
    }

    function getCategoryTree(Request $request)
    {
        $category_id = $request->get("category_id", 0);
        if ($category_id) {
            $category = $this->category->find($category_id);
            $categories = $category->getCategoryTree();
        } else {
            $categories = $this->category->getCategoryTree(true);
        }
        return $categories;
    }

    function parseUrl(Request $request) {
        $url = $request->get('url', 'tuyen-viet-nam-dau-hong-kong-hlv-troussier-gay-bat-ngo');
        $paper = Paper::where('url_alias', '=', $url)->first();
        return $paper;
    }

    function getAdetail()
    {
        $data = '
        {
            "data": {
                "site": {
                    "product": {
                        "id": "UHJvZHVjdDoyODQ1",
                        "entityId": 2845,
                        "name": "Clean element blazer - dark blue/off white - 08962",
                        "sku": "089626911",
                        "plainTextDescription": "De Clean element blazer van Studio Anneloes is een klassiek jasje met een tijdloze print. Vanwege de Medium travelstof...",
                        "variants": {
                            "edges": [
                                {
                                    "node": {
                                        "upc": "8719749964635",
                                        "sku": "089626911020",
                                        "inventory": {
                                            "isInStock": true
                                        },
                                        "options": {
                                            "edges": [
                                                {
                                                    "node": {
                                                        "entityId": 2844,
                                                        "displayName": "Maat",
                                                        "values": {
                                                            "edges": [
                                                                {
                                                                    "node": {
                                                                        "entityId": 15948,
                                                                        "label": "XS"
                                                                    }
                                                                }
                                                            ]
                                                        }
                                                    }
                                                }
                                            ]
                                        }
                                    }
                                },
                                {
                                    "node": {
                                        "upc": "8719749964642",
                                        "sku": "089626911030",
                                        "inventory": {
                                            "isInStock": true
                                        },
                                        "options": {
                                            "edges": [
                                                {
                                                    "node": {
                                                        "entityId": 2844,
                                                        "displayName": "Maat",
                                                        "values": {
                                                            "edges": [
                                                                {
                                                                    "node": {
                                                                        "entityId": 15949,
                                                                        "label": "S"
                                                                    }
                                                                }
                                                            ]
                                                        }
                                                    }
                                                }
                                            ]
                                        }
                                    }
                                },
                                {
                                    "node": {
                                        "upc": "8719749964659",
                                        "sku": "089626911040",
                                        "inventory": {
                                            "isInStock": true
                                        },
                                        "options": {
                                            "edges": [
                                                {
                                                    "node": {
                                                        "entityId": 2844,
                                                        "displayName": "Maat",
                                                        "values": {
                                                            "edges": [
                                                                {
                                                                    "node": {
                                                                        "entityId": 15950,
                                                                        "label": "M"
                                                                    }
                                                                }
                                                            ]
                                                        }
                                                    }
                                                }
                                            ]
                                        }
                                    }
                                },
                                {
                                    "node": {
                                        "upc": "8719749964666",
                                        "sku": "089626911050",
                                        "inventory": {
                                            "isInStock": true
                                        },
                                        "options": {
                                            "edges": [
                                                {
                                                    "node": {
                                                        "entityId": 2844,
                                                        "displayName": "Maat",
                                                        "values": {
                                                            "edges": [
                                                                {
                                                                    "node": {
                                                                        "entityId": 15951,
                                                                        "label": "L"
                                                                    }
                                                                }
                                                            ]
                                                        }
                                                    }
                                                }
                                            ]
                                        }
                                    }
                                },
                                {
                                    "node": {
                                        "upc": "8719749964673",
                                        "sku": "089626911060",
                                        "inventory": {
                                            "isInStock": true
                                        },
                                        "options": {
                                            "edges": [
                                                {
                                                    "node": {
                                                        "entityId": 2844,
                                                        "displayName": "Maat",
                                                        "values": {
                                                            "edges": [
                                                                {
                                                                    "node": {
                                                                        "entityId": 15952,
                                                                        "label": "XL"
                                                                    }
                                                                }
                                                            ]
                                                        }
                                                    }
                                                }
                                            ]
                                        }
                                    }
                                },
                                {
                                    "node": {
                                        "upc": "8719749964680",
                                        "sku": "089626911070",
                                        "inventory": {
                                            "isInStock": true
                                        },
                                        "options": {
                                            "edges": [
                                                {
                                                    "node": {
                                                        "entityId": 2844,
                                                        "displayName": "Maat",
                                                        "values": {
                                                            "edges": [
                                                                {
                                                                    "node": {
                                                                        "entityId": 15953,
                                                                        "label": "XXL"
                                                                    }
                                                                }
                                                            ]
                                                        }
                                                    }
                                                }
                                            ]
                                        }
                                    }
                                }
                            ]
                        }
                    }
                }
            }
        }';
        return $data;
    }

    function getStores()
    {
        // return;
        $stores = '
            [
                {
                    "store": {
                        "id": 2,
                        "code": "21",
                        "name": "Topsy",
                        "addressLine1": "Zeestraat 9",
                        "addressLine2": "",
                        "zipCode": "1131 ZD",
                        "city": "Volendam",
                        "isoCountryCode": "NL",
                        "phoneNumber": "0299 369 099",
                        "email": "info@topsy-fashion.nl",
                        "geoLatitude": 52.493539000,
                        "geoLongitude": 5.074221000,
                        "storeDetailUrl": null,
                        "websiteUrl": "www.topsy-fashion.nl",
                        "info": "Topsy Fashion, in hartje Volendam, is verdeeld over 2 panden en maar liefst 4 etages waardoor er ruimte is voor een breed assortiment voor de vrouw die op zoek is naar trendy collecties. Persoonlijke aandacht van onze verkoopster vinden wij zeer belangrijk en zij geven je onder het genot van een kopje koffie of thee graag stijladvies op basis van je behoeftes. Door verschillende corners voor iedere doelgroep te creeëren, beleef je 6 verschillende werelden onder 1 dak. Door de combinatie van een groot aantal vaste merken en maandelijkse trips naar Parijs of Bologna om de laatste trends op te pikken, bieden wij een unieke mix van fashion tegen een scherpe prijs tot duurdere kleding waarmee je op ieder feest terecht kunt.",
                        "storeTypes": [
                            "StandardPointOfSale"
                        ]
                    },
                    "stock": 2,
                    "latestStockDate": "2023-08-22T00:00:00"
                },
                {
                    "store": {
                        "id": 14,
                        "code": "35",
                        "name": "Kraan mode",
                        "addressLine1": "Aalsmeerderweg 213",
                        "addressLine2": "",
                        "zipCode": "1432 CM",
                        "city": "Aalsmeer",
                        "isoCountryCode": "NL",
                        "phoneNumber": "0297 324 394",
                        "email": "info@kraanmode.nl",
                        "geoLatitude": 52.278111000,
                        "geoLongitude": 4.790023100,
                        "storeDetailUrl": "",
                        "websiteUrl": "www.kraanmode.nl",
                        "info": "Kraan Mode in Aalsmeer heeft de ambitie om de leukste winkel van de regio te zijn! Je vindt er meer dan 40 trendy damesmodemerken onder één dak. Een inspirerende mix van grote merken en kleine, originele labels. Winkelen bij Kraan Mode is een beleving. Je kunt er even de tijd nemen voor een goede kop koffie of een mooi gesprek. Maar heb je haast, dan wordt je snel en efficiënt geadviseerd. Studio Anneloes heeft bij Kraan een speciaal plekje. Dat kun je gerust letterlijk nemen: in de winkel is een speciale Studio Anneloes-shop waar de grote basiscollectie bijna wekelijks wordt aangevuld met nieuwe items. Regelmatig is er in de winkel een bijzonder evenement. De Studio Anneloes-dagen met meet & greets met Anneloes zelf, zijn altijd een groot succes!",
                        "storeTypes": [
                            "StandardPointOfSale",
                            "SaAtWork"
                        ]
                    },
                    "stock": 1,
                    "latestStockDate": "2023-08-22T00:00:00"
                },
                {
                    "store": {
                        "id": 184,
                        "code": "255",
                        "name": "Outfit",
                        "addressLine1": "Oudestraat 29",
                        "addressLine2": "",
                        "zipCode": "9401 EH",
                        "city": "Assen",
                        "isoCountryCode": "NL",
                        "phoneNumber": "06 55376466",
                        "email": "vraag@outfitmode.nl ",
                        "geoLatitude": 52.995655799,
                        "geoLongitude": 6.561776900,
                        "storeDetailUrl": null,
                        "websiteUrl": "www.outfitmode.nl",
                        "info": "Ze hebben véééél kleding van topmerken en wisselen hun collectie voortdurend. Daarom is OUTFIT Assen bij ieder bezoek weer een snoepwinkel vol stijl. Elke dag weten nieuwe én vaste klanten hun winkel te vinden. Hun winkel is niet standaard en hun stylisten… de diva’s van Assen. OUTFIT Assen is de plek waar je echt gezien wordt zoals je bent, een unieke combi van luxe items en toffe basics en een winkel voor de merkbewuste vrouw. Winkelen is bij hun echt een feestje. En daarnaast is het gewoon supergezellig.",
                        "storeTypes": [
                            "StandardPointOfSale"
                        ]
                    },
                    "stock": 1,
                    "latestStockDate": "2023-08-22T00:00:00"
                },
                {
                    "store": {
                        "id": 187,
                        "code": "258",
                        "name": "Outfit",
                        "addressLine1": "Gorecht-Oost 37",
                        "addressLine2": "",
                        "zipCode": "9603AA",
                        "city": "Hoogezand",
                        "isoCountryCode": "NL",
                        "phoneNumber": "06 55376466",
                        "email": "vraag@outfitmode.nl",
                        "geoLatitude": 53.155937000,
                        "geoLongitude": 6.756634000,
                        "storeDetailUrl": null,
                        "websiteUrl": "www.outfitmode.nl",
                        "info": "",
                        "storeTypes": [
                            "StandardPointOfSale"
                        ]
                    },
                    "stock": 1,
                    "latestStockDate": "2023-08-22T00:00:00"
                }
            ]';

        return $stores;
    }
}
