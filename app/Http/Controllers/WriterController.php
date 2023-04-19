<?php

namespace App\Http\Controllers;

use App\Helper\ImageUpload;
use App\Models\Writer;
use Illuminate\Http\Request;

class WriterController extends Controller
{
    use ImageUpload;
    protected $request;
    protected $writer;

    public function __construct(
        Request $request,
        Writer $writer
    ) {
        $this->request = $request;
        $this->writer = $writer;
    }

    public function listOfWriter()
    {
        if (view()->exists('adminhtml.templates.writer.list')) {
            return view("adminhtml.templates.writer.list");
        } else {
            return redirect("adminhtml")->with("not_page", 1);
        }
    }

    public function createWriter()
    {
        if (view()->exists('adminhtml.templates.writer.create')) {
            return view("adminhtml.templates.writer.create");
        } else {
            redirect("admin")->with("no_router", 1);
        }
    }

    public function insertWriter()
    {
        $request = $this->request;
        $image_upload_path = $this->uploadImage($request, "public/images/writer", "images/resize/writer");

        $writer = $this->writer;
        $writer->fill([
            "name" => $request->__get("name"),
            "email" => $request->__get("email"),
            "phone" => $request->__get("phone"),
            "address" => $request->__get("address"),
            "image_path" => $image_upload_path ? $image_upload_path["file_path"] ?? $image_upload_path : null,
            "name_alias" => $request->__get("name_alias"),
            "active" => $request->__get("active") ?: true,
            "date_of_birth" => date('Y-m-d H:i:s',strtotime($request->__get("date_of_birth"))),
            "good" => $request->__get("good") ?: null
        ]);

        $result = $writer->save();
        if ($result) {
            return redirect()->back()->with("success", "created new writer");
        }else {
            return redirect()->back()->with("error", "create fail!!, please try again.");
        }
    }

    public function editWriter()
    {
        // $this->resize("");
    }
}
// customer_custom_email:
// items[0][order_item_id]: 7114181
// items[0][qty_requested]: 1
// items[0][wrong_size]: 9449
// items[0][wrong_order]: 9464
// defect_0: (binary)
// defect_0_value:
// items[1][order_item_id]: 7114181
// items[1][qty_requested]: 1
// items[1][wrong_size]: 9452
// items[1][wrong_order]: 9470
// defect_1: (binary)
// defect_1_value:
// send_by_your_own: 1
// rma_comment:
// form_key: sVzugqMZEZpDLmZm

// curl --location --request POST 'https://subdued.int.jmango.net/it_en//japi/rest_customer/orderReturnSubmit?order_id=1403457' \
// --header 'Authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJTSEEyNTYifQ.eyJkYXRhIjoidm5IVW9ITU5ZbDYxOFwvYVg4d3dQanJPK3NHSW1HWGlwUytoeVwvXC9RODVyVnBwRnVPa2VtXC9HNm1oT0ZDRkpxRkFCN0hXdEJLV2pPU3VobmNnS3l4Q0FzVzVZTGM0V0F1dFg3bGgrb2xJTFNnc2NmdVNHUlJzVXI2V1d5dDI3WElnIiwiaWF0IjoxNjgxODc2MTA2LCJleHAiOjE2ODE4NzcwMDYsImNpZCI6IjEyNDk3NTMiLCJjZ2lkIjoiMyJ9.lu7BNMfVwnJS3RaiWy8y980XCFGGhEh5L_AcWgdGuS0' \
// --header 'Cookie: PHPSESSID=grcb7vamrcclb0bstbtjuvco3m; authentication_flag=true; dataservices_cart_id=%2215917602%22; dataservices_customer_group=%7B%22customerGroupCode%22%3A%2277de68daecd823babbb58edb1c8e14d7106e83bb%22%7D; dataservices_customer_id=%221249753%22; smclient=086eb680-32af-4054-b660-c8606f580cba' \
// --form 'items[0][order_item_id]="7114181"' \
// --form 'items[0][qty_requested]="1"' \
// --form 'items[0][wrong_size]="9449"' \
// --form 'items[0][wrong_order]="9467"' \
// --form 'send_by_your_own="1"' \
// --form 'rma_comment="ppp"' \
// --form 'defect_0="(binary)"' \
// --form 'defect_0_value=@"/C:/Users/admin/Pictures/556.PNG"' \
// --form 'customer_custom_email=""'
