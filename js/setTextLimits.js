﻿jQuery.extend({
    setTextLimits: function () {
        var o = /(?:^|\s)textLimit(?:(\d+)(?:-(\d+))?)?(?=\s|$)/i;
        for (var a = document.getElementsByTagName("*"), f = 0, e = a.length; f < e; f++) {
            var d = a[f].className.match(o);
            if (!d || !a[f].lastChild) {
                continue;
            }
            var b = a[f];
            var p = d[1] || 1;
            var n = d[2] || Number.MAX_VALUE;
            var g = b.cloneNode(false);
            var j = g.appendChild(document.createTextNode("\u2026"));
            b.parentNode.replaceChild(g, b);
            var k = (g.scrollHeight - g.clientHeight) + ((g.clientHeight + 3) * p);
            g.parentNode.replaceChild(b, g);
            if (b.scrollHeight > k || b.scrollWidth > n) {
                b.title = (b.textContent || b.innerText || "").replace(/^\s+|\s+$/g, "");
                for (var h = b, c = h.lastChild;; c = h.lastChild) {
                    if (c && c.nodeType == 1) {
                        h = c;
                    } else {
                        if (!c) {
                            if (h == b) {
                                break;
                            }
                            c = h;
                            h = h.parentNode;
                            h.removeChild(c);
                        } else {
                            if (c.nodeType == 3) {
                                c.nodeValue = c.nodeValue.slice(0, -1);
                                if (!c.nodeValue) {
                                    h = c.parentNode;
                                    h.removeChild(c);
                                }
                            } else {
                                h.removeChild(c);
                            }
                        }
                        h.appendChild(j);
                        if (b.scrollHeight <= k && b.scrollWidth <= n) {
                            break;
                        }
                        h.removeChild(j);
                    }
                }
            }
        }
    }
});