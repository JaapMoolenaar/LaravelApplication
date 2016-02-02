/*
 * A basic jQuery implementation of underscores templating
 *
 * The templating function is based on http://underscorejs.org
 * Which is (c) 2009-2015 Jeremy Ashkenas, DocumentCloud and Investigative Reporters & Editors
 * It may be freely distributed under the MIT license.
 *
 * Compiles JavaScript templates into functions that can be evaluated for rendering.
 * Useful for rendering complicated bits of HTML from JSON data sources.
 *
 * Template functions can both interpolate values, using <%= … %>, as well as
 * execute arbitrary JavaScript code, with <% … %>. If you wish to interpolate
 * a value, and have it be HTML-escaped, use <%- … %>. When you evaluate a
 * template function, pass in a data object that has properties corresponding to
 * the template's free variables. The settings argument should be a hash
 * containing any $.template.defaults that should be overridden.
 *
 * Can be called on an element to use the elements contents as the template
 * source.
 *
 * <script type="text/html" id="tmp">hello: <%= name %></script>
 * $('#tmp').template('render', {name: 'moe'}); => hello: moe
 *
 * A template can be rendered to an element directly, using the 3rd and 4th
 * parameter, using the 3rd as destinatoin, and 4th as method; 'html', 'append',
 * 'prepend', etc. *
 * $('#tmp').template('render', {name: 'moe'}, $('#destination')); => hello: moe
 * $('#tmp').template('render', {name: 'moe'}, $('#destination'), 'html'); => hello: moe
 *
 * If called on a collection of 1 item, the result of a method will be returned:
 * The compiled template source can be accessed using the source method:
 * $('#tmp').template('source')
 */
;(function ($, window, document, undefined) {

    "use strict";

    var pluginName = "template",

        // When customizing `templateSettings`, if you don't want to define an
        // interpolation, evaluation or escaping regex, we need one that is
        // guaranteed not to match.
        noMatch = /(.)^/,

        // Certain characters need to be escaped so that they can be put into a
        // string literal.
        escapes = {
            "'":      "'",
            '\\':     '\\',
            '\r':     'r',
            '\n':     'n',
            '\u2028': 'u2028',
            '\u2029': 'u2029'
        },

        // The regex to match the above escapes
        escaper = /\\|'|\r|\n|\u2028|\u2029/g,

        // the function that does the actual replacement
        escapeChar = function(match) {
            return '\\' + escapes[match];
        };

    $[ pluginName ] = {}
    $[ pluginName ].defaults = {
        // By default, Underscore's template uses ERB-style template delimiters,
        // change the following template settings to use alternative delimiters.
        evaluate    : /<%([\s\S]+?)%>/g,
        interpolate : /<%=([\s\S]+?)%>/g,
        escape      : /<%-([\s\S]+?)%>/g
    };

    // JavaScript micro-templating, similar to John Resig's implementation.
    // Underscore templating handles arbitrary delimiters, preserves whitespace,
    // and correctly escapes quotes within interpolated code.
    $[ pluginName ].compileTemplate = function(text, settings) {
        settings = $.extend($[ pluginName ].defaults, settings||{});

        // Combine delimiters into one regular expression via alternation.
        var matcher = RegExp([
            (settings.escape || noMatch).source,
            (settings.interpolate || noMatch).source,
            (settings.evaluate || noMatch).source
        ].join('|') + '|$', 'g');

        // Compile the template source, escaping string literals appropriately.
        var index = 0;
        var source = "__p+='";
        text.replace(matcher, function(match, escape, interpolate, evaluate, offset) {
            source += text.slice(index, offset).replace(escaper, escapeChar);
            index = offset + match.length;

            if (escape) {
                source += "'+\n((__t=(" + escape + "))==null?'':_.escape(__t))+\n'";
            } else if (interpolate) {
                source += "'+\n((__t=(" + interpolate + "))==null?'':__t)+\n'";
            } else if (evaluate) {
                source += "';\n" + evaluate + "\n__p+='";
            }

            // Adobe VMs need the match returned to produce the correct offest.
            return match;
        });
        source += "';\n";

        // If a variable is not specified, place data values in local scope.
        if (!settings.variable) source = 'with(obj||{}){\n' + source + '}\n';

        source =
            "var __t,__p='',__j=Array.prototype.join," +
            "print=function(){__p+=__j.call(arguments,'');};\n" +
            source + 'return __p;\n';

        try {
            var render = new Function(settings.variable || 'obj', '$', source);
        } catch (e) {
            e.source = source;
            throw e;
        }

        var template = function(data) {
            return render.call(this, data, $);
        };

        // Provide the compiled source as a convenience for precompilation.
        var argument = settings.variable || 'obj';
        template.source = 'function(' + argument + '){\n' + source + '}';

        return template;
    }

    // The actual plugin constructor
    function Plugin(element, options) {
        this.element = element;
        this.settings = options;

        this.init();
    }

    // Avoid Plugin.prototype conflicts
    $.extend(Plugin.prototype, {
        compiled: function(){ return '' },
        init: function () {
            this.compiled = $[ pluginName ].compileTemplate(this.element.html(), this.settings);
        },
        public: {
            render: function(data, to, type) {
                if(to) {
                    if(typeof type === 'undefined') {
                        type = 'append';
                    }
                    $(to)[type](this.compiled(data));
                }
                return this.compiled(data);
            },
            source: function() {
                return this.compiled.source;
            }
        }
    });

    // Make sure the plugin's public methods are made available to be called
    // using .pluginname('methodname')
    for(var method in Plugin.prototype.public) {
        Plugin.prototype[method] = (function(method){
            return function() {
                return this.public[method].apply(this, Array.prototype.slice.call(arguments));
            }
        })(method);
    }

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[ pluginName ] = function () {
        var args = Array.prototype.slice.call(arguments),
            dataname = "plugin_" + pluginName,
            getInstance = function(element) {
                return element.data(dataname);
            },
            setInstance = function(element, instance) {
                element.data(dataname, instance);
                return instance;
            };

        // Call a method and return it's results if possible
        // which it only is if the funciton is called on just one element
        if(this instanceof jQuery && this.length === 1) {
            // get an instance of Plugin
            var instance = getInstance(this);

            // if the instance does not exist, create it
            if (!instance) {
                instance = setInstance(this, new Plugin(this, typeof args[0] === 'undefined' || typeof args[0] === 'object' ? args.shift() : undefined));
            }

            // See if a method has been called
            if(typeof args[0] === 'string' && typeof instance.public[args[0]] === 'function') {
                return instance.public[args.shift()].apply(instance, args);
            }

            return this;
        }
        else {
            // instantiate or call method on all elements
            return this.each(function () {
                $.fn[ pluginName ].apply($(this), args);
            });
        }
    };

})(jQuery, window, document);