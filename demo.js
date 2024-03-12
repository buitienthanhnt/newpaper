let allField = [
    {
        title: "Lần đầu công bố video tăng Leopard bị phá hủy",
        id: 18,
        url_alias: "lan-dau-cong-bo-video-tang-leopard-bi-pha-huy",
        short_conten: "Bộ Quốc phòng Nga vừa lần đầu công bố đoạn video ghi lại hình ảnh xe tăng hạng nặng Leopard của Ukraine bị phá hủy.",
        active: 1,
        show: 1,
        show_time: null,
        image_path: "http://192.168.100.156/laravel1/public/storage/files/shares/images up/suc-manh-hai-mau-truc-thang-vu-trang-nga-duoc-san-don-nhat-Hinh-8.jpg",
        tag: null,
        auto_hide: 0,
        writer: "1",
        show_writer: 0,
        deleted_at: null,
        created_at: "2023-06-08T06:50:26.000000Z",
        updated_at: "2023-06-08T06:50:26.000000Z",
        type: "content",
        info: {
           view_count: "41",
           comment_count: 3,
           like: "3",
           heart: "1"
        }
     }
];

// radio active = 2
// khi chuyển sang 1:

// b1: lấy các field có depend của button 

// b2: lấy các field có depend của button đó với value != 1;

// b3: loại bỏ các field đó trong all tạo newActive.
// b4: gán active = newActive.(vấn đề là chỉ kiểm tra được 1 nút trên thôi.)



// trong active đang có các field đúng.
// b3-2 loại bỏ các field b2 trong active.
// lúc này active key chưa có key mới.
// b-4-2 lấy tất cả các file trong active cùng với các field có depend value = 1 từ all để tạo new active




