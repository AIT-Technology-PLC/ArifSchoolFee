/**
 * Data can often be a complicated mix of numbers and letters (file names
 * are a common example) and sorting them in a natural manner is quite a
 * difficult problem.
 *
 * Fortunately a deal of work has already been done in this area by other
 * authors - the following plug-in uses the [naturalSort() function by Jim
 * Palmer](http://www.overset.com/2008/09/01/javascript-natural-sort-algorithm-with-unicode-support) to provide natural sorting in DataTables.
 *
 *  @name Natural sorting
 *  @summary Sort data with a mix of numbers and letters _naturally_.
 *  @author [Jim Palmer](http://www.overset.com/2008/09/01/javascript-natural-sort-algorithm-with-unicode-support)
 *  @author [Michael Buehler] (https://github.com/AnimusMachina)
 *
 *  @example
 *    $('#example').dataTable( {
 *       columnDefs: [
 *         { type: 'natural', targets: 0 }
 *       ]
 *    } );
 *
 *    Html can be stripped from sorting by using 'natural-nohtml' such as
 *
 *    $('#example').dataTable( {
 *       columnDefs: [
 *    	   { type: 'natural-nohtml', targets: 0 }
 *       ]
 *    } );
 *
 */

(function () {
    /*
     * Natural Sort algorithm for Javascript - Version 0.7 - Released under MIT license
     * Author: Jim Palmer (based on chunking idea from Dave Koelle)
     * Contributors: Mike Grier (mgrier.com), Clint Priest, Kyle Adams, guillermo
     * See: http://js-naturalsort.googlecode.com/svn/trunk/naturalSort.js
     */
    function naturalSort(a, b, html) {
        var re = /(^-?[0-9]+(\.?[0-9]*)[df]?e?[0-9]?%?$|^0x[0-9a-f]+$|[0-9]+)/gi,
            sre = /(^[ ]*|[ ]*$)/g,
            dre = /(^([\w ]+,?[\w ]+)?[\w ]+,?[\w ]+\d+:\d+(:\d+)?[\w ]?|^\d{1,4}[\/\-]\d{1,4}[\/\-]\d{1,4}|^\w+, \w+ \d+, \d{4})/,
            hre = /^0x[0-9a-f]+$/i,
            ore = /^0/,
            htmre = /(<([^>]+)>)/gi,
            // convert all to strings and trim()
            x = a.toString().replace(sre, "") || "",
            y = b.toString().replace(sre, "") || "";
        // remove html from strings if desired
        if (!html) {
            x = x.replace(htmre, "");
            y = y.replace(htmre, "");
        }
        // chunk/tokenize
        var xN = x
                .replace(re, "\0$1\0")
                .replace(/\0$/, "")
                .replace(/^\0/, "")
                .split("\0"),
            yN = y
                .replace(re, "\0$1\0")
                .replace(/\0$/, "")
                .replace(/^\0/, "")
                .split("\0"),
            // numeric, hex or date detection
            xD =
                parseInt(x.match(hre), 10) ||
                (xN.length !== 1 && x.match(dre) && Date.parse(x)),
            yD =
                parseInt(y.match(hre), 10) ||
                (xD && y.match(dre) && Date.parse(y)) ||
                null;

        // first try and sort Hex codes or Dates
        if (yD) {
            if (xD < yD) {
                return -1;
            } else if (xD > yD) {
                return 1;
            }
        }

        // natural sorting through split numeric strings and default strings
        for (
            var cLoc = 0, numS = Math.max(xN.length, yN.length);
            cLoc < numS;
            cLoc++
        ) {
            // find floats not starting with '0', string or 0 if not defined (Clint Priest)
            var oFxNcL =
                (!(xN[cLoc] || "").match(ore) && parseFloat(xN[cLoc], 10)) ||
                xN[cLoc] ||
                0;
            var oFyNcL =
                (!(yN[cLoc] || "").match(ore) && parseFloat(yN[cLoc], 10)) ||
                yN[cLoc] ||
                0;
            // handle numeric vs string comparison - number < string - (Kyle Adams)
            if (isNaN(oFxNcL) !== isNaN(oFyNcL)) {
                return isNaN(oFxNcL) ? 1 : -1;
            }
            // rely on string comparison if different types - i.e. '02' < 2 != '02' < '2'
            else if (typeof oFxNcL !== typeof oFyNcL) {
                oFxNcL += "";
                oFyNcL += "";
            }
            if (oFxNcL < oFyNcL) {
                return -1;
            }
            if (oFxNcL > oFyNcL) {
                return 1;
            }
        }
        return 0;
    }

    jQuery.extend(jQuery.fn.dataTableExt.oSort, {
        "natural-asc": function (a, b) {
            return naturalSort(a, b, true);
        },

        "natural-desc": function (a, b) {
            return naturalSort(a, b, true) * -1;
        },

        "natural-nohtml-asc": function (a, b) {
            return naturalSort(a, b, false);
        },

        "natural-nohtml-desc": function (a, b) {
            return naturalSort(a, b, false) * -1;
        },

        "natural-ci-asc": function (a, b) {
            a = a.toString().toLowerCase();
            b = b.toString().toLowerCase();

            return naturalSort(a, b, true);
        },

        "natural-ci-desc": function (a, b) {
            a = a.toString().toLowerCase();
            b = b.toString().toLowerCase();

            return naturalSort(a, b, true) * -1;
        },
    });
})();

// dataTables.buttons.min.js
/*!
 Buttons for DataTables 1.7.0
 ©2016-2021 SpryMedia Ltd - datatables.net/license
*/
(function (f) {
    "function" === typeof define && define.amd
        ? define(["jquery", "datatables.net"], function (A) {
              return f(A, window, document);
          })
        : "object" === typeof exports
        ? (module.exports = function (A, y) {
              A || (A = window);
              (y && y.fn.dataTable) || (y = require("datatables.net")(A, y).$);
              return f(y, A, A.document);
          })
        : f(jQuery, window, document);
})(function (f, A, y, t) {
    function E(a, b, c) {
        f.fn.animate
            ? a.stop().fadeIn(b, c)
            : (a.css("display", "block"), c && c.call(a));
    }
    function F(a, b, c) {
        f.fn.animate
            ? a.stop().fadeOut(b, c)
            : (a.css("display", "none"), c && c.call(a));
    }
    function H(a, b) {
        a = new q.Api(a);
        b = b ? b : a.init().buttons || q.defaults.buttons;
        return new u(a, b).container();
    }
    var q = f.fn.dataTable,
        M = 0,
        N = 0,
        z = q.ext.buttons,
        u = function (a, b) {
            if (!(this instanceof u))
                return function (c) {
                    return new u(c, a).container();
                };
            "undefined" === typeof b && (b = {});
            !0 === b && (b = {});
            Array.isArray(b) && (b = { buttons: b });
            this.c = f.extend(!0, {}, u.defaults, b);
            b.buttons && (this.c.buttons = b.buttons);
            this.s = {
                dt: new q.Api(a),
                buttons: [],
                listenKeys: "",
                namespace: "dtb" + M++,
            };
            this.dom = {
                container: f("<" + this.c.dom.container.tag + "/>").addClass(
                    this.c.dom.container.className
                ),
            };
            this._constructor();
        };
    f.extend(u.prototype, {
        action: function (a, b) {
            a = this._nodeToButton(a);
            if (b === t) return a.conf.action;
            a.conf.action = b;
            return this;
        },
        active: function (a, b) {
            var c = this._nodeToButton(a);
            a = this.c.dom.button.active;
            c = f(c.node);
            if (b === t) return c.hasClass(a);
            c.toggleClass(a, b === t ? !0 : b);
            return this;
        },
        add: function (a, b) {
            var c = this.s.buttons;
            if ("string" === typeof b) {
                b = b.split("-");
                var d = this.s;
                c = 0;
                for (var e = b.length - 1; c < e; c++) d = d.buttons[1 * b[c]];
                c = d.buttons;
                b = 1 * b[b.length - 1];
            }
            this._expandButton(c, a, d !== t, b);
            this._draw();
            return this;
        },
        container: function () {
            return this.dom.container;
        },
        disable: function (a) {
            a = this._nodeToButton(a);
            f(a.node).addClass(this.c.dom.button.disabled).attr("disabled", !0);
            return this;
        },
        destroy: function () {
            f("body").off("keyup." + this.s.namespace);
            var a = this.s.buttons.slice(),
                b;
            var c = 0;
            for (b = a.length; c < b; c++) this.remove(a[c].node);
            this.dom.container.remove();
            a = this.s.dt.settings()[0];
            c = 0;
            for (b = a.length; c < b; c++)
                if (a.inst === this) {
                    a.splice(c, 1);
                    break;
                }
            return this;
        },
        enable: function (a, b) {
            if (!1 === b) return this.disable(a);
            a = this._nodeToButton(a);
            f(a.node)
                .removeClass(this.c.dom.button.disabled)
                .removeAttr("disabled");
            return this;
        },
        name: function () {
            return this.c.name;
        },
        node: function (a) {
            if (!a) return this.dom.container;
            a = this._nodeToButton(a);
            return f(a.node);
        },
        processing: function (a, b) {
            var c = this.s.dt,
                d = this._nodeToButton(a);
            if (b === t) return f(d.node).hasClass("processing");
            f(d.node).toggleClass("processing", b);
            f(c.table().node()).triggerHandler("buttons-processing.dt", [
                b,
                c.button(a),
                c,
                f(a),
                d.conf,
            ]);
            return this;
        },
        remove: function (a) {
            var b = this._nodeToButton(a),
                c = this._nodeToHost(a),
                d = this.s.dt;
            if (b.buttons.length)
                for (var e = b.buttons.length - 1; 0 <= e; e--)
                    this.remove(b.buttons[e].node);
            b.conf.destroy && b.conf.destroy.call(d.button(a), d, f(a), b.conf);
            this._removeKey(b.conf);
            f(b.node).remove();
            a = f.inArray(b, c);
            c.splice(a, 1);
            return this;
        },
        text: function (a, b) {
            var c = this._nodeToButton(a);
            a = this.c.dom.collection.buttonLiner;
            a =
                c.inCollection && a && a.tag
                    ? a.tag
                    : this.c.dom.buttonLiner.tag;
            var d = this.s.dt,
                e = f(c.node),
                h = function (m) {
                    return "function" === typeof m ? m(d, e, c.conf) : m;
                };
            if (b === t) return h(c.conf.text);
            c.conf.text = b;
            a ? e.children(a).html(h(b)) : e.html(h(b));
            return this;
        },
        _constructor: function () {
            var a = this,
                b = this.s.dt,
                c = b.settings()[0],
                d = this.c.buttons;
            c._buttons || (c._buttons = []);
            c._buttons.push({ inst: this, name: this.c.name });
            for (var e = 0, h = d.length; e < h; e++) this.add(d[e]);
            b.on("destroy", function (m, g) {
                g === c && a.destroy();
            });
            f("body").on("keyup." + this.s.namespace, function (m) {
                if (!y.activeElement || y.activeElement === y.body) {
                    var g = String.fromCharCode(m.keyCode).toLowerCase();
                    -1 !== a.s.listenKeys.toLowerCase().indexOf(g) &&
                        a._keypress(g, m);
                }
            });
        },
        _addKey: function (a) {
            a.key &&
                (this.s.listenKeys += f.isPlainObject(a.key)
                    ? a.key.key
                    : a.key);
        },
        _draw: function (a, b) {
            a || ((a = this.dom.container), (b = this.s.buttons));
            a.children().detach();
            for (var c = 0, d = b.length; c < d; c++)
                a.append(b[c].inserter),
                    a.append(" "),
                    b[c].buttons &&
                        b[c].buttons.length &&
                        this._draw(b[c].collection, b[c].buttons);
        },
        _expandButton: function (a, b, c, d) {
            var e = this.s.dt,
                h = 0;
            b = Array.isArray(b) ? b : [b];
            for (var m = 0, g = b.length; m < g; m++) {
                var l = this._resolveExtends(b[m]);
                if (l)
                    if (Array.isArray(l)) this._expandButton(a, l, c, d);
                    else {
                        var k = this._buildButton(l, c);
                        k &&
                            (d !== t && null !== d
                                ? (a.splice(d, 0, k), d++)
                                : a.push(k),
                            k.conf.buttons &&
                                ((k.collection = f(
                                    "<" + this.c.dom.collection.tag + "/>"
                                )),
                                (k.conf._collection = k.collection),
                                this._expandButton(
                                    k.buttons,
                                    k.conf.buttons,
                                    !0,
                                    d
                                )),
                            l.init &&
                                l.init.call(e.button(k.node), e, f(k.node), l),
                            h++);
                    }
            }
        },
        _buildButton: function (a, b) {
            var c = this.c.dom.button,
                d = this.c.dom.buttonLiner,
                e = this.c.dom.collection,
                h = this.s.dt,
                m = function (n) {
                    return "function" === typeof n ? n(h, k, a) : n;
                };
            b && e.button && (c = e.button);
            b && e.buttonLiner && (d = e.buttonLiner);
            if (a.available && !a.available(h, a)) return !1;
            var g = function (n, p, r, v) {
                v.action.call(p.button(r), n, p, r, v);
                f(p.table().node()).triggerHandler("buttons-action.dt", [
                    p.button(r),
                    p,
                    r,
                    v,
                ]);
            };
            e = a.tag || c.tag;
            var l = a.clickBlurs === t ? !0 : a.clickBlurs,
                k = f("<" + e + "/>")
                    .addClass(c.className)
                    .attr("tabindex", this.s.dt.settings()[0].iTabIndex)
                    .attr("aria-controls", this.s.dt.table().node().id)
                    .on("click.dtb", function (n) {
                        n.preventDefault();
                        !k.hasClass(c.disabled) && a.action && g(n, h, k, a);
                        l && k.trigger("blur");
                    })
                    .on("keyup.dtb", function (n) {
                        13 === n.keyCode &&
                            !k.hasClass(c.disabled) &&
                            a.action &&
                            g(n, h, k, a);
                    });
            "a" === e.toLowerCase() && k.attr("href", "#");
            "button" === e.toLowerCase() && k.attr("type", "button");
            d.tag
                ? ((e = f("<" + d.tag + "/>")
                      .html(m(a.text))
                      .addClass(d.className)),
                  "a" === d.tag.toLowerCase() && e.attr("href", "#"),
                  k.append(e))
                : k.html(m(a.text));
            !1 === a.enabled && k.addClass(c.disabled);
            a.className && k.addClass(a.className);
            a.titleAttr && k.attr("title", m(a.titleAttr));
            a.attr && k.attr(a.attr);
            a.namespace || (a.namespace = ".dt-button-" + N++);
            d =
                (d = this.c.dom.buttonContainer) && d.tag
                    ? f("<" + d.tag + "/>")
                          .addClass(d.className)
                          .append(k)
                    : k;
            this._addKey(a);
            this.c.buttonCreated && (d = this.c.buttonCreated(a, d));
            return {
                conf: a,
                node: k.get(0),
                inserter: d,
                buttons: [],
                inCollection: b,
                collection: null,
            };
        },
        _nodeToButton: function (a, b) {
            b || (b = this.s.buttons);
            for (var c = 0, d = b.length; c < d; c++) {
                if (b[c].node === a) return b[c];
                if (b[c].buttons.length) {
                    var e = this._nodeToButton(a, b[c].buttons);
                    if (e) return e;
                }
            }
        },
        _nodeToHost: function (a, b) {
            b || (b = this.s.buttons);
            for (var c = 0, d = b.length; c < d; c++) {
                if (b[c].node === a) return b;
                if (b[c].buttons.length) {
                    var e = this._nodeToHost(a, b[c].buttons);
                    if (e) return e;
                }
            }
        },
        _keypress: function (a, b) {
            if (!b._buttonsHandled) {
                var c = function (d) {
                    for (var e = 0, h = d.length; e < h; e++) {
                        var m = d[e].conf,
                            g = d[e].node;
                        m.key &&
                            (m.key === a
                                ? ((b._buttonsHandled = !0), f(g).click())
                                : !f.isPlainObject(m.key) ||
                                  m.key.key !== a ||
                                  (m.key.shiftKey && !b.shiftKey) ||
                                  (m.key.altKey && !b.altKey) ||
                                  (m.key.ctrlKey && !b.ctrlKey) ||
                                  (m.key.metaKey && !b.metaKey) ||
                                  ((b._buttonsHandled = !0), f(g).click()));
                        d[e].buttons.length && c(d[e].buttons);
                    }
                };
                c(this.s.buttons);
            }
        },
        _removeKey: function (a) {
            if (a.key) {
                var b = f.isPlainObject(a.key) ? a.key.key : a.key;
                a = this.s.listenKeys.split("");
                b = f.inArray(b, a);
                a.splice(b, 1);
                this.s.listenKeys = a.join("");
            }
        },
        _resolveExtends: function (a) {
            var b = this.s.dt,
                c,
                d = function (g) {
                    for (
                        var l = 0;
                        !f.isPlainObject(g) && !Array.isArray(g);

                    ) {
                        if (g === t) return;
                        if ("function" === typeof g) {
                            if (((g = g(b, a)), !g)) return !1;
                        } else if ("string" === typeof g) {
                            if (!z[g]) throw "Unknown button type: " + g;
                            g = z[g];
                        }
                        l++;
                        if (30 < l) throw "Buttons: Too many iterations";
                    }
                    return Array.isArray(g) ? g : f.extend({}, g);
                };
            for (a = d(a); a && a.extend; ) {
                if (!z[a.extend])
                    throw "Cannot extend unknown button type: " + a.extend;
                var e = d(z[a.extend]);
                if (Array.isArray(e)) return e;
                if (!e) return !1;
                var h = e.className;
                a = f.extend({}, e, a);
                h && a.className !== h && (a.className = h + " " + a.className);
                var m = a.postfixButtons;
                if (m) {
                    a.buttons || (a.buttons = []);
                    h = 0;
                    for (c = m.length; h < c; h++) a.buttons.push(m[h]);
                    a.postfixButtons = null;
                }
                if ((m = a.prefixButtons)) {
                    a.buttons || (a.buttons = []);
                    h = 0;
                    for (c = m.length; h < c; h++) a.buttons.splice(h, 0, m[h]);
                    a.prefixButtons = null;
                }
                a.extend = e.extend;
            }
            return a;
        },
        _popover: function (a, b, c) {
            var d = this.c,
                e = f.extend(
                    {
                        align: "button-left",
                        autoClose: !1,
                        background: !0,
                        backgroundClassName: "dt-button-background",
                        contentClassName: d.dom.collection.className,
                        collectionLayout: "",
                        collectionTitle: "",
                        dropup: !1,
                        fade: 400,
                        rightAlignClassName: "dt-button-right",
                        tag: d.dom.collection.tag,
                    },
                    c
                ),
                h = b.node(),
                m = function () {
                    F(f(".dt-button-collection"), e.fade, function () {
                        f(this).detach();
                    });
                    f(
                        b
                            .buttons(
                                '[aria-haspopup="true"][aria-expanded="true"]'
                            )
                            .nodes()
                    ).attr("aria-expanded", "false");
                    f("div.dt-button-background").off("click.dtb-collection");
                    u.background(!1, e.backgroundClassName, e.fade, h);
                    f("body").off(".dtb-collection");
                    b.off("buttons-action.b-internal");
                };
            !1 === a && m();
            c = f(
                b
                    .buttons('[aria-haspopup="true"][aria-expanded="true"]')
                    .nodes()
            );
            c.length && ((h = c.eq(0)), m());
            c = f("<div/>")
                .addClass("dt-button-collection")
                .addClass(e.collectionLayout)
                .css("display", "none");
            a = f(a)
                .addClass(e.contentClassName)
                .attr("role", "menu")
                .appendTo(c);
            h.attr("aria-expanded", "true");
            h.parents("body")[0] !== y.body && (h = y.body.lastChild);
            e.collectionTitle &&
                c.prepend(
                    '<div class="dt-button-collection-title">' +
                        e.collectionTitle +
                        "</div>"
                );
            E(c.insertAfter(h), e.fade);
            d = f(b.table().container());
            var g = c.css("position");
            "dt-container" === e.align &&
                ((h = h.parent()), c.css("width", d.width()));
            if (
                "absolute" === g &&
                (c.hasClass(e.rightAlignClassName) ||
                    c.hasClass(e.leftAlignClassName) ||
                    "dt-container" === e.align)
            ) {
                var l = h.position();
                c.css({ top: l.top + h.outerHeight(), left: l.left });
                var k = c.outerHeight(),
                    n = d.offset().top + d.height(),
                    p = l.top + h.outerHeight() + k;
                n = p - n;
                p = l.top - k;
                var r = d.offset().top,
                    v = l.top - k - 5;
                (n > r - p || e.dropup) && -v < r && c.css("top", v);
                l = d.offset().left;
                d = d.width();
                d = l + d;
                g = c.offset().left;
                var x = c.width();
                x = g + x;
                var w = h.offset().left,
                    B = h.outerWidth();
                B = w + B;
                w = 0;
                c.hasClass(e.rightAlignClassName)
                    ? ((w = B - x),
                      l > g + w &&
                          ((g = l - (g + w)),
                          (d -= x + w),
                          (w = g > d ? w + d : w + g)))
                    : ((w = l - g),
                      d < x + w &&
                          ((g = l - (g + w)),
                          (d -= x + w),
                          (w = g > d ? w + d : w + g)));
                c.css("left", c.position().left + w);
            } else
                "absolute" === g
                    ? ((l = h.position()),
                      c.css({ top: l.top + h.outerHeight(), left: l.left }),
                      (k = c.outerHeight()),
                      (g = h.offset().top),
                      (w = 0),
                      (w = h.offset().left),
                      (B = h.outerWidth()),
                      (B = w + B),
                      (g = c.offset().left),
                      (x = a.width()),
                      (x = g + x),
                      (v = l.top - k - 5),
                      (n = d.offset().top + d.height()),
                      (p = l.top + h.outerHeight() + k),
                      (n = p - n),
                      (p = l.top - k),
                      (r = d.offset().top),
                      (n > r - p || e.dropup) && -v < r && c.css("top", v),
                      (w = "button-right" === e.align ? B - x : w - g),
                      c.css("left", c.position().left + w))
                    : ((g = c.height() / 2),
                      g > f(A).height() / 2 && (g = f(A).height() / 2),
                      c.css("marginTop", -1 * g));
            e.background && u.background(!0, e.backgroundClassName, e.fade, h);
            f("div.dt-button-background").on(
                "click.dtb-collection",
                function () {}
            );
            f("body")
                .on("click.dtb-collection", function (C) {
                    var I = f.fn.addBack ? "addBack" : "andSelf",
                        J = f(C.target).parent()[0];
                    ((!f(C.target).parents()[I]().filter(a).length &&
                        !f(J).hasClass("dt-buttons")) ||
                        f(C.target).hasClass("dt-button-background")) &&
                        m();
                })
                .on("keyup.dtb-collection", function (C) {
                    27 === C.keyCode && m();
                });
            e.autoClose &&
                setTimeout(function () {
                    b.on("buttons-action.b-internal", function (C, I, J, O) {
                        O[0] !== h[0] && m();
                    });
                }, 0);
            f(c).trigger("buttons-popover.dt");
        },
    });
    u.background = function (a, b, c, d) {
        c === t && (c = 400);
        d || (d = y.body);
        a
            ? E(
                  f("<div/>").addClass(b).css("display", "none").insertAfter(d),
                  c
              )
            : F(f("div." + b), c, function () {
                  f(this).removeClass(b).remove();
              });
    };
    u.instanceSelector = function (a, b) {
        if (a === t || null === a)
            return f.map(b, function (h) {
                return h.inst;
            });
        var c = [],
            d = f.map(b, function (h) {
                return h.name;
            }),
            e = function (h) {
                if (Array.isArray(h))
                    for (var m = 0, g = h.length; m < g; m++) e(h[m]);
                else
                    "string" === typeof h
                        ? -1 !== h.indexOf(",")
                            ? e(h.split(","))
                            : ((h = f.inArray(h.trim(), d)),
                              -1 !== h && c.push(b[h].inst))
                        : "number" === typeof h && c.push(b[h].inst);
            };
        e(a);
        return c;
    };
    u.buttonSelector = function (a, b) {
        for (
            var c = [],
                d = function (g, l, k) {
                    for (var n, p, r = 0, v = l.length; r < v; r++)
                        if ((n = l[r]))
                            (p = k !== t ? k + r : r + ""),
                                g.push({
                                    node: n.node,
                                    name: n.conf.name,
                                    idx: p,
                                }),
                                n.buttons && d(g, n.buttons, p + "-");
                },
                e = function (g, l) {
                    var k,
                        n = [];
                    d(n, l.s.buttons);
                    var p = f.map(n, function (r) {
                        return r.node;
                    });
                    if (Array.isArray(g) || g instanceof f)
                        for (p = 0, k = g.length; p < k; p++) e(g[p], l);
                    else if (null === g || g === t || "*" === g)
                        for (p = 0, k = n.length; p < k; p++)
                            c.push({ inst: l, node: n[p].node });
                    else if ("number" === typeof g)
                        c.push({ inst: l, node: l.s.buttons[g].node });
                    else if ("string" === typeof g)
                        if (-1 !== g.indexOf(","))
                            for (
                                n = g.split(","), p = 0, k = n.length;
                                p < k;
                                p++
                            )
                                e(n[p].trim(), l);
                        else if (g.match(/^\d+(\-\d+)*$/))
                            (p = f.map(n, function (r) {
                                return r.idx;
                            })),
                                c.push({
                                    inst: l,
                                    node: n[f.inArray(g, p)].node,
                                });
                        else if (-1 !== g.indexOf(":name"))
                            for (
                                g = g.replace(":name", ""), p = 0, k = n.length;
                                p < k;
                                p++
                            )
                                n[p].name === g &&
                                    c.push({ inst: l, node: n[p].node });
                        else
                            f(p)
                                .filter(g)
                                .each(function () {
                                    c.push({ inst: l, node: this });
                                });
                    else
                        "object" === typeof g &&
                            g.nodeName &&
                            ((n = f.inArray(g, p)),
                            -1 !== n && c.push({ inst: l, node: p[n] }));
                },
                h = 0,
                m = a.length;
            h < m;
            h++
        )
            e(b, a[h]);
        return c;
    };
    u.stripData = function (a, b) {
        if ("string" !== typeof a) return a;
        a = a.replace(
            /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
            ""
        );
        a = a.replace(/<!\-\-.*?\-\->/g, "");
        b.stripHtml && (a = a.replace(/<[^>]*>/g, ""));
        b.trim && (a = a.replace(/^\s+|\s+$/g, ""));
        b.stripNewlines && (a = a.replace(/\n/g, " "));
        b.decodeEntities && ((K.innerHTML = a), (a = K.value));
        return a;
    };
    u.defaults = {
        buttons: ["copy", "excel", "csv", "pdf", "print"],
        name: "main",
        tabIndex: 0,
        dom: {
            container: { tag: "div", className: "dt-buttons" },
            collection: { tag: "div", className: "" },
            button: {
                tag: "button",
                className: "dt-button",
                active: "active",
                disabled: "disabled",
            },
            buttonLiner: { tag: "span", className: "" },
        },
    };
    u.version = "1.7.0";
    f.extend(z, {
        collection: {
            text: function (a) {
                return a.i18n("buttons.collection", "Collection");
            },
            className: "buttons-collection",
            init: function (a, b, c) {
                b.attr("aria-expanded", !1);
            },
            action: function (a, b, c, d) {
                a.stopPropagation();
                d._collection.parents("body").length
                    ? this.popover(!1, d)
                    : this.popover(d._collection, d);
            },
            attr: { "aria-haspopup": !0 },
        },
        copy: function (a, b) {
            if (z.copyHtml5) return "copyHtml5";
        },
        csv: function (a, b) {
            if (z.csvHtml5 && z.csvHtml5.available(a, b)) return "csvHtml5";
        },
        excel: function (a, b) {
            if (z.excelHtml5 && z.excelHtml5.available(a, b))
                return "excelHtml5";
        },
        pdf: function (a, b) {
            if (z.pdfHtml5 && z.pdfHtml5.available(a, b)) return "pdfHtml5";
        },
        pageLength: function (a) {
            a = a.settings()[0].aLengthMenu;
            var b = [],
                c = [];
            if (Array.isArray(a[0])) (b = a[0]), (c = a[1]);
            else
                for (var d = 0; d < a.length; d++) {
                    var e = a[d];
                    f.isPlainObject(e)
                        ? (b.push(e.value), c.push(e.label))
                        : (b.push(e), c.push(e));
                }
            return {
                extend: "collection",
                text: function (h) {
                    return h.i18n(
                        "buttons.pageLength",
                        { "-1": "Show all rows", _: "Show %d rows" },
                        h.page.len()
                    );
                },
                className: "buttons-page-length",
                autoClose: !0,
                buttons: f.map(b, function (h, m) {
                    return {
                        text: c[m],
                        className: "button-page-length",
                        action: function (g, l) {
                            l.page.len(h).draw();
                        },
                        init: function (g, l, k) {
                            var n = this;
                            l = function () {
                                n.active(g.page.len() === h);
                            };
                            g.on("length.dt" + k.namespace, l);
                            l();
                        },
                        destroy: function (g, l, k) {
                            g.off("length.dt" + k.namespace);
                        },
                    };
                }),
                init: function (h, m, g) {
                    var l = this;
                    h.on("length.dt" + g.namespace, function () {
                        l.text(g.text);
                    });
                },
                destroy: function (h, m, g) {
                    h.off("length.dt" + g.namespace);
                },
            };
        },
    });
    q.Api.register("buttons()", function (a, b) {
        b === t && ((b = a), (a = t));
        this.selector.buttonGroup = a;
        var c = this.iterator(
            !0,
            "table",
            function (d) {
                if (d._buttons)
                    return u.buttonSelector(
                        u.instanceSelector(a, d._buttons),
                        b
                    );
            },
            !0
        );
        c._groupSelector = a;
        return c;
    });
    q.Api.register("button()", function (a, b) {
        a = this.buttons(a, b);
        1 < a.length && a.splice(1, a.length);
        return a;
    });
    q.Api.registerPlural(
        "buttons().active()",
        "button().active()",
        function (a) {
            return a === t
                ? this.map(function (b) {
                      return b.inst.active(b.node);
                  })
                : this.each(function (b) {
                      b.inst.active(b.node, a);
                  });
        }
    );
    q.Api.registerPlural(
        "buttons().action()",
        "button().action()",
        function (a) {
            return a === t
                ? this.map(function (b) {
                      return b.inst.action(b.node);
                  })
                : this.each(function (b) {
                      b.inst.action(b.node, a);
                  });
        }
    );
    q.Api.register(["buttons().enable()", "button().enable()"], function (a) {
        return this.each(function (b) {
            b.inst.enable(b.node, a);
        });
    });
    q.Api.register(["buttons().disable()", "button().disable()"], function () {
        return this.each(function (a) {
            a.inst.disable(a.node);
        });
    });
    q.Api.registerPlural("buttons().nodes()", "button().node()", function () {
        var a = f();
        f(
            this.each(function (b) {
                a = a.add(b.inst.node(b.node));
            })
        );
        return a;
    });
    q.Api.registerPlural(
        "buttons().processing()",
        "button().processing()",
        function (a) {
            return a === t
                ? this.map(function (b) {
                      return b.inst.processing(b.node);
                  })
                : this.each(function (b) {
                      b.inst.processing(b.node, a);
                  });
        }
    );
    q.Api.registerPlural("buttons().text()", "button().text()", function (a) {
        return a === t
            ? this.map(function (b) {
                  return b.inst.text(b.node);
              })
            : this.each(function (b) {
                  b.inst.text(b.node, a);
              });
    });
    q.Api.registerPlural(
        "buttons().trigger()",
        "button().trigger()",
        function () {
            return this.each(function (a) {
                a.inst.node(a.node).trigger("click");
            });
        }
    );
    q.Api.register("button().popover()", function (a, b) {
        return this.map(function (c) {
            return c.inst._popover(a, this.button(this[0].node), b);
        });
    });
    q.Api.register("buttons().containers()", function () {
        var a = f(),
            b = this._groupSelector;
        this.iterator(!0, "table", function (c) {
            if (c._buttons) {
                c = u.instanceSelector(b, c._buttons);
                for (var d = 0, e = c.length; d < e; d++)
                    a = a.add(c[d].container());
            }
        });
        return a;
    });
    q.Api.register("buttons().container()", function () {
        return this.containers().eq(0);
    });
    q.Api.register("button().add()", function (a, b) {
        var c = this.context;
        c.length &&
            ((c = u.instanceSelector(this._groupSelector, c[0]._buttons)),
            c.length && c[0].add(b, a));
        return this.button(this._groupSelector, a);
    });
    q.Api.register("buttons().destroy()", function () {
        this.pluck("inst")
            .unique()
            .each(function (a) {
                a.destroy();
            });
        return this;
    });
    q.Api.registerPlural(
        "buttons().remove()",
        "buttons().remove()",
        function () {
            this.each(function (a) {
                a.inst.remove(a.node);
            });
            return this;
        }
    );
    var D;
    q.Api.register("buttons.info()", function (a, b, c) {
        var d = this;
        if (!1 === a)
            return (
                this.off("destroy.btn-info"),
                F(f("#datatables_buttons_info"), 400, function () {
                    f(this).remove();
                }),
                clearTimeout(D),
                (D = null),
                this
            );
        D && clearTimeout(D);
        f("#datatables_buttons_info").length &&
            f("#datatables_buttons_info").remove();
        a = a ? "<h2>" + a + "</h2>" : "";
        E(
            f('<div id="datatables_buttons_info" class="dt-button-info"/>')
                .html(a)
                .append(
                    f("<div/>")["string" === typeof b ? "html" : "append"](b)
                )
                .css("display", "none")
                .appendTo("body")
        );
        c !== t &&
            0 !== c &&
            (D = setTimeout(function () {
                d.buttons.info(!1);
            }, c));
        this.on("destroy.btn-info", function () {
            d.buttons.info(!1);
        });
        return this;
    });
    q.Api.register("buttons.exportData()", function (a) {
        if (this.context.length) return P(new q.Api(this.context[0]), a);
    });
    q.Api.register("buttons.exportInfo()", function (a) {
        a || (a = {});
        var b = a;
        var c =
            "*" === b.filename &&
            "*" !== b.title &&
            b.title !== t &&
            null !== b.title &&
            "" !== b.title
                ? b.title
                : b.filename;
        "function" === typeof c && (c = c());
        c === t || null === c
            ? (c = null)
            : (-1 !== c.indexOf("*") &&
                  (c = c.replace("*", f("head > title").text()).trim()),
              (c = c.replace(/[^a-zA-Z0-9_\u00A1-\uFFFF\.,\-_ !\(\)]/g, "")),
              (b = G(b.extension)) || (b = ""),
              (c += b));
        b = G(a.title);
        b =
            null === b
                ? null
                : -1 !== b.indexOf("*")
                ? b.replace("*", f("head > title").text() || "Exported data")
                : b;
        return {
            filename: c,
            title: b,
            messageTop: L(this, a.message || a.messageTop, "top"),
            messageBottom: L(this, a.messageBottom, "bottom"),
        };
    });
    var G = function (a) {
            return null === a || a === t
                ? null
                : "function" === typeof a
                ? a()
                : a;
        },
        L = function (a, b, c) {
            b = G(b);
            if (null === b) return null;
            a = f("caption", a.table().container()).eq(0);
            return "*" === b
                ? a.css("caption-side") !== c
                    ? null
                    : a.length
                    ? a.text()
                    : ""
                : b;
        },
        K = f("<textarea/>")[0],
        P = function (a, b) {
            var c = f.extend(
                !0,
                {},
                {
                    rows: null,
                    columns: "",
                    modifier: { search: "applied", order: "applied" },
                    orthogonal: "display",
                    stripHtml: !0,
                    stripNewlines: !0,
                    decodeEntities: !0,
                    trim: !0,
                    format: {
                        header: function (v) {
                            return u.stripData(v, c);
                        },
                        footer: function (v) {
                            return u.stripData(v, c);
                        },
                        body: function (v) {
                            return u.stripData(v, c);
                        },
                    },
                    customizeData: null,
                },
                b
            );
            b = a
                .columns(c.columns)
                .indexes()
                .map(function (v) {
                    var x = a.column(v).header();
                    return c.format.header(x.innerHTML, v, x);
                })
                .toArray();
            var d = a.table().footer()
                    ? a
                          .columns(c.columns)
                          .indexes()
                          .map(function (v) {
                              var x = a.column(v).footer();
                              return c.format.footer(
                                  x ? x.innerHTML : "",
                                  v,
                                  x
                              );
                          })
                          .toArray()
                    : null,
                e = f.extend({}, c.modifier);
            a.select &&
                "function" === typeof a.select.info &&
                e.selected === t &&
                a.rows(c.rows, f.extend({ selected: !0 }, e)).any() &&
                f.extend(e, { selected: !0 });
            e = a.rows(c.rows, e).indexes().toArray();
            var h = a.cells(e, c.columns);
            e = h.render(c.orthogonal).toArray();
            h = h.nodes().toArray();
            for (
                var m = b.length,
                    g = [],
                    l = 0,
                    k = 0,
                    n = 0 < m ? e.length / m : 0;
                k < n;
                k++
            ) {
                for (var p = [m], r = 0; r < m; r++)
                    (p[r] = c.format.body(e[l], k, r, h[l])), l++;
                g[k] = p;
            }
            b = { header: b, footer: d, body: g };
            c.customizeData && c.customizeData(b);
            return b;
        };
    f.fn.dataTable.Buttons = u;
    f.fn.DataTable.Buttons = u;
    f(y).on("init.dt plugin-init.dt", function (a, b) {
        "dt" === a.namespace &&
            (a = b.oInit.buttons || q.defaults.buttons) &&
            !b._buttons &&
            new u(b, a).container();
    });
    q.ext.feature.push({ fnInit: H, cFeature: "B" });
    q.ext.features && q.ext.features.register("buttons", H);
    return u;
});

// buttons.html5.min.js
/*!
 HTML5 export buttons for Buttons and DataTables.
 2016 SpryMedia Ltd - datatables.net/license

 FileSaver.js (1.3.3) - MIT license
 Copyright © 2016 Eli Grey - http://eligrey.com
*/
(function (n) {
    "function" === typeof define && define.amd
        ? define([
              "jquery",
              "datatables.net",
              "datatables.net-buttons",
          ], function (u) {
              return n(u, window, document);
          })
        : "object" === typeof exports
        ? (module.exports = function (u, x, E, F) {
              u || (u = window);
              (x && x.fn.dataTable) || (x = require("datatables.net")(u, x).$);
              x.fn.dataTable.Buttons || require("datatables.net-buttons")(u, x);
              return n(x, u, u.document, E, F);
          })
        : n(jQuery, window, document);
})(function (n, u, x, E, F, B) {
    function I(a) {
        for (var c = ""; 0 <= a; )
            (c = String.fromCharCode((a % 26) + 65) + c),
                (a = Math.floor(a / 26) - 1);
        return c;
    }
    function O(a, c) {
        J === B &&
            (J =
                -1 ===
                M.serializeToString(
                    new u.DOMParser().parseFromString(
                        P["xl/worksheets/sheet1.xml"],
                        "text/xml"
                    )
                ).indexOf("xmlns:r"));
        n.each(c, function (d, b) {
            if (n.isPlainObject(b)) (d = a.folder(d)), O(d, b);
            else {
                if (J) {
                    var m = b.childNodes[0],
                        e,
                        f = [];
                    for (e = m.attributes.length - 1; 0 <= e; e--) {
                        var g = m.attributes[e].nodeName;
                        var p = m.attributes[e].nodeValue;
                        -1 !== g.indexOf(":") &&
                            (f.push({ name: g, value: p }),
                            m.removeAttribute(g));
                    }
                    e = 0;
                    for (g = f.length; e < g; e++)
                        (p = b.createAttribute(
                            f[e].name.replace(":", "_dt_b_namespace_token_")
                        )),
                            (p.value = f[e].value),
                            m.setAttributeNode(p);
                }
                b = M.serializeToString(b);
                J &&
                    (-1 === b.indexOf("<?xml") &&
                        (b =
                            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' +
                            b),
                    (b = b.replace(/_dt_b_namespace_token_/g, ":")),
                    (b = b.replace(/xmlns:NS[\d]+="" NS[\d]+:/g, "")));
                b = b.replace(/<([^<>]*?) xmlns=""([^<>]*?)>/g, "<$1 $2>");
                a.file(d, b);
            }
        });
    }
    function y(a, c, d) {
        var b = a.createElement(c);
        d &&
            (d.attr && n(b).attr(d.attr),
            d.children &&
                n.each(d.children, function (m, e) {
                    b.appendChild(e);
                }),
            null !== d.text &&
                d.text !== B &&
                b.appendChild(a.createTextNode(d.text)));
        return b;
    }
    function V(a, c) {
        var d = a.header[c].length;
        a.footer && a.footer[c].length > d && (d = a.footer[c].length);
        for (var b = 0, m = a.body.length; b < m; b++) {
            var e = a.body[b][c];
            e = null !== e && e !== B ? e.toString() : "";
            -1 !== e.indexOf("\n")
                ? ((e = e.split("\n")),
                  e.sort(function (f, g) {
                      return g.length - f.length;
                  }),
                  (e = e[0].length))
                : (e = e.length);
            e > d && (d = e);
            if (40 < d) return 54;
        }
        d *= 1.35;
        return 6 < d ? d : 6;
    }
    var D = n.fn.dataTable;
    D.Buttons.pdfMake = function (a) {
        if (!a) return F || u.pdfMake;
        F = a;
    };
    D.Buttons.jszip = function (a) {
        if (!a) return E || u.JSZip;
        E = a;
    };
    var K = (function (a) {
        if (
            !(
                "undefined" === typeof a ||
                ("undefined" !== typeof navigator &&
                    /MSIE [1-9]\./.test(navigator.userAgent))
            )
        ) {
            var c = a.document.createElementNS(
                    "http://www.w3.org/1999/xhtml",
                    "a"
                ),
                d = "download" in c,
                b = /constructor/i.test(a.HTMLElement) || a.safari,
                m = /CriOS\/[\d]+/.test(navigator.userAgent),
                e = function (h) {
                    (a.setImmediate || a.setTimeout)(function () {
                        throw h;
                    }, 0);
                },
                f = function (h) {
                    setTimeout(function () {
                        "string" === typeof h
                            ? (a.URL || a.webkitURL || a).revokeObjectURL(h)
                            : h.remove();
                    }, 4e4);
                },
                g = function (h) {
                    return /^\s*(?:text\/\S*|application\/xml|\S*\/\S*\+xml)\s*;.*charset\s*=\s*utf-8/i.test(
                        h.type
                    )
                        ? new Blob([String.fromCharCode(65279), h], {
                              type: h.type,
                          })
                        : h;
                },
                p = function (h, q, v) {
                    v || (h = g(h));
                    var r = this,
                        w = "application/octet-stream" === h.type,
                        C = function () {
                            var l = [
                                "writestart",
                                "progress",
                                "write",
                                "writeend",
                            ];
                            l = [].concat(l);
                            for (var z = l.length; z--; ) {
                                var G = r["on" + l[z]];
                                if ("function" === typeof G)
                                    try {
                                        G.call(r, r);
                                    } catch (A) {
                                        e(A);
                                    }
                            }
                        };
                    r.readyState = r.INIT;
                    if (d) {
                        var k = (a.URL || a.webkitURL || a).createObjectURL(h);
                        setTimeout(function () {
                            c.href = k;
                            c.download = q;
                            var l = new MouseEvent("click");
                            c.dispatchEvent(l);
                            C();
                            f(k);
                            r.readyState = r.DONE;
                        });
                    } else
                        (function () {
                            if ((m || (w && b)) && a.FileReader) {
                                var l = new FileReader();
                                l.onloadend = function () {
                                    var z = m
                                        ? l.result
                                        : l.result.replace(
                                              /^data:[^;]*;/,
                                              "data:attachment/file;"
                                          );
                                    a.open(z, "_blank") ||
                                        (a.location.href = z);
                                    r.readyState = r.DONE;
                                    C();
                                };
                                l.readAsDataURL(h);
                                r.readyState = r.INIT;
                            } else
                                k ||
                                    (k = (
                                        a.URL ||
                                        a.webkitURL ||
                                        a
                                    ).createObjectURL(h)),
                                    w
                                        ? (a.location.href = k)
                                        : a.open(k, "_blank") ||
                                          (a.location.href = k),
                                    (r.readyState = r.DONE),
                                    C(),
                                    f(k);
                        })();
                },
                t = p.prototype;
            if ("undefined" !== typeof navigator && navigator.msSaveOrOpenBlob)
                return function (h, q, v) {
                    q = q || h.name || "download";
                    v || (h = g(h));
                    return navigator.msSaveOrOpenBlob(h, q);
                };
            t.abort = function () {};
            t.readyState = t.INIT = 0;
            t.WRITING = 1;
            t.DONE = 2;
            t.error = t.onwritestart = t.onprogress = t.onwrite = t.onabort = t.onerror = t.onwriteend = null;
            return function (h, q, v) {
                return new p(h, q || h.name || "download", v);
            };
        }
    })(
        ("undefined" !== typeof self && self) ||
            ("undefined" !== typeof u && u) ||
            this.content
    );
    D.fileSave = K;
    var Q = function (a) {
            var c = "Sheet1";
            a.sheetName && (c = a.sheetName.replace(/[\[\]\*\/\\\?:]/g, ""));
            return c;
        },
        R = function (a) {
            return a.newline
                ? a.newline
                : navigator.userAgent.match(/Windows/)
                ? "\r\n"
                : "\n";
        },
        S = function (a, c) {
            var d = R(c);
            a = a.buttons.exportData(c.exportOptions);
            var b = c.fieldBoundary,
                m = c.fieldSeparator,
                e = new RegExp(b, "g"),
                f = c.escapeChar !== B ? c.escapeChar : "\\",
                g = function (v) {
                    for (var r = "", w = 0, C = v.length; w < C; w++)
                        0 < w && (r += m),
                            (r += b
                                ? b + ("" + v[w]).replace(e, f + b) + b
                                : v[w]);
                    return r;
                },
                p = c.header ? g(a.header) + d : "";
            c = c.footer && a.footer ? d + g(a.footer) : "";
            for (var t = [], h = 0, q = a.body.length; h < q; h++)
                t.push(g(a.body[h]));
            return { str: p + t.join(d) + c, rows: t.length };
        },
        T = function () {
            if (
                -1 === navigator.userAgent.indexOf("Safari") ||
                -1 !== navigator.userAgent.indexOf("Chrome") ||
                -1 !== navigator.userAgent.indexOf("Opera")
            )
                return !1;
            var a = navigator.userAgent.match(/AppleWebKit\/(\d+\.\d+)/);
            return a && 1 < a.length && 603.1 > 1 * a[1] ? !0 : !1;
        };
    try {
        var M = new XMLSerializer(),
            J;
    } catch (a) {}
    var P = {
            "_rels/.rels":
                '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>',
            "xl/_rels/workbook.xml.rels":
                '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>',
            "[Content_Types].xml":
                '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="xml" ContentType="application/xml" /><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml" /><Default Extension="jpeg" ContentType="image/jpeg" /><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml" /><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml" /><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml" /></Types>',
            "xl/workbook.xml":
                '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><fileVersion appName="xl" lastEdited="5" lowestEdited="5" rupBuild="24816"/><workbookPr showInkAnnotation="0" autoCompressPictures="0"/><bookViews><workbookView xWindow="0" yWindow="0" windowWidth="25600" windowHeight="19020" tabRatio="500"/></bookViews><sheets><sheet name="Sheet1" sheetId="1" r:id="rId1"/></sheets><definedNames/></workbook>',
            "xl/worksheets/sheet1.xml":
                '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" mc:Ignorable="x14ac" xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac"><sheetData/><mergeCells count="0"/></worksheet>',
            "xl/styles.xml":
                '<?xml version="1.0" encoding="UTF-8"?><styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" mc:Ignorable="x14ac" xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac"><numFmts count="6"><numFmt numFmtId="164" formatCode="#,##0.00_- [$$-45C]"/><numFmt numFmtId="165" formatCode="&quot;£&quot;#,##0.00"/><numFmt numFmtId="166" formatCode="[$€-2] #,##0.00"/><numFmt numFmtId="167" formatCode="0.0%"/><numFmt numFmtId="168" formatCode="#,##0;(#,##0)"/><numFmt numFmtId="169" formatCode="#,##0.00;(#,##0.00)"/></numFmts><fonts count="5" x14ac:knownFonts="1"><font><sz val="11" /><name val="Calibri" /></font><font><sz val="11" /><name val="Calibri" /><color rgb="FFFFFFFF" /></font><font><sz val="11" /><name val="Calibri" /><b /></font><font><sz val="11" /><name val="Calibri" /><i /></font><font><sz val="11" /><name val="Calibri" /><u /></font></fonts><fills count="6"><fill><patternFill patternType="none" /></fill><fill><patternFill patternType="none" /></fill><fill><patternFill patternType="solid"><fgColor rgb="FFD9D9D9" /><bgColor indexed="64" /></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="FFD99795" /><bgColor indexed="64" /></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="ffc6efce" /><bgColor indexed="64" /></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="ffc6cfef" /><bgColor indexed="64" /></patternFill></fill></fills><borders count="2"><border><left /><right /><top /><bottom /><diagonal /></border><border diagonalUp="false" diagonalDown="false"><left style="thin"><color auto="1" /></left><right style="thin"><color auto="1" /></right><top style="thin"><color auto="1" /></top><bottom style="thin"><color auto="1" /></bottom><diagonal /></border></borders><cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" /></cellStyleXfs><cellXfs count="68"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="3" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="3" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="3" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="3" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="3" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="left"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="center"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="right"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="fill"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment textRotation="90"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment wrapText="1"/></xf><xf numFmtId="9"   fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="164" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="165" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="166" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="167" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="168" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="169" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="3" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="4" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="1" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="2" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="14" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/></cellXfs><cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0" /></cellStyles><dxfs count="0" /><tableStyles count="0" defaultTableStyle="TableStyleMedium9" defaultPivotStyle="PivotStyleMedium4" /></styleSheet>',
        },
        U = [
            {
                match: /^\-?\d+\.\d%$/,
                style: 60,
                fmt: function (a) {
                    return a / 100;
                },
            },
            {
                match: /^\-?\d+\.?\d*%$/,
                style: 56,
                fmt: function (a) {
                    return a / 100;
                },
            },
            { match: /^\-?\$[\d,]+.?\d*$/, style: 57 },
            { match: /^\-?£[\d,]+.?\d*$/, style: 58 },
            { match: /^\-?€[\d,]+.?\d*$/, style: 59 },
            { match: /^\-?\d+$/, style: 65 },
            { match: /^\-?\d+\.\d{2}$/, style: 66 },
            {
                match: /^\([\d,]+\)$/,
                style: 61,
                fmt: function (a) {
                    return -1 * a.replace(/[\(\)]/g, "");
                },
            },
            {
                match: /^\([\d,]+\.\d{2}\)$/,
                style: 62,
                fmt: function (a) {
                    return -1 * a.replace(/[\(\)]/g, "");
                },
            },
            { match: /^\-?[\d,]+$/, style: 63 },
            { match: /^\-?[\d,]+\.\d{2}$/, style: 64 },
            {
                match: /^[\d]{4}\-[\d]{2}\-[\d]{2}$/,
                style: 67,
                fmt: function (a) {
                    return Math.round(25569 + Date.parse(a) / 864e5);
                },
            },
        ];
    D.ext.buttons.copyHtml5 = {
        className: "buttons-copy buttons-html5",
        text: function (a) {
            return a.i18n("buttons.copy", "Copy");
        },
        action: function (a, c, d, b) {
            this.processing(!0);
            var m = this;
            a = S(c, b);
            var e = c.buttons.exportInfo(b),
                f = R(b),
                g = a.str;
            d = n("<div/>").css({
                height: 1,
                width: 1,
                overflow: "hidden",
                position: "fixed",
                top: 0,
                left: 0,
            });
            e.title && (g = e.title + f + f + g);
            e.messageTop && (g = e.messageTop + f + f + g);
            e.messageBottom && (g = g + f + f + e.messageBottom);
            b.customize && (g = b.customize(g, b, c));
            b = n("<textarea readonly/>").val(g).appendTo(d);
            if (x.queryCommandSupported("copy")) {
                d.appendTo(c.table().container());
                b[0].focus();
                b[0].select();
                try {
                    var p = x.execCommand("copy");
                    d.remove();
                    if (p) {
                        c.buttons.info(
                            c.i18n("buttons.copyTitle", "Copy to clipboard"),
                            c.i18n(
                                "buttons.copySuccess",
                                {
                                    1: "Copied one row to clipboard",
                                    _: "Copied %d rows to clipboard",
                                },
                                a.rows
                            ),
                            2e3
                        );
                        this.processing(!1);
                        return;
                    }
                } catch (q) {}
            }
            p = n(
                "<span>" +
                    c.i18n(
                        "buttons.copyKeys",
                        "Press <i>ctrl</i> or <i>⌘</i> + <i>C</i> to copy the table data<br>to your system clipboard.<br><br>To cancel, click this message or press escape."
                    ) +
                    "</span>"
            ).append(d);
            c.buttons.info(
                c.i18n("buttons.copyTitle", "Copy to clipboard"),
                p,
                0
            );
            b[0].focus();
            b[0].select();
            var t = n(p).closest(".dt-button-info"),
                h = function () {
                    t.off("click.buttons-copy");
                    n(x).off(".buttons-copy");
                    c.buttons.info(!1);
                };
            t.on("click.buttons-copy", h);
            n(x)
                .on("keydown.buttons-copy", function (q) {
                    27 === q.keyCode && (h(), m.processing(!1));
                })
                .on("copy.buttons-copy cut.buttons-copy", function () {
                    h();
                    m.processing(!1);
                });
        },
        exportOptions: {},
        fieldSeparator: "\t",
        fieldBoundary: "",
        header: !0,
        footer: !1,
        title: "*",
        messageTop: "*",
        messageBottom: "*",
    };
    D.ext.buttons.csvHtml5 = {
        bom: !1,
        className: "buttons-csv buttons-html5",
        available: function () {
            return u.FileReader !== B && u.Blob;
        },
        text: function (a) {
            return a.i18n("buttons.csv", "CSV");
        },
        action: function (a, c, d, b) {
            this.processing(!0);
            a = S(c, b).str;
            d = c.buttons.exportInfo(b);
            var m = b.charset;
            b.customize && (a = b.customize(a, b, c));
            !1 !== m
                ? (m || (m = x.characterSet || x.charset),
                  m && (m = ";charset=" + m))
                : (m = "");
            b.bom && (a = "﻿" + a);
            K(new Blob([a], { type: "text/csv" + m }), d.filename, !0);
            this.processing(!1);
        },
        filename: "*",
        extension: ".csv",
        exportOptions: {},
        fieldSeparator: ",",
        fieldBoundary: '"',
        escapeChar: '"',
        charset: null,
        header: !0,
        footer: !1,
    };
    D.ext.buttons.excelHtml5 = {
        className: "buttons-excel buttons-html5",
        available: function () {
            return u.FileReader !== B && (E || u.JSZip) !== B && !T() && M;
        },
        text: function (a) {
            return a.i18n("buttons.excel", "Excel");
        },
        action: function (a, c, d, b) {
            this.processing(!0);
            var m = this,
                e = 0;
            a = function (k) {
                return n.parseXML(P[k]);
            };
            var f = a("xl/worksheets/sheet1.xml"),
                g = f.getElementsByTagName("sheetData")[0];
            a = {
                _rels: { ".rels": a("_rels/.rels") },
                xl: {
                    _rels: {
                        "workbook.xml.rels": a("xl/_rels/workbook.xml.rels"),
                    },
                    "workbook.xml": a("xl/workbook.xml"),
                    "styles.xml": a("xl/styles.xml"),
                    worksheets: { "sheet1.xml": f },
                },
                "[Content_Types].xml": a("[Content_Types].xml"),
            };
            var p = c.buttons.exportData(b.exportOptions),
                t,
                h,
                q = function (k) {
                    t = e + 1;
                    h = y(f, "row", { attr: { r: t } });
                    for (var l = 0, z = k.length; l < z; l++) {
                        var G = I(l) + "" + t,
                            A = null;
                        if (null === k[l] || k[l] === B || "" === k[l])
                            if (!0 === b.createEmptyCells) k[l] = "";
                            else continue;
                        var H = k[l];
                        k[l] =
                            "function" === typeof k[l].trim
                                ? k[l].trim()
                                : k[l];
                        for (var N = 0, W = U.length; N < W; N++) {
                            var L = U[N];
                            if (
                                k[l].match &&
                                !k[l].match(/^0\d+/) &&
                                k[l].match(L.match)
                            ) {
                                A = k[l].replace(/[^\d\.\-]/g, "");
                                L.fmt && (A = L.fmt(A));
                                A = y(f, "c", {
                                    attr: { r: G, s: L.style },
                                    children: [y(f, "v", { text: A })],
                                });
                                break;
                            }
                        }
                        A ||
                            ("number" === typeof k[l] ||
                            (k[l].match &&
                                k[l].match(/^-?\d+(\.\d+)?$/) &&
                                !k[l].match(/^0\d+/))
                                ? (A = y(f, "c", {
                                      attr: { t: "n", r: G },
                                      children: [y(f, "v", { text: k[l] })],
                                  }))
                                : ((H = H.replace
                                      ? H.replace(
                                            /[\x00-\x09\x0B\x0C\x0E-\x1F\x7F-\x9F]/g,
                                            ""
                                        )
                                      : H),
                                  (A = y(f, "c", {
                                      attr: { t: "inlineStr", r: G },
                                      children: {
                                          row: y(f, "is", {
                                              children: {
                                                  row: y(f, "t", {
                                                      text: H,
                                                      attr: {
                                                          "xml:space":
                                                              "preserve",
                                                      },
                                                  }),
                                              },
                                          }),
                                      },
                                  }))));
                        h.appendChild(A);
                    }
                    g.appendChild(h);
                    e++;
                };
            b.customizeData && b.customizeData(p);
            var v = function (k, l) {
                    var z = n("mergeCells", f);
                    z[0].appendChild(
                        y(f, "mergeCell", {
                            attr: { ref: "A" + k + ":" + I(l) + k },
                        })
                    );
                    z.attr("count", parseFloat(z.attr("count")) + 1);
                    n("row:eq(" + (k - 1) + ") c", f).attr("s", "51");
                },
                r = c.buttons.exportInfo(b);
            r.title && (q([r.title], e), v(e, p.header.length - 1));
            r.messageTop && (q([r.messageTop], e), v(e, p.header.length - 1));
            b.header && (q(p.header, e), n("row:last c", f).attr("s", "2"));
            d = e;
            var w = 0;
            for (var C = p.body.length; w < C; w++) q(p.body[w], e);
            w = e;
            b.footer &&
                p.footer &&
                (q(p.footer, e), n("row:last c", f).attr("s", "2"));
            r.messageBottom &&
                (q([r.messageBottom], e), v(e, p.header.length - 1));
            q = y(f, "cols");
            n("worksheet", f).prepend(q);
            v = 0;
            for (C = p.header.length; v < C; v++)
                q.appendChild(
                    y(f, "col", {
                        attr: {
                            min: v + 1,
                            max: v + 1,
                            width: V(p, v),
                            customWidth: 1,
                        },
                    })
                );
            q = a.xl["workbook.xml"];
            n("sheets sheet", q).attr("name", Q(b));
            b.autoFilter &&
                (n("mergeCells", f).before(
                    y(f, "autoFilter", {
                        attr: {
                            ref: "A" + d + ":" + I(p.header.length - 1) + w,
                        },
                    })
                ),
                n("definedNames", q).append(
                    y(q, "definedName", {
                        attr: {
                            name: "_xlnm._FilterDatabase",
                            localSheetId: "0",
                            hidden: 1,
                        },
                        text:
                            Q(b) +
                            "!$A$" +
                            d +
                            ":" +
                            I(p.header.length - 1) +
                            w,
                    })
                ));
            b.customize && b.customize(a, b, c);
            0 === n("mergeCells", f).children().length &&
                n("mergeCells", f).remove();
            c = new (E || u.JSZip)();
            d = {
                type: "blob",
                mimeType:
                    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            };
            O(c, a);
            c.generateAsync
                ? c.generateAsync(d).then(function (k) {
                      K(k, r.filename);
                      m.processing(!1);
                  })
                : (K(c.generate(d), r.filename), this.processing(!1));
        },
        filename: "*",
        extension: ".xlsx",
        exportOptions: {},
        header: !0,
        footer: !1,
        title: "*",
        messageTop: "*",
        messageBottom: "*",
        createEmptyCells: !1,
        autoFilter: !1,
        sheetName: "",
    };
    D.ext.buttons.pdfHtml5 = {
        className: "buttons-pdf buttons-html5",
        available: function () {
            return u.FileReader !== B && (F || u.pdfMake);
        },
        text: function (a) {
            return a.i18n("buttons.pdf", "PDF");
        },
        action: function (a, c, d, b) {
            this.processing(!0);
            d = c.buttons.exportData(b.exportOptions);
            a = c.buttons.exportInfo(b);
            var m = [];
            b.header &&
                m.push(
                    n.map(d.header, function (g) {
                        return {
                            text: "string" === typeof g ? g : g + "",
                            style: "tableHeader",
                        };
                    })
                );
            for (var e = 0, f = d.body.length; e < f; e++)
                m.push(
                    n.map(d.body[e], function (g) {
                        if (null === g || g === B) g = "";
                        return {
                            text: "string" === typeof g ? g : g + "",
                            style: e % 2 ? "tableBodyEven" : "tableBodyOdd",
                        };
                    })
                );
            b.footer &&
                d.footer &&
                m.push(
                    n.map(d.footer, function (g) {
                        return {
                            text: "string" === typeof g ? g : g + "",
                            style: "tableFooter",
                        };
                    })
                );
            d = {
                pageSize: b.pageSize,
                pageOrientation: b.orientation,
                content: [
                    { table: { headerRows: 1, body: m }, layout: "noBorders" },
                ],
                styles: {
                    tableHeader: {
                        bold: !0,
                        fontSize: 11,
                        color: "white",
                        fillColor: "#2d4154",
                        alignment: "center",
                    },
                    tableBodyEven: {},
                    tableBodyOdd: { fillColor: "#f3f3f3" },
                    tableFooter: {
                        bold: !0,
                        fontSize: 11,
                        color: "white",
                        fillColor: "#2d4154",
                    },
                    title: { alignment: "center", fontSize: 15 },
                    message: {},
                },
                defaultStyle: { fontSize: 10 },
            };
            a.messageTop &&
                d.content.unshift({
                    text: a.messageTop,
                    style: "message",
                    margin: [0, 0, 0, 12],
                });
            a.messageBottom &&
                d.content.push({
                    text: a.messageBottom,
                    style: "message",
                    margin: [0, 0, 0, 12],
                });
            a.title &&
                d.content.unshift({
                    text: a.title,
                    style: "title",
                    margin: [0, 0, 0, 12],
                });
            b.customize && b.customize(d, b, c);
            c = (F || u.pdfMake).createPdf(d);
            "open" !== b.download || T() ? c.download(a.filename) : c.open();
            this.processing(!1);
        },
        title: "*",
        filename: "*",
        extension: ".pdf",
        exportOptions: {},
        orientation: "portrait",
        pageSize: "A4",
        header: !0,
        footer: !1,
        messageTop: "*",
        messageBottom: "*",
        customize: null,
        download: "download",
    };
    return D.Buttons;
});

// buttons.print.min.js
/*!
 Print button for Buttons and DataTables.
 2016 SpryMedia Ltd - datatables.net/license
*/
(function (b) {
    "function" === typeof define && define.amd
        ? define([
              "jquery",
              "datatables.net",
              "datatables.net-buttons",
          ], function (c) {
              return b(c, window, document);
          })
        : "object" === typeof exports
        ? (module.exports = function (c, g) {
              c || (c = window);
              (g && g.fn.dataTable) || (g = require("datatables.net")(c, g).$);
              g.fn.dataTable.Buttons || require("datatables.net-buttons")(c, g);
              return b(g, c, c.document);
          })
        : b(jQuery, window, document);
})(function (b, c, g, y) {
    var u = b.fn.dataTable,
        n = g.createElement("a"),
        v = function (a) {
            n.href = a;
            a = n.host;
            -1 === a.indexOf("/") &&
                0 !== n.pathname.indexOf("/") &&
                (a += "/");
            return n.protocol + "//" + a + n.pathname + n.search;
        };
    u.ext.buttons.print = {
        className: "buttons-print",
        text: function (a) {
            return a.i18n("buttons.print", "Print");
        },
        action: function (a, k, p, h) {
            a = k.buttons.exportData(
                b.extend({ decodeEntities: !1 }, h.exportOptions)
            );
            p = k.buttons.exportInfo(h);
            var w = k
                    .columns(h.exportOptions.columns)
                    .flatten()
                    .map(function (d) {
                        return k.settings()[0].aoColumns[k.column(d).index()].sClass;
                    })
                    .toArray(),
                r = function (d, e) {
                    for (var x = "<tr>", l = 0, z = d.length; l < z; l++)
                        x +=
                            "<" +
                            e +
                            " " +
                            (w[l] ? 'class="' + w[l] + '"' : "") +
                            ">" +
                            (null === d[l] || d[l] === y ? "" : d[l]) +
                            "</" +
                            e +
                            ">";
                    return x + "</tr>";
                },
                m = '<table class="' + k.table().node().className + '">';
            h.header && (m += "<thead>" + r(a.header, "th") + "</thead>");
            m += "<tbody>";
            for (var t = 0, A = a.body.length; t < A; t++)
                m += r(a.body[t], "td");
            m += "</tbody>";
            h.footer &&
                a.footer &&
                (m += "<tfoot>" + r(a.footer, "th") + "</tfoot>");
            m += "</table>";
            var f = c.open("", "");
            f.document.close();
            var q = "<title>" + p.title + "</title>";
            b("style, link").each(function () {
                var d = q,
                    e = b(this).clone()[0];
                "link" === e.nodeName.toLowerCase() && (e.href = v(e.href));
                q = d + e.outerHTML;
            });
            try {
                f.document.head.innerHTML = q;
            } catch (d) {
                b(f.document.head).html(q);
            }
            f.document.body.innerHTML =
                "<h1>" +
                p.title +
                "</h1><div>" +
                (p.messageTop || "") +
                "</div>" +
                m +
                "<div>" +
                (p.messageBottom || "") +
                "</div>";
            b(f.document.body).addClass("dt-print-view");
            b("img", f.document.body).each(function (d, e) {
                e.setAttribute("src", v(e.getAttribute("src")));
            });
            h.customize && h.customize(f, h, k);
            a = function () {
                h.autoPrint && (f.print(), f.close());
            };
            navigator.userAgent.match(/Trident\/\d.\d/)
                ? a()
                : f.setTimeout(a, 1e3);
        },
        title: "*",
        messageTop: "*",
        messageBottom: "*",
        exportOptions: {},
        header: !0,
        footer: !1,
        autoPrint: !0,
        customize: null,
    };
    return u.Buttons;
});

// buttons.colVis.min.js
/*!
 Column visibility buttons for Buttons and DataTables.
 2016 SpryMedia Ltd - datatables.net/license
*/
(function (g) {
    "function" === typeof define && define.amd
        ? define([
              "jquery",
              "datatables.net",
              "datatables.net-buttons",
          ], function (e) {
              return g(e, window, document);
          })
        : "object" === typeof exports
        ? (module.exports = function (e, f) {
              e || (e = window);
              (f && f.fn.dataTable) || (f = require("datatables.net")(e, f).$);
              f.fn.dataTable.Buttons || require("datatables.net-buttons")(e, f);
              return g(f, e, e.document);
          })
        : g(jQuery, window, document);
})(function (g, e, f, l) {
    e = g.fn.dataTable;
    g.extend(e.ext.buttons, {
        colvis: function (b, a) {
            return {
                extend: "collection",
                text: function (c) {
                    return c.i18n("buttons.colvis", "Column visibility");
                },
                className: "buttons-colvis",
                buttons: [
                    {
                        extend: "columnsToggle",
                        columns: a.columns,
                        columnText: a.columnText,
                    },
                ],
            };
        },
        columnsToggle: function (b, a) {
            return b
                .columns(a.columns)
                .indexes()
                .map(function (c) {
                    return {
                        extend: "columnToggle",
                        columns: c,
                        columnText: a.columnText,
                    };
                })
                .toArray();
        },
        columnToggle: function (b, a) {
            return {
                extend: "columnVisibility",
                columns: a.columns,
                columnText: a.columnText,
            };
        },
        columnsVisibility: function (b, a) {
            return b
                .columns(a.columns)
                .indexes()
                .map(function (c) {
                    return {
                        extend: "columnVisibility",
                        columns: c,
                        visibility: a.visibility,
                        columnText: a.columnText,
                    };
                })
                .toArray();
        },
        columnVisibility: {
            columns: l,
            text: function (b, a, c) {
                return c._columnText(b, c);
            },
            className: "buttons-columnVisibility",
            action: function (b, a, c, d) {
                b = a.columns(d.columns);
                a = b.visible();
                b.visible(
                    d.visibility !== l ? d.visibility : !(a.length && a[0])
                );
            },
            init: function (b, a, c) {
                var d = this;
                a.attr("data-cv-idx", c.columns);
                b.on("column-visibility.dt" + c.namespace, function (h, k) {
                    k.bDestroying ||
                        k.nTable != b.settings()[0].nTable ||
                        d.active(b.column(c.columns).visible());
                }).on("column-reorder.dt" + c.namespace, function (h, k, m) {
                    1 === b.columns(c.columns).count() &&
                        (d.text(c._columnText(b, c)),
                        d.active(b.column(c.columns).visible()));
                });
                this.active(b.column(c.columns).visible());
            },
            destroy: function (b, a, c) {
                b.off("column-visibility.dt" + c.namespace).off(
                    "column-reorder.dt" + c.namespace
                );
            },
            _columnText: function (b, a) {
                var c = b.column(a.columns).index(),
                    d = b.settings()[0].aoColumns[c].sTitle;
                d || (d = b.column(c).header().innerHTML);
                d = d
                    .replace(/\n/g, " ")
                    .replace(/<br\s*\/?>/gi, " ")
                    .replace(/<select(.*?)<\/select>/g, "")
                    .replace(/<!\-\-.*?\-\->/g, "")
                    .replace(/<.*?>/g, "")
                    .replace(/^\s+|\s+$/g, "");
                return a.columnText ? a.columnText(b, c, d) : d;
            },
        },
        colvisRestore: {
            className: "buttons-colvisRestore",
            text: function (b) {
                return b.i18n("buttons.colvisRestore", "Restore visibility");
            },
            init: function (b, a, c) {
                c._visOriginal = b
                    .columns()
                    .indexes()
                    .map(function (d) {
                        return b.column(d).visible();
                    })
                    .toArray();
            },
            action: function (b, a, c, d) {
                a.columns().every(function (h) {
                    h =
                        a.colReorder && a.colReorder.transpose
                            ? a.colReorder.transpose(h, "toOriginal")
                            : h;
                    this.visible(d._visOriginal[h]);
                });
            },
        },
        colvisGroup: {
            className: "buttons-colvisGroup",
            action: function (b, a, c, d) {
                a.columns(d.show).visible(!0, !1);
                a.columns(d.hide).visible(!1, !1);
                a.columns.adjust();
            },
            show: [],
            hide: [],
        },
    });
    return e.Buttons;
});
