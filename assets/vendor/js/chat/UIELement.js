function UIElement(element) {
  this.element = element;
  this.element.__uielement = this;
  this.dataElements = [];
  this.data = {};
}

UIElement.prototype.parse = function () {

  for (var key in this.data) {
    var datum = this.data[key];
    if (typeof datum === 'string' && datum !== '{{ ' + key + ' }}') {
      this.doParseString(this.element, key, datum);
    } else if (datum instanceof UIElement || datum instanceof UIElementCollection) {
      this.doParseUIElement(this.element, key, datum);
    }
  }

}

UIElement.prototype.refresh = function () {
  for (var i = 0; i < this.dataElements.length; i++) {
    var dataElement = this.dataElements[i];
    dataElement.textContent = this.data[dataElement.dataset.uidataname]
  }
  for (var key in this.data) {
    var datum = this.data[key];
    if (datum instanceof UIElement) {
      datum.refresh();
    } else if (datum instanceof UIElementCollection) {
      for (d of datum.uiElements) {
        if (d instanceof UIElement) {
          console.log('d', d);
          d.refresh();
        }
      }
    }
  }
}
// {{ title }}

UIElement.prototype.doParseString = function (node, from, to) {
  return this.replaceMark(node, from, to, function (match) {
    var tag = document.createElement('span');
    tag.className = 'uielement--data';
    tag.dataset.uidataname = from;
    tag.textContent = match;
    return tag;
  });
}

UIElement.prototype.doParseUIElement = function (node, from, to) {
  return this.replaceMark(node, from, to, function (match) {
    return match.element;
  });
}

UIElement.prototype.replaceMark = function (node, from, to, callback) {
  var excludeElements = ['script', 'style', 'iframe', 'canvas'];

  var child = node.firstChild;

  while (child) {
    switch (child.nodeType) {
      case 1:
        if (excludeElements.indexOf(child.tagName.toLowerCase()) > -1)
          break;
        if (typeof child.__uielement == 'undefined') {
          this.replaceMark(child, from, to, callback);
          break;
        }
      case 3:
        var bk = 0;
        if (typeof child.data !== 'undefined') {
          child.data.replace(new RegExp('\{\{ ' + from + ' \}\}', 'g'), function (all) {
            var args = [].slice.call(arguments),
              offset = args[args.length - 2],
              newTextNode = child.splitText(offset + bk), tag;
            bk -= child.data.length + all.length;

            newTextNode.data = newTextNode.data.substr(all.length);
            console.log('to', to);
            var tag = callback(to);
            console.log('tag', tag);
            child.parentNode.insertBefore(tag, newTextNode);
            child = newTextNode;
          });
        }
        break;
    }

    child = child.nextSibling;
  }



  for (var i = 0; i < this.element.childNodes.length; i++) {
    var node = this.element.childNodes[i];
    if (typeof node.classList !== 'undefined' && node.classList.contains('uielement--data')) {
      if (this.dataElements.indexOf(node) === -1) {
        this.dataElements.push(node);
      }
    }
//                else {
//                  element.dataset.uielement.refresh();
//                }
  }
  return node;
}

UIElement.prototype.set = function (key, value) {

  var existed = true;

  if (typeof this.data[key] === 'undefined') {
    existed = false;
  }

  this.data[key] = value;

  if (!existed) {
    this.parse();
  }

  this.refresh();
}
UIElement.prototype.get = function (key) {
  return this.data[key];
}

function UIElementCollection(uiElements) {
  if (typeof uiElements !== 'undefined' && uiElements instanceof Array) {
    this.uiElements = uiElements;
  } else {
    this.uiElements = [];
  }
  this.element = document.createElement('div');
  this.element.className = 'uielement--collection';
}

UIElementCollection.prototype.add = function (uiElement) {
  this.uiElements.push(uiElement);
  this.element.append(uiElement.element);
}

UIElementCollection.prototype.remove = function (uiElement) {
  this.uiElements.splice(this.uiElements.indexOf(uiElement), 1);
  this.element.removeChild(uiElement.element);
}