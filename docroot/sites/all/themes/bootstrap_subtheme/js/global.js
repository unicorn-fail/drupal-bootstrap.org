+function ($, Prism) {
  var $window = $(window);

  // "Fix" Prism's commenting regex.
  // @see https://github.com/PrismJS/prism/issues/307#issuecomment-50856743
  var langs = ['clike', 'javascript', 'php'];
  for (var i = 0; i < langs.length; i++) {
    var comment;
    if (Array.isArray(Prism.languages[langs[i]].comment)) {
      comment = Prism.languages[langs[i]].comment[0];
      delete Prism.languages[langs[i]].comment[1].pattern;
    }
    else {
      comment = Prism.languages[langs[i]].comment;
    }
    comment.pattern = /(^[^"]*?("[^"]*?"[^"]*?)*?[^"\\]*?)(\/\*[\w\W]*?\*\/|(^|[^:])\/\/.*?(\r?\n|$))/g;
  }

  // Add in "JSON" language syntax.
  // @see https://github.com/PrismJS/prism/pull/370
  Prism.languages.json = {
    'property': /"(\b|\B)[\w-]+"(?=\s*:)/ig,
    'string': /"(?!:)(\\?[^'"])*?"(?!:)/g,
    'number': /\b-?(0x[\dA-Fa-f]+|\d*\.?\d+([Ee]-?\d+)?)\b/g,
    'function': {
      pattern: /[a-z0-9_]+\(/ig,
      inside: {
        punctuation: /\(/
      }
    },
    'punctuation': /[{}[\]);,]/g,
    'operator': /:/g,
    'boolean': /\b(true|false)\b/gi,
    'null': /\bnull\b/gi
  };
  Prism.languages.jsonp = Prism.languages.json;

  // Fix markdown "code" regex.
  delete Prism.languages.markdown.code;
  Prism.languages.insertBefore('markdown', 'comment', {
    'code': [
      {
        alias: 'block',
        pattern: /```[\w\W]+?```|``[\w\W]+?``/
      },
      {
        alias: 'inline',
        pattern: /`[^`\n]+`/
      }
    ]
  });

  // Alias/extend "htm" and "html" languages from "markup" language.
  Prism.languages.htm = Prism.languages.extend('markup', {});
  Prism.languages.html = Prism.languages.extend('markup', {});

  // Handle language labels.
  Prism.hooks.add('before-highlight', function(env) {
    var pre = env.element.parentNode;
    if (!pre || !/pre/i.test(pre.nodeName)) return;
    var language;
    if (/^json/i.test(env.language)) {
      language = 'JSON';
    }
    else if (/^(htm|html|markup)/i.test(env.language)) {
      language = 'HTML';
    }
    else {
      language = pre.getAttribute('data-language');
      pre.removeAttribute('data-language');
    }
    if (language) {
      $(pre).wrap('<div class="prism-wrapper" data-language="' + language + '">');
    }
  });

  var sourceHash = function () {
    var hash = location.hash.slice(1);
    var range = (hash.match(/\.([\d,-]+)$/) || [,''])[1];
    if (!range || document.getElementById(hash)) return;
    return '#' + hash.slice(0, hash.lastIndexOf('.'));
  };

  // Process "after-highlight" event.
  Prism.hooks.add('after-highlight', function (env) {
    var $element = $(env.element);

    // Ensure that the links added by the "Autolinker" plugin open in a new window.
    $element.find('a.token.url-link').attr('target', '_blank');

    // Handle code blocks.
    var pre = env.element.parentNode;
    if (!pre || !/pre/i.test(pre.nodeName)) return;

    var $pre = $element.parent();
    var links = $pre.data('links');

    // Merge the links from API module back in.
    if (links) {
      // Only replace the text inside tokens.
      $element.find('span.token').each(function () {
        var $token = $(this);
        var text = $token.html().replace(/^('|")/g, '').replace(/('|")$/g, '');
        if (links[text]) {
          var $link = $('<a>').text(text).attr(links[text]);
          $token.html($token.html().replace(new RegExp(text, 'g'), $link.wrap('<div>').parent().html()));
        }
      });
    }

    // Bind on hashchange event to offset the scrolltop a bit.
    $window.on('hashchange', function () {
      var $highlight = $(sourceHash()).find('.temporary.line-highlight');
      if (!$highlight[0]) return;
      var newTop = $window.scrollTop() - 100;
      if (newTop < 0) newTop = 0;
      $window.scrollTop(newTop);
    });

    // This is currently in a DOM ready event (page load), so if there is a hash ID present, go ahead and trigger it.
    if (sourceHash()) {
      // The Prism line-highlight plugin binds directly with addEventListener (which jQuery does not trigger).
      // We must manually trigger it via the window object.
      var triggerHashchange = function () {
        window.dispatchEvent(new HashChangeEvent('hashchange'));
      };

      // Determine if the code is inside a collapsible panel. If it is, expand it and then trigger the hashchange.
      var $toggle = $($pre.parents('fieldset').first().find('[data-toggle=collapse]').data('target'));
      if ($toggle[0]) {
        $window.on('shown.bs.collapse', triggerHashchange);
        $toggle.collapse('show');
      }
      // Otherwise trigger it now.
      else {
        triggerHashchange();
      }
    }
  });

  Prism.hooks.add('complete', function (env) {
    var pre = env.element.parentNode;
    if (!pre || !/pre/i.test(pre.nodeName)) return;

    // Show the code block (if hidden).
    var $pre = $(pre);
    if ($pre.hasClass('fade')) {
      $pre.addClass('in');
    }
  });

  // DOM ready.
  var $document = $(document);
  $document.ready(function () {
    var $body = $('body');
    var $footer = $body.find('.footer');
    var $sidebar = $body.find('.region-sidebar-second');

    // Affix the sidebar.
    $sidebar.affix({
      offset: {
        top: $sidebar.offset().top - 40,
        bottom: function () {
          return (this.bottom = $footer.outerHeight(true));
        }
      }
    });

    // Highlight code on page.
    Prism.highlightAll();

    // Set default anchor options.
    $.fn.anchor.Constructor.DEFAULTS = $.extend(true, $.fn.anchor.Constructor.DEFAULTS, {
      anchors: '.region-content h2, .region-content h3, .region-content h4, [data-anchor]',
      anchorIgnore: '.element-invisible, button, [data-anchor-ignore]:not([data-anchor-ignore="false"]),[data-dismiss],[data-slide],[data-toggle]:not([data-toggle="anchor"])'
    });

    // If there is a source hash (line highlighting), don't bind immediately.
    if (sourceHash()) {
      setTimeout(function () {
        $document.anchor();
      }, 300)
    }
    else {
      $document.anchor();
    }

  });


}(window.jQuery, window.Prism);
