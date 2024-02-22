var SebAutocompleter = {
    url: null,
    div: null,
    divinside: null,
    resultlist: null,
    resultlistid: null,
    inputid: null,
    input: null,
    options: null,
    currentRequest: null,
    currentElement: -1,
    init: function (input, url, options) {
        this.url = url;
        this.input = jQuery(input);
        this.inputid = this.input.attr('id');
        this.resultlistid = this.inputid + '_res_list';
        this.resultlist = jQuery('#' + this.resultlistid);
        this.options = options;
        this.build();
    },
    build: function () {
        this.input
                .on('keyup', {self: this}, this.inputkeypress)
                .on('change input submit', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                });
    },
    inputkeypress: function (e) {
        var self = e.data.self;
        if (e.keyCode == 40) { // keydown
            if (self.currentElement == self.resultlist.children('li').length) {
                self.currentElement = 1;
            } else {
                self.currentElement++;
            }
            self.resultlist.children('li').removeClass('active');
            self.resultlist.children('li:nth-child(' + self.currentElement + ')').addClass(self.options.activeitem)
        } else if (e.keyCode == 38) {  //key up
            if (self.currentElement >= 1) {
                self.currentElement--;
            }
            if (self.currentElement == 0) {
                self.resultlist.children('li').removeClass(self.options.activeitem);
                self.input.focus();
                let len = self.input.val().length * 2;
                self.input[0].setSelectionRange(len, len); //moves cursor to end of line
            } else {
                self.resultlist.children('li').removeClass(self.options.activeitem);
                self.resultlist.children('li:nth-child(' + self.currentElement + ')').addClass(self.options.activeitem);
            }
        } else if (e.keyCode == 13) { // enter
            e.preventDefault();
            if (self.currentElement > 0) {
                self.resultlist.children('li:nth-child(' + self.currentElement + ')').click();
            }
            return false;
        } else {
            if (self.input.val().length < self.options.minsearchstr) {
                self.resultlist.empty();
                return false;
            }
            self.search();
        }
    },
    search: function () {
        self = this;
        self.currentRequest = jQuery.ajax({
            url: self.url,
            data: {
                search: self.input.val(),
                maxresults: self.options.maxresults
            },
            encoding: self.options.encoding,
            type: 'post',
            dataType: 'json',
            cache: false,
            headers: {
                'X-CSRF-Token': self.options.csrf
            },
            beforeSend: function () {    // abort previous request.
                if (self.currentRequest != null) {
                    self.currentRequest.abort();
                }
            }
        }).done(function (data) {
            self.resultlist.empty();
            self.currentElement = 0;
            if (data.res.length > 0) {
                for (var i = 0; i < data.res.length; i++) {
                    let obj = data.res[i];
                    txt = (self.options.id_included ? obj[self.options.id_field] + ': ' : '') + obj[self.options.list_field];
                    let li = jQuery('<li></li>')
                            .addClass(self.options.resultlistclassitem)
                            .data(obj)
                            .html(txt)
                            .on('click', {self: self}, self.itemclick)
                            .on('mouseover', function () {
                                jQuery(this).addClass(self.options.activeitem)
                            })
                            .on('mouseout', function () {
                                jQuery(this).removeClass(self.options.activeitem)
                            });
                    self.resultlist.append(li);
                }
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            if (jqXHR.status == 419) {
                location.refresh();
            }
        });
    },
    itemclick: function (e) {
        e.preventDefault();
        var self = e.data.self;
        self.options.callback(jQuery(this).data());
        self.resultlist.empty();
        self.input.val('');
        return false;
    }
};

if (typeof Object.create !== 'function') {
    Object.create = function (o) {
        function F() { } // optionally move this outside the declaration and into a closure if you need more speed.
        F.prototype = o;
        return new F();
    };
}
// table builder function
(function (jQuery) {
    /* Create plugin */
    jQuery.fn.sebautocompleter = function (url, options) {
        return this.each(function () {
            var element = jQuery(this);
            if (element.prop('tagName') != 'INPUT')
                throw 'not a INPUT';
            // Return early if this element already has a plugin instance
            if (element.data('sebautocompleter'))
                return element.data('sebautocompleter');
            var sebautocompleter = Object.create(SebAutocompleter);
            sebautocompleter.init(this, url, options);
            // pass options to plugin constructor
            element.data('sebautocompleter', sebautocompleter);
        });
    };
})(jQuery);
