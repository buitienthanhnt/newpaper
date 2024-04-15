define([
	'require',
	'knockout'
], function (require, ko) {
	'use strict';

	function BuildUrl() {
		var self = this;

		self.getUrl = function (path = '', params = null) {
			const queryString = new URLSearchParams(params).toString();
			return baseUrl+ '/' + path + '?' + queryString;
		}

		self.getApiUrl = function(path = '', params = null){
			return this.getUrl('api' + '/' + path, params);
		}

		self.token = flashToken;

	}
	return new BuildUrl();
});