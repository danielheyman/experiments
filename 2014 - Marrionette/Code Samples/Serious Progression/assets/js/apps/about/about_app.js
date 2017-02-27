ContactManager.module("AboutApp", function(AboutApp, ContactManager, Backbone, Marionette, $, _){
  AboutApp.startWithParent = false;

  AboutApp.onStart = function(){
    console.log("starting about app");
  };

  AboutApp.onStop = function(){
    console.log("stopping about app");
  };
});

ContactManager.module("Routers.AboutApp", function(AboutAppRouter, ContactManager, Backbone, Marionette, $, _){
  AboutAppRouter.Router = Marionette.AppRouter.extend({
    appRoutes: {
      ":lang/about" : "showAbout"
    }
  });

  var API = {
    showAbout: function(lang){
      ContactManager.request("language:change", lang).always(function(){
        ContactManager.startSubApp("AboutApp");
        ContactManager.AboutApp.Show.Controller.showAbout();
        ContactManager.execute("set:active:header", "about");
      });
    }
  };

  this.listenTo(ContactManager, "about:show", function(){
    ContactManager.navigate("about");
    API.showAbout();
  });

  ContactManager.addInitializer(function(){
    new AboutAppRouter.Router({
      controller: API
    });
  });
});
