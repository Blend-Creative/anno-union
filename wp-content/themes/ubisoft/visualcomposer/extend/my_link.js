!function($) {
  var $current_el = null;
  var $input = null;

  function onMyLinkDialogClose(e) {
    $('#wp-link-submit').off('click', onMyLinkDialogSubmit);
    $('#wp-link-cancel, #wp-link-close').off('click', onMyLinkDialogClose);

    wpLink.textarea = $('body');
    wpLink.close();
    e.preventDefault();
    e.stopPropagation();

    $current_el = $input = null;

    return false;
  }

  function onMyLinkDialogSubmit(e) {
    $('#wp-link-submit').off('click', onMyLinkDialogSubmit);
    $('#wp-link-cancel, #wp-link-close').off('click', onMyLinkDialogClose);

    var linkAtts = wpLink.getAttrs();
    var linkText = $('#wp-link-text').val();
    linkAtts.text = linkText;

    for (var k in window.vc_url_mappping) {
      if (window.vc_url_mappping[k] === linkAtts.href) {
        linkAtts.id = k;
      }
    }

    $current_el.find('.my_link_title').text(linkText);
    $current_el.find('.my_link_url').text(linkAtts.href);
    $input.val(JSON.stringify(linkAtts));

    wpLink.textarea = $('body');
    wpLink.close();
    e.preventDefault();
    e.stopPropagation();

    $current_el = $input = null;

    return false;
  }

  $('.my_link_block').each(function () {
    var link_val = $(this).find('.my_link_field').val();
    link_val = '' === link_val ?
      {
        text: '-',
        href: '-',
        target: ''
      } :
      JSON.parse(link_val);

    var href = link_val.hasOwnProperty('id') ? window.vc_url_mappping[link_val.id] : link_val.href;
    $(this).find('.my_link_title').text(link_val.text);
    $(this).find('.my_link_url').text(href);
  });

  $('#vc_ui-panel-edit-element .button.my_link_button').on('click', function (e) {
    e.preventDefault();

    $current_el = $(this).parents('.my_link_block');
    $input = $current_el.find('.my_link_field');

    var values = $input.val();
    values = '' === values ?
      {
        text: '',
        href: '',
        target: ''
      } :
      JSON.parse(values);

    if ('-' === values.text) {
      values.text = '';
    }

    if (values.hasOwnProperty('id')) {
      values.href = window.vc_url_mappping[values.id];
    }

    if ('-' === values.href) {
      values.href = '';
    }

    wpLink.open(null, values.href, values.text);
    $('#wp-link-url').val(values.href);
    $('#wp-link-target').prop('checked', (values.target === "_blank"));

    $('#wp-link-submit').on('click', onMyLinkDialogSubmit);
    $('#wp-link-cancel, #wp-link-close').on('click', onMyLinkDialogClose);

    return false;
  });
}(window.jQuery);