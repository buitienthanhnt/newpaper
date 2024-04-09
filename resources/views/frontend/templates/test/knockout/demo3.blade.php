@extends('frontend.templates.test.knockout.baseTemplate')

@section('head_after_js')
@endsection

@section('content_page')
    <div>
        
    </div>
@endsection

@section('js_after')
    <script type="text/javascript">
        // https://stackoverflow.com/questions/39079389/requirejs-with-knockout
        // https://www.c-sharpcorner.com/UploadFile/dbd951/how-to-use-requirejs-with-knockoutjs-in-Asp-Net-mvc4/
        // https://hgminerva.wordpress.com/2013/11/01/multiple-knockout-js-model-in-one-page-master-child-relationship-page/
        // https://stackoverflow.com/questions/29513891/how-to-bind-several-view-models-in-a-single-page-using-knockout
        // AMD module 'some/module.js' encapsulating the configuration for a component

        var $koNamespace = {};
        var $koItemModel;
        var $koItemPriceModel;

        $koNamespace.initItem = function(Item) {
            var self = this;

            self.Id = ko.observable(!Item ? 0 : Item.Id);
            self.ArticleId = ko.observable(!Item ? 0 : Item.ArticleId);
            self.AccountId = ko.observable(!Item ? 0 : Item.AccountId);
            self.UnitId = ko.observable(!Item ? 0 : Item.UnitId);
            self.PurchaseTaxId = ko.observable(!Item ? 0 : Item.PurchaseTaxId);
            self.SalesTaxId = ko.observable(!Item ? 0 : Item.SalesTaxId);
            self.ItemCode = ko.observable(!Item ? "" : Item.ItemCode);
            self.Item = ko.observable(!Item ? "" : Item.Item);
            self.BarCode = ko.observable(!Item ? "" : Item.BarCode);
            self.Category = ko.observable(!Item ? "" : Item.Category);
            self.Unit = ko.observable(!Item ? "" : Item.Unit);
            self.Account = ko.observable(!Item ? "" : Item.Account);
            self.PurchaseTax = ko.observable(!Item ? "" : Item.PurchaseTax);
            self.SalesTax = ko.observable(!Item ? "" : Item.SalesTax);
            self.Remarks = ko.observable(!Item ? "" : Item.Remarks);
            self.IsAsset = ko.observable(!Item ? false : Item.IsAsset);

            return self;
        };

        $koNamespace.initItemPrice = function(ItemPrice) {
            var self = this;

            self.Id = ko.observable(!ItemPrice ? 0 : ItemPrice.Id);
            self.ArticleId = ko.observable(!ItemPrice ? 0 : ItemPrice.ArticleId);
            self.PriceDescription = ko.observable(!ItemPrice ? "NA" : ItemPrice.PriceDescription);
            self.Price = ko.observable(!ItemPrice ? 0 : ItemPrice.Price);

            return self;
        }

        // Item Binding
        $koNamespace.bindItem = function(Item) {
            var viewModel = $koNamespace.initItem(Item);
            ko.applyBindings(viewModel, $("#ItemDetail")[0]); //Bind the section #ItemDetail
            $koItemModel = viewModel;
        }

        // Item Price Binding
        $koNamespace.bindItemPrice = function(ItemPrice) {
            try {
                var viewModel = $koNamespace.initItemPrice(ItemPrice);
                ko.applyBindings(viewModel, $("#itemPriceDetail")[0]); //Bind the section #itemPriceDetail (Modal)
                $koItemPriceModel = viewModel;
            } catch (e) {
                $koItemPriceModel['Id'](!ItemPrice ? 0 : ItemPrice.Id);
                $koItemPriceModel['ArticleId'](!ItemPrice ? 0 : ItemPrice.ArticleId);
                $koItemPriceModel['PriceDescription'](!ItemPrice ? "" : ItemPrice.PriceDescription);
                $koItemPriceModel['Price'](!ItemPrice ? 0 : ItemPrice.Price);
            }
        }
    </script>
@endsection
