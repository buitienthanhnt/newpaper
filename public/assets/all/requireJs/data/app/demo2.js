define(['require', 'knockout'], function (require, ko) { // khai báo như này không được đặt tên nếu không sẽ bị lỗi.
	// Jquery được sử dụng như 1 biến toàn cục nên không cần thêm trên phần phụ thuộc.
	// mà vẫn sử dụng bình thường.
	// nếu đưa Jquery vào phần phụ thuộc có thể gay xung đột với các vị trí khác.
	var p1 = $('#platform');
	console.log(p1.html());
	
	return ko.components.register('like-or-dislike', {
		viewModel: { require: 'viewModal/component-like-widget' },
		template: { require: 'text!templates/component-like-widget.html' }
	});
});