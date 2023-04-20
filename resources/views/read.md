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
sử dụng:
<script type="text/javascript">
        // $('.show_confirm').click(function(event) {
        //     var form = $(this).closest("form");
        //     var name = $(this).data("name");
        //     var url = "{{ route('admin_writer_delete') }}";
        //     event.preventDefault();

        //     Swal.fire({
        //         title: 'Are you sure?',
        //         text: "You won't be able to revert this!",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Yes, delete it!'
        //     }).then((result) => {
        //         if (result.value) {
        //             console.log(result);
        //             console.log(url);
        //             // window.location.href = url;

        //             // var xhr = new XMLHttpRequest();
        //             // xhr.open("POST", url, true);
        //             // xhr.setRequestHeader('Content-Type', 'application/json');
        //             // xhr.send();

        //             // fetch(url, {
        //             //     method: 'POST',
        //             //     headers: {
        //             //         'Accept': 'application/json',
        //             //         'Content-Type': 'application/json'
        //             //     },
        //             //     body: JSON.stringify({
        //             //         "id": 78912
        //             //     })
        //             // })
        //             result.isConfirmed = 1;
        //         }

        //         if (result.isConfirmed) {
        //             Swal.fire(
        //                 'Deleted!',
        //                 'Your file has been deleted.',
        //                 'success'
        //             )
        //         }
        //     });
        //     return;
        // });
    </script>
===================================================================================================
