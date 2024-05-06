// AMD module whose value is a shared component viewmodel instance
define(['knockout'], function (ko) {

    function Tinhtien(params) {
        var self = this;
        self.input = ko.observable();
        self.note = ko.observable();
        self.error = ko.observable();

        self.list = ko.observableArray([]);

        self.onAdd = function(){
            if (isNaN(self.input()) || !self.input()) {
               self.error('giá trị đang nhập không hợp lệ!');
               setTimeout(function(){
                self.error(null);
               }, 2000);
               return;
            }

            self.list.push({value: self.input(), note: self.note()});
            self.input(null);
            self.note(null);
        }

        self.xoa = function(item){
            self.list.remove(item);
        }

        self.tong = ko.computed(function() { // gán thuộc tính phụ thuộc
            let tong = 0
            for (let index = 0; index < self.list().length; index++) {
                tong += Number( self.list()[index].value);
            }
            return tong;
        });
        
        self.formatTien = function(value){
            if (isNaN(value) && value) {
                return 'giá trị đang nhập không hợp lệ!';
            }
            const ttValue = value > 0 ? value : value*(-1);

            const hangTrieu = Math.floor(ttValue/1000000);
            const hangNghin = (ttValue%1000000)/1000;
            return `${value > 0 ? '' : '- '} ${hangTrieu > 0 ? hangTrieu + ' triệu' : ''} ${hangNghin > 0 ? hangNghin + ' nghìn' : ''} đồng`;
        }
    }

    return Tinhtien;
});

// nhấn Ctrl + F5 để tải lại js nếu trên trình duyệt không tự động tải lại.