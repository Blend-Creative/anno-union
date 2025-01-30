/**
 * Backend controller for Visual Composer
 */

(function($) {
  if (typeof vc !== 'undefined') {
    window.CustomElementView = vc.shortcode_view.extend( {
      changeShortcodeParams: function(model) {
        var params;
        var wrapper = this.$el.find('.wpb_element_wrapper');
        var wrapperContent = false;

        window.CustomElementView.__super__.changeShortcodeParams.call(this, model);
        params = _.extend({}, model.get('params'));
        _.templateSettings.interpolate = /\{\{(.+?)\}\}/g;

        // store original template in data attribute
        if (typeof this.$el.data('content') === 'undefined') {
          this.$el.attr('data-content', _.escape(wrapper.html()));
        }
        wrapperContent = _.unescape(this.$el.data('content'));

        // parse template
        if (_.isObject(params) && wrapperContent) {

          // get link title from vc_link
          if (params.link && params.link.indexOf('title:') > -1) {
            var linkTitle = params.link.substring((params.link.indexOf('title:') + 6));
            params.link_title = decodeURIComponent(str_replace('|', '', linkTitle));
          }

          // get image_url from image_id
          if (params.image_id) {
            wp.media.attachment(params.image_id).fetch().then(function (data) {
              params.image_url = wp.media.attachment(params.image_id).get('url');
              wrapper.html( _.template(wrapperContent)({params: params}) );
            });
          }

          // parse template without any external data
          else {
            wrapper.html( _.template(wrapperContent)({params: params}) );
          }

        }

      }

    } );
  }
})(window.jQuery);
