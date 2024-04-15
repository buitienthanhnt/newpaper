define([
	'require',
	'viewModal/BuildUrl'
], function (require, BuildUrl) {
	'use strict';
	function sendReply(data) {
		var element = $(data);
		let comment_id = element.attr("data-comment-id");
		let reply_conten = $("#reply-comment-" + comment_id).val();
		let reply_name = $("#reply-name-" + comment_id).val();
		let reply_email = $("#reply-email-" + comment_id).val();

		$.ajax({
			url: BuildUrl.getUrl('paper/commentReply' + "/" + comment_id),
			type: 'POST',
			contentType: 'application/json',
			data: JSON.stringify({
				_token: BuildUrl.token,
				paper_value: paper_value,
				conten: reply_conten,
				name: reply_name,
				email: reply_email
			}),
			success: function (result) {
				console.log("success");
				if (result) {
					var _data = JSON.parse(result);
					if (_data.code == 200) {
						element.parent().parent().children().first().addClass("alert-success").text("success!").show();
					} else {
						element.parent().parent().children().first().addClass("alert-warning").text("error!").show();
					}
				}
			}.bind(this),
			error: function (e) {
				element.parent().parent().children().first().addClass("alert-warning").text("error!").show();
			}
		})
	}
	return sendReply;
});
