var Loader = function () {}

Loader.prototype = {
  require: function (scripts) {
    this.loadCount      = 0;
    this.totalRequired  = scripts.length;
    this.scripts        = scripts;

    this.loadScript(this.loadCount);
  },
  loadScript: function (i) {
    this.writeScript(this.scripts[i]);
  },
  loaded: function (evt) {
    this.loadCount++;
    if (this.loadCount < this.totalRequired) {
      this.loadScript(this.loadCount);
    }
  },
  writeScript: function (src) {
    var self = this;
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.async = true;
    s.src = src;
    s.addEventListener('load', function (e) { self.loaded(e); }, false);
    var body = document.getElementsByTagName('body')[0];
    body.appendChild(s);
  }
}

var urls = [
  window.destockShareVar.start.mix.manifest,
  window.destockShareVar.start.mix.vendor,
  window.destockShareVar.start.mix.app
].concat(window.destockShareVar.otherScriptsToLoad)


var myLoader = new Loader();
myLoader.require(urls);
