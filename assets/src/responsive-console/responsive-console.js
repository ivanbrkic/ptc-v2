/**
 * Responsive console.
 */

/* eslint-disable */

import vars from "./responsive-console-export.module.scss";

vars.breakpoints = JSON.parse(
  vars.breakpoints.replace(/^['"]+|\s+|\\|(;\s?})+|['"]$/g, "")
);
vars.setup = JSON.parse(
  vars.setup.replace(/^['"]+|\s+|\\|(;\s?})+|['"]$/g, "")
);
vars.levels = JSON.parse(
  vars.levels.replace(/^['"]+|\s+|\\|(;\s?})+|['"]$/g, "")
);

var rcSetup = [vars.breakpoints, vars.setup, vars.levels];

// @see https://remysharp.com/2010/07/21/throttling-function-calls
function throttle(fn, threshhold, scope) {
  threshhold || (threshhold = 250);
  var last, deferTimer;
  return function() {
    var context = scope || this;

    var now = +new Date();
    var args = arguments;
    if (last && now < last + threshhold) {
      // hold on to it
      clearTimeout(deferTimer);
      deferTimer = setTimeout(function() {
        last = now;
        fn.apply(context, args);
      }, threshhold);
    } else {
      last = now;
      fn.apply(context, args);
    }
  };
}

Object.size = function(obj) {
  var size = 0;
  var key;
  for (key in obj) {
    if (obj.hasOwnProperty(key)) size++;
  }
  return size;
};

var rc = function() {
  var rc = this;
  rc.setMarker = function() {
    var w = Math.max(
      document.documentElement.clientWidth,
      window.innerWidth || 0
    ); // viewport
    var index = -1;
    var sektor;
    var range = [];
    for (var key in rcSetup[0]) {
      if (parseInt(rcSetup[0][key]) > w) {
        sektor = index;
        range[1] = parseInt(rcSetup[0][key]);
        break;
      } else {
        range[0] = parseInt(rcSetup[0][key]);
      }
      index++;
    }
    var sektorCount = Object.size(rcSetup[0]) - 1;
    var cw = $(".responsive-console").innerWidth(); // container width
    $(".responsive-console__marker").css(
      "left",
      (sektor / sektorCount) * 100 +
        (1 / sektorCount) * 100 * ((w - range[0]) / (range[1] - range[0])) +
        "%"
    ); // non linear! :)
  };

  rc.displayHeadingGraph = function(selector) {
    $(".responsive-console__svg-wrapper").addClass("is-active");
    $(".responsive-console").addClass("is-displaying-heading");
    var headingSetup = rcSetup[1];
    var index = 0;
    var graphset = [];
    var maxSize = 0;
    for (var breakpoint in headingSetup) {
      var headings = headingSetup[breakpoint];

      for (var klasa in headings) {
        var heading = headings[klasa];
        if (klasa === selector) {
          var level = rcSetup[2][heading];
          var fluid = false;
          if (typeof heading === "object") {
            fluid = true;
            heading = Object.keys(heading)[0];
          }

          var size = parseInt(rcSetup[2][heading]["font-size"]);
          if (size > maxSize) {
            maxSize = size;
          }
          graphset.push([index, klasa, size, fluid, heading]);
        }
      }
      index++;
    }
    rc.displaySet(graphset, maxSize);
  };
  rc.displaySet = function(graphset, maxSize) {
    var sektorCount = Object.size(rcSetup[0]) - 1;
    var svg = document.getElementsByClassName("responsive-console__svg")[0]; // Get svg element
    var gridGroup = document.createElementNS("http://www.w3.org/2000/svg", "g");
    var circleGroup = document.createElementNS(
      "http://www.w3.org/2000/svg",
      "g"
    );
    var newElement = document.createElementNS(
      "http://www.w3.org/2000/svg",
      "path"
    );
    var pathString = "";
    var first = true;
    var isFluid = false;
    var cx, cy;
    for (var one in graphset) {
      cx = ((graphset[one][0] * 1) / sektorCount) * 100;
      cy = (graphset[one][2] / maxSize) * 100;
      // transform
      cx = cx * 8;
      cy = -cy * 3;
      if (first) {
        pathString = pathString + "M" + cx + " " + cy + " ";
        gridGroup = rc.drawGrid(cx, cy, gridGroup);
        circleGroup = rc.drawCircle(cx, cy, circleGroup);
      } else {
        if (isFluid) {
          pathString = pathString + "L" + cx + " " + cy + " ";
        } else {
          pathString = pathString + "H " + cx + " L" + cx + " " + cy + " ";
        }
        gridGroup = rc.drawGrid(cx, cy, gridGroup);
        circleGroup = rc.drawCircle(cx, cy, circleGroup);
      }

      $(".responsive-console__svg-wrapper").append(
        '<div class="responsive-console__label" style="bottom:' +
          -cy +
          'px"><b>' +
          graphset[one][4] +
          "</b> - " +
          graphset[one][2] +
          "px</div>"
      );

      isFluid = graphset[one][3];
      first = false;
    }

    pathString = pathString + "L800 " + cy + " ";
    newElement.setAttribute("d", pathString);
    newElement.style.stroke = "#000"; // Set stroke colour
    newElement.style.strokeWidth = "1";
    newElement.style.fill = "none";
    svg.appendChild(gridGroup);
    svg.appendChild(newElement);
    svg.appendChild(circleGroup);
  };

  rc.drawCircle = function(cx, cy, g) {
    var newCircle = document.createElementNS(
      "http://www.w3.org/2000/svg",
      "circle"
    );
    newCircle.setAttribute("cx", cx);
    newCircle.setAttribute("cy", cy);
    newCircle.setAttribute("r", 3);
    newCircle.style.fill = "red";
    g.appendChild(newCircle);
    return g;
  };

  rc.drawGrid = function(cx, cy, g) {
    var gridLine = document.createElementNS(
      "http://www.w3.org/2000/svg",
      "path"
    );
    gridLine.setAttribute("stroke-dasharray", "5, 10");
    gridLine.setAttribute(
      "d",
      "M0 " + cy + " L 800 " + cy + " M" + cx + " 0 L" + cx + " -300"
    );

    gridLine.style.strokeWidth = "1";
    gridLine.style.stroke = "rgba(0,0,0,.3)";
    gridLine.style.fill = "none";
    g.appendChild(gridLine);
    return g;
  };

  rc.init = (function() {
    if ($(".responsive-console").length && typeof rcSetup !== "undefined") {
      for (var key in rcSetup[0]) {
        $(".responsive-console").append(
          '<div class="responsive-console__col rc-' +
            key +
            '">' +
            key +
            "</div>"
        );
      }
      rcSetup[0].last = "1920px";
      rc.setMarker();
      // rc.displayHeadingGraph('.t-demo');
      $(window).resize(
        throttle(function() {
          rc.setMarker();
        }, 100)
      );
    }
  })();
};

new rc();
