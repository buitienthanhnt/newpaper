

// promies là 1 tính năng cho phép thực thi các tiến trình mất nhiều thời gian.
// nó là 1 object được truyền vào 1 hàm có 2 đối số: resolve và reject
// resolve: là trả về 1 trạng thái thành công.
// reject: là trả về 1 trạng thái lỗi.
var promies = new Promise(async (resolve, reject) => {
    // mạng không thành công vì lý do nào đó (không tìm thấy máy chủ, không có kết nối, máy chủ không phản hồi, v.v...).
    // Bất kỳ kết quả nào được trả về từ máy chủ (404, 500, v.v.) đều được coi là yêu cầu thành công theo quan điểm hứa hẹn. 
    // Về mặt khái niệm, bạn đã đưa ra một yêu cầu từ máy chủ và máy chủ đã trả lời bạn, theo quan điểm mạng, yêu cầu đã hoàn tất thành công.
    // Sau đó, bạn cần kiểm tra phản hồi thành công đó để xem liệu có loại câu trả lời bạn mong muốn hay không. 
    // Nếu bạn muốn từ chối 404, bạn có thể tự viết mã đó:
    var request = fetch('http://localhost/laravel1/public/getUserTokenData').then((response) => {
        return response.json();
    }).then(data => {
        console.log('success: ', data);
    }).catch((error) => {
        console.log('er:  ', error);
    });

    // console.log('..............', request.json());
    //    setTimeout(()=>{
    //     reject('123 Error fetching data.');
    //    }, 2000)

    // setTimeout(function(){
    //     resolve({
    //         a: 1,
    //         sta: 'return in resolve'
    //     });
    // }, 3000)
})

// promies.then((data)=>{
//     console.log(data);
// }).catch((er)=>{
//     console.log('____________', er);
// })

// const getData = async function(){
//     var data = await fetch('http://localhost/laravel1/public/getUserToken');

//     var jData = await data.json();

//     console.log('?????????????', jData);
// }

// getData();

console.log('start');

console.log('start 2');

setTimeout(function () {
    console.log('on setTimeout 2000 ms');
}, 2000)

console.log('start 3');

async function name(params) {
    console.log('start');

    console.log('start 2');

    await new Promise((resolve, reject) => {
        setTimeout(function () {
            console.log('on setTimeout 2000 ms');
            resolve();
        }, 2000)
    })

    // start 3 chỉ chạy sau khi promies đưuọc resolve.
    console.log('start 3');
}