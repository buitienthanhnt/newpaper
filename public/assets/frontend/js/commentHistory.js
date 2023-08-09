function like(element, commment_id ,type = "like") {
	$.ajax({
		url: like_url+`/${commment_id}`,
		type: 'POST',
		contentType: 'application/json',
		data: JSON.stringify({
			_token: token,
			type: type
		}),
		success: function(result) {
			console.log("success");
			if (result) {
				var _data = JSON.parse(result);
				if (_data.code == 200) {
					console.log(_data);
					$(element).siblings().css("background-color", "");
					$(element).attr("disabled", true).css("background-color", "rebeccapurple");
					$(element).siblings().attr("disabled", false);
					$(element).parent().siblings().first().children().last().text(`${_data.count == 0 ? "" : _data.count + ' Likes'}`);
					// element.parent().parent().children().first().addClass("alert-success").text("success!").show();
				} else {
					// element.parent().parent().children().first().addClass("alert-warning").text("error!").show();
				}
			}
		}.bind(this),
		error: function(e) {
			// element.parent().parent().children().first().addClass("alert-warning").text("error!").show();
		}
	})
}
