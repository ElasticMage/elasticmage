
var fs = require('fs');
var vm = require('vm');
var includeInThisContext = function(path) {
    var code = fs.readFileSync(path);
    vm.runInThisContext(code, path);
}.bind(this);

//var assert = require("assert")
var should = require('should');


describe('Mapper', function(){
  describe('#map()', function(){
    includeInThisContext(__dirname+"/../src/mapper.js");

    it('returns the same ctx as being passed', function(){
      var ctx = { doc : {}};
      map(ctx).should.be.exactly(ctx);
    })
  })
})

