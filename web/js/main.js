/**
 * Created by OzY on 11.09.2015.
 */
$(document).ready(function(){
    /*$("a[class='ContentLink']").click(function(e){
     //заглушка чтобы нельзя было в меню кликнуть с нажатым ctrl
     if (e.ctrlKey) { return false; }
     console.log($(this).attr('href'));
     return false;
     });*/
    var downloadObjects = new downloadObject();
});

var downloadObject = function(){

    var self = this;
    this.btnItem = '';
    this.active = false;

    /**
     * выполняется во время инициализации класса
     */
    this.Init = function () {
        self.domCreate();
    };

    /**
     * функция вызывается
     */
    this.domCreate = function ()
    {
        self.btnItem = $("a[class='download']");

        //подуключаем события
        if (self.active == false) {
            //console.log(self.btnItem);return;
            self.btnItem.click(function(){
               // contentObject.contentLoad( '/content/index', '', 'contentIndex' );
                self.downloadAjax(2000, 0, 2, '');
            });

            self.active = true;
        }
    };

    this.downloadAjax = function(limit, offset, i, filename) {
        $.AbortedAjax('download', {
            type: 'POST',
            url: '/web/index.php?r=download%2Fdownload',
            data: {
                'company' : self.btnItem.attr('company'),
                'limit':limit,
                'offset':offset,
                'filename':filename,
                'i':i
            },
            success: function(data)
            {
                //var obj = jQuery.parseJSON(data);
                //if(obj.limit > 0 && obj.offset > 0) {
                //    self.downloadAjax(obj.limit, obj.offset, obj.i, obj.filename);
                //    return false;
               // } else {
                //    console.log(obj);
                    return false;
               // }

                //self.downloadAjax();
                // $('#content').html(data);
            },
            error: function(data) {
               // console.log(data);
               // return false;
            }
        });
    };

    self.Init();
};

jQuery.AbortedAjax = function(
    requestId,
    url,
    type,
    dataType,
    data,
    complete){

    if(jQuery.AbortedAjaxQueries == undefined) jQuery.AbortedAjaxQueries = [];
    if(jQuery.AbortedAjaxQueries[requestId] != undefined) jQuery.AbortedAjaxQueries[requestId].abort();

    if(complete == undefined)
        jQuery.AbortedAjaxQueries[requestId] = jQuery.ajax(url, type, dataType, data)
    else
        jQuery.AbortedAjaxQueries[requestId] = jQuery.ajax(url, type, dataType, data, complete);

    return jQuery.AbortedAjaxQueries[requestId];

};
