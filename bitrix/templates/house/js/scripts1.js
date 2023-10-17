var path_href;
var button_href;
function _classCallCheck(e, i) {
    if (!(e instanceof i)) throw new TypeError("Cannot call a class as a function")
}! function(e, i) {
    "function" == typeof define && define.amd ? define("ev-emitter/ev-emitter", i) : "object" == typeof module && module.exports ? module.exports = i() : e.EvEmitter = i()
}("undefined" != typeof window ? window : this, function() {
    function e() {}
    var i = e.prototype;
    return i.on = function(e, i) {
        if (e && i) {
            var s = this._events = this._events || {},
                o = s[e] = s[e] || [];
            return -1 == o.indexOf(i) && o.push(i), this
        }
    }, i.once = function(e, i) {
        if (e && i) {
            this.on(e, i);
            var s = this._onceEvents = this._onceEvents || {};
            return (s[e] = s[e] || {})[i] = !0, this
        }
    }, i.off = function(e, i) {
        var s = this._events && this._events[e];
        if (s && s.length) {
            var o = s.indexOf(i);
            return -1 != o && s.splice(o, 1), this
        }
    }, i.emitEvent = function(e, i) {
        var s = this._events && this._events[e];
        if (s && s.length) {
            var o = 0,
                t = s[o];
            i = i || [];
            for (var n = this._onceEvents && this._onceEvents[e]; t;) {
                var a = n && n[t];
                a && (this.off(e, t), delete n[t]), t.apply(this, i), t = s[o += a ? 0 : 1]
            }
            return this
        }
    }, i.allOff = i.removeAllListeners = function() {
        delete this._events, delete this._onceEvents
    }, e
}),
function(e, i) {
    "use strict";
    "function" == typeof define && define.amd ? define(["ev-emitter/ev-emitter"], function(s) {
        return i(e, s)
    }) : "object" == typeof module && module.exports ? module.exports = i(e, require("ev-emitter")) : e.imagesLoaded = i(e, e.EvEmitter)
}("undefined" != typeof window ? window : this, function(e, i) {
    function s(e, i) {
        for (var s in i) e[s] = i[s];
        return e
    }

    function o(e, i, t) {
        return this instanceof o ? ("string" == typeof e && (e = document.querySelectorAll(e)), this.elements = function(e) {
            var i = [];
            if (Array.isArray(e)) i = e;
            else if ("number" == typeof e.length)
                for (var s = 0; s < e.length; s++) i.push(e[s]);
            else i.push(e);
            return i
        }(e), this.options = s({}, this.options), "function" == typeof i ? t = i : s(this.options, i), t && this.on("always", t), this.getImages(), a && (this.jqDeferred = new a.Deferred), void setTimeout(function() {
            this.check()
        }.bind(this))) : new o(e, i, t)
    }

    function t(e) {
        this.img = e
    }

    function n(e, i) {
        this.url = e, this.element = i, this.img = new Image
    }
    var a = e.jQuery,
        r = e.console;
    (o.prototype = Object.create(i.prototype)).options = {}, o.prototype.getImages = function() {
        this.images = [], this.elements.forEach(this.addElementImages, this)
    }, o.prototype.addElementImages = function(e) {
        "IMG" == e.nodeName && this.addImage(e), !0 === this.options.background && this.addElementBackgroundImages(e);
        var i = e.nodeType;
        if (i && l[i]) {
            for (var s = e.querySelectorAll("img"), o = 0; o < s.length; o++) {
                var t = s[o];
                this.addImage(t)
            }
            if ("string" == typeof this.options.background) {
                var n = e.querySelectorAll(this.options.background);
                for (o = 0; o < n.length; o++) {
                    var a = n[o];
                    this.addElementBackgroundImages(a)
                }
            }
        }
    };
    var l = {
        1: !0,
        9: !0,
        11: !0
    };
    return o.prototype.addElementBackgroundImages = function(e) {
        var i = getComputedStyle(e);
        if (i)
            for (var s = /url\((['"])?(.*?)\1\)/gi, o = s.exec(i.backgroundImage); null !== o;) {
                var t = o && o[2];
                t && this.addBackground(t, e), o = s.exec(i.backgroundImage)
            }
    }, o.prototype.addImage = function(e) {
        var i = new t(e);
        this.images.push(i)
    }, o.prototype.addBackground = function(e, i) {
        var s = new n(e, i);
        this.images.push(s)
    }, o.prototype.check = function() {
        function e(e, s, o) {
            setTimeout(function() {
                i.progress(e, s, o)
            })
        }
        var i = this;
        return this.progressedCount = 0, this.hasAnyBroken = !1, this.images.length ? void this.images.forEach(function(i) {
            i.once("progress", e), i.check()
        }) : void this.complete()
    }, o.prototype.progress = function(e, i, s) {
        this.progressedCount++, this.hasAnyBroken = this.hasAnyBroken || !e.isLoaded, this.emitEvent("progress", [this, e, i]), this.jqDeferred && this.jqDeferred.notify && this.jqDeferred.notify(this, e), this.progressedCount == this.images.length && this.complete(), this.options.debug && r && r.log("progress: " + s, e, i)
    }, o.prototype.complete = function() {
        var e = this.hasAnyBroken ? "fail" : "done";
        if (this.isComplete = !0, this.emitEvent(e, [this]), this.emitEvent("always", [this]), this.jqDeferred) {
            var i = this.hasAnyBroken ? "reject" : "resolve";
            this.jqDeferred[i](this)
        }
    }, t.prototype = Object.create(i.prototype), t.prototype.check = function() {
        return this.getIsImageComplete() ? void this.confirm(0 !== this.img.naturalWidth, "naturalWidth") : (this.proxyImage = new Image, this.proxyImage.addEventListener("load", this), this.proxyImage.addEventListener("error", this), this.img.addEventListener("load", this), this.img.addEventListener("error", this), void(this.proxyImage.src = this.img.src))
    }, t.prototype.getIsImageComplete = function() {
        return this.img.complete && void 0 !== this.img.naturalWidth
    }, t.prototype.confirm = function(e, i) {
        this.isLoaded = e, this.emitEvent("progress", [this, this.img, i])
    }, t.prototype.handleEvent = function(e) {
        var i = "on" + e.type;
        this[i] && this[i](e)
    }, t.prototype.onload = function() {
        this.confirm(!0, "onload"), this.unbindEvents()
    }, t.prototype.onerror = function() {
        this.confirm(!1, "onerror"), this.unbindEvents()
    }, t.prototype.unbindEvents = function() {
        this.proxyImage.removeEventListener("load", this), this.proxyImage.removeEventListener("error", this), this.img.removeEventListener("load", this), this.img.removeEventListener("error", this)
    }, n.prototype = Object.create(t.prototype), n.prototype.check = function() {
        this.img.addEventListener("load", this), this.img.addEventListener("error", this), this.img.src = this.url;
        this.getIsImageComplete() && (this.confirm(0 !== this.img.naturalWidth, "naturalWidth"), this.unbindEvents())
    }, n.prototype.unbindEvents = function() {
        this.img.removeEventListener("load", this), this.img.removeEventListener("error", this)
    }, n.prototype.confirm = function(e, i) {
        this.isLoaded = e, this.emitEvent("progress", [this, this.element, i])
    }, o.makeJQueryPlugin = function(i) {
        (i = i || e.jQuery) && (a = i, a.fn.imagesLoaded = function(e, i) {
            return new o(this, e, i).jqDeferred.promise(a(this))
        })
    }, o.makeJQueryPlugin(), o
});
var _createClass = function() {
    function e(e, i) {
        for (var s, o = 0; o < i.length; o++) s = i[o], s.enumerable = s.enumerable || !1, s.configurable = !0, "value" in s && (s.writable = !0), Object.defineProperty(e, s.key, s)
    }
    return function(i, s, o) {
        return s && e(i.prototype, s), o && e(i, o), i
    }
}();
! function() {
    function e(e, i) {
        var s = e.width() / 1920;
        e.css({
            height: 1530 * s + "px"
        }), i.css({
            transform: "scale(" + s + ")"
        })
    }

    function i(e, i) {
        this.create = function() {
            this.x = i.x || 0, this.y = i.y || 0, this.vx = Math.random() * i.dispersion, this.v = 5 * Math.random(), this.g = 1 + 3 * Math.random(), this.size = 5 * Math.random(), this.max = Math.random() * i.destroyOffset, this.vx *= -1
        }, this.draw = function() {
            this.x -= this.vx, this.g += .1, this.y += this.g, e.fillStyle = i.colors[function(e, i) {
                return Math.floor(e + Math.random() * (i + 1 - e))
            }(0, i.colors.length - 1)], e.beginPath(), e.arc(this.x, this.y, this.size, 0, 2 * Math.PI, !1), e.fill(), this.y > this.max && this.create()
        }, this.create()
    }

    function s(e) {
        function s() {
            a.clearRect(0, 0, o.width, o.height), t.forEach(function(e) {
                return e.draw()
            }), n && requestAnimationFrame(s)
        }
        var o = {
                canvas: null,
                width: 100,
                height: 100,
                count: 50,
                interval: 20,
                destroyOffset: 300,
                dispersion: 5
            },
            t = [],
            n = !0;
        (o = Object.assign(o, e || {})).canvas.width = o.width, o.canvas.height = o.height;
        for (var a = o.canvas.getContext("2d"), r = 0; r < o.count; r++) setTimeout(function() {
            t.push(new i(a, e))
        }, r);
        s(), this.stop = function() {
            n = !1
        }, this.start = function() {
            n = !0, s()
        }
    }
    var o = function() {
            function e() {
                var i = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : [];
                _classCallCheck(this, e), this.list = i
            }
            return _createClass(e, [{
                key: "start",
                value: function() {
                    for (var e = this, i = 0, s = function(s) {
                            i += e.list[s].delay, setTimeout(function() {
                                return e.list[s].callback()
                            }, i)
                        }, o = 0; o < this.list.length; o++) s(o)
                }
            }]), e
        }(),
        t = $(".house-container"),
        n = $(".house-container .house"),
        a = [];
    a.push({
            delay: 200,
            callback: function() {
                ! function() {
                    var e = new s({
                        canvas: $(".house-container .soil-particles").get(0),
                        width: 1920,
                        height: 867,
                        count: 2e3,
                        interval: 10,
                        destroyOffset: 600,
                        dispersion: 5,
                        colors: ["#74685D", "#554641", "#695F56", "#8A775B", "#3B2B2D"]
                    });
                    $(".house-container .soil-container .soil").removeClass("house-item-hidden"), new o([{
                        delay: 3e3,
                        callback: function() {
                            $(".house-container .soil-particles").addClass("house-item-hidden")
                        }
                    }, {
                        delay: 300,
                        callback: function() {
                            e.stop()
                        }
                    }]).start()
                }()
            }
        }), a.push({
            delay: 3e3,
            callback: function() {
                ! function() {
                    var e = new s({
                        canvas: $(".house-container .sand-particles").get(0),
                        width: 1920,
                        height: 867,
                        count: 2e3,
                        interval: 10,
                        destroyOffset: 600,
                        dispersion: 5,
                        colors: ["#CDB298", "#CCA286", "#A38A70", "#928070"]
                    });
                    $(".house-container .sand-container .sand").removeClass("house-item-hidden"), new o([{
                        delay: 3e3,
                        callback: function() {
                            $(".house-container .sand-particles").addClass("house-item-hidden")
                        }
                    }, {
                        delay: 300,
                        callback: function() {
                            e.stop()
                        }
                    }]).start()
                }()
            }
        }), a.push({
            delay: 3e3,
            callback: function() {
                $(".house-container .foundation").removeClass("house-item-hidden"),
                    function() {
                        var e = new s({
                            canvas: $(".house-container .cement-particles").get(0),
                            width: 1389,
                            height: 426,
                            count: 2e3,
                            interval: 10,
                            destroyOffset: 150,
                            dispersion: 2,
                            x: 300,
                            y: 0,
                            colors: ["#757573", "#A5A5A5", "#CECFCF"]
                        });
                        $(".house-container .floor").removeClass("house-item-hidden"), $(".house-container .pipe").removeClass("house-item-hidden"), new o([{
                            delay: 2e3,
                            callback: function() {
                                $(".house-container .cement-particles").addClass("house-item-hidden"), $(".house-container .pipe").addClass("house-item-hidden")
                            }
                        }, {
                            delay: 300,
                            callback: function() {
                                e.stop()
                            }
                        }]).start()
                    }()
            }
        }), a.push({
            delay: 2500,
            callback: function() {
                (function() {
                    var e = [];
                    return [{
                        class: ".house-container .door-wall",
                        rows: 13
                    }, {
                        class: ".house-container .back-wall",
                        rows: 14
                    }, {
                        class: ".house-container .front-window-wall",
                        rows: 14
                    }, {
                        class: ".house-container .front-side-wall",
                        rows: 26
                    }, {
                        class: ".house-container .back-side-wall",
                        rows: 26
                    }].forEach(function(i) {
                        for (var s = [], o = function(e) {
                                s.push({
                                    delay: 150,
                                    callback: function() {
                                        $(i.class + " .row-" + e).removeClass("house-item-hidden")
                                    }
                                })
                            }, t = 1; t <= i.rows; t++) o(t);
                        e.push(s)
                    }), e
                })().forEach(function(e) {
                    new o(e).start()
                })
            }
        }), a.push({
            delay: 2500,
            callback: function() {
                $(".house-container .walls-shadow").removeClass("house-item-hidden"), $(".house-container .peak").removeClass("house-item-hidden")
            }
        }), a.push({
            delay: 1400,
            callback: function() {
                $(".house-container .rafters").removeClass("house-item-hidden"), $(".house-container .planks").removeClass("house-item-hidden")
            }
        }), a.push({
            delay: 700,
            callback: function() {
                new o(function() {
                    for (var e = [], i = function(i) {
                            e.push({
                                delay: 100,
                                callback: function() {
                                    $(".house-container .plate.plate-" + i).removeClass("house-item-hidden")
                                }
                            })
                        }, s = 14; 1 <= s; s--) i(s);
                    return e
                }()).start()
            }
        }), a.push({
            delay: 1400,
            callback: function() {
                $(".house-container .ridge").removeClass("house-item-hidden"), $(".house-container .roof-shadow").removeClass("house-item-hidden")
            }
        }), a.push({
            delay: 500,
            callback: function() {
                $(".house-container .facing").removeClass("house-item-hidden"), new o(function() {
                    var e = [];
                    return [".house-container .roof-window-1", ".house-container .roof-window-2", ".house-container .round-window", ".house-container .door", ".house-container .window-1", ".house-container .window-2", ".house-container .panorama-window"].forEach(function(i) {
                        e.push({
                            delay: 150,
                            callback: function() {
                                $(i).removeClass("house-item-hidden")
                            }
                        })
                    }), e
                }()).start()
            }
        }), a.push({
            delay: 1550,
            callback: function() {
                $(".house-container .foundation-bricks").removeClass("house-item-hidden")
            }
        }), a.push({
            delay: 500,
            callback: function() {
                $(".house-container .house-stairs").removeClass("house-item-hidden")
            }
        }), a.push({
            delay: 200,
            callback: function() {
                $(".house-container .outside-stairs").removeClass("house-item-hidden")
            }
        }), a.push({
            delay: 200,
            callback: function() {
                $(".house-container .outside-border").removeClass("house-item-hidden")
            }
        }), a.push({
            delay: 200,
            callback: function() {
                new o(function() {
                    for (var e = [], i = function(i) {
                            e.push({
                                delay: 100,
                                callback: function() {
                                    $(".house-container .tile-row.row-" + i).removeClass("house-item-hidden")
                                }
                            })
                        }, s = 1; 26 >= s; s++) i(s);
                    return e
                }()).start()
            }
        }), a.push({
            delay: 2600,
            callback: function() {
                $(".house-container .grass").removeClass("house-item-hidden")
            }
        }), a.push({
            delay: 200,
            callback: function() {
                $(".house-container .urn").removeClass("house-item-hidden"), $(".house-container .urn-shadow").removeClass("house-item-hidden")
            }
        }), a.push({
            delay: 200,
            callback: function() {
                $(".house-container .bench").removeClass("house-item-hidden"), $(".house-container .bench-shadow").removeClass("house-item-hidden")
            }
        }), a.push({
            delay: 200,
            callback: function() {
                $(".house-container .lion").removeClass("house-item-hidden"), $(".house-container .lion-shadow").removeClass("house-item-hidden")
            }
        }), a.push({
            delay: 200,
            callback: function() {
                $(".house-container .bush-left").removeClass("house-item-hidden"), $(".house-container .bush-left-shadow").removeClass("house-item-hidden")
            }
        }), a.push({
            delay: 200,
            callback: function() {
                $(".house-container .bush-right").removeClass("house-item-hidden"), $(".house-container .bush-right-shadow").removeClass("house-item-hidden")
            }
        }), a.push({
            delay: 300,
            callback: function() {
                $(".house-container .shapes").removeClass("house-item-hidden")
            }
        }), e(t, n),
        function(e, i) {
            var s = e.length;
            e.forEach(function(e) {
                var o = new Image;
                o.addEventListener("load", function() {
                    (s -= 1) || "function" != typeof i || i()
                }), o.src = e
            })
        }(["/bitrix/house/images/bench-shadow.png", "/bitrix/house/images/bench.png", "/bitrix/house/images/bush-left-shadow.png", "/bitrix/house/images/bush-left.png", "/bitrix/house/images/bush-right-shadow.png", "/bitrix/house/images/bush-right.png", "/bitrix/house/images/facing.png", "/bitrix/house/images/foundation-box.png", "/bitrix/house/images/foundation-bricks.png", "/bitrix/house/images/foundation-floor.png", "/bitrix/house/images/foundation.png", "/bitrix/house/images/grass.png", "/bitrix/house/images/house-stairs.png", "/bitrix/house/images/lion-shadow.png", "/bitrix/house/images/lion.png", "/bitrix/house/images/outside-border.png", "/bitrix/house/images/outside-stairs.png", "/bitrix/house/images/panaram-window.png", "/bitrix/house/images/peak.png", "/bitrix/house/images/pipe.png", "/bitrix/house/images/roof/planks.png", "/bitrix/house/images/roof/plate-1.png", "/bitrix/house/images/roof/plate-10.png", "/bitrix/house/images/roof/plate-11.png", "/bitrix/house/images/roof/plate-12.png", "/bitrix/house/images/roof/plate-13.png", "/bitrix/house/images/roof/plate-14.png", "/bitrix/house/images/roof/plate-2.png", "/bitrix/house/images/roof/plate-3.png", "/bitrix/house/images/roof/plate-4.png", "/bitrix/house/images/roof/plate-5.png", "/bitrix/house/images/roof/plate-6.png", "/bitrix/house/images/roof/plate-7.png", "/bitrix/house/images/roof/plate-8.png", "/bitrix/house/images/roof/plate-9.png", "/bitrix/house/images/roof/rafters.png", "/bitrix/house/images/roof/ridge.png", "/bitrix/house/images/roof/roof-shadow.png", "/bitrix/house/images/sand-soil.png", "/bitrix/house/images/sand.png", "/bitrix/house/images/soil-full.png", "/bitrix/house/images/soil.png", "/bitrix/house/images/tile/row-1.png", "/bitrix/house/images/tile/row-10.png", "/bitrix/house/images/tile/row-11.png", "/bitrix/house/images/tile/row-12.png", "/bitrix/house/images/tile/row-13.png", "/bitrix/house/images/tile/row-14.png", "/bitrix/house/images/tile/row-15.png", "/bitrix/house/images/tile/row-16.png", "/bitrix/house/images/tile/row-17.png", "/bitrix/house/images/tile/row-18.png", "/bitrix/house/images/tile/row-19.png", "/bitrix/house/images/tile/row-2.png", "/bitrix/house/images/tile/row-20.png", "/bitrix/house/images/tile/row-21.png", "/bitrix/house/images/tile/row-22.png", "/bitrix/house/images/tile/row-23.png", "/bitrix/house/images/tile/row-24.png", "/bitrix/house/images/tile/row-25.png", "/bitrix/house/images/tile/row-26.png", "/bitrix/house/images/tile/row-3.png", "/bitrix/house/images/tile/row-4.png", "/bitrix/house/images/tile/row-5.png", "/bitrix/house/images/tile/row-6.png", "/bitrix/house/images/tile/row-7.png", "/bitrix/house/images/tile/row-8.png", "/bitrix/house/images/tile/row-9.png", "/bitrix/house/images/tree-shadow.png", "/bitrix/house/images/urn-shadow.png", "/bitrix/house/images/urn.png", "/bitrix/house/images/walls/back-side-wall/row-1.png", "/bitrix/house/images/walls/back-side-wall/row-10.png", "/bitrix/house/images/walls/back-side-wall/row-11.png", "/bitrix/house/images/walls/back-side-wall/row-12.png", "/bitrix/house/images/walls/back-side-wall/row-13.png", "/bitrix/house/images/walls/back-side-wall/row-14.png", "/bitrix/house/images/walls/back-side-wall/row-15.png", "/bitrix/house/images/walls/back-side-wall/row-16.png", "/bitrix/house/images/walls/back-side-wall/row-17.png", "/bitrix/house/images/walls/back-side-wall/row-18.png", "/bitrix/house/images/walls/back-side-wall/row-19.png", "/bitrix/house/images/walls/back-side-wall/row-2.png", "/bitrix/house/images/walls/back-side-wall/row-20.png", "/bitrix/house/images/walls/back-side-wall/row-21.png", "/bitrix/house/images/walls/back-side-wall/row-22.png", "/bitrix/house/images/walls/back-side-wall/row-23.png", "/bitrix/house/images/walls/back-side-wall/row-24.png", "/bitrix/house/images/walls/back-side-wall/row-25.png", "/bitrix/house/images/walls/back-side-wall/row-26.png", "/bitrix/house/images/walls/back-side-wall/row-3.png", "/bitrix/house/images/walls/back-side-wall/row-4.png", "/bitrix/house/images/walls/back-side-wall/row-5.png", "/bitrix/house/images/walls/back-side-wall/row-6.png", "/bitrix/house/images/walls/back-side-wall/row-7.png", "/bitrix/house/images/walls/back-side-wall/row-8.png", "/bitrix/house/images/walls/back-side-wall/row-9.png", "/bitrix/house/images/walls/back-wall/row-1.png", "/bitrix/house/images/walls/back-wall/row-10.png", "/bitrix/house/images/walls/back-wall/row-11.png", "/bitrix/house/images/walls/back-wall/row-12.png", "/bitrix/house/images/walls/back-wall/row-13.png", "/bitrix/house/images/walls/back-wall/row-14.png", "/bitrix/house/images/walls/back-wall/row-2.png", "/bitrix/house/images/walls/back-wall/row-3.png", "/bitrix/house/images/walls/back-wall/row-4.png", "/bitrix/house/images/walls/back-wall/row-5.png", "/bitrix/house/images/walls/back-wall/row-6.png", "/bitrix/house/images/walls/back-wall/row-7.png", "/bitrix/house/images/walls/back-wall/row-8.png", "/bitrix/house/images/walls/back-wall/row-9.png", "/bitrix/house/images/walls/door-wall/row-1.png", "/bitrix/house/images/walls/door-wall/row-10.png", "/bitrix/house/images/walls/door-wall/row-11.png", "/bitrix/house/images/walls/door-wall/row-12.png", "/bitrix/house/images/walls/door-wall/row-13.png", "/bitrix/house/images/walls/door-wall/row-2.png", "/bitrix/house/images/walls/door-wall/row-3.png", "/bitrix/house/images/walls/door-wall/row-4.png", "/bitrix/house/images/walls/door-wall/row-5.png", "/bitrix/house/images/walls/door-wall/row-6.png", "/bitrix/house/images/walls/door-wall/row-7.png", "/bitrix/house/images/walls/door-wall/row-8.png", "/bitrix/house/images/walls/door-wall/row-9.png", "/bitrix/house/images/walls/front-side-wall/row-1.png", "/bitrix/house/images/walls/front-side-wall/row-10.png", "/bitrix/house/images/walls/front-side-wall/row-11.png", "/bitrix/house/images/walls/front-side-wall/row-12.png", "/bitrix/house/images/walls/front-side-wall/row-13.png", "/bitrix/house/images/walls/front-side-wall/row-14.png", "/bitrix/house/images/walls/front-side-wall/row-15.png", "/bitrix/house/images/walls/front-side-wall/row-16.png", "/bitrix/house/images/walls/front-side-wall/row-17.png", "/bitrix/house/images/walls/front-side-wall/row-18.png", "/bitrix/house/images/walls/front-side-wall/row-19.png", "/bitrix/house/images/walls/front-side-wall/row-2.png", "/bitrix/house/images/walls/front-side-wall/row-20.png", "/bitrix/house/images/walls/front-side-wall/row-21.png", "/bitrix/house/images/walls/front-side-wall/row-22.png", "/bitrix/house/images/walls/front-side-wall/row-23.png", "/bitrix/house/images/walls/front-side-wall/row-24.png", "/bitrix/house/images/walls/front-side-wall/row-25.png", "/bitrix/house/images/walls/front-side-wall/row-26.png", "/bitrix/house/images/walls/front-side-wall/row-3.png", "/bitrix/house/images/walls/front-side-wall/row-4.png", "/bitrix/house/images/walls/front-side-wall/row-5.png", "/bitrix/house/images/walls/front-side-wall/row-6.png", "/bitrix/house/images/walls/front-side-wall/row-7.png", "/bitrix/house/images/walls/front-side-wall/row-8.png", "/bitrix/house/images/walls/front-side-wall/row-9.png", "/bitrix/house/images/walls/front-window-wall/row-1.png", "/bitrix/house/images/walls/front-window-wall/row-10.png", "/bitrix/house/images/walls/front-window-wall/row-11.png", "/bitrix/house/images/walls/front-window-wall/row-12.png", "/bitrix/house/images/walls/front-window-wall/row-13.png", "/bitrix/house/images/walls/front-window-wall/row-14.png", "/bitrix/house/images/walls/front-window-wall/row-2.png", "/bitrix/house/images/walls/front-window-wall/row-3.png", "/bitrix/house/images/walls/front-window-wall/row-4.png", "/bitrix/house/images/walls/front-window-wall/row-5.png", "/bitrix/house/images/walls/front-window-wall/row-6.png", "/bitrix/house/images/walls/front-window-wall/row-7.png", "/bitrix/house/images/walls/front-window-wall/row-8.png", "/bitrix/house/images/walls/front-window-wall/row-9.png", "/bitrix/house/images/walls-shadow.png", "/bitrix/house/images/windows/door.png", "/bitrix/house/images/windows/panorama-window.png", "/bitrix/house/images/windows/roof-window-1.png", "/bitrix/house/images/windows/roof-window-2.png", "/bitrix/house/images/windows/round-window.png", "/bitrix/house/images/windows/window-1.png", "/bitrix/house/images/windows/window-2.png"], function() {
            $(".house-container .loading").addClass("flipOutX"), new o(a).start()
        }), window.addEventListener("resize", function() {
            e(t, n)
        }), $(".house-container .shapes path").on("mouseenter", function() {
            var e = $(this).attr("data-selector");
            t.find(e).addClass("active");
            // подстветка соотв. кнопки при наведении на элемент анимации
            path_href = $(this).attr("data-href");
            $('.header-section-buttons a').each(function(){
            	if (path_href == $(this).attr("href")) {
            		$(this).removeClass("header-section-button-inactive").addClass("header-section-button-active");		
				}
			});
			
        }).on("mouseleave", function() {
            var e = $(this).attr("data-selector");
            t.find(e).removeClass("active");
            // подстветка соотв. кнопки при наведении на элемент анимации (возврат)
            path_href = $(this).attr("data-href");
            $('.header-section-buttons a').each(function(){
            	if (path_href == $(this).attr("href")) {
            		$(this).removeClass("header-section-button-active").addClass("header-section-button-inactive");		
				}
			});

        }).on("click", function() {
            var e = $(this).attr("data-href");
            null !== e && (document.location.href = e)
        })
}();

$(function(){
	// подстветка фрагментов анимации при наведении на кнопки
	$('.header-section-buttons a').hover(function(){
		button_href = $(this).attr("href");
	    $(".house-container .shapes path").each(function(){
	    	if (button_href == $(this).attr("data-href")) {
	    		$(this).addClass("active");		
			}
		});
	},
	function(){
		button_href = $(this).attr("href");
	    $(".house-container .shapes path").each(function(){
	    	if (button_href == $(this).attr("data-href")) {
	    		$(this).removeClass("active");		
			}
		});
	});
});