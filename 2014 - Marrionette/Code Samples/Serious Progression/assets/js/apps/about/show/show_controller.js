ContactManager.module("AboutApp.Show", function(Show, ContactManager, Backbone, Marionette, $, _){
  var Controller = Marionette.Controller.extend({
    showAbout: function(){
      var view = new Show.Message();
      ContactManager.mainRegion.show(view);
    }
  });

  Show.Controller = new Controller();

  ContactManager.AboutApp.on("stop", function(){
    Show.Controller.destroy();
  });
});
