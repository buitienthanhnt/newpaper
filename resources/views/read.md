============================== 1 sweet alert =======================================================
dùng sweet alert trong blade.php
    @if($message = session('success'))
        <?php alert()->success('Title','Lorem Lorem Lorem'); ?>
    @endif

    @if($message = session('success'))
        <?php alert()->success('server message', $message);?>
    @elseif ($error = session("error"))
        <?php alert()->fail('server mesage',$error);?>
    @endif

hoặc:
    <script>
        @if($message = session('succes_message'))
            swal("{{ $message }}");
        @endif
    </script>
============================= 2 định dạng thời gian cho timestamp ==================================
    "date_of_birth" => date('Y-m-d H:i:s',strtotime($request->__get("date_of_birth")))
====================================================================================================
public_path(): lấy đường dẫn trỏ vào thư mục public.
public_path("/storage/images/writer/"): sẽ ánh xạ vào trong thư mục storage/app/public/images/writer 
====================================================================================================
  window.location.href = url; chuyển hướng tới 1 request bằng js.
===================================================================================================
sweet alert: https://sweetalert2.github.io/#declarative-templates
===================================================================================================