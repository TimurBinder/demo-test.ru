var path_href;
var button_href;

function _classCallCheck(e, t) {
    if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
}! function(e, t) {
    "function" == typeof define && define.amd ? define("ev-emitter/ev-emitter", t) : "object" == typeof module && module.exports ? module.exports = t() : e.EvEmitter = t()
}("undefined" != typeof window ? window : this, function() {
    function e() {}
    var t = e.prototype;
    return t.on = function(e, t) {
        if (e && t) {
            var n = this._events = this._events || {},
                r = n[e] = n[e] || [];
            return -1 == r.indexOf(t) && r.push(t), this
        }
    }, t.once = function(e, t) {
        if (e && t) {
            this.on(e, t);
            var n = this._onceEvents = this._onceEvents || {};
            return (n[e] = n[e] || {})[t] = !0, this
        }
    }, t.off = function(e, t) {
        var n = this._events && this._events[e];
        if (n && n.length) {
            var r = n.indexOf(t);
            return -1 != r && n.splice(r, 1), this
        }
    }, t.emitEvent = function(e, t) {
        var n = this._events && this._events[e];
        if (n && n.length) {
            var r = 0,
                o = n[r];
            t = t || [];
            for (var i = this._onceEvents && this._onceEvents[e]; o;) {
                var s = i && i[o];
                s && (this.off(e, o), delete i[o]), o.apply(this, t), o = n[r += s ? 0 : 1]
            }
            return this
        }
    }, t.allOff = t.removeAllListeners = function() {
        delete this._events, delete this._onceEvents
    }, e
}),
function(e, t) {
    "use strict";
    "function" == typeof define && define.amd ? define(["ev-emitter/ev-emitter"], function(n) {
        return t(e, n)
    }) : "object" == typeof module && module.exports ? module.exports = t(e, require("ev-emitter")) : e.imagesLoaded = t(e, e.EvEmitter)
}("undefined" != typeof window ? window : this, function(e, t) {
    function n(e, t) {
        for (var n in t) e[n] = t[n];
        return e
    }

    function r(e, t, o) {
        return this instanceof r ? ("string" == typeof e && (e = document.querySelectorAll(e)), this.elements = function(e) {
            var t = [];
            if (Array.isArray(e)) t = e;
            else if ("number" == typeof e.length)
                for (var n = 0; n < e.length; n++) t.push(e[n]);
            else t.push(e);
            return t
        }(e), this.options = n({}, this.options), "function" == typeof t ? o = t : n(this.options, t), o && this.on("always", o), this.getImages(), s && (this.jqDeferred = new s.Deferred), void setTimeout(function() {
            this.check()
        }.bind(this))) : new r(e, t, o)
    }

    function o(e) {
        this.img = e
    }

    function i(e, t) {
        this.url = e, this.element = t, this.img = new Image
    }
    var s = e.jQuery,
        a = e.console;
    (r.prototype = Object.create(t.prototype)).options = {}, r.prototype.getImages = function() {
        this.images = [], this.elements.forEach(this.addElementImages, this)
    }, r.prototype.addElementImages = function(e) {
        "IMG" == e.nodeName && this.addImage(e), !0 === this.options.background && this.addElementBackgroundImages(e);
        var t = e.nodeType;
        if (t && l[t]) {
            for (var n = e.querySelectorAll("img"), r = 0; r < n.length; r++) {
                var o = n[r];
                this.addImage(o)
            }
            if ("string" == typeof this.options.background) {
                var i = e.querySelectorAll(this.options.background);
                for (r = 0; r < i.length; r++) {
                    var s = i[r];
                    this.addElementBackgroundImages(s)
                }
            }
        }
    };
    var l = {
        1: !0,
        9: !0,
        11: !0
    };
    return r.prototype.addElementBackgroundImages = function(e) {
        var t = getComputedStyle(e);
        if (t)
            for (var n = /url\((['"])?(.*?)\1\)/gi, r = n.exec(t.backgroundImage); null !== r;) {
                var o = r && r[2];
                o && this.addBackground(o, e), r = n.exec(t.backgroundImage)
            }
    }, r.prototype.addImage = function(e) {
        var t = new o(e);
        this.images.push(t)
    }, r.prototype.addBackground = function(e, t) {
        var n = new i(e, t);
        this.images.push(n)
    }, r.prototype.check = function() {
        function e(e, n, r) {
            setTimeout(function() {
                t.progress(e, n, r)
            })
        }
        var t = this;
        return this.progressedCount = 0, this.hasAnyBroken = !1, this.images.length ? void this.images.forEach(function(t) {
            t.once("progress", e), t.check()
        }) : void this.complete()
    }, r.prototype.progress = function(e, t, n) {
        this.progressedCount++, this.hasAnyBroken = this.hasAnyBroken || !e.isLoaded, this.emitEvent("progress", [this, e, t]), this.jqDeferred && this.jqDeferred.notify && this.jqDeferred.notify(this, e), this.progressedCount == this.images.length && this.complete(), this.options.debug && a && a.log("progress: " + n, e, t)
    }, r.prototype.complete = function() {
        var e = this.hasAnyBroken ? "fail" : "done";
        if (this.isComplete = !0, this.emitEvent(e, [this]), this.emitEvent("always", [this]), this.jqDeferred) {
            var t = this.hasAnyBroken ? "reject" : "resolve";
            this.jqDeferred[t](this)
        }
    }, o.prototype = Object.create(t.prototype), o.prototype.check = function() {
        return this.getIsImageComplete() ? void this.confirm(0 !== this.img.naturalWidth, "naturalWidth") : (this.proxyImage = new Image, this.proxyImage.addEventListener("load", this), this.proxyImage.addEventListener("error", this), this.img.addEventListener("load", this), this.img.addEventListener("error", this), void(this.proxyImage.src = this.img.src))
    }, o.prototype.getIsImageComplete = function() {
        return this.img.complete && void 0 !== this.img.naturalWidth
    }, o.prototype.confirm = function(e, t) {
        this.isLoaded = e, this.emitEvent("progress", [this, this.img, t])
    }, o.prototype.handleEvent = function(e) {
        var t = "on" + e.type;
        this[t] && this[t](e)
    }, o.prototype.onload = function() {
        this.confirm(!0, "onload"), this.unbindEvents()
    }, o.prototype.onerror = function() {
        this.confirm(!1, "onerror"), this.unbindEvents()
    }, o.prototype.unbindEvents = function() {
        this.proxyImage.removeEventListener("load", this), this.proxyImage.removeEventListener("error", this), this.img.removeEventListener("load", this), this.img.removeEventListener("error", this)
    }, i.prototype = Object.create(o.prototype), i.prototype.check = function() {
        this.img.addEventListener("load", this), this.img.addEventListener("error", this), this.img.src = this.url;
        this.getIsImageComplete() && (this.confirm(0 !== this.img.naturalWidth, "naturalWidth"), this.unbindEvents())
    }, i.prototype.unbindEvents = function() {
        this.img.removeEventListener("load", this), this.img.removeEventListener("error", this)
    }, i.prototype.confirm = function(e, t) {
        this.isLoaded = e, this.emitEvent("progress", [this, this.element, t])
    }, r.makeJQueryPlugin = function(t) {
        (t = t || e.jQuery) && (s = t, s.fn.imagesLoaded = function(e, t) {
            return new r(this, e, t).jqDeferred.promise(s(this))
        })
    }, r.makeJQueryPlugin(), r
});
var _createClass = function() {
    function e(e, t) {
        for (var n, r = 0; r < t.length; r++) n = t[r], n.enumerable = n.enumerable || !1, n.configurable = !0, "value" in n && (n.writable = !0), Object.defineProperty(e, n.key, n)
    }
    return function(t, n, r) {
        return n && e(t.prototype, n), r && e(t, r), t
    }
}();
! function() {
    function e(e, t) {
        var n = e.width() / 1920;
        e.css({
            height: 1147.5 * n + "px"
        }), t.css({
            transform: "scale(" + n + ") translateY(-55%)"
        })
    }

    function t(e, t) {
        this.create = function() {
            this.x = t.x || 0, this.y = t.y || 0, this.vx = Math.random() * t.dispersion, this.v = 5 * Math.random(), this.g = 1 + 3 * Math.random(), this.size = 5 * Math.random(), this.max = Math.random() * t.destroyOffset, this.vx *= -1
        }, this.draw = function() {
            this.x -= this.vx, this.g += .1, this.y += this.g, e.fillStyle = t.colors[function(e, t) {
                return Math.floor(e + Math.random() * (t + 1 - e))
            }(0, t.colors.length - 1)], e.beginPath(), e.arc(this.x, this.y, this.size, 0, 2 * Math.PI, !1), e.fill(), this.y > this.max && this.create()
        }, this.create()
    }

    function n(e) {
        function n() {
            s.clearRect(0, 0, r.width, r.height), o.forEach(function(e) {
                return e.draw()
            }), i && requestAnimationFrame(n)
        }
        var r = {
                canvas: null,
                width: 100,
                height: 100,
                count: 50,
                interval: 20,
                destroyOffset: 300,
                dispersion: 5
            },
            o = [],
            i = !0;
        (r = Object.assign(r, e || {})).canvas.width = r.width, r.canvas.height = r.height;
        for (var s = r.canvas.getContext("2d"), a = 0; a < r.count; a++) setTimeout(function() {
            o.push(new t(s, e))
        }, a);
        n(), this.stop = function() {
            i = !1
        }, this.start = function() {
            i = !0, n()
        }
    }
    var r = function() {
            function e() {
                var t = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : [];
                _classCallCheck(this, e), this.list = t
            }
            return _createClass(e, [{
                key: "start",
                value: function() {
                    for (var e = this, t = 0, n = function(n) {
                            t += e.list[n].delay, setTimeout(function() {
                                return e.list[n].callback()
                            }, t)
                        }, r = 0; r < this.list.length; r++) n(r)
                }
            }]), e
        }(),
        o = "/bitrix/templates/house/images",
        i = $(".house-container"),
        s = $(".house-container .house"),
        a = [];
    a.push({
            delay: 200,
            callback: function() {
                ! function() {
                    var e = new n({
                        canvas: $(".house-container .soil-particles").get(0),
                        width: 1920,
                        height: 867,
                        count: 2e3,
                        interval: 10,
                        destroyOffset: 600,
                        dispersion: 5,
                        colors: ["#74685D", "#554641", "#695F56", "#8A775B", "#3B2B2D"]
                    });
                    $(".house-container .soil-container .soil").removeClass("house-item-hidden"), new r([{
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
                    var e = new n({
                        canvas: $(".house-container .sand-particles").get(0),
                        width: 1920,
                        height: 867,
                        count: 2e3,
                        interval: 10,
                        destroyOffset: 600,
                        dispersion: 5,
                        colors: ["#CDB298", "#CCA286", "#A38A70", "#928070"]
                    });
                    $(".house-container .sand-container .sand").removeClass("house-item-hidden"), new r([{
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
                        var e = new n({
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
                        $(".house-container .floor").removeClass("house-item-hidden"), $(".house-container .pipe").removeClass("house-item-hidden"), new r([{
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
                    }].forEach(function(t) {
                        for (var n = [], r = function(e) {
                                n.push({
                                    delay: 150,
                                    callback: function() {
                                        $(t.class + " .row-" + e).removeClass("house-item-hidden")
                                    }
                                })
                            }, o = 1; o <= t.rows; o++) r(o);
                        e.push(n)
                    }), e
                })().forEach(function(e) {
                    new r(e).start()
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
                new r(function() {
                    for (var e = [], t = function(t) {
                            e.push({
                                delay: 100,
                                callback: function() {
                                    $(".house-container .plate.plate-" + t).removeClass("house-item-hidden")
                                }
                            })
                        }, n = 14; 1 <= n; n--) t(n);
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
                $(".house-container .facing").removeClass("house-item-hidden"), new r(function() {
                    var e = [];
                    return [".house-container .roof-window-1", ".house-container .roof-window-2", ".house-container .round-window", ".house-container .door", ".house-container .window-1", ".house-container .window-2", ".house-container .panorama-window"].forEach(function(t) {
                        e.push({
                            delay: 150,
                            callback: function() {
                                $(t).removeClass("house-item-hidden")
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
                new r(function() {
                    for (var e = [], t = function(t) {
                            e.push({
                                delay: 100,
                                callback: function() {
                                    $(".house-container .tile-row.row-" + t).removeClass("house-item-hidden")
                                }
                            })
                        }, n = 1; 26 >= n; n++) t(n);
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
        }), e(i, s),
        function(e, t) {
            var n = e.length;
            e.forEach(function(e) {
                var r = new Image;
                r.addEventListener("load", function() {
                    (n -= 1) || "function" != typeof t || t()
                }), r.src = e
            })
        }([o + "/bench-shadow.png", o + "/bench.png", o + "/bush-left-shadow.png", o + "/bush-left.png", o + "/bush-right-shadow.png", o + "/bush-right.png", o + "/facing.png", o + "/foundation-box.png", o + "/foundation-bricks.png", o + "/foundation-floor.png", o + "/foundation.png", o + "/grass.png", o + "/house-stairs.png", o + "/lion-shadow.png", o + "/lion.png", o + "/outside-border.png", o + "/outside-stairs.png", o + "/panaram-window.png", o + "/peak.png", o + "/pipe.png", o + "/roof/planks.png", o + "/roof/plate-1.png", o + "/roof/plate-10.png", o + "/roof/plate-11.png", o + "/roof/plate-12.png", o + "/roof/plate-13.png", o + "/roof/plate-14.png", o + "/roof/plate-2.png", o + "/roof/plate-3.png", o + "/roof/plate-4.png", o + "/roof/plate-5.png", o + "/roof/plate-6.png", o + "/roof/plate-7.png", o + "/roof/plate-8.png", o + "/roof/plate-9.png", o + "/roof/rafters.png", o + "/roof/ridge.png", o + "/roof/roof-shadow.png", o + "/sand-soil.png", o + "/sand.png", o + "/soil-full.png", o + "/soil.png", o + "/tile/row-1.png", o + "/tile/row-10.png", o + "/tile/row-11.png", o + "/tile/row-12.png", o + "/tile/row-13.png", o + "/tile/row-14.png", o + "/tile/row-15.png", o + "/tile/row-16.png", o + "/tile/row-17.png", o + "/tile/row-18.png", o + "/tile/row-19.png", o + "/tile/row-2.png", o + "/tile/row-20.png", o + "/tile/row-21.png", o + "/tile/row-22.png", o + "/tile/row-23.png", o + "/tile/row-24.png", o + "/tile/row-25.png", o + "/tile/row-26.png", o + "/tile/row-3.png", o + "/tile/row-4.png", o + "/tile/row-5.png", o + "/tile/row-6.png", o + "/tile/row-7.png", o + "/tile/row-8.png", o + "/tile/row-9.png", o + "/tree-shadow.png", o + "/urn-shadow.png", o + "/urn.png", o + "/walls/back-side-wall/row-1.png", o + "/walls/back-side-wall/row-10.png", o + "/walls/back-side-wall/row-11.png", o + "/walls/back-side-wall/row-12.png", o + "/walls/back-side-wall/row-13.png", o + "/walls/back-side-wall/row-14.png", o + "/walls/back-side-wall/row-15.png", o + "/walls/back-side-wall/row-16.png", o + "/walls/back-side-wall/row-17.png", o + "/walls/back-side-wall/row-18.png", o + "/walls/back-side-wall/row-19.png", o + "/walls/back-side-wall/row-2.png", o + "/walls/back-side-wall/row-20.png", o + "/walls/back-side-wall/row-21.png", o + "/walls/back-side-wall/row-22.png", o + "/walls/back-side-wall/row-23.png", o + "/walls/back-side-wall/row-24.png", o + "/walls/back-side-wall/row-25.png", o + "/walls/back-side-wall/row-26.png", o + "/walls/back-side-wall/row-3.png", o + "/walls/back-side-wall/row-4.png", o + "/walls/back-side-wall/row-5.png", o + "/walls/back-side-wall/row-6.png", o + "/walls/back-side-wall/row-7.png", o + "/walls/back-side-wall/row-8.png", o + "/walls/back-side-wall/row-9.png", o + "/walls/back-wall/row-1.png", o + "/walls/back-wall/row-10.png", o + "/walls/back-wall/row-11.png", o + "/walls/back-wall/row-12.png", o + "/walls/back-wall/row-13.png", o + "/walls/back-wall/row-14.png", o + "/walls/back-wall/row-2.png", o + "/walls/back-wall/row-3.png", o + "/walls/back-wall/row-4.png", o + "/walls/back-wall/row-5.png", o + "/walls/back-wall/row-6.png", o + "/walls/back-wall/row-7.png", o + "/walls/back-wall/row-8.png", o + "/walls/back-wall/row-9.png", o + "/walls/door-wall/row-1.png", o + "/walls/door-wall/row-10.png", o + "/walls/door-wall/row-11.png", o + "/walls/door-wall/row-12.png", o + "/walls/door-wall/row-13.png", o + "/walls/door-wall/row-2.png", o + "/walls/door-wall/row-3.png", o + "/walls/door-wall/row-4.png", o + "/walls/door-wall/row-5.png", o + "/walls/door-wall/row-6.png", o + "/walls/door-wall/row-7.png", o + "/walls/door-wall/row-8.png", o + "/walls/door-wall/row-9.png", o + "/walls/front-side-wall/row-1.png", o + "/walls/front-side-wall/row-10.png", o + "/walls/front-side-wall/row-11.png", o + "/walls/front-side-wall/row-12.png", o + "/walls/front-side-wall/row-13.png", o + "/walls/front-side-wall/row-14.png", o + "/walls/front-side-wall/row-15.png", o + "/walls/front-side-wall/row-16.png", o + "/walls/front-side-wall/row-17.png", o + "/walls/front-side-wall/row-18.png", o + "/walls/front-side-wall/row-19.png", o + "/walls/front-side-wall/row-2.png", o + "/walls/front-side-wall/row-20.png", o + "/walls/front-side-wall/row-21.png", o + "/walls/front-side-wall/row-22.png", o + "/walls/front-side-wall/row-23.png", o + "/walls/front-side-wall/row-24.png", o + "/walls/front-side-wall/row-25.png", o + "/walls/front-side-wall/row-26.png", o + "/walls/front-side-wall/row-3.png", o + "/walls/front-side-wall/row-4.png", o + "/walls/front-side-wall/row-5.png", o + "/walls/front-side-wall/row-6.png", o + "/walls/front-side-wall/row-7.png", o + "/walls/front-side-wall/row-8.png", o + "/walls/front-side-wall/row-9.png", o + "/walls/front-window-wall/row-1.png", o + "/walls/front-window-wall/row-10.png", o + "/walls/front-window-wall/row-11.png", o + "/walls/front-window-wall/row-12.png", o + "/walls/front-window-wall/row-13.png", o + "/walls/front-window-wall/row-14.png", o + "/walls/front-window-wall/row-2.png", o + "/walls/front-window-wall/row-3.png", o + "/walls/front-window-wall/row-4.png", o + "/walls/front-window-wall/row-5.png", o + "/walls/front-window-wall/row-6.png", o + "/walls/front-window-wall/row-7.png", o + "/walls/front-window-wall/row-8.png", o + "/walls/front-window-wall/row-9.png", o + "/walls-shadow.png", o + "/windows/door.png", o + "/windows/panorama-window.png", o + "/windows/roof-window-1.png", o + "/windows/roof-window-2.png", o + "/windows/round-window.png", o + "/windows/window-1.png", o + "/windows/window-2.png"], function() {
            $(".house-container .loading").addClass("flipOutX"), new r(a).start()
        }), window.addEventListener("resize", function() {
            e(i, s)
        }), $(".house-container .shapes path").on("mouseenter", function() {
            var e = $(this).attr("data-selector");
            i.find(e).addClass("active");
            // подстветка соотв. кнопки при наведении на элемент анимации
            path_href = $(this).attr("data-href");
            $('.header-section-buttons a').each(function(){
            	if (path_href == $(this).attr("href")) {
            		$(this).removeClass("header-section-button-inactive").addClass("header-section-button-active");		
				}
			});
            
        }).on("mouseleave", function() {
            var e = $(this).attr("data-selector");
            i.find(e).removeClass("active");
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
